---
type: STATUS_REPORT
from: team_10 (nimrodbio_build)
to: team_100 (nimrodbio_arch)
wp_id: NB-S002-P001-WP001
date: 2026-05-25
subject: WP Dev Baseline — Current State
---

# WP Dev Baseline — nimrod-bio-2026

**Dev URL:** http://nimrod-bio-2026.s887.upress.link  
**Baseline date:** 2026-05-25

---

## Final state summary

| Setting | Value | Status |
|---|---|---|
| WordPress version | 7.0 | ✓ (> required 6.7+) |
| PHP version | 8.3 | ✓ |
| Active theme | Twenty Twenty-Five 1.5 | ✓ (default — will replace in WP002) |
| Site title | nimrod.bio · V200 dev | ✓ |
| Tagline | DEV — do not index | ✓ |
| Admin user | sb0233051_admin (non-default) | ✓ |
| Locale (he_IL) | Set via form — awaiting language pack install | ⚠ pending |
| Permalink structure | /blog/%postname%/ | ✓ |
| Pages at root | /sample-page/ (no /blog/ prefix) | ✓ |
| Search engine discouragement | blog_public=0 set | ✓ |
| X-Robots-Tag (uPress edge) | noindex, nofollow | ✓ |
| Active plugins | None (clean install) | ✓ |
| uPress drop-ins | advanced-cache.php, object-cache.php | ✓ (SuperCache active) |
| WP_ENVIRONMENT_TYPE | **NOT SET** — blocked on FTP | ✗ BLOCKED |
| MU plugin (nb-dev-app-passwords.php) | **Not deployed** — blocked on FTP | ✗ BLOCKED |
| Application Password (aos-publisher-dev) | **Not created** — blocked on MU plugin | ✗ BLOCKED |
| Basic auth (access restriction) | **Not configured** | ✗ PENDING |

---

## What was done (automated via REST API + admin session)

All configuration below was applied via authenticated WP REST API and admin form POSTs (cookie-based session — admin credentials confirmed working).

1. **Site title:** set to "nimrod.bio · V200 dev" via `POST /wp-json/wp/v2/settings`
2. **Tagline:** set to "DEV — do not index" via REST API
3. **Permalink structure:** set to `/blog/%postname%/` via `POST /wp-admin/options-permalink.php`
4. **Comment status:** default closed via REST API
5. **Ping status:** default closed via REST API
6. **blog_public=0:** set via `POST /wp-admin/options-general.php`
7. **WPLANG=he_IL:** submitted via general settings form — language pack download required on server to activate

---

## Blockers requiring team_00 action

### BLOCKER-1: FTP credentials incorrect (HIGH)

FTP login fails with `530 Login incorrect` for all attempted usernames:
- `dev@nimrod-bio-2026.s887.upress.link` (from .env.upress.dev)
- `sb0233051_up1` (DB username)
- `sb0233051` (base account prefix)

**My IP (147.235.203.51) IS in the allowlist** — the issue is credentials, not IP restriction.

**Action required (team_00):**
1. Log into uPress control panel for `nimrod-bio-2026`
2. Navigate to FTP Accounts
3. Verify or create an FTP account and update `.env.upress.dev`:
   - `UPRESS_FTP_USER`
   - `UPRESS_FTP_PASS`

Once fixed, re-run `scripts/wp_dev_baseline.sh` — steps 5 and 6 will deploy the MU plugin and document the wp-config.php change.

### BLOCKER-2: wp-config.php edit (depends on BLOCKER-1)

The constant `define( 'WP_ENVIRONMENT_TYPE', 'local' );` must be added to `wp-config.php` on the dev server before the `/* That's all, stop editing! */` line. This enables Application Passwords over HTTP.

**If FTP is unavailable**, team_00 can add this directly via:
- uPress control panel → File Manager → public_html/wp-config.php

### BLOCKER-3: MU plugin deployment (depends on BLOCKER-1 or BLOCKER-2)

`nimrod.bio/wp-content/mu-plugins/nb-dev-app-passwords.php` is committed to git. It must be deployed to `wp-content/mu-plugins/` on the dev server.

**If FTP is unavailable**, team_00 can upload via uPress File Manager.

### BLOCKER-4: Application Password creation (depends on BLOCKER-2 + BLOCKER-3)

Once the MU plugin is active and `WP_ENVIRONMENT_TYPE='local'` is in wp-config.php:
1. Visit https://nimrod-bio-2026.s887.upress.link/wp-admin/profile.php
2. Scroll to Application Passwords
3. Create password named `aos-publisher-dev`
4. Update `.env.upress.dev`:
   ```
   WP_REST_USER=sb0233051_admin
   WP_REST_APP_PASSWORD='<generated-password>'
   ```

### BLOCKER-5: Basic auth (Task 3)

Dev URL is currently unprotected (only noindex from edge). Basic auth via `.htpasswd` or uPress IP restriction needs to be configured.

**Option A (recommended):** uPress control panel → Password Protect Directories → apply to root.
**Option B:** Deploy `.htpasswd` file via FTP (depends on BLOCKER-1).

---

## Permalink verification

```
POST URL:  http://nimrod-bio-2026.s887.upress.link/blog/hello-world/  ✓
PAGE URL:  http://nimrod-bio-2026.s887.upress.link/sample-page/        ✓ (no /blog/ prefix)
```

---

## Files committed

| File | Status |
|---|---|
| `nimrod.bio/wp-content/mu-plugins/nb-dev-app-passwords.php` | In git — needs FTP deploy |
| `scripts/wp_dev_baseline.sh` | In git — idempotent, re-run after blockers resolved |
| `docs/upress_control_panel_audit.md` | In git — screenshots pending |
| `_COMMUNICATION/team_10/WP_DEV_BASELINE_v1.md` | This file |

---

*team_10 (nimrodbio_build) — 2026-05-25*
