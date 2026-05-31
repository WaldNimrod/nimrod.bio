#!/usr/bin/env python3
"""Team 50 — V200 full-system QA automation (mandate 2026-06-01)."""
from __future__ import annotations

import json
import re
import subprocess
import sys
from datetime import datetime, timezone
from html.parser import HTMLParser
from pathlib import Path
from urllib.parse import urljoin, urlparse

REPO = Path(__file__).resolve().parents[3]
EVIDENCE = Path(__file__).resolve().parent / "evidence"
sys.path.insert(0, str(REPO / "scripts" / "migration"))

BASE_HTTPS = "https://nimrod-bio-2026.s887.upress.link"
BASE_HTTP = "http://nimrod-bio-2026.s887.upress.link"

PAGES: list[tuple[str, str]] = [
    ("home", "/"),
    ("about", "/about/"),
    ("about-heritage", "/about/heritage/"),
    ("contact", "/contact/"),
    ("world-soil", "/world/soil/"),
    ("world-know", "/world/know/"),
    ("world-code", "/world/code/"),
    ("project-sfa", "/project/sfa/"),
    ("project-tiktrack", "/project/tiktrack/"),
    ("project-hagina", "/project/hagina-shel-nimrod/"),
    ("project-greenhouse", "/project/rest-x-greenhouse/"),
    ("service-bcs", "/services/bcs/"),
]

LOCK_TERMS: list[tuple[str, re.Pattern[str]]] = [
    ("Micha", re.compile(r"\bMicha\b", re.I)),
    ("Micha OS", re.compile(r"Micha\s+OS", re.I)),
    ("אנטרופיה", re.compile(r"אנטרופיה")),
    ("נגנטרופיה", re.compile(r"נגנטרופיה")),
    ("רקורסיה", re.compile(r"רקורסיה")),
    ("CDIP", re.compile(r"\bCDIP\b", re.I)),
    ("Cross-Domain Isomorphism", re.compile(r"Cross-Domain\s+Isomorphism", re.I)),
    ("פרמקלצר", re.compile(r"פרמקלצר")),
    ("3×", re.compile(r"3×|3\s*[×xX]\s*")),
    ("קואופרטיב", re.compile(r"קואופרטיב")),
    ("קומון", re.compile(r"קומון")),
    ("TBC", re.compile(r"\bTBC\b")),
]

EXTERNAL_LINKS = [
    ("sfa", "https://sfa.nimrod.bio/"),
    ("tiktrack", "https://tt.nimrod.bio"),
    ("whatsapp", "https://wa.me/972547776770"),
    ("maps", "https://maps.app.goo.gl/8ySCEcFw3B8hXtnP6"),
]

COPY_ANCHORS: dict[str, list[str]] = {
    "home": ["Unless", "אדמה", "sfa.nimrod.bio", "/project/rest-x-greenhouse/", "/services/bcs/"],
    "about": ["על נמרוד", "חממה"],
    "about-heritage": ["הגינה", "נמרוד"],
    "contact": ["wa.me/972547776770", "maps.app.goo.gl", "nimrod@nimrod.bio"],
    "world-soil": ["אדמה", "פעילויות"],
    "world-know": ["ייעוץ", "הוראה"],
    "world-code": ["דיגיטל", "sfa.nimrod.bio"],
    "project-sfa": ["קהילה", "SFA"],
    "project-tiktrack": ["TikTrack", "פיילוט"],
    "project-hagina": ["הגינה"],
    "project-greenhouse": ["מסעדה", "חממה"],
    "service-bcs": ["BCS", "Farm-Y"],
}

NEGATIVE_COPY = {
    "home": ["restaurant-supply", "קואופרטיב", "3×"],
    "world-soil": ["3×", "אינסטנסים", "CDIP"],
    "world-know": ["3×", "אינסטנסים", "CDIP"],
    "world-code": ["3×", "אינסטנסים", "tt.nimrod.bio"],
}


class TextExtractor(HTMLParser):
    """Collect visible/meta text; skip script/style."""

    def __init__(self) -> None:
        super().__init__()
        self.parts: list[str] = []
        self._skip = 0

    def handle_starttag(self, tag: str, attrs: list[tuple[str, str | None]]) -> None:
        if tag in ("script", "style", "noscript"):
            self._skip += 1
            return
        attr = dict(attrs)
        for key in ("alt", "title", "aria-label", "content"):
            val = attr.get(key)
            if val:
                self.parts.append(val)

    def handle_endtag(self, tag: str) -> None:
        if tag in ("script", "style", "noscript") and self._skip:
            self._skip -= 1

    def handle_data(self, data: str) -> None:
        if not self._skip and data.strip():
            self.parts.append(data.strip())


def curl_fetch(url: str, *, method: str = "GET") -> tuple[int, str, dict[str, str]]:
    cmd = [
        "curl",
        "-k",
        "-sS",
        "-L",
        "--max-time",
        "30",
        "-A",
        "team-50-qa-v200/1.0",
        "-w",
        "\n__HTTP_CODE__:%{http_code}",
        "-X",
        method,
        url,
    ]
    proc = subprocess.run(cmd, capture_output=True, text=True, check=False)
    body = proc.stdout
    code = 0
    if "__HTTP_CODE__:" in body:
        body, _, tail = body.rpartition("\n__HTTP_CODE__:")
        code = int(tail.strip() or "0")
    return code, body, {}


def curl_head(url: str) -> int:
    cmd = [
        "curl",
        "-k",
        "-sS",
        "-o",
        "/dev/null",
        "-w",
        "%{http_code}",
        "--max-time",
        "20",
        "-A",
        "team-50-qa-v200/1.0",
        url,
    ]
    proc = subprocess.run(cmd, capture_output=True, text=True, check=False)
    raw = proc.stdout.strip()
    return int(raw) if raw.isdigit() else 0


def extract_theme_version(html: str) -> str | None:
    m = re.search(r'nimrod-bio-2026[^"\']*ver=([\d.]+)', html)
    return m.group(1) if m else None


def strip_html_for_lock_scan(html: str) -> str:
    """Remove script/style blocks and HTML comments before lock scan."""
    cleaned = re.sub(r"<!--.*?-->", " ", html, flags=re.S)
    cleaned = re.sub(r"<script[^>]*>.*?</script>", " ", cleaned, flags=re.S | re.I)
    cleaned = re.sub(r"<style[^>]*>.*?</style>", " ", cleaned, flags=re.S | re.I)
    parser = TextExtractor()
    parser.feed(cleaned)
    return " ".join(parser.parts)


def scan_locks(text: str) -> list[dict]:
    hits: list[dict] = []
    for name, pattern in LOCK_TERMS:
        for m in pattern.finditer(text):
            start = max(0, m.start() - 40)
            end = min(len(text), m.end() + 40)
            hits.append({"term": name, "context": text[start:end].replace("\n", " ")})
    return hits


def extract_links(html: str, base_url: str) -> tuple[list[str], list[str]]:
    host = urlparse(base_url).netloc
    internal: set[str] = set()
    external: set[str] = set()
    for m in re.finditer(r'''(?:href|src)=["']([^"']+)["']''', html, re.I):
        href = m.group(1).strip()
        if href.startswith(("#", "mailto:", "tel:", "javascript:")):
            continue
        absolute = urljoin(base_url, href)
        parsed = urlparse(absolute)
        if parsed.netloc == host or not parsed.netloc:
            path = parsed.path or "/"
            if not path.endswith("/") and "." not in path.split("/")[-1]:
                path += "/"
            internal.add(f"{parsed.scheme}://{parsed.netloc}{path}")
        else:
            external.add(absolute.split("#")[0])
    return sorted(internal), sorted(external)


def prefetch_pages() -> dict[str, tuple[int, str]]:
    cache: dict[str, tuple[int, str]] = {}
    for slug, path in PAGES:
        url = BASE_HTTPS + path
        code, html, _ = curl_fetch(url)
        cache[slug] = (code, html)
        print(f"[fetch] {slug} -> {code}", flush=True)
    return cache


def check_http_status(cache: dict[str, tuple[int, str]]) -> dict:
    rows = []
    theme_version = None
    for slug, path in PAGES:
        code, html = cache[slug]
        if theme_version is None:
            theme_version = extract_theme_version(html)
        rows.append({"slug": slug, "path": path, "url": BASE_HTTPS + path, "status": code, "pass": code == 200})
    return {
        "generated_at": datetime.now(timezone.utc).isoformat(),
        "base": BASE_HTTPS,
        "theme_version": theme_version,
        "pages": rows,
        "pass": all(r["pass"] for r in rows),
    }


def check_lock_scan(cache: dict[str, tuple[int, str]]) -> dict:
    page_results = []
    all_hits: list[dict] = []
    for slug, path in PAGES:
        _, html = cache[slug]
        text = strip_html_for_lock_scan(html)
        hits = scan_locks(text)
        for h in hits:
            h["slug"] = slug
            h["path"] = path
        all_hits.extend(hits)
        page_results.append({"slug": slug, "path": path, "hits": hits, "pass": len(hits) == 0})
    term_counts = {name: sum(1 for h in all_hits if h["term"] == name) for name, _ in LOCK_TERMS}
    return {
        "generated_at": datetime.now(timezone.utc).isoformat(),
        "pages": page_results,
        "term_counts": term_counts,
        "total_hits": len(all_hits),
        "hits": all_hits,
        "pass": len(all_hits) == 0,
    }


def check_external_links() -> dict:
    rows = []
    for name, url in EXTERNAL_LINKS:
        code = curl_head(url)
        rows.append({"name": name, "url": url, "status": code, "pass": 200 <= code < 400})
    return {
        "generated_at": datetime.now(timezone.utc).isoformat(),
        "links": rows,
        "pass": all(r["pass"] for r in rows),
    }


def check_internal_links(cache: dict[str, tuple[int, str]]) -> dict:
    """Check internal links extracted from the 12 mandate pages only (no recursive crawl)."""
    host = urlparse(BASE_HTTPS).netloc
    checked: dict[str, int] = {}
    broken: list[dict] = []
    seeds: set[str] = set()

    for slug, path in PAGES:
        _, html = cache[slug]
        url = BASE_HTTPS + path
        internal, _ = extract_links(html, url)
        for link in internal:
            parsed = urlparse(link)
            if parsed.netloc and parsed.netloc != host:
                continue
            # Skip blog post permalinks — out of mandate scope unless linked from a page.
            if "/blog/" in parsed.path and parsed.path not in ("/blog/", "/blog/page/"):
                continue
            seeds.add(link)

    for link in sorted(seeds):
        if link in checked:
            continue
        code = curl_head(link)
        checked[link] = code
        if code == 0 or code >= 400:
            broken.append({"url": link, "status": code})

    return {
        "generated_at": datetime.now(timezone.utc).isoformat(),
        "links_checked": len(checked),
        "scope": "mandate_12_pages_extracted_links",
        "broken_links": broken,
        "pass": len(broken) == 0,
    }


def check_copy_anchors(cache: dict[str, tuple[int, str]]) -> dict:
    rows = []
    for slug, path in PAGES:
        _, html = cache[slug]
        text = strip_html_for_lock_scan(html)
        anchors = COPY_ANCHORS.get(slug, [])
        missing = [a for a in anchors if a not in html and a not in text]
        negatives = NEGATIVE_COPY.get(slug, [])
        found_bad = [n for n in negatives if n in html or n in text]
        rows.append(
            {
                "slug": slug,
                "path": path,
                "anchors_expected": anchors,
                "anchors_missing": missing,
                "negative_found": found_bad,
                "pass": not missing and not found_bad,
            }
        )
    return {
        "generated_at": datetime.now(timezone.utc).isoformat(),
        "pages": rows,
        "pass": all(r["pass"] for r in rows),
    }


def check_known_open_items(cache: dict[str, tuple[int, str]]) -> dict:
    _, home_html = cache["home"]
    _, code_html = cache["world-code"]
    return {
        "generated_at": datetime.now(timezone.utc).isoformat(),
        "world_zero_activities": "0 פעילויות" in home_html or "0 פעילויות" in code_html,
        "sfa_hardcoded": "sfa.nimrod.bio" in home_html,
        "tiktrack_hardcoded": "tt.nimrod.bio" in code_html,
        "media_placeholder_markers": bool(re.search(r"placeholder|media-pending|data-nb-placeholder", home_html, re.I)),
    }


def check_contact_form(cache: dict[str, tuple[int, str]]) -> dict:
    _, html = cache["contact"]
    nonce_m = re.search(r'name="nb_contact_nonce"\s+value="([^"]+)"', html)
    nonce = nonce_m.group(1) if nonce_m else None

    admin_email = None
    try:
        from _lib import dev_auth, dev_rest_base, load_envs, rest_request  # noqa: E402

        load_envs()
        user, password = dev_auth()
        settings = rest_request("GET", dev_rest_base().replace("/wp/v2", "") + "/wp/v2/settings", user, password)
        admin_email = settings.get("email") or settings.get("admin_email")
    except Exception as exc:
        admin_email = f"error: {exc}"

    result: dict = {
        "generated_at": datetime.now(timezone.utc).isoformat(),
        "displayed_email": "nimrod@nimrod.bio" if "nimrod@nimrod.bio" in html else None,
        "admin_email": admin_email,
        "email_mismatch": admin_email not in (None, "nimrod@nimrod.bio") and not str(admin_email).startswith("error"),
        "nonce_found": bool(nonce),
    }

    if nonce:
        post_url = BASE_HTTPS + "/wp-admin/admin-post.php"
        data = (
            f"action=nb_contact_submit&nb_contact_nonce={nonce}"
            f"&name=Team50+QA&email=qa-v200-test%40example.com"
            f"&message=Team+50+V200+QA+automated+contact+form+test+2026-06-01+mandate+run."
        )
        cmd = [
            "curl",
            "-k",
            "-sS",
            "-D",
            "-",
            "-o",
            "/dev/null",
            "--max-time",
            "30",
            "-X",
            "POST",
            post_url,
            "-d",
            data,
        ]
        proc = subprocess.run(cmd, capture_output=True, text=True, check=False)
        headers = proc.stdout
        loc_m = re.search(r"Location:\s*(.+)", headers, re.I)
        location = loc_m.group(1).strip() if loc_m else None
        result["submit_status"] = "ok" if location and "status=ok" in location else "fail"
        result["redirect_location"] = location

        # Invalid submit
        bad_cmd = cmd[:-2] + ["-d", f"action=nb_contact_submit&nb_contact_nonce={nonce}&name=&email=bad&message=short"]
        bad_proc = subprocess.run(bad_cmd, capture_output=True, text=True, check=False)
        bad_loc = re.search(r"Location:\s*(.+)", bad_proc.stdout, re.I)
        result["invalid_redirect"] = bad_loc.group(1).strip() if bad_loc else None
        result["invalid_pass"] = bool(result["invalid_redirect"] and "status=invalid" in result["invalid_redirect"])

    result["pass"] = result.get("submit_status") == "ok" and result.get("invalid_pass", False)
    return result


def main() -> int:
    EVIDENCE.mkdir(parents=True, exist_ok=True)
    cache = prefetch_pages()
    checks = {
        "http_status": check_http_status(cache),
        "lock_scan": check_lock_scan(cache),
        "external_links": check_external_links(),
        "internal_links": check_internal_links(cache),
        "copy_anchors": check_copy_anchors(cache),
        "known_open_items": check_known_open_items(cache),
        "contact_form": check_contact_form(cache),
    }
    for name, payload in checks.items():
        out = EVIDENCE / f"{name}.json"
        out.write_text(json.dumps(payload, ensure_ascii=False, indent=2), encoding="utf-8")
        print(f"[{'OK' if payload.get('pass', True) else 'FAIL'}] wrote {out}")

    summary = {
        "generated_at": datetime.now(timezone.utc).isoformat(),
        "mandate": "MANDATE_TEAM_50_QA_VISUAL_2026-06-01_v1",
        "checks": {k: v.get("pass", True) for k, v in checks.items()},
    }
    (EVIDENCE / "summary.json").write_text(json.dumps(summary, ensure_ascii=False, indent=2), encoding="utf-8")
    failed = [k for k, v in summary["checks"].items() if not v]
    print(f"[SUMMARY] failed_checks={failed or 'none'}")
    return 1 if failed else 0


if __name__ == "__main__":
    raise SystemExit(main())
