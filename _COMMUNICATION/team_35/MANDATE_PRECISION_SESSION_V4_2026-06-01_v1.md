# MANDATE + ACTIVATION — Mockup-Fidelity Results Scan + Interactive Precision Session — team_35 — v1

**Date:** 2026-06-01
**Author:** team_100
**To:** team_35 (Site Design + Build — Claude Design environment)
**Type:** MANDATE + ACTIVATION PROMPT
**WP:** NB-S002-P009-WP001 (UI Precision Pass)
**Env:** Claude Design · dev `https://nimrod-bio-2026.s887.upress.link` · theme **v0.7.15** · repo baseline commit **a35a67df**

## Why
The mockup-drift alignment (Buckets A/B/C from `DRIFT_REGISTER_MOCKUP_V4_2026-06-01_v1.md`) is implemented + verified (CDP 20/20, byte-parity, locks 0). Now: **(1) scan the live result against the Precision Mockup v4 to confirm fidelity**, and **(2) run an interactive precision session directly with Team 00** — Team 00 edits visually in Claude Design (point-and-edit, not prose); team_35 lands each accepted tweak in the proper module/template.

## SSOT + inputs
- **Design SSOT:** `_COMMUNICATION/team_100/_INBOX_design_handoff_v200/design_handoff_ui_precision_v200/Precision Mockup v4.html` (6 screens: t7/t1/contact/about/sys/states).
- **Drift register (what's done + open):** `_COMMUNICATION/team_100/DRIFT_REGISTER_MOCKUP_V4_2026-06-01_v1.md`.
- **QA harness:** `docs/QA_HARNESS.md` → `node scripts/qa/cdp/qa_probe.mjs` (browser overflow + lock scan + screenshots; dev TLS invalid BY DESIGN → cert-bypass DEV-ONLY).

## Part 1 — Results scan (fidelity confirmation)
Screen-by-screen, render live vs the mockup at 375 + 1440 and confirm each component now matches (the A/B/C fixes: eyebrow mono, wa-btn green, contact form/social, bridges 2-up, About prose + final-cta, system.css tokens shadow/radius/line-height 1.55). Use `qa_probe.mjs` for overflow/lock + screenshot capture; eyeball the screenshots vs the mockup screens. Produce a per-screen PASS / drift-remaining list.

## Part 2 — Interactive precision session with Team 00 (the point of this mandate)
Team 00 reviews the live site in **Claude Design** and makes visual precision edits. For each accepted change, team_35:
- lands it **in the module CSS / template-part itself** — **NO inline styles, NO overrides.css layer** (binding rule, team_00 2026-06-01);
- deploys via `docs/UPRESS_FTPS_MANDATORY_PROCEDURE.md`, bumps `NB_THEME_VERSION`, keeps repo in sync (pull deployed files back, one per connection), verifies cache-busted, lock-scans;
- preserves all content (mockup STYLE may be adopted, but live text/info is kept — team_00 ruling).

## Missing-element decisions (Team 00, 2026-06-01)
- **t7 home §06 — Recent-Posts / blog teaser** (`.posts-grid` + feature `.rp-card.feat` + 4 `.rp-card`; mockup ~lines 940-953; absent from `front-page.php`). **✅ team_00 DECISION: BUILD IT in this precision session.** Implement the §06 section in `front-page.php` to the mockup structure, pulling REAL recent posts (the `/blog/` index already has ~10; query latest published, feature the newest as `.rp-card.feat` + 4 more). Match the mockup's posts-grid styling; honor locks on any rendered title/excerpt. Place between §05 projects and the manifesto per the mockup order.
- Minor/cosmetic (Team 00 optional): t1 `.world-bg` faint basket watermark (decorative) — add or skip.
- Note (content, not build): t1 world journal (`vc-posts`) renders empty — no posts tagged to `world` taxonomy yet (content-seeding, separate).

## 🔒 Locks (every change, incl. alt/aria/comments)
Micha/"Micha OS" · CDIP · cross-domain · אנטרופיה · נגנטרופיה · רקורסיה · פרמקלצר · "3×" · אינסטנסים · קואופרטיב · קומון · marketing clichés — never surface. Lock-scan every diff.

## Deliverable
`_COMMUNICATION/team_35/COMPLETION_PRECISION_SESSION_V4_2026-XX-XX_v1.md` — Part 1 fidelity scan result + Part 2 list of session edits (file + selector + old→new) + Team 00 decision on §06 + deploy/version/byte-parity + final CDP PASS + lock-scan. Report to team_100 for independent re-verification.

## Boundaries
Writes to `_COMMUNICATION/team_35/` + theme source only. Never `_aos/`. No inline styles, no overrides layer. Cross-engine: team_50/team_190 validate, not team_35.

---

## Activation prompt (paste to start the team_35 session)
```
You are team_35 — Site Design + Build for nimrod.bio (WordPress, Hebrew RTL, uPress), Claude Design environment.
MANDATE: _COMMUNICATION/team_100/../team_35/MANDATE_PRECISION_SESSION_V4_2026-06-01_v1.md.
Task: (1) scan the live dev site (https://nimrod-bio-2026.s887.upress.link, theme v0.7.15) for fidelity vs Precision Mockup v4; (2) run an interactive precision session with Team 00 — Team 00 edits visually, you land each accepted tweak in the MODULE CSS / template-part itself (NO inline, NO overrides layer), deploy via FTPS, verify, keep repo synced on top of commit a35a67df.
FIRST: read the mandate + DRIFT_REGISTER_MOCKUP_V4_2026-06-01_v1.md + the mockup + docs/UPRESS_FTPS_MANDATORY_PROCEDURE.md + docs/QA_HARNESS.md.
BUILD §06: team_00 approved building the home Recent-Posts/blog-teaser (the one missing mockup element) into front-page.php with real recent posts, to mockup structure.
Honor both super-locks. Verify every change on the rendered page (qa_probe.mjs). Report to team_100.
```

*team_100 | mandate + activation | 2026-06-01 | results scan + interactive precision session (Claude Design) — §06 flagged for Team 00*
