#!/usr/bin/env python3
"""Run Lighthouse on representative URLs and emit consolidated JSON."""
from __future__ import annotations

import json
import os
import subprocess
from datetime import datetime
from pathlib import Path
from urllib.parse import urljoin

from playwright.sync_api import sync_playwright


DOCS_DIR = Path("docs")


def _build_url(base: str, path: str) -> str:
    return urljoin(base.rstrip("/") + "/", path.lstrip("/"))


def _discover_urls(base_url: str) -> list[str]:
    rest_base = _build_url(base_url, "/wp-json/wp/v2/")
    urls = [
        _build_url(base_url, "/"),
        _build_url(base_url, "/world/soil/"),
        _build_url(base_url, "/services/produce/"),
        _build_url(base_url, "/blog/"),
        _build_url(base_url, "/contact/"),
    ]
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        page = browser.new_page(ignore_https_errors=True)
        for route in ("projects?per_page=1&_fields=link", "posts?per_page=3&_fields=link"):
            resp = page.request.get(_build_url(rest_base, route), timeout=30000)
            if not resp.ok:
                continue
            data = resp.json()
            if isinstance(data, list):
                for item in data:
                    link = item.get("link")
                    if link:
                        urls.append(link)
        browser.close()
    # Keep first 8 URLs as required by LOD400.
    deduped = []
    seen = set()
    for url in urls:
        if url in seen:
            continue
        seen.add(url)
        deduped.append(url)
    return deduped[:8]


def _run_lighthouse(url: str) -> dict:
    cmd = [
        "npx",
        "--yes",
        "lighthouse",
        url,
        "--quiet",
        "--chrome-flags=--headless=new --ignore-certificate-errors",
        "--output=json",
        "--output-path=stdout",
    ]
    proc = subprocess.run(cmd, capture_output=True, text=True, check=False)
    if proc.returncode != 0:
        return {
            "url": url,
            "status": "error",
            "error": proc.stderr.strip() or proc.stdout.strip(),
            "scores": None,
        }
    report = json.loads(proc.stdout)
    categories = report.get("categories", {})
    return {
        "url": url,
        "status": "ok",
        "scores": {
            "performance": round((categories.get("performance", {}).get("score") or 0) * 100),
            "accessibility": round((categories.get("accessibility", {}).get("score") or 0) * 100),
            "best_practices": round((categories.get("best-practices", {}).get("score") or 0) * 100),
            "seo": round((categories.get("seo", {}).get("score") or 0) * 100),
        },
    }


def main() -> int:
    base_url = os.environ.get("UPRESS_DEV_URL")
    if not base_url:
        raise RuntimeError("UPRESS_DEV_URL is required (source .env.upress.dev first)")

    today = datetime.now().date().isoformat()
    output_path = DOCS_DIR / f"qa_lighthouse_results_{today}.json"
    urls = _discover_urls(base_url)

    results = [_run_lighthouse(url) for url in urls]
    ok_results = [r for r in results if r["status"] == "ok"]
    averages = {
        "performance": round(sum(r["scores"]["performance"] for r in ok_results) / len(ok_results), 2)
        if ok_results
        else None,
        "accessibility": round(sum(r["scores"]["accessibility"] for r in ok_results) / len(ok_results), 2)
        if ok_results
        else None,
        "best_practices": round(sum(r["scores"]["best_practices"] for r in ok_results) / len(ok_results), 2)
        if ok_results
        else None,
        "seo": round(sum(r["scores"]["seo"] for r in ok_results) / len(ok_results), 2) if ok_results else None,
    }

    payload = {
        "generated_at": datetime.utcnow().replace(microsecond=0).isoformat() + "Z",
        "base_url": base_url,
        "urls_tested": len(urls),
        "results": results,
        "averages": averages,
    }
    output_path.write_text(json.dumps(payload, ensure_ascii=False, indent=2), encoding="utf-8")
    print(f"[OK] wrote {output_path}")
    print(f"[INFO] urls={len(urls)} ok={len(ok_results)}")
    return 0 if len(ok_results) == len(urls) else 1


if __name__ == "__main__":
    raise SystemExit(main())
