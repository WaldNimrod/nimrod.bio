#!/usr/bin/env python3
"""Seed NB-S002-P003-WP003 instances via REST (_nb_seed=v200)."""

from __future__ import annotations

import base64
import json
import os
import sys
import urllib.error
import urllib.request
from pathlib import Path

REPO = Path(__file__).resolve().parent.parent


def load_env() -> None:
    env = REPO / ".env.upress.dev"
    if not env.exists():
        raise SystemExit(".env.upress.dev missing")
    for raw in env.read_text(encoding="utf-8").splitlines():
        line = raw.strip()
        if not line or line.startswith("#") or "=" not in line:
            continue
        k, v = line.split("=", 1)
        v = v.strip().strip("'\"")
        os.environ[k.strip()] = v


def req(method: str, url: str, data: dict | None = None) -> dict:
    user = os.environ["WP_REST_USER"]
    password = os.environ["WP_REST_APP_PASSWORD"]
    token = base64.b64encode(f"{user}:{password}".encode()).decode()
    headers = {"Authorization": f"Basic {token}", "Content-Type": "application/json"}
    body = None if data is None else json.dumps(data, ensure_ascii=False).encode("utf-8")
    request = urllib.request.Request(url, data=body, headers=headers, method=method)
    try:
        with urllib.request.urlopen(request, timeout=60) as resp:
            return json.loads(resp.read().decode("utf-8"))
    except urllib.error.HTTPError as exc:
        detail = exc.read().decode("utf-8", errors="replace")
        raise RuntimeError(f"{method} {url} -> HTTP {exc.code}: {detail}") from exc


def world_ids() -> dict[str, int]:
    terms = req("GET", f"{os.environ['WP_REST_BASE_URL']}/wp/v2/world?per_page=100")
    return {t["slug"]: t["id"] for t in terms}


def find_post_by_slug(rest_base: str, post_type: str, slug: str) -> dict | None:
    items = req("GET", f"{rest_base}/wp/v2/{post_type}?slug={slug}")
    return items[0] if items else None


def upsert_post(rest_base: str, post_type: str, payload: dict) -> dict:
    slug = payload["slug"]
    existing = find_post_by_slug(rest_base, post_type, slug)
    if existing:
        return req("POST", f"{rest_base}/wp/v2/{post_type}/{existing['id']}", payload)
    return req("POST", f"{rest_base}/wp/v2/{post_type}", payload)

def story_html(paragraphs: list[str]) -> str:
    return "".join(f"<p>{p}</p>" for p in paragraphs)


def main() -> int:
    load_env()
    base = os.environ["WP_REST_BASE_URL"]
    worlds = world_ids()
    for slug in ("soil", "know", "code"):
        if slug not in worlds:
            raise SystemExit(f"missing world term: {slug}")


    produce_sections = {
        "who": {
            "num": "01 · למי",
            "title": "מטבחים שמקפידים.",
            "bullets": [
                "מסעדות שף שצריכות עקביות — לא קופסה הפתעה.",
                "בשלנים פרטיים שמשתמשים בירקות במרכז הצלחת, לא בצד.",
                "מטבחי כשרות / טבעוני / בלי גלוטן — שיכולים לבחור מוצר ספציפי, לא 'סלסלת עונה'.",
            ],
            "meta": "TBC · להוסיף 2-3 שמות מסעדות עוגן (Q-05)",
        },
        "how": {
            "num": "02 · איך עובדים",
            "title": "תיק קבוע, רשימה שקופה.",
            "bullets": [
                "פגישת היכרות — מטבח, סגנון, מה משתמשים בשפע.",
                "תיק מק״טים פתוח — אתה רואה מה גדל עכשיו, מה גדל בעוד 3 שבועות.",
                "הזמנה עד יום ראשון 18:00 → משלוח שני בבוקר. (חמישי לקוחות חוזרים)",
                "תשלום חודשי, חשבונית מסודרת.",
            ],
            "meta": "פעיל · כולל ליווי חודשי קצר",
        },
        "what": {
            "num": "03 · מה תקבל",
            "title": "ירקות, ידע, ולוח זמנים.",
            "bullets": [
                "תוצרת מהחממה ומהשדה — קצורה באותו היום או יום קודם.",
                "רשימת ירקות שמתעדכנת לפי עונה — לא הבטחות שלא יקרו.",
                "ייעוץ עונתי קצר: מה גדל טוב החודש, מה אפשר לתכנן בתפריט.",
            ],
            "meta": "ללא מינימום חודשי, אבל עדיף עקביות",
        },
    }

    services = [
        {
            "slug": "produce",
            "title": "תוצרת מקצועית",
            "world": [worlds["soil"]],
            "meta": {
                "_nb_seed": "v200",
                "_nb_tagline": "ירקות אקולוגיים מהחממה — לבשלנים שצריכים עקביות, לא הפתעות.",
                "_nb_lede": "תיק מסירה קבוע. שני ימים בשבוע. רשימת מלאי שמתעדכנת חי לפי מה שגדל ומה שנקצר. לא קופסה אקראית — תיק שמתואם עם המטבח שלך.",
                "_nb_service_type": "service",
                "_nb_stage": "live",
                "_nb_is_anchor_for_world": "soil",
                "_nb_cta_label": "הצעת מחיר",
                "_nb_cta_whatsapp_href": "https://wa.me/972547776770",
                "_nb_cta_hint": "תגובה תוך 48 שעות · טופס או WhatsApp",
                "_nb_cta_final_h": "מטבח רציני? נדבר על מה גדל השבוע.",
                "_nb_cta_final_p": "ספר לי מה אתה מבשל. אני אשלח לך את מה שגדל השבוע, ומה שאני יכול לתכנן לחודש הבא. טופס או WhatsApp — מה שנוח לך.",
                "_nb_sections": json.dumps(produce_sections, ensure_ascii=False),
                "_nb_meta_strip": json.dumps(
                    [
                        {"k": "מסירה", "v": "2× בשבוע"},
                        {"k": "רדיוס", "v": "30 ק״מ"},
                        {"k": "מסעדות פעילות", "v": "5 קבועות"},
                        {"k": "מחיר", "v": "לפי מק״ט · חודשי", "spark": False},
                    ],
                    ensure_ascii=False,
                ),
                "_nb_hero_facts": json.dumps(
                    [
                        {"k": "פעיל מ", "v": "2024"},
                        {"k": "תדירות", "v": "שני · חמישי"},
                        {"k": "אזור", "v": "פרדס חנה — שרון"},
                        {"k": "מקור", "v": "החממה + שדה"},
                    ],
                    ensure_ascii=False,
                ),
                "_nb_linked_projects": ["rest-x-greenhouse"],
                "_nb_related_posts": [],
            },
        },
        {
            "slug": "consulting-hydro",
            "title": "ייעוץ · תכנון חממה",
            "world": [worlds["soil"], worlds["know"]],
            "meta": {
                "_nb_seed": "v200",
                "_nb_tagline": "החממה שלי, וחממות של אחרים. ידע שעבר בוץ.",
                "_nb_lede": "אבחון מערכות שלא עובדות, תכנון חממה חדשה, ליווי שנתי לחממה פעילה. לא PDF — נכנס לחממה, מודד, ובודק מה באמת קורה במים. CDIP בפעולה: עקרונות מהפיזיקה, לבעיות בשטח.",
                "_nb_service_type": "service",
                "_nb_stage": "live",
                "_nb_cta_label": "הצעת מחיר",
                "_nb_cta_hint": "פגישת היכרות חינם · תיק תיתואם אחרי",
                "_nb_cta_final_h": "החממה שלך לא יציבה — או שעוד לא קמה. שני המקרים פתורים.",
                "_nb_cta_final_p": "תכתוב לי מה המצב — ייצור, תכנון, או בעיה ספציפית. אחזור תוך יומיים עם הצעה לפגישה קצרה.",
                "_nb_sections": json.dumps(
                    {
                        "who": {
                            "num": "01 · למי",
                            "title": "מי שמרים חממה, ומי שלא יודע למה לא עובד.",
                            "bullets": [
                                "חממות פעילות שיש בהן בעיה חוזרת (יבול שלא מגיע, צמיחה לא אחידה, מערכת שלא יציבה).",
                                "מי שמתכנן להקים חממה חדשה — לפני שמזמינים ציוד.",
                                "חוות שרוצות ליווי שנתי עם בעלים אחד, לא רשת ספקים.",
                                "מסעדות שמקימות חממה זעירה בחצר.",
                            ],
                            "meta": "מתאים מ-30 מ״ר ועד גודל בינוני",
                        },
                        "how": {
                            "num": "02 · איך עובדים",
                            "title": "ביקור · מסמך · ליווי.",
                            "bullets": [
                                "פגישת היכרות (חינם, 30 דק׳) — מבינים מה הבעיה / המטרה.",
                                "ביקור בשטח — בדיקת מערכת, מים, אור, צמחים.",
                                "מסמך כתוב — תכנון / אבחון / המלצות, עם תיעדוף.",
                                "אופציה: ליווי חודשי (זום + ביקור רבעוני).",
                            ],
                            "meta": "טווח תגובה ראשון: 48 שעות",
                        },
                        "what": {
                            "num": "03 · מה תקבל",
                            "title": "תכנון כתוב, לא רק שיחה.",
                            "bullets": [
                                "מסמך אבחון / תכנון מותאם — כולל ספציפיקציות.",
                                "רשימת ציוד מומלץ עם ספקים שעובדים. לא קיקבק.",
                                "תיעוד מערכת חי — להעביר הלאה למפעיל.",
                                "(אופציונלי) ליווי שנתי עם פגישות תקופתיות.",
                            ],
                            "meta": "הכל בכתב — לא רק זום",
                        },
                    },
                    ensure_ascii=False,
                ),
                "_nb_meta_strip": json.dumps(
                    [
                        {"k": "סוגי מעורבות", "v": "אבחון · תכנון · ליווי שנתי"},
                        {"k": "פגישת היכרות", "v": "30 דק׳ — ללא תשלום"},
                        {"k": "תמחור", "v": "לפי תיק / רטיינר"},
                        {"k": "תוצאות", "v": "כתובות אחרי ביקור"},
                    ],
                    ensure_ascii=False,
                ),
                "_nb_hero_facts": json.dumps(
                    [
                        {"k": "חממות בלוויה", "v": "4 פעילות"},
                        {"k": "ניסיון", "v": "5 עונות שטח"},
                        {"k": "אזור", "v": "ישראל — כולל זום"},
                        {"k": "סוגי מערכת", "v": "NFT · DWC · Drip"},
                    ],
                    ensure_ascii=False,
                ),
                "_nb_linked_projects": ["rest-x-greenhouse"],
                "_nb_related_posts": [],
            },
        },
        {
            "slug": "sfa",
            "title": "SFA · Small Farm Agents",
            "world": [worlds["soil"], worlds["code"]],
            "meta": {
                "_nb_seed": "v200",
                "_nb_tagline": "סוכן AI לחווה קטנה — חינם, קהילתי. נבנה מהשטח שלי, חוזר לשטח של אחרים.",
                "_nb_lede": "מערכת ידע מובנית של חוות קטנות, שמדברת ב-AI אבל מבוססת על מקרים אמיתיים. אתה שואל — היא עונה לפי מה שעבד בשטח, לא לפי הבטחות. חינמית. קהילתית. עכשיו בשלב בניית תשתית הידע.",
                "_nb_service_type": "system",
                "_nb_stage": "live",
                "_nb_is_free": True,
                "_nb_cta_label": "השתמש בכלי",
                "_nb_cta_hint": "ללא תשלום · בלי הרשמה עם כרטיס אשראי",
                "_nb_cta_final_h": "חוות קטנה לא צריכה לקנות פלטפורמה כדי לדעת מה לעשות.",
                "_nb_cta_final_p": "ה-SFA נבנה מהשטח של חוות שעובדות. אם יש לך מה לתרום (ידע, מקרה, שאלה) — הצטרף. אם אתה רוצה רק לקבל — גם בסדר.",
                "_nb_sections": json.dumps(
                    {
                        "who": {
                            "num": "01 · למי",
                            "title": "חוות קטנות — ומי שמלווה אותן.",
                            "bullets": [
                                "חוות אקולוגיות, market garden, חממות זעירות — שלא יכולות להרשות לעצמן ייעוץ פרטי.",
                                "מורי חקלאות שרוצים מקור ידע מבוסס-מקרים לתלמידים.",
                                "חוקרים שזקוקים לדאטה איכותית מהשטח.",
                                "כל מי שעובד את האדמה ורוצה לתרום ניסיון.",
                            ],
                            "meta": "מודל ההצטרפות: חופשי לכולם · ללא תשלום",
                        },
                        "how": {
                            "num": "02 · איך זה עובד",
                            "title": "ידע מהשטח → בסיס ידע → סוכן.",
                            "bullets": [
                                "כל חבר קהילה תורם מקרים מהחווה שלו — מה ניסה, מה עבד, מה לא.",
                                "המידע מובנה לבסיס ידע מובנה, לא רק טקסט חופשי.",
                                "סוכן ה-AI עונה לשאלות לפי המידע הזה — עם ציטוט מקור.",
                                "אתה יכול גם לבקש 'מה עבד בחווה דומה לשלי' — קישור אנושי.",
                            ],
                            "meta": "v0.1 חזוי לסוף 2026",
                        },
                        "what": {
                            "num": "03 · מה תקבל",
                            "title": "תשובות מבוססות-שטח, חינם.",
                            "bullets": [
                                "גישה לסוכן ה-AI עם תיעדוף לפי הסיטואציה שלך.",
                                "מאגר מקרים פתוח — דפדף, חפש, למד.",
                                "אופציה ליצור קשר עם מי שהיה בסיטואציה דומה — אם רוצים.",
                                "אפס מינוי, אפס כרטיס אשראי, אפס פיצ׳ר נעול.",
                            ],
                            "meta": "תמיד יישאר חינמי",
                        },
                    },
                    ensure_ascii=False,
                ),
                "_nb_meta_strip": json.dumps(
                    [
                        {"k": "סטטוס", "v": "vision · v0.1", "spark": True},
                        {"k": "תשתית ידע", "v": "נבנית כעת"},
                        {"k": "קוד פתוח", "v": "מתוכנן · 2027"},
                        {"k": "תרומה לידע", "v": "פתוחה לכולם"},
                    ],
                    ensure_ascii=False,
                ),
                "_nb_hero_facts": json.dumps(
                    [
                        {"k": "שלב", "v": "vision · v0.1"},
                        {"k": "מקור ידע", "v": "החממה שלי + 4 חוות"},
                        {"k": "גישה", "v": "חינם · קהילתי"},
                        {"k": "טכנולוגיה", "v": "Claude · MCP"},
                    ],
                    ensure_ascii=False,
                ),
                "_nb_linked_projects": [],
                "_nb_related_posts": [],
            },
        },
    ]

    created_services: dict[str, int] = {}
    for svc in services:
        payload = {
            "title": svc["title"],
            "slug": svc["slug"],
            "status": "publish",
            "world": svc["world"],
            "meta": svc["meta"],
        }
        out = upsert_post(base, "services", payload)
        created_services[svc["slug"]] = out["id"]
        print(f"created service {svc['slug']} id={out['id']}")

    rx_story = [
        "מסעדת שף בעמק חפר. שף שמדבר על עלים כמו על תווים — כל יום שונה, כל יום בוחר. החצר האחורית שלהם הייתה ריקה — 18 מ״ר, צפון-מערב, גישה למים. הם רצו ירקות אבל לא רצו לנהל חממה.",
        "התחלנו ביום בדיקה — מה התפריט באמת צריך, מה מגיע ממנו פעם בשבוע ומה צריך יומי. רוב הירקות שלהם הסתדרו עם משלוחים. אבל העלים — בייבי תרד, רוקט, בזיליקום — נדרשו טריים, ולא הגיעו במצב טוב.",
        "התכנון: NFT קטן, 4 שורות, מערכת השקיה אוטונומית, מבנה פוליקרבונט שקוף. בלי מכלי דשן ענקיים — פשוט, נגיש, חצי-שעה תחזוקה ביום.",
        "ההתקנה לקחה 3 שבועות. החודש הראשון היה בלגן — בעיות pH, צמיחה לא אחידה, ספק מים שתפס תקלה. ישבנו על זה יחד, אבחנו, תיקנו, יצבנו. עונה שנייה עכשיו — חלקה.",
        "מה לא הלך: הניסיון להכניס עגבניות שרי לא הצליח בגלל גובה החממה. בייבי-קייל גם — לא הסתדר עם החום של עמק חפר ביולי. שתי החלטות שעדיף לא לעשות פעמיים.",
        "היום: השף יודע ביום ראשון מה יקטוף ביום רביעי. אם בא לו שינוי בתפריט — הוא מסתכל מה זמין בחממה. ליווי חודשי, ביקור רבעוני. החממה משלמת על עצמה תוך 18 חודש.",
    ]

    hagina_story = [
        "ב-2014 הקמנו את 'הגינה של נמרוד' בפרדס חנה — 1.2 דונם, אקולוגי, market garden. המודל היה פשוט: ירקות לבעלי-בית באזור, סלים שבועיים, ימי שוק. בלי תיווך. בלי משלוחים גדולים. גינה.",
        "תשע עונות. כל אחת לימדה משהו. שנה אחת הייתה ירודה (בצורת + עכבר השדה). שנה אחרת הייתה הטובה ביותר. את רוב הזמן לא היה דרמטי — היה עבודה. שתילה, ניכוש, קצירה, אריזה, שוק, חוזרים.",
        "ב-2022 התחלתי לחשוב על המעבר. הסיבה לא הייתה כסף — הגינה הייתה ויאבילית. הסיבה הייתה שהבנתי שמה שצברתי בתשע השנים האלה הוא ידע — ושאני יכול לעזור ליותר אנשים בשירותי-ידע מאשר בלגדל בעצמי.",
        "סוף עונת 2023 — סגירה מתוכננת. לא משבר, לא פשיטת רגל. החלטה. ספירת מלאי. סיום חוזים עם לקוחות עוגן. תשתית פוזרה — חממות לחברים, ציוד לחקלאים מתחילים, ידע נשאר.",
        "מה נשאר היום: החממה ההידרופונית (תשתית מקצועית-קטנה במקום שדה גדול). הידע — ייעוץ, הוראה, SFA. הקשרים — מסעדות מתקופת הגינה ממשיכות להזמין תוצרת. וגם — כתב יד מ-9 עונות שלא הקלדתי עוד לקובץ.",
        "זה לא פוסט-מורטם. זה ההמשך. <em>Unless.</em>",
    ]

    coop_story = [
        "רעיון. 5–8 חממות קטנות באזור השרון, שמחלקות ידע, ציוד יקר, ולוגיסטיקה. כל אחת ממשיכה להיות עצמאית — אבל לא לבד.",
        "מה זה <em>לא</em>: רשת שיווק שגובה אחוזים. סולל שמכתיב מסלול גידול. פלטפורמה שמחזיקה את הדאטה שלך.",
        "מה זה <em>כן</em>: 5 בעלי חממות שמשלמים יחד על מערכת אבחון יקרה. שמתאמים שילוח אחד למסעדות שיש להן כמה ספקים. שמכירים את הצמחים זה של זה — ונותנים יד כשמשהו לא עובד.",
        "איפה אנחנו: שלב רעיון. 2 חממות מעוניינות (פרדס חנה + שפיים — TBC). צריך 3–5 נוספות, ושותף טכני שיכול לבנות platform-MCP מינימליסטי על בסיס הידע ש-SFA כבר אוסף.",
        "מי אנחנו מחפשים: בעל חממה קטנה (50–300 מ״ר) באזור השרון/חוף; בעל מקצוע טכני (devops · MCP · data); מנטור עסקי שיודע על קואופרטיבים אמיתיים.",
        "זה הצעד הבא של מה שהתחיל ב-SFA — מ-AI agent למבנה חברתי-טכני שעובד. <em>Unless.</em>",
    ]

    projects = [
        {
            "slug": "rest-x-greenhouse",
            "title": "חממת מסעדת X",
            "content": story_html(rx_story),
            "world": [worlds["soil"], worlds["know"]],
            "meta": {
                "_nb_seed": "v200",
                "_nb_scope": "client-case",
                "_nb_stage": "live",
                "_nb_year": "2025",
                "_nb_location": "עמק חפר · TBC עיר",
                "_nb_duration": "מאוגוסט 2024",
                "_nb_summary": "חממה זעירה בחצר אחורית של מסעדת שף — תכנון מלא, התקנה, ליווי שנתי. עלים יומיים לתפריט עונתי.",
                "_nb_name_tbc": True,
                "_nb_linked_services": ["consulting-hydro", "produce"],
                "_nb_outcomes": json.dumps(
                    {
                        "title": "מספרים אחרי שנה.",
                        "tiles": [
                            {"n": "01", "v": "12 ק״ג", "l": "יבול שבועי · עונה 2", "desc": "בייבי תרד, רוקט, בזיליקום, נענע — קצירה יומית.", "color": "soil"},
                            {"n": "02", "v": "18 חודש", "l": "החזר השקעה", "desc": "כולל ציוד, התקנה, ליווי שנה ראשונה.", "color": "soil"},
                            {"n": "03", "v": "30 דק׳", "l": "תחזוקה יומית", "desc": "השף או סו-שף — לא טכנאי.", "color": "know"},
                            {"n": "04", "v": "100%", "l": "תפריט עלים מהחצר", "desc": "בעונה — כל העלים מהחממה. בחורף — 70%.", "color": "soil"},
                        ],
                    },
                    ensure_ascii=False,
                ),
                "_nb_gallery": [],
                "_nb_more_projects_ids": ["hagina-shel-nimrod", "coop-sharon"],
            },
        },
        {
            "slug": "hagina-shel-nimrod",
            "title": "הגינה של נמרוד",
            "content": story_html(hagina_story),
            "world": [worlds["soil"]],
            "meta": {
                "_nb_seed": "v200",
                "_nb_scope": "own-venture",
                "_nb_stage": "legacy",
                "_nb_year": "2014–2023",
                "_nb_location": "פרדס חנה",
                "_nb_duration": "9 עונות",
                "_nb_summary": "החווה האקולוגית שפעלה 9 עונות — מ-2014 עד 2023. נסגרה מתוך בחירה, לא כשלון. מהמורשת שלה גדל כל מה שיש היום.",
                "_nb_legacy_of": "התשתית שממנה צמחה החממה ההידרופונית, הייעוץ, ומה שמתהווה את SFA.",
                "_nb_outcomes": json.dumps(
                    {
                        "title": "9 עונות במספרים.",
                        "tiles": [
                            {"n": "01", "v": "9", "l": "עונות פעילות · 2014→2023", "desc": "כולל שנה ירודה אחת, שנה שיא אחת.", "color": "archived"},
                            {"n": "02", "v": "1.2 דונם", "l": "שטח גידול", "desc": "אקולוגי · market-garden · רוטציה 7 מקטעים.", "color": "archived"},
                            {"n": "03", "v": "~80", "l": "סלים פעילים בעונה", "desc": "לקוחות חוזרים · רובם פרדס חנה והאזור.", "color": "archived"},
                            {"n": "04", "v": "5", "l": "מסעדות שהתחילו פה", "desc": "חלקן ממשיכות לקבל תוצרת מהחממה היום.", "color": "archived"},
                        ],
                        "note": "התוצאות פה היסטוריות. הן לא 'מה אני מציע היום' — הן 'מהיכן הגעתי'. הסיפור החי ממשיך בחממה ההידרופונית ובשירותי הידע.",
                    },
                    ensure_ascii=False,
                ),
                "_nb_outcomes_note": "התוצאות פה היסטוריות. הן לא 'מה אני מציע היום' — הן 'מהיכן הגעתי'. הסיפור החי ממשיך בחממה ההידרופונית ובשירותי הידע.",
                "_nb_gallery": [],
                "_nb_more_projects_ids": ["rest-x-greenhouse", "coop-sharon"],
            },
        },
        {
            "slug": "coop-sharon",
            "title": "קואופרטיב חממות קטנות · השרון",
            "content": story_html(coop_story),
            "world": [worlds["soil"], worlds["code"]],
            "meta": {
                "_nb_seed": "v200",
                "_nb_scope": "own-venture",
                "_nb_stage": "seeking-partners",
                "_nb_year": "2026 → ?",
                "_nb_location": "השרון · חוף הכרמל",
                "_nb_duration": "כעת בשלב רעיון",
                "_nb_summary": "רשת של 5–8 חממות קטנות באזור השרון שמחלקות ידע, ציוד יקר ולוגיסטיקה — בלי לפגוע בעצמאות של כל אחת. מ-SFA למבנה חברתי-טכני אמיתי.",
                "_nb_name_tbc": True,
                "_nb_seeking_note": "2 חממות מעוניינות · צריך 3–5 נוספות + שותף טכני",
                "_nb_outcomes": json.dumps(
                    {
                        "title": "מה צריך לקרות.",
                        "tiles": [
                            {"n": "01", "v": "2 / 5", "l": "חממות מעוניינות / נדרשות", "desc": "פרדס חנה + שפיים (TBC). מחפש 3–5 נוספות.", "color": "code"},
                            {"n": "02", "v": "0 / 1–2", "l": "שותפים טכניים", "desc": "devops, MCP, data — אחד מספיק להתחלה.", "color": "code"},
                            {"n": "03", "v": "30K₪", "l": "תקציב פילוט שנה", "desc": "אומדן ראשוני · להוצאות משותפות בלבד.", "color": "code"},
                            {"n": "04", "v": "עונה 1", "l": "אופק פילוט", "desc": "תאוריה: ספטמבר 2026 → אוקטובר 2027.", "color": "code"},
                        ],
                        "note": "כל המספרים פה הם הערכה — לא הבטחה. הפרויקט יקרה רק אם השותפים יבואו. אם זה לא יקרה — זה בסדר. רעיון תקף לעצמו.",
                    },
                    ensure_ascii=False,
                ),
                "_nb_outcomes_note": "כל המספרים פה הם הערכה — לא הבטחה. הפרויקט יקרה רק אם השותפים יבואו. אם זה לא יקרה — זה בסדר. רעיון תקף לעצמו.",
                "_nb_seeking_cta_h": "אם זה נראה לך — דבר איתי.",
                "_nb_seeking_cta_p": "פגישה ראשונה לא מחייבת כלום. אני מסביר את הרעיון, אתה מספר על החממה / היכולת שלך, ומחליטים אם יש כיוון. שיחה של 30 דק׳, ללא תשלום.",
                "_nb_seeking_cta_label": "התעניינות · שיחה ראשונה",
                "_nb_seeking_cta_hint": "ללא התחייבות · 30 דק׳",
                "_nb_gallery": [],
                "_nb_more_projects_ids": ["rest-x-greenhouse", "hagina-shel-nimrod"],
            },
        },
    ]

    for proj in projects:
        payload = {
            "title": proj["title"],
            "slug": proj["slug"],
            "status": "publish",
            "content": proj["content"],
            "world": proj["world"],
            "meta": proj["meta"],
        }
        out = upsert_post(base, "projects", payload)
        print(f"created project {proj['slug']} id={out['id']}")

    print("seed complete")
    return 0


if __name__ == "__main__":
    sys.exit(main())
