---
id: MANDATE_NB-S002-P007-WP004_FINAL_VALIDATION
type: VALIDATION_MANDATE
from: team_110 (orchestrator session)
to: team_50 (functional QA) → team_190 (constitutional validate)
cc: team_00
project: nimrod-bio
milestone: V200
wp_id: NB-S002-P007-WP004 (proposed)
wave: 4 of 4 (P007 — final gate)
date: 2026-05-27
priority: P0 (final gate before COMPLETION_CONTENT_PHASE v2.0.0)
status: PARKED — activates after Wave 3 all sub-batches PASS
engines:
  qa: Cursor Composer + MCP (team_50)
  constitutional: OpenAI/Codex (team_190)
wp_registration_status: PENDING_DB_DOMAIN_PROVISIONING
predecessor: NB-S002-P007-WP003 (Content Fill COMPLETIONs)
---

# Wave 4 MANDATE — Final Pre-Cutover Validation Sweep

## 1. Mission

Final dual-layer validation:
- **team_50 functional sweep** — re-run prior 14 QA items + scenario matrix + screenshot diff vs Wave 1 baseline + Lighthouse retest
- **team_190 constitutional validate** — L-GATE_VALIDATE on cumulative P007 deliverables (all 3 prior WPs)

Outcome: PASS → team_110 signs `COMPLETION_CONTENT_PHASE v2.0.0` → cutover MANDATE unfrozen.

## 2. Inputs

| # | Path | Purpose |
|---|---|---|
| 1 | All `_COMMUNICATION/team_110/COMPLETION_NB-S002-P007-WP003_BATCH-*` | Wave 3 build evidence |
| 2 | `_COMMUNICATION/team_50/MCP_QA_REPORT_NB-S002-P007-WP001_*` | Wave 1 baseline screenshots |
| 3 | `_COMMUNICATION/team_00/RESPONSE_INVENTORY_P007_*` | what was committed to fill |
| 4 | `_COMMUNICATION/team_00/INVENTORY_*` ×3 | what was promised |
| 5 | Live dev URL + WP REST | current state |

## 3. Sub-stage 4a — team_50 functional + visual sweep

### Scope
- Re-run ALL 14 QA items from `MANDATE_NB-V200-FULL-QA-PRE-CUTOVER_2026-05-26_v1.0.0.md` (now should pass with content)
- Re-run 5-scenario matrix per GCR-002
- **NEW:** screenshot diff vs Wave 1 baseline (improvement check)
- **NEW:** Lighthouse re-run on `/`, 1 service, 1 project, 1 post (compare to Wave 1 + P005-WP001 baseline)
- **NEW:** placeholder marker check — must be `_nb_placeholder=true` == 0 across all posts (or explicit team_00 acceptance)

### Deliverable
`_COMMUNICATION/team_50/MCP_QA_FINAL_NB-S002-P007-WP004_<date>_v1.0.0.md` + verdict box (PASS / PASS_WITH_FINDINGS / FAIL) at top.

## 4. Sub-stage 4b — team_190 constitutional validate

### Scope (Iron Rule #1: team_190 Codex, distinct from Cursor builders)
- Independent + adversarial review of cumulative P007 deliverables
- VC-3 file scope: theme PHP / CSS / theme.json — empty diff expected
- Iron Rule conformance check
- Findings categorization
- Final verdict at L-GATE_VALIDATE

### Deliverable
`_COMMUNICATION/team_190/VERDICT_NB-S002-P007-WP004_L-GATE_VALIDATE_v1.0.0.md` + §0 verdict box.

## 5. Sub-stage 4c — team_110 final signature

Conditional on 4a + 4b both PASS / PASS_WITH_FINDINGS:

Deliverable: `_COMMUNICATION/team_110/COMPLETION_CONTENT_PHASE_<date>_v2.0.0.md`
- Supersedes v1.0.0 (aef6fbf7)
- Signs V200 sub-phase 2 closed
- Unfreezes P005-WP002 cutover MANDATE (precondition met)

## 6. Acceptance tests (Wave 4 overall)

| # | Criterion | PASS |
|---|---|---|
| AT-V1 | team_50 4a PASS or PASS_WITH_FINDINGS | report verdict box |
| AT-V2 | team_190 4b PASS or PASS_WITH_FINDINGS | verdict commit |
| AT-V3 | Screenshot improvement vs Wave 1 | majority of templates show fill (content present where placeholder was) |
| AT-V4 | Placeholder marker count = 0 | (or explicit team_00 acceptance) |
| AT-V5 | Lighthouse no further regression vs Wave 1 baseline | (uplift to V300 acceptable) |
| AT-V6 | Cumulative P007 has clean diff path | git history clean from main |
| AT-V7 | team_110 signs v2.0.0 | artifact present |

## 7. STOP conditions

- Wave 3 sub-batches not all PASS → STOP, route back to team_10
- AT-V4 fails AND team_00 has NOT explicitly accepted publish-with-placeholder → STOP, escalate
- team_190 issues FAIL or BLOCKED → STOP, route back per verdict route_recommendation
- AT-V5 shows Lighthouse WORSE than Wave 1 (regression on top of regression) → STOP, escalate to team_00

## 8. Activation prompts (2 separate sub-sessions)

### 8.1 team_50 sub-session

```
═══════════════════════════════════════════════════════════════
TEAM 50 — QA & Functional Acceptance (Cursor + MCP)
ACTIVATION — V200 Wave 4a · Final QA Sweep
═══════════════════════════════════════════════════════════════

זהות + Engine: team_50 / Cursor + MCP
Governance: /Users/nimrod/Documents/agents-os/_aos/governance/team_50.md
Context: V200 Wave 4 — final pre-cutover validation; predecessor Wave 3 PASS

המנדט
─────
_COMMUNICATION/team_50/MANDATE_NB-S002-P007-WP004_FINAL_VALIDATION_v1.0.0.md §3

המשימה
──────
1. /AOS_mail
2. Re-run 14 QA items + 5-scenario matrix
3. Capture new screenshots + diff vs Wave 1 baseline
4. Lighthouse re-run on 4 URLs + compare to baselines
5. Placeholder marker count
6. Produce MCP_QA_FINAL artifact + verdict box

ETA: ~2-3 שעות
═══════════════════════════════════════════════════════════════
```

### 8.2 team_190 sub-session

```
═══════════════════════════════════════════════════════════════
TEAM 190 — Senior Constitutional Validator (Codex / OpenAI)
ACTIVATION — V200 Wave 4b · L-GATE_VALIDATE on P007
═══════════════════════════════════════════════════════════════

זהות + Engine: team_190 / OpenAI Codex (cross-engine vs Cursor builders)
Governance: /Users/nimrod/Documents/agents-os/_aos/governance/team_190.md

קונטקסט
───────
- WP: NB-S002-P007-WP004 (cumulative P007 validate)
- Branch path: per Wave 3 sub-batches
- Independence + adversarial mandatory

המנדט
─────
_COMMUNICATION/team_50/MANDATE_NB-S002-P007-WP004_FINAL_VALIDATION_v1.0.0.md §4

המשימה
──────
1. /AOS_mail
2. L-GATE_VALIDATE on cumulative P007 (WP001..WP003)
3. §0 verdict box (PASS / PASS_WITH_FINDINGS / FAIL / BLOCKED)
4. Commit: validate(NB-S002-P007-WP004/L-GATE_VALIDATE): {VERDICT} — Team 190

ETA: ~1-2 שעות
═══════════════════════════════════════════════════════════════
```

— team_110 (originating session) — 2026-05-27
