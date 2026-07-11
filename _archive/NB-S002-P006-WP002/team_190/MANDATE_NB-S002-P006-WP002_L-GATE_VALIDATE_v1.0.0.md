---
type: MANDATE
gate: L-GATE_VALIDATE
from: team_110 (Domain Architect · cursor-composer)
to: team_190 (Senior Constitutional Validator · OpenAI/Codex)
cc: team_00 (Principal)
project: nimrod-bio
milestone: V200
wp: NB-S002-P006-WP002
date: 2026-05-27
priority: P1 (gates COMPLETION_CONTENT_PHASE → cutover P005-WP002)
branch: feat/p006-wp002-media-migration
branch_head: ed7b839c (verified on origin)
predecessor_validate: NB-S002-P006-WP001 PASS_WITH_FINDINGS (verdict commit 2c92ecef)
artifacts_refs:
  lod400: _COMMUNICATION/team_110/LOD400_DRAFT_NB-S002-P006-WP002_v1.0.0.md
  completion: _COMMUNICATION/team_110/COMPLETION_NB-S002-P006-WP002_2026-05-27_v1.0.0.md
  validate_request: _COMMUNICATION/team_190/VALIDATE_REQUEST_NB-S002-P006-WP002_v1.0.0.md
authorization_chain:
  - team_00 directive 2026-05-26 (content phase + media migration scope)
  - team_110 GATE_2 architecture approval (LOD400 v1.0.0)
  - team_10 build COMPLETION (2026-05-27 v1.0.0)
expects_response: true
expected_verdict_path: _COMMUNICATION/team_190/VERDICT_NB-S002-P006-WP002_L-GATE_VALIDATE_v1.0.0.md
---

# MANDATE — L-GATE_VALIDATE on NB-S002-P006-WP002 (Media Migration + Bundled Cleanup)

## 1. Authority + scope of this validate

Constitutional L-GATE_VALIDATE on Batch 002 (media migration + bundled SFA dead-code cleanup + Yoast Unless). Binary verdict per team_190 governance: **PASS / PASS_WITH_FINDINGS / FAIL / BLOCKED**. §0 verdict box mandatory.

This is a **content-migration + minor-cleanup batch** with significant runtime data changes (685 media file uploads + 22 post HTML rewrites). Adversarial review expected.

## 2. Scope of changes to validate (commit ed7b839c)

### 2.1 New files (in scope)
| File | LOC | Purpose |
|---|---|---|
| `scripts/migration/migrate_media_v200_p006_wp002.py` | 754 | Resumable media migration (REST upload + URL rewrite) |
| `scripts/migration/state/migrate_media_progress.json` | 5,530 | Per-file state (resumable checkpoint) |
| `scripts/migration/state/migrate_media_report.json` | 166 | Final report |
| `scripts/migration/state/url_map.json` | 5,481 | old→new URL mapping (canonical) |
| `scripts/migration/state/pre_rewrite_posts_backup.json` | 266 | Pre-rewrite post HTML backup (rollback) |
| `scripts/migration/logs/migrate_media_*.log` | ~48 | Run logs (2 successful runs) |
| `_COMMUNICATION/team_110/COMPLETION_NB-S002-P006-WP002_2026-05-27_v1.0.0.md` | 86 | COMPLETION |
| `_COMMUNICATION/team_190/MSG-HUB-20260527-001_*.md` | 22 | HUB message to you |
| `_COMMUNICATION/team_190/VALIDATE_REQUEST_NB-S002-P006-WP002_v1.0.0.md` | 65 | Validate request |

### 2.2 Modified files (in scope)
| File | Δ | Purpose |
|---|---|---|
| `wp-content/themes/nimrod-bio-2026/single-service.php` | +/−31 | SFA dead-code removal (5 conditionals) |
| `wp-content/themes/nimrod-bio-2026/template-parts/t2-hero.php` | +/−1 | SFA dead-code removal (image caption) |
| `wp-content/themes/nimrod-bio-2026/inc/template-helpers.php` | −6 | SFA static seed data removal |
| `wp-content/mu-plugins/sfagent-allow-json.php` | +/−21 | ⚠️ **NOT IN ORIGINAL LOD400** — see §3.3 special attention |

### 2.3 Out of scope for this verdict
- LOD400 §3.1 in-scope checks remain in effect (Iron Rule §8.1 file scope binding)
- NB-S002-P006-WP001 already PASS_WITH_FINDINGS (verdict 2c92ecef) — do NOT re-validate
- Batch 001 commits (4d480c0..2d4cf13b) appear in branch stack but are predecessor — validate ONLY ed7b839c delta

## 3. Verification targets

### 3.1 Iron Rule conformance
- **IR #1 cross-engine:** builder team_10 = Cursor Composer; validator team_190 = OpenAI/Codex — CONFIRM cross
- **IR #6 canonical artifacts:** COMPLETION + VALIDATE_REQUEST in correct paths
- **IR #7 API-only when DB online:** N/A — this batch touches WP REST (not AOS DB)
- **IR §8.1 HEAD-freeze on main:** this batch never committed to main; PASS by construction
- **AT-9 design system unchanged:** verify `system.css` / `shell.css` / `theme.json` empty diff

### 3.2 Acceptance test verification (team_10 self-reported PASS)
| AT | claim | how to verify |
|---|---|---|
| AT-M1 | dev media count meaningful jump from 0 → ~685 | `curl -sIk $UPRESS_DEV_URL_HTTP/wp-json/wp/v2/media?per_page=1 \| grep x-wp-total` |
| AT-M2 | 30/30 sampled inline `<img>` URLs → HTTP 200 | random-sample from `pre_rewrite_posts_backup.json` (post HTML pre-rewrite) → check rewritten URLs on dev |
| AT-M3 | 17/17 featured_media coverage 100% | sample 5 posts → check `featured_media != 0` |
| AT-M4 | sitemap regen | NOTE: team_10 reported PASS_WITH_NOTE — media sitemap flag false on dev. Adversarial check: is this acceptable per Yoast config or oversight? |
| AT-M5 | uploads disk size sane | low priority |
| AT-M6 | 9 SFA files skipped | `migrate_media_report.json` should list `sfa_skipped_count: 9` |
| AT — Unless on Yoast home | `curl /` → `<title>` includes "Unless" | confirmed by team_110 — `<title>בית - nimrod.bio · V200 dev · Unless</title>` |
| AT — theme SFA refs removed | `grep -rE "'sfa' === \$slug" wp-content/themes/` returns 0 | verify branch state |

### 3.3 ⚠️ Special attention — mu-plugin change

`wp-content/mu-plugins/sfagent-allow-json.php` was modified (21 lines) but **NOT in original LOD400 §3 scope**. team_110 architecture review:

- The plugin name suggests it relates to JSON file upload allowance (likely the JSON file `sfagent-crop-book-manifest-of-urls.json` seen as the first media item on prod)
- Likely added during migration to allow JSON MIME type uploads that WP rejects by default
- **Adversarial question:** Was this scope creep, or a legitimate infrastructure-class adaptation required for AT-M1 to pass? If the latter, team_10 should have escalated as a CLARIFICATION_REQUEST or annotated it explicitly in COMPLETION §3 (deltas).

team_190 may flag this as a FINDING (not necessarily blocking) requiring team_110 to note + accept post-hoc. team_00 final approval likely covers it.

### 3.4 Data integrity (live state)
- 22 migrated posts still render correctly (no broken HTML from URL rewrite)
- 11 placeholder posts (Batch 001) untouched
- No orphan references to old prod URLs `https://www.nimrod.bio/wp-content/uploads/` (replaced)
- `pre_rewrite_posts_backup.json` is complete and matches the 22 source posts

### 3.5 Constitutional checks
- No new tooling/plugin classes beyond infrastructure (mu-plugin is infrastructure-class — acceptable per Iron Rule "תוסף חדש אסור אלא אם infrastructure-class")
- No `_aos/` writes from team_10 (verify via `git diff main..ed7b839c -- _aos/` should be empty)
- No `system.css` / `shell.css` / `theme.json` writes

## 4. Verdict guidance (not binding — your independence required)

team_110 architect estimate based on COMPLETION + tree review:
- Most likely: **PASS_WITH_FINDINGS** (analogous to Batch 001)
- Findings to expect: AT-M4 media sitemap flag, mu-plugin scope addition, scale of JSON state files
- Blocking issues: none anticipated

team_190 adversarial verdict overrides this estimate. STOP if you find a constitutional violation.

## 5. Deliverable

| Item | Path |
|---|---|
| Verdict artifact | `_COMMUNICATION/team_190/VERDICT_NB-S002-P006-WP002_L-GATE_VALIDATE_v1.0.0.md` |
| §0 box | mandatory in chat output |
| Commit | `validate(NB-S002-P006-WP002/L-GATE_VALIDATE): {VERDICT} — Team 190` |

## 6. After verdict — next gate

| Verdict | Route |
|---|---|
| PASS | team_110 → activate team_50 (QA mandate already parked at `_COMMUNICATION/team_50/MANDATE_NB-V200-FULL-QA-PRE-CUTOVER_*.md`) |
| PASS_WITH_FINDINGS | Same as PASS for routing; findings logged for COMPLETION_CONTENT_PHASE record |
| FAIL | STOP; route_recommendation back to team_10 with specific fixes |
| BLOCKED | STOP; escalate to team_00 |

— team_110 (cursor-composer · Mac) — 2026-05-27
