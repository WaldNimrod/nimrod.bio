---
type: INVENTORY_MEDIA
from: team_110 (Domain Architect / P007 orchestrator · Wave 2)
to: team_00 (Principal — provide or confirm each asset)
cc: team_110 (gate sign), team_10 (Wave 3 executor)
project: nimrod-bio
milestone: V200
wp_id: NB-S002-P007-WP002
wave: 2 of 4 (P007)
date: 2026-05-28
version: v1.0.0
mandate_ref: _COMMUNICATION/team_110/MANDATE_NB-S002-P007-WP002_COMPLETION_INVENTORY_v1.0.0.md
predecessor: MCP_QA_REPORT_NB-S002-P007-WP001_2026-05-28_v1.0.0.md
current_media_library: 843 files (confirmed via WP REST)
---

# Inventory — Media · NB-S002-P007-WP002

## How to use this list

For each asset row, mark one of:
- **PROVIDE** — upload file to WP admin media library and note the URL / attachment ID
- **ACCEPT-AS-IS** — current placeholder / grey thumbnail is OK for cutover
- **DEFER-V300** — publish as-is; address post-cutover

Suggested format: `PROVIDE: /path/to/file.jpg (or "already in library at media ID NNN")`

---

## Section 1 — Service featured images

All 10 services have `featured_media = 0`. Template T2 shows TBD placeholder ("תמונה בהכנה") on hero area. Screenshot evidence: `docs/qa/screenshots/p007-wp001/t2-services-produce_1440.png`

Required per slot: **1200 × 800px minimum, JPEG/WebP, subject-appropriate photo or graphic**

| # | service slug | ID | display name | current state | suggested source | priority |
|---|---|---|---|---|---|---|
| M-01 | produce | 22 | תוצרת מקצועית | featured_media=0, TBD placeholder | Farm produce / harvest photo | P1 |
| M-02 | hydro-greenhouse | 23 | החממה ההידרופונית | featured_media=0 | Hydroponic greenhouse interior | P1 |
| M-03 | bcs | 24 | BCS · שירותי שטח | featured_media=0 | BCS tractor / field work | P1 |
| M-04 | nursery | 25 | משתלה | featured_media=0 | Nursery seedlings / trays | P1 |
| M-05 | consulting-hydro | 26 | ייעוץ · תכנון חממה | featured_media=0 | Greenhouse blueprint or consultation | P1 |
| M-06 | consulting-agro | 27 | ייעוץ · אגרו | featured_media=0 | Field / crop consulting | P1 |
| M-07 | tiktrack | 29 | tiktrack | featured_media=0 | App screenshot or code/tech visual | P2 |
| M-08 | teaching | 30 | הוראה | featured_media=0 | Workshop / teaching scene | P2 |
| M-09 | seed-t7-produce | 42 | תוצרת (seed) | featured_media=0 | Same as M-01 if not deleted | P3 |
| M-10 | seed-t7-consulting-hydro | 43 | ייעוץ הידרו (seed) | featured_media=0 | Same as M-05 if not deleted | P3 |

**Note on M-09/M-10:** Depends on D-07 decision (seed entries may be deleted).

---

## Section 2 — Project hero images

All 5 projects have `featured_media = 0`. Template T3 shows patterned placeholder. Screenshot: `docs/qa/screenshots/p007-wp001/t3-project-coop-sharon_1440.png`

Required per slot: **1440 × 900px minimum (wide hero format), JPEG/WebP**

| # | project slug | ID | display name | current state | suggested source | priority |
|---|---|---|---|---|---|---|
| M-11 | coop-sharon | 53 | קואופרטיב חממות קטנות · השרון | featured_media=0 | Aerial/group shot, Sharon greenhouses | P1 |
| M-12 | hagina-shel-nimrod | 49 | הגינה של נמרוד | featured_media=0 | Garden / hands-in-soil photo | P1 |
| M-13 | restaurant-supply | 33 | מסירה למסעדות | featured_media=0 | Produce delivery / kitchen scene | P2 |
| M-14 | farm-y-bcs | 32 | חווה Y · BCS | featured_media=0 | Farm field or BCS machinery | P2 |
| M-15 | rest-x-greenhouse | 31 | חממת מסעדת X | featured_media=0 | Greenhouse attached to restaurant | P2 |

---

## Section 3 — Migrated posts missing featured images

17 of 22 migrated posts have featured images. 5 do not. These show generic grey thumbnail on blog index and T4 single post.

Required per slot: **800 × 533px minimum, JPEG/WebP, content-appropriate**

| # | ID | slug (decoded) | current state | note | priority |
|---|---|---|---|---|---|
| M-16 | 66 | יום-בגינה | featured_media=0, grey thumb | Original post from nimrod.bio — source photo from original site archives if available | P1 |
| M-17 | 64 | קהילה-חקלאית | featured_media=0 | Farm community photo | P2 |
| M-18 | 71 | שתילות-חוץ | featured_media=0 | Outdoor planting / transplant scene | P2 |
| M-19 | 80 | מועדי-זריעה | featured_media=0 | Planting calendar visual | P2 |
| M-20 | 81 | צמחים-חברים | featured_media=0 | Companion plants / polyculture | P2 |

---

## Section 4 — Placeholder post hero images

11 placeholder posts (Group A in INVENTORY_TEXTS). Current: `featured_media=0`, grey thumbnail on blog index.

Decision: provide images for cutover, or accept grey thumbnails and fill post-cutover as part of content fill.

| # | slug | priority (if filling before cutover) | suggested visual |
|---|---|---|---|
| M-21 | agents-os | P1 (if content filled) | AOS diagram / code/org visual |
| M-22 | israel-microgreens | P1 (if content filled) | Container hydro / microgreens |
| M-23 | smallfarmsagents | P1 (if content filled) | Small farm / community network |
| M-24 | אנטרופיה | P2 | Abstract / conceptual graphic |
| M-25 | eyal-amit-2026 | P2 | Web/WordPress visual |
| M-26 | shaked-wg-agent | P2 | Search / data visual |
| M-27 | tiktrack-phoenix | P2 | App UI screenshot |
| M-28 | agros-insite | P2 | Agricultural data / field |
| M-29 | capra-mio | P2 | Sailing / map / navigation |
| M-30 | אלה-אם-unless | P2 | Dr. Seuss / unless reference or abstract |
| M-31 | back-to-mud | P3 | Earth / soil / hands |

**Team_00 note:** If ACCEPT-AS-IS for all M-21..M-31, grey thumbnails ship with placeholder posts. Low visual impact; all acceptable for cutover.

---

## Section 5 — Asset integrity fix

| # | location | issue | action | priority |
|---|---|---|---|---|
| M-32 | /blog/harish2021/ inline content | console.error 404 on one embedded resource (Wave 1 F-004). The asset URL is `wp-content/uploads/2026/05/unnamed-file-*` (missing in library) | Option A: locate original file, re-upload, rewrite URL in post. Option B: remove the img tag from post body. Decision: D-06 in INVENTORY_DECISIONS | P1 |

---

## Summary

| Section | Slots | All-DEFER possible? |
|---|---|---|
| 1 — Service featured images (8 real + 2 seed) | 10 | Yes (theme shows placeholder gracefully) — aesthetic impact only |
| 2 — Project hero images | 5 | Yes (patterned placeholder) |
| 3 — Migrated posts missing featured image | 5 | Yes (grey thumb) |
| 4 — Placeholder post images | 11 | Yes (tied to text fill decision) |
| 5 — harish2021 broken asset | 1 | No — console 404 on live page, should fix |
| **Total slots** | **32** | |

**Minimum before cutover (team_110 recommendation):**
- M-32 (harish2021 asset) — fix or remove the img
- Service M-01..M-08 — P1 if team_00 wants professional appearance on cutover day
- Project M-11..M-12 — P1 (high-traffic: coop-sharon, hagina)
- Migrated M-16 (יום-בגינה) — P1 per Wave 1 F-002

— team_110 (orchestrator · Wave 2) — 2026-05-28
