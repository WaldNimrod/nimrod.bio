---
type: COMPLETION
from: team_10 (nimrodbio_build)
to: team_100 / team_190
wp_id: NB-S002-V200-MAINT (Stage C/D dead-code cleanup — t8.css)
project: nimrod-bio
milestone: V200
program: Stage C/D follow-up (maintenance / dead-code)
date: 2026-06-01
gate: L-GATE_BUILD
spec_ref: docs/UPRESS_FTPS_MANDATORY_PROCEDURE.md
---

# COMPLETION — Stage C/D dead-code cleanup (t8.css) + dev redeploy

## Status

**DONE — committed + pushed to `origin/main` + deployed to dev + re-verified.**

ניקוי CSS מת בלבד (pure dead-code). אין שינוי ויזואלי מכוון. דפי `/contact/`
ו-`/about/` נטענים זהים-בייטית (HTTP 200, cache-busted).

---

## 1. מה בוצע (Scope)

Stage C יישר את ה-markup של Contact hero ו-About ל-class anchors חדשים
(`.page-hero`, `.hero-act` flex row, `.btn-wa`, `.about-hero`/`.ah-*`,
`.timeline`/`.tl-*`, `.principle-grid`/`.principle-tile`, `.sea`/`.sea-quote`).
חוקי ה-CSS הישנים שהוחלפו נותרו יתומים בקובץ. בוצע מחיקה מאומתת שלהם.

**אומת לפני מחיקה** (grep על כל `*.php` + `template-parts/`) — כל סלקטור
מאושר כמת כי ה-Stage C כתב מחדש את ה-template parts:

| Template part | פולט עכשיו | סלקטורים שמתו |
|---|---|---|
| `t8-value-tile.php` | `.principle-tile` / `.pt-k` | `.values`, `.value-tile`, `.value-tile *` |
| `t8-journey-timeline.php` | `<ol class="timeline">` + `.tl-row`/`.tl-dot`/`.tl-year`/`.tl-body` | `.journey`, `.journey-track`, `.journey-list`, `.journey-event*`, וגם הילדים `.j-year`/`.j-title`/`.j-body` |
| `t8-cdip-thesis.php` | `.sec-head` / `.about-prose` | `.thesis`, `.thesis .label`, `.thesis h3`, `.thesis p`, `.thesis-links` |
| `page-contact.php` | `.page-hero` + `.hero-act` (flex) + `.btn-wa` | `.contact-hero*`, `.contact-hero-actions`, old `.hero-act` inline-flex, `.hero-act svg`, `.hero-act--primary`, `.hero-act--ghost` |

## 2. מה נמחק מ-`assets/css/t8.css`

- בלוקי הכללים: `journey`, `thesis`, `values`, `contact-hero`.
- כפתור hero ישן: old `.hero-act` inline-flex rule + `.hero-act svg` + `--primary`/`--ghost`.
- סלקטורים מתים בתוך media queries `900px` / `640px` / `760px`
  (נשמרו החיים: `.contact-body`, `.heritage-end`, `.media-grid`).
- override מת נוסף: `.contact-hero h1` בתוך 760px.
- `.thesis-links`.
- עודכנו 2 הערות שהפכו ל-stale (Stage C banner + mobile banner).

**נשמר במכוון** (עדיין בשימוש): `.story-block`/`.story-inner`/`.pullquote`
(About §01), `.hero-act` (ה-flex row החדש), `.btn-wa`.

Brace balance: 241/241 (תקין).

## 3. Deploy (FTPS — נוהל מחייב)

לפי `docs/UPRESS_FTPS_MANDATORY_PROCEDURE.md`:

- Preflight: IP `79.177.137.169` ✅ ב-`UPRESS_FTP_ALLOWED_IPS`; port 21 ל-`ftp.s887.upress.link` reachable ✅.
- `python3 scripts/upress_ftps_upload.py --env-file .env.upress.dev --local-dir nimrod.bio/wp-content/themes/nimrod-bio-2026 --remote-dir wp-content/themes/nimrod-bio-2026` → **`[OK] Uploaded 172 file(s)`**.
- `NB_THEME_VERSION` הועלה `0.7.11` → **`0.7.12`** (`functions.php:8`) — מבטל cache על ה-asset.

## 4. Re-verification (evidence)

- `t8.css` שמוגש מ-dev **md5-identical** למקומי: `dda43b1718bada2488117677bf86a238` (31493 bytes).
- אפס סלקטורים מתים בכללים (grep אחרי הסרת הערות).
- כל הסלקטורים החיים נוכחים: `.principle-tile`, `.timeline`, `.tl-row`, `.story-block`, `.about-prose`, `.btn-wa`.
- `https://nimrod-bio-2026.s887.upress.link/contact/` → **HTTP 200**, asset `t8.css?ver=0.7.12`.
- `.../about/` → **HTTP 200**, asset `t8.css?ver=0.7.12`.

## 5. Git state — ⚠ נבלע בקומיטים של session מקבילי

במהלך העבודה רץ **session מקביל של team_100** שביצע `git add -A` commits
ובלע את עריכות ה-working-tree הלא-מקומיטות שלי:

- **`df6000fa`** (v0.7.11) — מכיל את ה-diff המדויק של ניקוי הקוד המת
  (אומת ע"י מחרוזת ההערה הייחודית `"Stage C dead-code cleanup"` ב-`HEAD:t8.css`).
- **`9317240e`** (v0.7.12) — "completion(team_100): V200 design build A-D complete on dev" — בלע את bump גרסת ה-theme ל-`0.7.12`.

**שני הקומיטים כבר על `origin/main`** (`git branch -r --contains` מאשר). אין branch
נפרד ואין PR פתוח — הכל הלך ישירות ל-main וכבר נדחף. team_100 ממשיך לדחוף עבודה
מעל (origin/main כבר ב-`96805bcf`).

## 6. דחיפה ומיזוג לאחר בדיקה (push / merge guidance)

הסטטוס בפועל: **הדחיפה כבר בוצעה.** אין מה למזג כ-branch. לכן ה"דחיפה והמיזוג
לאחר בדיקה" מתורגם כך:

1. **אימות מצב remote** (מומלץ, כי המצב זז תחת ידינו):
   `git fetch origin && git log --oneline origin/main -5`
   — לוודא ש-`df6000fa` + `9317240e` קיימים ושאין conflict עם עבודת team_100 שמעל.
2. **Review ממוקד של הניקוי** (אם רוצים לבחון אותו מבודד מ-Stage D):
   `git show df6000fa -- nimrod.bio/wp-content/themes/nimrod-bio-2026/assets/css/t8.css`
3. **Gate review** — L-GATE_BUILD לאישור team_190 (cross-engine) + QA dev (team_50)
   על `/contact/` + `/about/` (HTTP 200 + cache-bust כבר מאומתים בדוח זה).
4. **Production cutover** — רק כשנפתח gate ה-V200 ל-prod: deploy זהה (FTPS theme)
   ל-staging על הדומיין הראשי, ואז cutover. אין שינוי ויזואלי → סיכון נמוך.
5. **Rollback** — uPress auto-backup (30-day) + snapshot ידני לפני cutover.
   במקרה צורך: שחזור הגרסה הקודמת של `t8.css` והורדת `NB_THEME_VERSION`.

## 7. Open items / הערות ל-team_100

- העבודה לא קיבלה קומיט נפרד — היא מעורבבת בקומיטים של Stage D של team_100
  (`df6000fa` כולל גם activity counts / external links / token-RTL sweep). אם
  ה-changelog דורש ניקוי-קוד-מת כיחידה עצמאית — להשתמש ב-diff המבודד מסעיף 6.2.
- Session-ים מקבילים על אותו spoke (Iron Rule #4 — single logical writer) —
  לתשומת לב team_100: היו שני actors כותבים בו-זמנית ל-working tree של `nimrod-bio`.

---

*Issued by team_10 (nimrodbio_build) per Iron Rule #6. Routed to team_100 / team_190.*
