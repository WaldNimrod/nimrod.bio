---
type: VERDICT
document_title: "VERDICT — NB-S002-P004-WP002 — cycle 1 re-validation"
document_version: "v1.0.0"
document_date: "2026-05-25"
team_id: team_190
phase_owner: team_190
project: nimrod-bio
milestone: V200
program: P004
work_package: NB-S002-P004-WP002
gate: L-GATE_VALIDATE
correction_cycle: 1
builder: team_10
builder_engine: "Cursor (Codex 5.3)"
validator: team_190
validator_engine: "GPT-5.5"
spec_ref: "_aos/work_packages/NB-S002-P004-WP002/LOD400_NB-S002-P004-WP002.md"
trigger_verdict_ref: "_COMMUNICATION/team_190/VERDICT_NB-S002-P004-WP002_v1.0.0.md"
remediation_ref: "_COMMUNICATION/team_10/REMEDIATION_NB-S002-P004-WP002_cycle1_v1.0.0.md"
validate_request_ref: "_COMMUNICATION/team_190/VALIDATE_REQUEST_NB-S002-P004-WP002_cycle1_v1.0.0.md"
verdict: PASS
route_recommendation: "PASS -> team_100 for gate advancement and canonical recording of the approved runtime enforcement path"
---

# VERDICT — NB-S002-P004-WP002 — cycle 1 re-validation

## 1. Verdict

**Result: PASS.**

Team_190 independently revalidated remediation cycle 1 for NB-S002-P004-WP002. The previously failing runtime contract now passes after the runtime enforcement layer was deployed:

- R5: `23/23` redirect rows return `301` with expected destination.
- R6: `6/6` drop rows return `410`.
- R8: `/video1/` returns `301` to `/blog/יום-בגינה/`.
- R9: the heritage old slug returns `301` to `/about/heritage/`.
- R7 keep rows remain `2/2` PASS.
- R10-R17 regression checks remain PASS/PASS-with-notes, with `validate_aos.sh` at `32 PASS / 16 SKIP / 0 FAIL`.

One governance note remains for Team 100: cycle 1 resolves runtime enforcement through a generated MU plugin while retaining the additive `.htaccess` artifact. Because LOD400 locked `.htaccess` as the original mechanism and explicitly excluded plugin/MU-plugin enforcement, Team 100 should record or ratify this architecture adjustment while advancing the gate.

## 2. Cross-Engine Attestation

- Builder: `team_10`, Cursor / Codex 5.3.
- Validator: `team_190`, GPT-5.5.
- Trigger verdict: `_COMMUNICATION/team_190/VERDICT_NB-S002-P004-WP002_v1.0.0.md`.
- Remediation artifact: `_COMMUNICATION/team_10/REMEDIATION_NB-S002-P004-WP002_cycle1_v1.0.0.md`.
- Independent live replay was performed against `http://nimrod-bio-2026.s887.upress.link`; builder evidence was not inherited as proof.
- Iron Rule #1 maintained: builder engine != validator engine.

## 3. Findings

| id | severity | evidence-by-path | route_recommendation |
|---|---|---|---|
| T190-P004-WP002-C1-FIXED | INFO | Independent retry replay over all `docs/url_migration_decisions_2026-05-25.json` rows returned redirect `23/23`, drop `6/6`, keep `2/2`; `docs/redirect_verification_2026-05-25.json` also records `all_pass: true`. | PASS -> `team_100` gate advancement. |
| T190-P004-WP002-C1-R8-FIXED | INFO | Live probe `GET /video1/` returned `301`, `Location: /blog/%D7%99%D7%95%D7%9D-%D7%91%D7%92%D7%99%D7%A0%D7%94/`, `X-Redirect-By: NB-V200-runtime`. | PASS -> record remediation closure. |
| T190-P004-WP002-C1-R9-FIXED | INFO | Live probe of encoded heritage slug returned `301`, `Location: /about/heritage/`, `X-Redirect-By: NB-V200-runtime`; `/?page_id=2516` also returned `301 /about/heritage/`. | PASS -> record remediation closure. |
| T190-P004-WP002-C1-GOV-N1 | MEDIUM | Remediation added `scripts/redirects/generate_runtime_mu_plugin.py` and `nimrod.bio/wp-content/mu-plugins/nb-v200-runtime-redirects.php`. Runtime behavior passes, but LOD400 §3 locked `.htaccess` and said NOT plugin/MU plugin. | `team_100` should ratify/record the mechanism adjustment in gate notes; no further `team_10` remediation required unless Team 100 rejects the mechanism. |
| T190-P004-WP002-C1-N2 | INFO | `bash _aos/lean-kit/modules/validation-quality/scripts/validate_aos.sh .` returned `32 PASS / 16 SKIP / 0 FAIL`; constitutional package linter script `scripts/lint_constitutional_package.py` is absent. | Record as baseline clean. |

## 4. R1-R17 Revalidation Table

| row | result | independent evidence-by-path |
|---|---|---|
| R1 | PASS | `docs/htaccess_v200_redirects.txt` retains START/END markers and independent count found `30` `RewriteRule` lines. |
| R2 | PASS | `docs/htaccess_v200_redirects.txt` contains `563` `%d7%` occurrences; `scripts/redirects/_lib.py` still provides canonical `quote(..., safe='/')` encoding. |
| R3 | PASS-with-notes | Prior `.htaccess` idempotency evidence remains intact; cycle 1 did not alter `scripts/redirects/deploy_htaccess.py`. |
| R4 | PASS | `.migration-cache/` contains `7` `htaccess*.bak` backup files. |
| R5 | PASS | Independent retry replay over all redirect decisions returned `23/23`; JSON evidence at `docs/redirect_verification_2026-05-25.json` records redirect `23/23`. |
| R6 | PASS | Independent retry replay over all drop decisions returned `6/6`; JSON evidence records drop `6/6`. |
| R7 | PASS | Independent replay confirmed keep rows `/shook/` and `/blog/` return `200`; JSON evidence records keep `2/2`. |
| R8 | PASS | Live probe `/video1/` returned `301` to `/blog/יום-בגינה/` with `X-Redirect-By: NB-V200-runtime`. |
| R9 | PASS | Live probe of heritage old slug returned `301` to `/about/heritage/`; `/?page_id=2516` legacy alias also returned `301 /about/heritage/`. |
| R10 | PASS | Authenticated active plugins REST probe returned `200` and Yoast active signal (`wordpress-seo/wp-seo` / `wordpress-seo`). |
| R11 | PASS | `GET /sitemap_index.xml` returned `200`, XML content, and `7` `<loc>` entries. |
| R12 | PASS | `GET /post-sitemap.xml` returned `200`, XML content, and `23` `<loc>` entries, satisfying `>=22`. |
| R13 | PASS | Independent post-sitemap scan found zero hits for the 6 dropped slugs. |
| R14 | PASS | `docs/search_console_runbook.md` remains documentation-only and contains `7` numbered steps for P005-WP002. |
| R15 | PASS-with-notes | Additive `.htaccess` artifact remains intact; cycle 1 did not replace the `.htaccess` deploy path and added runtime enforcement separately. |
| R16 | PASS | `bash _aos/lean-kit/modules/validation-quality/scripts/validate_aos.sh .` returned `32 PASS / 16 SKIP / 0 FAIL`. |
| R17 | PASS | `git cat-file -e c434f77a^{commit}` succeeded; commit subject is `c434f77a fix(V200): enforce WP002 runtime redirects on nginx dev`; tracked files include `scripts/redirects/generate_runtime_mu_plugin.py`, `nimrod.bio/wp-content/mu-plugins/nb-v200-runtime-redirects.php`, redirect scripts, generated docs, and runbook. |

## 5. Runtime Evidence Summary

Independent replay evidence:

```text
RETRY decision_summary {'redirect': {'pass': 23, 'total': 23}, 'drop': {'pass': 6, 'total': 6}, 'keep': {'pass': 2, 'total': 2}}
RETRY failures []
```

Focused probes:

```text
R8_video1 301 /blog/%D7%99%D7%95%D7%9D-%D7%91%D7%92%D7%99%D7%A0%D7%94/ NB-V200-runtime
R6_grow 410  NB-V200-runtime
R9_heritage_slug 301 /about/heritage/ NB-V200-runtime
heritage_pageid 301 /about/heritage/ NB-V200-runtime
R7_blog 200
R7_shook 200
```

Regression probes:

```text
sitemap_index status 200 loc_count 7 xml True
post_sitemap status 200 loc_count 23 xml True
post_sitemap_drop_hits []
R10_plugins 200 yoast_active True
R14_runbook_steps 7
```

## 6. Evidence Completeness

Evidence is complete for cycle 1 PASS:

- Trigger verdict, remediation artifact, validate request, completion artifact, and LOD400 were read.
- `docs/redirect_verification_2026-05-25.json` records `all_pass: true`.
- Independent live replay confirmed all redirect/drop/keep rows with retries.
- Regression checks for Yoast, sitemap, runbook, git-tracked deliverables, and AOS baseline passed.

## 7. Route Recommendation

**PASS -> `team_100` for L-GATE_VALIDATE gate advancement / canonical completion record.**

Team 100 should explicitly record the cycle 1 mechanism adjustment: the additive `.htaccess` artifact remains, but live enforcement is provided by `NB-V200-runtime` MU plugin on the nginx-served dev host. If Team 100 rejects that mechanism as outside the LOD400 locked decision, route back to `team_10`; otherwise no further remediation cycle is required.
