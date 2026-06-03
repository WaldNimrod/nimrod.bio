# VALIDATE REQUEST — NB-S002-P009-WP007 LOD400 (L-GATE_SPEC) — team_100 → team_190 — v1

**Date:** 2026-06-03
**From:** team_100 (author engine = Claude Code)
**To:** team_190 (L-GATE_SPEC — cross-engine review of the spec)
**Type:** L-GATE_SPEC — LOD400 review (NOT a deployed result; nothing built yet)
**WP:** NB-S002-P009-WP007 — Full design implementation / precision walk vs Precision Mockup v5
**Spec under review:** `_aos/work_packages/NB-S002-P009-WP007/LOD400_NB-S002-P009-WP007.md`

## Iron Rule #1 (cross-engine)
Author = **Claude Code (team_100)**. Codex unavailable → review by **Cursor/team_190** (Cursor ≠ Claude Code → cross-engine preserved). This is the SPEC gate; the later L-GATE_VALIDATE (post-build) is also cross-engine.

## What to validate (LOD400 quality, not a build)
Confirm the LOD400 is **complete, checkable, and correctly scoped** before it becomes the build contract:
1. **Coverage:** every page-type NOT in the v4 pass is covered — T2 (index + single), T3 single, T4 single, T5 index, world know/code, heritage — each mapped to its theme template + v5 screen. Confirm nothing required is missing.
2. **SSoT correctness:** `Precision Mockup v5.html` is the right canonical authority (per team_35 `DESIGN_COMPLETENESS_REPLY` rows 1–5). Confirm the per-screen ATs (§4 AT-1..AT-7) faithfully reflect the v5 screens (vocabulary/blocks/precision values).
3. **Stale-gap check:** verify the LOD400's claim that **G1 projects archive is already DONE** (repo `cpt-project has_archive='projects'`; `/projects/` 200 on dev+local) — i.e., correctly EXCLUDED from build scope (non-regression only). team_35's reply still flags it as open; confirm it's stale.
4. **a11y non-regression gate (AC-A):** confirm the WP006 baseline (axe 0 / Lighthouse a11y ≥95) is correctly set as a hard gate, and the know/code accent recolor (deep tokens on world-wash) is flagged for AA verification.
5. **Constraints:** module-CSS/template-parts only, no inline, no overrides, `system.css` LOCKED, RTL logical properties, super-locks, G3a lands here (not hot-patch). Confirm these are unambiguous and checkable.
6. **Phasing/routing:** §6 phasing + §8 cross-engine routing + §9 handoff plan are sound.

## team_100 grounding (independent verification welcome)
- v5 per-screen vocabulary extracted from the SSoT; coverage cross-checked vs `TEMPLATE_COVERAGE_AUDIT_2026-06-02` + theme templates.
- Baseline pinned: v0.7.19 @ `161e8078`; local = 100% dev mirror; `validate_aos` 0 FAIL.

## Output
`_COMMUNICATION/team_190/VERDICT_NB-S002-P009-WP007_LOD400_2026-06-03_v1.md` — PASS / PASS_WITH_FINDINGS / FAIL on the spec. On PASS → team_100 issues the implementation-session HANDOFF (§9).

*team_100 → team_190 · L-GATE_SPEC validate request · 2026-06-03 · spec review only · cross-engine (Cursor)*
