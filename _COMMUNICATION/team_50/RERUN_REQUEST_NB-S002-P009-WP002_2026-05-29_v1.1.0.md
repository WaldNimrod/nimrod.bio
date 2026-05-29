---
type: RERUN_REQUEST
from: team_100
to: team_50 (QA · MCP)
wp_id: NB-S002-P009-WP002
date: 2026-05-29
target_version: 0.5.1
ref: DEVICE_CHECK v1.0.0 (FAIL) + FIX_PASS_DEPLOYED 2026-05-29
---

# RE-RUN REQUEST — device-check v1.1.0 on 0.5.1

Dev is now **theme 0.5.1** (fix pass deployed + verified live). Please re-run the device-check and re-issue `DEVICE_CHECK …_v1.1.0`. Targeted confirmations on the prior FAIL items:

| Prior defect | What to confirm on 0.5.1 |
|---|---|
| **D1 — Post h-scroll** | **Force the viewport to a true 360 / 414** (your v1.0.0 run had `innerWidth:1044/1440` — the window never resized; `overflowEls` was empty at correct 768). Re-measure `documentElement.scrollWidth` vs `clientWidth` at genuine 360 px. Expect ≈ viewport (no h-scroll). |
| **D2 — Contact h-scroll (~10k)** | Confirm `scrollWidth ≈ viewport` now. Root cause was the honeypot `.hp-field` (`left:-9999px`) → replaced with scroll-safe `clip`/`clip-path` (`left:auto`). |
| **D3 — topic-chip 39px** | Confirm `.topic-chip` computed height ≥ 44 px @ ≤640. |
| D4 drawer-close 36×36 | No change — accepted spec §2.1 exception (don't fail). |
| D5 desktop nav <44 @768 | Out of WP002 scope (desktop nav at tablet, mobile drawer only ≤640) — note only, don't fail. |

Re-confirm the already-PASS items hold (drawer 3-close-paths + focus trap, FAB on/off contact + hide-on-drawer, footer 1/2-col, inputs ≥16px, RTL). Capture before/after contact + post screenshots @360.

Cache note: if your client shows 0.5.0/0.4.1, hard-refresh or append `?nocache=1` — server confirmed serving 0.5.1.

*team_100 · 2026-05-29*
