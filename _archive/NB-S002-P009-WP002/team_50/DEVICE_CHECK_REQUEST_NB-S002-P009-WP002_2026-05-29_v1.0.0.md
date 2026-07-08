---
type: DEVICE_CHECK_REQUEST
from: team_100 (Chief Architect / build orchestrator)
to: team_50 (QA · MCP real-device / browser)
project: nimrod-bio
wp_id: NB-S002-P009-WP002
date: 2026-05-29
version: v1.0.0
---

# DEVICE CHECK REQUEST — NB-S002-P009-WP002 (Mobile)

## Pre-condition
Run AFTER deploy confirmed (theme v0.5.0 live on `http://nimrod-bio-2026.s887.upress.link`). HTTP (dev cert expired — use http, or `curl -k` for https). Runs in parallel with team_190 (Codex) code/structure validation.

## Task — MCP browser, real viewport emulation
Test at **360px, 414px, 768px** across all 7 templates: Home (`/`), a World (`/world/soil/`), a Service, a Project, a Post, Blog (`/blog/`), About (`/about/`), Contact (`/contact/`).

### Interaction checks
1. **Drawer:** hamburger visible ≤640px, desktop nav hidden; tap opens drawer from inline-end (RTL = right); close via X / backdrop / ESC; body scroll locked while open; focus moves into drawer on open, back to toggle on close.
2. **WhatsApp FAB:** visible bottom-inline-end on every page EXCEPT `/contact/`; hidden while drawer open.
3. **No horizontal scroll** at 360px on every template (the key carry-over probe deferred from P003/P005 — verify anonymously, not in admin-bar context).
4. **Touch targets** ≥44×44 on nav/CTAs/chips.
5. **Contact form** inputs do not trigger iOS zoom (font-size ≥16px).
6. **RTL** intact at every breakpoint.

### Evidence
Screenshots: drawer open/closed @375px, FAB on a service + absent on /contact, footer 1-col@375 / 2-col@768, each template @360 + @768 (≥14 shots). Note any overflow/clipping with the element + viewport.

## Deliverable
`_COMMUNICATION/team_50/DEVICE_CHECK_NB-S002-P009-WP002_2026-05-29_v1.0.0.md` + screenshots → feeds team_190 verdict + team_100 gate.

*Issued by team_100 · 2026-05-29*
