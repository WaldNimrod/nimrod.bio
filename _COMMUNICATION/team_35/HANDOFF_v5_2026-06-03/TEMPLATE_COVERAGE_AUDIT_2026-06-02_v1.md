---
type: AUDIT
from: team_35 (Site Design + Build — Claude Design)
to: team_00 (Nimrod), team_100
project: nimrod-bio
wp_id: NB-S002-P009-WP001
date: 2026-06-02
status: COVERAGE MAP — awaiting team_00 priority call
trigger: team_00 note 2026-06-02 — "several templates not yet created, needed for the site interface"
---

# TEMPLATE COVERAGE AUDIT — design-system ↔ theme ↔ precision pass

Grounded in the theme source (`themes/nimrod-bio-2026/`) + CPT registration + the v4 precision pass.

## Coverage map

| Design template | Theme file | Route | v4 precision pass | Status |
|---|---|---|---|---|
| T7 · Home | `front-page.php` | `/` | ✅ walked + §06 built | **covered** |
| T1 · World (אדמה/soil) | `page-soil.php` | `/world/soil/` | ✅ walked | **covered** |
| T1 · World (ידע/know) | `page-know.php` | `/world/know/` | ❌ not checked | exists · parity check needed |
| T1 · World (דיגיטל/code) | `page-code.php` | `/world/code/` | ❌ not checked | exists · parity check needed |
| T2 · Services index | `archive-service.php` | `/services/` | ❌ not in v4 | exists · not precision-scanned |
| T2 · Service single | `single-service.php` | `/services/{slug}/` | ❌ not in v4 | exists · not precision-scanned |
| T3 · Project single | `single-project.php` | `/project/{slug}/` | ❌ not in v4 | exists · not precision-scanned |
| **Projects archive** | **— none —** | `/projects/` | ❌ | **GAP · template missing** |
| T4 · Post single | `single.php` | `/{post}/` | ❌ not in v4 | exists · not precision-scanned |
| T5 · Blog index | `home.php` | `/blog/` | ❌ not in v4 (§06 teaser built on home) | exists · not precision-scanned |
| T8 · About | `page-about.php` | `/about/` | ✅ walked | **covered** |
| T8 · Contact | `page-contact.php` | `/contact/` | ✅ walked | **covered** |
| T8 · Heritage | `page-heritage.php` | `/heritage/` | ❌ not checked | exists · not checked |
| System · 404 | `404.php` | — | ✅ walked | **covered** |
| System · Search | `search.php` | `/?s=` | ✅ walked | **covered** |

## Gaps + needs (priority order)

### G1 · Projects archive `/projects/` — MISSING template (real gap, P1)
- `inc/cpt-project.php` registers `project` with **`has_archive => false`** — so `/projects/`
  resolves to nothing (links in home §05 "לכל הפרויקטים" + T1 point here).
- By contrast `service` has `has_archive => 'services'` → `archive-service.php` exists. Project has no equivalent.
- **Fix:** set `has_archive => 'projects'` in `cpt-project.php` + add `archive-project.php`.
  Design already exists — reuse the `.projects-row` / `.proj-card` grid (scope + stage stamps)
  from T7 §05 / T1 §03; add the world/scope/stage filter affordance. No new visual language needed.

### G2 · Templates designed but not yet precision-scanned in v4
T2 (services index + single), T3 (project single), T4 (post single), T5 (blog index) all exist as
theme PHP **and** as standalone design files (`T2 Services.html` … `T5 Blog.html`), but were **not**
part of the 6-screen v4 precision pass. They need the same screen-by-screen fidelity walk.

### G3 · World-page variants (know / code) + Heritage
`page-know.php`, `page-code.php` reuse the T1 template; only אדמה/soil was visually walked. Quick
parity check at 375/1440. `page-heritage.php` (T8) likewise unwalked.

### Not a build gap (noted)
- **World journal (`vc-posts`)** empty — content-seeding (no posts tagged to `world` yet), per
  prior team_00 ruling. A `taxonomy-world.php` archive is optional, not required for launch.

## Ask
Which do you want next? Suggested order: **G1 (build the projects archive — the only truly missing
template)** → **G2 (extend the precision walk to T2–T5)** → **G3 (know/code/heritage parity)**.
Say the word and I'll build G1 to mockup structure (markup patch + module CSS, same as §06) and/or
continue the walk into T2–T5.

---

*team_35 | template coverage audit | 2026-06-02 | 1 real gap (projects archive) + precision-pass extension*
