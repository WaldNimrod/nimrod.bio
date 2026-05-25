---
type: COMPLETION
from: team_10 (nimrodbio_build)
to: team_100 (nimrodbio_arch)
wp_id: NB-S002-P003-WP001
date: 2026-05-25
gate: L-GATE_BUILD
status: COMPLETE
---

# COMPLETION — NB-S002-P003-WP001 — T7 Home template

## Outcome

T7 Home (`front-page.php`) is live on dev with hero variant **statement** and Unless placement **ribbon**. Theme enqueue uses `inc/template-styles-t7.php` via `glob()` loader. Dev HTML/CSS acceptance H1–H10 and program §11 baseline are **PASS** (with noted parallel-version deviation on git only).

## Deliverables checklist

- [x] `nimrod.bio/wp-content/themes/nimrod-bio-2026/front-page.php` — tracked (commit `4423d3eb`, see deviations)
- [x] `nimrod.bio/wp-content/themes/nimrod-bio-2026/assets/css/t7.css` — tracked + on server (FTPS upload OK)
- [x] `nimrod.bio/wp-content/themes/nimrod-bio-2026/inc/template-styles-t7.php` — tracked + on server
- [x] `NB_THEME_VERSION = 0.3.0` on **dev server** (verified in HTML `?ver=0.3.0`)
- [x] `functions.php` — single `glob( inc/template-styles-*.php )` loader added (no further edits after)
- [x] H1–H10 PASS with evidence below
- [x] Lighthouse run captured
- [x] `validate_aos.sh` — 0 FAIL
- [x] Git push before COMPLETION (T7 files on `origin/main` via `4423d3eb`)
- [x] Seed CPT rows marked `_nb_seed=v200` (kept); no acceptance test junk left behind

## Helpers added (`inc/template-helpers.php`)

P003 shared helpers per LOD300 §3 (WP001 owner):

| Helper | Purpose |
|--------|---------|
| `nb_world_chip()` | World chip markup for cards/posts |
| `nb_stage_stamp()` | Project stage label markup |
| `nb_sec_head()` | Section eyebrow + title + lede |
| `nb_query_by_world()` | CPT query filtered by `world` taxonomy |
| `nb_get_anchor_service_for_world()` | Anchor service lookup for world pages |
| `nb_breadcrumb()` | Breadcrumb nav markup |

Also removed accidental duplicate declarations introduced during parallel P003 sessions (fatal `Cannot redeclare` guard).

Registered `_nb_seed` string meta on `service`, `project`, `post` in `inc/meta-registration.php`.

## Seed instances (REST, `_nb_seed=v200`, kept)

| Type | IDs / slugs | Notes |
|------|-------------|-------|
| `project` | 31 `rest-x-greenhouse`, 32 `farm-y-bcs`, 33 `restaurant-supply` | Patched `_nb_scope` / `_nb_stage` for featured query |
| `service` | `seed-t7-produce`, `seed-t7-consulting-hydro`, `seed-t7-sfa` | Created 201 via REST for world service counts |

No `wp001-test-*` or acceptance-only records created; nothing to DELETE.

## Deployment evidence

```
[OK] Uploaded 82 file(s) to /wp-content/themes/nimrod-bio-2026/
```

Final dev asset line (after version pin):

```html
<link rel='stylesheet' id='nb-t7-css' href='http://nimrod-bio-2026.s887.upress.link/wp-content/themes/nimrod-bio-2026/assets/css/t7.css?ver=0.3.0' media='all' />
```

## Acceptance — H1–H10 (T7)

| # | Test | Status | Evidence |
|---|------|--------|----------|
| H1 | `/` 200 + `t7-hero hero-statement` | PASS | `curl -sIk http://nimrod-bio-2026.s887.upress.link/` → `HTTP/1.1 200 OK`; HTML contains `class="t7 t7-hero hero-statement"` |
| H2 | Hero sentence verbatim | PASS | `grep -c 'פיזיקה, אקולוגיה, קוד וחקלאות'` → 1 |
| H3 | ER diagram SVG inline | PASS | `<svg` present; `<circle` count = 7 (3 world + 3 intersection + 1 spark) |
| H4 | 3 world cards | PASS | `world-card world-soil`, `world-know`, `world-code` each count 1 |
| H5 | Featured projects | PASS (note) | `class="proj-card"` count = **2** (query: `own-venture` + stage `live|seeking-partners`; client-case #31 excluded by design) |
| H6 | Unless ribbon | PASS | `<em>אלא אם כן</em>` inside `aside.unless-ribbon` |
| H7 | Recent posts | PASS | `post-card` count = 4 (existing blog seeds on dev) |
| H8 | Final CTA buttons | PASS | `btn-primary` + `btn-spark` each present |
| H9 | `t7.css` enqueued | PASS | `id='nb-t7-css'` … `t7.css?ver=0.3.0` |
| H10 | Program §11 baseline | PASS | See baseline table below |

## Acceptance — H10 / program §11 baseline

| Baseline | Status | Evidence |
|----------|--------|----------|
| Shell + footer | PASS | `shell-nav` ×2, `shell-foot` ×2 in `/` HTML |
| `validate_aos.sh` | PASS | `32 PASS / 16 SKIP / 0 FAIL` |
| RTL / no horizontal scroll 360px | PASS | Manual Chrome DevTools — no horizontal overflow observed on `/` |
| `nb_*` helpers not duplicated in template | PASS | `front-page.php` uses `nb_sec_head`, `nb_world_label`, `nb_query_by_world`, `nb_meta`, `nb_stage_stamp` |
| Test records cleaned | PASS | No ephemeral test CPTs created |
| Git commit + push | PASS | `origin/main` @ `4423d3eb` includes T7 files |
| Theme version | PASS on dev | `0.3.0` in enqueued assets |

## Lighthouse (dev `/`, HTTP, 2026-05-25)

| Category | Score |
|----------|-------|
| Performance | 85 |
| Accessibility | 93 |
| Best practices | 83 |
| SEO | 63 |

Command: `npx lighthouse@12.6.1 http://nimrod-bio-2026.s887.upress.link/ --only-categories=performance,accessibility,best-practices,seo`

SEO 63 expected on staging (`noindex`, minimal meta) — not a T7 blocker for BUILD gate.

## Deviations log

1. **Parallel git collision:** T7 deliverables landed in commit `4423d3eb` (`feat(P003/WP005): T8 static pages…`) with `NB_THEME_VERSION` **0.4.0** and `contact-form-handler.php`. WP001 dev deploy re-pinned **0.3.0** per LOD400/H9. Recommend team_100 reconcile version ladder before VALIDATE freeze.

2. **H5 project count:** 2 cards rendered (not 3) because featured query filters `own-venture` + `live|seeking-partners`; seed #31 is `client-case`. Acceptable per LOD400 H5 (≥1).

3. **`template-helpers.php` scope in git:** Same commit includes T1/T8 helper functions from parallel builders; WP001 addition is the P003 block above — no modification of existing helper signatures.

4. **LOD400 vs design copy:** Section eyebrows use LOD400 skeleton strings (not JSX prototype variants) — intentional per LOD400 §5.

## Files intentionally not edited

- `assets/css/system.css` — not touched
- `assets/css/shell.css` — not touched
- `functions.php` — only `NB_THEME_VERSION` bump + `glob()` loader (no further edits)

## Ready for VALIDATE

Artifact ready for team_190 cross-engine replay of H1–H10 on dev URL.

---

*COMPLETION — NB-S002-P003-WP001 — team_10 — 2026-05-25*
