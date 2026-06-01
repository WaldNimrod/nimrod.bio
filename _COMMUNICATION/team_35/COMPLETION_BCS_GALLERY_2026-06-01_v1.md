# COMPLETION — BCS Gallery Section — team_35 — 2026-06-01

**Status:** COMPLETE  
**Author:** team_35 (Site Design + Build)  
**Theme version:** 0.7.14  
**Env:** dev `https://nimrod-bio-2026.s887.upress.link`  
**Service:** post ID 24, slug `bcs`

---

## (a) Code changes

### `inc/meta-registration.php`
Added `gallery` to the service CPT `$array_meta` loop (line 41):
```php
foreach ( array( 'linked_projects', 'related_posts', 'gallery' ) as $key ) {
    register_post_meta( 'service', '_nb_' . $key, $array_meta );
}
```
Mirrors exactly the `project` CPT pattern — same schema (`show_in_rest` array-of-strings, `single=true`, `type=array`). String IDs preserved as per project pattern.

### `single-service.php`
Added gallery include after `t2-three-col`, gated on non-empty `_nb_gallery`:
```php
$_nb_gallery_items = nb_meta_array( $post_id, 'gallery' );
if ( ! empty( $_nb_gallery_items ) ) :
    get_template_part( 'template-parts/t3-gallery', null, array( 'post_id' => $post_id ) );
endif;
```
Gallery renders only when photos are wired. Empty gallery → no section, no placeholder box visible to visitors.

### `inc/template-styles-t2-t3.php`
Added `t3.css` enqueue for service pages (required because `t3-gallery.php` uses `t3-wrap` / `t3-section` / `.gallery` CSS from `t3.css`). Without this the gallery overflowed at 375px (scrollWidth=1024). Fix: added `nb-t3` enqueue inside `is_singular('service')` branch, depending on `nb-t2`.

### `functions.php`
Version bumped `NB_THEME_VERSION`: `0.7.13` → `0.7.14`

All 4 changed PHP files passed `php -l` (no syntax errors).

---

## (b) Deploy + byte-parity

**Preflight:**
- Public IPv4: `79.177.137.169`
- Allowlist status: IP confirmed present in `UPRESS_FTP_ALLOWED_IPS`
- TCP probe port 21: pass

**Files uploaded (first deploy — 3 files):**
- `wp-content/themes/nimrod-bio-2026/functions.php`
- `wp-content/themes/nimrod-bio-2026/inc/meta-registration.php`
- `wp-content/themes/nimrod-bio-2026/single-service.php`

**Files uploaded (second deploy — 1 file, CSS overflow fix):**
- `wp-content/themes/nimrod-bio-2026/inc/template-styles-t2-t3.php`

**Byte-parity:** All 4 files re-downloaded and `diff` confirmed zero delta.

**HTTP 200 check:** `style.css` → 200 OK.

---

## (c) Photos converted + uploaded + gallery order

All photos converted: `cwebp -q 82 -resize 2400 0 -metadata none`. EXIF stripped.

| Order | File | Media ID | Alt text (Hebrew) | Size |
|-------|------|----------|-------------------|------|
| 1 | bcs-ground-blaster.webp | **1091** | טרקטור BCS 740 עם מחרשה סיבובית RotorBlade עובד בשטח פרי-עץ מוזנח | 752K |
| 2 | bcs-field-work-1.webp | **1092** | שורות אדמה שזה עתה נחרשו בשדה עם טרקטור BCS כחול ברקע | 541K |
| 3 | bcs-field-work-2.webp | **1093** | תצפית רחבה על שדה מעובד עם שורות ערוגות לאחר חרישה | 483K |
| 4 | bcs-prepared-bed.webp | **1094** | ערוגות שדה מוכנות עם קווי טפטוף בשעת ערב — תוצאת עבודת BCS | 425K |
| 5 | bcs-field-overview.webp | **1095** | מבט-על של שטח השדה עם פרישת ערוגות אדמה ושוליים ירוקים | 1.4M |
| 6 | bcs-team-transport.webp | **1096** | שניים רוכבים על עגלת גרירה של BCS בדרך עפר בשדה פתוח | 398K |
| 7 | bcs-mature-garden-1.webp | **1097** | ערוגות גינה בשלות עם סלק, חסה אדומה וכרובית בגידול מלא | 323K |
| 8 | bcs-mature-garden-2.webp | **1098** | מבט רחב על ערוגות ירקות בשלות, מגוון גדל בשורות מסודרות | 543K |
| 9 | bcs-flail-mower.webp | **1090** | טרקטור BCS כחול עם מכסחת ארגז חותך דשא גבוה ליד קיר | 435K |

**Gallery meta set:** `POST /wp-json/wp/v2/services/24` `{"meta":{"_nb_gallery":["1091","1092","1093","1094","1095","1096","1097","1098","1090"]}}`

**Confirmed persisted via GET:** 9 IDs returned.

**Left out (owner gaps — DO NOT substitute):**
- מתחחת (tiller) — no photo in Drive archive
- Power-Harrow (מטחחת אקולוגית) — no photo in Drive archive

**Hero** (media 1085, flail/box mower, pre-existing): unchanged.

**Hero shot also in gallery (1090):** Included as last item per spec optional allowance — it reads well as a close-up tool detail vs. hero's action framing.

---

## (d) Verification table

| Check | Result |
|-------|--------|
| `/services/bcs/` HTTP status | 200 |
| Gallery `<div class="gallery">` present | Yes |
| Gallery img count rendered | 9 |
| Non-empty Hebrew alt count | 10 (9 gallery + 1 hero) |
| No empty `alt=""` in gallery items | Confirmed |
| `_nb_gallery` meta GET (service 24) | Array of 9 string IDs — persisted |
| Hero (media 1085) still present | Confirmed |
| No TBD, CDIP, Micha, מיכה in page | Clean |
| QA CDP — BCS mobile (375px) overflow | PASS (scrollWidth=375, overflow=false) |
| QA CDP — BCS desktop (1440px) overflow | PASS |
| QA CDP — /services/ mobile | PASS |
| QA CDP — /services/ desktop | PASS |
| QA CDP forbidden terms scan | PASS (0 found) |
| Regression — /services/produce/ mobile | PASS |
| Regression — /services/consulting-hydro/ mobile | PASS |
| Total QA failures | **0 / 4 (main run), 0 / 6 (regression)** |

**Initial QA failure (resolved):** First CDP run found mobile overflow (scrollWidth=1024 at 375px). Root cause: `t3.css` was not enqueued for service pages; `t3-gallery.php` uses `t3-wrap` / `.gallery` CSS only defined there. Fix: added `nb-t3` enqueue inside `is_singular('service')` in `template-styles-t2-t3.php`. Uploaded, re-verified — PASS.

---

## (e) Blockers

None. All steps completed.

---

*team_35 | 2026-06-01 | BCS gallery section complete — ready for team_100 commit*
