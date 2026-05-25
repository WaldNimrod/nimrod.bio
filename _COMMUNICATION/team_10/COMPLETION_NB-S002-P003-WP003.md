---
type: COMPLETION
from: team_10 (nimrodbio_build)
to: team_100 (nimrodbio_arch)
wp_id: NB-S002-P003-WP003
date: 2026-05-25
gate: L-GATE_BUILD
status: COMPLETE
status_note: ALL_S1_S15_PASS
---

# COMPLETION — NB-S002-P003-WP003 — T2 Services + T3 Projects

## Outcome

WP003 delivered `single-service.php` (T2) and `single-project.php` (T3) with 10 template-parts, `t2.css` / `t3.css`, conditional enqueue, six `_nb_seed=v200` REST instances, FTPS deploy to dev, acceptance S1–S15 PASS, git push (`a32a4934`).

## Deliverables checklist (13 theme files + tooling)

| # | File | Status |
|---|---|---|
| 1 | `single-service.php` | ✅ tracked + deployed |
| 2 | `single-project.php` | ✅ tracked + deployed |
| 3 | `template-parts/t2-hero.php` | ✅ |
| 4 | `template-parts/t2-three-col.php` | ✅ |
| 5 | `template-parts/t2-heritage-strip.php` | ✅ |
| 6 | `template-parts/t2-meta-strip.php` | ✅ |
| 7 | `template-parts/t2-final-cta.php` | ✅ |
| 8 | `template-parts/t3-story.php` | ✅ |
| 9 | `template-parts/t3-outcomes.php` | ✅ |
| 10 | `template-parts/t3-gallery.php` | ✅ |
| 11 | `template-parts/t3-seeking-ribbon.php` | ✅ |
| 12 | `template-parts/t3-legacy-ribbon.php` | ✅ |
| 13 | `assets/css/t2.css` | ✅ |
| 14 | `assets/css/t3.css` | ✅ |
| 15 | `inc/template-styles-t2-t3.php` | ✅ |
| + | `scripts/seed_wp003_instances.py` | ✅ upsert/idempotent seed |
| + | `scripts/run_wp003_acceptance.sh` | ✅ acceptance runner |

`git ls-files` count for WP003 theme paths: **15** (+ 2 scripts in commit).

## Helpers added / used (`inc/template-helpers.php`)

WP003 relies on P003 shared helpers (already present from parallel P003 sessions) plus these additions documented here:

| Helper | Purpose |
|---|---|
| `nb_get_project_by_slug()` | Resolve linked project cards on T2 |
| `nb_json_meta()` | Decode `_nb_sections` / `_nb_outcomes` JSON |
| `nb_bridge_style_attr()` | Inline `--bridge-a/b` for bridge heroes/cards |
| `nb_service_breadcrumb_crumbs()` | T2 breadcrumb data |
| `nb_project_breadcrumb_crumbs()` | T3 breadcrumb data |
| `nb_render_tbc()` | Name-TBC badge markup |
| `nb_whatsapp_icon_svg()` | WhatsApp CTA icon on produce |

**Not modified:** `system.css`, `shell.css`, `functions.php` (glob already present from WP001; version owned by parallel P003 cascade — see below).

## Meta registration extensions (`inc/meta-registration.php`)

REST-exposed fields added for richer seed + templates:

- **service:** `_nb_cta_hint`, `_nb_cta_final_h`, `_nb_cta_final_p`, `_nb_hero_facts`
- **project:** `_nb_seeking_cta_*`, `_nb_outcomes_note`
- **both:** `_nb_seed` (already registered for all CPTs)

## Deployment evidence

- FTPS: `python3 scripts/upress_ftps_upload.py` → `[OK] Uploaded 82 file(s) to /wp-content/themes/nimrod-bio-2026/` (includes WP003 + parallel P003 artifacts already on disk).
- Live CSS enqueue (produce): `nb-t2-css` present, `nb-t3-css` absent.
- Live CSS enqueue (project): `nb-t3-css` present, `nb-t2-css` absent.
- Asset cache param on dev at test time: `?ver=0.3.0` (theme constant from parallel WP001 deploy; see version note).

## Seed instances (`_nb_seed=v200` — kept on dev)

| Type | Slug | REST ID | Stage / scope |
|---|---|---:|---|
| service | `produce` | 22 | soil · anchor |
| service | `consulting-hydro` | 26 | soil×know bridge |
| service | `sfa` | 28 | soil×code · free |
| project | `rest-x-greenhouse` | 31 | client-case · live |
| project | `hagina-shel-nimrod` | 49 | own-venture · legacy |
| project | `coop-sharon` | 53 | own-venture · seeking-partners |

Verify:

```bash
source .env.upress.dev
curl -sk -u "$WP_REST_USER:$WP_REST_APP_PASSWORD" \
  "$WP_REST_BASE_URL/wp/v2/services?slug=produce&_fields=slug,meta._nb_seed"
curl -sk -u "$WP_REST_USER:$WP_REST_APP_PASSWORD" \
  "$WP_REST_BASE_URL/wp/v2/projects?slug=coop-sharon&_fields=slug,meta._nb_seed"
```

**Note:** Other `_nb_seed=v200` records from parallel WPs (T7 seeds, T1 extras) remain untouched per coordination rules.

## Acceptance matrix S1–S15

| Test | Status | Evidence |
|---|---|---|
| S1 | PASS | REST slugs `produce`, `consulting-hydro`, `sfa` each return `meta._nb_seed=v200` |
| S2 | PASS | REST slugs `rest-x-greenhouse`, `hagina-shel-nimrod`, `coop-sharon` each return `meta._nb_seed=v200` |
| S3 | PASS | `GET /services/produce/` → HTTP 200; HTML contains `single-hero` |
| S4 | PASS | `GET /services/consulting-hydro/` → `bridge-hero` + `bridge-stripe seam` |
| S5 | PASS | `GET /services/sfa/` → `sfa-origin-flow` / `Origin · 3 שלבים` |
| S6 | PASS | produce → `t2-heritage-strip` + link `/about/heritage/` |
| S7 | PASS | consulting-hydro → no `t2-heritage-strip` |
| S8 | PASS | `GET /project/rest-x-greenhouse/` → `class="outcomes"` (4 tiles after meta update id=31) |
| S9 | PASS | `GET /project/coop-sharon/` → `t3-seeking-ribbon` |
| S10 | PASS | coop-sharon → `התוכנית` section (not outcomes header) |
| S11 | PASS | hagina-shel-nimrod → `קשור · מיזמים אחרים` |
| S12 | PASS | Both templates render `<nav class="breadcrumb">` via `nb_breadcrumb()` |
| S13 | PASS | produce has `nb-t2-css` only; rest-x-greenhouse has `nb-t3-css` only |
| S14 | PASS | `stage-stamp stage-live` on rest-x-greenhouse; `stage-seeking-partners` on coop-sharon |
| S15 | PASS | Baseline: `/` shell-nav + shell-foot; `validate_aos.sh` → `32 PASS / 16 SKIP / 0 FAIL` |

Runner: `bash scripts/run_wp003_acceptance.sh` (manual re-run 2026-05-25 — all checks green after project seed + outcomes meta).

## Test record cleanup

No ephemeral S1–S15 **test** CPT rows were created during this WP. Only the six seed instances above (plus pre-existing parallel seeds) remain.

## Git evidence

- Commit: `a32a4934` — `feat(P003/WP003): T2 services + T3 project templates with seed data.`
- Push: `origin/main` updated (`7bc18350..a32a4934`)
- Pre-push: `git pull --rebase origin main` (unrelated script edits stashed)

## Version note (NB_THEME_VERSION)

Mandate target for WP003: **0.3.2**. Parallel P003 sessions already bumped the deployed theme constant (`functions.php` on disk at completion: **0.4.1** from WP005 track). WP003 did **not** edit `functions.php` per coordination rule (glob-only extension point). Cache bust for `t2.css`/`t3.css` is active via the deployed theme version param.

## Exit criteria

- [x] 13+ theme files tracked
- [x] 6 seed instances verified via REST GET
- [x] S1–S15 PASS
- [x] Baseline §11 PASS (`validate_aos.sh` 0 FAIL)
- [x] git push complete

## Ready for L-GATE_VALIDATE

Cross-engine replay of S1–S15 + shell regression recommended on dev URL `http://nimrod-bio-2026.s887.upress.link`.
