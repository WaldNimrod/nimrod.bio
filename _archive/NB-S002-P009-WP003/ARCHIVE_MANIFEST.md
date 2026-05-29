# ARCHIVE MANIFEST — NB-S002-P009-WP003

**WP:** NB-S002-P009-WP003 — T7 Home Precision Rebuild + Design-Language Uplift (impl of team_35 package v5)
**Milestone:** V200 (pre-cutover) · Program P009
**Closed:** 2026-05-30 · **Terminal state:** LOD500_LOCKED
**Closure owner:** team_100 (ADR042)
**Gate:** L-GATE_VALIDATE = PASS_WITH_FINDINGS (team_190 Codex VERDICT v1.0.0 + team_50 MCP DEVICE_CHECK v1.2.0 PASS)
**Cross-engine:** build = Claude Code (team_100 orchestrated sub-agents) · validate = Codex (team_190) + MCP (team_50) — Iron Rule #1 preserved

## What shipped (theme nimrod-bio-2026 @ v0.6.3, live on dev)
- **G-01 fix:** `t1.css` `:root` wash tokens restored → world-page strata gradients render (root cause of "sketch" look).
- **T7 home full precision rebuild** (`front-page.php` + `t7.css` + new `components.css`): hero poster (market photo), worlds + negentropy concept SVG, systems (SFA/tiktrack rows), services carousel, bridges band, Unless lockup, projects carousel, manifesto, final-CTA, footer garden-icon strip.
- **Design language:** heritage basket logo (nav, ink/paper state-flip) + per-world nav icons + `.atop` transparency; basket emblems; IconPark `<symbol>` sprite; world/bridge/spark/negentropy SVGs; components (type tokens, chips, stamps, `.img-ph` fallback).
- **JS:** `nb-nav-atop.js` (hero-aware nav), `nb-carousel.js` (RTL-correct scroll-snap arrows).
- 2 precision passes (spacing/lines/colors/illustrations/media reconciled to mockup) + hygiene (0 `!important` in t7/shell, no `#fff` bg, RTL logical).

## Validation history
- team_190 VERDICT v1.0.0 = PASS_WITH_FINDINGS (F1=home h-scroll → fixed).
- team_50 DEVICE_CHECK v1.0.0 FAIL → v1.1.0 FAIL (D2 residual) → **v1.2.0 PASS** (0.6.3). D1 carousel RTL + D2 hero/worlds overflow + D3 worlds 1-col all fixed.

## Key artifacts
- LOD400: `_aos/work_packages/NB-S002-P009-WP003/LOD400_NB-S002-P009-WP003.md`
- Design SSoT: `sources/team_35_design_package/design_handoff_home/` (Precision Mockup.html + README + DESIGN_GAP_ANALYSIS)
- VERDICT: `_COMMUNICATION/team_190/VERDICT_NB-S002-P009-WP003_VALIDATE_v1.0.0.md`
- DEVICE_CHECK: `_COMMUNICATION/team_50/DEVICE_CHECK_NB-S002-P009-WP003_2026-05-30_v1.2.0.md`
- CANONICAL CLOSURE: `_COMMUNICATION/team_100/COMPLETION_CANONICAL_NB-S002-P009-WP003_v1.0.0.md`

## Commit range
G-01 5fa7da6a → … → D2 fix 2bf47ba4 → close (this commit)

## V300 / follow-on carry-forward
- **Open assets pending team_35:** logo SVG master, favicon, OG image, watercolor washes ×5, real SFA/tiktrack screenshots (placeholders live now; hot-swap on delivery).
- **Image-weight optimization:** ~2 MB home load — responsive `sizes` + `wp media regenerate` + compression before cutover.
- Broader design-language propagation to T2–T8 (deferred per team_00 D-1).
- F2 (hero h1 `.poster-h1` vs `.t-display`, tracking equiv present).

*Archived by team_100 · 2026-05-30 · ADR042 · cross-engine validated*
