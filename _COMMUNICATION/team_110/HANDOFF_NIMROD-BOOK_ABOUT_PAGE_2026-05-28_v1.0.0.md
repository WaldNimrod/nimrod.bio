---
type: HANDOFF
from: team_110 (nimrod-bio orchestrator)
to: nimrod-book domain session
project: nimrod-bio (spoke)
date: 2026-05-28
version: v1.0.0
task: כתיבת תוכן ראשוני לדף /about/ — "על נמרוד"
wp_ref: NB-S002-P008-WP003 (informal — pre-cutover content fill)
urgency: P1 — חוסם cutover
---

# Handoff — nimrod-book → כתיבת דף "על נמרוד"

## §1 הקשר

האתר `nimrod.bio` בשלבי סיום לפני עלייה לאוויר. כל התוכן מלא, כל השירותים והפרויקטים חיים. נשאר דף אחד ריק: `/about/` — "על נמרוד" — שהוא עמוד מרכזי לכל מי שמגיע לאתר ורוצה להבין עם מי הוא עובד.

הדף קיים ב-WordPress (ID: 37, slug: `about`, title: "על נמרוד"), כרגע יש בו 72 תווים בלבד (placeholder ריק). יש לכתוב תוכן ראשוני מלא ולפצ'ר אותו דרך WP REST API.

## §2 על האתר

- **האתר:** nimrod.bio — האתר המקצועי של נמרוד ולד
- **שלושה עולמות:** soil (חקלאות, תוצרת, חממה), know (ייעוץ, הוראה, ידע שטחי), code (TikTrack, SFA, בניית מערכות)
- **קהל יעד:** מסעדנים, חקלאים מתחילים, יזמים חקלאיים, מפתחים
- **קול:** עברית ישירה, לא שיווקית, קצר ומדויק — "אנחנו" לא "ניתן", ספציפי לא כללי

## §3 מה לכתוב — "על נמרוד"

דף /about/ צריך לענות על שאלה אחת: מי זה נמרוד ולד ולמה כדאי לעבוד איתו?

**נקודות שחייבות להיכלל:**
- עבודה בשטח — חקלאות הידרופונית, תוצרת למסעדות, חממות
- ייעוץ — תכנון חממות, ליווי אגרו, חקלאים שמתחילים
- טכנולוגיה — TikTrack (מעקב פעילות שטחי), SFA (מערכת ניהול חוות), כלים שנבנו מהצורך
- האינטגרציה — אדם אחד שחי בשלושת העולמות האלה בו-זמנית
- לא CV — חוויה, לא רשימה

**טון:**
- גוף ראשון רבים ("עשינו", "אנחנו") או שלישי ישיר ("נמרוד")
- ספציפי — מספרים, מקומות, דוגמאות אמיתיות
- קצר יותר טוב מארוך — 3-5 פסקאות, לא עמוד חיים שלמה
- אין תמונת פרופיל, אין לוח זמנים — טקסט בלבד בשלב הזה

**מינימום:** 800 תווים rendered, 3 פסקאות לפחות

## §4 ביצוע טכני

### Credentials
```bash
source /Users/nimrod/Documents/nimrod-bio/.env.upress.dev
BASE="${WP_REST_BASE_URL/\/wp-json/}"
AUTH="${WP_REST_USER}:${WP_REST_APP_PASSWORD}"
```

### קרא את מה שיש (72 תווים) לפני שכותב:
```bash
curl -s -u "$AUTH" "$BASE/wp-json/wp/v2/pages/37?context=edit&_fields=content" | \
  python3 -c "import sys,json; d=json.load(sys.stdin); print(d['content']['raw'])"
```

### קרא כמה פוסטים לדוגמה לקול:
```bash
# TikTrack — קול טכנולוגי
curl -s -u "$AUTH" "$BASE/wp-json/wp/v2/services/29?context=edit&_fields=content" | \
  python3 -c "import sys,json; d=json.load(sys.stdin); print(d['content']['raw'][:600])"

# consulting-hydro — קול ייעוץ
curl -s -u "$AUTH" "$BASE/wp-json/wp/v2/services/26?context=edit&_fields=content" | \
  python3 -c "import sys,json; d=json.load(sys.stdin); print(d['content']['raw'][:600])"
```

### PATCH הדף:
```bash
curl -s -X POST -u "$AUTH" "$BASE/wp-json/wp/v2/pages/37" \
  -H "Content-Type: application/json" \
  -d '{"content": "GUTENBERG_CONTENT_HERE"}' | \
  python3 -c "import sys,json; d=json.load(sys.stdin); print('chars:', len(d.get('content',{}).get('rendered','')))"
```

**Gutenberg format:**
```
<!-- wp:paragraph -->
<p>פסקה ראשונה...</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>פסקה שנייה...</p>
<!-- /wp:paragraph -->
```

### וידוא:
```bash
# rendered chars ≥ 800
curl -s "$BASE/wp-json/wp/v2/pages/37?_fields=content" | \
  python3 -c "import sys,json; d=json.load(sys.stdin); print('chars:', len(d['content']['rendered']))"

# עמוד חי
curl -so /dev/null -w "%{http_code}" "http://nimrod-bio-2026.s887.upress.link/about/"
```

## §5 Deliverable

צור:
`/Users/nimrod/Documents/nimrod-bio/_COMMUNICATION/team_110/COMPLETION_NB-S002-P008-WP003_ABOUT_2026-05-28_v1.0.0.md`

כולל:
- chars_before → chars_after
- snippet של 200 תווים ראשונים מה-rendered content
- AT: ≥ 800 chars ✓ / HTTP 200 ✓
- git add + git commit

## §6 הפעלה — העתק לסשן חדש

```
═══════════════════════════════════════════════════════════════
NIMROD-BOOK DOMAIN SESSION
ACTIVATION — nimrod.bio · /about/ page content fill
═══════════════════════════════════════════════════════════════

זהות
────
- Domain: nimrod-book
- Role: Content Creator / ידע על נמרוד ולד
- Engine: claude-code (או כל engine זמין בדומיין)

המשימה
──────
כתוב תוכן ראשוני לדף "על נמרוד" באתר nimrod.bio.

מנדט מלא:
_COMMUNICATION/team_110/HANDOFF_NIMROD-BOOK_ABOUT_PAGE_2026-05-28_v1.0.0.md

שלבים:
1. קרא את ה-HANDOFF המלא
2. קרא 2-3 שירותים קיימים כ-reference קול
3. כתוב את התוכן (3-5 פסקאות, ≥800 תווים, עברית ישירה)
4. PATCH /wp-json/wp/v2/pages/37 עם credentials מ-.env.upress.dev
5. ודא rendered chars ≥ 800 + HTTP 200 על /about/
6. כתוב COMPLETION + commit

Working directory: /Users/nimrod/Documents/nimrod-bio
═══════════════════════════════════════════════════════════════
```

— team_110 — 2026-05-28
