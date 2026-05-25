---
type: COMPLETION
from: team_10 (nimrodbio_build)
to: team_100 / team_190
wp_id: NB-S002-P004-WP001
project: nimrod-bio
milestone: V200
date: 2026-05-25
gate: L-GATE_BUILD
spec_ref: _aos/work_packages/NB-S002-P004-WP001/LOD400_NB-S002-P004-WP001.md
---

# COMPLETION — NB-S002-P004-WP001 — Content migration (Phases 4–6)

## Status summary

| Phase | Status | Notes |
|-------|--------|-------|
| 1 — Prod creds | ✅ DONE (team_00) | `.env.upress` verified |
| 2 — Fetch prod | ✅ DONE | 25/25 cached READ ONLY |
| 3 — Tagging tool | ✅ DONE | 22-row HTML ready |
| 4 — Import to dev | ✅ DONE | 22 posts + 1 page (shook) via REST |
| 5 — Uploads transfer | ✅ DONE (partial media) | 461 files cached; FTPS uploaded; content URLs patched to HTTP |
| 6 — Seed cleanup | ✅ DONE | 7 legacy `v200` seeds deleted; 22 migrated remain |

**Tagging input:** `docs/content_tagging_decisions_2026-05-25.json` (team_00, 22 posts, all worlds + flow_style filled).

## Deliverables

| Deliverable | Path | Status |
|-------------|------|--------|
| fetch_prod_posts.py | `scripts/migration/fetch_prod_posts.py` | ✅ tracked |
| transform_post.py | `scripts/migration/transform_post.py` | ✅ tracked |
| tagging_export.py | `scripts/migration/tagging_export.py` | ✅ tracked |
| import_to_dev.py | `scripts/migration/import_to_dev.py` | ✅ tracked + run |
| uploads_transfer.py | `scripts/migration/uploads_transfer.py` | ✅ tracked + run |
| cleanup_seeds.py | `scripts/migration/cleanup_seeds.py` | ✅ tracked + run |
| Tagging triage HTML | `docs/content_tagging_triage.html` | ✅ 22 rows |
| Tagging decisions | `docs/content_tagging_decisions_2026-05-25.json` | ✅ team_00 |
| id_mapping | `.migration-cache/id_mapping.json` | ✅ 23 entries (gitignored) |
| Raw cache | `.migration-cache/raw/*.json` | ✅ 25 files (gitignored) |

## Phase 4 import summary

```
[INFO] Importing 23 entities using tagging from content_tagging_decisions_2026-05-25.json
[OK] pages prod=90533 -> dev=59 slug=shook
[OK] posts prod=91178..1218 -> dev=60..81 (22 posts)
[OK] Wrote .migration-cache/id_mapping.json (23 entries)
[OK] Wrote .migration-cache/referenced_uploads.json (493 upload URLs)
```

- Skipped: page 2516 (heritage — hardcoded T8), page 90896 (/blog/ archive collision)
- `_nb_seed='v200-migrated'` on all imported posts
- Original `post_date` preserved from prod raw JSON
- Script fixes applied: Hebrew slug URL-encoding in DELETE lookup; REST retry on dev timeouts

## Phase 5 uploads summary

```
[INFO] Referenced uploads: 493
[OK] Downloaded/cached 461 files under .migration-cache/uploads
[OK] FTPS upload to dev wp-content/uploads/
[OK] Patched content URLs for 23 imported entities (HTTP dev base)
```

**Known gaps (non-blocking for WP002):**
- ~32 prod URLs failed download: Cloudflare 429 rate-limit + Hebrew filename paths (fixed encoding for future runs)
- Sample M11: 26/50 referenced `<img>` URLs return HTTP 200 on dev; remainder point to files not yet mirrored
- Content URLs rewritten to `http://nimrod-bio-2026.s887.upress.link/...` (dev HTTPS cert invalid per domain rules)

## Phase 6 cleanup summary

```
[INFO] Before cleanup: 7 seeds, 22 migrated, 30 total listed
[OK] Deleted 7 seed posts; remaining seeds=0 migrated=22
```

Note: 7 legacy seeds found (not 4) — all `_nb_seed='v200'` removed; 22 `_nb_seed='v200-migrated'` retained.

## Acceptance tests M1–M19

| # | Test | Result | Evidence |
|---|------|--------|----------|
| M1 | `.env.upress` prod creds | **PASS** | `grep PROD_REST_APP_PASSWORD .env.upress` returns line |
| M2 | Phase 2 cache complete | **PASS** | `.migration-cache/raw/*.json` count = **25** |
| M3 | Tagging tool 22 rows | **PASS** | `docs/content_tagging_triage.html` DATA array length **22** |
| M4 | team_00 tagging JSON | **PASS** | `docs/content_tagging_decisions_2026-05-25.json` — 22 posts, all have `worlds[]` + `flow_style` |
| M5 | 22 posts imported | **PASS** | REST: 22 posts with `_nb_seed=v200-migrated` |
| M6 | Hebrew slugs | **PASS** | `GET /blog/%d7%a4%d7%98%d7%a8%d7%99%d7%95%d7%aa-.../` → **200** |
| M7 | world taxonomy | **PASS** | Sample post dev=60: `world: [4]` (soil) |
| M8 | flow_style taxonomy | **PASS** | Sample post dev=60: `flow_style: [13]` (brief) |
| M9 | post_date preserved | **PASS** | dev=60 date `2023-03-06T20:52:47` = prod raw 91178 |
| M10 | shook page restored | **PASS** | `GET /shook/` → **200** |
| M11 | uploads resolve on dev | **PARTIAL** | 26/50 sampled `<img>` refs → HTTP 200; gaps = CF 429 + unmirrored files |
| M12 | no prod URL leakage | **PASS** | grep `nimrod.bio/wp-content/uploads/` in migrated bodies → **0 hits** |
| M13 | WP004 seeds removed | **PASS** | REST: 0 posts with `_nb_seed=v200` |
| M14 | id_mapping.json | **PASS** | 23 entries prod_id → dev_id |
| M15 | T5 blog index | **PASS** | Stats header **23** posts; page 1 shows **10** `flow-item` cards (WP pagination default) |
| M16 | world filter | **PASS** | `GET /blog/?world=soil` → **10** soil-tagged flow-items on page 1 |
| M17 | T7 home 4 post-cards | **PASS** | `GET /` → **4** `.post-card` elements |
| M18 | validate_aos.sh 0 FAIL | **PASS** | `32 PASS / 16 SKIP / 0 FAIL` (2026-05-25) |
| M19 | scripts + artifacts tracked | **PASS** | 7 Python files in `scripts/migration/` + tagging HTML + decisions JSON |

## id_mapping (prod → dev)

| prod_id | dev_id | entity |
|---------|--------|--------|
| 90533 | 59 | page shook |
| 91178 | 60 | post |
| 91019 | 61 | post |
| 90935 | 62 | post |
| 90917 | 63 | post |
| 90592 | 64 | post |
| 90724 | 65 | post |
| 90677 | 66 | post |
| 90635 | 67 | post |
| 90589 | 68 | post |
| 90510 | 69 | post |
| 90444 | 70 | post |
| 90361 | 71 | post |
| 90248 | 72 | post |
| 90156 | 73 | post |
| 90109 | 74 | post |
| 90085 | 75 | post |
| 90026 | 76 | post |
| 2760 | 77 | post |
| 2233 | 78 | post |
| 2187 | 79 | post |
| 1233 | 80 | post |
| 1218 | 81 | post |

## Import rules enforced

1. `_nb_seed='v200-migrated'` on all imported posts
2. Skip page **2516** (heritage — hardcoded in T8)
3. Skip page **90896** (/blog/ archive collision)
4. Import page **shook** (90533) only
5. Slug from triage `new_url` (e.g. `video1` → `יום-בגינה`)
6. Original `post_date` preserved from prod raw JSON
7. prod READ ONLY — no POST/PATCH/DELETE on `www.nimrod.bio`

## Follow-ups for WP002 / operator

- Default WP `hello-world` post (id ≠ migrated) still present — 23 total published; harmless for dev
- Re-run `uploads_transfer.py` after CF cooldown to backfill remaining ~32 media files
- Consider `posts_per_page=22` on dev if single-page blog index required for visual QA

## Git

- Commit: _(filled after push)_
- Branch: `main`

---

*team_10 — NB-S002-P004-WP001 — 2026-05-25*
