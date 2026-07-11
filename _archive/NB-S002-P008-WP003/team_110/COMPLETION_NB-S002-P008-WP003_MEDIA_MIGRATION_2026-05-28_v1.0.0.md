---
type: COMPLETION
from: team_110 (orchestrator — self-executed)
to: team_00 (Principal)
project: nimrod-bio
milestone: V200 (pre-cutover)
wp_id: NB-S002-P008-WP003 (informal — media migration from production)
date: 2026-05-28
version: v1.0.0
status: PASS — all old posts now carry their original production images
supersedes: COMPLETION_NB-S002-P008-WP003_IMAGES_2026-05-28_v1.0.0.md (Drive phase)
---

# Completion — P008-WP003 · Media Migration from Production

## §1 Mandate correction (team_00 instruction)

After the Drive-photo assignment phase (v1.0.0), team_00 issued a critical correction:

> "פוסטים ישנים - חובה לבצע מיגרציה של התמונות שהיו לפוסטים האלו - לא נחליף.
>  יש להעביר לתקיית המדיה של האתר החדש ולקשר בהתאם."

Rule: **every old post's original featured image must be migrated from production — no Drive substitutions.**

Methodology:
1. For each old post on dev, look up its counterpart on prod (`https://www.nimrod.bio`) by title match
2. Retrieve prod `featured_media` URL
3. Download from prod CDN → resize to max 1600px (`sips -Z 1600`) → upload to dev media library → set `featured_media` on dev post
4. If prod `featured_media = 0` for a post → leave dev `featured_media = 0` (do not substitute)

## §2 Actions taken

### 2a — Drive assignments rolled back for old posts

| Dev Post | Old Assignment (Drive) | Action |
|---|---|---|
| Post 64 (קהילה-חקלאית) | Media 1038 (Drive carrots) | Replaced with migrated prod original |
| Post 66 (יום-בגינה) | Media 1040 (Drive chameleon) — wrongly assigned | Replaced with migrated prod original |
| Post 71 (שתילות חורף) | Media 1037 (Drive seedling trays) | Reverted to fm=0 (prod had no image) |

### 2b — Production images migrated to dev

| Dev Media ID | Filename | Source (prod) | Subject | Assigned to |
|---|---|---|---|---|
| 1042 | kahila-haclaait-original.jpg | prod media 90828 (`uploads/2023/02/1febd3f4-...jpg`) | Person harvesting red peppers in greenhouse | Post 64 (קהילה-חקלאית) |
| 1044 | yom-baginah-original.jpg | prod media 90685 (`uploads/2023/01/למה-אנחנו-קמים-בבוקר.jpg`) | YouTube thumbnail — Nimrod in greenhouse | Post 66 (יום-בגינה) |
| 1045 | farme-logo.gif | prod media 6518 (`uploads/2017/12/Farmer.gif`) | Farmer app logo GIF | Post 1046 (new on dev) |
| 1047 | mahasanei-hatevaonut-partnership.png | prod media 2384 (`uploads/2016/02/מחסני-הטבעונות-web.png`) | Partnership announcement screenshot | Post 1048 (new on dev) |

### 2c — Missing prod posts created on dev

Two posts existed on production but were absent from the dev rebuild. They were created on dev with their original images migrated:

| Dev Post ID | Title | Prod Post ID | Dev Media | Prod Media |
|---|---|---|---|---|
| 1046 | חדש – הזמנות מהגינה באפליקציית Farmer | 6516 | 1045 (Farmer logo GIF) | 6518 |
| 1048 | שיתוף פעולה חדש בין הגינה ומחסני הטבעונות | 2236 | 1047 (partnership PNG) | 2384 |

Both posts created as `status: draft` with `content.raw` from prod, `date` from prod, `featured_media` set to migrated image.

## §3 Post-migration state — all dev posts

| Dev Post | Title | featured_media | Source | Notes |
|---|---|---|---|---|
| 64 | קהילה-חקלאית | 1042 ✅ | Migrated from prod 90828 | Pepper harvest in greenhouse |
| 66 | יום-בגינה | 1044 ✅ | Migrated from prod 90685 | YouTube thumbnail |
| 71 | שתילות חורף | 0 | Prod fm=0, "לא נחליף" | No image — intentional |
| 1046 | Farmer app post | 1045 ✅ | Migrated (new post on dev) | Was missing from dev |
| 1048 | מחסני הטבעונות | 1047 ✅ | Migrated (new post on dev) | Was missing from dev |

Wave 3 previously confirmed 16/16 old posts migrated correctly. These 5 are the image-correction layer on top.

## §4 Acceptance tests

| AT | Criterion | Result |
|---|---|---|
| AT-1 | Old posts carry their original prod images (not Drive substitutes) | ✅ PASS — posts 64, 66 corrected |
| AT-2 | Posts with fm=0 on prod retain fm=0 on dev | ✅ PASS — post 71 reverted to 0 |
| AT-3 | No prod posts missing from dev (content complete) | ✅ PASS — posts 1046, 1048 created |
| AT-4 | All media uploads returned valid IDs and alt text set | ✅ PASS — IDs 1042, 1044, 1045, 1047 |
| AT-5 | No secrets echoed to chat | ✅ PASS — credentials sourced from .env, never displayed |

## §5 Still open (unchanged from Drive phase)

- Services 26 (consulting-hydro), 27 (consulting-agro), 30 (teaching) — no suitable image on prod or Drive
- Project 53 (coop-sharon) — no image
- Posts 120–125, 137, 1019 (new tech posts) — need screenshots/captures
- Post 71 — intentionally fm=0
- `/about/` page — see `HANDOFF_NIMROD-BOOK_ABOUT_PAGE_2026-05-28_v1.0.0.md`

— team_110 — 2026-05-28
