# MANDATE — Pre-cutover Full QA Re-run (design A–D + media) — team_50 — v1

**Date:** 2026-06-01
**Author:** team_100
**To:** team_50 (QA / Validation)
**Type:** MANDATE
**WP:** NB-S002-P005-WP001B
**Engine constraint (Iron Rule #1):** team_50 ≠ builders (team_100 design sub-agents / team_35 / team_10). Independent validator. ✅

## Why
Since team_50's earlier `QA_REPORT_V200` (text-site, PASS_WITH_FINDINGS), the site gained the full **design build (Stages A–D)** + **media** + **new templates**. This re-run validates the current state on **dev** before the V200 cutover gate.

## Scope — environment
- Target: **dev** `https://nimrod-bio-2026.s887.upress.link` (invalid TLS cert expected; `curl -k`). Theme **v0.7.12**. Do NOT test production.
- Copy SSOT: `SITE_DELIVERY_PACKAGE_2026-05-31_v3.md` + `SITE_COPY_*_v1.md`.
- Open-items context: `OPEN_ITEMS_REGISTER_V200_2026-06-01_v1.md`.

## What changed since last QA (focus areas)
1. **Stage A** — hero kicker (420 מ״ר / 9 עונות / SFA חי); `.vc-principle` (was vc-cdip); bridge SFA-only.
2. **Stage B** — new `404.php`, `search.php`, empty-archive `.empty-state`; `.ph.clean` media degradation; **TBD caption admin-only** (must be invisible to anon).
3. **Stage C** — Contact hero (`.btn-wa`/`.btn-ghost`); About journey timeline (`.timeline`/`.tl-row`), "קצת ים" (`.sea`), principle grid.
4. **Stage D** — activity counts (בקרוב/אחת/N — never "0 פעילויות"); data-driven external links (`_nb_external_url`, `.ext-link`, omit when empty); token/RTL sweep.
5. **Media** — real photos: home/know/soil heroes, manifesto + About portrait; **galleries** Garden(49, ×7), Greenhouse+עירית שומית(31, ×13), **BCS hero**(service 24).
6. **t8.css dead-code cleanup** (team_10) — verify no regression (already verified by team_100; reconfirm).

## Required checks (12 pages + system templates)
Pages: `/` · `/about/` · `/about/heritage/` · `/contact/` · `/world/{soil,know,code}/` · `/project/{sfa,tiktrack,hagina-shel-nimrod,rest-x-greenhouse}/` · `/services/bcs/`. Plus **404** (bogus URL) + **search** (`/?s=…` hit + no-hit).
1. **Visual / screenshot** — desktop 1440 + mobile 375, full-page; store under `_COMMUNICATION/team_50/qa_v200b/screenshots/`. Flag layout/RTL/overflow. **375px: no horizontal scroll.**
2. **Functional** — internal links resolve (incl. the F-002 `/services/` 404 — confirm fixed or still open); external live links (sfa/tt/WhatsApp/Maps); nav/footer consistent.
3. **System templates** — 404 renders designed layout (H1 "השביל הזה לא מוביל"); search renders results + `.empty-state` on no-hit.
4. **Media** — galleries render real `<img>` with Hebrew alt (Garden 7, Greenhouse 13, BCS hero); empty slots show `.ph.clean` (NOT broken boxes); the 5 owner gaps + hero-code remain `.ph.clean` (expected, not a failure).
5. **Counts / external links** — no "0 פעילויות" anywhere; `.ext-link` shows on SFA/TikTrack, omitted where no URL.
6. **TBD gating** — **anonymous sees 0 "TBD"** on every page (caption is admin-only).
7. **Content vs copy** — spot-check key facts (one restaurant; SFA community tool; 420 מ״ר; no retired terms).
8. **Lock re-scan** — rendered HTML incl. alt/aria/meta: **0** for Micha · אנטרופיה · נגנטרופיה · רקורסיה · CDIP · cross-domain · פרמקלצר · "3×" · אינסטנסים · קואופרטיב · קומון.
9. **Lighthouse** — perf/a11y/SEO on home + one project + about (note scores; flag a11y contrast/regressions).
10. **Contact form** — submit a test; confirm delivery to `nimrod@mezoo.co` (F-001 close-out); confirm no public email displayed.

## Deliverable
`_COMMUNICATION/team_50/QA_REPORT_V200B_2026-06-01_v1.md` — verdict box + per-page PASS/FAIL matrix + screenshot index + Lighthouse scores + defect list (severity-ranked) + lock-scan result. Build defects → team_35 / team_100; this report feeds the team_190 constitutional L-GATE_VALIDATE that precedes cutover.

## Authority / boundaries
- Read-only on content/theme; report, do not fix. Writes confined to `_COMMUNICATION/team_50/`.
- DB online → no structured mutations.
- STOP conditions: any lock breach, any page non-200, anon "TBD" leak.

*team_100 | mandate | 2026-06-01 | pre-cutover full QA re-run (WP P005-WP001B)*
