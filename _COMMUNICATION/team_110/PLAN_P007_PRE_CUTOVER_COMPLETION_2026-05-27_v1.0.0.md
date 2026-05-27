---
type: PROGRAM_PLAN
from: team_110 (Domain Architect)
to: team_00 (Principal) + future-session orchestrator
project: nimrod-bio
milestone: V200
program: P007 — Pre-Cutover Completion
date: 2026-05-27
version: v1.0.0
status: APPROVED (team_00 approval 2026-05-27)
db_registration_status: PENDING (see FOLLOW_UP_aos_domain_db_provisioning_2026-05-27)
orchestrator_session: NEW SESSION required (this current session ends after handoff)
---

# P007 — Pre-Cutover Completion · Master Plan (4 Waves)

## 1. Charter

V200 sub-phase 2 — content completion + design fidelity + final validation — between sub-phase 1 closure (COMPLETION_CONTENT_PHASE v1.0.0, commit aef6fbf7) and P005-WP002 production cutover.

**Cutover MANDATE** stays parked. Precondition amended (see v1.1.0) from "team_00 D-day GO" to "P007-WP004 PASS + team_00 D-day GO".

## 2. Wave structure (canonical)

| # | WP ID | Title | Owner | Engine | Duration | Gate |
|---|---|---|---|---|---|---|
| 1 | NB-S002-P007-WP001 | MCP Browser QA + Design Fidelity | team_50 (ext.) | Cursor + MCP | ~2-3h | team_110 review |
| 2 | NB-S002-P007-WP002 | Completion Inventory (3 lists) | team_110 | Cursor | ~1-2h | team_00 commitment |
| 3 | NB-S002-P007-WP003 | Content Fill + Integration | team_10 + team_00 | Cursor + admin UI | days (team_00 paced) | team_110 sweep |
| 4 | NB-S002-P007-WP004 | Final Validation Sweep | team_50 + team_190 | Cursor + Codex | ~3-4h | PASS → COMPLETION_CONTENT_PHASE v2.0.0 |

## 3. Cross-engine map (Iron Rule #1)

| Role | Engine |
|---|---|
| Wave 1 builder = team_50 | Cursor + MCP |
| Wave 2 builder = team_110 | Cursor |
| Wave 3 builder = team_10 | Cursor |
| Wave 4 functional = team_50 | Cursor |
| **Wave 4 constitutional = team_190** | **Codex (distinct ✓)** |
| Cutover executor = team_99 | claude-code (distinct from team_190 ✓) |

## 4. Per-wave MANDATEs (this session writes; new session dispatches)

| MANDATE | Path |
|---|---|
| Wave 1 | `_COMMUNICATION/team_50/MANDATE_NB-S002-P007-WP001_MCP_DESIGN_QA_v1.0.0.md` |
| Wave 2 | `_COMMUNICATION/team_110/MANDATE_NB-S002-P007-WP002_COMPLETION_INVENTORY_v1.0.0.md` |
| Wave 3 | `_COMMUNICATION/team_10/MANDATE_NB-S002-P007-WP003_CONTENT_FILL_v1.0.0.md` |
| Wave 4 | `_COMMUNICATION/team_50/MANDATE_NB-S002-P007-WP004_FINAL_VALIDATION_v1.0.0.md` |

Each MANDATE includes embedded activation prompt for paste-to-session use.

## 5. Session orchestration

**This current session (team_110 originating):**
- Writes 4 MANDATEs
- Writes this PLAN
- Writes cutover MANDATE amendment (v1.1.0)
- Writes FOLLOW_UP for DB block
- Performs 8-check self-validation
- Writes AOS_handoff full artifact for next session

**Next session (orchestrator, team_110 role, new seat):**
- Picks up via handoff artifact
- Dispatches Wave 1 (gives user the activation prompt)
- Monitors Wave 1 COMPLETION
- Dispatches Wave 2, 3, 4 in sequence
- Signs gates between waves
- Final signs COMPLETION_CONTENT_PHASE v2.0.0
- Unfreezes cutover MANDATE

Each wave session (team_50, team_10, team_190 invocations) is sub-session under orchestrator.

## 6. Self-validation checklist (team_110 GATE_2)

| # | Check | Result |
|---|---|---|
| 1 | Strategic alignment with V200 roadmap | ✅ extends V200 to true cutover-ready state |
| 2 | Iron Rules — cross-engine map | ✅ team_190 Codex ≠ Cursor builders ≠ team_99 claude-code |
| 3 | Iron Rules — file scope per MANDATE | ✅ each MANDATE §3 explicit |
| 4 | LOD400 sufficiency — junior dev can implement | ✅ each MANDATE has activation prompt + tasks + AT |
| 5 | Team assignments — TRACK_FOCUSED | ✅ team_50 QA / team_110 architect / team_10 build / team_190 constitutional |
| 6 | Acceptance tests defined | ✅ per-wave AT section |
| 7 | Stop conditions | ✅ each MANDATE §7 |
| 8 | Cross-engine on every builder→validator boundary | ✅ Wave 4 enforces |

PASS — proceed with handoff to new session.

## 7. Pending items (not blocking handoff)

- FOLLOW_UP_aos_domain_db_provisioning_2026-05-27 — out-of-band ops issue
- nimrod-bio domain ULID still missing (artifact-based WPs accepted per ADR034 R8 spirit)
- Cutover MANDATE precondition updated to require P007-WP004 PASS

— team_110 (originating session · cursor-composer) — 2026-05-27
