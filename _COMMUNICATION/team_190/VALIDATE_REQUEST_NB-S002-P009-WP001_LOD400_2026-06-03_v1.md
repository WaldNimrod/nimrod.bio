# VALIDATE REQUEST — NB-S002-P009-WP001 LOD400 (spec-level) — team_100 → team_190 — v1

**Date:** 2026-06-03
**From:** team_100
**To:** team_190 (constitutional validation, cross-engine, immutable)
**Type:** L-GATE_SPEC external validation (LOD400 precision gate) — pre-build
**WP:** NB-S002-P009-WP001 · **Track:** A · **Effort:** NORMAL

## What to validate
The **LOD400 spec** for landing team_35's V200 precision package — NOT the deployed result (that comes later at L-GATE_VALIDATE). Per team_00 directive: "characterize the move + LOD400 + external validation."

- **Spec:** `_aos/work_packages/NB-S002-P009-WP001/LOD400_NB-S002-P009-WP001.md`
- **Source package:** `_COMMUNICATION/team_35/HANDOFF_CLAUDE_CODE_V200_2026-06-03/` (README + 6 theme files + 2 render-parity previews)
- **Baseline:** theme `nimrod-bio-2026` @ `a35a67df` (v0.7.15)

## LOD400 precision-gate criteria (team_190 standard)
Verify the spec is detailed enough for any fresh agent to implement without gaps/guesses:
1. **Change inventory complete + accurate** — does the §3 Δ table match the actual diff between the handoff `theme/` files and the repo @ a35a67df? (Re-diff to confirm: front-page +70L, archive-project NEW, cpt-project 2L, template-styles-t1 2L, t7 ~149L, t1 ~55L.)
2. **Both new templates fully specified** — §06 recent-posts (WP_Query shape, markup, placement, retired scaffolds) + `/projects/` archive (has_archive flip, enqueue, empty-state, permalink-flush dependency).
3. **All 3-source fixes captured** — team_00 (A1 Unless, A2 bridge underline, Δ1 line-breaks, Δ2 world-card images), team_35 (B1 lattice crush; B2/B3 = no-change-correct), team_50 (C1 resolved, C2 open, C4 byte-check no-change).
4. **Acceptance criteria checkable** — are the 11 §6 criteria each verifiable on dev against the mockup + render-parity previews?
5. **Procedure compliance** — FTPS deploy, permalink flush, byte-parity, no-inline/no-overrides rule, Iron Rule #4 single-writer, lock-scan.
6. **Open gaps correctly scoped out** — G2/G3/C2 carried forward, not silently dropped.
7. **Locks** — spec + package lock-clean (Micha · demonstrate-never-name).

## Verdict
PASS / PASS_WITH_FINDINGS / FAIL to `_COMMUNICATION/team_190/VERDICT_NB-S002-P009-WP001_LOD400_*`. On PASS, team_100 proceeds to the atomic land+deploy (Phase 2–4), then a SEPARATE L-GATE_VALIDATE on the deployed result.

## Cross-engine note
team_190 engine ≠ the team_35 design engine and ≠ team_100. Independent review of the spec's completeness + correctness.

*team_100 | validate request (LOD400 spec gate) | 2026-06-03*
