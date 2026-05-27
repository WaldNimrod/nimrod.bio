#!/usr/bin/env python3
"""Seed P006-WP001 placeholder posts via WP REST.

Creates 11 blog posts with `_nb_placeholder=true` marker per LOD400 v1.0.1
§3.2 table. Each post is a placeholder shell — team_00 will fill the body
before COMPLETION_CONTENT_PHASE.

Run:
    python3 scripts/seed_wp006_p006_wp001_placeholders.py

Required env (loaded from .env.upress.dev):
    WP_REST_BASE_URL   e.g. https://nimrod-bio-2026.s887.upress.link/wp-json/
    WP_REST_USER       e.g. agent
    WP_REST_APP_PASSWORD  WP Application Password
"""
from __future__ import annotations

import base64
import json
import os
import sys
import ssl
import urllib.error
import urllib.parse
import urllib.request
from pathlib import Path

REPO = Path(__file__).resolve().parent.parent
ENV = REPO / ".env.upress.dev"

# LOD400 v1.0.1 §3.2 — 11 placeholder posts (team_00 corrections applied)
SEEDS = [
    {
        "slug": "agents-os",
        "title": "Agents-OS — מסגרת ממשל לסוכנים",
        "worlds": ["code", "know"],
        "flow_style": "feature",
        "summary": "Agents-OS היא מתודולוגיה למיקרו-ניהול של צוותי סוכני AI — מי כותב, מי מאשר, איך עוברים גייטים.",
        "second": "כרגע במצב L0 (lean governance) · מקור אמת ב-_aos/, אבל זז בכיוון L2 (DB-as-SSoT).",
    },
    {
        "slug": "eyal-amit-2026",
        "title": "אייל עמית — אתר 2026",
        "worlds": ["code"],
        "flow_style": "feature",
        "summary": "אתר 2026 לאייל עמית — WordPress פעיל, custom theme.",
        "second": "פרויקט פעיל · אחזקה שוטפת.",
    },
    {
        "slug": "israel-microgreens",
        "title": "Israel Microgreens — מכולה הידרופונית (תכנון + חקלאות)",
        "worlds": ["soil", "know", "code"],
        "flow_style": "lead",
        "summary": "פרויקט תכנון + הקמת מכולה הידרופונית לגידול microgreens באיכות מסעדנית. שילוב של הנדסת מערכת, חקלאות מבוקרת, ופרוטוקול תיעוד.",
        "second": "סטטוס: בתכנון מתקדם · החלטות תכנוניות מתועדות · נקודות חיבור ל-SFA עתידי.",
    },
    {
        "slug": "shaked-wg-agent",
        "title": "Shaked WG — סוכן חיפוש בזל",
        "worlds": ["code"],
        "flow_style": "feature",
        "summary": "סוכן חיפוש WhatsApp/web ייעודי לאוסף בזל. מבוסס Claude API + RAG.",
        "second": "פעיל · משמש ככלי משלים לקבוצת בזל.",
    },
    {
        "slug": "smallfarmsagents",
        "title": "SmallFarmsAgents — מערכת קהילתית לחווה אורגנית",
        "worlds": ["soil", "know", "code"],
        "flow_style": "lead",
        "summary": "מערכת קהילתית לניהול חווה אורגנית + בסיס ידע פתוח לחקלאים וגננים. כולל סוכן AI שאלות-תשובות, יומן שטח, ולוח גידולים.",
        "second": "סטטוס: סמוך ל-go-live (M7) · admin פנימי פעיל · public page בתכנון.",
    },
    {
        "slug": "tiktrack-phoenix",
        "title": "TikTrack Phoenix",
        "worlds": ["code"],
        "flow_style": "brief",
        "summary": "מערכת מעקב תיק השקעות פיננסי — SaaS multi-user.",
        "second": "סטטוס: staging פעיל · production cutover עתידי.",
    },
    {
        "slug": "agros-insite",
        "title": "Agros Insite",
        "worlds": ["soil"],
        "flow_style": "feature",
        "summary": "פלטפורמה חקלאית — תכנון, מעקב, וניהול נתוני שטח.",
        "second": "פרויקט פעיל · קוד פתוח · נמצא בפיתוח.",
    },
    {
        "slug": "capra-mio",
        "title": "Capra Mio — סוכן הפלגה",
        "worlds": ["code"],
        "flow_style": "feature",
        "summary": "סוכן AI ייעודי לתכנון מסלולי הפלגה — מזג אוויר, נמלים, צוות, ציוד.",
        "second": "סטטוס: vision · v0.1 בתכנון.",
    },
    {
        "slug": "אנטרופיה",
        "title": "אנטרופיה",
        "worlds": ["know"],
        "flow_style": "typo",
        "summary": "אנטרופיה כעקרון מארגן בעבודה ובחיים.",
        "second": "פלייסהולדר · ימולא בפסקאות הגותיות לפני cutover.",
    },
    {
        "slug": "אלה-אם-unless",
        "title": "אלה אם — Unless",
        "worlds": ["code", "know"],
        "flow_style": "typo",
        "summary": "Unless כתאי המוצא של הסיפור. בלוגיקת התנאי של חיים — else if.",
        "second": "פלייסהולדר · ימולא לפני cutover.",
    },
    {
        "slug": "back-to-mud",
        "title": "Back to Mud (placeholder)",
        "worlds": ["soil"],
        "flow_style": "brief",
        "summary": "חזרה לבוץ — הקישור היה מקושר מהדף הראשי. נוצר כפלייסהולדר כדי לסגור את ה-404.",
        "second": "team_00 ימלא את התוכן ויתכן ישנה את הכותרת.",
    },
]

PLACEHOLDER_HTML_TEMPLATE = """<!-- nb-content-phase-001 placeholder · v1.0 · 2026-05-26 -->
<!-- replace_before: COMPLETION_CONTENT_PHASE_*.md signing -->
<div class="placeholder-notice" data-nb-placeholder="true" style="border-right:4px solid #c33; padding:.5rem 1rem; background:#fff8f0; margin-bottom:1.5rem;">
  <strong>פלייסהולדר —</strong> פוסט זה ימולא בתוכן מלא לפני cutover. תוכן זמני נגזר מ-AOS metadata.
</div>

<p>{summary}</p>

<p>{second}</p>

<!-- TODO checklist for team_00 -->
<ul class="nb-placeholder-todo">
  <li>☐ פסקה ראשונה — הקשר אישי</li>
  <li>☐ פסקה שנייה — מה הפרויקט עושה</li>
  <li>☐ פסקה שלישית — איפה זה היום</li>
  <li>☐ תמונה ראשית</li>
  <li>☐ cross-links לפוסטים אחרים</li>
</ul>
"""


def load_env(path: Path) -> None:
    if not path.exists():
        raise SystemExit(f"{path} missing — request from team_00 (gitignored)")
    for raw in path.read_text(encoding="utf-8").splitlines():
        line = raw.strip()
        if not line or line.startswith("#") or "=" not in line:
            continue
        key, value = line.split("=", 1)
        os.environ[key.strip()] = value.strip().strip("'\"")


def _ssl_ctx() -> ssl.SSLContext | None:
    """Return an unverified SSL context when WP_REST_INSECURE=1 is set.

    Used by team_99 from waldhomeserver to route via PLAT-synth IPv6 (broken
    CLAT on host) where the cert chain on the synthesized address path is
    not the canonical upress cert. Mac-side execution does not need this.
    """
    if os.environ.get("WP_REST_INSECURE") == "1":
        return ssl._create_unverified_context()
    return None


def req(method: str, url: str, user: str, password: str, payload: dict | None = None) -> dict:
    token = base64.b64encode(f"{user}:{password}".encode()).decode()
    headers = {
        "Accept": "application/json",
        "Authorization": f"Basic {token}",
    }
    data = None
    if payload is not None:
        data = json.dumps(payload, ensure_ascii=False).encode("utf-8")
        headers["Content-Type"] = "application/json"
    request = urllib.request.Request(url, data=data, headers=headers, method=method)
    ctx = _ssl_ctx()
    try:
        with urllib.request.urlopen(request, timeout=60, context=ctx) as resp:
            body = resp.read().decode("utf-8")
            return json.loads(body) if body else {}
    except urllib.error.HTTPError as exc:
        detail = exc.read().decode("utf-8", errors="replace")
        raise RuntimeError(f"{method} {url} -> HTTP {exc.code}: {detail}") from exc


def term_ids(base: str, user: str, password: str, taxonomy: str) -> dict[str, int]:
    data = req("GET", f"{base}/wp/v2/{taxonomy}?per_page=100", user, password)
    return {t["slug"]: t["id"] for t in data}


def find_post(base: str, user: str, password: str, slug: str) -> dict | None:
    items = req(
        "GET",
        f"{base}/wp/v2/posts?slug={urllib.parse.quote(slug, safe='')}&status=any",
        user,
        password,
    )
    return items[0] if items else None


def main() -> int:
    load_env(ENV)
    base = os.environ["WP_REST_BASE_URL"].rstrip("/")
    if base.endswith("/wp/v2"):
        base = base[: -len("/wp/v2")]
    user = os.environ["WP_REST_USER"]
    password = os.environ["WP_REST_APP_PASSWORD"]

    world_map = term_ids(base, user, password, "world")
    flow_map = term_ids(base, user, password, "flow_style")

    results: list[dict] = []
    for seed in SEEDS:
        slug = seed["slug"]
        worlds = [world_map[w] for w in seed["worlds"]]
        flow = flow_map[seed["flow_style"]]
        content = PLACEHOLDER_HTML_TEMPLATE.format(
            summary=seed["summary"],
            second=seed["second"],
        )
        payload = {
            "title": seed["title"],
            "slug": slug,
            "status": "publish",
            "content": content,
            "world": worlds,
            "flow_style": [flow],
            "meta": {"_nb_placeholder": True},
        }
        existing = find_post(base, user, password, slug)
        if existing:
            url = f"{base}/wp/v2/posts/{existing['id']}"
            method = "POST"
            action = "updated"
        else:
            url = f"{base}/wp/v2/posts"
            method = "POST"
            action = "created"
        out = req(method, url, user, password, payload)
        results.append(
            {
                "slug": slug,
                "id": out.get("id"),
                "link": out.get("link"),
                "action": action,
            }
        )
        print(f"  {action}: slug={slug} id={out.get('id')} link={out.get('link')}")

    print()
    print(f"summary: {len(results)} posts processed.")
    summary_path = REPO / "_COMMUNICATION" / "team_110" / "p006_wp001_post_creates_result.json"
    summary_path.parent.mkdir(parents=True, exist_ok=True)
    summary_path.write_text(json.dumps(results, indent=2, ensure_ascii=False), encoding="utf-8")
    print(f"summary written: {summary_path}")
    return 0


if __name__ == "__main__":
    sys.exit(main())
