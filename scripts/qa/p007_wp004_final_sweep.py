#!/usr/bin/env python3
"""Team 50 — P007-WP004 final pre-cutover QA sweep (Wave 4a)."""
from __future__ import annotations

import json
import re
import subprocess
import sys
import urllib.parse
from datetime import datetime, timezone
from pathlib import Path

REPO = Path(__file__).resolve().parent.parent.parent
sys.path.insert(0, str(REPO / "scripts" / "migration"))
from _lib import dev_auth, dev_rest_base, dev_site_url, load_envs, rest_request  # noqa: E402

IMG_RE = re.compile(r"""<img[^>]+src=["']([^"']+)["']""", re.I)
WORLDS = ["soil", "know", "code"]
STATIC = ["/about/", "/about/heritage/", "/contact/"]
DELETED_POST_SLUGS = ["harish2021"]
WAVE4_POSTS = ["nimrod-context-book", "agents-os"]
SFA_SERVICE_PATHS = ["/services/sfa/", "/services/seed-t7-sfa/"]


def curl_status(url: str, *, method: str = "GET") -> int:
    cmd = [
        "curl",
        "-sS",
        "-o",
        "/dev/null",
        "-w",
        "%{http_code}",
        "--max-time",
        "25",
        "-X",
        method,
        "-A",
        "team-50-wp004/1.0",
        url,
    ]
    proc = subprocess.run(cmd, capture_output=True, text=True, check=False)
    return int(proc.stdout.strip() or "0") if proc.stdout.strip().isdigit() else 0


def curl_body(url: str) -> str:
    cmd = ["curl", "-sS", "-L", "--max-time", "25", "-A", "team-50-wp004/1.0", url]
    proc = subprocess.run(cmd, capture_output=True, text=True, check=False)
    return proc.stdout


def fetch_paged(endpoint: str, *, extra: str = "") -> list[dict]:
    user, password = dev_auth()
    base = dev_rest_base()
    page = 1
    rows: list[dict] = []
    while True:
        url = f"{base}{endpoint}?per_page=100&page={page}&status=publish{extra}"
        data = rest_request("GET", url, user, password, retries=3)
        if not data:
            break
        rows.extend(data)
        if len(data) < 100:
            break
        page += 1
    return rows


def count_placeholder_markers(posts: list[dict], base: str) -> list[dict]:
    rows = []
    for post in posts:
        slug = post.get("slug", "")
        body = curl_body(f"{base}/blog/{urllib.parse.quote(slug)}/")
        has_marker = 'data-nb-placeholder="true"' in body
        if has_marker:
            rows.append({"id": post.get("id"), "slug": slug})
    return rows


def main() -> int:
    load_envs()
    base = dev_site_url().rstrip("/")
    today = datetime.now(timezone.utc).date().isoformat()
    report: dict = {
        "generated_at": datetime.now(timezone.utc).isoformat(),
        "team": "team_50",
        "wp_id": "NB-S002-P007-WP004",
        "base": base,
        "checks": {},
    }

    home_html = curl_body(f"{base}/")
    report["checks"]["QA-1"] = {
        "status": curl_status(f"{base}/"),
        "has_unless": "Unless" in home_html,
        "world_links": all(f"/world/{w}/" in home_html for w in WORLDS),
        "has_project_sfa_link": "/project/sfa/" in home_html or "project/sfa" in home_html,
        "has_sfa_external_link": "sfa.nimrod.bio" in home_html,
        "has_legacy_services_sfa_link": "/services/sfa/" in home_html or "services/sfa" in home_html,
        "pass": all(
            [
                curl_status(f"{base}/") == 200,
                "Unless" in home_html,
                all(f"/world/{w}/" in home_html for w in WORLDS),
                ("/project/sfa/" in home_html or "project/sfa" in home_html),
                "sfa.nimrod.bio" in home_html,
                not ("/services/sfa/" in home_html or "services/sfa" in home_html),
            ]
        ),
    }

    qa2 = []
    for w in WORLDS:
        code = curl_status(f"{base}/world/{w}/")
        body = curl_body(f"{base}/world/{w}/")
        qa2.append({"world": w, "status": code, "pass": code == 200 and len(body) > 400})
    report["checks"]["QA-2"] = {"worlds": qa2, "pass": all(x["pass"] for x in qa2)}

    services = fetch_paged("/services", extra="&_fields=id,slug,content")
    projects = fetch_paged("/projects", extra="&_fields=id,slug,content")
    posts = fetch_paged("/posts", extra="&_fields=id,slug,meta,content")
    report["entity_counts"] = {
        "services": len(services),
        "projects": len(projects),
        "posts": len(posts),
    }

    svc_rows = [
        {"slug": s["slug"], "status": curl_status(f"{base}/services/{s['slug']}/")}
        for s in services
    ]
    sfa_svc = [{"path": p, "status": curl_status(f"{base}{p}")} for p in SFA_SERVICE_PATHS]
    report["checks"]["QA-3"] = {
        "services": svc_rows,
        "legacy_sfa_service_urls": sfa_svc,
        "count": len(services),
        "pass": len(services) == 10 and all(x["status"] == 200 for x in svc_rows)
        and all(x["status"] == 404 for x in sfa_svc),
    }

    proj_rows = [
        {"slug": p["slug"], "status": curl_status(f"{base}/project/{p['slug']}/")}
        for p in projects
    ]
    sfa_project = next((p for p in projects if p.get("slug") == "sfa"), None)
    report["checks"]["QA-4"] = {
        "projects": proj_rows,
        "count": len(projects),
        "sfa_project_live": bool(sfa_project),
        "pass": len(projects) == 6 and all(x["status"] == 200 for x in proj_rows) and bool(sfa_project),
    }

    migrated = [p for p in posts if (p.get("meta") or {}).get("_nb_seed") == "v200-migrated"]
    sample_posts = migrated[:10] + [p for p in posts if p.get("slug") in WAVE4_POSTS]
    tested = []
    for p in sample_posts:
        link = p.get("link") or f"{base}/blog/{p.get('slug', '')}/"
        code = curl_status(link)
        tested.append({"slug": p.get("slug"), "link": link, "status": code, "pass": code == 200})
    deleted = [{"slug": s, "status": curl_status(f"{base}/blog/{s}/")} for s in DELETED_POST_SLUGS]
    report["checks"]["QA-5"] = {
        "migrated_count": len(migrated),
        "tested": tested,
        "deleted_posts": deleted,
        "pass": all(t["pass"] for t in tested) and all(d["status"] == 404 for d in deleted),
    }

    blog_html = curl_body(f"{base}/blog/")
    report["checks"]["QA-6"] = {
        "status": curl_status(f"{base}/blog/"),
        "flow_items": blog_html.count("flow-item"),
        "rest_post_count": len(posts),
        "pass": curl_status(f"{base}/blog/") == 200 and len(posts) == 33,
    }

    static = [{"path": p, "status": curl_status(f"{base}{p}")} for p in STATIC]
    report["checks"]["QA-7"] = {"pages": static, "pass": all(x["status"] == 200 for x in static)}

    marker_rows = count_placeholder_markers(posts, base)
    report["checks"]["QA-8"] = {
        "placeholder_marker_count": len(marker_rows),
        "posts_with_marker": marker_rows,
        "pass": len(marker_rows) == 0,
    }

    imgs: list[str] = []
    for p in migrated:
        rendered = (p.get("content") or {}).get("rendered", "")
        imgs.extend(IMG_RE.findall(rendered))
    unique = sorted(set(imgs))[:30]
    img_rows = []
    for u in unique:
        if u.startswith("/"):
            u = base + u
        if u.startswith("//"):
            u = "http:" + u
        st = curl_status(u)
        img_rows.append({"url": u, "status": st, "pass": st == 200})
    report["checks"]["QA-9"] = {
        "sample": len(img_rows),
        "http_200": sum(1 for r in img_rows if r["pass"]),
        "pass": img_rows and all(r["pass"] for r in img_rows),
    }

    surfaces = ["/", "/blog/", "/contact/", "/about/", "/world/soil/", "/services/produce/"]
    meta = []
    for path in surfaces:
        body = curl_body(f"{base}{path}")
        t = re.search(r"<title>(.*?)</title>", body, re.I | re.S)
        title = t.group(1).strip() if t else ""
        meta.append(
            {
                "path": path,
                "title": title,
                "has_unless": "unless" in title.lower(),
                "pass": bool(title),
            }
        )
    report["checks"]["QA-10"] = {
        "surfaces": meta,
        "pass": any(m["has_unless"] for m in meta) and all(m["pass"] for m in meta),
    }

    prior = json.loads(
        (REPO / "docs/qa_redirect_verification_2026-05-25.json").read_text(encoding="utf-8")
    )
    spot = []
    for row in prior["results"][:3]:
        st = curl_status(row["url_tested"])
        spot.append(
            {
                "url": row["url_tested"],
                "expected": row["expected"],
                "actual": st,
                "pass": str(st) == str(row["expected"]),
            }
        )
    report["checks"]["QA-11"] = {
        "prior_all_pass": prior.get("all_pass"),
        "spot_check": spot,
        "pass": bool(prior.get("all_pass")) and all(s["pass"] for s in spot),
    }

    user, password = dev_auth()
    sfa_rest = []
    for mid in (28, 44):
        try:
            rest_request("GET", f"{dev_rest_base()}/services/{mid}", user, password, retries=2)
            sfa_rest.append({"id": mid, "pass": False})
        except RuntimeError as exc:
            sfa_rest.append({"id": mid, "pass": "404" in str(exc)})
    report["checks"]["QA-13"] = {
        "service_count": len(services),
        "sfa_rest": sfa_rest,
        "pass": len(services) == 10 and all(x["pass"] for x in sfa_rest),
    }

    sm = curl_body(f"{base}/sitemap_index.xml")
    report["checks"]["QA-14"] = {
        "status": curl_status(f"{base}/sitemap_index.xml"),
        "has_post_sitemap": "post-sitemap" in sm,
        "has_media_sitemap": "media-sitemap" in sm,
        "pass": curl_status(f"{base}/sitemap_index.xml") == 200 and "post-sitemap" in sm,
    }

    tik = next((s for s in services if s.get("slug") == "tiktrack"), None)
    tik_body = (tik or {}).get("content", {}).get("rendered", "") if tik else ""
    report["checks"]["AT-F4"] = {
        "slug": "tiktrack",
        "status": curl_status(f"{base}/services/tiktrack/"),
        "has_tt_cta": "tt.nimrod.bio" in tik_body,
        "body_chars": len(re.sub(r"<[^>]+>", "", tik_body)),
        "pass": curl_status(f"{base}/services/tiktrack/") == 200 and "tt.nimrod.bio" in tik_body,
    }

    report["checks"]["AT-F5"] = report["checks"]["QA-1"]

    out = REPO / f"docs/qa_v200_wp004_final_sweep_{today}.json"
    out.write_text(json.dumps(report, ensure_ascii=False, indent=2), encoding="utf-8")
    fails = [k for k, v in report["checks"].items() if not v.get("pass")]
    print(json.dumps({"written": str(out), "entity_counts": report["entity_counts"], "fails": fails}))
    return 0 if not fails else 1


if __name__ == "__main__":
    raise SystemExit(main())
