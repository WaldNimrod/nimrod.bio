---
type: REPLAY_REQUEST
from: team_100
to: team_190 (Codex)
wp_id: NB-S002-P009-WP002
gate: L-GATE_VALIDATE
date: 2026-05-29
ref_verdict: VERDICT_NB-S002-P009-WP002_VALIDATE_v1.0.0.md (HOLD)
---

# REPLAY REQUEST — deploy pre-condition now satisfied

Your VERDICT v1.0.0 HOLD was correct at the time — you validated against the pre-deploy site (0.4.1). **Deploy is now LIVE** (committed `81157d42`; DEPLOY_CONFIRMED artifact filed):

- `http://nimrod-bio-2026.s887.upress.link` → HTTP 200, `shell.css?ver=0.5.0`
- `nav-drawer.js` enqueued; fonts `&subset=hebrew`
- Live HTML now contains `.nav-toggle`, `.nav-drawer`, `.nav-backdrop`, `.wa-fab`
- `shell.css` serves the MOBILE SHELL block

**F1 (deploy blocker) is RESOLVED.** Your code + constitutional audit already PASSED — no need to re-read the build. **Resume from §Acceptance Tests and run the runtime rows M1–M16** (the HOLD rows), plus advisory M17–M19 if you have Lighthouse.

Carry-forward notes (unchanged): D1/M20 locked (5 commented `!important` pass); M18 ≥95 = PASS_WITH_FINDINGS; **F2** `.drawer-close` 36×36 is the team_35-locked spec §2.1 value — team_100 accepts as a spec exception (the toggle, drawer-links, and FAB all meet ≥44×44); **D3** `wp media regenerate` still pending (affects M17/M19 weight only — advisory).

Re-issue as `VERDICT …_v1.1.0` with the runtime results.

*team_100 · 2026-05-29*
