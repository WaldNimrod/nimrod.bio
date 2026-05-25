#!/usr/bin/env python3
"""Run responsive QA probes across template representatives."""
from __future__ import annotations

import json
import os
from datetime import datetime
from pathlib import Path
from urllib.parse import urljoin

from playwright.sync_api import Error as PlaywrightError
from playwright.sync_api import sync_playwright


DOCS_DIR = Path("docs")
VIEWPORTS = [
    {"name": "mobile", "width": 360, "height": 800},
    {"name": "tablet", "width": 768, "height": 1024},
    {"name": "laptop", "width": 1280, "height": 800},
    {"name": "desktop", "width": 1920, "height": 1080},
]


def _build_url(base: str, path: str) -> str:
    return urljoin(base.rstrip("/") + "/", path.lstrip("/"))


def _rest_get_json(page, rest_base: str, route: str):
    resp = page.request.get(_build_url(rest_base, route), timeout=30000)
    if not resp.ok:
        return None
    try:
        return resp.json()
    except Exception:
        return None


def discover_sample_urls(base_url: str) -> list[dict[str, str]]:
    rest_base = _build_url(base_url, "/wp-json/wp/v2/")
    urls = [
        {"template": "T7 Home", "url": _build_url(base_url, "/")},
        {"template": "T1 World", "url": _build_url(base_url, "/world/soil/")},
        {"template": "T2 Service", "url": _build_url(base_url, "/services/produce/")},
        {"template": "T5 Blog Index", "url": _build_url(base_url, "/blog/")},
        {"template": "T8 Static", "url": _build_url(base_url, "/contact/")},
    ]

    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        page = browser.new_page(ignore_https_errors=True)

        projects = _rest_get_json(page, rest_base, "projects?per_page=1&_fields=link,slug")
        if isinstance(projects, list) and projects:
            urls.append({"template": "T3 Project", "url": projects[0].get("link", "")})
        else:
            urls.append({"template": "T3 Project", "url": _build_url(base_url, "/projects/")})

        posts = _rest_get_json(page, rest_base, "posts?per_page=1&_fields=link,slug")
        if isinstance(posts, list) and posts:
            urls.append({"template": "T4 Post", "url": posts[0].get("link", "")})
        else:
            urls.append({"template": "T4 Post", "url": _build_url(base_url, "/blog/")})
        browser.close()

    # Ensure 7 template representatives to produce 28 probes.
    return [entry for entry in urls if entry.get("url")]


def main() -> int:
    base_url = os.environ.get("UPRESS_DEV_URL")
    if not base_url:
        raise RuntimeError("UPRESS_DEV_URL is required (source .env.upress.dev first)")

    samples = discover_sample_urls(base_url)
    now = datetime.utcnow().replace(microsecond=0).isoformat() + "Z"
    today = datetime.now().date().isoformat()
    output_path = DOCS_DIR / f"qa_responsive_matrix_{today}.json"

    records = []
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True, args=["--ignore-certificate-errors"])
        for sample in samples:
            for viewport in VIEWPORTS:
                page = browser.new_page(
                    ignore_https_errors=True,
                    viewport={"width": viewport["width"], "height": viewport["height"]},
                )
                row = {
                    "template": sample["template"],
                    "url": sample["url"],
                    "viewport": viewport,
                    "http_status": None,
                    "no_horizontal_scroll": None,
                    "pass": False,
                    "error": None,
                }
                try:
                    response = page.goto(sample["url"], wait_until="domcontentloaded", timeout=60000)
                    row["http_status"] = response.status if response else None
                    metrics = page.evaluate(
                        """() => {
                            const sw = document.documentElement.scrollWidth;
                            const iw = window.innerWidth;
                            const maxScroll = Math.max(0, sw - iw);
                            const initialX = window.scrollX;
                            window.scrollTo({ left: maxScroll, behavior: "instant" });
                            const afterX = window.scrollX;
                            window.scrollTo({ left: initialX, behavior: "instant" });
                            return {
                                scroll_width: sw,
                                inner_width: iw,
                                overflow_px: maxScroll,
                                scroll_x_after_probe: afterX
                            };
                        }"""
                    )
                    row["scroll_width"] = metrics["scroll_width"]
                    row["inner_width"] = metrics["inner_width"]
                    row["overflow_px"] = metrics["overflow_px"]
                    # Pass criteria is based on actual horizontal scrollability,
                    # not hidden off-canvas elements that don't move viewport.
                    row["no_horizontal_scroll"] = metrics["scroll_x_after_probe"] <= 2
                    row["pass"] = (
                        bool(row["http_status"])
                        and int(row["http_status"]) < 400
                        and bool(row["no_horizontal_scroll"])
                    )
                except PlaywrightError as exc:
                    row["error"] = str(exc)
                finally:
                    page.close()
                records.append(row)
        browser.close()

    passed = sum(1 for item in records if item["pass"])
    payload = {
        "generated_at": now,
        "base_url": base_url,
        "total_templates": len(samples),
        "total_probes": len(records),
        "passed_probes": passed,
        "pass_rate": round((passed / len(records)) * 100, 2) if records else 0.0,
        "results": records,
    }
    output_path.write_text(json.dumps(payload, ensure_ascii=False, indent=2), encoding="utf-8")
    print(f"[OK] wrote {output_path}")
    print(f"[INFO] probes={len(records)} passed={passed}")
    return 0 if passed == len(records) else 1


if __name__ == "__main__":
    raise SystemExit(main())
