# VERDICT — NB-S002-P006-WP002 — team_190 — v1.0.0

**Date:** 2026-05-27
**Author:** team_190
**WP:** NB-S002-P006-WP002
**Type:** VERDICT

## §0 Verdict Box

| Field | Value |
|---|---|
| Verdict | PASS_WITH_FINDINGS |
| WP / Gate / Round | NB-S002-P006-WP002 / L-GATE_VALIDATE / Round 1 |
| Scope | Validate only commit `ed7b839c` delta; NB-S002-P006-WP001 predecessor excluded |
| One-line next step | Route may continue to team_50 pre-cutover QA, with findings logged for team_110/team_00 acceptance. |

## 1. Independence Statement

team_190 performed an independent constitutional validation of the `ed7b839c` delta on branch `feat/p006-wp002-media-migration`. The review used the mandate, LOD400, completion package, target commit diff, generated migration state, and independent live probes against the dev site.

Builder engine and validator engine remain cross-engine:

| Role | Team | Engine |
|---|---|---|
| Builder | team_10 | Cursor / Codex |
| Validator | team_190 | OpenAI / Codex API |

## 2. Scope Reviewed

In scope:

- `_COMMUNICATION/team_110/LOD400_DRAFT_NB-S002-P006-WP002_v1.0.0.md`
- `_COMMUNICATION/team_110/COMPLETION_NB-S002-P006-WP002_2026-05-27_v1.0.0.md`
- `_COMMUNICATION/team_190/VALIDATE_REQUEST_NB-S002-P006-WP002_v1.0.0.md`
- `scripts/migration/migrate_media_v200_p006_wp002.py`
- `scripts/migration/state/migrate_media_progress.json`
- `scripts/migration/state/migrate_media_report.json`
- `scripts/migration/state/url_map.json`
- `scripts/migration/state/pre_rewrite_posts_backup.json`
- `nimrod.bio/wp-content/mu-plugins/sfagent-allow-json.php`
- `nimrod.bio/wp-content/themes/nimrod-bio-2026/single-service.php`
- `nimrod.bio/wp-content/themes/nimrod-bio-2026/template-parts/t2-hero.php`
- `nimrod.bio/wp-content/themes/nimrod-bio-2026/inc/template-helpers.php`

Out of scope:

- NB-S002-P006-WP001 Batch 001, already signed by prior team_190 verdict.
- Non-target dirty-tree artifacts not introduced by `ed7b839c`.

## 3. Acceptance Replay

| Check | Result | Evidence |
|---|---|---|
| AT-M1 media count | PASS | Old media header `694`; dev media header `843`. |
| AT-M2 inline image availability | PASS | Independent deterministic sample: `30/30` unique rendered image URLs returned HTTP 200; no non-200 sample result. |
| AT-M3 featured media | PASS | Public REST showed 17 posts with non-zero `featured_media`, matching expected 17/17 coverage. |
| AT-M4 sitemap | FINDING | `/sitemap_index.xml` returned HTTP 200 but does not include `media-sitemap`. |
| AT-M5 size sanity | PASS_WITH_NOTE | Final report records only incremental closing-run upload size (`0.31 MB`); full migration evidence is in progress/state and live media count. |
| AT-M6 SFA exclusion | PASS | Progress state has 9 `skipped_sfa`; URL map has 0 SFA-pattern keys/values. |
| Yoast Unless | PASS | Home `<title>` is `בית - nimrod.bio · V200 dev · Unless`. |
| SFA theme refs removed | PASS | No theme match for `'sfa' === $slug`; inspected diff removes SFA service branches. |

## 4. Data Integrity Findings

Migration state is internally consistent:

- `migrate_media_progress.json`: 694 items total.
- Status counts: 684 `uploaded`, 9 `skipped_sfa`, 1 `source_http_error`.
- The single exception is `https://www.nimrod.bio/wp-content/uploads/woocommerce-placeholder.png`, which returns HTTP 404 from source.
- `url_map.json`: `replacement_count=4788`, actual map entries `4788`, `old_to_new_media_id` count `684`.
- `pre_rewrite_posts_backup.json`: 22 posts, 0 old-prod upload refs, 556 dev upload refs.
- Public rendered dev posts: 91 unique image URLs, 0 old-prod upload refs.

## 5. Constitutional Checks

| Check | Result | Evidence |
|---|---|---|
| IR #1 cross-engine | PASS | Builder team_10 != validator team_190; Cursor/Codex vs OpenAI/Codex API. |
| IR #6 canonical artifacts | PASS | Completion and validate request are in canonical `_COMMUNICATION/` paths. |
| IR #7 API-only structured mutations | PASS | No AOS DB structured mutation in this batch; WordPress REST/media mutation only. |
| `_aos/` writes | PASS | `git diff ed7b839c^ ed7b839c -- _aos` is empty. |
| Design system immutability | PASS | `system.css`, `shell.css`, and `theme.json` have empty target diff. |
| PHP syntax | PASS | `php -l` passed for all touched PHP files. |
| Python syntax | PASS | `python3 -m py_compile` passed for migration script and `_lib.py`. |
| AOS validation | FINDING | `validate_aos.sh` reports 31 PASS / 16 SKIP / 1 FAIL due Check 12 forbidden patterns in `scripts/seed_wp006_p006_wp001_placeholders.py`; that file is not touched by `ed7b839c`. |

## 6. Findings

| ID | Severity | Finding | evidence_by_path | route_recommendation |
|---|---|---|---|---|
| F-001 | Non-blocking governance finding | `sfagent-allow-json.php` was changed beyond the LOD400 primary media scope. The SVG MIME addition is a legitimate infrastructure-class adaptation for media migration, but the same MU plugin also adds a Yoast title fallback. This is operationally effective and not a blocker, but it is post-hoc scope expansion and should be explicitly accepted by team_110/team_00. | `nimrod.bio/wp-content/mu-plugins/sfagent-allow-json.php`; `_COMMUNICATION/team_110/LOD400_DRAFT_NB-S002-P006-WP002_v1.0.0.md`; `_COMMUNICATION/team_110/COMPLETION_NB-S002-P006-WP002_2026-05-27_v1.0.0.md` | team_110 to log post-hoc acceptance; team_50 to ensure no unintended title duplication during pre-cutover QA. |
| F-002 | Non-blocking acceptance finding | AT-M4 remains false on dev: sitemap index exists but no media sitemap is exposed. This does not invalidate media availability or rendered post integrity, but it should remain visible for SEO/cutover verification. | `scripts/migration/state/migrate_media_report.json`; live `/sitemap_index.xml` probe | Carry to team_50 pre-cutover QA / cutover SEO checklist. |
| F-003 | Non-blocking inherited constitutional debt | `validate_aos.sh` is not clean because Check 12 flags forbidden strings in `scripts/seed_wp006_p006_wp001_placeholders.py`. The target commit did not touch that file, so this is not a blocker for the `ed7b839c` delta verdict, but the branch should not be represented as globally AOS-clean until resolved or formally waived. | `scripts/seed_wp006_p006_wp001_placeholders.py`; `validate_aos.sh` output; empty target diff for that file | Route to team_110/team_00 for branch-level cleanup or explicit waiver before final cutover merge. |

## 7. Verdict

PASS_WITH_FINDINGS.

No blocking constitutional violation was found in the `ed7b839c` delta. Media migration outcomes are live-verified, generated state is internally consistent, SFA media exclusion is honored, SFA dead-code cleanup is correct, and protected AOS/design files were not modified.

The findings are non-blocking because they do not break the WP002 acceptance target or introduce a target-delta Iron Rule violation. They must be carried forward into the content-phase closeout and team_50 pre-cutover QA.
