#!/usr/bin/env python3
"""Phase 5 — download referenced prod uploads and FTPS upload to dev."""
from __future__ import annotations

import argparse
import json
import os
import subprocess
import sys
import urllib.request
from pathlib import Path
from urllib.parse import quote, urlsplit, urlunsplit

sys.path.insert(0, str(Path(__file__).resolve().parent))

from _lib import (  # noqa: E402
    REPO,
    REFERENCED_UPLOADS_PATH,
    UPLOADS_DIR,
    dev_rest_base,
    dev_auth,
    dev_site_url,
    ensure_cache_dirs,
    load_envs,
    rest_request,
    upload_path_from_url,
)
from transform_post import transform_record  # noqa: E402
from _lib import importable_posts, load_decisions, positive_decisions  # noqa: E402


def load_referenced_urls() -> list[str]:
    if REFERENCED_UPLOADS_PATH.exists():
        return json.loads(REFERENCED_UPLOADS_PATH.read_text(encoding="utf-8"))
    urls: set[str] = set()
    for row in importable_posts(load_decisions()):
        urls.update(transform_record(row)["referenced_uploads"])
    return sorted(urls)


def download_file(url: str, dest: Path) -> bool:
    dest.parent.mkdir(parents=True, exist_ok=True)
    if dest.exists() and dest.stat().st_size > 0:
        return True
    parts = urlsplit(url)
    safe_path = quote(parts.path, safe="/%")
    safe_url = urlunsplit((parts.scheme, parts.netloc, safe_path, parts.query, parts.fragment))
    req = urllib.request.Request(safe_url, headers={"User-Agent": "nimrod-bio-migration/1.0"})
    try:
        with urllib.request.urlopen(req, timeout=120) as resp:
            dest.write_bytes(resp.read())
        return True
    except Exception as exc:  # noqa: BLE001
        print(f"[WARN] download failed {url}: {exc}")
        return False


def patch_post_content(dev_id: int, content: str, base: str, user: str, password: str) -> None:
    rest_request(
        "POST",
        f"{base}/posts/{dev_id}",
        user,
        password,
        {"content": content},
        sleep_ms=100,
    )


def main() -> int:
    parser = argparse.ArgumentParser()
    parser.add_argument("--dry-run", action="store_true")
    parser.add_argument("--skip-upload", action="store_true")
    args = parser.parse_args()

    load_envs()
    ensure_cache_dirs()
    urls = load_referenced_urls()
    print(f"[INFO] Referenced uploads: {len(urls)}")

    downloaded = 0
    for url in urls:
        rel = upload_path_from_url(url)
        if not rel:
            continue
        dest = UPLOADS_DIR / rel
        if args.dry_run:
            print(f"[DRY] {rel}")
            continue
        if download_file(url, dest):
            downloaded += 1

    if args.dry_run:
        return 0

    print(f"[OK] Downloaded/cached {downloaded} files under {UPLOADS_DIR}")

    if not args.skip_upload and downloaded:
        ftps_script = REPO / "scripts" / "upress_ftps_upload.py"
        cmd = [
            sys.executable,
            str(ftps_script),
            "--env-file",
            str(REPO / ".env.upress.dev"),
            "--local-dir",
            str(UPLOADS_DIR),
            "--remote-dir",
            "wp-content/uploads",
            "--skip-ip-check",
        ]
        print(f"[INFO] FTPS upload: {' '.join(cmd)}")
        result = subprocess.run(cmd, cwd=str(REPO), check=False)
        if result.returncode != 0:
            print(f"[ERROR] FTPS upload failed with code {result.returncode}")
            return 1

    # Rewrite any remaining prod URLs in imported posts.
    base = dev_rest_base()
    user, password = dev_auth()
    dev_url = dev_site_url()
    mapping_path = REPO / ".migration-cache" / "id_mapping.json"
    if mapping_path.exists():
        mapping = json.loads(mapping_path.read_text(encoding="utf-8"))
        for prod_id, dev_id in mapping.items():
            row = next((r for r in positive_decisions() if str(r["id"]) == str(prod_id)), None)
            if not row or row.get("type") != "post":
                continue
            transformed = transform_record(row)
            patch_post_content(int(dev_id), transformed["payload"]["content"], base, user, password)
        print(f"[OK] Patched content URLs for {len(mapping)} imported entities")

    return 0


if __name__ == "__main__":
    raise SystemExit(main())
