#!/usr/bin/env python3
"""Seed WP004 blog posts via REST."""
from __future__ import annotations

import json
import os
import sys
import urllib.error
import urllib.request
from pathlib import Path

REPO = Path(__file__).resolve().parent.parent
ENV = REPO / ".env.upress.dev"

SEEDS = [
    {
        "title": "מבט אחורה — שורש אחד",
        "slug": "mabat-achorah-shoresh-echad",
        "excerpt": "לא חזרה, מטמורפוזה. שורש אחד, שלוש זרועות. Unless.",
        "content": (
            "<p>פתיחה על שורש אחד ושלוש זרועות.</p>"
            '<h2 id="why-closed"><span class="num">01</span>למה הגינה נסגרה</h2>'
            "<p>הסיבה הייתה החלפת קנה מידה.</p>"
            '<h2 id="what-stays"><span class="num">02</span>מה נשאר</h2>'
            "<p>החקלאות לא נגמרה.</p>"
            '<h2 id="unless"><span class="num">03</span>Unless</h2>'
            "<p>העולם הוא כזה — אלא אם כן.</p>"
        ),
        "worlds": ["soil", "know", "code"],
        "flow_style": "lead",
        "read": "14 דק׳",
        "image_subject": "אדמה · ידיים",
    },
    {
        "title": "מדריך מהיר לחממה הידרופונית",
        "slug": "madrikh-mahir-chamama",
        "excerpt": "NFT, EC, pH — מה שצריך לפני שמתחילים.",
        "content": "<p>מדריך קצר לחממה ביתית-מקצועית.</p>",
        "worlds": ["soil"],
        "flow_style": "wide",
        "read": "8 דק׳",
        "image_subject": "חממה · NFT",
    },
    {
        "title": "SFA — קוד שמסייע לחקלאים",
        "slug": "sfa-kod-shemsaay",
        "excerpt": "ארכיטקטורה ראשונית לסוכן AI חקלאי.",
        "content": "<p>SFA v0.1 — MCP, לא פלטפורמה.</p>",
        "worlds": ["code"],
        "flow_style": "tall",
        "read": "16 דק׳",
        "image_subject": "SFA · סקיצה",
    },
    {
        "title": "ברוכים הבאים ל-nimrod.bio",
        "slug": "nimrod-bio-welcome",
        "excerpt": "אתר חדש, שורש ישן.",
        "content": "<p>ברוכים הבאים לגרסת V200.</p>",
        "worlds": ["soil"],
        "flow_style": "brief",
        "read": "3 דק׳",
        "image_subject": "nimrod.bio",
    },
]


def load_env(path: Path) -> None:
    if not path.exists():
        return
    for raw in path.read_text(encoding="utf-8").splitlines():
        line = raw.strip()
        if not line or line.startswith("#") or "=" not in line:
            continue
        key, value = line.split("=", 1)
        value = value.strip().strip("'\"")
        os.environ[key.strip()] = value


def request(method: str, url: str, user: str, password: str, payload: dict | None = None) -> dict:
    data = None
    headers = {"Accept": "application/json"}
    if payload is not None:
        data = json.dumps(payload, ensure_ascii=False).encode("utf-8")
        headers["Content-Type"] = "application/json"
    req = urllib.request.Request(url, data=data, headers=headers, method=method)
    token = urllib.request.HTTPBasicAuthHandler()
    import base64

    cred = base64.b64encode(f"{user}:{password}".encode()).decode()
    req.add_header("Authorization", f"Basic {cred}")
    with urllib.request.urlopen(req, timeout=30) as resp:
        body = resp.read().decode("utf-8")
        return json.loads(body) if body else {}


def term_id(base: str, user: str, password: str, taxonomy: str, slug: str) -> int:
    data = request("GET", f"{base}/{taxonomy}?slug={slug}", user, password)
    if not data:
        raise RuntimeError(f"Missing term {taxonomy}/{slug}")
    return int(data[0]["id"])


def main() -> int:
    load_env(ENV)
    base = os.environ["WP_REST_BASE_URL"].rstrip("/")
    if not base.endswith("/wp/v2"):
        base = base + "/wp/v2"
    user = os.environ["WP_REST_USER"]
    password = os.environ["WP_REST_APP_PASSWORD"]

    world_ids = {slug: term_id(base, user, password, "world", slug) for slug in ("soil", "know", "code")}
    flow_ids = {
        slug: term_id(base, user, password, "flow_style", slug)
        for slug in ("lead", "wide", "tall", "brief")
    }

    for seed in SEEDS:
        existing = request("GET", f"{base}/posts?slug={seed['slug']}&status=any", user, password)
        if existing:
            request("DELETE", f"{base}/posts/{existing[0]['id']}?force=true", user, password)
        payload = {
            "title": seed["title"],
            "slug": seed["slug"],
            "status": "publish",
            "excerpt": seed["excerpt"],
            "content": seed["content"],
            "world": [world_ids[s] for s in seed["worlds"]],
            "flow_style": [flow_ids[seed["flow_style"]]],
            "meta": {
                "_nb_seed": "v200",
                "_nb_read_time": seed["read"],
                "_nb_image_subject": seed["image_subject"],
            },
        }
        created = request("POST", f"{base}/posts", user, password, payload)
        print(f"created id={created['id']} slug={created['slug']} flow={seed['flow_style']}")
    return 0


if __name__ == "__main__":
    try:
        raise SystemExit(main())
    except urllib.error.HTTPError as exc:
        print(exc.read().decode(), file=sys.stderr)
        raise SystemExit(1)
