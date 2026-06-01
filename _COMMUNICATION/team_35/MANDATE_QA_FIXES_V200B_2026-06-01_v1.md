# MANDATE — QA V200B fixes (F-002/F-003/F-004) — team_35 — v1

**Date:** 2026-06-01
**Author:** team_100
**To:** team_35 (Site Design + Build)
**Type:** MANDATE (build defects)
**WP:** NB-S002-P005-WP001B (QA) → fixes feed back before L-GATE_VALIDATE
**Source:** `_COMMUNICATION/team_50/QA_REPORT_V200B_2026-06-01_v1.md` + `_COMMUNICATION/team_100/TRIAGE_QA_V200B_FINDINGS_2026-06-01_v1.md`

Three defects to clear before cutover. All on dev `https://nimrod-bio-2026.s887.upress.link` (v0.7.12). Honor both super-locks. Deploy via `docs/UPRESS_FTPS_MANDATORY_PROCEDURE.md`, bump `NB_THEME_VERSION`, keep repo synced (one file per download), verify cache-busted, report to team_100.

## F-003 (S2) — Gallery horizontal overflow ⟵ do first (highest visible impact)
`/project/hagina-shel-nimrod/` + `/project/rest-x-greenhouse/` overflow to scrollWidth ~4294px (desktop) / ~2082px (375). **Root cause:** `t3-gallery.php` renders `wp_get_attachment_image(…, 'large', class 'img-ph g-*')` — the real `<img>` IS the `.img-ph`, but `width:100%` rules only target `.img-ph.clean > img`; no global img reset → tiles at intrinsic 1024px.
**Fix:** ensure gallery images are contained — e.g. `t3.css`: `.img-ph > img, img.img-ph { max-inline-size:100%; block-size:auto; }` and/or a safe global `img { max-width:100%; height:auto; }` reset in system.css (check it doesn't break fixed-size logos/emblems). Verify scrollWidth == viewport on both project pages at 1440 + 375.

## F-002 (S2) — `/services/` 404
Home "כל השירותים" → `/services/` 404s. Build a services archive/landing (lists the 7 `service` CPT posts per the design system) OR repoint the link to an existing destination. Archive is the correct fix (also completes the missing-template audit). Verify HTTP 200.

## F-004 (S2) — Remove public email (team_00 decision 2026-06-01)
`mailto:nimrod@nimrod.bio` renders on /contact/ ("אימייל ישיר" card) AND in the sitewide footer. **team_00 ruling: REMOVE from both** (address is not a live mailbox). Keep WhatsApp + the contact form as the paths. Verify: rendered /contact/ + footer (any page) show **no** `mailto:`/`@nimrod.bio`/`@mezoo`. (Dedicated mailbox provisioning stays deferred.)

## Also (small) — BCS gallery section
Per `REQUEST_BCS_GALLERY_SECTION_2026-06-01_v1.md`: register `_nb_gallery` on the `service` CPT + add a gallery part to `single-service.php` so BCS tool/field photos can be wired (hero already live). Lower priority than F-002/F-003/F-004.

## On completion
Report `_COMMUNICATION/team_35/COMPLETION_QA_FIXES_V200B_…md` with per-fix before/after + deploy/version + lock-scan. team_100 re-verifies, then runs Lighthouse + routes to team_190 L-GATE_VALIDATE.

*team_100 | mandate | 2026-06-01 | clear F-002/F-003/F-004 + BCS gallery section before cutover gate*
