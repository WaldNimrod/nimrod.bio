---
type: REMEDIATION
team: team_10
phase_owner: team_10
wp_id: NB-S002-P004-WP002
project: nimrod-bio
milestone: V200
program: P004
date: 2026-05-25
builder_engine: Cursor (Codex 5.3)
correction_cycle: 1
trigger_verdict_ref: _COMMUNICATION/team_190/VERDICT_NB-S002-P004-WP002_v1.0.0.md
spec_ref: _aos/work_packages/NB-S002-P004-WP002/LOD400_NB-S002-P004-WP002.md
completion_ref: _COMMUNICATION/team_10/COMPLETION_NB-S002-P004-WP002.md
remediation_commit: c434f77a
remediation_branch: main
remediation_remote: origin/main
---

# REMEDIATION — NB-S002-P004-WP002 — cycle 1

## Root cause

`team_190` FAIL findings were correct: runtime traffic on dev is served by nginx and does not enforce Apache `.htaccess` rewrite rules, so all redirect/drop probes were handled as generic `404` before this remediation.

## Fix strategy implemented

1. Kept the additive `.htaccess` artifact and markers intact (`docs/htaccess_v200_redirects.txt`) with no removals.
2. Added a server-compatible runtime enforcement path in WordPress MU plugin space:
   - generator: `scripts/redirects/generate_runtime_mu_plugin.py`
   - generated plugin: `nimrod.bio/wp-content/mu-plugins/nb-v200-runtime-redirects.php`
3. Plugin enforces:
   - `23` redirect rows as `301`
   - `6` drop rows as `410`
   - legacy `?page_id=2516` heritage alias redirect to `/about/heritage/`
4. Kept Hebrew slug handling canonical in tooling: generator uses shared `_lib.quote_slug()` (`urllib.parse.quote(..., safe='/')` with lowercase `%d7%` normalization).
5. Deployed MU plugins to dev via canonical FTPS flow (`scripts/upress_ftps_upload.py` with `prot_c` + PASV).
6. Hardened verifier (`scripts/redirects/verify_redirects.py`) with transient network retry and timeout capture, then regenerated JSON evidence.

## Commands executed (cycle 1)

```bash
python3 -m py_compile scripts/redirects/generate_runtime_mu_plugin.py scripts/redirects/verify_redirects.py
python3 scripts/redirects/generate_runtime_mu_plugin.py
python3 scripts/upress_ftps_upload.py --env-file .env.upress.dev --local-dir nimrod.bio/wp-content/mu-plugins --remote-dir wp-content/mu-plugins
python3 scripts/redirects/verify_redirects.py
bash _aos/lean-kit/modules/validation-quality/scripts/validate_aos.sh .
```

Explicit probes for previous blockers:

```bash
curl -sSI "$UPRESS_DEV_URL_HTTP/video1/"
curl -sSI "$UPRESS_DEV_URL_HTTP/grow/"
curl -sSI "$UPRESS_DEV_URL_HTTP/%D7%94%D7%96%D7%9E%D7%A0%D7%AA-%D7%A1%D7%9C-%D7%99%D7%A8%D7%A7%D7%95%D7%AA-%D7%90%D7%95%D7%A8%D7%92%D7%90%D7%A0%D7%99-%D7%A4%D7%A8%D7%93%D7%A1-%D7%97%D7%A0%D7%94-2-2-2-2-2/"
```

## Runtime outcome summary

- `/video1/` -> `301` + `Location: /blog/%D7%99%D7%95%D7%9D-%D7%91%D7%92%D7%99%D7%A0%D7%94/` (`X-Redirect-By: NB-V200-runtime`)
- `/grow/` -> `410` (`X-Redirect-By: NB-V200-runtime`)
- heritage encoded slug -> `301` + `Location: /about/heritage/` (`X-Redirect-By: NB-V200-runtime`)

Verification JSON: `docs/redirect_verification_2026-05-25.json`
- redirect: `23/23`
- drop: `6/6`
- keep: `2/2`
- `all_pass: true`

Yoast/sitemap regression:
- active plugins REST check contains `wordpress-seo/wp-seo`
- `sitemap_index.xml` -> `200`
- `post-sitemap.xml` -> `200`, `<loc>` count `23`, drop slug hits `0`

## R1-R17 status (post-remediation)

| ID | Result | Evidence |
|---|---|---|
| R1 | PASS | `docs/htaccess_v200_redirects.txt` exists with START/END markers and 30 rewrite rules. |
| R2 | PASS | Hebrew encoding remains canonical in generator path (`quote(..., safe='/')` via `_lib.quote_slug`). |
| R3 | PASS | `.htaccess` idempotent deploy evidence unchanged from prior cycle; this remediation did not alter deploy logic. |
| R4 | PASS | Backup behavior unchanged in `deploy_htaccess.py`; prior backup artifacts preserved under `.migration-cache/`. |
| R5 | PASS | `docs/redirect_verification_2026-05-25.json` summary redirect `23/23`. |
| R6 | PASS | `docs/redirect_verification_2026-05-25.json` summary drop `6/6` with `410`. |
| R7 | PASS | `docs/redirect_verification_2026-05-25.json` summary keep `2/2`. |
| R8 | PASS | Probe `/video1/` now returns `301` to `/blog/יום-בגינה/` (encoded `Location` accepted). |
| R9 | PASS | Heritage encoded slug now returns `301` to `/about/heritage/`. |
| R10 | PASS | REST active plugin check includes `wordpress-seo/wp-seo`. |
| R11 | PASS | `/sitemap_index.xml` returns `200`. |
| R12 | PASS | `/post-sitemap.xml` returns `200` and `<loc>` count `23` (`>=22`). |
| R13 | PASS | Drop slugs absent from post sitemap (`0` hits). |
| R14 | PASS | `docs/search_console_runbook.md` retained as runbook-only (no direct Search Console execution). |
| R15 | PASS | Existing `.htaccess` additive preservation constraints untouched by cycle 1 changes. |
| R16 | PASS | `validate_aos.sh .` -> `32 PASS / 16 SKIP / 0 FAIL`. |
| R17 | PASS | Remediation files tracked + committed in `c434f77a`; pushed to `origin/main`. |

## Files changed in cycle 1

- `scripts/redirects/generate_runtime_mu_plugin.py` (new)
- `nimrod.bio/wp-content/mu-plugins/nb-v200-runtime-redirects.php` (new, generated)
- `scripts/redirects/verify_redirects.py` (timeout/retry resilience)
- `docs/redirect_verification_2026-05-25.json` (fresh PASS evidence)

## Git and push proof

- Commit: `c434f77a`
- Branch: `main`
- Push proof:
  - `To https://github.com/WaldNimrod/nimrod.bio.git`
  - `c838a00b..c434f77a  main -> main`

## Request

Cycle 1 remediation is complete and ready for independent `team_190` re-validation.
