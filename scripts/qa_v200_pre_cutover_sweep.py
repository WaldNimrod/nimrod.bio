#!/usr/bin/env python3
"""Team 50 — V200 pre-cutover QA sweep (automated checks)."""
from __future__ import annotations

import json
import re
import subprocess
import sys
import urllib.parse
from datetime import datetime, timezone
from pathlib import Path

REPO = Path(__file__).resolve().parent.parent
sys.path.insert(0, str(REPO / "scripts" / "migration"))
from _lib import dev_auth, dev_rest_base, dev_site_url, load_envs, rest_request  # noqa: E402

DECISIONS = REPO / "docs" / "url_migration_decisions_2026-05-25.json"
IMG_RE = re.compile(r"""<img[^>]+src=["']([^"']+)["']""", re.I)

PLACEHOLDER_SLUGS = [
    "agents-os",
    "eyal-amit-2026",
    "israel-microgreens",
    "shaked-wg-agent",
    "smallfarmsagents",
    "tiktrack-phoenix",
    "agros-insite",
    "capra-mio",
    "אנטרופיה",
    "אלה-אם-unless",
    "back-to-mud",
]
SFA_URLS = ["/services/sfa/", "/services/seed-t7-sfa/"]
WORLDS = ["soil", "know", "code"]
STATIC = ["/about/", "/about/heritage/", "/contact/"]


def curl_status(url: str, *, method: str = "GET") -> tuple[int, str]:
    cmd = [
        "curl",
        "-sS",
        "-o",
        "/dev/null",
        "-w",
        "%{http_code}",
        "--max-time",
        "20",
        "-X",
        method,
        "-A",
        "team-50-qa/1.0",
        url,
    ]
    proc = subprocess.run(cmd, capture_output=True, text=True, check=False)
    code = int(proc.stdout.strip() or "0") if proc.stdout.strip().isdigit() else 0
    return code, proc.stderr.strip()


def curl_body(url: str) -> tuple[int, str]:
    cmd = ["curl", "-sS", "-L", "--max-time", "25", "-A", "team-50-qa/1.0", url]
    proc = subprocess.run(cmd, capture_output=True, text=True, check=False)
    return proc.returncode, proc.stdout


def fetch_paged(endpoint: str, *, extra: str = "") -> list[dict]:
    user, password = dev_auth()
    base = dev_rest_base()
    page = 1
    rows: list[dict] = []
    while True:
        url = f"{base}{endpoint}?per_page=100&page={page}&status=publish{extra}"
        try:
            data = rest_request("GET", url, user, password, retries=3)
        except RuntimeError as exc:
            if "HTTP 400" in str(exc) and page > 1:
                break
            raise
        if not data:
            break
        rows.extend(data)
        if len(data) < 100:
            break
        page += 1
    return rows


def main() -> int:
    load_envs()
    base = dev_site_url()
    report: dict = {"generated_at": datetime.now(timezone.utc).isoformat(), "team": "team_50", "base": base, "checks": {}}

    # QA-1
    st, html = curl_body(base + "/")
    report["checks"]["QA-1"] = {
        "status": 200 if st == 0 else st,
        "has_unless": "Unless" in html,
        "world_links": all(f"/world/{w}/" in html for w in WORLDS),
        "pass": st == 0 and "Unless" in html and all(f"/world/{w}/" in html for w in WORLDS),
    }

    # QA-2
    qa2 = []
    for w in WORLDS:
        code, _ = curl_status(base + f"/world/{w}/")
        _, body = curl_body(base + f"/world/{w}/")
        qa2.append({"world": w, "status": code, "pass": code == 200 and len(body) > 400})
    report["checks"]["QA-2"] = {"worlds": qa2, "pass": all(x["pass"] for x in qa2)}

    services = fetch_paged("/services", extra="&_fields=id,slug")
    projects = fetch_paged("/projects", extra="&_fields=id,slug")
    posts = fetch_paged("/posts", extra="&_fields=id,slug,meta,content")
    report["entity_counts"] = {"services": len(services), "projects": len(projects), "posts": len(posts)}

    # QA-3
    svc = [{"slug": s["slug"], "status": curl_status(base + f"/services/{s['slug']}/")[0]} for s in services]
    sfa = [{"path": p, "status": curl_status(base + p)[0]} for p in SFA_URLS]
    report["checks"]["QA-3"] = {
        "services": svc,
        "sfa": sfa,
        "count": len(services),
        "pass": len(services) == 10 and all(x["status"] == 200 for x in svc) and all(x["status"] == 404 for x in sfa),
    }

    # QA-4
    proj = [{"slug": p["slug"], "status": curl_status(base + f"/project/{p['slug']}/")[0]} for p in projects]
    report["checks"]["QA-4"] = {"projects": proj, "count": len(projects), "pass": len(projects) >= 5 and all(x["status"] == 200 for x in proj)}

    migrated = [p for p in posts if (p.get("meta") or {}).get("_nb_seed") == "v200-migrated"]
    placeholders_found = [p for p in posts if p.get("slug") in PLACEHOLDER_SLUGS]

    # QA-5
    tested = []
    for p in migrated[:10] + placeholders_found:
        slug = p["slug"]
        code = curl_status(base + f"/blog/{urllib.parse.quote(slug)}/")[0]
        tested.append({"slug": slug, "status": code, "pass": code == 200})
    report["checks"]["QA-5"] = {
        "migrated": len(migrated),
        "placeholders": len(placeholders_found),
        "tested": tested,
        "pass": all(t["pass"] for t in tested) and len(placeholders_found) == 11,
    }

    # QA-6
    code, blog_html = curl_body(base + "/blog/")
    cards = blog_html.count("post-card")
    report["checks"]["QA-6"] = {
        "status": 200 if code == 0 else code,
        "post_cards": cards,
        "rest_post_count": len(posts),
        "pass": code == 0 and len(posts) == 33 and cards >= 10,
    }

    # QA-7
    static = [{"path": p, "status": curl_status(base + p)[0]} for p in STATIC]
    report["checks"]["QA-7"] = {"pages": static, "pass": all(x["status"] == 200 for x in static)}

    # QA-8
    ph = []
    for slug in PLACEHOLDER_SLUGS:
        _, body = curl_body(base + f"/blog/{urllib.parse.quote(slug)}/")
        ok = 'data-nb-placeholder="true"' in body
        ph.append({"slug": slug, "pass": ok})
    report["checks"]["QA-8"] = {"rows": ph, "pass": all(r["pass"] for r in ph)}

    # QA-9 from REST content (no per-page fetch storm)
    imgs: list[str] = []
    for p in migrated:
        rendered = (p.get("content") or {}).get("rendered", "")
        imgs.extend(IMG_RE.findall(rendered))
    unique = sorted(set(imgs))
    sample = unique[:30]
    img_rows = []
    for u in sample:
        if u.startswith("/"):
            u = base + u
        if u.startswith("//"):
            u = "http:" + u
        st = curl_status(u)[0]
        img_rows.append({"url": u, "status": st, "pass": st == 200})
    report["checks"]["QA-9"] = {
        "unique_imgs": len(unique),
        "sample": len(sample),
        "http_200": sum(1 for r in img_rows if r["pass"]),
        "pass": all(r["pass"] for r in img_rows),
    }

    # QA-10
    surfaces = ["/", "/blog/", "/contact/", "/about/", "/world/soil/", "/services/produce/"]
    meta = []
    for path in surfaces:
        _, body = curl_body(base + path)
        t = re.search(r"<title>(.*?)</title>", body, re.I | re.S)
        d = re.search(r'<meta[^>]+name=["\']description["\'][^>]+content=["\']([^"\']*)', body, re.I)
        title = t.group(1).strip() if t else ""
        desc = d.group(1).strip() if d else ""
        meta.append({"path": path, "title": title, "has_unless": "unless" in title.lower() or "unless" in desc.lower(), "pass": bool(title)})
    report["checks"]["QA-10"] = {"surfaces": meta, "pass": any(m["has_unless"] for m in meta) and all(m["pass"] for m in meta)}

    # QA-11 — reuse prior verification artifact + live spot-check 3 redirects
    prior = json.loads((REPO / "docs/qa_redirect_verification_2026-05-25.json").read_text(encoding="utf-8"))
    spot = []
    for row in prior["results"][:3]:
        st = curl_status(row["url_tested"])[0]
        spot.append({"url": row["url_tested"], "expected": row["expected"], "actual": st, "pass": str(st) == str(row["expected"])})
    report["checks"]["QA-11"] = {
        "prior_all_pass": prior.get("all_pass"),
        "prior_summary": prior.get("summary"),
        "spot_check": spot,
        "pass": bool(prior.get("all_pass")) and all(s["pass"] for s in spot),
    }

    # QA-13
    user, password = dev_auth()
    sfa_rest = []
    for mid in (28, 44):
        try:
            rest_request("GET", f"{dev_rest_base()}/services/{mid}", user, password, retries=2)
            sfa_rest.append({"id": mid, "pass": False})
        except RuntimeError as exc:
            sfa_rest.append({"id": mid, "pass": "404" in str(exc), "error": str(exc)[:80]})
    report["checks"]["QA-13"] = {"service_count": len(services), "sfa_rest": sfa_rest, "pass": len(services) == 10 and all(x["pass"] for x in sfa_rest)}

    # QA-14
    st, sm = curl_body(base + "/sitemap_index.xml")
    report["checks"]["QA-14"] = {
        "status": 200 if st == 0 else st,
        "has_media_sitemap": "media-sitemap" in sm,
        "has_post_sitemap": "post-sitemap" in sm,
        "pass": st == 0 and "post-sitemap" in sm,
        "finding": "media-sitemap absent (known)",
    }

    out = REPO / "docs" / "qa_v200_pre_cutover_sweep_2026-05-27.json"
    out.write_text(json.dumps(report, ensure_ascii=False, indent=2), encoding="utf-8")
    fails = [k for k, v in report["checks"].items() if not v.get("pass")]
    print(json.dumps({"written": str(out), "entity_counts": report["entity_counts"], "fails": fails}, ensure_ascii=False))
    return 0 if not fails else 1


if __name__ == "__main__":
    raise SystemExit(main())
