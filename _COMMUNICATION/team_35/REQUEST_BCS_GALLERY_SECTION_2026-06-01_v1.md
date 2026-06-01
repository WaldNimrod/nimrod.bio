# REQUEST — BCS service gallery section (design + meta) — team_100 → team_35 — v1

**Date:** 2026-06-01
**Author:** team_100
**To:** team_35 (Site Design + Build — Claude Design)
**Type:** BUILD REQUEST (folds into MANDATE_TEAM_35_UI_TEMPLATES + WP NB-S002-P009-WP005)
**Env:** dev `https://nimrod-bio-2026.s887.upress.link` · theme v0.7.12

## Context
Gallery wiring (WP005) is done for the two **project** CPTs (Garden 49, Greenhouse+עירית שומית 31) — photos live, alt-set, locks clean. The **BCS hero** is also wired (service 24 `featured_media=1085`, flail/box mower). What's left for BCS is a **gallery section** to show the remaining tool/field photos — and that needs a design + registration change, which is team_35's domain (not a data-only fix).

## The two blockers (why team_100 didn't just do it)
1. **`_nb_gallery` is not registered on the `service` CPT.** `inc/meta-registration.php` registers `gallery` (array meta) only for `project` (line ~51), not `service` (line ~42 loop = `linked_projects`, `related_posts` only). → REST/UI can't persist a service gallery until `service` gets the meta.
2. **`single-service.php` renders no gallery part.** It uses `t2-*` parts (hero/meta-strip/three-col/final-cta); there is no `t3-gallery` (or equivalent) include. The only image slot is the hero (post thumbnail) — already used.

## Requested work
1. Register `_nb_gallery` (array meta, `show_in_rest` array-of-strings, matching the `project` pattern) on the **`service`** CPT in `inc/meta-registration.php`.
2. Add a gallery section to `single-service.php` (reuse `template-parts/t3-gallery.php` or a T2-styled variant), gated to render only when `_nb_gallery` is non-empty; otherwise `.ph.clean` / nothing (no visitor "TBD").
3. Deploy via the mandatory FTPS procedure + bump `NB_THEME_VERSION`; keep repo synced.
4. Notify team_100 — we (or you) then wire the BCS gallery IDs.

## Photos ready (in Drive, per `sources/photos/SHORTLIST_bcs.md`)
- מכסחת ארגז `IMG_20180123_122202` (already the hero) · Ground Blaster `IMG_20180123_130615`
- field/after: `IMG_20180130_171114`, `…171130`, `IMG_20180221_125157`, `…125210`, prepared-bed `IMG_20180130_171052`, overview `42625111_…_o.jpg`, team/transport `IMG_20180129_153151`
- 🛑 STILL MISSING (owner gaps, do NOT substitute): **מתחחת (tiller)** + **Power-Harrow** — not in Drive; owner to supply from Google Photos.

## Locks
Both super-locks apply (incl. alt/aria/caption). Alt-text Hebrew, descriptive, lock-clean.

*team_100 | build request | 2026-06-01 | BCS gallery section → team_35; projects already wired*
