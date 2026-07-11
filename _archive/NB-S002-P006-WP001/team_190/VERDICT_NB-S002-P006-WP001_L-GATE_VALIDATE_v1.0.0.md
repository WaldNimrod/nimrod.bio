---
type: VERDICT
document_title: "VERDICT — NB-S002-P006-WP001 — Content Batch 001 — L-GATE_VALIDATE cycle 1"
document_version: "v1.0.0"
document_date: "2026-05-27"
date: 2026-05-27
team_id: team_190
phase_owner: team_190
project: nimrod-bio
milestone: V200
program: P006
work_package: NB-S002-P006-WP001
gate: L-GATE_VALIDATE
cycle: "1"
builder: team_99
builder_engine: "Claude Code (claude-code)"
architect: team_110
validator: team_190
validator_engine: "Cursor / GPT-5.5"
spec_ref: "_COMMUNICATION/team_110/LOD400_DRAFT_NB-S002-P006-WP001_v1.0.0.md"
completion_ref: "_COMMUNICATION/team_110/COMPLETION_NB-S002-P006-WP001_2026-05-26_v1.1.0.md"
validate_request_ref: "_COMMUNICATION/team_190/MSG-HUB-20260526-001.md"
branch: feat/p006-wp001-content-batch-001
build_commit: "0ffd8074"
verdict: PASS_WITH_FINDINGS
route_recommendation: "PASS_WITH_FINDINGS -> team_100 gate advancement on feat/p006-wp001-content-batch-001; carry forward Yoast meta polish + WP registration + theme SFA dead-code cleanup as non-blocking advisories"
scope_basis: "Amended scope per MSG-HUB-20260526-{003,004,005,006}; original LOD400 §3.1 SFA CTA theme edit superseded by MSG-005"
---

# VERDICT — NB-S002-P006-WP001 — Content Batch 001 — L-GATE_VALIDATE cycle 1

## 1. Verdict

**Result: PASS_WITH_FINDINGS.**

Team_190 performed independent lightweight L-GATE_VALIDATE on branch `feat/p006-wp001-content-batch-001` against amended LOD400 scope (MSG-003 through MSG-006). Net delivered code change is a single migration script; live dev state matches spec for 11 placeholder posts and SFA service cleanup. No theme or design-system files changed at HEAD. Non-blocking advisories remain for Yoast meta template, roadmap WP registration, validate_aos Check 12 false-positive on content strings, and deferred theme SFA dead-code cleanup.

## 2. Cross-Engine Attestation

- Builder: `team_99`, Claude Code (waldhomeserver), completion artifact `_COMMUNICATION/team_110/COMPLETION_NB-S002-P006-WP001_2026-05-26_v1.1.0.md`.
- Architect/request issuer: `team_110`, Cursor, LOD400 + amendments MSG-003–006.
- Validator: `team_190`, Cursor / GPT-5.5, independent replay against LOD400, git diff, seed script review, and live dev HTTP/REST probes.
- Iron Rule #1 maintained: builder engine (Claude Code) ≠ validator engine (Cursor).

## 3. Findings

| id | severity | evidence-by-path | route_recommendation |
|---|---|---|---|
| T190-P006-WP001-C1-F1 | PASS | `git diff main...HEAD --name-only`: only `scripts/seed_wp006_p006_wp001_placeholders.py` plus `_COMMUNICATION/**` artifacts; no `_aos/` paths; commit `18b35031` reverts SFA seed portion of `4d480c0c`. | PASS; VC-3 / AT-10 satisfied on amended scope. |
| T190-P006-WP001-C1-F2 | PASS | `git diff main...HEAD -- wp-content/themes/nimrod-bio-2026/system.css shell.css theme.json`: empty diff (0 bytes). | PASS; AT-9 satisfied. |
| T190-P006-WP001-C1-F3 | PASS | `scripts/seed_wp006_p006_wp001_placeholders.py`: 11 SEEDS match LOD400 §3.2 v1.0.1 table (slugs, titles, world, flow_style); placeholder HTML matches §3.3 (`data-nb-placeholder="true"`, TODO checklist); loads `.env.upress.dev` without echoing credentials; idempotent slug lookup before POST. | PASS; no code defects blocking merge. |
| T190-P006-WP001-C1-F4 | PASS | Live REST `GET /wp-json/wp/v2/posts?per_page=1`: `X-WP-Total: 33`. All 11 slugs return HTTP 200 on dev HTTP; `data-nb-placeholder="true"` present 11/11 rendered pages. IDs align with `_COMMUNICATION/team_110/p006_wp001_post_creates_result.json` (120–127, 136–138). | PASS; AT-4, AT-5, AT-7, AT-8 satisfied. |
| T190-P006-WP001-C1-F5 | PASS | Live REST services: `?slug=sfa` count=0; `?slug=seed-t7-sfa` count=0; `X-WP-Total: 10`; GET `/services/28` and `/services/44` → HTTP 404. | PASS; AT-S1, AT-S2, AT-S3 satisfied (post-DELETE count=10; team_110 ACK MSG-007 resolves prior discrepancy). |
| T190-P006-WP001-C1-F6 | PASS | Theme grep: `nimrod.bio/wp-content/themes/nimrod-bio-2026/page-heritage.php:104` and `template-parts/shell-footer.php:7` render user-visible "Unless"; footer Mezoo link count=1 at `shell-footer.php:13`. | PASS; amended AT-1 PHP criterion and AT-3 satisfied. |
| T190-P006-WP001-C1-A1 | ADVISORY | Live `<title>` on `/`, `/about/`, `/about/heritage/` use `%title% - nimrod.bio · V200 dev` pattern with no "Unless" substring. Matches builder AT-1 PARTIAL report; architect declared non-blocking (MSG-004/005, MSG-007). | Non-blocking; track as V300 polish or Batch 002 Yoast template task. |
| T190-P006-WP001-C1-A2 | ADVISORY | `_aos/roadmap.yaml` has `NB-S002-P006-PROGRAM` only; `NB-S002-P006-WP001` not registered as standalone WP row. COMPLETION documents `wp_registration_status: PENDING_DOMAIN_REGISTRATION` with team_00 follow-up artifact. | Non-blocking for content delivery; team_100 / team_00 to register WP via authorized path (API when DB online). |
| T190-P006-WP001-C1-A3 | ADVISORY | `bash _aos/lean-kit/modules/validation-quality/scripts/validate_aos.sh .` returned `31 PASS / 16 SKIP / 1 FAIL` — Check 12 flags project-name strings inside placeholder seed content (`tiktrack`, `smallfarmsagents`, etc.). These are blog-post topic metadata, not cross-repo imports. | Non-blocking mechanical false positive; optional Check 12 allowlist for `scripts/seed_wp006_*.py` in a future hub validation touch. |
| T190-P006-WP001-C1-A4 | ADVISORY | COMPLETION §8 lists 7 residual `sfa` slug conditionals in theme PHP (`single-service.php`, `t2-hero.php`, `template-helpers.php`). Out of amended batch scope per MSG-005; dead code while SFA service CPT removed. | Non-blocking; route to Batch 002 / team_10 cleanup per team_110 plan. |
| T190-P006-WP001-C1-N1 | INFO | AT-2 (SFA CTA theme edit) N/A per MSG-005 scope amendment. AT-6 sitemap_index HTTP 200 (smoke). AT-10 / VC-3 adjudicated by this verdict (F1). | PASS; no action. |

## 4. Acceptance Matrix Replay (amended scope)

| row | result | independent evidence |
|---|---|---|
| VC-3 / AT-10 file scope | PASS | Branch diff: one code file `scripts/seed_wp006_p006_wp001_placeholders.py`; no theme/CSS/theme.json; no `_aos/` writes. |
| AT-1 Unless tagline | PASS_WITH_FINDING | PHP renders ≥2 (`page-heritage.php`, `shell-footer.php`). Yoast `<title>` meta lacks "Unless" on sampled pages (A1 advisory). |
| AT-2 SFA CTA | N/A | SFA removed from nimrod.bio per MSG-005. |
| AT-3 Mezoo count | PASS | One user-visible footer link "דיגיטל / מיזו" in `shell-footer.php`. |
| AT-4 11 posts HTTP 200 | PASS | 11/11 slug URLs return HTTP 200 on dev HTTP. |
| AT-5 post count 33 | PASS | REST `X-WP-Total: 33` published posts. |
| AT-6 sitemap | PASS | `GET /sitemap_index.xml` → HTTP 200 (smoke). |
| AT-7 back-to-mud | PASS | `GET /blog/back-to-mud/` → HTTP 200; post id=138. |
| AT-8 placeholder marker ×11 | PASS | `data-nb-placeholder="true"` present on all 11 rendered post pages. |
| AT-9 design system unchanged | PASS | Git diff empty for system.css, shell.css, theme.json. |
| AT-S1 services slug=sfa | PASS | REST query returns empty array. |
| AT-S2 services slug=seed-t7-sfa | PASS | REST query returns empty array. |
| AT-S3 services count post-DELETE | PASS | REST `X-WP-Total: 10`; ids 28 and 44 return 404. |

## 5. Reconciliation with COMPLETION v1.1.0

Independent replay confirms builder claims for post creates, SFA deletions, AT-9, AT-4/5/7/8, AT-S1/S2, and resolved AT-S3 count. COMPLETION §6 post ID table matches `p006_wp001_post_creates_result.json` and live REST. AT-1 Yoast PARTIAL and theme SFA dead-code follow-ups match builder documentation; no material contradictions found.

## 6. Constitutional Assessment

- No unauthorized `_aos/` writes on branch (IR#13 respected by builder after mandate corrections).
- Content-only batch delivered via REST seed script; no MU plugin or theme code changes at HEAD.
- App Password sourced from gitignored `.env.upress.dev`; validator session did not print credentials.
- WP registration gap is process debt, not an implementation defect introduced by this batch.

## 7. Route Recommendation

**PASS_WITH_FINDINGS -> team_100 gate advancement** for `NB-S002-P006-WP001` on branch `feat/p006-wp001-content-batch-001` (build commit `0ffd8074`).

Carry forward as non-blocking:

1. Yoast meta template "Unless" alignment (AT-1 advisory).
2. Register `NB-S002-P006-WP001` in roadmap via authorized API path (team_100 / team_00).
3. Theme SFA dead-code cleanup in Batch 002.
4. Optional validate_aos Check 12 allowlist for content seed scripts.

Do **not** route to team_00 STOP. team_110 may proceed to Batch 002 (media migration via team_10) per activation handoff.
