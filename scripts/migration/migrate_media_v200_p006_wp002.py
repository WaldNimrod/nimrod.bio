#!/usr/bin/env python3
"""NB-S002-P006-WP002 media migration and post URL rewrite."""
from __future__ import annotations

import argparse
import base64
import json
import random
import re
import socket
import subprocess
import time
import urllib.error
import urllib.parse
import urllib.request
from dataclasses import dataclass
from datetime import datetime, timezone
from pathlib import Path
from typing import Any

from _lib import (
    dev_auth,
    dev_rest_base,
    dev_site_url,
    load_envs,
    prod_auth,
    prod_rest_base,
    rest_request,
)

OLD_REST_BASE = "https://www.nimrod.bio/wp-json/wp/v2"
OLD_SITE_BASE = "https://www.nimrod.bio"

SCRIPT_DIR = Path(__file__).resolve().parent
STATE_DIR = SCRIPT_DIR / "state"
LOGS_DIR = SCRIPT_DIR / "logs"
PROGRESS_PATH = STATE_DIR / "migrate_media_progress.json"
URL_MAP_PATH = STATE_DIR / "url_map.json"
BACKUP_PATH = STATE_DIR / "pre_rewrite_posts_backup.json"
REPORT_PATH = STATE_DIR / "migrate_media_report.json"

SFA_PATTERNS = (
    re.compile(r"/sfa/", re.I),
    re.compile(r"sfagent", re.I),
    re.compile(r"small.?farms.?agents", re.I),
    re.compile(r"smartfieldagent", re.I),
)
IMG_SRC_RE = re.compile(r"""<img[^>]+src=["']([^"']+)["']""", re.I)

# Guard against indefinite TCP connect hangs during bulk migration.
socket.setdefaulttimeout(90)


def now_iso() -> str:
    return datetime.now(timezone.utc).isoformat()


def human_mb(num_bytes: int) -> float:
    return round(num_bytes / (1024 * 1024), 2)


def contains_sfa(value: str) -> bool:
    return any(pattern.search(value or "") for pattern in SFA_PATTERNS)


def upload_rel_path(url: str) -> str | None:
    marker = "/wp-content/uploads/"
    idx = url.find(marker)
    if idx == -1:
        return None
    return urllib.parse.unquote(url[idx + len(marker) :].split("?", 1)[0])


def make_aliases(old_source_url: str, dev_http_base: str) -> list[str]:
    rel = upload_rel_path(old_source_url)
    if not rel:
        return [old_source_url]
    aliases = {
        old_source_url,
        f"https://www.nimrod.bio/wp-content/uploads/{rel}",
        f"http://www.nimrod.bio/wp-content/uploads/{rel}",
        f"https://nimrod.bio/wp-content/uploads/{rel}",
        f"http://nimrod.bio/wp-content/uploads/{rel}",
        f"//www.nimrod.bio/wp-content/uploads/{rel}",
        f"{dev_http_base}/wp-content/uploads/{rel}",
        f"{dev_http_base.replace('http://', 'https://')}/wp-content/uploads/{rel}",
    }
    return sorted(aliases)


def old_request_json(url: str) -> tuple[Any, dict[str, str]]:
    req = urllib.request.Request(
        url,
        headers={
            "Accept": "application/json",
            "User-Agent": "nimrod-bio-media-migration/1.0",
        },
    )
    with urllib.request.urlopen(req, timeout=120) as resp:
        body = resp.read().decode("utf-8")
        headers = {k.lower(): v for k, v in resp.headers.items()}
    return json.loads(body), headers


def old_request_bytes(url: str) -> bytes:
    parts = urllib.parse.urlsplit(url)
    safe_path = urllib.parse.quote(parts.path, safe="/%")
    safe_url = urllib.parse.urlunsplit((parts.scheme, parts.netloc, safe_path, parts.query, parts.fragment))
    cmd = [
        "curl",
        "--silent",
        "--show-error",
        "--location",
        "--fail-with-body",
        "--connect-timeout",
        "15",
        "--max-time",
        "120",
        "--user-agent",
        "nimrod-bio-media-migration/1.0",
        safe_url,
    ]
    proc = subprocess.run(cmd, capture_output=True, check=False)
    if proc.returncode != 0:
        stderr = proc.stderr.decode("utf-8", "replace")
        match = re.search(r"returned error: (\\d{3})", stderr)
        if match:
            raise RuntimeError(f"HTTP {match.group(1)}")
        raise RuntimeError(stderr[:300] if stderr else f"curl exit {proc.returncode}")
    return proc.stdout


@dataclass
class Ctx:
    dev_base: str
    dev_site_http: str
    dev_user: str
    dev_password: str
    log_path: Path
    sleep_ms: int
    dry_run: bool


class Logger:
    def __init__(self, log_path: Path) -> None:
        self.log_path = log_path
        self.log_path.parent.mkdir(parents=True, exist_ok=True)

    def write(self, level: str, msg: str) -> None:
        line = f"[{datetime.now().strftime('%Y-%m-%d %H:%M:%S')}] [{level}] {msg}"
        print(line)
        with self.log_path.open("a", encoding="utf-8") as fh:
            fh.write(line + "\n")


def load_progress() -> dict[str, Any]:
    if not PROGRESS_PATH.exists():
        return {
            "version": "1.0.0",
            "started_at": now_iso(),
            "updated_at": now_iso(),
            "items": {},
        }
    return json.loads(PROGRESS_PATH.read_text(encoding="utf-8"))


def save_progress(progress: dict[str, Any]) -> None:
    STATE_DIR.mkdir(parents=True, exist_ok=True)
    progress["updated_at"] = now_iso()
    PROGRESS_PATH.write_text(json.dumps(progress, ensure_ascii=False, indent=2), encoding="utf-8")


def list_old_media() -> tuple[list[dict[str, Any]], int | None]:
    all_items: list[dict[str, Any]] = []
    prod_base = prod_rest_base()
    prod_user, prod_password = prod_auth()
    page = 1
    old_total: int | None = None
    while True:
        url = (
            f"{prod_base}/media?per_page=100&page={page}&context=edit"
            "&_fields=id,source_url,slug,mime_type,title,alt_text,caption,post,date"
        )
        try:
            data = rest_request("GET", url, prod_user, prod_password, retries=2)
        except RuntimeError as exc:
            if "HTTP 400" in str(exc) and page > 1:
                break
            raise
        if not data:
            break
        all_items.extend(data)
        if old_total is None:
            old_total = len(data)
        if len(data) < 100:
            break
        page += 1
    old_total = len(all_items)
    return all_items, old_total


def dev_media_exists(ctx: Ctx, media_id: int) -> bool:
    try:
        rest_request("GET", f"{ctx.dev_base}/media/{media_id}", ctx.dev_user, ctx.dev_password, retries=1)
        return True
    except Exception:  # noqa: BLE001
        return False


def upload_media_binary(ctx: Ctx, item: dict[str, Any], blob: bytes) -> dict[str, Any]:
    filename = urllib.parse.unquote(item["source_url"].split("/")[-1].split("?")[0]) or f"media-{item['id']}"
    mime = item.get("mime_type") or "application/octet-stream"
    # urllib request headers are latin-1 encoded; keep ASCII in filename while passing RFC5987 UTF-8 name.
    filename_ascii = filename.encode("ascii", "ignore").decode("ascii").strip() or f"media-{item['id']}"
    filename_utf8 = urllib.parse.quote(filename, safe="")
    content_disposition = (
        f'attachment; filename="{filename_ascii}"; '
        f"filename*=UTF-8''{filename_utf8}"
    )
    cred = base64.b64encode(f"{ctx.dev_user}:{ctx.dev_password}".encode()).decode()
    req = urllib.request.Request(
        f"{ctx.dev_base}/media",
        data=blob,
        method="POST",
        headers={
            "Authorization": f"Basic {cred}",
            "Content-Type": mime,
            "Content-Disposition": content_disposition,
            "User-Agent": "nimrod-bio-media-migration/1.0",
            "Accept": "application/json",
        },
    )
    with urllib.request.urlopen(req, timeout=180) as resp:
        body = resp.read().decode("utf-8")
    uploaded = json.loads(body)

    payload: dict[str, Any] = {}
    if item.get("alt_text"):
        payload["alt_text"] = item["alt_text"]
    title = (item.get("title") or {}).get("rendered") if isinstance(item.get("title"), dict) else ""
    if title:
        payload["title"] = title
    caption = (item.get("caption") or {}).get("rendered") if isinstance(item.get("caption"), dict) else ""
    if caption:
        payload["caption"] = caption
    if payload:
        try:
            rest_request(
                "POST",
                f"{ctx.dev_base}/media/{uploaded['id']}",
                ctx.dev_user,
                ctx.dev_password,
                payload,
                retries=2,
            )
        except Exception:
            pass
    return uploaded


def list_dev_posts(ctx: Ctx) -> list[dict[str, Any]]:
    posts = rest_request(
        "GET",
        f"{ctx.dev_base}/posts?per_page=100&status=publish&_fields=id,slug,featured_media,meta",
        ctx.dev_user,
        ctx.dev_password,
    )
    migrated = [p for p in posts if (p.get("meta") or {}).get("_nb_seed") == "v200-migrated"]
    return migrated


def fetch_post_edit(ctx: Ctx, post_id: int) -> dict[str, Any]:
    return rest_request(
        "GET",
        f"{ctx.dev_base}/posts/{post_id}?context=edit&_fields=id,slug,content,featured_media,meta,title",
        ctx.dev_user,
        ctx.dev_password,
    )


def rewrite_content(content: str, replacement_pairs: list[tuple[str, str]]) -> tuple[str, int]:
    rewritten = content
    replaced = 0
    for old, new in replacement_pairs:
        if old in rewritten:
            count = rewritten.count(old)
            rewritten = rewritten.replace(old, new)
            replaced += count
    return rewritten, replaced


def list_old_posts_summary() -> list[dict[str, Any]]:
    prod_base = prod_rest_base()
    prod_user, prod_password = prod_auth()
    page = 1
    rows: list[dict[str, Any]] = []
    while True:
        url = f"{prod_base}/posts?per_page=100&page={page}&context=edit&_fields=id,slug,featured_media,status"
        try:
            data = rest_request("GET", url, prod_user, prod_password, retries=2)
        except RuntimeError as exc:
            if "HTTP 400" in str(exc) and page > 1:
                break
            raise
        if not data:
            break
        rows.extend(data)
        if len(data) < 100:
            break
        page += 1
    return rows


def http_status(url: str) -> int:
    parts = urllib.parse.urlsplit(url)
    safe_path = urllib.parse.quote(parts.path, safe="/%")
    safe_url = urllib.parse.urlunsplit((parts.scheme, parts.netloc, safe_path, parts.query, parts.fragment))
    req = urllib.request.Request(safe_url, method="HEAD", headers={"User-Agent": "nimrod-bio-media-migration/1.0"})
    try:
        with urllib.request.urlopen(req, timeout=45) as resp:
            return int(resp.status)
    except urllib.error.HTTPError as exc:
        if int(exc.code) == 405:
            try:
                with urllib.request.urlopen(
                    urllib.request.Request(safe_url, method="GET", headers={"User-Agent": "nimrod-bio-media-migration/1.0"}),
                    timeout=45,
                ) as resp:
                    return int(resp.status)
            except urllib.error.HTTPError as exc2:
                return int(exc2.code)
            except Exception:
                return 0
        return int(exc.code)
    except Exception:
        return 0


def list_dev_media_count(ctx: Ctx) -> int:
    page = 1
    count = 0
    while True:
        try:
            rows = rest_request(
                "GET",
                f"{ctx.dev_base}/media?per_page=100&page={page}&context=edit&_fields=id,source_url",
                ctx.dev_user,
                ctx.dev_password,
            )
        except RuntimeError as exc:
            if "HTTP 400" in str(exc) and page > 1:
                break
            raise
        if not rows:
            break
        count += len(rows)
        if len(rows) < 100:
            break
        page += 1
    return count


def run(args: argparse.Namespace) -> int:
    load_envs()
    STATE_DIR.mkdir(parents=True, exist_ok=True)
    LOGS_DIR.mkdir(parents=True, exist_ok=True)
    log_path = LOGS_DIR / f"migrate_media_{datetime.now().strftime('%Y%m%d_%H%M%S')}.log"
    log = Logger(log_path)

    ctx = Ctx(
        dev_base=dev_rest_base(),
        dev_site_http=dev_site_url().rstrip("/"),
        dev_user=dev_auth()[0],
        dev_password=dev_auth()[1],
        log_path=log_path,
        sleep_ms=max(0, int(args.sleep_ms)),
        dry_run=bool(args.dry_run),
    )
    progress = load_progress()
    items_state: dict[str, Any] = progress.setdefault("items", {})

    old_media, old_total = list_old_media()
    log.write("INFO", f"old_media_count={len(old_media)} old_total_header={old_total}")
    # Undo earlier accidental SFA uploads from stale state.
    sfa_purged = 0
    for old_id, state in list(items_state.items()):
        if state.get("status") != "uploaded":
            continue
        old_url = str(state.get("old_url") or "")
        if not contains_sfa(old_url):
            continue
        media_id = state.get("new_id")
        if media_id:
            try:
                rest_request("DELETE", f"{ctx.dev_base}/media/{int(media_id)}?force=true", ctx.dev_user, ctx.dev_password)
            except Exception:
                pass
        items_state[old_id] = {
            "status": "skipped_sfa_deleted",
            "old_url": old_url,
            "updated_at": now_iso(),
        }
        sfa_purged += 1
    if sfa_purged:
        save_progress(progress)
        log.write("INFO", f"sfa_purged_from_previous_runs={sfa_purged}")


    dev_pre_count = list_dev_media_count(ctx)
    log.write("INFO", f"dev_media_count_pre={dev_pre_count}")
    log.write("INFO", f"env_loaded=.env.upress.dev has_wp_rest_user={bool(ctx.dev_user)} has_app_password={bool(ctx.dev_password)}")

    upload_success = 0
    upload_failed = 0
    upload_skipped_existing = 0
    source_404_count = 0
    sfa_skipped_count = 0
    downloaded_bytes = 0
    uploaded_bytes = 0
    consecutive_download_failures = 0
    consecutive_upload_5xx = 0
    old_to_new_id: dict[str, int] = {}
    replacement_map: dict[str, str] = {}

    for idx, item in enumerate(old_media, start=1):
        old_id = str(item["id"])
        old_url = str(item.get("source_url") or "")
        state = items_state.get(old_id, {})
        if contains_sfa(old_url):
            items_state[old_id] = {
                "status": "skipped_sfa",
                "old_url": old_url,
                "updated_at": now_iso(),
            }
            sfa_skipped_count += 1
            continue

        if state.get("status") == "uploaded" and state.get("new_id"):
            new_id = int(state["new_id"])
            upload_skipped_existing += 1
            old_to_new_id[old_id] = new_id
            new_url = str(state.get("new_url") or "")
            if old_url and new_url:
                for alias in make_aliases(old_url, ctx.dev_site_http):
                    replacement_map[alias] = new_url
            continue

        if ctx.dry_run:
            log.write("INFO", f"[DRY] would upload old_id={old_id} url={old_url}")
            continue

        try:
            blob = old_request_bytes(old_url)
            downloaded_bytes += len(blob)
            consecutive_download_failures = 0
        except RuntimeError as exc:
            code_match = re.search(r"HTTP\\s+(\\d{3})", str(exc))
            code = int(code_match.group(1)) if code_match else 0
            status = "source_404" if code == 404 else "source_http_error"
            items_state[old_id] = {
                "status": status,
                "old_url": old_url,
                "error": f"HTTP {code}" if code else str(exc)[:300],
                "updated_at": now_iso(),
            }
            if code == 404:
                source_404_count += 1
            upload_failed += 1
            consecutive_download_failures += 1
            log.write("WARN", f"download failed old_id={old_id} code={code or 'n/a'} url={old_url}")
            save_progress(progress)
            if consecutive_download_failures > 10:
                log.write("ERROR", "STOP: >10 consecutive source download failures")
                return 2
            continue
        except Exception as exc:  # noqa: BLE001
            items_state[old_id] = {
                "status": "source_error",
                "old_url": old_url,
                "error": str(exc)[:300],
                "updated_at": now_iso(),
            }
            upload_failed += 1
            consecutive_download_failures += 1
            log.write("WARN", f"download failed old_id={old_id} error={exc}")
            save_progress(progress)
            if consecutive_download_failures > 10:
                log.write("ERROR", "STOP: >10 consecutive source download failures")
                return 2
            continue

        try:
            last_exc: Exception | None = None
            uploaded: dict[str, Any] | None = None
            for _attempt in range(3):
                try:
                    uploaded = upload_media_binary(ctx, item, blob)
                    break
                except Exception as exc:  # noqa: BLE001
                    last_exc = exc
                    time.sleep(1.5 * (_attempt + 1))
            if uploaded is None:
                raise RuntimeError(str(last_exc) if last_exc else "upload failed without response")
            consecutive_upload_5xx = 0
            new_id = int(uploaded["id"])
            new_url = str(uploaded.get("source_url") or "")
            old_to_new_id[old_id] = new_id
            uploaded_bytes += len(blob)
            items_state[old_id] = {
                "status": "uploaded",
                "old_url": old_url,
                "new_id": new_id,
                "new_url": new_url,
                "bytes": len(blob),
                "updated_at": now_iso(),
            }
            upload_success += 1
            for alias in make_aliases(old_url, ctx.dev_site_http):
                replacement_map[alias] = new_url
            if idx % 20 == 0:
                log.write("INFO", f"progress uploaded={upload_success} failed={upload_failed} processed={idx}/{len(old_media)}")
        except urllib.error.HTTPError as exc:
            code = int(exc.code)
            items_state[old_id] = {
                "status": "upload_http_error",
                "old_url": old_url,
                "error": f"HTTP {code}",
                "updated_at": now_iso(),
            }
            upload_failed += 1
            log.write("WARN", f"upload failed old_id={old_id} code={code}")
            if code >= 500:
                consecutive_upload_5xx += 1
            else:
                consecutive_upload_5xx = 0
            save_progress(progress)
            if consecutive_upload_5xx > 3:
                log.write("ERROR", "STOP: >3 consecutive upload 5xx failures")
                return 3
            continue
        except Exception as exc:  # noqa: BLE001
            items_state[old_id] = {
                "status": "upload_error",
                "old_url": old_url,
                "error": str(exc)[:300],
                "updated_at": now_iso(),
            }
            upload_failed += 1
            log.write("WARN", f"upload failed old_id={old_id} error={exc}")
            save_progress(progress)
            continue
        finally:
            save_progress(progress)
            if ctx.sleep_ms:
                time.sleep(ctx.sleep_ms / 1000.0)

    # Rebuild map from all persisted uploaded entries to keep idempotent reruns stable.
    for old_id, state in items_state.items():
        if state.get("status") != "uploaded":
            continue
        old_url = str(state.get("old_url") or "")
        new_url = str(state.get("new_url") or "")
        if not old_url or not new_url:
            continue
        for alias in make_aliases(old_url, ctx.dev_site_http):
            replacement_map[alias] = new_url

    url_map_payload = {
        "generated_at": now_iso(),
        "dev_site_http": ctx.dev_site_http,
        "replacement_count": len(replacement_map),
        "old_to_new_media_id": old_to_new_id,
        "map": replacement_map,
    }
    URL_MAP_PATH.write_text(json.dumps(url_map_payload, ensure_ascii=False, indent=2), encoding="utf-8")
    log.write("INFO", f"wrote_url_map path={URL_MAP_PATH} replacements={len(replacement_map)}")

    # Rewrite migrated posts only.
    migrated_posts = list_dev_posts(ctx)
    migrated_posts.sort(key=lambda p: int(p["id"]))
    log.write("INFO", f"migrated_posts_found={len(migrated_posts)}")

    post_backup: list[dict[str, Any]] = []
    rewritten_posts = 0
    rewritten_urls_count = 0

    replacement_pairs = sorted(replacement_map.items(), key=lambda kv: len(kv[0]), reverse=True)
    for post in migrated_posts:
        post_id = int(post["id"])
        try:
            post_full = fetch_post_edit(ctx, post_id)
        except Exception as exc:  # noqa: BLE001
            log.write("WARN", f"post fetch failed post_id={post_id} error={exc}")
            continue
        meta = dict(post_full.get("meta") or {})
        already_rewritten = str(meta.get("_nb_url_rewritten", "")).lower() in ("1", "true", "yes")
        content_raw = str((post_full.get("content") or {}).get("raw") or "")
        post_backup.append(
            {
                "id": post_id,
                "slug": post_full.get("slug"),
                "meta": meta,
                "content_raw": content_raw,
            }
        )
        if already_rewritten:
            continue
        rewritten, replaced = rewrite_content(content_raw, replacement_pairs)
        if replaced == 0 or rewritten == content_raw:
            continue
        payload = {"content": rewritten, "meta": {"_nb_url_rewritten": "1"}}
        try:
            rest_request("POST", f"{ctx.dev_base}/posts/{post_id}", ctx.dev_user, ctx.dev_password, payload)
        except Exception:
            # Fallback when post meta key is not writable.
            rest_request("POST", f"{ctx.dev_base}/posts/{post_id}", ctx.dev_user, ctx.dev_password, {"content": rewritten})
        rewritten_posts += 1
        rewritten_urls_count += replaced

    BACKUP_PATH.write_text(json.dumps(post_backup, ensure_ascii=False, indent=2), encoding="utf-8")
    log.write("INFO", f"wrote_backup path={BACKUP_PATH} posts={len(post_backup)}")

    # Featured media relinking.
    old_posts = list_old_posts_summary()
    old_featured_by_slug = {
        str(row.get("slug")): int(row.get("featured_media") or 0)
        for row in old_posts
        if str(row.get("slug"))
    }
    relink_attempted = 0
    relink_success = 0
    for post in migrated_posts:
        slug = str(post.get("slug") or "")
        old_featured = old_featured_by_slug.get(slug, 0)
        if old_featured <= 0:
            continue
        relink_attempted += 1
        if int(post.get("featured_media") or 0) != 0:
            relink_success += 1
            continue
        new_media_id = old_to_new_id.get(str(old_featured))
        if not new_media_id:
            continue
        try:
            rest_request(
                "POST",
                f"{ctx.dev_base}/posts/{int(post['id'])}",
                ctx.dev_user,
                ctx.dev_password,
                {"featured_media": int(new_media_id)},
            )
            relink_success += 1
        except Exception:
            pass

    # Acceptance tests
    dev_post_count = list_dev_media_count(ctx)
    img_urls: list[str] = []
    refreshed_posts = list_dev_posts(ctx)
    for post in refreshed_posts:
        try:
            post_full = fetch_post_edit(ctx, int(post["id"]))
        except Exception as exc:  # noqa: BLE001
            log.write("WARN", f"acceptance fetch failed post_id={post.get('id')} error={exc}")
            continue
        content = str((post_full.get("content") or {}).get("raw") or "")
        img_urls.extend(IMG_SRC_RE.findall(content))
    unique_img_urls = sorted(set(img_urls))
    sample_size = min(30, len(unique_img_urls))
    sampled = random.Random(20260526).sample(unique_img_urls, sample_size) if sample_size else []
    sample_results = [{"url": url, "status": http_status(url)} for url in sampled]
    sample_200 = sum(1 for row in sample_results if row["status"] == 200)
    sample_404 = sum(1 for row in sample_results if row["status"] == 404)

    featured_expected = 0
    featured_covered = 0
    for post in refreshed_posts:
        slug = str(post.get("slug") or "")
        if old_featured_by_slug.get(slug, 0) > 0:
            featured_expected += 1
            if int(post.get("featured_media") or 0) != 0:
                featured_covered += 1
    featured_coverage_pct = round((featured_covered / featured_expected) * 100, 2) if featured_expected else 100.0

    sitemap_url = f"{ctx.dev_site_http}/sitemap_index.xml"
    try:
        index_xml = old_request_bytes(sitemap_url).decode("utf-8", "replace")
    except Exception:
        index_xml = ""
    has_media_sitemap = "media-sitemap" in index_xml

    report = {
        "generated_at": now_iso(),
        "log_path": str(log_path),
        "progress_path": str(PROGRESS_PATH),
        "url_map_path": str(URL_MAP_PATH),
        "backup_path": str(BACKUP_PATH),
        "preflight": {
            "old_media_total_header": old_total,
            "dev_media_pre_count": dev_pre_count,
        },
        "migration": {
            "old_media_found": len(old_media),
            "uploaded_success": upload_success,
            "failed": upload_failed,
            "skipped_existing": upload_skipped_existing,
            "source_404_count": source_404_count,
            "sfa_skipped_count": sfa_skipped_count,
            "downloaded_bytes": downloaded_bytes,
            "uploaded_bytes": uploaded_bytes,
            "downloaded_mb": human_mb(downloaded_bytes),
            "uploaded_mb": human_mb(uploaded_bytes),
        },
        "rewrite": {
            "migrated_posts_found": len(migrated_posts),
            "rewritten_posts": rewritten_posts,
            "rewritten_url_replacements": rewritten_urls_count,
        },
        "featured_relink": {
            "attempted": relink_attempted,
            "success": relink_success,
        },
        "acceptance": {
            "AT-M1_dev_media_count": dev_post_count,
            "AT-M2_sample_size": sample_size,
            "AT-M2_http_200": sample_200,
            "AT-M2_http_404": sample_404,
            "AT-M3_featured_expected": featured_expected,
            "AT-M3_featured_covered": featured_covered,
            "AT-M3_featured_coverage_pct": featured_coverage_pct,
            "AT-M4_has_media_sitemap": has_media_sitemap,
            "AT-M5_uploaded_mb": human_mb(uploaded_bytes),
            "AT-M6_sfa_skipped_count": sfa_skipped_count,
        },
        "sample_results": sample_results,
    }
    REPORT_PATH.write_text(json.dumps(report, ensure_ascii=False, indent=2), encoding="utf-8")
    log.write("INFO", f"wrote_report path={REPORT_PATH}")
    log.write("INFO", f"AT-M2 sample_size={sample_size} http_200={sample_200} http_404={sample_404}")
    log.write("INFO", f"AT-M3 coverage={featured_coverage_pct}% ({featured_covered}/{featured_expected})")
    log.write("INFO", f"AT-M4 media_sitemap_present={has_media_sitemap}")
    log.write("INFO", f"AT-M6 sfa_skipped_count={sfa_skipped_count}")
    return 0


def parse_args() -> argparse.Namespace:
    parser = argparse.ArgumentParser(description="Migrate old prod media and rewrite migrated posts.")
    parser.add_argument("--sleep-ms", type=int, default=150, help="Sleep between uploads (default: 150)")
    parser.add_argument("--dry-run", action="store_true", help="Do not upload/update, only log intent")
    return parser.parse_args()


if __name__ == "__main__":
    raise SystemExit(run(parse_args()))
