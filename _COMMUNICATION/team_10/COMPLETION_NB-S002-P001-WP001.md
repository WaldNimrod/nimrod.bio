---
type: COMPLETION
from: team_10 (nimrodbio_build)
to: team_100 (nimrodbio_arch)
wp_id: NB-S002-P001-WP001
date: 2026-05-25
gate: L-GATE_BUILD
status: PARTIAL — 2 BLOCKERS require team_00 intervention
---

# COMPLETION — NB-S002-P001-WP001 — uPress Dev Environment Preparation

## סיכום

רוב המשימות הושלמו. 2 BLOCKERS הדורשים גישה ל-FTP/control panel ע"י team_00.

**Dev URL:** http://nimrod-bio-2026.s887.upress.link  
**WP version found:** 7.0 (> required 6.7+)

---

## תוצאות לפי משימה

| # | משימה | תוצאה |
|---|-------|-------|
| 1 | Audit מצב dev env | ✓ (חלקי — 5 screenshots ממתינים לידי team_00) |
| 2 | Fresh WP install — baseline settings | ✓ |
| 3 | Basic auth | ✗ BLOCKED (FTP נדרש) |
| 4 | Permalink validation | ✓ |
| 5 | validate_aos.sh + git hygiene | ✓ (2 FAIL pre-existing — ראה פירוט) |
| 6 | Application Passwords (MU plugin + wp-config.php) | ✗ BLOCKED (FTP נדרש) |

---

## משימה 1 — Audit

בוצע via HTTP probe + REST API:
- WP 7.0 ✓, PHP 8.3 ✓, Twenty Twenty-Five 1.5 active ✓
- Clean install confirmed (no Flatsome, no old content)
- No active plugins ✓
- uPress drop-ins: `advanced-cache.php`, `object-cache.php`, `ezcache-config.json` (SuperCache active)
- `X-Robots-Tag: noindex, nofollow` confirmed from uPress edge ✓

**Deliverable:** `docs/upress_control_panel_audit.md` ✓

**5 screenshots pending:** team_00 must capture from uPress control panel (panel overview, SuperCache, Firewall, Backups, FTP Accounts).

---

## משימה 2 — Fresh WP install (baseline settings)

כל ההגדרות הוחלו via WP REST API + authenticated admin session (cookie):

| Setting | Value | Method |
|---|---|---|
| Site title | nimrod.bio · V200 dev | REST API `/wp/v2/settings` |
| Tagline | DEV — do not index | REST API |
| Default comment status | closed | REST API |
| Default ping status | closed | REST API |
| Permalink structure | /blog/%postname%/ | Admin form POST |
| blog_public | 0 (noindex) | Admin form POST |
| WPLANG | he_IL submitted | Admin form POST — language pack install pending |

**Deliverables:**
- `scripts/wp_dev_baseline.sh` ✓ (idempotent — re-run after blockers resolved)
- `_COMMUNICATION/team_10/WP_DEV_BASELINE_v1.md` ✓

---

## משימה 3 — Basic auth ✗ BLOCKED

נדרש FTP לפריסת `.htpasswd` (אופציה B) או גישה ל-uPress control panel (אופציה A).

**FTP status:** Login fails with `530 Login incorrect` for all usernames tried:
- `dev@nimrod-bio-2026.s887.upress.link`
- `sb0233051_up1`
- `sb0233051`

**IP allowlist:** 147.235.203.51 ✓ (current machine IP matches `.env.upress.dev` allowlist)

**Action required (team_00):**
אופציה A (מועדפת): uPress control panel → Password Protect Directories → הגדר על root של הדומיין.
אופציה B: תקן credentials ב-`.env.upress.dev` (FTP Accounts מהפאנל), ואז רץ `scripts/wp_dev_baseline.sh`.

---

## משימה 4 — Permalink validation ✓

```
POST:  http://nimrod-bio-2026.s887.upress.link/blog/hello-world/   ✓
PAGE:  http://nimrod-bio-2026.s887.upress.link/sample-page/         ✓ (no /blog/ prefix)
```

---

## משימה 5 — validate_aos.sh

```
RESULT: 30 PASS / 14 SKIP / 2 FAIL
```

**FAIL 1 — Check 32 (permitted):** Uncommitted `_aos/definition.yaml` drift — pre-existing, per mandate exemption.

**FAIL 2 — Check 12 (pre-existing, not my scope):** Cross-project contamination — `sources/team_35_design_package/_handoff/02-PROMPT-logo-family.md` contains the pattern 'tiktrack'. This file is an **untracked** team_35 design package file, already on disk before this WP. Check 12 uses filesystem grep (not git-tracked only), so it finds this file.

**Recommended action (team_100):** File GCR to update Check 12 to exclude `sources/` directory, OR add `sources/team_35_design_package/` to `.gitignore` and document it.

**git:** Committing only my deliverables. Pre-existing drift (definition.yaml, team_100 files, team_35 files) left untouched per mandate.

---

## משימה 6 — Application Passwords ✗ BLOCKED (depends on FTP)

**Deliverable committed to git:** `nimrod.bio/wp-content/mu-plugins/nb-dev-app-passwords.php` ✓

**Blocked:** Deploying the MU plugin to the server requires FTP access (same BLOCKER as Task 3).
**Blocked:** Adding `define( 'WP_ENVIRONMENT_TYPE', 'local' );` to `wp-config.php` requires FTP or uPress File Manager.

**Once FTP is fixed:**
1. `scripts/wp_dev_baseline.sh` will auto-deploy the MU plugin
2. team_00 adds `define( 'WP_ENVIRONMENT_TYPE', 'local' );` to `wp-config.php` via file manager or SSH
3. team_00 creates Application Password `aos-publisher-dev` from `/wp-admin/profile.php`
4. team_00 updates `.env.upress.dev`:
   ```
   WP_REST_USER=sb0233051_admin
   WP_REST_APP_PASSWORD='<generated-value>'
   ```
5. Verify: `curl -u "sb0233051_admin:<app-password>" http://nimrod-bio-2026.s887.upress.link/wp-json/wp/v2/users/me`

---

## Exit criteria checklist

- [x] `docs/upress_control_panel_audit.md` קיים, מלא (screenshots pending team_00)
- [x] `scripts/wp_dev_baseline.sh` idempotent + מתועד
- [x] `_COMMUNICATION/team_10/WP_DEV_BASELINE_v1.md` מצב סופי
- [ ] `curl … /` מחזיר 401 בלי auth ← **BLOCKED** (Task 3)
- [x] Permalink `/blog/%postname%/` עובד — `blog/hello-world/` מאומת
- [x] `validate_aos.sh` — 2 FAIL, שניהם pre-existing (exempted per mandate + recommendation filed)
- [ ] `wp-config.php` כולל `WP_ENVIRONMENT_TYPE = 'local'` ← **BLOCKED** (Task 6)
- [ ] MU plugin `nb-dev-app-passwords.php` deployed ← **BLOCKED** (Task 6, in git ✓)
- [ ] Application Password נוצר + נבדק ← **BLOCKED** (Task 6)
- [x] git: commit + push לכל deliverables שלי, ללא נגיעה ב-drift חיצוני

---

## Files committed in this WP

| File | Notes |
|---|---|
| `nimrod.bio/wp-content/mu-plugins/nb-dev-app-passwords.php` | MU plugin — needs FTP deploy to server |
| `scripts/wp_dev_baseline.sh` | Idempotent baseline script |
| `docs/upress_control_panel_audit.md` | Audit doc — screenshots pending |
| `_COMMUNICATION/team_10/WP_DEV_BASELINE_v1.md` | Baseline status |
| `_COMMUNICATION/team_10/COMPLETION_NB-S002-P001-WP001.md` | This file |

---

*nimrod-bio | Team 10 → Team 100 | 2026-05-25*
