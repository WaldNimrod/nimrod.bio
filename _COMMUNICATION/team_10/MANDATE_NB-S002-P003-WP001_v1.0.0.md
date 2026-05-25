---
type: MANDATE
from: team_100 (nimrodbio_arch — Cursor's Claude)
to: team_10 (nimrodbio_build — Cursor session #1 of 5 parallel)
wp_id: NB-S002-P003-WP001
project: nimrod-bio
milestone: V200
program: P003 (templates cascade · 5 parallel WPs)
date: 2026-05-25
gate: L-GATE_SPEC PASS → entering L-GATE_BUILD
track: A · STANDARD
priority: HIGH
predecessor: NB-S002-P002-WP002 (COMPLETE)
spec_ref: _aos/work_packages/NB-S002-P003-WP001/LOD400_NB-S002-P003-WP001.md
program_ref: _aos/work_packages/S002/P003/LOD300_P003_program.md
---

# MANDATE — NB-S002-P003-WP001 — T7 Home template

**לצוות 10 (session #1 of 5 parallel · T7 Home):**

ראשון מ-5 templates שרצים במקביל. CPTs מוכנים. Theme שלד פעיל. עכשיו אתה בונה את ה-front-page (`/`).

## 📖 קרא בסדר הזה

1. **`_aos/work_packages/S002/P003/LOD300_P003_program.md`** — דפוסים משותפים ל-5 ה-templates. **קרא לעומק** — מגדיר helpers משותפים, asset enqueue extension point, query patterns, conventions
2. **`_aos/work_packages/NB-S002-P003-WP001/LOD400_NB-S002-P003-WP001.md`** — ה-SSOT שלך, ממוקד ב-T7

## תוצרים

3 קבצים חדשים:
- `front-page.php`
- `assets/css/t7.css`
- `inc/template-styles-t7.php`

+ עדכון `NB_THEME_VERSION` ל-`0.3.0`.

## כללי-זהב (ספציפיים ל-P003)

1. **לא לגעת בfunctions.php אחרי הוספת `glob()` ה-template-styles** (אם עוד לא קיים — אתה הראשון ומוסיף פעם אחת בלבד)
2. **לא לערוך system.css או shell.css** — אם משהו חסר, GCR לטים_35 דרך team_100
3. **כל helper משותף שתוסיף ל-`template-helpers.php` — תעד ב-COMPLETION** (אחרים יראו אותו)
4. **Hero locked: `statement`. Unless locked: `ribbon`** — מ-team_35 §1 תשובה לצוות 100. אסור variants
5. **10 בדיקות H1-H10 + baseline §11 program** — כולן עם evidence
6. **WP002-2 lesson: ניקוי test records לפני COMPLETION**
7. **git add + commit + push לפני COMPLETION** (אל תפספס שוב!)

## Exit criteria

ב-`_COMMUNICATION/team_10/COMPLETION_NB-S002-P003-WP001.md`:
- [ ] 3 קבצים חדשים tracked + on server
- [ ] `NB_THEME_VERSION = 0.3.0`
- [ ] H1-H10 PASS עם evidence
- [ ] Lighthouse run + תוצאות
- [ ] `validate_aos.sh` 0 net-new FAILs
- [ ] לוג סטיות (אם היו) מ-LOD400

## תזמון

- Start: מיד
- Target: 3 ימי עבודה
- VALIDATE: team_190 cross-engine אחרי COMPLETION

— team_100 (nimrod-bio) — 2026-05-25
