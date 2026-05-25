---
type: MANDATE
from: team_100 (nimrodbio_arch)
to: team_10 (Cursor session #2 of 5 parallel)
wp_id: NB-S002-P003-WP002
project: nimrod-bio
milestone: V200
program: P003
date: 2026-05-25
gate: L-GATE_SPEC PASS → L-GATE_BUILD
track: A · STANDARD
priority: HIGH
spec_ref: _aos/work_packages/NB-S002-P003-WP002/LOD400_NB-S002-P003-WP002.md
program_ref: _aos/work_packages/S002/P003/LOD300_P003_program.md
---

# MANDATE — NB-S002-P003-WP002 — T1 World pages

**לצוות 10 (session #2 of 5 · T1 Worlds):**

3 דפי עולם — soil/know/code — כל אחד **Variant C** עם bridge signal **seam** (שתיהן נעולות מ-team_35).

## 📖 קרא בסדר הזה
1. `_aos/work_packages/S002/P003/LOD300_P003_program.md`
2. `_aos/work_packages/NB-S002-P003-WP002/LOD400_NB-S002-P003-WP002.md`

## תוצרים

- `page-{soil,know,code}.php` × 3 (thin wrappers)
- `template-parts/t1-body.php` + 4 t1-* partials
- `assets/css/t1.css`
- `inc/template-styles-t1.php`
- Helper הוספה ל-`template-helpers.php`: `nb_get_bridges_for_world()`

+ עדכון `NB_THEME_VERSION` ל-`0.3.1`.

## כללי-זהב

1. **Variant C נעול. Signal seam נעול.** אסור A/B variants
2. כל world בונה משתי גשרים בלבד (לא 3-way bridges)
3. אם anchor service חסר ל-world → placeholder TBD + note ב-COMPLETION
4. כל ה-rest של כללי P003 (LOD300 §11)

## Exit criteria

ב-`COMPLETION_NB-S002-P003-WP002.md`:
- [ ] 3 + 5 קבצים tracked
- [ ] 14 בדיקות W1-W14 PASS
- [ ] baseline §11 PASS
- [ ] git push + version bump

## תזמון

4 ימי עבודה. VALIDATE cross-engine אחרי COMPLETION.

— team_100 (nimrod-bio) — 2026-05-25
