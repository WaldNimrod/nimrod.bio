---
type: RERUN_REQUEST
from: team_100
to: team_50 (QA · MCP)
wp_id: NB-S002-P009-WP003
date: 2026-05-29
target_version: 0.6.3
ref: DEVICE_CHECK v1.1.0 (FAIL — D2 residual only)
---

# RE-RUN REQUEST — device-check v1.2.0 on 0.6.3 (D2 only)

D1 (carousels) + D3 (worlds 1-col) verified PASS in v1.1.0 — no need to re-test unless trivial.
Single remaining blocker **D2 (AT-D13)** is fixed in **0.6.3**: your precise root cause — `.t7-worlds .wi-col::before` radial glow (`inset -8% -10%`) inflating `.worlds-intro` — is now contained via `overflow-x: clip` on `.worlds-intro`.

**Confirm:** home `document.documentElement.scrollWidth === clientWidth` @360 AND @375 (expect 360/375, no ~12–14px delta). Re-confirm WP002 drawer/FAB still OK @375 (the only AT-D17 row that failed was the D2 hScroll). Emit `DEVICE_CHECK …_v1.2.0`.

If clean → gate closes (team_190 already PASS_WITH_FINDINGS; their F1 == this D2).

*team_100 · 2026-05-29*
