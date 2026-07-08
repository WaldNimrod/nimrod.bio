---
type: COMPLETION
team: team_10
wp_id: NB-S002-P004-WP002
project: nimrod-bio
milestone: V200
program: P004
date: 2026-05-25
builder_engine: Cursor (Codex 5.3)
status: PARTIAL_BLOCKED
blocking_reason: "Dev host serves via nginx and ignores .htaccess rules; redirect/410 runtime checks fail despite successful additive deploy."
commit_sha: ceda4535
branch: main
remote: origin/main
---

# COMPLETION — NB-S002-P004-WP002

## Scope delivered

- Implemented `scripts/redirects/` toolchain:
  - `scripts/redirects/_lib.py`
  - `scripts/redirects/generate_htaccess_block.py`
  - `scripts/redirects/deploy_htaccess.py`
  - `scripts/redirects/verify_redirects.py`
- Generated and committed `docs/htaccess_v200_redirects.txt`.
- Added Search Console documentation-only runbook: `docs/search_console_runbook.md`.
- Installed and activated Yoast on dev via mandated REST flow.
- Captured redirect verification JSON: `docs/redirect_verification_2026-05-25.json`.
- Ran AOS validation (`32 PASS / 16 SKIP / 0 FAIL`).
- Committed and pushed implementation before completion artifact (`ceda4535` on `main`).

## Golden-constraint compliance

1. **Additive `.htaccess` block only**: YES (`# AOS-V200-redirects-START` / `# AOS-V200-redirects-END`).
2. **Hebrew URL encoding via quote(..., safe='/')**: YES (implemented in `_lib.py`, lowercase `%d7%` normalization).
3. **Drops as 410 Gone**: YES (`[G,L]` rules generated for 6 drops).
4. **Yoast install/activate via REST**: YES (`POST /wp/v2/plugins` with `{"slug":"wordpress-seo","status":"active"}`).
5. **Search Console actions not executed**: YES (runbook only).
6. **Git push before completion marked done**: YES (`git push origin main` completed before this artifact).

## Acceptance evidence (R1-R17)

| ID | Result | Evidence |
|---|---|---|
| R1 | PASS | `docs/htaccess_v200_redirects.txt` exists with START/END markers and `RewriteRule` count `30` (`23+6+1`). |
| R2 | PASS | `%d7%` occurrences in generated block: `563` (>=20). |
| R3 | PASS | Deploy idempotency check: `R3_idempotent_same True` after back-to-back deploys. |
| R4 | PASS | Backup files created under `.migration-cache/htaccess.<ts>.bak` (latest: `.migration-cache/htaccess.20260525T191114+0000.bak`). |
| R5 | **FAIL (BLOCKED)** | `verify_redirects.py` summary: redirect `0/23` pass. |
| R6 | **FAIL (BLOCKED)** | `verify_redirects.py` summary: drop `0/6` pass. |
| R7 | PASS | `verify_redirects.py` summary: keep `2/2` pass. |
| R8 | **FAIL (BLOCKED)** | `curl -I /video1/` returns `HTTP/1.1 404 Not Found` (expected 301). |
| R9 | **FAIL (BLOCKED)** | `curl -I /<heritage-encoded-slug>/` returns `HTTP/1.1 404 Not Found` (expected 301 `/about/heritage/`). |
| R10 | PASS | Active plugins endpoint includes `wordpress-seo/wp-seo` (`R10_yoast_active True`). |
| R11 | PASS | `sitemap_index.xml` status `200`. |
| R12 | PASS | `post-sitemap.xml` status `200`, `<loc>` count `23` (>=22). |
| R13 | PASS | All 6 dropped slugs absent from `post-sitemap.xml` (`R13_drop_absence_all True`). |
| R14 | PASS | `docs/search_console_runbook.md` includes 7-step cutover checklist (documentation only). |
| R15 | PASS | Non-block `.htaccess` content preserved across deploy (`R15_non_block_same True`). |
| R16 | PASS | `validate_aos.sh` result: `32 PASS / 16 SKIP / 0 FAIL`. |
| R17 | PASS | Tracked deliverables committed in `ceda4535` (scripts + generated docs + verification JSON + runbook). |

## Verification JSON (required)

- File: `docs/redirect_verification_2026-05-25.json`
- Summary:
  - `redirect`: `0/23`
  - `drop`: `0/6`
  - `keep`: `2/2`
  - `all_pass`: `false`

## Yoast execution evidence

```bash
curl -X POST -u "$WP_REST_USER:$WP_REST_APP_PASSWORD" \
  "$WP_REST_BASE_URL/wp/v2/plugins" \
  -H "Content-Type: application/json" \
  -d '{"slug":"wordpress-seo","status":"active"}'
```

Returned plugin status: `wordpress-seo/wp-seo` with `"status":"active"`.

## Blocker details

- Runtime headers on redirect probes show `Server: nginx`.
- On this dev host, `.htaccess` directives are not being applied by the serving stack, so Apache rewrite-based 301/410 enforcement cannot be validated at runtime.
- Current state is deploy-complete and auditable, but runtime redirect acceptance (R5/R6/R8/R9) remains blocked until rewrite rules are enforced by server layer compatible with `.htaccess` semantics (or equivalent nginx rewrite layer under explicit architecture approval).

## Git evidence

- Commit: `ceda4535`
- Push:
  - `To https://github.com/WaldNimrod/nimrod.bio.git`
  - `eb432f63..ceda4535  main -> main`
