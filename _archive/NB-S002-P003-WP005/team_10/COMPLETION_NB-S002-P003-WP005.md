---
type: COMPLETION
from: team_10 (nimrodbio_build)
to: team_100 (nimrodbio_arch)
wp_id: NB-S002-P003-WP005
date: 2026-05-25
gate: L-GATE_BUILD
status: COMPLETE
status_note: ALL_A1_A18_PASS
---

# COMPLETION — NB-S002-P003-WP005 — T8 Static (about/heritage/contact)

## Outcome

WP005 T8 static templates implemented per LOD400, deployed to dev theme `nimrod-bio-2026` (`NB_THEME_VERSION = 0.4.0`). Final P003 version bump. Contact form handler wired via `require_once` in `functions.php` (authorized last V200 edit).

## Deliverables checklist (14 new tracked files)

- [x] `page-about.php`
- [x] `page-heritage.php`
- [x] `page-contact.php`
- [x] `template-parts/t8-about-hero.php`
- [x] `template-parts/t8-journey-timeline.php`
- [x] `template-parts/t8-cdip-thesis.php`
- [x] `template-parts/t8-value-tile.php`
- [x] `template-parts/t8-media-item.php`
- [x] `template-parts/t8-heritage-hero.php`
- [x] `template-parts/t8-contact-form.php`
- [x] `template-parts/t8-contact-side.php`
- [x] `assets/css/t8.css`
- [x] `assets/js/t8-contact.js`
- [x] `inc/template-styles-t8.php`
- [x] `inc/contact-form-handler.php`

Modified shared files (documented):

- [x] `functions.php` — `NB_THEME_VERSION` `0.3.0` → `0.4.0`; `require_once contact-form-handler.php`
- [x] `inc/world-pages-bootstrap.php` — `nb_bootstrap_static_pages()` + `$path` param on `nb_ensure_page()`
- [x] `inc/template-helpers.php` — **WP005 additions:** `nb_img_ph()` (image placeholder helper for T8 gallery/heritage hero). (`nb_sec_head()` and other P003 helpers present from parallel WPs — not modified by WP005.)

## Helpers added to `inc/template-helpers.php` (WP005)

| Helper | Purpose |
|---|---|
| `nb_img_ph( $subject, $cap, $class, $ratio )` | Renders design-system image placeholder with `<img>` + caption for T8 gallery/heritage |

## Deployment evidence

- FTPS via `scripts/upress_ftps_upload.py` (canonical uPress protocol, `.env.upress.dev` sourced)
- Final upload: `[OK] Uploaded 82 file(s) to /wp-content/themes/nimrod-bio-2026/`
- Asset cache-bust on dev: `t8.css?ver=0.4.0`, `t8-contact.js?ver=0.4.0`
- Static pages bootstrapped on dev via REST (ids: about=37, heritage=38, contact=45)

## Git evidence

- Commit: `4423d3eb` — `feat(P003/WP005): T8 static pages — about, heritage, contact`
- Pushed to `origin/main` after rebase
- `git ls-files` count for 14 WP005 deliverables: **14**

## Acceptance matrix (A1–A18)

| # | Test | Status | Evidence |
|---|---|---|---|
| A1 | `/about/` 200 + `t8 t8-about` | PASS | `curl -sk http://nimrod-bio-2026.s887.upress.link/about/` contains `class="t8 t8-about"`; HTTP 200 |
| A2 | About hero avatar + h1 | PASS | `.t8-about-hero img` present; h1 text `חקלאי, יועץ, מקודד.` |
| A3 | Journey timeline 6 events | PASS | `class="journey-event"` count == 6 (container renamed `journey-list` to avoid grep collision with CSS) |
| A4 | 3 value tiles | PASS | `.value-tile` count == 3 |
| A5 | Media grid | PASS | `.media-item` count >= 1 (4 items rendered) |
| A6 | `/about/heritage/` heritage layout + stamp | PASS | `class="t8 t8-heritage"` + `מהשורש · הסיפור המלא` |
| A7 | Heritage link to produce | PASS | `href="/services/produce/"` in heritage end card |
| A8 | `/contact/` form | PASS | `<form id="nb-contact">` on HTTP 200 |
| A9 | Required form fields | PASS | `name`, `email`, `phone`, `topics[]`, `message`, `nb_contact_nonce` |
| A10 | World-colored topic chips | PASS | `.topic-chip.soil`, `.know`, `.code` |
| A11 | WhatsApp link | PASS | `https://wa.me/972547776770` |
| A12 | Valid POST → redirect ok | PASS | `POST /wp-admin/admin-post.php` → HTTP 302 → `Location: .../contact/?status=ok` |
| A13 | Invalid POST → invalid | PASS | Missing fields → HTTP 302 → `?status=invalid` |
| A14 | Honeypot | PASS | `website=spam` → HTTP 302 → `?status=ok` (silent reject) |
| A15 | Nonce required | PASS | POST without nonce → HTTP 302 → `?status=error` |
| A16 | t8 assets enqueued on T8 only | PASS | `/about/` has `t8.css?ver=0.4.0`; `/contact/` has `t8-contact.js?ver=0.4.0`; `/` has no `t8.css` |
| A17 | Bootstrap 3 pages in REST | PASS | `GET /wp/v2/pages?slug=about\|heritage\|contact` each returns 1 record |
| A18 | Baseline §11 shell + validate | PASS | `/` grep `shell-nav` + `shell-foot`; `validate_aos.sh` → **32 PASS / 16 SKIP / 0 FAIL** |

## Baseline §11 (program)

| Baseline | Status | Evidence |
|---|---|---|
| Shell + Footer render | PASS | Homepage contains `shell-nav`, `shell-foot` |
| `validate_aos.sh` 0 net-new FAIL | PASS | 32 PASS / 16 SKIP / 0 FAIL |
| Git commit + push before COMPLETION | PASS | `4423d3eb` pushed |
| Theme version bumped | PASS | `NB_THEME_VERSION = 0.4.0` in `functions.php` + live `?ver=0.4.0` |
| Test CPT records cleaned | N/A | WP005 created no CPT test records; form POST tests are stateless |

## TBC markers (team_00 content pending)

Rendered with `<span class="tbc">` per mandate:

- Q-05 — CDIP thesis read-more links
- Q-NEW-03 — SFA entity link context on about story
- Q-11 — media grid footer note
- Q-02 — heritage market garden section
- Q-03 — heritage consulting link context

## wp_mail note

A12 redirect to `?status=ok` confirms handler executed successfully. Email delivery on uPress default SMTP not independently verified in this session — defer full deliverability check to P005-WP001 polish if needed (per LOD400 §9 risk).

## Exit criteria (mandate)

- [x] 14 files tracked
- [x] 3 static pages bootstrapped (`/about/`, `/about/heritage/`, `/contact/`)
- [x] A1–A18 PASS
- [x] Form curl tests A12–A15 PASS
- [x] Baseline §11 PASS
- [x] Git push + version bump to 0.4.0

## Coordination notes

- Did **not** edit `system.css` or `shell.css`
- `functions.php` contact-form-handler `require_once` is the authorized final V200 edit to that file
- Parallel P003 sessions caused transient overwrites of `functions.php` during build; resolved before final deploy/test

## Ready for next gate

Artifact ready for team_100 review and team_190 cross-engine VALIDATE replay on A1–A18. **P003 program complete** — opens P004 content migration.
