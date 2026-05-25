---
type: research_note
author: team_100 (nimrodbio_arch)
date: 2026-05-25
status: v1.0-pre-spec
intended_for: NB-S002-P002-WP001 (LOD400 input)
---

# Custom theme `nimrod-bio-2026` — structure plan

Pre-spec mapping from team_35 design package → WordPress classic theme layout. Becomes the skeleton of LOD400 when NB-S002-P002-WP001 enters L-GATE_SPEC.

## CSS payload inventory (from design package)

| File | Lines | Role |
|---|---|---|
| `brand/system.css` | 182 | Design tokens (colors, spacing, type scale), reset, base components (`.btn`, `.card`, `.grid-6`) |
| `templates/T1-styles.css` | 745 | World pages + Shell/Footer atoms (`.shell-nav`, `.shell-foot`, `.wc`, `.svc-card`, `.proj-card`, `.bridge-card`, `.anchor-card`) — **note: Shell lives here, not in system.css** |
| `templates/T2-styles.css` | 412 | Services (`.three-col`, `.heritage-strip`, `.final-cta`, `.meta-strip`, `.breadcrumb`) |
| `templates/T3-styles.css` | 364 | Projects (`.story`, `.outcomes`, `.gallery`, `.seeking-ribbon`, `.legacy-ribbon`, `.stage-stamp`) |
| `templates/T4-styles.css` | 271 | Single post (3-col aside, ToC, share, entity-link) |
| `templates/T5-styles.css` | 465 | Blog index (filter chips, flow grid, post variants) |
| `templates/T7-styles.css` | 472 | Home (hero variants `[data-hero]`, Unless ribbon, ER diagram) |
| `templates/T8-styles.css` | 556 | About + Heritage + Contact (journey timeline, value tiles, media grid, form) |
| **Total** | **3,467** | — |

## Theme file structure

```
nimrod.bio/wp-content/themes/nimrod-bio-2026/
├── style.css                           # Theme header + @import system.css
├── functions.php                       # Theme bootstrap, asset enqueue, CPT/tax registration
├── theme.json                          # Block editor color palette (sync to system.css tokens)
├── inc/
│   ├── cpt-service.php                 # CPT: service + ACF field group
│   ├── cpt-project.php                 # CPT: project + ACF field group
│   ├── taxonomies.php                  # world (soil/know/code) + flow_style
│   ├── acf-fields.php                  # ACF JSON sync target
│   ├── nav-walker.php                  # Custom Shell nav walker (3-worlds group + secondary)
│   ├── template-helpers.php            # get_world_chip(), get_stage_stamp(), get_bridge_signal()
│   └── enqueue.php                     # Asset enqueue with per-template conditional loading
├── assets/
│   ├── css/
│   │   ├── system.css                  # ← from brand/system.css (verbatim)
│   │   ├── shell.css                   # ← extracted from T1-styles.css (.shell-* blocks)
│   │   ├── t1.css                      # ← T1-styles minus shell
│   │   ├── t2.css … t8.css
│   │   └── critical-home.css           # Inline-able critical CSS for T7
│   ├── js/
│   │   ├── shell.js                    # nav interaction (mobile drawer)
│   │   ├── t5-filter.js                # blog filter chips (vanilla)
│   │   └── t7-hero-tweaks.js           # hero variant switcher (if Tweaks panel kept)
│   ├── fonts/                          # (optional self-host; default = Google Fonts CDN)
│   ├── img/
│   │   ├── bg-soil@{1x,2x}.{png,svg}   # Placeholder until T-03 watercolors delivered
│   │   ├── bg-know@…
│   │   ├── bg-code@…
│   │   ├── bg-blog@…
│   │   └── bg-about@…
│   └── icons/
│       ├── home.svg
│       ├── whatsapp.svg
│       └── share-{copy,whatsapp,email}.svg
├── template-parts/
│   ├── shell-nav.php
│   ├── shell-footer.php
│   ├── card-service.php
│   ├── card-project.php
│   ├── card-post-flow.php              # 7 flow_style variants in one file with switch
│   ├── card-bridge.php                 # bridge signal=seam locked
│   ├── ribbon-seeking.php
│   ├── ribbon-legacy.php
│   ├── heritage-strip.php
│   ├── three-col.php
│   ├── outcomes-tiles.php
│   ├── gallery.php
│   ├── final-cta.php
│   ├── meta-strip.php
│   └── breadcrumbs.php
├── single-service.php                   # T2
├── single-project.php                   # T3
├── single.php                           # T4 (blog post)
├── archive.php                          # T5 (blog index — falls through)
├── page-world-soil.php                  # T1 (one per world, or single template + world detection)
├── page-world-know.php                  # …or alternative: template-tags using slug
├── page-world-code.php
├── front-page.php                       # T7
├── page.php                             # T8 default (about default)
├── page-heritage.php                    # T8 heritage variant
├── page-contact.php                     # T8 contact variant
├── header.php                           # opens HTML + Shell nav
├── footer.php                           # Shell footer + closes HTML
├── 404.php
└── searchform.php                       # (likely unused — no search in design)
```

## Asset loading strategy

**Always loaded (Shell-level):**
- `system.css` (tokens + base)
- `shell.css` (nav + footer)
- Google Fonts preconnect + family link
- `shell.js`

**Conditional per template** (via `wp_enqueue_style` with template detection):

| Template loads | When |
|---|---|
| `t7.css` + critical-inline | `is_front_page()` |
| `t1.css` | `is_page_template('page-world-*.php')` or world taxonomy archive |
| `t2.css` | `is_singular('service')` |
| `t3.css` | `is_singular('project')` |
| `t4.css` | `is_singular('post')` |
| `t5.css` | `is_home() \|\| is_archive() && get_post_type() === 'post'` |
| `t8.css` | `is_page('about','heritage','contact')` |

## CPT + taxonomy registration (matches design §3 spec verbatim)

**`service` CPT** — registered in `inc/cpt-service.php`:
- `public => true`, `has_archive => false`, `rewrite => ['slug' => 'services']`
- Supports: title, editor (lede), thumbnail (hero_image), custom-fields
- ACF field group: `service_fields.json` — slug, tagline, lede, worlds (multi), service_type, stage, is_free, cta_label, cta_whatsapp_href, linked_projects (relationship), related_posts (relationship), sections (repeater: who/how/what), meta_strip (repeater), is_anchor_for_world (select)

**`project` CPT** — registered in `inc/cpt-project.php`:
- `public => true`, `has_archive => false`, `rewrite => ['slug' => 'project']`
- ACF field group: as per design §3 — name_tbc, scope, stage, worlds, year, location, duration, summary, story, linked_services, outcomes (repeater 4 tiles), gallery, more_projects_ids, seeking_note (conditional on stage=seeking-partners), legacy_of (conditional on stage=legacy)

**`world` taxonomy** — `inc/taxonomies.php`:
- Terms: `soil`, `know`, `code`
- `object_type` => `[service, project, post]`
- Hierarchical: no (flat)

**`flow_style` taxonomy** — `inc/taxonomies.php`:
- Terms: `lead`, `wide`, `tall`, `typo`, `quote`, `feature`, `brief`
- `object_type` => `[post]`
- Hierarchical: no

## Permalink structure

Keep `/%postname%/` (current prod setting) — preserves Hebrew slugs and matches design URL patterns:

| Content type | URL |
|---|---|
| Home | `/` |
| World page | `/world/soil/` `/world/know/` `/world/code/` (rewrite endpoint via world taxonomy or static pages — decide in WP002 LOD400) |
| Service | `/services/{slug}/` |
| Project | `/project/{slug}/` (singular — design spec) |
| Post | `/{slug}/` (root level — preserves legacy URLs) |
| Blog index | `/blog/` (page with `front_page_displays` style override — or page-blog.php) |
| Static | `/about/` `/about/heritage/` `/contact/` (heritage as child page) |

## Open architecture questions (for NB-S002-P002-WP001 LOD400)

1. **World page implementation** — static WP pages with `page-world-{slug}.php` templates, or term archive via `taxonomy-world-{slug}.php`? Recommended: static pages for editorial flexibility (anchor card config), pulling content via `WP_Query` on the world taxonomy.
2. **Tweaks panel** — drop unless team_35 confirms it's production-facing.
3. **Critical CSS extraction** — manual inline of `t7-hero` block, or automated via build step (e.g. `critical` npm package)? Recommend manual for v1 (low surface area).
4. **JS bundling** — none (vanilla, per-file enqueue) for v1. Revisit if T5 filtering grows.
5. **Font self-hosting** — Google Fonts CDN for v1 (display=swap). Self-host if PageSpeed flags it.

## Risk flags (theme-level)

- **Hebrew slug routing** — when WP serves `/%postname%/` with Hebrew slug, the URL is auto-encoded to `%d7%9e…`. The custom theme must NEVER manually output decoded Hebrew in `<a href>` — use `get_permalink()` which handles encoding. Validate in P005-WP001 QA.
- **RTL direction** — set `<html dir="rtl" lang="he">` in `header.php`. All CSS already uses logical properties per design package.
- **No half-built fallbacks** — design package locks color/type/spacing. Theme code must not provide alternatives or "safe defaults". If a token is missing, that's a defect to file back to team_35.
