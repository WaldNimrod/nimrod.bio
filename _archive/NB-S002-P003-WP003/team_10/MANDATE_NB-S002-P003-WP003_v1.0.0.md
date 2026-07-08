---
type: MANDATE
from: team_100 (nimrodbio_arch)
to: team_10 (Cursor session #3 of 5 parallel)
wp_id: NB-S002-P003-WP003
project: nimrod-bio
milestone: V200
program: P003
date: 2026-05-25
gate: L-GATE_SPEC PASS → L-GATE_BUILD
track: A · STANDARD
priority: HIGH
spec_ref: _aos/work_packages/NB-S002-P003-WP003/LOD400_NB-S002-P003-WP003.md
program_ref: _aos/work_packages/S002/P003/LOD300_P003_program.md
---

# MANDATE — NB-S002-P003-WP003 — T2 Services + T3 Projects

**לצוות 10 (session #3 of 5 · T2 + T3):**

2 single-* templates + 6 instances (3 services + 3 projects). זה ה-WP הגדול ביותר ב-P003.

## 📖 קרא בסדר הזה
1. `_aos/work_packages/S002/P003/LOD300_P003_program.md`
2. `_aos/work_packages/NB-S002-P003-WP003/LOD400_NB-S002-P003-WP003.md`

## תוצרים

- `single-service.php`, `single-project.php`
- 10 template-parts (t2-* × 5 + t3-* × 5)
- `assets/css/t2.css`, `assets/css/t3.css`
- `inc/template-styles-t2-t3.php`
- **6 seed instances** via REST POST (תוכן verbatim מ-`T*-instances.jsx`)

+ עדכון `NB_THEME_VERSION` ל-`0.3.2`.

## כללי-זהב

1. **Heritage strip ONLY on `produce` slug**. אחרים — לא
2. **SFA Origin flow ONLY on `sfa` slug**. אחרים — לא
3. **Seeking ribbon ONLY for stage=seeking-partners**. Legacy ribbon ONLY for stage=legacy
4. **Seed instances מסומנים `_nb_seed=v200`** — נשארים, לא נמחקים
5. Test records (תוצר בדיקות C1-C10 וכו') כן נמחקים
6. שאר כללי P003

## Exit criteria

- [ ] 13 קבצים tracked
- [ ] 6 seed instances ב-REST (verify GET)
- [ ] 15 בדיקות S1-S15 PASS
- [ ] baseline §11 PASS
- [ ] git push + version bump

## תזמון

6 ימי עבודה (הארוך ביותר ב-P003). VALIDATE cross-engine.

— team_100 (nimrod-bio) — 2026-05-25
