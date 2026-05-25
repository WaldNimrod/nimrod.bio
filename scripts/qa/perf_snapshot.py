#!/usr/bin/env python3
"""Capture lightweight performance baseline for dev URLs."""
from __future__ import annotations

import json
import os
from datetime import datetime
from pathlib import Path
from urllib.parse import urljoin

import requests


DOCS_DIR = Path("docs")


def _build_url(base: str, path: str) -> str:
    return urljoin(base.rstrip("/") + "/", path.lstrip("/"))


def _probe(url: str) -> dict:
    try:
        response = requests.get(url, timeout=30, verify=False)
        headers = dict(response.headers)
        return {
            "url": url,
            "status": response.status_code,
            "ttfb_ms": round(response.elapsed.total_seconds() * 1000, 2),
            "bytes": len(response.content),
            "request_count": None,
            "lcp_ms": None,
            "cache_header": headers.get("x-cache")
            or headers.get("cf-cache-status")
            or headers.get("cache-control"),
        }
    except requests.RequestException as exc:
        return {
            "url": url,
            "status": 0,
            "error": str(exc),
            "ttfb_ms": None,
            "bytes": None,
            "request_count": None,
            "lcp_ms": None,
            "cache_header": None,
        }


def main() -> int:
    base_url = os.environ.get("UPRESS_DEV_URL")
    if not base_url:
        raise RuntimeError("UPRESS_DEV_URL is required (source .env.upress.dev first)")

    targets = [
        _build_url(base_url, "/"),
        _build_url(base_url, "/blog/"),
        _build_url(base_url, "/services/produce/"),
    ]
    rows = [_probe(url) for url in targets]
    payload = {
        "generated_at": datetime.utcnow().replace(microsecond=0).isoformat() + "Z",
        "base_url": base_url,
        "rows": rows,
        "notes": "TTFB/bytes captured via requests; request_count and LCP require browser tracing and are left null.",
    }
    today = datetime.now().date().isoformat()
    output_path = DOCS_DIR / f"perf_baseline_dev_{today}.json"
    output_path.write_text(json.dumps(payload, ensure_ascii=False, indent=2), encoding="utf-8")
    ok = sum(1 for row in rows if row.get("status", 0) >= 200 and row.get("status", 0) < 400)
    print(f"[OK] wrote {output_path}")
    print(f"[INFO] urls={len(rows)} ok={ok}")
    return 0 if ok == len(rows) else 1


if __name__ == "__main__":
    raise SystemExit(main())
