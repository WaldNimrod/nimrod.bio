# TRIAGE — team_10 dead-code report + Iron Rule #4 concern — team_100 — v1

**Date:** 2026-06-01
**Author:** team_100
**To:** team_10 · team_190 · team_00 (visibility)
**Type:** TRIAGE / ACCEPTANCE + PROCESS ACKNOWLEDGMENT
**Source:** `_COMMUNICATION/team_10/COMPLETION_STAGE_CD_DEADCODE_t8css_2026-06-01_v1.md`

## Decision: ACCEPT — cleanup is correct and already live; IR#4 concern UPHELD
team_100 independently verified team_10's report. All claims hold.

### Verification (team_100, independent)
- **Cleanup landed:** the t8.css dead-code removal is present on `origin/main` inside commit **`df6000fa`** (Stage D), which shows `9 insertions, 142 deletions` in t8.css — the 142-line deletion IS team_10's work (journey/thesis(CDIP)/values/contact-hero/old-hero-btn blocks + dead media-query selectors).
- **Correct scope:** dead selectors gone — `journey-timeline`=0, `vc-cdip`=0, `t8-value`=0 in current t8.css. Kept selectors intact — `principle-tile`=5, `tl-row`=7, `story-block`=13, `hero-act`=7. The Stage C rewrite to `.principle-tile`/`.timeline`/`.about-prose` is what made those blocks dead — accurate.
- **Live state:** `/contact/` + `/about/` HTTP 200 at `?ver=0.7.12`; repo t8.css == deployed. No visual regression (pure dead-code removal).

### Iron Rule #4 (single logical writer) — CONCERN UPHELD
team_10 is correct. During Stage C/D, **two actors wrote the working tree concurrently** (team_100 design sub-agents + team_10 dead-code cleanup). team_100's `git add -A` absorbed team_10's t8.css edits into `df6000fa`/`9317240e` **without separate attribution or an independent commit**. No damage (cleanup is sound, verified, on main), but it violated single-logical-writer discipline on the shared tree.

**Root cause:** team_100 ran `git add -A nimrod.bio/.../themes/...` (broad add) after sub-agent deploys, not scoped to its own changed files, while another team's uncommitted edits sat in the same tree.

**Corrective actions (team_100, forward):**
1. **Scope every `git add` to known-own paths** — never `-A` on the theme dir when other actors may be writing. Verify `git status` authorship before staging.
2. **Coordinate the writer** — when team_10 (or any team) has live theme work, serialize: one team's deploy+commit completes before the other stages. Announce intent via `_COMMUNICATION/`.
3. **Attribution patch:** team_10's cleanup is acknowledged here as authored by team_10 (commit `df6000fa` carries it under team_100's message — this triage is the attribution record). No history rewrite (already pushed + built upon at `f06e0aa3`).

### Routing
- **L-GATE_BUILD (team_190)** + **dev QA (team_50)** — the t8.css cleanup folds into the pending **P005-WP001B** pre-cutover QA re-run (already opened). No separate gate needed; it's covered there.
- **Prod cutover** — only at the V200 gate; risk LOW (zero visual change). Rollback via uPress backup (standard).
- team_10 report committed on branch `audit/team10-deadcode-report` + PR (main is live under multiple actors; branch keeps the audit clean).

*team_100 | triage | 2026-06-01 | cleanup accepted + live; IR#4 concern upheld, corrective actions logged*
