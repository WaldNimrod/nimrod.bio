# ARCHIVE MANIFEST — NB-S002-P009-WP004

**WP:** NB-S002-P009-WP004 — Home asset finalization (team_35 v6/v7) + image optimization + precision round 2
**Milestone:** V200 · Program P009 · **Closed:** 2026-05-30 (LOD500)
**Closure:** team_00 (Principal) acceptance — "can close this round." Formal cross-engine L-GATE_VALIDATE deferred to pre-cutover QA (changes are CSS/asset polish on validated WP003).
**Build:** team_100 orchestrated (Claude sub-agents). Theme **0.7.3** live on dev.

## What shipped
- **v6 assets integrated:** watercolor washes (section backgrounds, responsive), favicon (svg/32/48/180), OG image (Yoast filter + fallback), logo SVG (basket data-URI fix) + footer logo.
- **Image optimization (the headline):** WebP + downscale of studio materials — WebP browsers load **~1.0 MB vs ~7.7 MB (~87% lighter)**; `<picture>` WebP+JPEG fallback (7 imgs) + wash `image-set()` WebP-first.
- **Dummy SFA/tiktrack demo app-screens** (SVG, marked DEMO) — placeholder until real captures.
- **v7 precision:** client-tightened section rhythm `--sec-y: clamp(38px,4.6vw,52px)` (~50px, was ~112px) applied uniformly site-wide per HOME_DELTA + SPACING_CHANGELOG; fonts verified loading (G1).

## Key sources
- team_35 v6: `sources/team_35_design_package/design_handoff_home/assets/ASSETS_README.md`
- team_35 v7: `sources/team_35_design_package/design_handoff/{HOME_DELTA,SPACING_CHANGELOG,TEMPLATES}.md` + Precision Mockup.html
- COMPLETION_CANONICAL: `_COMMUNICATION/team_100/COMPLETION_CANONICAL_NB-S002-P009-WP004_v1.0.0.md`

## Commit range
v0.7.0 (assets) → 0.7.1 (WebP) → 0.7.2 (demo screens) → 0.7.3 (v7 spacing) — 6eaef9be … 8b0f5a79

## Carry-forward (pre-cutover)
- Real SFA/tiktrack screenshots (team_35) → swap dummies.
- Lighthouse + visual QA pass (validation deferred this round).
- system.css body line-height 1.65 vs spec 1.55 (LOCKED — GCR if pursued).
- **NEXT PHASE (team_00): content precision.**

*Archived by team_100 · 2026-05-30*
