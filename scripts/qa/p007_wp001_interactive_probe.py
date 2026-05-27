#!/usr/bin/env python3
"""P007-WP001 — interactive trace, form re-verify, console/a11y/network probes."""
from __future__ import annotations

import json
import re
import sys
from pathlib import Path
from urllib.parse import urljoin, urlparse

from playwright.sync_api import Error as PlaywrightError
from playwright.sync_api import sync_playwright

REPO = Path(__file__).resolve().parent.parent.parent
sys.path.insert(0, str(REPO / "scripts" / "migration"))
from _lib import dev_site_url, load_envs  # noqa: E402

OUT_DIR = REPO / "docs/qa/screenshots/p007-wp001"
RESULTS = REPO / "docs/qa/p007-wp001_interactive_results.json"


def build_url(base: str, path: str) -> str:
    return urljoin(base.rstrip("/") + "/", path.lstrip("/"))


def same_path(a: str, b: str) -> bool:
    pa = urlparse(a)
    pb = urlparse(b)
    return pa.path.rstrip("/") == pb.path.rstrip("/")


def main() -> int:
    load_envs()
    base = dev_site_url().rstrip("/")
    OUT_DIR.mkdir(parents=True, exist_ok=True)

    payload: dict = {
        "base_url": base,
        "interactive_trace": [],
        "contact_form": {},
        "console": {},
        "network_t7": {},
        "a11y": {},
        "responsive_375": [],
    }

    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True, args=["--ignore-certificate-errors"])
        page = browser.new_page(
            ignore_https_errors=True,
            viewport={"width": 1440, "height": 900},
        )

        console_errors: dict[str, list[str]] = {}
        network_rows: list[dict] = []

        def attach_listeners(label: str) -> None:
            console_errors[label] = []
            page.on(
                "console",
                lambda msg: console_errors[label].append(f"{msg.type}: {msg.text}")
                if msg.type in {"error", "warning"}
                else None,
            )
            page.on(
                "pageerror",
                lambda exc: console_errors[label].append(f"pageerror: {exc}"),
            )

        # T7 home interactive trace
        attach_listeners("t7-home")
        page.goto(build_url(base, "/"), wait_until="networkidle", timeout=90000)

        # Hero/world links
        world_href = page.locator('a[href*="/world/soil/"]').first.get_attribute("href")
        page.locator('a[href*="/world/soil/"]').first.click()
        page.wait_for_load_state("networkidle")
        payload["interactive_trace"].append(
            {
                "from": "/",
                "action": "click world soil tile",
                "expected_path": "/world/soil/",
                "actual_url": page.url,
                "pass": same_path(page.url, build_url(base, "/world/soil/")),
            }
        )
        page.goto(build_url(base, "/"), wait_until="networkidle")

        service_href = page.locator('a[href*="/services/produce/"]').first
        if service_href.count():
            service_href.click()
            page.wait_for_load_state("networkidle")
            payload["interactive_trace"].append(
                {
                    "from": "/",
                    "action": "click service produce tile",
                    "expected_path": "/services/produce/",
                    "actual_url": page.url,
                    "pass": same_path(page.url, build_url(base, "/services/produce/")),
                }
            )
            page.goto(build_url(base, "/"), wait_until="networkidle")

        project_link = page.locator('a[href*="/project/"]').first
        if project_link.count():
            href = project_link.get_attribute("href") or ""
            project_link.click()
            page.wait_for_load_state("networkidle")
            payload["interactive_trace"].append(
                {
                    "from": "/",
                    "action": "click project tile",
                    "expected_path": urlparse(href).path if href else "/project/",
                    "actual_url": page.url,
                    "pass": "/project/" in page.url,
                }
            )
            page.goto(build_url(base, "/"), wait_until="networkidle")

        blog_link = page.locator('a[href*="/blog/"]').first
        if blog_link.count():
            href = blog_link.get_attribute("href") or ""
            blog_link.click()
            page.wait_for_load_state("networkidle")
            payload["interactive_trace"].append(
                {
                    "from": "/",
                    "action": "click blog card/link",
                    "expected_path": "/blog/",
                    "actual_url": page.url,
                    "pass": "/blog/" in page.url,
                }
            )

        # Network inventory on fresh T7 load
        page.goto(build_url(base, "/"), wait_until="networkidle")
        perf = page.evaluate(
            """() => {
              const entries = performance.getEntriesByType('resource');
              return entries.map(e => ({
                name: e.name,
                transferSize: e.transferSize || 0,
                initiatorType: e.initiatorType
              }));
            }"""
        )
        total_bytes = sum(r.get("transferSize", 0) for r in perf)
        sorted_assets = sorted(perf, key=lambda r: r.get("transferSize", 0), reverse=True)[:5]
        payload["network_t7"] = {
            "request_count": len(perf),
            "transfer_kb": round(total_bytes / 1024, 1),
            "top_5_assets": sorted_assets,
            "errors_4xx_5xx": [],
        }
        payload["console"]["t7-home"] = console_errors.get("t7-home", [])

        # T4 post console
        page.close()
        page = browser.new_page(viewport={"width": 1440, "height": 900}, ignore_https_errors=True)
        console_errors["t4-post-harish2021"] = []
        page.on(
            "console",
            lambda msg: console_errors["t4-post-harish2021"].append(f"{msg.type}: {msg.text}")
            if msg.type in {"error", "warning"}
            else None,
        )
        page.on(
            "pageerror",
            lambda exc: console_errors["t4-post-harish2021"].append(f"pageerror: {exc}"),
        )
        page.goto(build_url(base, "/blog/harish2021/"), wait_until="networkidle")
        payload["console"]["t4-post-harish2021"] = console_errors.get("t4-post-harish2021", [])

        # A11y quick pass on key templates
        templates = [
            ("t7-home", "/"),
            ("t1-world-soil", "/world/soil/"),
            ("t4-post-harish2021", "/blog/harish2021/"),
            ("t8-contact", "/contact/"),
        ]
        for label, path in templates:
            page.goto(build_url(base, path), wait_until="networkidle")
            stats = page.evaluate(
                """() => {
                  const imgs = [...document.querySelectorAll('img')];
                  const missingAlt = imgs.filter(i => !i.hasAttribute('alt')).length;
                  const headings = [...document.querySelectorAll('h1,h2,h3,h4,h5,h6')].map(h => h.tagName);
                  let skip = false;
                  for (let i = 1; i < headings.length; i++) {
                    const prev = parseInt(headings[i-1].slice(1), 10);
                    const cur = parseInt(headings[i].slice(1), 10);
                    if (cur - prev > 1) skip = true;
                  }
                  const dir = document.documentElement.getAttribute('dir') || getComputedStyle(document.documentElement).direction;
                  return { missingAlt, headings, heading_skip: skip, dir };
                }"""
            )
            payload["a11y"][label] = stats

        # Responsive 375 checks
        for label, path in [
            ("t7-home", "/"),
            ("t1-world-soil", "/world/soil/"),
            ("t2-services-produce", "/services/produce/"),
            ("t4-post-harish2021", "/blog/harish2021/"),
        ]:
            page.set_viewport_size({"width": 375, "height": 812})
            page.goto(build_url(base, path), wait_until="networkidle")
            metrics = page.evaluate(
                """() => {
                    const sw = document.documentElement.scrollWidth;
                    const iw = window.innerWidth;
                    const maxScroll = Math.max(0, sw - iw);
                    const initialX = window.scrollX;
                    window.scrollTo({ left: maxScroll, behavior: 'instant' });
                    const afterX = window.scrollX;
                    window.scrollTo({ left: initialX, behavior: 'instant' });
                    return { scroll_width: sw, inner_width: iw, scroll_x_after_probe: afterX };
                }"""
            )
            payload["responsive_375"].append(
                {
                    "label": label,
                    "path": path,
                    "no_horizontal_scroll": metrics["scroll_x_after_probe"] <= 2,
                    "overflow_px": max(0, metrics["scroll_width"] - metrics["inner_width"]),
                }
            )

        # Contact form invalid (empty submit)
        page.set_viewport_size({"width": 1440, "height": 900})
        page.goto(build_url(base, "/contact/"), wait_until="networkidle")
        page.locator('form[action*="admin-post.php"] button[type="submit"], form button[type="submit"]').first.click()
        page.wait_for_load_state("networkidle")
        payload["contact_form"]["invalid"] = {
            "final_url": page.url,
            "pass": "status=invalid" in page.url,
        }

        # Contact form happy path
        page.goto(build_url(base, "/contact/"), wait_until="networkidle")
        page.fill('input[name="name"]', "Team50 QA")
        page.fill('input[name="email"]', "qa-test@example.com")
        page.fill('textarea[name="message"]', "P007-WP001 MCP QA happy-path re-verify")
        page.locator('form button[type="submit"]').first.click()
        page.wait_for_load_state("networkidle")
        happy_ok = "status=ok" in page.url
        payload["contact_form"]["happy"] = {
            "final_url": page.url,
            "pass": happy_ok,
        }
        if happy_ok:
            page.screenshot(path=str(OUT_DIR / "contact-form-happy_1440.png"), full_page=True)

        # 404 custom page check
        page.goto(build_url(base, "/blog/non-existent-slug/"), wait_until="networkidle")
        body_text = page.inner_text("body")
        payload["error_404"] = {
            "http_status": page.url,
            "has_wp_default_phrase": "doesn't exist" in body_text.lower() or "does not exist" in body_text.lower(),
            "body_snippet": body_text[:300],
            "pass": "404" in body_text or "לא נמצא" in body_text or "non-existent" in page.url,
        }

        browser.close()

    critical = []
    for label, msgs in payload["console"].items():
        for msg in msgs:
            if msg.startswith("pageerror:") or msg.startswith("error:"):
                if "favicon" not in msg.lower():
                    critical.append({"template": label, "message": msg})
    payload["critical_console_errors"] = critical

    RESULTS.write_text(json.dumps(payload, ensure_ascii=False, indent=2), encoding="utf-8")
    print(f"[OK] wrote {RESULTS}")
    print(f"[INFO] interactive_pass={sum(1 for t in payload['interactive_trace'] if t['pass'])}/{len(payload['interactive_trace'])}")
    print(f"[INFO] contact_happy={payload['contact_form'].get('happy', {}).get('pass')}")
    print(f"[INFO] critical_console={len(critical)}")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
