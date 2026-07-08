---
type: VALIDATE_REQUEST
from: team_100
to: team_190 (Independent Validator · Codex)
project: nimrod-bio
wp_id: NB-S002-P009-WP003
gate: L-GATE_VALIDATE
date: 2026-05-29
target_version: 0.6.1
cross_engine_constraint: "BUILD = Claude Code (team_100 orchestrated). VALIDATE MUST be Codex (Iron Rule #1)."
---

# VALIDATE_REQUEST — NB-S002-P009-WP003 (T7 Home Precision Rebuild)

team_100 orchestrated this build (Claude sub-agents) → you (Codex) are the cross-engine validator. team_50 runs parallel MCP visual/device.

## Read first
- LOD400 (AT-D1…D19): `_aos/work_packages/NB-S002-P009-WP003/LOD400_NB-S002-P009-WP003.md`
- Design SSoT: `sources/team_35_design_package/design_handoff_home/` (Precision Mockup.html + README + DESIGN_GAP_ANALYSIS)

## Pre-condition
Dev serves theme **0.6.1** (`?ver=0.6.1`). Confirm before starting.

## Scope — acceptance tests AT-D1…AT-D19 (LOD400 §6)
Key: **AT-D1** world washes render (G-01 fixed — verify `t1.css` `:root` restored + `/world/soil/` not flat); **AT-D5** bridges band present on home; **AT-D6** Unless typographic lockup; **AT-D12** `.img-ph.clean` fallback (no collapsed image-less cards); **AT-D13** no h-scroll @375 (home); **AT-D14** RTL logical props; **AT-D15** nav `.atop`; **AT-D16** carousels; **AT-D17 WP002 mobile NOT regressed**; **AT-D18 system.css LOCK intact (empty diff)**; AT-D19 Lighthouse (advisory).

## Constitutional
- **APPEND-ONLY / additive** for WP002-protected blocks: verify the WP002 `@media` blocks in t7.css + the MOBILE SHELL/BASE/`.wa-fab` in shell.css were NOT modified (`git log`/diff).
- `system.css` LOCK: empty diff (vars live in components.css/shell.css, not system.css).
- `!important`: t7.css=0, shell.css=0, components.css=1 — all spec-justified/commented.
- `validate_aos.sh` (Check 12 = known benign content false-positive).
- Cross-engine: build=Claude, validate=Codex.

## Note on fidelity
This is a precision rebuild; design-fidelity vs the mockup is the substance. team_50's MCP visual check is the primary fidelity evidence — incorporate it. Lighthouse perf is advisory (page is image-heavy; responsive-image optimization is a documented follow-on).

## Deliverable
`_COMMUNICATION/team_190/VERDICT_NB-S002-P009-WP003_VALIDATE_v1.0.0.md` — PASS / PASS_WITH_FINDINGS / FAIL.

*team_100 · 2026-05-29*
