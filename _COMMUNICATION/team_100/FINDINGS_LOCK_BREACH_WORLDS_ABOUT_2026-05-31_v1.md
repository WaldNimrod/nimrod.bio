# FINDINGS — Lock breaches on Worlds + About (display layer) — team_100 — v1

**Date:** 2026-05-31
**Author:** team_100
**WP:** — (site content, V200)
**Type:** FINDINGS / DECISION REQUEST

## What was confirmed-and-done this session
1. **SFA page (G)** — `SITE_COPY_SFA_v1` applied to `/project/sfa/` (id 1006). Live, HTTP 200, all locks clean. ✅
2. **About (page 37) content** + **world term descriptions (4/5/6)** — updated via REST per approved draft. Data correct in DB (API-verified). ⚠ **But not displayed** — see finding below.

## Core finding — About & Worlds render from hardcoded theme PHP, not WP fields
- `/about/` renders `themes/nimrod-bio-2026/page-about.php` (custom template, hardcoded copy) — it does **not** call `the_content()`. My REST edit to page 37 is correct in the DB but **never surfaces**.
- World archives do **not** print `term_description` (`t1-body.php` has no such call). My world descriptions are stored but **never surface**.
- Project/service pages DO use `the_content()` → SFA edit works normally.
- **Implication:** Worlds + About copy must be edited in theme template parts, not via REST.

## Super-lock / locked-fact violations on the live (dev) site
Confined to About + world archives + one post (home + all product pages are CLEAN):

| Location | Violation | Lock / fact |
|---|---|---|
| `page-about.php:63` | "תוצרת ל-**5 מסעדות**" (×3 on page) | Locked fact: **ONE** restaurant (המחתרת התאילנדית). Fabricated number. |
| `page-about.php:63` | "אבחון של **4 חממות נוספות**" | Unverified number — handoff said drop. |
| `page-about.php:63` | SFA link → `/services/sfa/` | SFA lives at `/project/sfa/`. |
| `page-about.php` | `TBC · Q-NEW-03`, `TBC · Q-05` (7× TBC rendered) | Live placeholders shown to visitors. |
| `template-parts/t8-cdip-thesis.php` | "CDIP — Cross-Domain Isomorphism Perception. 3×" + link "פוסט CDIP בעברית" | **Demonstrate-never-name** — names the thesis explicitly. |
| world archive strip (`t1-body.php`) | label "CDIP · cross-domain isomorphism" + "אותם עקרונות יסוד…" (1× per world page) | **Demonstrate-never-name.** |
| Post **id 136**, slug `אנטרופיה`, status `publish` | Title = forbidden term `אנטרופיה`; appears in world/know archive | **Demonstrate-never-name.** |

## Scan summary (rendered, cache-busted)
Home=clean · SFA/BCS/TikTrack=clean · About=CDIP×4 / "5 מסעדות"×3 / TBC×7 · world/{soil,know,code}=CDIP×1 each · world/know also lists post "אנטרופיה".

## Decision needed (Team 00)
This is theme-template + published-content territory (team_35 builds the theme). Options:
- **[1] team_100 fixes now** — clear violations against an *already-approved* lock; I edit `page-about.php` (rewrite §3 to one-restaurant + drop 4-greenhouses + fix SFA link + strip TBC), neutralize `t8-cdip-thesis.php` + world CDIP strip (demonstrate, not name), and set post 136 to draft. Then re-scan to 0.
- **[2] Route to team_35** — file a build brief; team_35 owns theme edits; team_100 supplies the replacement copy.
- **[3] Fix the post only now, defer theme** — unpublish/retitle post 136 immediately; queue the theme fixes.

## Note
My REST edits to About page 37 + world descriptions are benign (aligned, just not rendered). Leave as-is or revert on request.

*team_100 | findings + decision request | 2026-05-31*
