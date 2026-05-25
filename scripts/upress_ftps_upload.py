#!/usr/bin/env python3
"""uPress FTPS uploader using the canonical SFA protocol.

Protocol (from AOS runbook):
  connect -> login -> prot_c -> set_pasv(True)
This is explicit FTPS on port 21 with certificate verification disabled.
"""

from __future__ import annotations

import argparse
import ftplib
import os
import socket
import ssl
import sys
import urllib.request
from pathlib import Path


REPO_ROOT = Path(__file__).resolve().parent.parent
DEFAULT_ENV = REPO_ROOT / ".env.upress.dev"
DEFAULT_LOCAL = REPO_ROOT / "nimrod.bio" / "wp-content" / "themes" / "nimrod-bio-2026"
DEFAULT_REMOTE = "wp-content/themes/nimrod-bio-2026"


def _load_env_file(path: Path) -> None:
    if not path.exists():
        return
    for raw in path.read_text(encoding="utf-8").splitlines():
        line = raw.strip()
        if not line or line.startswith("#") or "=" not in line:
            continue
        key, value = line.split("=", 1)
        key = key.strip()
        value = value.strip()
        if (value.startswith("'") and value.endswith("'")) or (
            value.startswith('"') and value.endswith('"')
        ):
            value = value[1:-1]
        os.environ[key] = value


def _get_public_ipv4() -> str:
    endpoints = (
        "https://ipv4.icanhazip.com",
        "https://ifconfig.me/ip",
    )
    for url in endpoints:
        req = urllib.request.Request(url, headers={"User-Agent": "nimrod-bio-ftps-uploader/1.0"})
        try:
            with urllib.request.urlopen(req, timeout=10) as resp:
                value = resp.read().decode("utf-8").strip()
            # Basic IPv4 shape check.
            if value.count(".") == 3:
                return value
        except Exception:
            continue
    raise RuntimeError("Could not determine current public IPv4")


def _ip_allowlist_check(skip_check: bool) -> None:
    if skip_check:
        return
    current_ip = _get_public_ipv4()
    allowed_raw = os.getenv("UPRESS_FTP_ALLOWED_IPS", "")
    allowed = {entry.strip() for entry in allowed_raw.split(",") if entry.strip()}

    print(f"[INFO] Current public IPv4: {current_ip}")
    if not allowed:
        print(
            "[WARN] UPRESS_FTP_ALLOWED_IPS is empty. "
            "uPress may block FTPS unless the IP is allowlisted."
        )
        return
    if current_ip not in allowed:
        raise RuntimeError(
            "Current IP is not in UPRESS_FTP_ALLOWED_IPS. "
            "Update allowlist in uPress panel (FTP Accounts) and in .env.upress.dev, then retry."
        )


def _tcp_probe(host: str, port: int) -> None:
    sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    sock.settimeout(8)
    try:
        sock.connect((host, port))
    finally:
        sock.close()


def _connect(host: str, port: int, user: str, password: str) -> ftplib.FTP_TLS:
    ctx = ssl.create_default_context()
    ctx.check_hostname = False
    ctx.verify_mode = ssl.CERT_NONE

    ftp = ftplib.FTP_TLS(context=ctx)
    ftp.connect(host, port, timeout=20)
    ftp.login(user, password)
    ftp.prot_c()  # Canonical uPress mode: prot_p may fail data channel.
    ftp.set_pasv(True)
    return ftp


def _ensure_dir(ftp: ftplib.FTP_TLS, remote_path: str) -> None:
    parts = [p for p in remote_path.strip("/").split("/") if p]
    if not parts:
        return
    ftp.cwd("/")
    for part in parts:
        try:
            ftp.cwd(part)
        except ftplib.error_perm:
            ftp.mkd(part)
            ftp.cwd(part)


def _upload_tree(ftp: ftplib.FTP_TLS, local_root: Path, remote_root: str, dry_run: bool) -> int:
    files = sorted(p for p in local_root.rglob("*") if p.is_file())
    print(f"[INFO] Files to upload: {len(files)}")
    if dry_run:
        for f in files:
            rel = f.relative_to(local_root).as_posix()
            print(f"[DRY] {remote_root}/{rel}")
        return len(files)

    uploaded = 0
    for f in files:
        rel = f.relative_to(local_root).as_posix()
        parent = Path(rel).parent.as_posix()
        target_dir = remote_root if parent == "." else f"{remote_root}/{parent}"
        _ensure_dir(ftp, target_dir)
        ftp.cwd("/")
        ftp.cwd(target_dir)
        with f.open("rb") as stream:
            ftp.storbinary(f"STOR {Path(rel).name}", stream)
        uploaded += 1
        print(f"[UP] {target_dir}/{Path(rel).name}")
    return uploaded


def main() -> int:
    parser = argparse.ArgumentParser(description="Upload local directory to uPress via canonical FTPS protocol")
    parser.add_argument("--env-file", default=str(DEFAULT_ENV))
    parser.add_argument("--local-dir", default=str(DEFAULT_LOCAL))
    parser.add_argument("--remote-dir", default=DEFAULT_REMOTE)
    parser.add_argument("--dry-run", action="store_true")
    parser.add_argument("--skip-ip-check", action="store_true")
    args = parser.parse_args()

    _load_env_file(Path(args.env_file))

    host = os.getenv("UPRESS_FTP_HOST", "").strip()
    user = os.getenv("UPRESS_FTP_USER", "").strip()
    password = os.getenv("UPRESS_FTP_PASS", "").strip()
    port = int(os.getenv("UPRESS_FTP_PORT", "21"))

    if not (host and user and password):
        print("[ERROR] Missing UPRESS_FTP_HOST / UPRESS_FTP_USER / UPRESS_FTP_PASS")
        return 1

    local_dir = Path(args.local_dir).resolve()
    if not local_dir.exists():
        print(f"[ERROR] Local directory not found: {local_dir}")
        return 1

    try:
        _ip_allowlist_check(skip_check=args.skip_ip_check)
        print(f"[INFO] Probing TCP {host}:{port} ...")
        _tcp_probe(host, port)
    except Exception as exc:
        print(f"[ERROR] Preflight failed: {exc}")
        return 2

    if args.dry_run:
        # Dry-run still prints upload plan without opening FTPS session.
        _upload_tree(None, local_dir, args.remote_dir.strip("/"), dry_run=True)  # type: ignore[arg-type]
        return 0

    ftp = None
    try:
        print(f"[INFO] Connecting FTPS to {host}:{port} as {user} ...")
        ftp = _connect(host, port, user, password)
        uploaded = _upload_tree(ftp, local_dir, args.remote_dir.strip("/"), dry_run=False)
        print(f"[OK] Uploaded {uploaded} file(s) to /{args.remote_dir.strip('/')}/")
        return 0
    except Exception as exc:
        print(f"[ERROR] Upload failed: {exc}")
        return 3
    finally:
        if ftp is not None:
            try:
                ftp.quit()
            except Exception:
                try:
                    ftp.close()
                except Exception:
                    pass


if __name__ == "__main__":
    sys.exit(main())
