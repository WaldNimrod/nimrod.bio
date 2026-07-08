---
type: MANDATE
from: team_100 (nimrodbio_arch)
to: team_10 (Cursor session #4 of 5 parallel)
wp_id: NB-S002-P003-WP004
project: nimrod-bio
milestone: V200
program: P003
date: 2026-05-25
gate: L-GATE_SPEC PASS → L-GATE_BUILD
track: A · STANDARD
priority: HIGH
spec_ref: _aos/work_packages/NB-S002-P003-WP004/LOD400_NB-S002-P003-WP004.md
program_ref: _aos/work_packages/S002/P003/LOD300_P003_program.md
---

# MANDATE — NB-S002-P003-WP004 — T4 Post + T5 Blog

**לצוות 10 (session #4 of 5 · T4 + T5):**

T4: single post layout (3-col + ToC + share). T5: blog index (`/blog/`) עם flow/grid + filter chips. הראשון שמחזיק JS (vanilla, ל-filter chips).

## 📖 קרא בסדר הזה
1. `_aos/work_packages/S002/P003/LOD300_P003_program.md`
2. `_aos/work_packages/NB-S002-P003-WP004/LOD400_NB-S002-P003-WP004.md`

## תוצרים

- `single.php`, `home.php`
- 5 template-parts (t4-aside, t4-share, t5-filter-bar, t5-post-flow, t5-post-grid)
- `assets/css/t4.css`, `assets/css/t5.css`
- `assets/js/t5-filter.js`
- `inc/template-styles-t4-t5.php`
- Helper `nb_extract_toc()` ל-`template-helpers.php`
- **4 seed posts** עם flow_style שונים (lead/wide/tall/brief)

+ עדכון `NB_THEME_VERSION` ל-`0.3.3`.

## כללי-זהב

1. **`home.php` הוא ה-template של `/blog/`** — לא index.php. ודא ב-Settings → Reading שה-"posts page" מוגדר ל-`blog` (זה הוגדר ב-WP001 של V200)
2. **filter via URL params** (`?world=...&view=...`) — server-side. JS הוא enhancement בלבד
3. **ToC רגיל** — extract בPHP, לא JS (מונע flash)
4. **seed posts מסומנים `_nb_seed=v200`** — נשארים
5. **Vanilla JS only** — לא React/jQuery
6. שאר כללי P003

## Exit criteria

- [ ] 10 קבצים tracked
- [ ] 4 seed posts ב-REST
- [ ] 14 בדיקות B1-B14 PASS
- [ ] JS-disabled fallback עובד (filter via URL only, no JS required)
- [ ] baseline §11 PASS
- [ ] git push + version bump

## תזמון

5 ימי עבודה. VALIDATE cross-engine.

— team_100 (nimrod-bio) — 2026-05-25
