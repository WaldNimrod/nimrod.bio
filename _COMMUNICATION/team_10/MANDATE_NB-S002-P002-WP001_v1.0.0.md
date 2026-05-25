---
type: MANDATE
from: team_100 (nimrodbio_arch — Chief Architect)
to: team_10 (nimrodbio_build — Domain Builder, Cursor)
wp_id: NB-S002-P002-WP001
project: nimrod-bio
milestone: V200
date: 2026-05-25
gate: L-GATE_SPEC PASS → entering L-GATE_BUILD
track: A · STANDARD
priority: HIGH
predecessor: NB-S002-P001-WP001 (COMPLETE)
successor: NB-S002-P002-WP002 (CPTs — blocked until this lands)
authorization: team_00 approval 2026-05-25 (in-session)
spec_ref: _aos/work_packages/NB-S002-P002-WP001/LOD400_NB-S002-P002-WP001.md
---

# MANDATE — NB-S002-P002-WP001 — Custom theme skeleton

**לצוות 10 (Builder — Cursor):**

WP001 הושלם, חתום, ב-roadmap. עכשיו ה-WP הראשון בטרק STANDARD של V200 — שלד ה-theme.

## הקשר

dev env כבר עומד עם WP 7.0, permalinks `/blog/%postname%/`, MU plugin פעיל, App Password עובד. עכשיו בונים את ה-theme `nimrod-bio-2026` שיחליף את `twentytwentyfive` הזמני. **שלד בלבד — Shell + Footer + asset plumbing.** ללא templates, ללא CPTs (אלה WP003+ ו-WP002-2 בהתאמה).

## המפרט המלא

🎯 **`_aos/work_packages/NB-S002-P002-WP001/LOD400_NB-S002-P002-WP001.md`**

זה ה-SSOT למשימה. קרא מקצה לקצה לפני שאתה כותב שורת קוד. הוא מכיל:

- §3 — מבנה תיקיות מלא (16 קבצים)
- §4 — תוכן file-by-file (PHP מלא, CSS, JSON, SVG — קוד מוכן להעתקה לרוב)
- §5 — procedure אקטיבציה (FTP upload + REST API activation)
- §6 — 15 בדיקות acceptance (T1–T15)
- §7 — תהליך VALIDATE ע״י team_190
- §8 — out of scope מפורט (חשוב — אל תרחיב)
- §9 — risk register
- §10 — אומדן: 3 ימי עבודה

## כללי-זהב לפני שמתחילים

1. **ה-system.css נעול.** העתקה verbatim בלבד. אל תיגע ב-tokens. אם משהו חסר/שבור — דרוש GCR לצוות 35 דרך team_100.
2. **Shell HTML הוא תרגום ישיר 1:1 מ-JSX**: lines 311–375 של `sources/team_35_design_package/_handoff/templates/T1-data.jsx`. הטקסט העברי חייב להיות זהה — לא להחליף "ייעוץ והוראה" ל"ידע" וכו'.
3. **shell.css חולץ מ-T1-styles.css** — lines 31–119 (nav) + 468–501 (footer). LOD400 §4.14 מפרט את ה-selectors המדויקים.
4. **theme.json עם 12 צבעים נעולים, `customDuotone: false`, `defaultPalette: false`** — לא להוסיף ולא להחסיר.
5. **לא Twig, לא Timber, לא React, לא npm/webpack/vite.** PHP native + `wp_enqueue_*`.
6. **`sources/team_35_design_package/`** ב-gitignore — לעולם לא לעשות commit לקבצים משם. רק לקרוא.
7. **אסור לגעת ב-prod** (`nimrod.bio` הראשי).
8. **drift קיים ב-`_aos/` לא באחריותך** — אל תיגע.

## Activation flow (תזכורת מ-LOD400 §5)

```bash
# 1. צור את כל הקבצים תחת nimrod.bio/wp-content/themes/nimrod-bio-2026/
# 2. העלה ב-FTPS (creds ב-.env.upress.dev)
# 3. הפעל ב-REST:
set -a; source .env.upress.dev; set +a
curl -X POST -u "$WP_REST_USER:$WP_REST_APP_PASSWORD" \
     "$WP_REST_BASE_URL/wp/v2/themes/nimrod-bio-2026" \
     -H "Content-Type: application/json" \
     -d '{"status":"active"}'
# 4. הרץ את 15 בדיקות T1–T15
```

## Exit criteria — L-GATE_BUILD

חזרה לטיפול team_100 כש-COMPLETION report ב-`_COMMUNICATION/team_10/COMPLETION_NB-S002-P002-WP001.md` כולל:

- [ ] כל 16 הקבצים שב-LOD400 §3 קיימים ועל השרת
- [ ] Theme פעיל (`curl` ל-`/wp-json/wp/v2/themes/nimrod-bio-2026` → `"status":"active"`)
- [ ] 15 בדיקות T1–T15 מ-LOD400 §6 כולן PASS, עם evidence (curl outputs / screenshots)
- [ ] Lighthouse run על `/` עם תוצאות מצורפות (Performance ≥85, A11y ≥95, SEO ≥90)
- [ ] `validate_aos.sh` — 0 net-new FAILs
- [ ] git commit + push לכל deliverables (ה-theme directory בלבד; לא לגעת ב-drift חיצוני)
- [ ] לוג קצר של כל סטיות מ-LOD400 (אם היו) — תיעוד שקוף לטובת VALIDATE

## L-GATE_VALIDATE

**STANDARD track — דרוש.** עם COMPLETION, team_100 יזמין את team_190 (Codex/OpenAI — שונה מ-Cursor engine שלך) לבצע ולידציה חוצת-מנועים. אל תפתח את WP002-2 לפני שתקבל VERDICT_PASS.

## תזמון

- **Start:** מיד.
- **Target completion:** 3 ימי עבודה (לפי LOD400 §10).
- **Status updates:** ב-`_COMMUNICATION/team_10/` אם חוסם משהו מעבר ל-4 שעות.

## תלות פתוחה — לא חוסמת

הפריטים הבאים פתוחים על team_00 ולא חוסמים אותך:
- 5 screenshots של uPress control panel (audit completion)
- Basic auth ב-edge (אופציה A או B מ-MANDATE הקודם)

אם תזדקק ל-uPress panel בעצמך לאיזושהי סיבה (לא צפוי), פנה ל-team_100.

---

## Reference

- LOD400 (SSOT למשימה): `_aos/work_packages/NB-S002-P002-WP001/LOD400_NB-S002-P002-WP001.md`
- LOD300 milestone: `_aos/work_packages/S002/LOD300_V200_milestone.md`
- COMPLETION של WP001 (תקדים): `_COMMUNICATION/team_10/COMPLETION_NB-S002-P001-WP001.md`
- Design package (read-only): `sources/team_35_design_package/_handoff/`
- DEV env (gitignored): `.env.upress.dev`
- Hub roadmap entry: registered as IN_PROGRESS

— team_100 (nimrod-bio) — 2026-05-25
