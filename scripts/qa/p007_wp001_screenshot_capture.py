#!/usr/bin/env python3
"""P007-WP001 — capture canonical screenshot pack for MCP QA report."""
from __future__ import annotations

import json
import sys
from pathlib import Path
from urllib.parse import urljoin

from playwright.sync_api import Error as PlaywrightError
from playwright.sync_api import sync_playwright

REPO = Path(__file__).resolve().parent.parent.parent
sys.path.insert(0, str(REPO / "scripts" / "migration"))
from _lib import dev_site_url, load_envs  # noqa: E402

MANIFEST = REPO / "docs/qa/p007-wp001_url_manifest.json"
OUT_DIR = REPO / "docs/qa/screenshots/p007-wp001"


def build_url(base: str, path: str) -> str:
    return urljoin(base.rstrip("/") + "/", path.lstrip("/"))


def main() -> int:
    load_envs()
    base = dev_site_url().rstrip("/")
    manifest = json.loads(MANIFEST.read_text(encoding="utf-8"))
    OUT_DIR.mkdir(parents=True, exist_ok=True)

    # Deduplicate (name, path, viewport) tuples while preserving order.
    seen: set[tuple[str, str, int]] = set()
    jobs: list[tuple[str, str, int]] = []
    for name, path, viewports in manifest["pages"]:
        for vp in viewports:
            key = (name, path, vp)
            if key not in seen:
                seen.add(key)
                jobs.append(key)

    # Hebrew migrated post uses REST-resolved path when present.
    hebrew_path = manifest.get("t4_migrated_hebrew_path") or "/blog/%D7%99%D7%95%D7%9D-%D7%91%D7%92%D7%99%D7%A0%D7%94/"
    jobs = [
        (n, hebrew_path if n == "t4-post-hebrew" else p, vp)
        for n, p, vp in jobs
    ]

    results: list[dict] = []
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True, args=["--ignore-certificate-errors"])
        for name, path, viewport in jobs:
            url = build_url(base, path)
            filename = f"{name}_{viewport}.png"
            out_path = OUT_DIR / filename
            page = browser.new_page(
                ignore_https_errors=True,
                viewport={"width": viewport, "height": 900},
            )
            row = {
                "name": name,
                "path": path,
                "viewport": viewport,
                "url": url,
                "file": str(out_path.relative_to(REPO)),
                "http_status": None,
                "pass": False,
                "error": None,
            }
            try:
                response = page.goto(url, wait_until="networkidle", timeout=90000)
                row["http_status"] = response.status if response else None
                page.screenshot(path=str(out_path), full_page=True)
                row["pass"] = bool(row["http_status"]) and int(row["http_status"]) < 500
            except PlaywrightError as exc:
                row["error"] = str(exc)
            finally:
                page.close()
            results.append(row)
            status = "OK" if row["pass"] else "FAIL"
            print(f"[{status}] {filename} -> {row['http_status']}")
        browser.close()

    summary_path = REPO / "docs/qa/p007-wp001_screenshot_results.json"
    summary_path.write_text(json.dumps(results, ensure_ascii=False, indent=2), encoding="utf-8")
    passed = sum(1 for r in results if r["pass"])
    print(f"[INFO] captured={len(results)} passed={passed}")
    return 0 if passed == len(results) else 1


if __name__ == "__main__":
    raise SystemExit(main())
