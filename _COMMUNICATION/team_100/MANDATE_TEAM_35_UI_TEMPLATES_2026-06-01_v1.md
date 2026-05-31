# MANDATE + ACTIVATION — UI Precision + Missing Templates — team_35 — v1

**Date:** 2026-06-01
**Author:** team_100
**To:** team_35 (Site Design + Build — Claude Design environment)
**Type:** MANDATE + ACTIVATION PROMPT
**WP:** — (V200 site rebuild)
**Environment:** Claude Design (design/build engine — distinct from QA team_50 and from this session's text builders)

## Why
All page TEXT for SITE_DELIVERY_PACKAGE v3 is implemented and verified on dev. What remains is **design-layer**: UI precision/polish and **completing missing/partial templates** in the system. This is team_35's domain.

## Scope of work
1. **UI precision pass** (all 12 pages, dev `https://nimrod-bio-2026.s887.upress.link`):
   - RTL spacing/typography consistency; heading hierarchy; card grids; button styles (the new Contact hero buttons `.hero-act` + socials `.contact-social`); world-page lattice + bridge/"seam" cards; About timeline + "קצת ים" section.
   - Mobile responsiveness; consistent nav/footer; consistent section rhythm.
2. **Complete missing / partial templates:**
   - **World card activity counts** — currently render "0 פעילויות"; wire to real CPT counts.
   - **Project external links data-driven** — register `_nb_external_url` / `_nb_external_label` for the `project` CPT and set for SFA (sfa.nimrod.bio) + TikTrack (tt.nimrod.bio); replace the hardcoded code-world URLs.
   - **Media slots** — finalize the gallery/image-placeholder templates so they degrade cleanly until photos arrive (placeholders currently show `data-cap="TBD · …"`; ensure they render acceptably or hide until media supplied). Media assets themselves arrive separately (owner/domain team) — do NOT block on them.
   - **Dead-code cleanup:** `nb_render_cdip_diagram()` in `inc/template-helpers.php` (no longer called) and orphaned `template-parts/t8-media-item.php` — remove or repurpose.
   - Audit for any other missing/stub templates (T1/T2 service templates, archive states, 404, search) and complete them to the design system.

## 🔒 Two locks (override everything — apply to every change incl. meta/alt/aria)
1. **Micha Stokes / "Micha OS"** — never published anywhere.
2. **Demonstrate, never name** — no surface: אנטרופיה · נגנטרופיה · רקורסיה · CDIP · Cross-Domain Isomorphism · פרמקלצר · "3×". No marketing clichés (disruption · game-changer · "הפלטפורמה שלנו" · "אנחנו מאמינים" · AI-first). No coop fabrication (קואופרטיב/קומון). Voice rules: `SITE_HANDOFF_2026-05-31_v1.md` §2.

## ⚠ MANDATORY procedures (read before any edit)
- **Deploy:** `docs/UPRESS_FTPS_MANDATORY_PROCEDURE.md` — theme files deploy to dev via FTPS (`scripts/upress_ftps_upload.py`). The repo is now synced to deployed state (commit `7526893b`); keep them in sync — after deploying, pull deployed files back into the repo (`scripts/upress_ftps_download.py`, one file per connection — batch downloads degrade to 0-byte) and commit.
- **Theme version:** bump `NB_THEME_VERSION` in `functions.php` (currently 0.7.5) when changing CSS/assets, to cache-bust.
- **Verify** every change on the rendered page (cache-busted `?nc=` bypasses SuperCache) before reporting done.
- **Scope:** writes to `_COMMUNICATION/team_35/` + theme source only. Never `_aos/`.

## Inputs / source of truth
- `_COMMUNICATION/team_100/SITE_DELIVERY_PACKAGE_2026-05-31_v3.md` (master index)
- `SITE_COPY_*_v1.md` (per-page approved text — do NOT alter text, only design)
- `SITE_HANDOFF_2026-05-31_v1.md` (voice + locked facts)
- `COMPLETION_SITE_DELIVERY_V3_FULL_2026-06-01_v1.md` (open-items list — items to complete)

## Coordination
team_50 runs a parallel QA + visual pass (`MANDATE_TEAM_50_QA_VISUAL_2026-06-01_v1.md`). Consume their `QA_REPORT_V200` defect list as input; resolve build defects they raise.

## Deliverable
`_COMMUNICATION/team_35/COMPLETION_UI_TEMPLATES_V200_2026-XX-XX_v1.md` — what was polished + which templates completed + before/after notes + deploy/sync confirmation + lock-scan result. Report back to team_100.

---

## Activation prompt (paste to start the team_35 session)
```
You are team_35 — Site Design + Build for nimrod.bio (WordPress, Hebrew RTL, uPress), Claude Design environment.
MANDATE: _COMMUNICATION/team_100/MANDATE_TEAM_35_UI_TEMPLATES_2026-06-01_v1.md.
Task: UI precision pass + complete missing/partial templates on dev (https://nimrod-bio-2026.s887.upress.link). Page TEXT is final & locked — design only, do not alter approved copy.
FIRST: read the mandate above + SITE_DELIVERY_PACKAGE_2026-05-31_v3.md + docs/UPRESS_FTPS_MANDATORY_PROCEDURE.md.
Honor both super-locks (Micha · demonstrate-not-name). Deploy via FTPS; keep repo in sync (commit 7526893b is current baseline); verify every change on the rendered page; report to team_100.
```

*team_100 | mandate + activation | 2026-06-01 | UI precision + missing templates (Claude Design)*
