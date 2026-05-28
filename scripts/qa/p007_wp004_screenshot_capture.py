#!/usr/bin/env python3
"""P007-WP004 — capture Wave 4 final screenshot pack (diff vs wp001 baseline)."""
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

MANIFEST = REPO / "docs/qa/p007-wp004_url_manifest.json"
OUT_DIR = REPO / "docs/qa/screenshots/p007-wp004"
BASELINE_DIR = REPO / "docs/qa/screenshots/p007-wp001"


def build_url(base: str, path: str) -> str:
    return urljoin(base.rstrip("/") + "/", path.lstrip("/"))


def main() -> int:
    load_envs()
    base = dev_site_url().rstrip("/")
    manifest = json.loads(MANIFEST.read_text(encoding="utf-8"))
    OUT_DIR.mkdir(parents=True, exist_ok=True)

    jobs: list[tuple[str, str, int]] = []
    seen: set[tuple[str, str, int]] = set()
    for name, path, viewports in manifest["pages"]:
        for vp in viewports:
            key = (name, path, vp)
            if key not in seen:
                seen.add(key)
                jobs.append(key)

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
            print(f"[{'OK' if row['pass'] else 'FAIL'}] {filename} -> {row['http_status']}")

        browser.close()

    out_json = REPO / "docs/qa/p007-wp004_screenshot_results.json"
    out_json.write_text(json.dumps(results, ensure_ascii=False, indent=2), encoding="utf-8")
    print(f"[OK] wrote {out_json}")
    return 0 if all(r["pass"] for r in results) else 1


if __name__ == "__main__":
    raise SystemExit(main())
