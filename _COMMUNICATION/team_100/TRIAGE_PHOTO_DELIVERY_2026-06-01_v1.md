# TRIAGE — Domain Team Photo Delivery — team_100 — v1

**Date:** 2026-06-01
**Author:** team_100
**To:** team_35 · team_00 (visibility)
**Type:** TRIAGE / ACCEPTANCE
**Source (committed `c2853116` / `8f87875d`):**
- `_COMMUNICATION/team_100/SITE_PHOTO_REPORT_2026-06-01.md`
- `sources/photos/PHOTO_MANIFEST_2026-06-01.md` + `SHORTLIST_{bcs,greenhouse,hero_about,garden_teaching}.md` + `ARCHIVE_INDEX.md`

## Decision: ACCEPT — selections only; binaries pulled at integration time
Domain media team ran a 4-agent triage of the owner's Drive archive **"מהגינה" (2,579 images)** and produced per-page photo **selections (as archive paths)** + 4 shortlists + a consolidated manifest. **No image binaries committed** — by design (report note: pull approved files from Drive into WP Media at integration). Git reconciled clean (these commits sit under team_100 Stage-A + team_50 QA; no conflict).

## Validation done by team_100
- Archive present: `/Users/nimrod/Documents/Google Drive/מהגינה` — 2,579 JPEG/JPG confirmed.
- Selection paths resolve under the nested real-archive root `שיווק/תוכן ושיווק/סרטונים ותמונות/…` (spot-checked: `מגדלים ונהנים/20210506_185213.jpg` ✓, `בראשית הגינה/IMG_20180123_122202.jpg` ✓). The manifest's "relative to מהגינה folder" paths need that prefix — team_35 should resolve by filename, not assume top-level.
- Manifest + shortlists intact in git; `sources/photos/{about,bcs,biochar,garden,greenhouse,hero}/` are empty staging dirs (binaries land here only on owner approval, per report "Next steps").

## 🛑 The 5 gaps — DO NOT substitute (keep `.ph.clean` until owner supplies)
1. **ים / סירה (sea/boat)** — About §06 "קצת ים" — zero in Drive
2. **פאטבונג (pak-bung)** — greenhouse crop
3. **מתחחת + Power Harrow** — 2 of the 4 BCS tools
4. **ביוצ'ר (biochar)** — process/workshop/field
5. **HEIC** `IMG_0943–0949` (hydroponic greens) — untriaged; convertible locally (may hold pak-bung/crop shots)

These slots render `.ph.clean` (Stage B) — no stand-ins.

## Confirmed selections (✅ ready to wire)
| Slot | Asset (filename; under …/סרטונים ותמונות/) | Status |
|---|---|---|
| hero-home | `מגדלים ונהנים/20210506_185213.jpg` | ✅ |
| hero-know | `קורס גינון/WhatsApp…20.05.07.jpeg` | ✅ |
| hero-soil | a garden bed/field shot (SHORTLIST_garden) | ✅ |
| hero-code | — | 🛑 use SFA/tt screenshot or abstract (no garden photo fits) |
| About portrait | `קו הרצליה/IMG-20211104-WA0036.jpg` | ✅ |
| About §06 sea | — | 🛑 gap #1 |
| Garden gallery ×7 | SHORTLIST_garden_teaching.md | ✅ |
| Greenhouse ×13 | SHORTLIST_greenhouse.md | ✅ |
| עירית שומית | `IMG-20210726-WA0002.jpg` + `WhatsApp…2021-12-05…13.42.36.jpeg` | ✅ |
| BCS מכסחת ארגז | `בראשית הגינה/IMG_20180123_122202.jpg` | ✅ |
| BCS Ground Blaster | `בראשית הגינה/IMG_20180123_130615.jpg` | ✅ |
| BCS field/after | `IMG_20180130_171114/171130`, `IMG_20180221_125157/125210` | ✅ |
| BCS מתחחת/Power-Harrow | — | 🛑 gap #3 |
| brand | `מספיק לכולם לתמיד.jpg` (quote flyer) + logo PNG (transparent) | ✅ |

## Integration mechanics (report §"Integration instructions" is authoritative)
Per asset: Drive original → WebP (q82, ≤2400px) → WP Media Library → assign to slot. Alt-text Hebrew, descriptive, per shortlist — **both super-locks apply to alt + caption**. Heroes: crop wide, **no baked-in text** (headline is live HTML); container holds `aspect-ratio` + `object-fit:cover` (canon G-05). Do NOT hotlink from `_COMMUNICATION/` or `sources/`. Logo PNG is production-ready.

## Note on the "integration guide"
The hand-off message referenced `_COMMUNICATION/team_35/SITE_PHOTO_INTEGRATION_GUIDE_2026-06-01.md` — **not present** in the repo. Non-blocking: the report's integration section + the MANIFEST cover the slot map + alt/lock rules.

## Sequencing into the staged design build
- **Stage B (next)** — `.ph.clean` media degradation + dev-only TBD caption → graceful empty slots (prereq for any media).
- **Stage C/D** — as each page's slots are touched, wire the ✅ selections (home/know heroes, About portrait, Garden/Greenhouse galleries, עירית שומית, BCS tools, brand); the 5 gaps stay `.ph.clean`.
- team_35 can run mechanical Drive→WP integration in parallel using the MANIFEST.

*team_100 | triage + acceptance | 2026-06-01 | photo selections accepted; 5 placeholders locked; wiring → Stage B/C/D*
