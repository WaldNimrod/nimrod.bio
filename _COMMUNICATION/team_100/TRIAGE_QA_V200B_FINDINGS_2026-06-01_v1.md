# TRIAGE — QA V200B findings → routing — team_100 — v1

**Date:** 2026-06-01
**Author:** team_100
**To:** team_35 (build) · team_00 (decision on F-004) · team_190 (gate awareness)
**Type:** TRIAGE / ROUTING
**Source:** `_COMMUNICATION/team_50/QA_REPORT_V200B_2026-06-01_v1.md` (verdict: PASS_WITH_FINDINGS, no STOP)
**WP:** NB-S002-P005-WP001B

## Decision: ACCEPT PASS_WITH_FINDINGS. NOT yet cutover-ready — 3 findings to clear first.
team_100 independently re-confirmed all three. Locks 0, all pages 200, anon TBD 0, system templates + media + counts + external links all PASS. Two NEW S2 defects block a clean cutover; one known S2 carries over.

## Findings → routing

### F-002 (S2, OPEN, known) — `/services/` returns 404 → team_35
Home "כל השירותים" links to `/services/` which 404s. Already in `REQUEST_BCS_GALLERY_SECTION`/mandate scope. Build the services archive/landing, or repoint the link. **Owner: team_35.**

### F-003 (S2, NEW, confirmed) — gallery horizontal overflow → team_35
`/project/hagina-shel-nimrod/` + `/project/rest-x-greenhouse/` blow out to scrollWidth ~4294px (desktop) / ~2082px (375). **Root cause (confirmed in source):** `t3-gallery.php` renders `wp_get_attachment_image(…, 'large', class 'img-ph g-*')` — the real `<img>` IS the `.img-ph`, but the CSS `width:100%` rules only target `.img-ph.clean > img` (clean placeholders) and there is no global `img{max-width:100%}` reset → tiles render at intrinsic 1024px. **Surfaced by team_100's WP005 gallery wiring** (data was correct; latent containment bug exposed once real imgs populated). **Fix (team_35):** add `max-inline-size:100%` to gallery `.img-ph`/`.img-ph > img` (or a global `img{max-width:100%;height:auto}` reset) in `t3.css`/`system.css`; deploy via FTPS; re-verify scrollWidth==viewport. **Owner: team_35.**

### F-004 (S2, NEW, confirmed) — public email contradicts owner decision → team_00 / team_35
`mailto:nimrod@nimrod.bio` renders on **/contact/** (אימייל ישיר card) AND in the **sitewide footer** (every page). This contradicts the prior team_00 decision (TRIAGE_QA_V200_FINDINGS F-001: "remove displayed email" — address is not a provisioned mailbox → would bounce). Earlier removal didn't cover the contact card + footer. **team_00 DECISION (2026-06-01): REMOVE from both** (per prior ruling — address not a live mailbox). **team_35** removes the `mailto:nimrod@nimrod.bio` from the /contact/ "אימייל ישיר" card AND the sitewide footer; WhatsApp + form remain the contact paths. Dedicated mailbox provisioning stays deferred (no date). **Owner: team_35 build.**

## INFO (expected, not failures)
- 5 owner photo gaps + hero-code remain `.ph.clean` — correct.
- Contact form server contract PASS (nonce enforced; valid→ok, bad→error); inbox delivery to nimrod@mezoo.co = owner-verify.
- Lighthouse NOT run (no CLI on host) → manual follow-up before cutover.

## Gate impact
P005-WP001B stays IN_PROGRESS until F-002/F-003/F-004 cleared + Lighthouse run. Then → team_190 constitutional L-GATE_VALIDATE → P005-WP002 cutover. No STOP; risk contained to two layout/policy fixes + one decision.

*team_100 | triage | 2026-06-01 | QA V200B accepted; F-002/F-003/F-004 → team_35 (+F-004 owner decision); Lighthouse pending*
