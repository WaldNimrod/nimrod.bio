---
type: LOD300_PROGRAM_SPEC
program: P003 — Templates
project: nimrod-bio
milestone: V200
author: team_100 (nimrodbio_arch)
date: 2026-05-25
version: v1.0.0
status: ACTIVE
scope: 5 parallel WPs (WP001..WP005)
predecessor: NB-S002-P002-WP002 (COMPLETE — CPTs + taxonomies + meta boxes operational)
---

# LOD300 — P003 Program Spec — 7 templates

This is the **shared layer** for all 5 P003 WPs. Each per-WP LOD400 references this doc and specifies only its template-specific deltas. **Read this first** before any P003 LOD400.

## 1. Scope of P003

| WP | Template(s) | URL pattern | Source design files |
|---|---|---|---|
| WP001 | T7 Home | `/` | `T7 Home.html`, `T7-styles.css`, `T7-data.jsx` (variants locked: hero=`statement`, unless=`ribbon`) |
| WP002 | T1 World (×3) | `/world/soil/`, `/world/know/`, `/world/code/` | `T1 World - אדמה.html`, `T1-styles.css`, `T1-data.jsx`, `T1-variants.jsx` (Variant **C** locked) |
| WP003 | T2 Service + T3 Project | `/services/{slug}/`, `/project/{slug}/` | `T2 Services.html`, `T2-styles.css`, `T2-data.jsx`, `T2-instances.jsx`, `T3 Project.html`, `T3-styles.css`, `T3-data.jsx`, `T3-instances.jsx` |
| WP004 | T4 Post + T5 Blog index | `/blog/{slug}/`, `/blog/` | `T4 Post.html`, `T4-styles.css`, `T5 Blog.html`, `T5-styles.css`, `T4-T5-data.jsx` |
| WP005 | T8 Static (about/heritage/contact) | `/about/`, `/about/heritage/`, `/contact/` | `T8 Static.html`, `T8-styles.css` |

T6 (Portfolio) does NOT exist in scope — removed in Sitemap v3.1.

## 2. Parallel execution model

All 5 WPs can be implemented and validated **in parallel**. Conflict avoidance:

- Each WP adds ONLY its own files. **No WP modifies shared files** except via documented extension points (§4).
- Builders coordinate via dedicated WP folders + per-WP COMPLETION reports.
- team_190 validates one VERDICT per WP — no shared verdict.

**Functions.php discipline:** None of the P003 WPs add `require_once` to functions.php. All extension happens via the `nb_enqueue_template_styles` action (added in WP002) and via WP's template hierarchy.

## 3. Shared deliverable — extended `inc/template-helpers.php`

P003 WP001 (T7 — first WP to start) **owns** the addition of the following helpers to `inc/template-helpers.php`. Subsequent WPs use them; if a helper is missing when a later WP needs it, that WP adds it and notes the addition in its COMPLETION.

```php
// ─── World chip / world label ──────────────────────────────
function nb_world_chip( string $slug, bool $ghost = false ): string {
    $label = esc_html( nb_world_label( $slug ) );
    $cls = 'wc ' . esc_attr( $slug ) . ( $ghost ? ' wc-ghost' : '' );
    return '<span class="' . $cls . '">' . $label . '</span>';
}

// ─── Stage stamp (for ProjCard / project hero / SvcCard) ───
function nb_stage_stamp( string $stage ): string {
    $labels = [
        'seed'             => 'seed',
        'seeking-partners' => 'seeking partners',
        'pilot'            => 'pilot',
        'live'             => 'live',
        'legacy'           => 'legacy',
    ];
    $label = $labels[ $stage ] ?? $stage;
    return '<span class="stage-stamp stage-' . esc_attr( $stage ) . '">' . esc_html( $label ) . '</span>';
}

// ─── Section header (eyebrow + title + lede) ───────────────
function nb_sec_head( int $num, string $eyebrow, string $title, string $lede = '' ): string {
    $out  = '<header class="sec-head">';
    $out .= '<div class="s-eyebrow"><span class="num">§ ' . esc_html( str_pad( (string) $num, 2, '0', STR_PAD_LEFT ) ) . '</span>' . esc_html( $eyebrow ) . '</div>';
    $out .= '<h2 class="s-title">' . esc_html( $title ) . '</h2>';
    if ( $lede ) $out .= '<p class="s-lede">' . esc_html( $lede ) . '</p>';
    $out .= '</header>';
    return $out;
}

// ─── Query helpers for CPTs filtered by world ──────────────
function nb_query_by_world( string $post_type, string $world_slug, int $limit = -1 ): WP_Query {
    return new WP_Query( [
        'post_type'      => $post_type,
        'posts_per_page' => $limit,
        'post_status'    => 'publish',
        'tax_query'      => [
            [
                'taxonomy' => 'world',
                'field'    => 'slug',
                'terms'    => [ $world_slug ],
                'operator' => 'IN',
            ],
        ],
        'no_found_rows'  => true,
    ] );
}

function nb_get_anchor_service_for_world( string $world_slug ): ?WP_Post {
    $q = new WP_Query( [
        'post_type'      => 'service',
        'posts_per_page' => 1,
        'post_status'    => 'publish',
        'meta_query'     => [
            [ 'key' => '_nb_is_anchor_for_world', 'value' => $world_slug ],
        ],
    ] );
    return $q->have_posts() ? $q->posts[0] : null;
}

// ─── Breadcrumb (T2, T3, T4) ───────────────────────────────
function nb_breadcrumb( array $crumbs ): string {
    $out = '<nav class="breadcrumb" aria-label="פירורי לחם"><ol>';
    foreach ( $crumbs as $c ) {
        if ( isset( $c['href'] ) ) {
            $out .= '<li><a href="' . esc_url( $c['href'] ) . '">' . esc_html( $c['label'] ) . '</a></li>';
        } else {
            $out .= '<li aria-current="page">' . esc_html( $c['label'] ) . '</li>';
        }
    }
    $out .= '</ol></nav>';
    return $out;
}
```

Each helper has a single, idempotent purpose. Builders may add MORE helpers as needed; **never modify existing helpers** without a GCR to team_100.

## 4. Asset enqueue — extension point

Each template registers its CSS via the `nb_enqueue_template_styles` action (already wired in `inc/enqueue.php` from WP002 §4.4).

Pattern:

```php
// In template file, e.g. front-page.php top:
add_action( 'nb_enqueue_template_styles', function () {
    if ( is_front_page() ) {
        wp_enqueue_style( 'nb-t7', NB_THEME_URI . '/assets/css/t7.css', [ 'nb-shell' ], NB_THEME_VERSION );
    }
} );
```

This file MUST be loaded before `wp_enqueue_scripts` runs. Easiest: `require_once` of a small `inc/template-styles.php` from `functions.php`, but to honor §2 (no functions.php edits), use this pattern instead:

In **`functions.php`** (single line added by WP002 already, but if not, add via WP001 of P003):
```php
foreach ( glob( NB_THEME_DIR . '/inc/template-styles-*.php' ) as $f ) require_once $f;
```

Then each WP creates `inc/template-styles-{template}.php` (e.g. `template-styles-t7.php`) with the `add_action()` call above. This avoids touching functions.php after WP001 of P003.

**Convention:** the file is named per template (`t1`/`t2`/`t3`/`t4`/`t5`/`t7`/`t8`). Each contains only the conditional enqueue. Total per-WP code in this file: 3–8 lines.

## 5. Template hierarchy mapping

WordPress falls through template hierarchy. For each WP, the spec assigns these template files:

| WP | Files created |
|---|---|
| WP001 T7 | `front-page.php` |
| WP002 T1 | `page-soil.php`, `page-know.php`, `page-code.php` (matched by page slug — works because the 3 world pages have slugs `soil`/`know`/`code`) |
| WP003 T2+T3 | `single-service.php`, `single-project.php` |
| WP004 T4+T5 | `single.php`, `home.php` (blog index — note: WP's `home.php` template handles the posts page when permalink structure has /blog/) |
| WP005 T8 | `page-about.php`, `page-heritage.php`, `page-contact.php` |

`page.php` is not required (we don't have generic pages outside about/heritage/contact in V200). `index.php` from WP002 acts as ultimate fallback.

## 6. CSS extraction conventions

Each per-template CSS file goes in `assets/css/`:
- `t1.css`, `t2.css`, `t3.css`, `t4.css`, `t5.css`, `t7.css`, `t8.css`

**Extraction rule:** Copy the relevant `T*-styles.css` from `sources/team_35_design_package/_handoff/templates/` **verbatim minus the section already covered by `shell.css`** (which extracted the `.shell-*` selectors).

Specifically:
- `shell.css` (already in theme from WP002) owns: `.shell-nav*`, `.shell-foot*`, `.wc`, `.nav-*`
- Each `t{N}.css` owns: everything else from its source file

When in doubt: don't duplicate `wc` (world chip) styles into per-template files. If a `.wc` rule is missing from shell.css, it should be ADDED to shell.css via a coordinated update, not duplicated.

## 7. Hebrew / RTL / A11y baseline (applies to every template)

- All container divs use logical properties (`inset-inline-start/end`, `margin-inline-*`, `padding-inline-*`) — never `left`/`right`.
- All section headers use `<h2 class="s-title">` (the nb_sec_head helper handles this).
- All interactive elements have `:focus-visible` styles (already in system.css).
- All images have meaningful `alt` text — not "TBD" or empty.
- Skip-link present (already in header.php from WP002).
- Color contrast WCAG AA — verified by team_190 in VALIDATE.

## 8. CPT data flow — every template reads from CPTs

Templates **never hardcode content** that exists in CPTs. Examples:

| Element in template | Source |
|---|---|
| Service card title, tagline, world chip | `service` CPT post + `_nb_*` meta + `world` taxonomy |
| Project card title, year, stage, thumbnail | `project` CPT post + meta |
| Post in blog grid/flow | `post` (built-in) + `flow_style` taxonomy + `world` taxonomy |
| Anchor service on world page | `nb_get_anchor_service_for_world( $slug )` |
| 3 featured projects on home (T7) | Configurable — for V200 use latest 3 of `scope=own-venture` with stage=live OR seeking-partners |
| Heritage strip on T2 `produce` | Hardcoded link to `/about/heritage/` (the only exception — content is the same per design §6.T2) |

If a template needs hardcoded content (e.g. T7 hero copy), it lives in the template file as PHP strings — NOT in CPTs. T7 hero is "פיזיקה, אקולוגיה, קוד וחקלאות — אותה מערכת. שלוש זרועות, 3× חיבורים." — keep verbatim from JSX.

## 9. Test data — minimum CPT records for templates to render

Each WP that depends on CPT content must instantiate test instances via REST POST during build. **Cleanup discipline (per WP002-2 lesson):** test records must be deleted before COMPLETION is filed, OR clearly marked `_nb_seed_test=1` and explained in COMPLETION.

Minimum for full V200 build:
- 4 services (one per world + 1 bridge: produce/soil, consulting-hydro/soil+know, sfa/soil+code, consulting-agro/know)
- 3 projects (1 client-case + 1 own-venture + 1 seeking-partners)
- 4 sample posts with different `flow_style` values (lead, wide, tall, brief)

Test data is owned by **WP004 T4+T5** (since blog visibility depends on it) — that WP will create the test instances. Other WPs reuse what's there.

## 10. Out of scope for ALL P003 WPs

- Image generation (T-03 watercolor backgrounds, T-04 logo family) — Team 00 explicit exclusion
- Mobile-specific designs (Stage 5 of team_35) — responsive inferred from desktop tokens
- WXR content import — P004-WP001
- 301 redirects — P004-WP002
- Production cutover — P005-WP002
- Comments / sidebars / search
- Block editor blocks for services/projects

## 11. Acceptance baseline (applies to every P003 WP)

In addition to per-template tests in each LOD400, every WP must verify:

| Baseline | Method |
|---|---|
| Shell + Footer still render | `curl /` + grep `shell-nav` `shell-foot` |
| `validate_aos.sh` | 0 net-new FAILs |
| RTL: no horizontal scroll on 360px viewport | Chrome devtools |
| All `nb_*` helpers used (not duplicated) | Code review |
| Test CPT records cleaned up before COMPLETION | REST DELETE |
| Git commit + push BEFORE COMPLETION | `git ls-files <files>` ≥ expected |
| Theme version bumped (each WP bumps by 0.0.1) | Check `NB_THEME_VERSION` |

## 12. Versioning convention for parallel WPs

To avoid version conflicts when 5 WPs run in parallel:
- WP001 T7: 0.2.1 → 0.3.0
- WP002 T1: 0.3.0 → 0.3.1
- WP003 T2+T3: 0.3.1 → 0.3.2
- WP004 T4+T5: 0.3.2 → 0.3.3
- WP005 T8: 0.3.3 → 0.4.0

If parallel sessions can't coordinate easily — each session checks `git log -1` for current version and bumps from there. Conflicts in functions.php are unlikely since `glob('inc/template-styles-*.php')` is the only common code path and there's no shared file write.

If a conflict occurs (rare): team_100 mediates. Don't rebase blindly — discuss.

## 13. Reference

- LOD300 V200 milestone: `_aos/work_packages/S002/LOD300_V200_milestone.md`
- WP002 LOD400 (theme skeleton): `_aos/work_packages/NB-S002-P002-WP001/LOD400_NB-S002-P002-WP001.md`
- WP002-2 LOD400 (CPTs): `_aos/work_packages/NB-S002-P002-WP002/LOD400_NB-S002-P002-WP002.md`
- Design package handoff: `sources/team_35_design_package/_handoff/00-HANDOFF-claude-code-110.md`
- Design canon: `sources/team_35_design_package/_handoff/brand/` (system.css, voice.md, typography.md)

---

*P003 program spec | nimrod-bio · V200 · 5 parallel template WPs*
