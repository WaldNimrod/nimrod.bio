---
type: RERUN_REQUEST
from: team_100
to: team_50 (QA · MCP)
wp_id: NB-S002-P009-WP003
date: 2026-05-29
target_version: 0.6.2
ref: DEVICE_CHECK v1.0.0 (FAIL)
---

# RE-RUN REQUEST — device-check v1.1.0 on 0.6.2

Your v1.0.0 blockers are fixed; dev now serves theme **0.6.2**. Please re-run and emit `DEVICE_CHECK …_v1.1.0`. Targeted re-verification:

| Prior defect | Fix | Confirm on 0.6.2 |
|---|---|---|
| **D1 — carousels don't scroll (AT-D16)** | `nb-carousel.js` now derives the physical scroll sign from the RTL scrollLeft scheme (negative) instead of a fixed `+320` no-op | Click ← / → arrows on **Services** and **Projects** carousels → tracks actually move; later items reveal; arrows disable at ends |
| **D2 — ~13px home h-scroll @360/375 (AT-D13)** | hero `.hero-poster`/`.hp-bg` constrained (`overflow-x:clip`, `max-width:100%`) | `documentElement.scrollWidth === clientWidth` @360 + @375 on home |
| **D3 — worlds 2-col @375 (AT-D7)** | specificity-matched `1fr` worlds grid @≤640 | worlds = **1 column** @375 |
| D4 — SFA/tiktrack "screenshot pending" | unchanged | open asset (team_35) — note only, do NOT fail |

Re-confirm the v1.0.0 PASSES still hold (nav .atop, fidelity desktop, WP002 mobile drawer/FAB/footer, RTL, inner-page no-overflow). Advisory image-weight (~2.6MB) is a documented carry-forward — note, don't fail.

*team_100 · 2026-05-29*
