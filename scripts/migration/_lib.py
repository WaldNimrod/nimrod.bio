"""Shared helpers for P004 content migration scripts."""
from __future__ import annotations

import base64
import json
import os
import re
import time
import urllib.error
import urllib.request
from pathlib import Path
from typing import Any

REPO = Path(__file__).resolve().parent.parent.parent
DECISIONS_PATH = REPO / "docs" / "url_migration_decisions_2026-05-25.json"
CACHE_DIR = REPO / ".migration-cache"
RAW_DIR = CACHE_DIR / "raw"
UPLOADS_DIR = CACHE_DIR / "uploads"
ID_MAPPING_PATH = CACHE_DIR / "id_mapping.json"
REFERENCED_UPLOADS_PATH = CACHE_DIR / "referenced_uploads.json"

SKIP_IMPORT_PAGE_IDS = {"2516", "90896"}  # heritage hardcoded; /blog/ archive collision
NB_SEED_MIGRATED = "v200-migrated"
NB_SEED_LEGACY = "v200"

WORLDS = ("soil", "know", "code")
FLOW_STYLES = ("lead", "wide", "tall", "typo", "quote", "feature", "brief")

PROD_UPLOAD_PATTERNS = (
    re.compile(r"https?://(?:www\.)?nimrod\.bio/wp-content/uploads/[^\s\"'<>]+", re.I),
    re.compile(r"//(?:www\.)?nimrod\.bio/wp-content/uploads/[^\s\"'<>]+", re.I),
)


def load_env_file(path: Path) -> None:
    if not path.exists():
        return
    for raw in path.read_text(encoding="utf-8").splitlines():
        line = raw.strip()
        if not line or line.startswith("#") or "=" not in line:
            continue
        key, value = line.split("=", 1)
        value = value.strip().strip("'\"")
        os.environ[key.strip()] = value


def load_envs() -> None:
    load_env_file(REPO / ".env.upress")
    load_env_file(REPO / ".env.upress.dev")


def prod_rest_base() -> str:
    base = os.environ.get("PROD_REST_BASE", "https://www.nimrod.bio/wp-json").rstrip("/")
    if not base.endswith("/wp/v2"):
        base = f"{base}/wp/v2" if "/wp/v2" not in base else base
    return base


def dev_rest_base() -> str:
    base = os.environ["WP_REST_BASE_URL"].rstrip("/")
    if not base.endswith("/wp/v2"):
        base = f"{base}/wp/v2"
    return base


def dev_site_url() -> str:
    return os.environ.get("UPRESS_DEV_URL", os.environ.get("UPRESS_DEV_URL_HTTP", "")).rstrip("/")


def prod_auth() -> tuple[str, str]:
    return os.environ["PROD_REST_USER"], os.environ["PROD_REST_APP_PASSWORD"]


def dev_auth() -> tuple[str, str]:
    return os.environ["WP_REST_USER"], os.environ["WP_REST_APP_PASSWORD"]


def rest_request(
    method: str,
    url: str,
    user: str,
    password: str,
    payload: dict | None = None,
    *,
    sleep_ms: int = 0,
) -> Any:
    if sleep_ms:
        time.sleep(sleep_ms / 1000.0)
    data = None
    headers = {
        "Accept": "application/json",
        "User-Agent": "nimrod-bio-migration/1.0",
    }
    if payload is not None:
        data = json.dumps(payload, ensure_ascii=False).encode("utf-8")
        headers["Content-Type"] = "application/json; charset=utf-8"
    req = urllib.request.Request(url, data=data, headers=headers, method=method)
    cred = base64.b64encode(f"{user}:{password}".encode()).decode()
    req.add_header("Authorization", f"Basic {cred}")
    try:
        with urllib.request.urlopen(req, timeout=60) as resp:
            body = resp.read().decode("utf-8")
            return json.loads(body) if body else {}
    except urllib.error.HTTPError as exc:
        detail = exc.read().decode("utf-8", "replace")
        raise RuntimeError(f"{method} {url} -> HTTP {exc.code}: {detail[:400]}") from exc


def load_decisions() -> dict:
    return json.loads(DECISIONS_PATH.read_text(encoding="utf-8"))


def positive_decisions(data: dict | None = None) -> list[dict]:
    data = data or load_decisions()
    return [
        row
        for row in data["decisions"]
        if row.get("decision") in ("redirect", "keep")
    ]


def importable_posts(data: dict | None = None) -> list[dict]:
    return [row for row in positive_decisions(data) if row.get("type") == "post"]


def slug_from_new_url(new_url: str | None, fallback: str) -> str:
    if not new_url:
        return fallback
    path = new_url.strip().strip("/")
    if path.startswith("blog/"):
        return path[5:].strip("/")
    parts = [p for p in path.split("/") if p]
    return parts[-1] if parts else fallback


def strip_html(text: str) -> str:
    text = re.sub(r"<[^>]+>", " ", text or "")
    text = re.sub(r"\s+", " ", text)
    return text.strip()


def excerpt_from_record(record: dict, limit: int = 200) -> str:
    for key in ("excerpt", "content"):
        rendered = record.get(key, {})
        if isinstance(rendered, dict):
            raw = rendered.get("rendered", "")
        else:
            raw = str(rendered or "")
        plain = strip_html(raw)
        if plain:
            return plain[:limit]
    return ""


def read_cached_raw(entity_id: str) -> dict:
    path = RAW_DIR / f"{entity_id}.json"
    if not path.exists():
        raise FileNotFoundError(f"Missing cache file: {path}")
    return json.loads(path.read_text(encoding="utf-8"))


def taxonomy_hints(record: dict) -> dict[str, list[str]]:
    hints: dict[str, list[str]] = {"categories": [], "tags": []}
    embedded = record.get("_embedded", {})
    for term in embedded.get("wp:term", []) or []:
        if not term:
            continue
        for item in term:
            tax = item.get("taxonomy")
            slug = item.get("slug")
            if tax == "category" and slug:
                hints["categories"].append(slug)
            elif tax == "post_tag" and slug:
                hints["tags"].append(slug)
    return hints


def extract_upload_urls(text: str) -> set[str]:
    found: set[str] = set()
    for pattern in PROD_UPLOAD_PATTERNS:
        for match in pattern.findall(text or ""):
            url = match if match.startswith("http") else f"https:{match}"
            found.add(url.split("?")[0])
    return found


def upload_path_from_url(url: str) -> str | None:
    marker = "/wp-content/uploads/"
    idx = url.find(marker)
    if idx == -1:
        return None
    return url[idx + len(marker) :]


def rewrite_upload_urls(content: str, dev_url: str) -> str:
    dev_url = dev_url.rstrip("/")
    out = re.sub(
        r"https?://(?:www\.)?nimrod\.bio(/wp-content/uploads/)",
        dev_url + r"\1",
        content or "",
        flags=re.I,
    )
    out = re.sub(
        r"//(?:www\.)?nimrod\.bio(/wp-content/uploads/)",
        dev_url + r"\1",
        out,
        flags=re.I,
    )
    return out


def ensure_cache_dirs() -> None:
    RAW_DIR.mkdir(parents=True, exist_ok=True)
    UPLOADS_DIR.mkdir(parents=True, exist_ok=True)
