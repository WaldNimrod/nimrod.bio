# Handoff: V200 UI Precision + Missing Templates

## Overview
Design-layer work for the nimrod.bio V200 rebuild (WordPress, Hebrew **RTL**, uPress; active theme `nimrod-bio-2026`). Page **text is final and locked** — this handoff is **design + template completion only**: a UI-precision pass plus the missing/partial templates (Contact hero, About timeline + "קצת ים", 404, search, empty-archive) and three data-driven affordances (activity counts, external links, media degradation).

Read `UI_DESIGN_REVIEW.md` (in this folder) for the findings and rationale. This README is the implementation spec.

## About the design files
`Precision Mockup v4.html` is a **design reference created in HTML** — a single, screen-switchable prototype showing intended look and behavior. It is **not** production code to paste. Recreate its screens inside the **existing `nimrod-bio-2026` theme** using that theme's established patterns: PHP `template-parts/`, the `assets/css/{system,shell,t1…t8}.css` files, the `--token` system, and RTL logical properties. Reuse existing markup wherever it already exists (the contact form/side, world cards, project cards) — most tasks are edits, not rewrites.

Switch screens in the mock via the top bar: **בית · T7 / עולם · T1 / צור קשר · T8 / אודות · T8 / 404·חיפוש / מצבים·states**. The **states** screen is a spec sheet (0/1/many counts, external-link, media degradation), not a live page.

## Fidelity
**High-fidelity.** Final colors, type, spacing, and copy. Recreate pixel-faithfully with the theme's CSS. Where a value isn't stated, read it off the mock's CSS.

## ⚠ Locks (override everything, incl. meta/alt/aria)
1. **Micha / "Micha OS"** — never appears anywhere.
2. **Demonstrate, never name** — never surface: CDIP · cross-domain isomorphism · אנטרופיה · נגנטרופיה · רקורסיה · פרמקלצר · "3×" · אינסטנסים · קואופרטיב/קומון · "4 חממות" · "5 מסעדות". No disruption/game-changer/AI-first/"הפלטפורמה שלנו"/"אנחנו מאמינים".
Voice rules: `SITE_HANDOFF_2026-05-31_v1.md §2`. Lock-scan every diff before commit.

---

## Tasks → files

### 1 · Lock + fact corrections (copy/markup; `front-page.php`, `template-parts/t1-*`, world parts)
Apply Fixes A–E from `SITE_COPY_FIXES_v1.md` and the About fact-fixes:
- **Hero lead** → "שלוש זרועות, שורש אחד. הייחוד הוא בחיבורים ביניהן." Kicker → owner-verified facts only: `חממה 420 מ״ר` · `9 עונות` · `SFA חי`.
- **Concept caption** → drop "נֶגֶנְטְרוֹפְּיָה"; keep the SVG graphic (it demonstrates it).
- **World CODE list** → `AOS · SFA · קהילתי` / `tiktrack (פילוט)` / `פיתוח ממשקים · ייעוץ דיגיטלי`.
- **Home bridge אדמה×דיגיטל** → remove tiktrack ("מזינה את SFA…"); tag `SFA · ייעוץ דיגיטלי`.
- **Manifesto §06** → approved Fix E body; remove "אנטרופיה".
- **World principle block** (`.vc-principle`, was `.vc-cdip`) → label "אותו עיקרון · חומר אחר"; body = About §03 ("…זו אותה מערכת, בחומר אחר."). No CDIP/3×/אינסטנסים.
- **World lattice facts** → `עונות 9 · חממה 420 מ״ר · מסירה שבועי`; tag `evidence-based · מבוסס-שדה`.
- Rename theme class `.vc-cdip`→`.vc-principle`. Remove `קואופרטיב` everywhere (cards, footer).

### 2 · System templates
- **`404.php`** — replace the stub with the **System→404** layout: three-world dot lockup (`4 ● 4`, dots = soil/know/code), H1 "השביל הזה לא מוביל לשום מקום.", lead, and pill links (home + 3 worlds + contact). Classes `.err-404`, `.err-links`.
- **`search.php`** — results template: `.search-field` (search icon + input + button), `.search-meta` ("N תוצאות לְ…"), `.results-list` > `.result-row` (`.r-kind` chip + h4 + excerpt). Add a no-results branch using `.empty-state`.
- **Empty archive** — when a world/blog archive has no posts, render `.empty-state` (emblem + on-voice copy, **not** "no results found") instead of a blank loop.

### 3 · Media degradation (`assets/css/t8.css` + helpers)
- Add `.ph.clean` (world-tinted wash + quiet basket emblem, **no visitor-facing caption**). Render it whenever a media slot has no attachment.
- The `TBD · …` caption becomes **dev-only** (e.g. gated to logged-in/admin), never shipped to visitors. Offer `.ph.collapse` (display:none) where an empty slot should simply disappear.
- Move `aspect-ratio` + `overflow:hidden` to the **container**, not the `<img>` (canon G-05).

### 4 · Contact hero (`page-contact.php`)
Add a hero above the existing form/side: eyebrow, H1 "דבר איתי", lead (approved), and `.hero-act` with two buttons — **WhatsApp primary** (`.btn.btn-wa`, `https://wa.me/972547776770`, WhatsApp glyph) and **`לטופס למטה`** (`.btn.btn-ghost`, anchors `#nb-contact`). Keep `t8-contact-form.php` + `t8-contact-side.php` (incl. `.contact-social`) as-is.

### 5 · About (`page-about.php` / `t8-*`)
Personal-register page: hero (portrait slot + lede), §01 origin prose, **§02 journey timeline** (`.timeline` RTL rail, `.tl-row` with `.tl-dot`/`.tl-year`/`.tl-body`, `.key` for milestone rows), §03 principle prose, §04 `.principle-grid` of 3 `.principle-tile`, **§06 "קצת ים"** (`.sea` band, `.sea-quote`), contact CTA. **§05 press stays hidden** until verified links exist — no "TBC".

### 6 · Counts + external links (CPT meta)
- **Counts:** wire `.more` to real `project`/`service` CPT counts per world; render `בקרוב` (0) / `פעילות אחת` (1) / `N פעילויות` (≥2) — never "0 פעילויות".
- **External links:** register `_nb_external_url` + `_nb_external_label` on the `project` CPT; set SFA=`https://sfa.nimrod.bio`, TikTrack=`https://tt.nimrod.bio`. Render `.ext-link` (out-arrow `#i-ext` + domain); omit when no URL. Replace the hardcoded code-world lattice URLs.

### 7 · Dead code
Delete `nb_render_cdip_diagram()` from `inc/template-helpers.php` (uncalled) and the orphaned `template-parts/t8-media-item.php`.

---

## Design tokens (from the mock `:root`)
```
Paper:  --paper #f5f3ec · --paper-2 #e8e7df · --paper-3 #dedccf · --line #d6d2c2
Ink:    --ink #1f1e1c · --ink-soft #4a4844 · --soil(brown) #5b483a
Worlds: soil deep #3a5220 / light #6a8a3a · know deep #9a4f2b / light #c46a3e · code deep #1f5e60 / light #2d8a8c
Washes: --soil-wash #eef0e0 · --soil-wash-2 #e3e6cf · --know-wash #f4e5d6 · --code-wash #d8e6e6
Spark:  --spark #d23a2e  (≤3–5 uses site-wide — budget it)
WhatsApp button: #1f8a4c (hover #187a42)
Space:  --s-1 16 · --s-2 24 · --s-3 32 · --s-4 48 · --s-5 64 · --s-6 96 · section rhythm --sec-y clamp(38,4.6vw,52)
Radius: s 8 · m 14 · l 20 · pill 100 · Shadow: --shadow-s/m/l (see mock)
Type:   serif "Frank Ruhl Libre" · sans "Assistant" · mono "JetBrains Mono"; body 17px/1.55; dir rtl
```

## Interactions & behavior
- Cards lift on hover (`translateY(-3px)` + shadow). Spark toggle (`body[data-spark]`) gates playful motion; honor `prefers-reduced-motion`.
- Contact form: client `required`/`minlength=20`; honeypot `.hp-field`; submits to `admin-post.php` (`nb_contact_submit`) — confirm a live submission is received.
- Carousels (`.projects-row`, `.products-grid.carousel`) scroll-snap with prev/next arrows.
- All hit targets ≥ 44px; nav goes transparent over the Home hero, solid elsewhere.

## Assets
- `images/basket-{soil,know,code,paper,ink}.png` — world emblems / wordmark.
- `images/raw/*.jpg|jpeg` — placeholder photography (real media arrives separately from team_110). **Note:** `raw/why-morning.jpg` has a baked-in video play-button (it's a video still) — don't use as a static portrait; `raw/plowing.jpg` & `raw/leaf-garden.jpg` are price-table screenshots, not photos.
- Icons are an inline SVG sprite at the top of the mock (`#i-soil/know/code/bridge/ext/search/anchor`, `#ic-*`). Reuse the theme's existing sprite.

## Files in this bundle
- `Precision Mockup v4.html` — visual SSoT (all screens).
- `UI_DESIGN_REVIEW.md` — findings + severity + sequence.
- `images/` — assets so the mock renders standalone.

## Deploy / sync (after implementation)
Per `docs/UPRESS_FTPS_MANDATORY_PROCEDURE.md`: bump `NB_THEME_VERSION` (0.7.5 → 0.7.6) in `functions.php`; deploy theme files via `scripts/upress_ftps_upload.py`; pull deployed files back (`upress_ftps_download.py`, one file per connection); verify each page cache-busted (`?nc=`) on `https://nimrod-bio-2026.s887.upress.link`; lock-scan; commit on top of `7526893b`. **Dev only** — production cutover is separate.
