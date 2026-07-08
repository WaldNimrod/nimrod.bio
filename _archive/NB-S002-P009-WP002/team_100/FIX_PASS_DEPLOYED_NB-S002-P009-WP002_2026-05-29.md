# FIX PASS DEPLOYED — NB-S002-P009-WP002 — team_100

**Date:** 2026-05-29 · **To:** team_50 + team_190 · **Version:** dev now **0.5.1** (was 0.5.0)

Triage of team_50 DEVICE_CHECK v1.0.0 (FAIL) → 2 fixes deployed, 3 dispositions:

| team_50 defect | Disposition |
|---|---|
| **D2 — Contact h-scroll (~10k)** | **FIXED.** Root cause = pre-existing honeypot `.hp-field { left:-9999px }` (P003-WP005, commit 4423d3eb) inflating `scrollWidth`. Replaced with scroll-safe visually-hidden (`clip`/`clip-path`, `left:auto`) — append-only override in t8.css. Honeypot still functions. |
| **D3 — topic-chip 39px** | **FIXED.** `min-height: 36px → 44px` @≤760px (M16/§1.3 wins over spec §9's 36px). |
| **D1 — Post h-scroll (1044/1055)** | **NOT a code defect — harness mis-size.** `audit-results.json` shows `innerWidth:1044` (post) / `1440` (contact @"360") — the viewport never resized to 360; overflowEls = the desktop shell-nav at that width; at a correctly-sized 768 `overflowEls` is empty. **team_50: please re-run Post @ true 360/414** (force viewport) to confirm. |
| **D4 — drawer-close 36×36** | Accepted spec §2.1 locked exception (toggle/links/FAB all ≥44). |
| **D5 — desktop nav <44 @768** | Pre-existing desktop nav at tablet width (mobile drawer only ≤640). Out of WP002 scope; LOW. Candidate V300/GCR. |

**Live evidence (0.5.1):** contact page → `t8.css?ver=0.5.1`; served t8.css contains MOBILE FIX PASS block (`left:auto`, `min-height:44px`). 6/8 templates were already clean on M15.

**Requests:**
- **team_50:** re-run device check on **0.5.1** with correct 360/414 viewport sizing; confirm contact `scrollWidth ≈ viewport` and chips ≥44px. Re-issue DEVICE_CHECK v1.1.0.
- **team_190:** your runtime replay should target **0.5.1** (M15/M16 now addressed). Reflect D1 as harness artifact + D2/D3 fixed in your VERDICT v1.1.0.

*team_100 · 2026-05-29*
