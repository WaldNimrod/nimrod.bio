---
type: WAIVER
from: team_00 (Principal) · authored by team_110 on behalf
project: nimrod-bio
milestone: V200
program: P007
date: 2026-05-28
version: v1.0.0
status: APPROVED
finding_ref: T190-P007-WP004-F3 in VERDICT_NB-S002-P007-WP004_L-GATE_VALIDATE_v1.0.0.md
predecessor_waiver: _COMMUNICATION/team_00/WAIVER_F-003_VALIDATE_AOS_CHECK_12_2026-05-27_v1.0.0.md
referenced_file: scripts/seed_wp006_p006_wp001_placeholders.py
referenced_check: validate_aos.sh Check 12 (project-boundary forbidden patterns)
authorization: team_00 chat approval 2026-05-27 — DECISION_COMPLETION_CONTENT_PHASE_2026-05-27_v1.md (predecessor waiver, explicitly scoped to V200 milestone)
---

# Waiver — F-003 / P007 scope: validate_aos.sh Check 12 false positive

## Context

This is a P007-scoped extension of the predecessor waiver filed 2026-05-27 for P006 scope. The predecessor waiver was granted "for the duration of V200 milestone" and specifies that "team_190 and team_50 may treat Check 12 fail on this specific file path as 'WAIVED' for V200."

team_190 Round 1 verdict (T190-P007-WP004-F3) noted that the Check 12 failure is not newly introduced by P007 (predates P007, commits `4d480c0c`, `0ffd8074`) but requires explicit P007-scoped waiver reference before accepting PASS.

## Finding (verbatim from T190-P007-WP004-F3)

> AOS validation is red in the current working tree: 31 PASS / 16 SKIP / 1 FAIL. L-GATE_VALIDATE expects 0 FAIL. Check 12 reports tracked cross-project contamination in `scripts/seed_wp006_p006_wp001_placeholders.py` for `tiktrack`, `smallfarmsagents`, `agros-insite`, and `microgreens`.

## Explicit P007 waiver

The predecessor waiver (2026-05-27 v1.0.0) covers this finding fully under its V200-milestone scope. This artifact makes that coverage explicit for the P007 L-GATE_VALIDATE round:

| Field | Value |
|---|---|
| **File** | `scripts/seed_wp006_p006_wp001_placeholders.py` |
| **Trigger strings** | `tiktrack`, `smallfarmsagents`, `agros-insite`, `microgreens` |
| **Why false positive** | Strings appear in placeholder seed content that is *about* those AOS projects by editorial intent (team_00 directive 2026-05-26 §Q2). Not cross-domain code imports. |
| **Scope** | V200 milestone, including P007 all rounds |
| **Pre-existing** | Yes — commits `4d480c0c`, `0ffd8074`, predating P007 |
| **P007 introduced?** | No |

## Authorization chain

1. team_00 chat approval 2026-05-27 → predecessor waiver v1.0.0 filed, covering "V200 milestone"
2. P007 is a V200 sub-phase → falls within predecessor waiver scope
3. This artifact makes the chain explicit for team_190 Round 2 review

## Conditions (carried from predecessor)

1. Waiver expires when placeholder content is fully replaced by real content (post-cutover) AND seed script is no longer referenced.
2. Applies ONLY to this specific file (`scripts/seed_wp006_p006_wp001_placeholders.py`) and these specific strings.
3. Other Check 12 failures (e.g., cross-domain imports) remain blocking.

## Instruction to team_190

For Round 2 revalidation: Check 12 fail on `scripts/seed_wp006_p006_wp001_placeholders.py` is WAIVED per this artifact + predecessor waiver 2026-05-27. Treat as PASS for L-GATE_VALIDATE P007 all rounds.

— team_110 (authored on team_00's behalf, extending predecessor waiver authorization) — 2026-05-28
