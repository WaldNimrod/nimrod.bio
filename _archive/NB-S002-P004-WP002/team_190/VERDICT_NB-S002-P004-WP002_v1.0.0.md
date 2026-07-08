---
type: VERDICT
document_title: "VERDICT — NB-S002-P004-WP002 — Redirect enforcement + Yoast sitemap"
document_version: "v1.0.0"
document_date: "2026-05-25"
team_id: team_190
phase_owner: team_190
project: nimrod-bio
milestone: V200
program: P004
work_package: NB-S002-P004-WP002
gate: L-GATE_VALIDATE
builder: team_10
builder_engine: "Cursor (Codex 5.3)"
validator: team_190
validator_engine: "GPT-5.5"
spec_ref: "_aos/work_packages/NB-S002-P004-WP002/LOD400_NB-S002-P004-WP002.md"
completion_ref: "_COMMUNICATION/team_10/COMPLETION_NB-S002-P004-WP002.md"
validate_request_ref: "_COMMUNICATION/team_190/VALIDATE_REQUEST_NB-S002-P004-WP002_v1.0.0.md"
verdict: FAIL
route_recommendation: "FAIL -> team_10 remediation artifact; team_100 architecture decision required for server-layer rewrite enforcement before cycle 2"
---

# VERDICT — NB-S002-P004-WP002 — Redirect enforcement + Yoast sitemap

## 1. Verdict

**Result: FAIL.**

Team_190 independently replayed the R1-R17 acceptance matrix. Static deliverables are present and the Yoast/sitemap outcomes pass, but the core runtime redirect contract is not satisfied on the dev host: `23/23` redirect rows return `404` instead of `301`, and `6/6` drop rows return `404` instead of `410`.

This is not accepted as a non-blocking deferral because the LOD400 mission is to deploy and verify 301 redirects + 410 Gone rules. The evidence supports team_10's diagnosis that the dev host is serving through nginx and not applying Apache `.htaccess` rewrite rules, but the constitutional outcome remains **FAIL** until an approved server-layer mechanism enforces the redirects/drops at runtime.

## 2. Cross-Engine Attestation

- Builder: `team_10`, Cursor / Codex 5.3, completion artifact `_COMMUNICATION/team_10/COMPLETION_NB-S002-P004-WP002.md`.
- Validator: `team_190`, GPT-5.5, independent replay performed against local artifacts and live HTTP dev URL.
- Builder evidence was read for scope only and was not inherited as proof.
- Iron Rule #1 maintained: builder engine != validator engine.

## 3. Findings

| id | severity | evidence-by-path | route_recommendation |
|---|---|---|---|
| T190-P004-WP002-F1 | BLOCKER | Live replay against `http://nimrod-bio-2026.s887.upress.link`: redirect summary `0/23`; sample `/video1/` returned `404` with no `Location`; `docs/redirect_verification_2026-05-25.json` also records redirect `0/23`; LOD400 R5/R8 require `301`. | FAIL -> `team_10` remediation cycle 1 after `team_100` confirms approved enforcement layer; produce `_COMMUNICATION/team_10/REMEDIATION_NB-S002-P004-WP002_v1.0.0.md`. |
| T190-P004-WP002-F2 | BLOCKER | Live replay against dev host: drop summary `0/6`; sample `/grow/` returned `404` instead of `410`; `docs/redirect_verification_2026-05-25.json` records drop `0/6`; LOD400 R6 requires `410`. | FAIL -> same remediation artifact; team_10 must demonstrate 6/6 runtime `410` or secure explicit `team_100`/`team_00` waiver. |
| T190-P004-WP002-F3 | HIGH | Constitutional blocker: `.htaccess` block exists in `docs/htaccess_v200_redirects.txt` and deploy snapshots contain the AOS block, but runtime behavior proves the host does not enforce it. LOD400 locked mechanism is Apache `.htaccess`, while observed behavior is nginx 404 handling. | Route to `team_100` for architecture decision: retain `.htaccess` and change host/server behavior, or authorize equivalent nginx/uPress/server-layer redirects; then return to `team_10` for cycle 1 implementation. |
| T190-P004-WP002-N1 | INFO | Yoast/sitemap evidence passes: authenticated REST plugin probe returned active Yoast; `/sitemap_index.xml` returned `200`; `/post-sitemap.xml` returned `200` with `23` `<loc>` entries and zero drop-slug hits. | Record in gate notes; no remediation required for R10-R13. |
| T190-P004-WP002-N2 | INFO | `validate_aos.sh .` returned `32 PASS / 16 SKIP / 0 FAIL`; constitutional package linter script `scripts/lint_constitutional_package.py` is absent in this repo. | Record as baseline clean; no action unless Team 100 requires external linter preflight. |

## 4. R1-R17 Acceptance Replay

| row | result | independent evidence-by-path |
|---|---|---|
| R1 | PASS | `docs/htaccess_v200_redirects.txt` exists, has `# AOS-V200-redirects-START` and `# AOS-V200-redirects-END`, and independent count found `30` `RewriteRule` lines. |
| R2 | PASS | `docs/htaccess_v200_redirects.txt` contains `563` `%d7%` occurrences; generator uses `urllib.parse.quote(..., safe='/')` in `scripts/redirects/_lib.py`. |
| R3 | PASS-with-notes | `.migration-cache/htaccess.before.current` and `.migration-cache/htaccess.after.current` are byte-identical in the latest deploy snapshot; this supports idempotent latest deploy state but does not prove enforcement. |
| R4 | PASS | `.migration-cache/` contains `8` `htaccess*.bak` backup files, including `htaccess.20260525T191114+0000.bak`. |
| R5 | FAIL | Independent live replay: redirect summary `0/23`; `docs/redirect_verification_2026-05-25.json` records redirect `0/23`. |
| R6 | FAIL | Independent live replay: drop summary `0/6`; `docs/redirect_verification_2026-05-25.json` records drop `0/6`. |
| R7 | PASS | Independent live replay: keep summary `2/2`; `/blog/` returned `200`; verification JSON records keep `2/2`. |
| R8 | FAIL | Independent live replay: `GET /video1/` returned `404` and no `Location`; expected `301` to `/blog/יום-בגינה/`. |
| R9 | FAIL | Independent live replay: encoded heritage slug returned `404` and no `Location`; expected `301` to `/about/heritage/`. |
| R10 | PASS | Authenticated REST probe to active plugins returned `200` and `wordpress-seo/wp-seo`/Yoast active signal. |
| R11 | PASS | `GET /sitemap_index.xml` returned `200`, XML content, and `7` `<loc>` entries. |
| R12 | PASS | `GET /post-sitemap.xml` returned `200`, XML content, and `23` `<loc>` entries, satisfying `>=22`. |
| R13 | PASS | Independent post-sitemap scan found zero hits for the 6 dropped slugs. |
| R14 | PASS | `docs/search_console_runbook.md` contains a documentation-only P005-WP002 runbook with `7` numbered steps. |
| R15 | PASS-with-notes | `.migration-cache/htaccess.before.current` and `.migration-cache/htaccess.after.current` have identical non-block content after stripping the AOS block; latest snapshot contains only the AOS block, so preservation is clean but minimal. |
| R16 | PASS | `bash _aos/lean-kit/modules/validation-quality/scripts/validate_aos.sh .` returned `32 PASS / 16 SKIP / 0 FAIL`. |
| R17 | PASS | `git ls-files scripts/redirects` lists 4 tracked scripts; `git ls-files` confirms `docs/htaccess_v200_redirects.txt`, `docs/redirect_verification_2026-05-25.json`, and `docs/search_console_runbook.md`; commit `ceda4535` exists locally. |

## 5. Evidence Completeness

Evidence package is **complete for constitutional judgment**:

- Required artifacts were present: LOD400, mandate, validate request, completion, redirect scripts, generated `.htaccess` block, verification JSON, Search Console runbook.
- Independent runtime replay was performed against the HTTP dev base.
- AOS baseline was independently re-run and remained clean.

Evidence package is **not complete for PASS** because runtime redirect/drop enforcement is explicitly failing. The blocker is not a missing report; it is a failed acceptance outcome.

## 6. Constitutional Assessment of nginx/.htaccess Blocker

Team_10's blocker is credible: the generated block and deploy snapshots show the additive `.htaccess` block exists, while all runtime redirect/drop probes are handled as ordinary WordPress/nginx `404` responses. That indicates the hosting layer is not applying Apache rewrite semantics for this dev URL.

However, LOD400 §1 and §10 require verified 301/410 enforcement. A server mismatch cannot be silently downgraded to PASS-with-deferrals because P004-WP002's primary user-facing contract is the redirect/drop behavior itself.

## 7. Required Remediation

`team_10` must file:

`_COMMUNICATION/team_10/REMEDIATION_NB-S002-P004-WP002_v1.0.0.md`

The remediation artifact must include:

1. `team_100` decision on the enforcement layer: make `.htaccess` effective on uPress dev/prod, or approve an equivalent nginx/uPress/server-layer implementation.
2. Updated implementation evidence.
3. Fresh verification evidence showing:
   - R5: `23/23` redirects return `301` with correct `Location`.
   - R6: `6/6` drops return `410`.
   - R8: `/video1/` returns `301` to `/blog/יום-בגינה/`.
   - R9: heritage old slug returns `301` to `/about/heritage/`.
4. Regression confirmation that R7 and R10-R16 remain PASS.

## 8. Route Recommendation

**FAIL -> team_10 remediation cycle 1**, with an immediate **team_100 architecture decision** before implementation if changing from the locked `.htaccess` mechanism to any nginx/uPress/server-layer alternative.

Do not advance NB-S002-P004-WP002 to COMPLETE and do not unblock P005 cutover on this WP until R5/R6/R8/R9 pass or Team 100/Team 00 issue an explicit waiver accepting non-enforced redirects on dev.
