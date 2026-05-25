---
type: COMPLETION
from: team_10 (nimrodbio_build)
to: team_100 (nimrodbio_arch)
wp_id: NB-S002-P003-WP004
date: 2026-05-25
gate: L-GATE_BUILD
status: COMPLETE
status_note: B1-B14_PASS_EXCEPT_B7_KNOWN_SEED_CONFLICT
---

# COMPLETION — NB-S002-P003-WP004 — T4 Post + T5 Blog

## Outcome

T4 (`single.php`) and T5 (`home.php`) implemented, deployed to dev theme `nimrod-bio-2026`, 4 seed posts created via REST with `_nb_seed=v200`. Theme version on dev after parallel P003 cascade: **0.4.1** (WP005 landed before push; WP004 files enqueue with active version).

## Deliverables checklist (14 tracked files)

- [x] `nimrod.bio/wp-content/themes/nimrod-bio-2026/single.php`
- [x] `nimrod.bio/wp-content/themes/nimrod-bio-2026/home.php`
- [x] `nimrod.bio/wp-content/themes/nimrod-bio-2026/template-parts/t4-aside.php`
- [x] `nimrod.bio/wp-content/themes/nimrod-bio-2026/template-parts/t4-share.php`
- [x] `nimrod.bio/wp-content/themes/nimrod-bio-2026/template-parts/t5-filter-bar.php`
- [x] `nimrod.bio/wp-content/themes/nimrod-bio-2026/template-parts/t5-post-flow.php`
- [x] `nimrod.bio/wp-content/themes/nimrod-bio-2026/template-parts/t5-post-grid.php`
- [x] `nimrod.bio/wp-content/themes/nimrod-bio-2026/assets/css/t4.css`
- [x] `nimrod.bio/wp-content/themes/nimrod-bio-2026/assets/css/t5.css`
- [x] `nimrod.bio/wp-content/themes/nimrod-bio-2026/assets/js/t5-filter.js`
- [x] `nimrod.bio/wp-content/themes/nimrod-bio-2026/inc/template-styles-t4-t5.php`
- [x] `scripts/seed_wp004_posts.py` (REST seed helper)
- [x] `nimrod.bio/wp-content/themes/nimrod-bio-2026/inc/template-helpers-t4-t5.php` (WP004 helpers — see below)
- [x] `nimrod.bio/wp-content/themes/nimrod-bio-2026/inc/taxonomies.php` (minimal exception for `/blog/?world=` — see Deviations)

## Helpers added (WP004)

File: `inc/template-helpers-t4-t5.php` (loaded by `template-styles-t4-t5.php`):

| Helper | Purpose |
|---|---|
| `nb_extract_toc()` | LOD400 §4 — PHP regex ToC from `<h2 id="...">` |
| `nb_prepare_post_body_html()` | Auto-inject h2 ids + `§ NN` numbered prefix |
| `nb_get_post_world_slugs()` | World taxonomy slugs for post |
| `nb_get_post_flow_style()` | flow_style slug (default `feature`) |
| `nb_post_read_label()` | `_nb_read_time` meta or default |
| `nb_post_featured_image()` | Thumbnail or `nb_img_ph` placeholder |
| `nb_get_related_posts()` | Related posts by shared world |

## Seed posts (kept — `_nb_seed=v200`)

| ID | Slug | flow_style | worlds |
|---|---|---|---|
| 48 | `mabat-achorah-shoresh-echad` | lead | soil, know, code |
| 50 | `madrikh-mahir-chamama` | wide | soil |
| 51 | `sfa-kod-shemsaay` | tall | code |
| 52 | `nimrod-bio-welcome` | brief | soil |

## Dev bootstrap performed

Reading settings were missing posts page (WP001 gap). Created via REST:

- Page `blog` (id=54) → `page_for_posts`
- Page `home` (id=55) → `page_on_front`
- `show_on_front=page` → `/blog/` routes to `home.php`, `/` stays T7 `front-page.php`

## Deployment evidence

- FTPS: `[OK] Uploaded 82 file(s) to /wp-content/themes/nimrod-bio-2026/` (final deploy)
- Asset URLs (dev):
  - `/blog/mabat-achorah-shoresh-echad/` → `t4.css?ver=0.4.1`
  - `/blog/` → `t5.css?ver=0.4.1`, `t5-filter.js?ver=0.4.1`

## Acceptance matrix (B1–B14)

| # | Test | Status | Evidence |
|---|---|---|---|
| B1 | ≥4 posts via REST | PASS | `GET /wp-json/wp/v2/posts?per_page=10` → 8 publish (includes parallel seeds + 4 WP004 seeds) |
| B2 | flow_style + `_nb_seed` per sample | PASS | slugs `mabat-*`, `madrikh-*`, `sfa-*`, `nimrod-bio-welcome` → `_nb_seed=v200`, flow_style term IDs assigned |
| B3 | `/blog/` 200 + T5 flow + chips | PASS | HTTP 200; grep `t5-flow`, `filter-chip`, `data-world` |
| B4 | `/blog/?view=grid` | PASS | grep `t5-grid`, `posts-grid`, `blog-featured` |
| B5 | `/blog/?world=soil` count matches REST | PASS | HTML `post-flow-*` count=6 matches `GET /posts?world=4` count=6 |
| B6 | Multi-world `?world=soil,code` | PASS | HTTP 200; union renders 7 flow cards |
| B7 | Empty state `?world=know` | **FAIL (spec/seed conflict)** | Lead seed includes `know` → 1+ posts render; empty-state markup present in `home.php` but not triggered with current seeds |
| B8 | 4 flow_style CSS classes | PASS | `post-flow-lead`, `post-flow-wide`, `post-flow-tall`, `post-flow-brief` in `/blog/` HTML |
| B9 | Single post T4 layout | PASS | `/blog/mabat-achorah-shoresh-echad/` → `t4-body`, `post-aside`, `toc-list` |
| B10 | ToC from h2s | PASS | `href="#why-closed"`, `href="#unless"` in aside ToC |
| B11 | Share buttons | PASS | 3× `share-btn`, `wa.me` href present |
| B12 | t4.css + t5.css conditional | PASS | t4 on single; t5 on blog index only (`?ver=0.4.1`) |
| B13 | t5-filter.js on `/blog/` only | PASS | `t5-filter.js?ver=0.4.1` on `/blog/`; absent on single |
| B14 | Baseline §11 | PASS | `/` shell-nav + shell-foot; `validate_aos.sh` → `L-GATE_BUILD EXIT CRITERION: SATISFIED` |

## JS-disabled fallback

Filter chips are `<button>` elements; server-side `pre_get_posts` tax_query on `?world=` works without JS. Verified `/blog/?world=soil` returns filtered HTML with HTTP 200 after taxonomies fix.

## Git evidence

- Local commit: `58af7f61` — `feat(P003/WP004): T4 post + T5 blog templates with seed data.`
- **Push:** failed (`could not read Username for https://github.com`) — commit is local; parent/user must `git push origin main` with credentials.
- `git ls-files` includes all 12 theme deliverables above.

## Deviations log

1. **Version target 0.3.3 → deployed 0.4.1:** Parallel WP005 bumped `NB_THEME_VERSION` before WP004 push; no downgrade applied.
2. **`inc/taxonomies.php` edit:** WP002 `template_redirect` returned 404 for any `?world=` query, breaking T5 filter (B5–B6). Added exception when path is `blog` or `blog/*`.
3. **Reading settings bootstrap:** Created `home` + `blog` pages + REST settings update (WP001 gap).
4. **B7 empty state:** LOD400 seed table assigns `know` to lead post, conflicting with B7 empty-state test for `?world=know`. Recommend team_100 clarify or adjust seed worlds in VALIDATE.

## Test record cleanup

No ephemeral B1–B14 test CPT/posts created beyond the 4 mandated seeds. Deleted stale `back-to-mud` (id=46) before seeding.

## Exit criteria (mandate)

- [x] 10+ files tracked (14)
- [x] 4 seed posts in REST with `_nb_seed=v200`
- [x] B1–B6, B8–B14 PASS
- [ ] B7 PASS (blocked by seed/spec conflict — documented)
- [x] JS-disabled URL filter works
- [x] baseline §11 PASS
- [x] git commit (push pending auth)

## Ready for VALIDATE

Artifact ready for team_190 cross-engine replay. Recommend B7 adjudication against seed table vs acceptance row.
