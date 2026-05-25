#!/usr/bin/env python3
"""Crawl internal links/images and report HTTP status health."""
from __future__ import annotations

import json
import os
from collections import deque
from datetime import datetime
from pathlib import Path
from urllib.parse import urljoin, urlparse, urldefrag

import requests
import urllib3
from bs4 import BeautifulSoup


DOCS_DIR = Path("docs")
MAX_DEPTH = 3
TIMEOUT = 25
urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)


def _normalize(url: str) -> str:
    clean, _ = urldefrag(url)
    return clean


def _is_internal(url: str, host: str) -> bool:
    parsed = urlparse(url)
    return parsed.scheme in ("http", "https") and parsed.netloc == host


def _fetch_status(url: str) -> int:
    for _ in range(2):
        try:
            head = requests.head(url, allow_redirects=False, timeout=TIMEOUT, verify=False)
            if head.status_code == 405:
                get = requests.get(url, allow_redirects=False, timeout=TIMEOUT, verify=False)
                return get.status_code
            return head.status_code
        except requests.RequestException:
            try:
                get = requests.get(url, allow_redirects=False, timeout=TIMEOUT, verify=False)
                return get.status_code
            except requests.RequestException:
                continue
    return 0


def main() -> int:
    base_url = os.environ.get("UPRESS_DEV_URL")
    if not base_url:
        raise RuntimeError("UPRESS_DEV_URL is required (source .env.upress.dev first)")

    parsed_base = urlparse(base_url)
    host = parsed_base.netloc
    seed_urls = [
        urljoin(base_url.rstrip("/") + "/", path)
        for path in ("", "blog/", "contact/", "world/soil/", "services/produce/")
    ]

    queue = deque((u, 0) for u in seed_urls)
    visited_pages: set[str] = set()
    discovered_assets: set[str] = set()
    links_status: dict[str, int] = {}
    pages_crawled = []

    session = requests.Session()
    session.headers["User-Agent"] = "nimrod-bio-link-crawl/1.0"

    while queue:
        current, depth = queue.popleft()
        current = _normalize(current)
        if current in visited_pages or depth > MAX_DEPTH:
            continue
        visited_pages.add(current)

        try:
            response = session.get(current, timeout=TIMEOUT, verify=False)
            status_code = response.status_code
        except requests.RequestException:
            pages_crawled.append({"url": current, "status": 0, "depth": depth})
            continue

        pages_crawled.append({"url": current, "status": status_code, "depth": depth})
        if status_code >= 400:
            continue

        soup = BeautifulSoup(response.text, "html.parser")
        refs = []
        for tag, attr in (("a", "href"), ("img", "src")):
            for node in soup.find_all(tag):
                candidate = node.get(attr)
                if not candidate:
                    continue
                absolute = _normalize(urljoin(current, candidate))
                if not _is_internal(absolute, host):
                    continue
                refs.append(absolute)
                if tag == "img":
                    discovered_assets.add(absolute)

        for ref in refs:
            if ref not in links_status:
                links_status[ref] = _fetch_status(ref)
            if ref not in visited_pages and depth + 1 <= MAX_DEPTH:
                queue.append((ref, depth + 1))

    broken = {url: code for url, code in links_status.items() if code == 0 or code >= 400}
    payload = {
        "generated_at": datetime.utcnow().replace(microsecond=0).isoformat() + "Z",
        "base_url": base_url,
        "crawl_depth": MAX_DEPTH,
        "pages_crawled": pages_crawled,
        "links_checked_total": len(links_status),
        "assets_discovered_total": len(discovered_assets),
        "broken_links": [{"url": url, "status": code} for url, code in sorted(broken.items())],
        "summary": {
            "ok_links": sum(1 for c in links_status.values() if 200 <= c < 400),
            "planned_410": sum(1 for c in links_status.values() if c == 410),
            "broken_count": len(broken),
        },
    }

    today = datetime.now().date().isoformat()
    output_path = DOCS_DIR / f"qa_broken_links_{today}.json"
    output_path.write_text(json.dumps(payload, ensure_ascii=False, indent=2), encoding="utf-8")
    print(f"[OK] wrote {output_path}")
    print(f"[INFO] checked={len(links_status)} broken={len(broken)}")
    return 0 if len(broken) == 0 else 1


if __name__ == "__main__":
    raise SystemExit(main())
