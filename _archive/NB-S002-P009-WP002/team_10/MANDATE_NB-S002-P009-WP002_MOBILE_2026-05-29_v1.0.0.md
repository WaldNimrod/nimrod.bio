---
type: MANDATE
from: team_100 (Chief Architect / Orchestrator) on behalf of team_00 (Principal)
to: team_10 (Builder · Cursor)
project: nimrod-bio
milestone: V200 (pre-cutover)
wp_id: NB-S002-P009-WP002
label: "nimrod-bio — Mobile Responsiveness (T-02 implementation)"
date: 2026-05-29
version: v1.0.0
status: OPEN — PHASED AUTHORIZATION
track: A · STANDARD
spec_ref: _aos/work_packages/NB-S002-P009-WP002/LOD400_NB-S002-P009-WP002.md
design_input: sources/team_35_design_package/_handoff/04-MOBILE-spec.md (T-02 · closed 2026-05-25 · on disk, gitignored)
predecessor: NB-S002-P009-WP001 (team_35 · IN PROGRESS — see §3 phased gate)
authorization: team_00 directive 2026-05-29 ("proceed as far as possible with info in hand until team_35 delivers")
governance_depth: full
---

# MANDATE — NB-S002-P009-WP002 · Mobile Responsiveness (Phased)

**Date:** 2026-05-29
**Author:** team_100
**WP:** NB-S002-P009-WP002
**Type:** MANDATE (phased authorization)

## §1 Context

The mobile-responsiveness spec from team_35 (`04-MOBILE-spec.md`, T-02, closed 2026-05-25) is **delivered and on disk** at `sources/team_35_design_package/_handoff/04-MOBILE-spec.md` (29KB, gitignored — read locally). The full implementation spec is the LOD400 at `_aos/work_packages/NB-S002-P009-WP002/LOD400_NB-S002-P009-WP002.md` — **read it in full; it is the SSoT for this build.** This MANDATE does not restate it; it defines *what you may start now* vs *what is held*.

team_35's **WP001 (UI Precision Pass + final visual assets)** is still in progress. Its precision pass will refine desktop values inside the per-template CSS files (`t1.css`–`t8.css`, parts of `shell.css`). Building mobile `@media` overrides on top of those files *before* WP001 lands risks rework.

**team_00 directive (2026-05-29):** the production cutover window stays unset until full completion (design + content + all bugs/gaps); meanwhile, *advance as far as possible with the information already in hand.* This MANDATE implements that: start the net-new, structurally-independent work now; hold the value-tuning that cascades from WP001's desktop deltas.

## §2 PHASE A — AUTHORIZED NOW (GO)

All deliverables here are **net-new / additive** — new files, new elements, new selectors, new function registrations. They do not modify the desktop CSS values WP001 will refine, so there is no rework collision. This is LOD400 **Phase 1 (Shell + Base)** plus the responsive-image *infrastructure* (registration + PHP plumbing only).

| # | Deliverable | LOD400 ref |
|---|---|---|
| A1 | Breakpoint custom properties (`--bp-mobile:640px; --bp-tablet:900px; --bp-desktop:1100px`) in `base.css`/`system.css`; safe-area-inset utilities | §3, §4a, §5 Phase 1.1 |
| A2 | `assets/js/nav-drawer.js` (~80 lines: open/close, ESC, focus trap, focus management, backdrop click, body-scroll lock; progressive-enhancement + `<noscript>` fallback) | §4c |
| A3 | `header.php` — `.nav-toggle` (44×44, `aria-expanded`), `.nav-drawer` + `.nav-backdrop`; confirm `dir="rtl"` on `<html>` | §4b |
| A4 | `footer.php` — `.wa-fab` anchor → `https://wa.me/972547776770` (locked T-09) | §4b |
| A5 | `data-page="<?php echo get_post_field('post_name', get_the_ID()); ?>"` on `<body>` (enables WA-FAB suppression on /contact) | §4b |
| A6 | Drawer + WA-FAB **component CSS** (new selectors; `shell.css` and/or new `wa-fab.css`) — these are net-new components, not desktop-value edits | §4a, §4c |
| A7 | Footer mobile reflow `@media` (4→2→1 col) | §4a `shell.css` |
| A8 | `functions.php` — enqueue `nb-nav-drawer` (defer, in_footer); `add_image_size('nb-hero-mobile',800,9999,false)` + `('nb-hero-tablet',1280,9999,false)`; add `&display=swap&subset=hebrew,latin` to Google Fonts URL | §4a, §4c, §4d, §4e |
| A9 | Responsive-image PHP: upgrade hero `get_the_post_thumbnail()` calls (T2/T3 hero, T1 anchor) with `sizes` + explicit `width`/`height` + `loading`/`fetchpriority` per LOD400 §4d | §4d |
| A10 | `header.php` — `<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>` | §4e |

**RTL discipline applies to ALL new CSS (A6/A7):** logical properties only — `inset-inline-*`, `margin-inline-*`, `padding-inline-*`, `border-inline-*`. Exception: `linear-gradient` direction via `--bg-stripe`. **`!important` cap: max 2 site-wide, each commented `/* mobile override — intentional */`.**

**Spec resolution (LOD400 §4e preload note):** §4e mentions a font `preload` but the code sample shows only `preconnect`. **Decision:** ship `preconnect` now (A10). Defer any `<link rel="preload">` of specific font files to Phase B perf work (Phase 5), where the final font weights/URLs are settled. Do not add a speculative preload now.

**Phase A acceptance subset (run these now):** M1, M2, M3, M4, M5, M6, M16 (touch targets on shell + FAB), M20 (`!important` audit). Deploy to dev via `python3 scripts/upress_ftps_upload.py` after build and spot-check at 375px + 768px. (FTP IP must be re-allowlisted in the uPress panel first — operational step, team_00.)

## §3 PHASE B — HELD (do NOT start until gate below clears)

| # | Deliverable | LOD400 ref |
|---|---|---|
| B1 | `t7.css` mobile `@media` (home reflow) | §5 Phase 2 |
| B2 | `t1.css` mobile `@media` (lattice/echoes/bridges/projects/posts) | §5 Phase 2 |
| B3 | `t2.css` + `t3.css` mobile `@media` | §5 Phase 3 |
| B4 | `t4.css` + `t5.css` + `t8.css` mobile `@media` | §5 Phase 4 |
| B5 | Phase 5 — Lighthouse mobile, VoiceOver/focus-trap on-device, horizontal-scroll + touch-target full audit | §5 Phase 5 |

**Phase B start gate (ALL must be true):**
1. team_35 **NB-S002-P009-WP001 ≥ L-GATE_BUILD PASS** (COMPLETION artifact filed; team_100 gate-signs)
2. team_100 issues `MANDATE …_v1.1.0` (Phase B release) confirming final desktop CSS values are frozen

Building B1–B5 before this gate is **out of authorization** — the per-template `@media` overrides cascade from desktop values WP001 will change.

## §4 Locked decisions (carry into Phase B validation)

- **M18 (Lighthouse mobile A11y = 100):** team_00 **accepted** (2026-05-29) that a score **≥ 95 = PASS_WITH_FINDINGS**, consistent with the existing V300 a11y-uplift carry-forward (P005-WP001 baseline measured 88–94). Do not block the WP on A11y=100; report actual scores.
- **M17/M19 (Performance ≥ 90):** real LCP/total-weight depend on final visual assets from WP001 (watercolors, logo). Measure in Phase B *after* WP001 assets are loaded; flag any miss with the asset-weight breakdown.
- Breakpoints (640/900/1100) are **LOCKED** by team_35 — no changes without written team_35 approval.

## §5 Reporting

On Phase A completion, file `_COMMUNICATION/team_10/COMPLETION_NB-S002-P009-WP002_PHASE-A_<date>_v1.0.0.md` with: screenshots (shell 375px + 768px, drawer open/closed, WA-FAB on a service page + absent on /contact, footer 1-col/2-col), Phase A acceptance results (M1–M6, M16-shell, M20), commit hash, deviations. Then **stop and wait** for the Phase B release MANDATE — do not proceed into B.

**Cross-engine integrity (Iron Rule #1):** builder = team_10 (Cursor); validator = team_190 (Codex) + team_50 (MCP real-device). Maintain separation.

---

*Issued by team_100 · 2026-05-29 · phased authorization under team_00 directive · spoke session (nimrod-bio)*
