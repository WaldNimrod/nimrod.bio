# MANDATE — Full-System QA + Visual Inspection — team_50 — v1

**Date:** 2026-06-01
**Author:** team_100
**To:** team_50 (QA / Validation)
**Type:** MANDATE
**WP:** — (V200 site rebuild, non-WP delivery)
**Engine constraint (Iron Rule #1):** team_50 ≠ the builders (sub-agents / team_35). Independent validator. ✅

## Why
SITE_DELIVERY_PACKAGE_2026-05-31_v3 is fully implemented on the **dev** environment and was independently text-verified by team_100 (12 pages, all locked terms = 0). Before any production cutover, the site needs a **functional + visual QA pass with screenshot evidence** across the whole system.

## Scope — environment
- Target: **dev** `https://nimrod-bio-2026.s887.upress.link` (invalid cert → browser warning expected; `curl -k`). Do NOT test production.
- Reference copy (source of truth for text): `_COMMUNICATION/team_100/SITE_DELIVERY_PACKAGE_2026-05-31_v3.md` + per-page `SITE_COPY_*_v1.md`.
- Locks to re-verify (must NOT appear anywhere, incl. meta/alt): Micha/Micha OS · אנטרופיה · נגנטרופיה · רקורסיה · CDIP · Cross-Domain Isomorphism · פרמקלצר · "3×" · קואופרטיב · קומון · TBC.

## Pages to QA (12)
home `/` · `/about/` · `/about/heritage/` · `/contact/` · `/world/soil/` · `/world/know/` · `/world/code/` · `/project/sfa/` · `/project/tiktrack/` · `/project/hagina-shel-nimrod/` · `/project/rest-x-greenhouse/` · `/services/bcs/`

## Required checks
1. **Visual / screenshot** — capture each page (desktop + mobile widths) and store under `_COMMUNICATION/team_50/qa_v200/screenshots/`. Flag layout breakage, RTL issues, overflow, broken images (media placeholders are EXPECTED — note them, don't fail them).
2. **Functional** — every internal link resolves (no 404/redirect loops); external live links open (sfa.nimrod.bio, tt.nimrod.bio, WhatsApp `wa.me/972547776770`, Maps `maps.app.goo.gl/8ySCEcFw3B8hXtnP6`); nav + footer consistent.
3. **Contact form** — submit a test message; confirm delivery behavior (note: delivery address = WP `admin_email`; flagged ambiguity vs displayed `nimrod@nimrod.bio` — verify what actually receives).
4. **Content vs copy** — spot-check each page's text matches the approved `SITE_COPY_*` (esp. one-restaurant fact, generic greenhouse phrasing, SFA = community tool).
5. **Lock re-scan** — rendered HTML scan for the forbidden terms above → expect 0.
6. **Known open items** (verify, don't re-discover): world card "0 פעילויות" counts not wired; SFA/TikTrack URLs hardcoded; media placeholders pending.

## Deliverable
`_COMMUNICATION/team_50/QA_REPORT_V200_2026-06-01_v1.md` — per-page PASS/FAIL table + screenshot index + defect list (severity-ranked) + lock-scan result. Route findings back to team_100; build defects → team_35 (see parallel mandate).

## Authority / boundaries
- Read-only on content/theme; do NOT fix — report. Writes confined to `_COMMUNICATION/team_50/`.
- DB online → no structured mutations.

*team_100 | mandate | 2026-06-01 | full-system QA + visual evidence on dev*
