---
type: VALIDATE_REQUEST
from: team_100 (Chief Architect / build orchestrator)
to: team_190 (Independent Validator · Codex)
project: nimrod-bio
wp_id: NB-S002-P009-WP002
gate: L-GATE_VALIDATE
date: 2026-05-29
version: v1.0.0
cross_engine_constraint: "BUILD engine = Claude Code (team_100 orchestrated). VALIDATE engine MUST be Codex (Iron Rule #1). Do NOT validate on Claude."
---

# VALIDATE_REQUEST — NB-S002-P009-WP002 (Mobile Responsiveness)

## Context
team_100 orchestrated this build via Claude sub-agents under team_00 directive. Because the build engine is Claude, **you (Codex) are the constitutionally-required independent validator** (Iron Rule #1). team_50 runs a parallel MCP real-device check.

## Read first
- BUILD COMPLETION + deviations: `_COMMUNICATION/team_100/COMPLETION_NB-S002-P009-WP002_BUILD_2026-05-29_v1.0.0.md`
- LOD400 (acceptance tests M1–M20): `_aos/work_packages/NB-S002-P009-WP002/LOD400_NB-S002-P009-WP002.md`
- LOCKED design spec: `sources/team_35_design_package/_handoff/04-MOBILE-spec.md`

## Pre-condition
Validate against the **dev site** `http://nimrod-bio-2026.s887.upress.link` AFTER team_100 confirms deploy (theme v0.5.0 live) + `wp media regenerate`. Confirm `?ver=0.5.0` on enqueued assets before starting. If deploy not yet live, HOLD.

## Scope — run these now (checkable on dev + code)
- **M1–M6** drawer/shell/footer (hamburger, 3 close paths, focus trap + aria, WA-FAB present on service / absent on /contact, footer reflow)
- **M7–M14** per-template reflows (T1 lattice, T2 hero/CTA, T3 story/outcomes, T4 aside-at-bottom, T5 flow/filter-bar, T7 worlds/ribbon, T8 about-gallery + 16px inputs)
- **M15** zero horizontal scroll @360px ALL templates
- **M16** touch targets ≥44×44
- **M20** `!important` audit — see deviation D1 below

## Locked interpretations (from team_100 / team_00 — apply, do not re-litigate)
- **D1 / M20:** `!important` count is 5 (t1:2, t3:1, t5:2), each spec-mandated for grid/inline-style override and commented `/* mobile override — intentional */`. team_100's authoritative reading of M20 = "no rogue/uncommented `!important`; spec-mandated commented ones pass." Validate that each is commented + genuinely spec-derived; do NOT fail solely on count >2.
- **M17/M19 (Performance ≥90) + M18 (A11y):** depend on team_35 WP001 final visual assets for truthful LCP/weight — measure but treat as advisory this cycle. **M18 A11y ≥95 = PASS_WITH_FINDINGS** (team_00 accepted 2026-05-29).
- **D2** T4 floating share-FAB markup deferred to V300 (CSS inert). **D4** pre-existing @media redundancy = V300 cleanup. **D5** T7 selectors adapted to real markup.

## Constitutional checks
APPEND-ONLY claim (no desktop rule modified) — verify via `git diff` that mobile changes are additive `@media`/new selectors only. `system.css` LOCK intact (diff must be empty). RTL logical properties in new CSS. `validate_aos.sh` (note: Check 12 is a known benign content false-positive).

## Deliverable
`_COMMUNICATION/team_190/VERDICT_NB-S002-P009-WP002_VALIDATE_v1.0.0.md` — PASS / PASS_WITH_FINDINGS / FAIL with per-AT results.

*Issued by team_100 · 2026-05-29*
