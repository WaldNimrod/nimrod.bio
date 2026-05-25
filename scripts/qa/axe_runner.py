#!/usr/bin/env python3
"""Run axe-core CLI on representative URLs and save aggregated results."""
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
        for route in ("projects?per_page=1&_fields=link", "posts?per_page=1&_fields=link"):
            resp = page.request.get(_build_url(rest_base, route), timeout=30000)
            if not resp.ok:
                continue
            data = resp.json()
            if isinstance(data, list) and data and data[0].get("link"):
                urls.append(data[0]["link"])
        browser.close()
    deduped = []
    seen = set()
    for url in urls:
        if url in seen:
            continue
        seen.add(url)
        deduped.append(url)
    return deduped[:7]


def _run_axe(url: str) -> dict:
    cmd = [
        "npx",
        "--yes",
        "@axe-core/cli",
        url,
        "--chrome-options=--headless=new --ignore-certificate-errors",
        "--stdout",
    ]
    proc = subprocess.run(cmd, capture_output=True, text=True, check=False)
    if proc.returncode != 0:
        return {"url": url, "status": "error", "error": proc.stderr.strip() or proc.stdout.strip()}
    data = json.loads(proc.stdout)
    result = data[0] if isinstance(data, list) and data else data
    violations = result.get("violations", [])
    serious_or_critical = [
        v for v in violations if v.get("impact") in ("serious", "critical")
    ]
    return {
        "url": url,
        "status": "ok",
        "violations_total": len(violations),
        "violations_serious_or_critical": len(serious_or_critical),
        "violations": violations,
    }


def main() -> int:
    base_url = os.environ.get("UPRESS_DEV_URL")
    if not base_url:
        raise RuntimeError("UPRESS_DEV_URL is required (source .env.upress.dev first)")

    today = datetime.now().date().isoformat()
    output_path = DOCS_DIR / f"qa_a11y_axe_results_{today}.json"
    urls = _discover_urls(base_url)
    results = [_run_axe(url) for url in urls]

    ok_results = [r for r in results if r.get("status") == "ok"]
    serious_total = sum(r.get("violations_serious_or_critical", 0) for r in ok_results)
    payload = {
        "generated_at": datetime.utcnow().replace(microsecond=0).isoformat() + "Z",
        "base_url": base_url,
        "urls_tested": len(urls),
        "results": results,
        "summary": {
            "ok_results": len(ok_results),
            "total_violations": sum(r.get("violations_total", 0) for r in ok_results),
            "serious_or_critical_total": serious_total,
        },
    }
    output_path.write_text(json.dumps(payload, ensure_ascii=False, indent=2), encoding="utf-8")
    print(f"[OK] wrote {output_path}")
    print(f"[INFO] urls={len(urls)} serious_or_critical={serious_total}")
    return 0 if len(ok_results) == len(urls) else 1


if __name__ == "__main__":
    raise SystemExit(main())
