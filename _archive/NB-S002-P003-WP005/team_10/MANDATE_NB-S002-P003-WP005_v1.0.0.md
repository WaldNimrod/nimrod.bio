---
type: MANDATE
from: team_100 (nimrodbio_arch)
to: team_10 (Cursor session #5 of 5 parallel)
wp_id: NB-S002-P003-WP005
project: nimrod-bio
milestone: V200
program: P003
date: 2026-05-25
gate: L-GATE_SPEC PASS → L-GATE_BUILD
track: A · STANDARD
priority: HIGH
spec_ref: _aos/work_packages/NB-S002-P003-WP005/LOD400_NB-S002-P003-WP005.md
program_ref: _aos/work_packages/S002/P003/LOD300_P003_program.md
---

# MANDATE — NB-S002-P003-WP005 — T8 Static (about/heritage/contact)

**לצוות 10 (session #5 of 5 · T8 Static):**

3 דפים סטטיים. ה-WP הראשון עם form handler ו-`wp_mail()`. ה-WP האחרון של P003.

## 📖 קרא בסדר הזה
1. `_aos/work_packages/S002/P003/LOD300_P003_program.md`
2. `_aos/work_packages/NB-S002-P003-WP005/LOD400_NB-S002-P003-WP005.md`

## תוצרים

- `page-about.php`, `page-heritage.php`, `page-contact.php`
- 8 template-parts (t8-about-hero, journey-timeline, cdip-thesis, value-tile, media-item, heritage-hero, contact-form, contact-side)
- `assets/css/t8.css`
- `assets/js/t8-contact.js`
- `inc/template-styles-t8.php`
- `inc/contact-form-handler.php` (server-side POST handler)
- Bootstrap function `nb_bootstrap_static_pages()` ב-`world-pages-bootstrap.php` (או קובץ נפרד)
- `require_once` ל-contact-form-handler ב-functions.php (העריכה האחרונה ל-functions.php ב-V200)

+ עדכון `NB_THEME_VERSION` ל-`0.4.0` (קץ P003).

## כללי-זהב

1. **About + Heritage תוכן hardcoded ב-PHP** — לא ב-CPT
2. **TBC markers** עם class `.tbc` לכל הצריך תוכן מ-team_00 (Q-05/Q-NEW-03/Q-11/Q-02/Q-03)
3. **Form: nonce + honeypot + sanitization** — A14/A15 חובה
4. **`wp_mail()` עלול לא לעבוד** — אם נכשל, redirect ל-`?status=error` ודווח ב-COMPLETION (defer ל-P005-WP001 polish)
5. **WhatsApp link** = `https://wa.me/972547776770`
6. שאר כללי P003

## Exit criteria

- [ ] 14 קבצים tracked
- [ ] 3 static pages bootstrapped ב-DB (`/about/`, `/about/heritage/`, `/contact/`)
- [ ] 18 בדיקות A1-A18 PASS
- [ ] form נשלח דרך curl ומחזיר redirect (gen test A12)
- [ ] honeypot blocks (A14)
- [ ] baseline §11 PASS
- [ ] git push + version bump

## תזמון

4 ימי עבודה. VALIDATE cross-engine. בסיום — **כל P003 הושלם, נפתח את P004 (content migration)**.

— team_100 (nimrod-bio) — 2026-05-25
