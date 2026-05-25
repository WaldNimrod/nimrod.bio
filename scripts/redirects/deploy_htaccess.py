#!/usr/bin/env python3
"""Deploy generated AOS redirects block into remote .htaccess via FTPS."""
from __future__ import annotations

import ftplib
from pathlib import Path

from _lib import (
    HTACCESS_BLOCK_PATH,
    MIGRATION_CACHE_DIR,
    ftps_connect_from_env,
    ftps_read_file,
    ftps_write_file,
    iso_utc_now,
    load_default_envs,
    strip_existing_block,
)

REMOTE_HTACCESS_PATH = ".htaccess"


def main() -> int:
    load_default_envs()
    if not HTACCESS_BLOCK_PATH.exists():
        raise RuntimeError(
            f"Missing generated block file: {HTACCESS_BLOCK_PATH}. "
            "Run scripts/redirects/generate_htaccess_block.py first."
        )

    block = HTACCESS_BLOCK_PATH.read_text(encoding="utf-8").strip() + "\n"
    if "# AOS-V200-redirects-START" not in block or "# AOS-V200-redirects-END" not in block:
        raise RuntimeError("Generated block is missing START/END markers")

    ftp = ftps_connect_from_env()
    try:
        try:
            current = ftps_read_file(ftp, REMOTE_HTACCESS_PATH)
        except ftplib.error_perm as exc:
            if "No such file or directory" in str(exc):
                current = ""
            else:
                raise
        ts = iso_utc_now().replace(":", "").replace("-", "")
        MIGRATION_CACHE_DIR.mkdir(parents=True, exist_ok=True)
        backup_path = MIGRATION_CACHE_DIR / f"htaccess.{ts}.bak"
        before_path = MIGRATION_CACHE_DIR / "htaccess.before.current"
        after_path = MIGRATION_CACHE_DIR / "htaccess.after.current"

        backup_path.write_text(current, encoding="utf-8")
        before_path.write_text(current, encoding="utf-8")

        stripped, replaced_existing = strip_existing_block(current)
        base = stripped.strip("\n")
        if base:
            merged = base + "\n\n" + block.strip() + "\n"
        else:
            merged = block.strip() + "\n"

        ftps_write_file(ftp, REMOTE_HTACCESS_PATH, merged)
        after_path.write_text(merged, encoding="utf-8")

        print(f"[OK] Uploaded updated {REMOTE_HTACCESS_PATH}")
        print(f"[INFO] Backup: {backup_path}")
        print(f"[INFO] Before snapshot: {before_path}")
        print(f"[INFO] After snapshot: {after_path}")
        print(f"[INFO] Existing AOS block replaced: {'yes' if replaced_existing else 'no'}")
        return 0
    finally:
        try:
            ftp.quit()
        except Exception:
            ftp.close()


if __name__ == "__main__":
    raise SystemExit(main())
