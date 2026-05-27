---
id: MANDATE_P005-WP002_CUTOVER
type: CUTOVER_MANDATE
from: team_110 (Domain Architect)
to: team_99 (Home Server Team · claude-code)
cc: team_00, team_190, team_50
project: nimrod-bio
milestone: V200
wp: NB-S002-P005-WP002
date: 2026-05-27
version: v1.1.0
supersedes: MANDATE_P005-WP002_CUTOVER_2026-05-27_v1.0.0.md
priority: P0 (final V200 step)
status: PARKED — awaiting (a) P007-WP004 PASS AND (b) team_00 D-day GO
amendment_reason: V200 cutover gated on additional pre-cutover validation suite (P007) per team_00 directive 2026-05-27
authorization_chain:
  - team_00 directive 2026-05-26 (content phase) + 2026-05-27 (4-wave P007 plan)
  - team_110 PLAN_P007_PRE_CUTOVER_COMPLETION_2026-05-27_v1.0.0.md
  - team_110 COMPLETION_CONTENT_PHASE_2026-05-27_v1.0.0.md (sub-phase 1 closed)
lod400_ref: _aos/work_packages/NB-S002-P005-WP002/LOD400_NB-S002-P005-WP002.md
---

# MANDATE v1.1.0 — Production cutover · ADDITIONAL PRECONDITION

## What changed vs v1.0.0

**v1.0.0 precondition:** "team_00 explicit go-signal on D-day"
**v1.1.0 precondition:** **P007-WP004 PASS** (cumulative pre-cutover validation) **AND** team_00 D-day GO

All other content from v1.0.0 (sequence, rollback, STOP conditions, out-of-scope, deliverable) is **unchanged**. See v1.0.0 for full runbook reference.

## P007-WP004 PASS — gating artifact

Cutover may proceed ONLY after team_110 signs:
`_COMMUNICATION/team_110/COMPLETION_CONTENT_PHASE_<date>_v2.0.0.md`

which supersedes v1.0.0 and explicitly confirms:
- All 4 P007 waves PASSed
- team_50 functional QA final sweep PASS
- team_190 constitutional validate PASS at L-GATE_VALIDATE on cumulative P007
- 0 unresolved BLOCKING findings

## Authorization to execute

Unchanged from v1.0.0. team_99 reads team_00 chat / inbox GO signal on D-day.

## Note to team_99

If activated under v1.0.0 expectation but COMPLETION_CONTENT_PHASE v2.0.0 not present:
- STOP immediately
- Acknowledge to team_110
- Wait for v2.0.0 signature before D-1 prep

— team_110 — 2026-05-27 (v1.1.0)
