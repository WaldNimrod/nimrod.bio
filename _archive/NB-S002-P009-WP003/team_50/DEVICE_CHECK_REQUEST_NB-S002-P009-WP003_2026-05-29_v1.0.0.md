---
type: DEVICE_CHECK_REQUEST
from: team_100
to: team_50 (QA · MCP browser)
project: nimrod-bio
wp_id: NB-S002-P009-WP003
date: 2026-05-29
target_version: 0.6.1
---

# DEVICE CHECK REQUEST — NB-S002-P009-WP003 (T7 Home Precision Rebuild)

Theme **0.6.1** live on `http://nimrod-bio-2026.s887.upress.link/`. This WP is a **design-fidelity** rebuild of the home page (T7) + design-language uplift, implementing team_35 package v5. Your check is primarily a **visual fidelity comparison vs the mockup** + responsive/regression.

## Reference (the target)
`sources/team_35_design_package/design_handoff_home/Precision Mockup.html` (serve locally or open) — the home page should match it section-by-section. README + DESIGN_GAP_ANALYSIS in same folder.

## Check — desktop (1440) + mobile (375) + tablet (768)
**Fidelity (vs mockup), all 11 sections top→bottom:** nav (basket logo + world icons + transparency over hero), hero poster (market photo visible, world-color H1 lockup + dots), worlds (3 cards + negentropy backdrop), systems (SFA+tiktrack rows), services carousel, bridges band (dual-color spines), Unless lockup (spark period), projects carousel, manifesto (portrait + watermark), final-CTA (4 path cards), footer (garden icon strip). Flag spacing / decoration-line / color / illustration / media divergences.

**Functional/responsive:**
- Nav `.atop` transparency toggles (transparent over hero → solid after scroll).
- Carousels (services + projects): arrows scroll, scroll-snap works.
- **Zero horizontal scroll @375 + @360** on the home page (G-11 / AT-D13).
- **WP002 mobile NOT regressed (AT-D17):** hamburger drawer opens/closes (toggle/backdrop/ESC), WhatsApp FAB shows (hidden on /contact + while drawer open), footer reflows. Re-confirm on home + one inner page.
- RTL intact at every breakpoint.

## Evidence
Section screenshots desktop + mobile (≥16), drawer open/closed @375, plus a divergence list (element · viewport · what's off vs mockup). Note: page is image-heavy (raw photos) — flag load weight.

## Deliverable
`_COMMUNICATION/team_50/DEVICE_CHECK_NB-S002-P009-WP003_2026-05-29_v1.0.0.md` + screenshots.

*team_100 · 2026-05-29*
