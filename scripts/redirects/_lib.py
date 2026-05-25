"""Shared helpers for P004-WP002 redirect automation."""
from __future__ import annotations

import ftplib
import json
import os
import re
import ssl
from datetime import datetime, timezone
from pathlib import Path
from typing import Any
from urllib.parse import quote, unquote, urlparse

REPO_ROOT = Path(__file__).resolve().parent.parent.parent
DECISIONS_PATH = REPO_ROOT / "docs" / "url_migration_decisions_2026-05-25.json"
HTACCESS_BLOCK_PATH = REPO_ROOT / "docs" / "htaccess_v200_redirects.txt"
MIGRATION_CACHE_DIR = REPO_ROOT / ".migration-cache"
VERIFICATION_DIR = REPO_ROOT / "docs"

BLOCK_START = "# AOS-V200-redirects-START"
BLOCK_END = "# AOS-V200-redirects-END"

HERITAGE_PAGE_ID = "2516"
HERITAGE_TARGET = "/about/heritage/"


def load_env_file(path: Path) -> None:
    if not path.exists():
        return
    for raw in path.read_text(encoding="utf-8").splitlines():
        line = raw.strip()
        if not line or line.startswith("#") or "=" not in line:
            continue
        key, value = line.split("=", 1)
        key = key.strip()
        value = value.strip().strip("'\"")
        os.environ[key] = value


def load_default_envs() -> None:
    # Keep same precedence used elsewhere in this repo.
    load_env_file(REPO_ROOT / ".env.upress")
    load_env_file(REPO_ROOT / ".env.upress.dev")


def load_decisions() -> dict[str, Any]:
    return json.loads(DECISIONS_PATH.read_text(encoding="utf-8"))


def quote_slug(value: str) -> str:
    encoded = quote((value or "").strip().strip("/"), safe="/")
    return re.sub(r"%[0-9A-F]{2}", lambda m: m.group(0).lower(), encoded)


def quote_url_path(value: str) -> str:
    parsed = urlparse(value)
    path = parsed.path or value
    if not path.startswith("/"):
        path = "/" + path
    encoded = quote(path, safe="/")
    encoded = re.sub(r"%[0-9A-F]{2}", lambda m: m.group(0).lower(), encoded)
    if not encoded.endswith("/"):
        encoded += "/"
    return encoded


def iso_utc_now() -> str:
    return datetime.now(timezone.utc).replace(microsecond=0).isoformat()


def normalize_path(path_or_url: str) -> str:
    raw = path_or_url
    if "://" in raw:
        raw = urlparse(raw).path or "/"
    if not raw.startswith("/"):
        raw = "/" + raw
    decoded = unquote(raw)
    if decoded != "/" and not decoded.endswith("/"):
        decoded += "/"
    return decoded


def strip_existing_block(text: str) -> tuple[str, bool]:
    start = text.find(BLOCK_START)
    if start == -1:
        return text.rstrip() + "\n", False
    end = text.find(BLOCK_END, start)
    if end == -1:
        raise RuntimeError("Found START marker without END marker in .htaccess")
    end = end + len(BLOCK_END)
    while end < len(text) and text[end] in "\r\n":
        end += 1
    prefix = text[:start]
    suffix = text[end:]
    prefix_lines = [line.strip() for line in prefix.splitlines() if line.strip()]
    if prefix_lines and all(line == "# ============================================================" for line in prefix_lines):
        prefix = ""
    stripped = (prefix + suffix).rstrip() + "\n"
    return stripped, True


def ftps_connect_from_env() -> ftplib.FTP_TLS:
    host = os.getenv("UPRESS_FTP_HOST", "").strip()
    user = os.getenv("UPRESS_FTP_USER", "").strip()
    password = os.getenv("UPRESS_FTP_PASS", "").strip()
    port = int(os.getenv("UPRESS_FTP_PORT", "21"))
    if not (host and user and password):
        raise RuntimeError("Missing UPRESS_FTP_HOST / UPRESS_FTP_USER / UPRESS_FTP_PASS")

    ctx = ssl.create_default_context()
    ctx.check_hostname = False
    ctx.verify_mode = ssl.CERT_NONE

    ftp = ftplib.FTP_TLS(context=ctx)
    ftp.connect(host, port, timeout=20)
    ftp.login(user, password)
    ftp.prot_c()
    ftp.set_pasv(True)
    return ftp


def ftps_read_file(ftp: ftplib.FTP_TLS, remote_path: str) -> str:
    chunks: list[bytes] = []
    ftp.retrbinary(f"RETR {remote_path}", chunks.append)
    return b"".join(chunks).decode("utf-8", errors="replace")


def ftps_write_file(ftp: ftplib.FTP_TLS, remote_path: str, content: str) -> None:
    payload = content.encode("utf-8")
    from io import BytesIO

    ftp.storbinary(f"STOR {remote_path}", BytesIO(payload))
