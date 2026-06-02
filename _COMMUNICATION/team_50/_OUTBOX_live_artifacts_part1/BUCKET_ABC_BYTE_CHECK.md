# Bucket A/B/C — live computed-style byte-check (1440 unless noted)

**Captured:** 2026-06-02 · **Live:** `https://nimrod-bio-2026.s887.upress.link` · **Theme:** `NB_THEME_VERSION=0.7.15`  
**Source JSON:** `computed_style_proofs_1440.json` · **Mockup SSOT:** `Precision Mockup v4.html` `:root` + component rules

## Bucket A

| Check | Selector (live) | Live computed | Mockup target | Pass |
|-------|-----------------|---------------|---------------|------|
| Eyebrow mono (contact) | `.eyebrow` on `/contact/` | `font-family: "JetBrains Mono", monospace`; `12px`; `letter-spacing: 1.68px` (.14em); `uppercase`; `color: rgb(31, 94, 96)` | mono 12px / .14em / uppercase / world color | ✅ |
| Eyebrow mono (home hero) | `.t7-eyebrow` on `/` | same mono stack; `color: rgb(58, 82, 32)` (soil-deep) | mono 12px / soil-deep | ✅ |
| WA button green | `.wa-btn` on `/contact/` | `background-color: rgb(31, 138, 76)` (#1f8a4c) | `#1f8a4c` | ✅ |
| WA button hover | live `t8.css` rule | `#187a42` on `.contact-card .wa-btn:hover` | `#187a42` | ✅ (stylesheet; see `wa_btn_hover_proof.json`) |

## Bucket B

| Check | Selector | Live @ 1440 | Mockup / mandate expectation | Pass |
|-------|----------|-------------|------------------------------|------|
| Contact form present | `.contact-form` | `true`; selectors include `FORM.contact-form`, labels, fields | form block present | ✅ |
| Contact social present | `.contact-social a` | 3 links (Facebook×2, YouTube) | social block present | ✅ |
| Home bridges grid | `.bridges-grid` on `/` | `grid-template-columns: 360px 360px 360px` (3-up) | mockup 2-up (`1fr 1fr`) at desktop | ❌ drift |
| World bridges (t1) | `.vc-bridges` on `/world/soil/` | `553px 553px` (2-up) | 2-up | ✅ |

## Bucket C

| Check | Selector | Live | Mockup | Pass |
|-------|----------|------|--------|------|
| About prose measure | `.about-prose` | `max-width: 576.107px`; sans 19.5px; `line-height: 34.125px` | prose block present | ✅ |
| About final CTA | `.t8-final-cta` | `display: block` | `.final-cta` present | ✅ |
| Body line-height | `body` | `26.35px` (= 1.55 × 17px base) | `1.55` | ✅ |
| Section line-height | first `section` (home) | `26.35px` | `1.55` | ✅ |
| `--radius-s/m/l` | `:root` resolved | `8px` / `14px` / `20px` | mockup `:root` | ✅ |
| `--shadow-s/m/l` | `:root` resolved | 2-layer s; m/l per mockup strings | mockup `:root` | ✅ |

## §06 (mandate D — separate)

| Check | Live `/` @ 1440 | Expected | Pass |
|-------|-----------------|----------|------|
| `.posts-grid` + `.rp-card.feat` + 4 `.rp-card` | **Absent** — `rpCardCount: 0` | Built §06 between §05 projects and §07 final-cta | ❌ **not deployed** |
| Section order | `t7-projects` → `manifesto` → `final-cta` (no posts grid) | projects → §06 → final-cta | ❌ |

*team_50 | byte-check summary for team_35 Part 1 scan | 2026-06-02*
