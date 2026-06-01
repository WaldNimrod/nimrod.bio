# COMPLETION — QA V200B Fixes (F-002/F-003/F-004) — team_35 — v1

**Date:** 2026-06-01
**Author:** team_35 (Site Design + Build)
**To:** team_100 (Chief Architect)
**Type:** COMPLETION
**Source mandate:** `_COMMUNICATION/team_35/MANDATE_QA_FIXES_V200B_2026-06-01_v1.md`
**Version deployed:** 0.7.13 (bumped from 0.7.12)

---

## §0 Result

| Finding | Status |
|---------|--------|
| F-003 gallery overflow | **FIXED + DEPLOYED** |
| F-002 /services/ 404 | **FIXED + DEPLOYED** |
| F-004 public email | **FIXED + DEPLOYED** |
| BCS gallery section | **SKIPPED** (scope) |

All 7 changed files: byte-parity PASS. Version 0.7.13 live on dev.

---

## §1 F-003 — Gallery horizontal overflow

### Root cause (confirmed)
`wp_get_attachment_image()` renders `<img width="1024" height="684" class="img-ph g-wide">` — the `<img>` IS the `.img-ph` element. The existing rule `.img-ph > img { width:100%; height:100%; object-fit:cover; }` only targets an img that is a **child** of `.img-ph`; it does not fire when the img carries the `.img-ph` class directly. No global `img { max-width:100% }` reset existed in the theme. The HTML `width="1024"` attribute caused tiles to render at intrinsic 1024px, blowing the grid to ~4294px (desktop) / ~2082px (mobile375).

### Fix applied
**File:** `assets/css/t3.css` — added after the `.gallery .g-square` rule:

```css
/* F-003: when wp_get_attachment_image renders the <img> as the .img-ph element
   itself (not a child), constrain it to its grid cell.
   Scoped to .gallery only — does not affect logos, emblems, or other images. */
.gallery img.img-ph,
.gallery .img-ph > img {
  display: block;
  width: 100%;
  height: 100%;
  object-fit: cover;
  max-inline-size: 100%;
}
```

### Scope decision — why NOT a global img reset
The theme uses `<img>` elements with explicit CSS pixel dimensions for:
- `.shell-mark .basket` (36×36px) — nav basket logo SVG
- `.nb-emblem > img` (36×36px), `.t7-worlds .world-card .nb-emblem .em-ic` (25×25px)
- `.manifesto .mf-emblem img` (38×38px)
- `.about-hero .ah-emblem img` (36×36px)
- Footer logo `<img class="logo" width="200" height="64">`

A `height: auto` global reset would override these. The gallery-scoped rule is safe: it targets only `.gallery img.img-ph` and `.gallery .img-ph > img`, which are exclusively gallery tile images. All emblem/logo images are outside `.gallery` context.

### F-003 verification
- **Method:** REASONED — no Chrome headless available in this session. Verification based on:
  1. CSS rule confirmed live on server: `curl -k .../t3.css?ver=0.7.13` returns the `.gallery img.img-ph` rule.
  2. The HTML attribute `width="1024"` is overridden by CSS `width:100%` (CSS specificity wins over HTML attributes). The grid cells have `aspect-ratio` set; `width:100%` fills the cell, `height:100%` + `object-fit:cover` fills the aspect-ratio box.
  3. The intrinsic-width blowout mechanism identified by team_50 is now closed.
- **team_100 / team_50 browser re-verify is the authoritative scrollWidth check** for final sign-off.

---

## §2 F-002 — /services/ 404

### Fix applied
Two changes:

**1. `inc/cpt-service.php`** — enabled the archive:
```php
'has_archive' => 'services',  // was: false
```

**2. Created `archive-service.php`** (new file in theme root) — archive template that:
- Queries all published `service` CPT posts (7 slugs: bcs, teaching, hydro-greenhouse, consulting-agro, consulting-hydro, nursery, produce)
- Renders each via `get_template_part('template-parts/t1-svc-card')` — the existing design-system card
- Uses `.services-grid` (inline 3-column grid) for layout consistent with world pages
- On-voice heading: "שירותים" / "יעוץ, הוראה, ייצור וגידול — מגוון הפעילויות של נמרוד ולד"
- Empty-state via `.empty-state` if no posts

**3. `inc/template-styles-t1.php`** — added `is_post_type_archive('service')` to the `t1.css` enqueue condition so `.svc-card` styles load on the archive:
```php
if ( is_page( array( 'soil', 'know', 'code' ) ) || is_post_type_archive( 'service' ) ) {
```

**Rewrite flush:** `inc/rewrites.php` already flushes on `NB_THEME_VERSION` change. Version bump to 0.7.13 triggers the flush on first request — confirmed working (page returns 200).

### F-002 verification
- `/services/?nc=14663` → HTTP **200** (executed, `curl -k -L`)
- Page renders 7 service card links: `/services/bcs/`, `/services/teaching/`, `/services/hydro-greenhouse/`, `/services/consulting-agro/`, `/services/consulting-hydro/`, `/services/nursery/`, `/services/produce/` — all present in rendered HTML.
- `/services/bcs/` → HTTP **200** (confirmed, not broken by archive registration).

---

## §3 F-004 — Public email removed

### Fix applied
Removed the email card from contact sidebar and the footer link:

**`template-parts/t8-contact-side.php`** — removed the entire "אימייל ישיר" card block:
```html
<!-- REMOVED:
<div class="contact-card">
  <div class="label">אימייל ישיר</div>
  <p>אם מעדיף אימייל פרטי, או יש לך קובץ לשלוח.</p>
  <a href="mailto:nimrod@nimrod.bio" class="direct">nimrod@nimrod.bio</a>
</div>
-->
```
WhatsApp card, מיקום, רשתות — all retained. Layout is clean (no empty card placeholder).

**`template-parts/shell-footer.php`** — removed the mailto link from the "קשר" column:
```html
<!-- REMOVED: <a href="mailto:nimrod@nimrod.bio">nimrod@nimrod.bio</a> -->
<!-- KEPT: <a href=".../contact/">צור קשר</a> -->
```

### F-004 verification (executed — `curl -k -L`, counts)
| Page | `mailto:` hits | `nimrod@nimrod.bio` hits |
|------|:---:|:---:|
| /contact/ | **0** | **0** |
| / (footer check) | **0** | **0** |

---

## §4 Version + deploy evidence

| Item | Value |
|------|-------|
| **Version bumped** | `NB_THEME_VERSION` 0.7.12 → **0.7.13** |
| **Preflight IP** | `79.177.137.169` — in `UPRESS_FTP_ALLOWED_IPS` allowlist |
| **Files uploaded** | 7 (list below) |
| **Upload method** | `scripts/upress_ftps_upload.py` — FTP_TLS + prot_c() + PASV |
| **Byte-parity** | All 7 PASS (MD5 local == remote) |
| **Version live** | `ver=0.7.13` confirmed on page source |

**Files changed and deployed:**
1. `assets/css/t3.css` — F-003 gallery img containment
2. `inc/cpt-service.php` — F-002 `has_archive => 'services'`
3. `inc/template-styles-t1.php` — F-002 t1.css enqueue for archive
4. `archive-service.php` — F-002 new archive template
5. `template-parts/t8-contact-side.php` — F-004 email card removed
6. `template-parts/shell-footer.php` — F-004 footer mailto removed
7. `functions.php` — version 0.7.13

---

## §5 Verification table

| Check | Method | Result |
|-------|--------|--------|
| /services/ HTTP status | executed curl | **200** |
| /contact/ HTTP status | executed curl | **200** |
| /project/hagina-shel-nimrod/ HTTP | executed curl | **200** |
| /project/rest-x-greenhouse/ HTTP | executed curl | **200** |
| / (home) HTTP | executed curl | **200** |
| /about/, /about/heritage/, /world/soil,know,code/, /project/sfa,tiktrack/, /services/bcs/ | executed curl | **All 200** |
| /services/ lists 7 service cards | executed curl+grep | **PASS** (7 links confirmed) |
| /contact/ mailto count | executed curl+grep | **0** |
| / (home/footer) mailto count | executed curl+grep | **0** |
| /contact/ @nimrod.bio count | executed curl+grep | **0** |
| Lock terms on /services/ | executed curl+grep | **0** |
| Lock terms on /contact/ | executed curl+grep | **0** |
| Lock terms on /project/hagina/ | executed curl+grep | **0** |
| Lock terms on /project/rest-x-greenhouse/ | executed curl+grep | **0** |
| F-003 gallery CSS live on server | executed curl | **PASS** — `.gallery img.img-ph` rule confirmed |
| F-003 scrollWidth @375 + @1440 | **REASONED** | CSS mechanism closes the overflow; authoritative browser measurement deferred to team_50/team_100 re-verify |
| Byte-parity (all 7 files) | MD5 comparison | **7/7 PASS** |
| php -l (all PHP files) | executed | **0 errors** |

---

## §6 BCS gallery section

**SKIPPED this round** — scope discipline. F-002/F-003/F-004 are the S2 blockers for L-GATE_VALIDATE. BCS gallery (`_nb_gallery` on service CPT + `t3-gallery.php` section in `single-service.php`) is lower priority and does not block cutover. Can be addressed in a follow-on increment.

---

## §7 Blockers

None. All three S2 findings resolved and deployed.

*team_35 | COMPLETION | 2026-06-01 | ready for team_100 re-verify + team_190 L-GATE_VALIDATE*
