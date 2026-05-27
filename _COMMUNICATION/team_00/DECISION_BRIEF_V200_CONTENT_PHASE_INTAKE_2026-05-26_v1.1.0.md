---
type: DECISION_BRIEF
from: team_110 (Domain Architect · cursor-composer-2)
to: team_00 (Nimrod, Principal)
project: nimrod-bio
milestone: V200
phase: Content Expansion (pre-cutover)
date: 2026-05-26
version: v1.1.0
status: OPEN
language: he
supersedes: DECISION_BRIEF_V200_CONTENT_PHASE_INTAKE_2026-05-26_v1.0.0.md
mechanism: AOS_decide (canonical, file-based — hub API reachable on waldhomeserver `http://100.125.98.56:8090`, used for context probe only)
companion_to:
  - _COMMUNICATION/team_110/MISSION_BRIEF_CONTENT_PHASE_2026-05-26_v1.0.0.md
  - _COMMUNICATION/team_110/HANDOFF_SELF_110_GENERAL_2026-05-25_v1.md
priority: HIGH
---

# Decision Brief — V200 · שלב הרחבת תוכן · Intake (Q1–Q11)

## 1. זהות

- **מאת:** `team_110` — Domain Architect, engine `cursor-composer-2`, סמכות GATE_2 (architecture approval).
- **אל:** `team_00` — Principal (נמרוד).
- **פרויקט:** `nimrod-bio` · **מילסטון:** `V200` (Site Rebuild) · **שלב:** Content Expansion (פרה-cutover).
- **תיק WP:** אין WP פעיל; הברף הזה ימפה ל-`NB-S002-P006-WPnn` (בכפוף לאישור פתיחת התוכנית P006 — ראה OP-3).

## 2. משילות

- **שער פתוח:** GATE_2 (architecture approval) על שלב "תוכן-כארכיטקטורה" — אני מסווג כל פריט תוכן ל-(א) data-only על תבניות קיימות, (ב) הרחבת תבנית, או (ג) תבנית חדשה / GCR ל-team_35.
- **Iron Rules בתוקף:**
  - **#4** — single logical writer על `roadmap.yaml` (team_100 ירשום בשמי).
  - **#6** — תקשורת בין צוותים דרך artifact קאנוני ב-`_COMMUNICATION/`.
  - **#7** — API-only structured mutations (DB online — אומת מול ה-hub). כל פוסט/CPT instance עובר דרך WP REST או admin UI, לעולם לא DB ישיר.
  - **דומיין:** אסור לגעת ב-`system.css` / `shell.css` / `theme.json` (LOCKED ע"י team_35); אין תוסף חדש אלא אם infrastructure-class; slugs בעברית נשמרים.
- **סמכות ההחלטה:** `team_00` — תוכן בבעלות העיקרית; team_110 מציג מסגרת + השלכות ארכיטקטוניות בלבד.
- **מה זה פותח:** authoring של `CONTENT_PHASE_INTAKE_*.md`, פתיחת P006 WPs, MANDATE לבאצ' ראשון ל-team_10, ובהמשך unfreeze של P005-WP002 (cutover).

## 3. המשימה

לתת לי תשובות (או "דחייה ל-V300" / "מחק") על 11 שאלות ה-intake למטה, כך שאוכל להפיק תוכנית באצ'ים מסודרת + הערכת זמן + לקבוע איזו שאלה דורשת ברף נפרד.

## 4. קונטקסט

- 12/13 WPs הושלמו על dev `https://nimrod-bio-2026.s887.upress.link` (בדיקה הסשן: theme stamp `wp-theme-nimrod-bio-2026` רנדר; `validate_aos.sh .` → 32 PASS / 16 SKIP / 0 FAIL).
- 7 תבניות פעילות (T7/T1×3/T2/T3/T4/T5/T8); 22 פוסטים מהוגרים תחת `/blog/`; page `/shook/`; 6 seed CPT instances (3 services + 3 projects); 4 sample posts.
- שכבת redirects: 23×301 + 6×410 דרך MU plugin + `.htaccess` portable.
- SMTP פעיל דרך `smtp.inbox.co.il:587/TLS` (אחרי rotation cycle 1.1, CONDITIONAL GO 25.5).
- P005-WP002 cutover LOD400 מוכן, DEFERRED — מוקפא עד `COMPLETION_CONTENT_PHASE` מ-team_110.
- **Backlog נושא:** 5 בלוקי TBC (Q-05/Q-NEW-03/Q-11/Q-02/Q-03), broken link `/blog/back-to-mud/`, Lighthouse uplift (V300 בלבד — לא בברף הזה).

---

## 5. שאלות

כל שאלה מסומנת **[סגורה]** (אופציות בדידות עם trade-offs) או **[פתוחה]** (תוכן חופשי; הנחיות פורמט בלבד).

---

### Q1 — סדר ה-TBC blocks *[סגורה]*

**השאלה:** מתוך 5 בלוקי ה-TBC — אילו אתה יכול לסגור עכשיו, אילו לדחות ל-V300, אילו למחוק? באיזה סדר?

| Block | היכן בא לידי ביטוי | Fit ברירת מחדל |
|---|---|---|
| Q-05 מסעדות עוגן | T2 produce + T8 about factrow | data-only |
| Q-NEW-03 טאגליין "Unless" | 4+ נקודות באתר | מחרוזת בודדת |
| Q-11 מיתוג מיזו | T7 footer + T8 about | מחרוזת + אולי לוגו |
| Q-02 מודל תמחור SFA | T2 sfa CTA + T1 know | מחרוזת + CTA |
| Q-03 מקומות הוראה | T2 know + T8 about | data, אולי factrow חדש |

#### אופציה A — לסגור הכל בשלב הזה, סדר מומלץ: Q-NEW-03 → Q-02 → Q-05 → Q-03 → Q-11

| מאפיין | ערך |
|---|---|
| **What** | מסיימים את 5 הבלוקים בשלב הזה, באצ' אחד או שניים. |
| **Work cost** | MEDIUM — team_10, ~1 יום באצ' |
| **Dependencies** | אין; לכל אחת יש תוכן או אופציה ברורה |
| **Flexibility** | HIGH — שינוי בכל אחת לפני cutover עולה דקות-שעות |
| **Novelty** | INCREMENTAL |
| **Short-term** | סוגר את כל ה-TBC לפני cutover, המבנה הוויזואלי מתייצב |
| **Long-term** | מסיר חוב טכני שמאיים על קוהרנטיות מותגית בלאנץ' |
| **AOS alignment** | ALIGNED (Iron Rule #6, GATE_2) |
| **Risk** | LOW |

**יתרונות:** סגירה מלאה לפני cutover · בלוק ה-TBC נעלם · אין צורך לתחזק שניהם בנפרד · Yoast meta מתייצב.
**חסרונות:** דורש זמן ממך ל-5 קלטים תוכניים · אם אחד מהם תקוע בהמתנה לאישור צד שלישי (למשל לוגו מיזו), כל הבאצ' מחכה.

#### אופציה B — לסגור 3 קלות (Q-NEW-03 / Q-02 / Q-11), לדחות 2 (Q-05 / Q-03) ל-V300

| מאפיין | ערך |
|---|---|
| **What** | רק בלוקים שתלויים במחרוזת/החלטה ובלי תלות חיצונית; השאר ב-V300. |
| **Work cost** | LOW — team_10, ~3 שעות |
| **Dependencies** | אין |
| **Flexibility** | HIGH |
| **Novelty** | INCREMENTAL |
| **Short-term** | cutover מואץ; חלק מ-factrows באתר נשאר עם copy זמני |
| **Long-term** | חוסר אחידות בין מה שמופיע באתר בלאנץ' לבין הסיפור המלא |
| **AOS alignment** | ALIGNED |
| **Risk** | LOW–MEDIUM (תפיסה חיצונית שהאתר לא "גמור") |

**יתרונות:** מהיר מאוד · אין תלות בצד שלישי · מאפשר cutover תוך פחות משבוע.
**חסרונות:** factrows חסרים בעמוד produce ובעמוד know · מסר מותגי לא שלם בלאנץ'.

#### אופציה C — לדחות הכל ל-V300, להמשיך ישר ל-cutover

| מאפיין | ערך |
|---|---|
| **What** | להחיל את הצד הוויזואלי הקיים על production, להשאיר כל ה-TBC כפלייסהולדרים. |
| **Work cost** | LOW — 0 |
| **Dependencies** | קבלת team_00 שהאתר עולה עם copy חלקי |
| **Flexibility** | HIGH (עריכה אחרי לאנץ') |
| **Novelty** | INCREMENTAL |
| **Short-term** | cutover עכשיו (3-4 ימים) |
| **Long-term** | חוב מותגי מצטבר; ה-V300 ממילא יידרש לטפל |
| **AOS alignment** | TENSION — directive team_00 25.5 דרש "הרחבת תוכן לפני cutover" |
| **Risk** | MEDIUM (סטיית מן ההכוונה של team_00) |

**יתרונות:** הקצר ביותר ללאנץ'.
**חסרונות:** סותר לכאורה את הדירקטיב המקורי שלך · מותגית פחות מספק.

**המלצה:** **A** — כל ה-5, בסדר Q-NEW-03 → Q-02 → Q-05 → Q-03 → Q-11 (משימות מחרוזת קצרות קודם, אחר כך factrows). אם זמן לחוץ — **B**.

---

### Q2 — תור פוסטים חדשים *[פתוחה]*

**השאלה:** כמה פוסטים חדשים תרצה לפרסם לפני cutover, ומה המקור?

**פורמט תגובה מבוקש (בתוך ה-snippet):**
- `q2_count` — מספר (או טווח כמו "5–10")
- `q2_source` — אחד מ: `drafts_ready_in_doc` / `bullets_we_expand_together` / `full_co_authoring` / `mix`
- `q2_topics` — רשימה גסה של נושאים
- `q2_featured_image_status` — `have_for_all` / `have_for_some` / `none_use_V300_placeholder`

**השלכת ארכיטקטורה:** פוסטים = data טהור על T4. אם המספר > 30 נצטרך לכוון פגינציה (קונפיגורציית הבלוג היום ≤50). חוסר תמונה ראשית — T4 כבר תומך ב-fallback ללא תמונה.

---

### Q3 — רענון 22 הפוסטים הקיימים *[פתוחה]*

**השאלה:** אילו מ-22 הפוסטים המהוגרים צריכים רענון עריכתי לפני לאנץ'?

**פורמט תגובה מבוקש:** רשימה של `slug → reason → tag` (`language` / `facts` / `cross_link` / `imagery`), או `q3_none: true`.

**השלכת ארכיטקטורה:** רענון = data-only. בקשת "פוסטים קשורים" כאלמנט UI חדש ב-T4 = הרחבת תבנית; דורש GCR ל-team_35. הנחת ברירת מחדל: cross-links הם anchor inline בתוך body — בלי שינוי תבנית.

---

### Q4 — services / projects חדשים *[סגורה]*

**השאלה:** מעבר ל-6 ה-seeds (services: produce / consulting-hydro / sfa; projects: 3 seed slugs) — האם להוסיף instances חדשים?

#### אופציה A — להישאר עם 6, לדחות ל-V300

| מאפיין | ערך |
|---|---|
| **What** | publish בלי תוספות; להעריך ב-V300 |
| **Work cost** | LOW — 0 |
| **Dependencies** | אין |
| **Flexibility** | HIGH |
| **Novelty** | INCREMENTAL |
| **Short-term** | cutover מהיר; portfolio רזה |
| **Long-term** | עלול להרגיש דק; אנחנו ממילא נתעסק ב-V300 |
| **AOS alignment** | ALIGNED |
| **Risk** | LOW |

**יתרונות:** אפס עבודה · scope cutover נקי · design package §3 ציין "6 anchors מספיק".
**חסרונות:** עלול להרגיש דק בלאנץ' · מאבד הזדמנות הרושם ראשון.

#### אופציה B — להוסיף 1–3 מכל סוג (מומלץ)

| מאפיין | ערך |
|---|---|
| **What** | באצ' אחד של CPT instances חדשים דרך REST POST או admin UI; T2/T3 ללא שינוי |
| **Work cost** | MEDIUM — team_10, ~0.25 יום per instance |
| **Dependencies** | תוכן ממך (~15 שדות per instance) + תמונה (או V300 placeholder) |
| **Flexibility** | HIGH — instance חדש מתווסף/נמחק בקלות |
| **Novelty** | INCREMENTAL |
| **Short-term** | רוחב משמעותי ב-portfolio ללא שינוי תבנית |
| **Long-term** | מבסס את T2/T3 כתבנית מספקת; חוסך טיפול ב-V300 |
| **AOS alignment** | ALIGNED |
| **Risk** | LOW |

**יתרונות:** יחס מצוין בין רוחב לסיכון · אין שינוי תבנית · בודק בפועל שה-CPT schema מתאים למגוון רחב יותר.
**חסרונות:** דורש ממך תוכן עשיר לכל instance · צורך בתמונות (או placeholders).

#### אופציה C — קטלוג גדול (>3 מכל סוג)

| מאפיין | ערך |
|---|---|
| **What** | bulk authoring; אולי הרחבת T2/T3 עם sections אופציונליים |
| **Work cost** | HIGH — team_10, ≥1 יום + סקירת ארכיטקטורה |
| **Dependencies** | אישור שדות חסרים → אולי GCR ל-team_35 |
| **Flexibility** | MEDIUM (instances רבים = העלות לבטל גדלה) |
| **Novelty** | SIGNIFICANT (אם נדרשת הרחבת תבנית) |
| **Short-term** | cutover נדחה ב-3–5 ימים |
| **Long-term** | אתר נראה portfolio מלא |
| **AOS alignment** | TENSION — דורש סקירת מספיקות שדות לפני |
| **Risk** | MEDIUM (design-spec drift אם לא מתואם) |

**יתרונות:** מילוי portfolio מלא.
**חסרונות:** דוחה cutover · עלול לחייב GCR ל-team_35 · אובר-scope ל-V200.

**המלצה:** **B**. אם **C** — אבקש לפתוח ברף נפרד על מספיקות סכמת ה-CPT לפני.

---

### Q5 — broken link `/blog/back-to-mud/` *[סגורה]*

**השאלה:** הקישור הזה מופיע ב-T7 hero (או T1 lead related-entities, יאומת בבילד). היום 404. מה הפתרון?

#### אופציה A — לכתוב את הפוסט עכשיו

| מאפיין | ערך |
|---|---|
| **What** | publish של `/blog/back-to-mud/`; הקישור הופך תקין |
| **Work cost** | LOW — team_10 ~1 שעה + תוכן ממך |
| **Dependencies** | סיפור/נושא ממך |
| **Flexibility** | HIGH |
| **Novelty** | INCREMENTAL |
| **Short-term** | סוגר 404; ה-related-entities עובד |
| **Long-term** | פוסט נוסף בתיק |
| **AOS alignment** | ALIGNED |
| **Risk** | LOW |

**יתרונות:** משמר את הכוונה העריכתית המקורית · סוגר 404 לחלוטין.
**חסרונות:** דורש ממך תוכן חדש · +1 פריט באצ'.

#### אופציה B — להפנות ל-slug קיים

| מאפיין | ערך |
|---|---|
| **What** | עריכת הטמפלייט/דאטה כך שהקישור מצביע ל-post קיים |
| **Work cost** | LOW — team_10 דקות |
| **Dependencies** | אתה מציע slug חלופי סמנטית מתאים |
| **Flexibility** | HIGH |
| **Novelty** | INCREMENTAL |
| **Short-term** | סוגר 404 |
| **Long-term** | תלוי בהתאמת ההפניה |
| **AOS alignment** | ALIGNED |
| **Risk** | LOW |

**יתרונות:** אפס עבודת תוכן · מהיר.
**חסרונות:** התחליף חייב להיות מתאים סמנטית.

#### אופציה C — להסיר את הקישור

| מאפיין | ערך |
|---|---|
| **What** | מחיקת הקישור מהטמפלייט/דאטה |
| **Work cost** | LOW — team_10 דקות |
| **Dependencies** | אין |
| **Flexibility** | MEDIUM (החזרה דורשת לזכור איפה היה) |
| **Novelty** | INCREMENTAL |
| **Short-term** | hero/related-entities עלול להיראות דליל |
| **Long-term** | מאבד cross-reference מתוכנן |
| **AOS alignment** | ALIGNED |
| **Risk** | LOW |

**יתרונות:** הכי נקי תפעולית.
**חסרונות:** מאבד reference בכוונה תחילה · אולי דליל ויזואלית.

**המלצה:** **A** אם יש לך סיפור ל-`back-to-mud`; אחרת **B** עם slug חלופי. **C** רק כברירה אחרונה.

---

### Q6 — מסעדות עוגן (Q-05) *[פתוחה]*

**השאלה:** 3–5 שמות מסעדות שקונות מהשירות produce + אישור לפרסום פומבי.

**פורמט תגובה מבוקש:** רשימה של `{name, city, display_permission: yes/no/pending, logo_available: yes/no/V300}`. ניתן להשאיר `pending` ונפרסם בלי לוגו עד אישור.

**השלכת ארכיטקטורה:** name-only → factrow קיים ב-T2, ללא שינוי. אם דרושים לוגואים → רשת לוגואים חדשה ב-T2/T8 — הרחבת תבנית קלה, ללא GCR (לוגו = תוכן, לא design system).

---

### Q7 — נעילת טאגליין "Unless" (Q-NEW-03) *[סגורה]*

**השאלה:** "Unless" סופי (היום ב-T7 hero, T8 about, page metas, footer)?

#### אופציה A — נועלים "Unless"

| מאפיין | ערך |
|---|---|
| **What** | סוגרים את המחרוזת; אין שינויים נוספים |
| **Work cost** | LOW — 0 |
| **Dependencies** | אין |
| **Flexibility** | HIGH (החלפה אפשרית גם אחר כך) |
| **Novelty** | INCREMENTAL |
| **Short-term** | Yoast metas יציבות; ה-TBC נסגר |
| **Long-term** | זהות מותגית נעולה ל-V200 |
| **AOS alignment** | ALIGNED |
| **Risk** | LOW |

**יתרונות:** סוגר את ה-TBC הוותיק ביותר · יציבות מטא.
**חסרונות:** אין, אלא אם יש לך מחשבות שניות.

#### אופציה B — טאגליין חלופי

| מאפיין | ערך |
|---|---|
| **What** | אתה מספק מחרוזת חלופית; אני מבצע global replace דרך WP REST + עריכת טמפלייט + meta box + Yoast sync |
| **Work cost** | LOW–MEDIUM — team_10 ~1 שעה |
| **Dependencies** | מחרוזת חלופית ממך |
| **Flexibility** | HIGH לפני cutover; MEDIUM אחר כך (sitemap מתאינדקס) |
| **Novelty** | INCREMENTAL |
| **Short-term** | זהות מותגית מתעדכנת לפני לאנץ' |
| **Long-term** | מבסס את ה-tagline החדש |
| **AOS alignment** | ALIGNED |
| **Risk** | LOW |

**יתרונות:** הזדמנות אחרונה לכוון את הקול לפני שהוא בפרודקשן.
**חסרונות:** שינויים נוספים אחר cutover יקרים יותר.

#### אופציה C — וריאנטים פר משטח (T7 vs T8 vs footer vs meta)

| מאפיין | ערך |
|---|---|
| **What** | מחרוזות שונות פר משטח |
| **Work cost** | MEDIUM — team_10 ~2 שעות + תוכן |
| **Dependencies** | 4 גרסאות ממך |
| **Flexibility** | MEDIUM (תחזוקה גדולה יותר) |
| **Novelty** | SIGNIFICANT |
| **Short-term** | דיוק טונאלי |
| **Long-term** | מדלל זהירות מותגית; קשה לזכור איפה מה |
| **AOS alignment** | TENSION (Iron Rule רוח: עקביות מותגית) |
| **Risk** | MEDIUM (consistency drift) |

**יתרונות:** מקסימום דיוק טונאלי.
**חסרונות:** 4 מחרוזות לתחזק · Yoast meta מסתבך · עלול לדלל recall.

**המלצה:** **A**. אם **B** — שלח את המחרוזת בעברית מדויקת ב-snippet. **C** לא מומלץ בסקייל הזה.

---

### Q8 — מיתוג מיזו (Q-11) *[סגורה]*

**השאלה:** איך "מיזו" צריך להופיע?

#### אופציה A — sub-brand mention בלבד (ברירת מחדל קיימת)

| מאפיין | ערך |
|---|---|
| **What** | "דיגיטל / מיזו" נשאר ב-footer credit + אזכור בודד ב-about |
| **Work cost** | LOW — 0 |
| **Dependencies** | אין |
| **Flexibility** | HIGH |
| **Novelty** | INCREMENTAL |
| **Short-term** | אפס שינוי |
| **Long-term** | מיזו נשאר רקע, נמרוד הוא הסיפור הראשי |
| **AOS alignment** | ALIGNED |
| **Risk** | LOW |

**יתרונות:** אפס עבודה · ממקד את האתר באישיות נמרוד.
**חסרונות:** אם רוצים נראות גדולה יותר למיזו — לא יקרה.

#### אופציה B — full brand presence עם link

| מאפיין | ערך |
|---|---|
| **What** | footer + about מקושרים ל-URL מיזו; אולי לוגו קטן |
| **Work cost** | LOW — team_10 ~30 דקות |
| **Dependencies** | URL ממך · מדיניות external-link (target=_blank? rel=noopener?) · לוגו אם רלוונטי |
| **Flexibility** | HIGH |
| **Novelty** | INCREMENTAL |
| **Short-term** | חיבור מותגי גלוי בין שני הסיפורים |
| **Long-term** | תלוי כמה רחב מיזו רוצה להיות |
| **AOS alignment** | ALIGNED |
| **Risk** | LOW (small SEO juice leak ב-external link) |

**יתרונות:** מבסס את הקשר בין שני המותגים · מאפשר תנועה.
**חסרונות:** דורש החלטות מדיניות קישור · דליפת link-equity מינורית.

#### אופציה C — subpage ייעודי `/about/mezoo/`

| מאפיין | ערך |
|---|---|
| **What** | T8 instance חדש שמתאר את היחס בין נמרוד-bio למיזו |
| **Work cost** | MEDIUM — team_10 ~חצי יום (תוכן + page + nav) |
| **Dependencies** | תוכן ארוך ממך · החלטה איך מיזו מופיעה ב-nav |
| **Flexibility** | MEDIUM (חזרה אחורה דורשת מחיקת page + redirects) |
| **Novelty** | SIGNIFICANT |
| **Short-term** | מרחיב את היקף האתר |
| **Long-term** | מערבב שני סיפורי מותג; עלול לבלבל מבקרים חדשים |
| **AOS alignment** | TENSION (scope creep ל-V200) |
| **Risk** | MEDIUM (brand muddiness) |

**יתרונות:** הסבר מלא של הקשר.
**חסרונות:** ערבוב מותגי · scope creep.

**המלצה:** **A** אלא אם אתה רוצה נראות גדולה יותר למיזו. אם **B** — שלח: target URL, נתיב לוגו או "text only", מדיניות לינק.

---

### Q9 — מודל תמחור SFA (Q-02) *[סגורה]*

**השאלה:** המודל המסחרי של SFA כפי שהוא מוצג באתר?

#### אופציה A — declared free (פתוח/חופשי)

| מאפיין | ערך |
|---|---|
| **What** | T2 sfa CTA = "Use it" / "השתמש בכלי"; אין copy תמחורי; אין purchase flow |
| **Work cost** | LOW — 0 |
| **Dependencies** | אין |
| **Flexibility** | HIGH (אפשר לעבור ל-B בעתיד) |
| **Novelty** | INCREMENTAL |
| **Short-term** | פשטות מקסימלית |
| **Long-term** | מודל הכנסה לא נראה |
| **AOS alignment** | ALIGNED |
| **Risk** | LOW |

**יתרונות:** הכי פשוט · אין commerce plumbing · מתאים לסיפור open-tool.
**חסרונות:** מודל הכנסה לא נראה.

#### אופציה B — commercial-free (חופשי לשימוש, שירותים בתשלום סביב)

| מאפיין | ערך |
|---|---|
| **What** | T2 sfa CTA = "דבר איתנו"; copy ב-T1 know מסביר "כלי חופשי + שירותי אינטגרציה בתשלום" |
| **Work cost** | LOW — team_10 ~1 שעה + ~50 מילים ממך |
| **Dependencies** | copy ממך · CTA יעד = contact form |
| **Flexibility** | HIGH |
| **Novelty** | INCREMENTAL |
| **Short-term** | מודל הכנסה גלוי |
| **Long-term** | מסר עקבי בין הכלי לשירות |
| **AOS alignment** | ALIGNED |
| **Risk** | LOW |

**יתרונות:** אמיתי · מאפשר משפך לקוחות · עלות נמוכה.
**חסרונות:** דורש ממך ~50 מילות copy.

#### אופציה C — מוצר בתשלום

| מאפיין | ערך |
|---|---|
| **What** | pricing page + purchase CTA |
| **Work cost** | HIGH — דורש commerce infra (WooCommerce או חלופה) |
| **Dependencies** | תוסף commerce חדש |
| **Flexibility** | LOW (הפיכה דורשת מחיקת מערכת) |
| **Novelty** | PARADIGM_SHIFT |
| **Short-term** | cutover נדחה ב-≥שבוע |
| **Long-term** | מחייב תחזוקת commerce |
| **AOS alignment** | CONFLICT — Iron Rule "no new plugins unless infrastructure-class" |
| **Risk** | HIGH |

**יתרונות:** מודל הכנסה ישיר.
**חסרונות:** סותר את ה-Iron Rule הקיים · מחוץ ל-scope V200.

**המלצה:** **B**. **A** מקובל אם אתה רוצה פשטות מקסימלית. **C** מחוץ ל-scope V200 (סותר Iron Rule).

---

### Q10 — מקומות הוראה (Q-03) *[פתוחה]*

**השאלה:** איפה אתה מלמד באופן קבוע (מוסדות/תוכניות/קוהורטות)?

**פורמט תגובה מבוקש:** רשימה של `{name, type: academic/private_program/self_run, city_or_remote, frequency, public_mention_ok: yes/no}`.

**השלכת ארכיטקטורה:** 1–3 מקומות = factrow קיים ב-T8 + body ב-T2 know — data-only. >5 או רשת לוח זמנים חוזר → רכיב "timeline הוראה" קטן ב-T8 (הרחבת תבנית ~חצי יום + ייתכן GCR ל-team_35 אם הטיפול הויזואלי שונה).

---

### Q11 — חלון זמן (effort window) *[סגורה]*

**השאלה:** חלון לוח שנה לשלב התוכן? cutover (P005-WP002) מוקפא עד שתסמן "סיימנו".

#### אופציה A — צמודה (≤ שבוע)

| מאפיין | ערך |
|---|---|
| **What** | באצ'ים אגרסיביים (2 גדולים); כל מה שדורש תיאום/lookup → V300 |
| **Work cost** | HIGH per batch — team_10 |
| **Dependencies** | זמינות שלך אינטנסיבית לכמה ימים |
| **Flexibility** | MEDIUM (אם מתעכב, סדק ב-cutover) |
| **Novelty** | INCREMENTAL |
| **Short-term** | cutover תוך 7 ימים |
| **Long-term** | נופלים על V300 הכל מה שלא נכנס |
| **AOS alignment** | ALIGNED |
| **Risk** | LOW |

**יתרונות:** cutover מהיר · momentum.
**חסרונות:** פחות מקום ל-iteration על copy · מצריך זמינות שלך.

#### אופציה B — מדודה (1–3 שבועות) (מומלץ)

| מאפיין | ערך |
|---|---|
| **What** | 3–4 באצ'ים של ~3-5 פריטים; סקירת ארכיטקטורה לכל באצ' |
| **Work cost** | MEDIUM per batch |
| **Dependencies** | זמינותך פתוחה לבחינת תוכן בין באצ'ים |
| **Flexibility** | HIGH |
| **Novelty** | INCREMENTAL |
| **Short-term** | cutover תוך ~שבועיים |
| **Long-term** | איכות תוכן גבוהה ביותר ללאנץ' |
| **AOS alignment** | ALIGNED |
| **Risk** | LOW |

**יתרונות:** איכות תוכן · סדר עבודה נינוח · מצריך פחות זמן צמוד שלך.
**חסרונות:** cutover מאוחר יותר ב-שבוע-שבועיים.

#### אופציה C — open-ended ("כשזה ייצא נכון")

| מאפיין | ערך |
|---|---|
| **What** | אין לחץ לו"ז; התוכן מכתיב |
| **Work cost** | בלתי מוגבל |
| **Dependencies** | משמעת תוכן מצידך |
| **Flexibility** | מקסימלית, אך נוטה ל-drift |
| **Novelty** | INCREMENTAL |
| **Short-term** | cutover לא מוגדר |
| **Long-term** | סיכון ש-V200 יתמזג עם V300 ויאבד את ה-milestone שלו |
| **AOS alignment** | TENSION (ההגדרה של V200 כ-discrete milestone מתערערת) |
| **Risk** | MEDIUM (drift) |

**יתרונות:** איכות לפני הכל.
**חסרונות:** cutover נדחה ללא תאריך · scope creep.

**המלצה:** **B** — מתאים לנפח שצפוי מ-Q1+Q2+Q4. אתאם תכנון ל-~שבועיים ואתקן לפי תשובות Q2/Q4.

---

## 6. מטריצת השוואה (שאלות סגורות בלבד)

| Q | המלצה | Work cost | Risk | תיקוף Iron Rule | Reversibility |
|---|---|---|---|---|---|
| Q1 | A | MEDIUM | LOW | ✓ #6 #7 | HIGH |
| Q4 | B | MEDIUM | LOW | ✓ #7 | HIGH |
| Q5 | A או B | LOW | LOW | ✓ | HIGH |
| Q7 | A | LOW | LOW | ✓ | HIGH |
| Q8 | A | LOW | LOW | ✓ | HIGH |
| Q9 | B | LOW | LOW | ✓ Iron Rule plugins | HIGH |
| Q11 | B | n/a | LOW | ✓ | HIGH |

## 7. סיכום המלצות בשורה

- **Q1** → A (סדר Q-NEW-03 → Q-02 → Q-05 → Q-03 → Q-11)
- **Q2** → צריך נתון ממך
- **Q3** → צריך רשימה ממך (או `none`)
- **Q4** → B (1–3 services + 1–3 projects)
- **Q5** → A אם יש סיפור, אחרת B
- **Q6** → צריך 3–5 שמות + אישור פומבי
- **Q7** → A (נועלים "Unless")
- **Q8** → A (sub-brand)
- **Q9** → B (commercial-free)
- **Q10** → צריך רשימה ממך
- **Q11** → B (1–3 שבועות)

## 8. Open parameters (אי-ודאויות שלא הוכרעו)

- **OP-1** — חסימת פוסט בלי תמונה ראשית? ברירת מחדל שלי: לא, T4 תומך ב-no-image fallback.
- **OP-2** — Google Search Console resubmission בין באצ'ים? ברירת מחדל שלי: רק בסוף השלב.
- **OP-3** — לפתוח תוכנית `P006 — Content Expansion` ב-`roadmap.yaml` ולסדר WPs לפי באצ'ים? ברירת מחדל שלי: כן (אבקש מ-team_100 לרשום).

---

## 9. Response snippet — מילוי תגובה

```yaml
─── תגובה / RESPONSE ─────────────────────────────────────────────
# DECISION_BRIEF_V200_CONTENT_PHASE_INTAKE_2026-05-26_v1.1.0 — תגובת team_00
date: 2026-05-26
from: team_00

# Q1 — סדר TBC
q1_order:                    # ordered list. ברירת מחדל: [Q-NEW-03, Q-02, Q-05, Q-03, Q-11]
q1_deferred_to_v300:         # רשימה
q1_dropped:                  # רשימה
q1_choice:                   # A | B | C

# Q2 — פוסטים חדשים
q2_count:                    # int או טווח
q2_source:                   # drafts_ready_in_doc | bullets_we_expand_together | full_co_authoring | mix
q2_topics:                   # רשימה
q2_featured_image_status:    # have_for_all | have_for_some | none_use_V300_placeholder

# Q3 — רענון 22 פוסטים
q3_refresh:                  # [{slug, reason, tags}] או q3_none: true
q3_none:                     # true/false

# Q4 — services/projects חדשים
q4_choice:                   # A | B | C
q4_new_services:             # רשימת labels
q4_new_projects:             # רשימת labels

# Q5 — broken link back-to-mud
q5_choice:                   # A | B | C
q5_post_topic:               # אם A
q5_substitute_slug:          # אם B

# Q6 — מסעדות
q6_restaurants:              # [{name, city, display_permission, logo_available}]

# Q7 — טאגליין
q7_choice:                   # A | B | C
q7_new_tagline:              # אם B
q7_variants:                 # {t7_hero, t8_about, footer, meta} אם C

# Q8 — מיזו
q8_choice:                   # A | B | C
q8_mezoo_url:                # אם B/C
q8_logo:                     # path או "text_only"

# Q9 — תמחור SFA
q9_choice:                   # A | B | C (C מחוץ ל-scope V200)
q9_paid_services_copy:       # ~50 מילים אם B

# Q10 — מקומות הוראה
q10_locations:               # [{name, type, city_or_remote, frequency, public_mention_ok}]

# Q11 — חלון זמן
q11_choice:                  # A | B | C

# Open parameters
op1_block_post_without_image:    # true/false (default: false)
op2_gsc_resubmission_between_batches: # true/false (default: false)
op3_open_p006_program:           # true/false (default: true)

# פתוח
modify:                          # שינויים שאני צריך לבצע במסגרת זו
defer:                           # פריטים לדחות ל-V300
notes:                           # חופשי
──────────────────────────────────────────────────────────────────
```

---

## 10. מה קורה אחרי התגובה שלך

1. שומר את התגובה כ-`_COMMUNICATION/team_110/CONTENT_PHASE_INTAKE_2026-05-26_v1.0.0.md`.
2. מפיק Phase Plan (A → B → C → D) עם מספר באצ'ים והערכת לוח שנה ל-`_COMMUNICATION/team_00/`.
3. פותח באצ' ראשון: `LOD400_CONTENT_BATCH_001.md` (team_110) + `MANDATE_CONTENT_BATCH_001.md` ל-team_10.
4. P005-WP002 נשאר DEFERRED — נפתח רק כש-`COMPLETION_CONTENT_PHASE_*.md` מ-team_110 חותם.

— team_110 (cursor-composer-2) — 2026-05-26
