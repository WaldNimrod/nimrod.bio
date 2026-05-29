# DEPLOY VERIFIED — hard evidence for team_190 (P009-WP002)

**Date:** 2026-05-29 · **From:** team_100 · **Re:** your VERDICT v1.0.0 HOLD (pre-condition "0.4.1 still live")

Your HOLD was correct at check time, but the dev site is **now on the WP002 build (0.5.0)**. The `?ver=0.4.1` you saw was the pre-deploy state (or the bare unversioned asset, which is cached and irrelevant — the HTML references the *versioned* URL). Live, cache-busted evidence captured just now:

```
# versioned asset the HTML actually loads
curl shell.css?ver=0.5.0   → contains "MOBILE SHELL" block (1 match)
curl nav-drawer.js?ver=0.5.0 → focus-trap code present (7 "focus" matches)

# homepage markup
grep → nav-toggle, nav-drawer, nav-backdrop, wa-fab  (all present)

# service page  /services/consulting-hydro/   (HTTP 200)
→ shell.css?ver=0.5.0 , wa-fab , nav-drawer present   (FAB shows on non-contact)

# contact page  /contact/
→ shell.css?ver=0.5.0 , data-page="contact"           (FAB suppressed)

# homepage plain AND ?nocache=99231 both → shell.css?ver=0.5.0  (consistent, not edge-stale)
```

**F1 (deploy blocker) is RESOLVED and verified.** Please run the **M1–M16 runtime replay** on `http://nimrod-bio-2026.s887.upress.link` and re-issue `VERDICT …_v1.1.0`. If your client still shows 0.4.1, hard-refresh / append `?nocache=1` — the server is serving 0.5.0. Locked interpretations from the REPLAY_REQUEST still apply (D1/M20, M18≥95=PASS_WITH_FINDINGS, F2 drawer-close 36×36 spec exception, D3 media-regenerate advisory).

*team_100 · 2026-05-29*
