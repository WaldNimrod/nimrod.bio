#!/usr/bin/env python3
"""Verify redirects/drops/keeps on dev site and emit JSON evidence."""
from __future__ import annotations

import json
import socket
import time
import urllib.error
import urllib.request
from datetime import datetime
from urllib.parse import quote, urljoin, urlparse

from _lib import VERIFICATION_DIR, load_decisions, load_default_envs, normalize_path


class NoRedirectHandler(urllib.request.HTTPRedirectHandler):
    def redirect_request(self, req, fp, code, msg, headers, newurl):  # type: ignore[override]
        return None


def request_once(url: str) -> tuple[int, str, str]:
    opener = urllib.request.build_opener(NoRedirectHandler())
    req = urllib.request.Request(
        url,
        headers={"User-Agent": "nimrod-bio-redirect-verify/1.0"},
        method="GET",
    )
    try:
        with opener.open(req, timeout=30) as resp:
            return int(resp.status), "", ""
    except urllib.error.HTTPError as exc:
        return int(exc.code), (exc.headers.get("Location") or ""), ""
    except urllib.error.URLError as exc:
        return 0, "", str(exc.reason)
    except TimeoutError:
        return 0, "", "timeout"
    except socket.timeout:
        return 0, "", "timeout"


def request_with_retries(url: str, retries: int = 2) -> tuple[int, str, str]:
    last = (0, "", "unknown")
    for attempt in range(retries + 1):
        code, location, error = request_once(url)
        last = (code, location, error)
        if code != 0:
            return last
        if attempt < retries:
            time.sleep(1.5)
    return last


def make_url(base: str, slug: str) -> str:
    encoded = quote(slug.strip("/"), safe="/")
    return urljoin(base.rstrip("/") + "/", encoded + "/")


def ends_with_expected(location: str, expected_path: str) -> bool:
    parsed = urlparse(location)
    loc_path = parsed.path if parsed.scheme else location
    return normalize_path(loc_path) == normalize_path(expected_path)


def main() -> int:
    load_default_envs()
    import os

    base = os.environ.get("UPRESS_DEV_URL_HTTP", "").rstrip("/")
    if not base:
        raise RuntimeError("UPRESS_DEV_URL_HTTP is required")

    payload = load_decisions()
    rows = payload.get("decisions", [])

    results: list[dict] = []
    summary = {
        "redirect": {"pass": 0, "total": 0},
        "drop": {"pass": 0, "total": 0},
        "keep": {"pass": 0, "total": 0},
    }

    for row in rows:
        decision = row.get("decision")
        if decision not in ("redirect", "drop", "keep"):
            continue
        summary[decision]["total"] += 1
        slug = row.get("slug", "")
        url = make_url(base, slug)
        code, location, net_error = request_with_retries(url, retries=2)

        ok = False
        expected = ""
        if decision == "redirect":
            expected = row.get("new_url") or ""
            ok = code == 301 and ends_with_expected(location, expected)
        elif decision == "drop":
            expected = "410"
            ok = code == 410
        elif decision == "keep":
            expected = "200"
            ok = code == 200

        if ok:
            summary[decision]["pass"] += 1

        results.append(
            {
                "id": row.get("id"),
                "type": row.get("type"),
                "slug": slug,
                "decision": decision,
                "url_tested": url,
                "expected": expected,
                "actual_status": code,
                "actual_location": location,
                "network_error": net_error,
                "pass": ok,
            }
        )

    all_pass = all(item["pass"] == item["total"] for item in summary.values())
    today = datetime.now().date().isoformat()
    out_path = VERIFICATION_DIR / f"redirect_verification_{today}.json"
    output = {
        "base_url": base,
        "generated_at": datetime.utcnow().replace(microsecond=0).isoformat() + "Z",
        "summary": summary,
        "all_pass": all_pass,
        "results": results,
    }
    out_path.write_text(json.dumps(output, ensure_ascii=False, indent=2), encoding="utf-8")

    print(json.dumps(output["summary"], ensure_ascii=False))
    print(f"[INFO] all_pass={all_pass}")
    print(f"[OK] wrote {out_path}")
    return 0 if all_pass else 1


if __name__ == "__main__":
    raise SystemExit(main())
