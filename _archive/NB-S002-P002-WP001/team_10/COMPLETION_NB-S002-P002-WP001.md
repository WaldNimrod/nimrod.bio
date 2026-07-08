---
type: COMPLETION
from: team_10 (nimrodbio_build)
to: team_100 (nimrodbio_arch)
wp_id: NB-S002-P002-WP001
date: 2026-05-25
gate: L-GATE_BUILD
status: COMPLETE_WITH_EXTERNAL_BLOCKER
status_note: ALL_THEME_SCOPE_DONE__T10_SEO_BLOCKED_BY_UPRESS_NOINDEX_EDGE
---

# COMPLETION - NB-S002-P002-WP001 - Custom theme skeleton

## Outcome

Theme skeleton implementation is complete per LOD400 and deployed to dev.

Theme directory:

`nimrod.bio/wp-content/themes/nimrod-bio-2026/`

Deployment status:

- Uploaded to dev via canonical FTPS uploader (`scripts/upress_ftps_upload.py`) - 17 files uploaded.
- Theme activated via wp-admin theme action URL.
- REST verification confirms active theme: `nimrod-bio-2026`.

## Deliverables implemented (16/16 local)

- `style.css`
- `theme.json`
- `functions.php`
- `header.php`
- `footer.php`
- `index.php`
- `404.php`
- `searchform.php`
- `inc/enqueue.php`
- `inc/template-helpers.php`
- `inc/nav-walker.php`
- `assets/css/system.css` (locked source copy + required lock header)
- `assets/css/shell.css` (shell-nav + shell-footer selectors + approved local additions)
- `assets/js/shell.js`
- `assets/icons/home.svg`
- `template-parts/shell-nav.php`
- `template-parts/shell-footer.php`

## Local validation completed

- PHP syntax checks: PASS for all PHP files in the new theme.
- Shell Hebrew strings preserved per locked source (including `ייעוץ והוראה`).
- `theme.json` locked palette flags preserved:
  - `customDuotone: false`
  - `defaultPalette: false`

## Deployment / remote test status (T1-T15)

| Test | Status | Evidence |
|---|---|---|
| T1 Theme activation via REST | PASS (effective) | `/wp/v2/themes?status=active` returns `nimrod-bio-2026` as active. Note: `POST /wp/v2/themes/...` is not supported on this WP build (GET-only endpoint), so activation used wp-admin action URL. |
| T2 HTML structure on dev URL | PASS | `dir=\"rtl\"`, `lang=\"he-IL\"`, skip-link, and `data-active-world=\"\"` all present after header remediation deploy. |
| T3 Asset loading | PASS | `system.css`, `shell.css`, Google Fonts return 200; source order is fonts -> system -> shell. |
| T4 Shell nav renders | PASS | 8 required links/texts render with locked Hebrew labels. |
| T5 World active state | PASS | `/world/soil/`, `/world/know/`, `/world/code/` each render matching `.is-active` class. |
| T6 Footer renders | PASS | Footer columns + Unless line render; current year renders dynamically. |
| T7 RTL flow | PASS | RTL confirmed and viewport overflow checks (360/768/1280) show no horizontal overflow. |
| T8 Font loads | PASS (source-level) | Google Fonts stylesheet is enqueued and returns 200; families include Assistant + Frank Ruhl Libre + JetBrains Mono. |
| T9 Color tokens | PASS | Locked token file deployed; browser computed style check returned body background `rgb(245, 243, 236)` (paper). |
| T10 Lighthouse on `/` | PARTIAL (external blocker) | HTTP run after remediations: Perf 82 / A11y 98 / SEO 63. HTTPS run (with `--ignore-certificate-errors` due dev cert): Perf 88 / A11y 98 / SEO 63 / Best 100. Remaining SEO failure is `Page is blocked from indexing` (`X-Robots-Tag: noindex, nofollow` at uPress edge). |
| T11 No console errors | PASS | Lighthouse best-practices `errors-in-console` score is 1 after favicon/404 remediation. |
| T12 `validate_aos.sh` | PASS | 32 PASS / 16 SKIP / 0 FAIL (L-GATE_BUILD EXIT CRITERION: SATISFIED). |
| T13 Theme.json palette in editor | PASS (config-level) | `theme.json` verified with 12-color palette and locked flags (`customDuotone=false`, `defaultPalette=false`); global-styles endpoint returns theme palette entries accordingly. |
| T14 `wp_get_environment_type()` | PASS | Homepage fallback panel renders `local`. |
| T15 App Password still works | PASS | `GET /wp/v2/users/me` returns 200 for app-password auth. |

## FTPS protocol update and deployment diagnostics

- Adopted canonical protocol from SFA/hub runbook:
  - `connect -> login -> prot_c -> PASV` (explicit FTPS on port 21)
- Added uploader: `scripts/upress_ftps_upload.py`
- Added mandatory procedure: `docs/UPRESS_FTPS_MANDATORY_PROCEDURE.md`
- Updated baseline flow: `scripts/wp_dev_baseline.sh` now uses canonical uploader.
- Resolved deploy block by updating uPress allowlist + `.env.upress.dev` to current IPv4 values during session rotations (`79.177.129.243` then `147.235.197.125`).

## Deviations from LOD400 (transparent log)

1. **Activation API mismatch**: LOD400 §5 expects `POST /wp/v2/themes/nimrod-bio-2026`, but current WP REST schema exposes themes endpoint as `GET`-only; activation performed via wp-admin action URL.
2. **T10 SEO threshold blocked by platform edge policy**: dev environment enforces `X-Robots-Tag: noindex, nofollow`; Lighthouse SEO remains 63 due `is-crawlable=0` independent of theme code.
3. **Dev HTTPS certificate is invalid/expired on `*.upress.link`**: HTTPS Lighthouse requires `--ignore-certificate-errors` in CLI for measurement.

## Next action for final gate decision

1. Treat T10 SEO gap as external/environmental blocker (uPress edge noindex), not theme implementation defect.
2. If strict numeric compliance is mandatory in this WP, run Lighthouse on an indexable staging endpoint on primary domain (not `*.upress.link`).
3. Submit to team_100/team_190 with this evidence and blocker annotation.

## Fix cycle 1 (2026-05-25)

| Blocker | Status | Evidence |
|---|---|---|
| B1 T8 h1 font | FIXED | `Runtime.evaluate` on dev page reports `getComputedStyle(document.querySelector('h1')).fontFamily = "Frank Ruhl Libre", "David Libre", Georgia, serif` |
| B2 system.css drift | FIXED | `tiktrack` -> `TikTrack` restored; source/body drift removed except approved header comment wrapper |
| B3 shell.css footer | FIXED | Footer values restored to source opacities; `Runtime.evaluate` shows `.shell-foot .bottom .unless em` computed color `rgb(210, 58, 46)` |
| B4 git tracking | FIXED | `git ls-files 'nimrod.bio/wp-content/themes/nimrod-bio-2026/**'` now returns tracked files (16+) after staging/commit |

- [x] (B1) T8 acceptance test now PASS
- [x] (B2) system.css verbatim restored
- [x] (B3) shell.css footer values match T1-styles.css lines 468–501
- [x] (B4) theme directory tracked in git
- [x] re-deployed to dev (FTP)
- [x] cache-busted via version bump (`NB_THEME_VERSION = 0.1.1`)
- [x] `validate_aos.sh` = 0 FAIL (32 PASS / 16 SKIP)
