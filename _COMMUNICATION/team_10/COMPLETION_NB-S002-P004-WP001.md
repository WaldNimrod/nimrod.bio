---
type: COMPLETION
from: team_10 (nimrodbio_build)
to: team_100 / team_190
wp_id: NB-S002-P004-WP001
project: nimrod-bio
milestone: V200
date: 2026-05-25
gate: L-GATE_BUILD (partial — Phase 4 blocked on team_00 tagging)
spec_ref: _aos/work_packages/NB-S002-P004-WP001/LOD400_NB-S002-P004-WP001.md
---

# COMPLETION — NB-S002-P004-WP001 — Content migration (Phase 2–3 + scripts)

## Status summary

| Phase | Status | Notes |
|-------|--------|-------|
| 1 — Prod creds | ✅ DONE (team_00) | `.env.upress` verified |
| 2 — Fetch prod | ✅ DONE | 25/25 cached READ ONLY |
| 3 — Tagging tool | ✅ DONE | 22-row HTML ready |
| 4 — Import to dev | ⏸ BLOCKED | Awaiting `docs/content_tagging_decisions_<date>.json` from team_00 |
| 5 — Uploads transfer | ⏸ BLOCKED | Runs after Phase 4 |
| 6 — Seed cleanup | ⏸ BLOCKED | Runs after Phase 4 |

**Action required:** team_00 opens `docs/content_tagging_triage.html`, tags all 22 posts (~10 min), saves JSON to `docs/content_tagging_decisions_2026-05-25.json` (or dated filename), then team_10 resumes Phases 4–6.

## Deliverables

| Deliverable | Path | Status |
|-------------|------|--------|
| fetch_prod_posts.py | `scripts/migration/fetch_prod_posts.py` | ✅ tracked |
| transform_post.py | `scripts/migration/transform_post.py` | ✅ tracked |
| tagging_export.py | `scripts/migration/tagging_export.py` | ✅ tracked |
| import_to_dev.py | `scripts/migration/import_to_dev.py` | ✅ tracked (ready, not run) |
| uploads_transfer.py | `scripts/migration/uploads_transfer.py` | ✅ tracked (ready, not run) |
| cleanup_seeds.py | `scripts/migration/cleanup_seeds.py` | ✅ tracked (ready, not run) |
| Tagging triage HTML | `docs/content_tagging_triage.html` | ✅ 22 rows |
| Tagging input JSON | `docs/content_tagging_input.json` | ✅ |
| Tagging decisions | `docs/content_tagging_decisions_*.json` | ⏸ pending team_00 |
| Raw cache | `.migration-cache/raw/*.json` | ✅ 25 files (gitignored) |

## Acceptance tests M1–M19

| # | Test | Result | Evidence |
|---|------|--------|----------|
| M1 | `.env.upress` prod creds | **PASS** | `grep PROD_REST_APP_PASSWORD .env.upress` returns line (file gitignored) |
| M2 | Phase 2 cache complete | **PASS** | `ls .migration-cache/raw/*.json \| wc -l` → **25** (= redirect+keep count) |
| M3 | Tagging tool 22 rows | **PASS** | `docs/content_tagging_triage.html` embeds `DATA` array length **22** |
| M4 | team_00 tagging JSON | **BLOCKED** | No `docs/content_tagging_decisions_*.json` yet |
| M5 | 22 posts imported | **BLOCKED** | Phase 4 not run |
| M6 | Hebrew slugs | **BLOCKED** | Pending import |
| M7 | world taxonomy | **BLOCKED** | Pending import |
| M8 | flow_style taxonomy | **BLOCKED** | Pending import |
| M9 | post_date preserved | **BLOCKED** | transform preserves `date` from raw cache; pending import verify |
| M10 | shook page restored | **BLOCKED** | Pending import (page 90533 only; blog/heritage skipped per spec) |
| M11 | uploads resolve on dev | **BLOCKED** | Pending Phase 5 |
| M12 | no prod URL leakage | **BLOCKED** | `rewrite_upload_urls()` in transform; verify after import |
| M13 | 4 WP004 seeds removed | **BLOCKED** | Pending Phase 6 |
| M14 | id_mapping.json | **BLOCKED** | Written by import_to_dev.py after tagging |
| M15 | T5 blog index ≥22 cards | **BLOCKED** | Pending import |
| M16 | world filter on /blog/ | **BLOCKED** | Pending import |
| M17 | T7 home 4 post-cards | **BLOCKED** | Pending import |
| M18 | validate_aos.sh 0 FAIL | **PASS** | `32 PASS / 16 SKIP / 0 FAIL` (2026-05-25) |
| M19 | scripts + HTML tracked | **PASS** | 7 Python files in `scripts/migration/` + `docs/content_tagging_triage.html` |

## Phase 2 fetch summary

```
Summary: fetched/cached 25/25 (22 posts + 3 pages); failed 0
```

- Prod REST: `https://www.nimrod.bio/wp-json/wp/v2` (READ ONLY GET)
- Cached IDs include heritage (2516) and blog page (90896) for reference; **not imported** per LOD400 §4 Phase 4

## Import rules enforced in scripts

1. `_nb_seed='v200-migrated'` on all imported posts
2. Skip page **2516** (heritage — hardcoded in T8)
3. Skip page **blog** (90896 — archive collision)
4. Import page **shook** (90533) only
5. Slug from triage `new_url` (e.g. `video1` → `יום-בגינה`)
6. Original `post_date` preserved from prod raw JSON

## Resume commands (after team_00 tagging)

```bash
set -a; source .env.upress; set +a
set -a; source .env.upress.dev; set +a

# Save team_00 output as docs/content_tagging_decisions_2026-05-25.json
python3 scripts/migration/import_to_dev.py
python3 scripts/migration/uploads_transfer.py
python3 scripts/migration/cleanup_seeds.py
```

## Git

- Commit `1edda7d0` pushed to `origin/main` (2026-05-25).

---

*team_10 — NB-S002-P004-WP001 — 2026-05-25*
