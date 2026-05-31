#!/usr/bin/env python3
"""Download specific remote files from uPress via canonical FTPS protocol (for byte-parity diffing)."""
from __future__ import annotations
import argparse, ftplib, os, ssl, sys
from pathlib import Path

REPO_ROOT = Path(__file__).resolve().parent.parent
DEFAULT_ENV = REPO_ROOT / ".env.upress.dev"


def _load_env_file(path: Path) -> None:
    if not path.exists():
        return
    for raw in path.read_text(encoding="utf-8").splitlines():
        line = raw.strip()
        if not line or line.startswith("#") or "=" not in line:
            continue
        key, value = line.split("=", 1)
        key = key.strip(); value = value.strip()
        if (value[:1] == value[-1:]) and value[:1] in ("'", '"'):
            value = value[1:-1]
        os.environ[key] = value


def _connect(host, port, user, password):
    ctx = ssl.create_default_context()
    ctx.check_hostname = False
    ctx.verify_mode = ssl.CERT_NONE
    ftp = ftplib.FTP_TLS(context=ctx)
    ftp.connect(host, port, timeout=20)
    ftp.login(user, password)
    ftp.prot_c()
    ftp.set_pasv(True)
    return ftp


def main() -> int:
    p = argparse.ArgumentParser()
    p.add_argument("--env-file", default=str(DEFAULT_ENV))
    p.add_argument("--remote-dir", default="wp-content/themes/nimrod-bio-2026")
    p.add_argument("--dest", required=True)
    p.add_argument("--files", nargs="+", required=True, help="relative paths under remote-dir")
    args = p.parse_args()
    _load_env_file(Path(args.env_file))
    host = os.getenv("UPRESS_FTP_HOST", "").strip()
    user = os.getenv("UPRESS_FTP_USER", "").strip()
    password = os.getenv("UPRESS_FTP_PASS", "").strip()
    port = int(os.getenv("UPRESS_FTP_PORT", "21"))
    if not (host and user and password):
        print("[ERROR] missing creds"); return 1
    dest = Path(args.dest); dest.mkdir(parents=True, exist_ok=True)
    ftp = _connect(host, port, user, password)
    try:
        rroot = args.remote_dir.strip("/")
        for rel in args.files:
            local = dest / rel
            local.parent.mkdir(parents=True, exist_ok=True)
            with local.open("wb") as fh:
                ftp.retrbinary(f"RETR /{rroot}/{rel}", fh.write)
            print(f"[DL] {rel} -> {local}")
    finally:
        try: ftp.quit()
        except Exception: ftp.close()
    return 0


if __name__ == "__main__":
    sys.exit(main())
