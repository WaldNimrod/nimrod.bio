---
id: MANDATE_NB-S002-P008-WP001_SERVICE_CONTENT_FILL
type: BUILD_MANDATE
from: team_110 (orchestrator)
to: team_10 (Builder · sub-agent)
project: nimrod-bio
milestone: V200
wp_id: NB-S002-P008-WP001
date: 2026-05-28
version: v1.0.0
priority: P1
predecessor: NB-S002-P007-WP004 (COMPLETE — constitutional gate passed)
env_file: .env.upress.dev
rest_base: see §3
---

# MANDATE — P008-WP001 · שירותים ריקים — מילוי תוכן

## §1 מטרה

7 שירותים ב-WP האתר אין להם תוכן (content body = 0 תווים). כולם `status: publish` ו-`stage: live`. יש לכתוב תוכן ראשוני לכל אחד — בעברית, קול Nimrod, מינימום 300 תווים — ולעדכן ב-REST.

## §2 הכנה

```bash
source .env.upress.dev
BASE_URL="${WP_REST_BASE_URL/\/wp-json/}"   # e.g. http://nimrod-bio-2026.s887.upress.link
AUTH="${WP_REST_USER}:${WP_REST_APP_PASSWORD}"
REST="$BASE_URL/wp-json/wp/v2/services"
```

## §3 רשימת שירותים למילוי

| ID | slug | title | lede (מה יודעים) | מידע נוסף |
|---|---|---|---|---|
| 22 | `produce` | תוצרת מקצועית | ירקות אקולוגיים מהחממה — למסעדות | `_nb_tagline`: "תיק מסירה קבוע" |
| 23 | `hydro-greenhouse` | החממה ההידרופונית | התשתית שעליה יושב כל מה שיוצא לחוץ | meta_strip: שטח 240 מ"ר / הספק 12 ק"ג / `_nb_is_anchor_for_world: soil` |
| 24 | `bcs` | BCS · שירותי שטח | טרקטור קטן, פעולה מדויקת | `_nb_tagline`: "BCS 853" |
| 25 | `nursery` | משתלה | מה שגדל מהזרע | `_nb_service_type: background` |
| 26 | `consulting-hydro` | ייעוץ · תכנון חממה | ידע שעבר בוץ — לא רק PDF | אותו מגרש כמו seed-t7-consulting-hydro |
| 27 | `consulting-agro` | ייעוץ · אגרו | מסלולי גידול שכבר נוסו | — |
| 30 | `teaching` | הוראה | הוראה בשטח | `_nb_is_anchor_for_world: know` |

## §4 תוכן reference — אותו קול

ראה את הסגנון מ-3 שירותים שכבר מלאים (GET /wp/v2/services/{id}?context=edit):
- ID 29 (`tiktrack`) — 891 תווים raw — פלטפורמת מעקב
- ID 42 (`seed-t7-produce`) — 776 תווים raw — תוצרת טרייה
- ID 43 (`seed-t7-consulting-hydro`) — 799 תווים raw — ייעוץ הידרופוניקה

**מאפייני הקול:**
- עברית ישירה, משפטים קצרים
- "אנחנו" — לא "ניתן"
- ספציפי — מספרים, שמות, דוגמאות מהשטח
- אין בולטים אם לא צריך — פסקאות
- אין שפה שיווקית ריקה ("מוביל בתחומו", "פתרון מקיף")
- Gutenberg blocks: `<!-- wp:paragraph --><p>...</p><!-- /wp:paragraph -->`

## §5 לכל שירות — תהליך

```bash
# 1. כתוב תוכן (~600-900 תווים raw, 2-3 פסקאות)
# 2. PATCH:
curl -s -X POST \
  -u "$AUTH" \
  "$REST/{ID}" \
  -H "Content-Type: application/json" \
  -d '{"content": "<!-- wp:paragraph --><p>...</p><!-- /wp:paragraph -->"}' \
  | python3 -c "import sys,json; d=json.load(sys.stdin); print('status:', d.get('status'), '| chars:', len(d.get('content',{}).get('rendered','')))"

# 3. וודא ≥ 300 תווים rendered
```

## §6 Acceptance tests (per service)

| AT | קריטריון | PASS |
|---|---|---|
| AT-1 | GET /services/{id} → rendered content ≥ 300 chars | לכל 7 |
| AT-2 | 0 × placeholder markers (`data-nb-placeholder`) | — |
| AT-3 | קול עברי תקני, ללא Lorem ipsum / placeholder text | — |
| AT-4 | live page `/services/{slug}/` → HTTP 200 | לכל 7 |

## §7 STOP conditions

- REST failures >3 consecutive → STOP, דווח
- Any service renders error / 5xx → STOP, rollback content patch

## §8 Deliverable

`_COMMUNICATION/team_110/COMPLETION_NB-S002-P008-WP001_2026-05-28_v1.0.0.md`

כולל:
- per-service: before (0) → after (N chars)
- AT-1..AT-4 per service
- overall: PASS / FAIL

— team_110 — 2026-05-28
