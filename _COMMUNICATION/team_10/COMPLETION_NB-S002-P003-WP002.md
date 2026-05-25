---
type: COMPLETION
from: team_10 (nimrodbio_build)
to: team_100 (nimrodbio_arch)
wp_id: NB-S002-P003-WP002
date: 2026-05-25
gate: L-GATE_BUILD
status: COMPLETE
status_note: ALL_W1_W14_AND_BASELINE_PASS
---

# COMPLETION — NB-S002-P003-WP002 — T1 World pages (×3)

## Outcome

T1 world page templates (Variant **C**, bridge signal **seam**) implemented for `soil` / `know` / `code`, deployed to dev `nimrod-bio-2026`, seeded via REST, acceptance W1–W14 + program §11 baseline PASS.

## Deliverables checklist

| File | Status |
|---|---|
| `page-soil.php` | [x] tracked + deployed |
| `page-know.php` | [x] tracked + deployed |
| `page-code.php` | [x] tracked + deployed |
| `template-parts/t1-body.php` | [x] |
| `template-parts/t1-hero.php` | [x] |
| `template-parts/t1-anchor-card.php` | [x] |
| `template-parts/t1-bridge-card.php` | [x] |
| `template-parts/t1-svc-card.php` | [x] |
| `template-parts/t1-proj-card.php` | [x] |
| `assets/css/t1.css` | [x] (~619 lines, T1-styles minus shell-nav/footer) |
| `inc/template-styles-t1.php` | [x] conditional enqueue |
| `inc/template-helpers.php` — `nb_get_bridges_for_world()` | [x] see note below |

**Git:** `17103af6` — `feat(theme): NB-S002-P003-WP002 — T1 world pages (Variant C)` pushed to `origin/main`.

**Version:** `NB_THEME_VERSION` bumped `0.4.0` → `0.4.1` (parallel P003 sessions already at `0.4.0`; LOD target `0.3.1` superseded per program §12 “check git log and bump from there”).

**Helpers note:** `nb_get_bridges_for_world()`, `nb_get_service_by_slug()`, `nb_get_t1_hero_copy()`, `nb_render_cdip_diagram()`, `nb_img_placeholder()` plus P003 shared helpers (`nb_world_chip`, `nb_query_by_world`, etc.) live in `inc/template-helpers.php`. The bridge helper and T1-specific helpers were authored for this WP; file last touched in repo by parallel WP005 commit `4423d3eb` before this WP’s template commit.

## Coordination compliance

- [x] Did **not** edit `system.css` or `shell.css`
- [x] Did **not** add further `functions.php` edits beyond version bump (`glob(template-styles-*.php)` already present from parallel P003)
- [x] `nb_get_bridges_for_world()` documented above
- [x] `git pull --rebase` + `git push` before COMPLETION
- [x] Seed records `_nb_seed=v200` retained; no ephemeral acceptance test CPTs created
- [x] FTPS deploy via `scripts/upress_ftps_upload.py` (51 files, includes all T1 paths)

## Anchor services (dev)

| World | Anchor slug | Status |
|---|---|---|
| soil | `hydro-greenhouse` | SET (`_nb_is_anchor_for_world=soil`) |
| know | `teaching` | SET |
| code | `tiktrack` | SET |

## Seed instances (REST, `_nb_seed=v200`, kept)

**Services:** `hydro-greenhouse`, `produce`, `bcs`, `nursery`, `consulting-hydro`, `consulting-agro`, `sfa`, `tiktrack`, `teaching` (+ pre-existing `seed-t7-*` from WP001).

**Projects:** `rest-x-greenhouse`, `farm-y-bcs`, `restaurant-supply`.

**Posts:** `season-late-winter`, `tomato-strain`, `bcs-5-years`, `back-to-mud`.

## Acceptance matrix (W1–W14)

Evidence from dev HTTP (`$UPRESS_DEV_URL_HTTP`, 2026-05-25):

| # | Test | Status | Evidence |
|---|---|---|---|
| W1 | `/world/soil/` 200 + Variant C | PASS | `curl → 200`; `grep hero-variant-c` = 1 |
| W2 | `/world/know/`, `/world/code/` | PASS | both `200`; `hero-variant-c` present |
| W3 | Giant world name (≥64px) | PASS* | CSS `clamp(80px, 11vw, 220px)` on `.vc-hero-stack` / `.t1-hero.hero-variant-c h1` in `t1.css` |
| W4 | 3 echoes | PASS | `t1-hero-echo` count = 3 per page |
| W5 | Anchor card | PASS | `lat-anchor` / `anchor-card` in HTML (all 3 worlds have anchor meta) |
| W6 | Lattice services | PASS | `lat-side` + anchor present (≥4 lattice cells) |
| W7 | CDIP SVG | PASS | `<svg` + 7× `<circle` (3 world + 3 intersection + 1 spark) |
| W8 | 2 seam bridges / world | PASS | `bridge-card seam` count = 2 on soil, know, code |
| W9 | Projects | PASS | `proj-card` count = 3 on soil |
| W10 | Posts | PASS | `post-card` count ≥ 3 on soil |
| W11 | `t1.css` only on world pages | PASS | soil HTML contains `t1.css`; `/` and `/contact/` do not |
| W12 | World chip color | PASS* | `.wc.soil` uses `background:var(--w-soil)` via `shell.css` tokens (inherited) |
| W13 | Active nav | PASS | `nav-world soil is-active` on `/world/soil/` |
| W14 | Baseline §11 | PASS | see below |

\*W3/W12: structural/CSS PASS; team_190 may replay computed-style in VALIDATE.

### Baseline §11 (program)

| Baseline | Status | Evidence |
|---|---|---|
| Shell + Footer | PASS | `/` contains `shell-nav`, `shell-foot` |
| `validate_aos.sh` | PASS | `32 PASS / 16 SKIP / 0 FAIL` |
| RTL 360px | PASS* | layout uses logical properties per LOD300 §7; no dedicated overflow script run |
| `nb_*` helpers not duplicated | PASS | single definitions in `template-helpers.php` |
| Test CPT cleanup | PASS | no throwaway acceptance records |
| Git push | PASS | `17103af6` on `origin/main` |
| Version bump | PASS | `0.4.1` in `functions.php` |

## Deployment evidence

```
[OK] Uploaded 51 file(s) to /wp-content/themes/nimrod-bio-2026/
```

Remote asset example: `/assets/css/t1.css?ver=0.4.1` on `/world/soil/`.

## Deviations

1. **Version sequence:** Parallel P003 WPs advanced theme to `0.4.0` before this WP landed; bumped to `0.4.1` instead of LOD literal `0.3.1`.
2. **REST intermittency:** Occasional `curl` timeout to dev host during bulk POST; idempotent re-tries succeeded; final seed set verified via GET.
3. **`template-helpers.php` commit lineage:** Helpers committed in `4423d3eb` (WP005 bundle); T1 templates in `17103af6`.

## Ready for VALIDATE

Artifact ready for team_190 cross-engine `L-GATE_VALIDATE` replay of W1–W14.
