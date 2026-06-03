# Nimrod.bio / AOS — חבילת Handoff מלאה

**תאריך:** 2026-06-03 · **גרסה:** v5 (precision SSoT) · **צוות:** team_35 (Site Design + Build)
**WP:** NB-S002-P009-WP001 · **theme:** nimrod-bio-2026

חבילה מאוחדת אחת לקראת ה-WP של **המימוש המלא (full implementation)** של כל המוקאפים.
כוללת את מקור-האמת העיצובי (SSoT), שכבת ה-build לתמה החיה, רכיבים, brand, ונכסים.

---

## 🎯 התחל מכאן

1. פתח **`01_design_ssot/Precision Mockup v5 — standalone (offline).html`** — קובץ אחד, עובד אופליין, כולל את **כל סוגי העמודים**.
   בסרגל העליון יש מתגי מסך: T7 · T1 · T2 · **שירות·single** · T3 · T4 · T5 · צור-קשר · אודות · **מורשת** · 404/חיפוש · מצבים.
2. במסך **T1 (עולם)** — לחץ על שבבי **אדמה / ידע / דיגיטל** כדי לראות את ריקול-האקסנט (soil=ירוק, know=כתום, code=טורקיז). אותו layout, accent בלבד.
3. לקריאת ההיררכיה והסקופ — המשך כאן למטה.

---

## 📐 מקור-האמת (SSoT) — מה קובע מה

| שכבה | מה זה | קובץ |
|---|---|---|
| **screen SSoT** | האוטוריטה הויזואלית לכל page-type. בונים ומאמתים מולה. | `01_design_ssot/Precision Mockup v5.html` |
| **build / delta** | איך המסכים יושבים על התמה החיה (template-parts, module CSS, CPT, תיקונים). | `04_build_layer/` |
| **brand foundation** | tokens, טיפוגרפיה, voice, taxonomy נעולה. | `02_brand/` |
| **components** | אוצר-מילים ויזואלי (foundations + library + Bridge). | `03_components/` |
| **reference (stage3)** | הפרוטוטייפים לכל-תבנית + data/instances. **נגזר ל-v5 כאוטוריטת precision** — שמור כהקשר/data בלבד. | `05_reference_stage3/` |
| **brand assets** | לוגו, favicons, og-image, washes. | `06_brand_assets/` |

> **כלל:** במחלוקת בין קבצים — **v5 קובע** את המראה; שכבת ה-build קובעת את ההשתלה בתמה; `brand/system.css` נעול ל-tokens.

---

## 🗂 מבנה החבילה

```
HANDOFF_nimrod-bio_2026-06-03/
├── README.md                         ← הקובץ הזה
├── 01_design_ssot/
│   ├── Precision Mockup v5.html       ← ה-SSoT (מקור, ניתן לעריכה)
│   ├── Precision Mockup v5 — standalone (offline).html  ← קובץ-יחיד לצפייה
│   └── images/                        ← כל הנכסים שה-SSoT מצריך (raw + baskets)
├── 02_brand/
│   ├── system.css                     ← design tokens (LOCKED)
│   ├── typography.md · voice.md
│   ├── TAXONOMY-v3.4-LOCKED.md         ← הטקסונומיה הנעולה הקובעת
│   ├── TAXONOMY-v3.3-LOCKED.md         ← (superseded)
│   └── site-context-2026-05-v2.md      ← CANONICAL (team_100)
├── 03_components/
│   ├── Foundations.html · Components.html · Components v3 - Bridge.html
├── 04_build_layer/
│   ├── HANDOFF_CLAUDE_CODE_V200/        ← theme parts, archive-project, cpt, §06, previews
│   │   ├── theme/ (front-page, archive-project, inc/cpt-project, template-styles-t1, assets/css)
│   │   ├── G2_G3_C2_PRECISION_2026-06-03_v1.md
│   │   └── *Preview.html · README.md
│   ├── DESIGN_COMPLETENESS_REPLY_team_100_2026-06-03_v1.md  ← תשובת rows 1–5
│   ├── TEMPLATE_COVERAGE_AUDIT_2026-06-02_v1.md
│   └── COMPLETION_PRECISION_SESSION_V4_2026-06-02_v1.md
├── 05_reference_stage3/                 ← T1–T8 prototypes + css/ (הקשר/data)
└── 06_brand_assets/                     ← logo-master.svg, favicons, og-image, washes
```

---

## 🧭 מפת page-type → תבנית → מסך ב-SSoT

| Page-type | תבנית בתמה | מסך ב-v5 | סטטוס precision |
|---|---|---|---|
| Home (T7) | `front-page.php` | `t7` | ✅ |
| World soil/know/code (T1) | `page-soil/know/code.php` | `t1` + **world-switcher** | ✅ (accent-only variants) |
| Services index (T2) | `archive-service.php` | `t2` | ✅ |
| **Service single (T2)** | `single-service.php` | **`t2s`** | ✅ (חדש ב-v5) |
| Project single (T3) | `single-project.php` | `t3` | ✅ |
| Post single (T4) | `single.php` | `t4` | ✅ |
| Blog index (T5) | `home.php` | `t5` | ✅ |
| About (T8) | `page-about.php` | `about` | ✅ |
| Contact (T8) | `page-contact.php` | `contact` | ✅ |
| **Heritage (T8)** | `page-heritage.php` | **`heritage`** | ✅ (חדש ב-v5) |
| 404 / Search / Empty | `404.php` / `search.php` | `sys` | ✅ |
| States (spec) | — | `states` | ✅ (מפרט, לא עמוד חי) |
| **Projects archive** | `archive-project.php` *(חסר)* | — | ⚠ **לבנות ב-WP** (G1) |

---

## ⚠ הפער הפתוח היחיד — Projects archive (G1)

`inc/cpt-project.php` רשום עם `has_archive => false` → `/projects/` לא קיים, אבל קישורים מצביעים אליו.
**העיצוב כבר קיים** (preview ב-`04_build_layer/HANDOFF_CLAUDE_CODE_V200/`, ושימוש-חוזר ב-`.proj-card`).
**משימת build:** `has_archive => 'projects'` + `archive-project.php`. אין צורך בשפה ויזואלית חדשה.

---

## 🔒 Locks / constraints (לא להפר)

- עריכות נוחתות ב-**module CSS + template-parts** בלבד. אין inline, אין שכבת overrides, **`system.css` נעול**.
- שני super-locks (Micha; demonstrate-never-name) על כל בייט — markup, alt/aria, הערות.
- **RTL logical properties** (`inset-inline-*`, `padding-block`, `margin-inline`).
- a11y non-regression: baseline WP006 — axe 0, Lighthouse a11y ≥ 95.
- אין filler / אין "TBD" בתוכן — placeholders מסומנים בלבד.

## ✅ qa_probe — מה לאמת מול v5 (375 + 1440)

- **T2** index + single — יישור svc-card grid, hero + measure.
- **T3/T4/T5** — יחס תמונת hero, חותמות scope/stage, measure גוף ~66ch, TOC, world-filter.
- **T1 know/code** — אקסנט מתחלף (כתום/טורקיז), soil ללא שינוי. ה-CSS override (+25 שורות) נוחת כחלק מה-WP (לא hot-patch).
- **Heritage** — dropcap, h2 ממוספר, pullquotes, blockquote, heritage-end.

---

*team_35 · full handoff · 2026-06-03 · v5 precision SSoT · כל page-type מכוסה · נותר build אחד (projects archive)*
