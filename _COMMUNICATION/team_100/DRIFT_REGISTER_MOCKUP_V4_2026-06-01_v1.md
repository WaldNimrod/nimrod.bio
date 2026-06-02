# DRIFT REGISTER — Precision Mockup v4 vs live theme — team_100 — v1

**Date:** 2026-06-01
**Author:** team_100
**Type:** DRIFT REGISTER (mockup-diff, 4 parallel read-only scans)
**SSOT:** `_INBOX_design_handoff_v200/.../Precision Mockup v4.html` · **Live:** theme v0.7.14 CSS modules
**Method:** per-component CSS diff (no visual narration) — t7/t1/t8/system+t2+t3

## Verdict
The Stage A–D ports are largely faithful. Drift concentrates in **(a) the pre-v4 leftover rules** never re-aligned (About §01, contact form/side, contact CTA) and **(b) a few token deltas in LOCKED system.css**. Sorted into 3 action buckets.

---

## BUCKET A — clear drifts in EDITABLE module files (fix to match mockup; no judgment needed)
| # | file | selector | drift | sev |
|---|------|----------|-------|-----|
| A1 | t8.css | base `.eyebrow` | **rule missing from T8 bundle** → every eyebrow (contact/about/sea) falls back to body sans 17px instead of mono 12px/.14em/uppercase/soil-deep | ● |
| A2 | t8.css | `.wa-btn` | dark `--ink` bg instead of WhatsApp green `#1f8a4c` (hover `#187a42`) | ● |
| A3 | t8.css | `.contact-form label` | bold sans 13px vs mockup uppercase mono 11.5px/.06em/ink-soft | ● |
| A4 | t8.css | `.contact-card.response` | transparent/no-pad vs mockup `--soil-wash` tinted card + border | ● |
| A5 | t8.css | `.contact-card .direct` | sans-ink-underline vs mockup mono `--w-code-deep`/ltr | ◐ |
| A6 | t8.css | `.contact-social a` | underline rows vs mockup know-dot `::before` + hover know-deep | ◐ |
| A7 | t8.css | about-hero band | legacy `.t8-about-hero` adds `--paper-2` bg the mockup `.page-hero` doesn't have; also shortens padding | ◐ |
| A8 | t8.css | contact form input focus | `--w-soil-deep` (green) vs mockup `--w-know` (terracotta); textarea serif vs sans; input 15px/10px-pad vs 16px/13px | ◐ |
| A9 | t1.css | `.post-card:hover h5` | hover tint `--w-know-deep` vs mockup `--w-soil-deep` (wrong world on soil page) | ◐ |
| A10 | t1.css | `.vc-hero-stack` | `clamp(80px,13vw,220px)` vs mockup `clamp(72px,12vw,180px)` — hero ~22% oversized | ◐ |
| A11 | t3.css | `.ls-card .more` | unconditional `::after{content:" ←"}` — no count-zero/one/many degradation classes (`בקרוב`/quiet state) | ◐ |
| A12 | t1.css/t8.css | spacing/type micro-deltas (lat-anchor pad 36→32, intro gaps, side-card body 13.5→14.5, etc.) | many ○ — batch-align to mockup values | ○ |
| A13 | t7.css | comment `recursion` (L29) | forbidden surface term in a comment (cleanup) | ○ |

## BUCKET B — LOCKED `system.css` tokens (cascade site-wide; need DECISION — GCR vs accept, NOT a direct edit)
> Iron-rule note: `system.css` is team_35-LOCKED (prior P009-WP004 note + t1-scan confirm). Changing tokens needs a GCR, not an in-place edit.
| # | token | mockup | live | impact | sev |
|---|-------|--------|------|--------|-----|
| B1 | `--shadow-s` | 2-layer (hairline + `0 4px 12px -8px …`) | **single hairline only** — soft drop-shadow dropped | every card/input/form across the site reads flatter | ● |
| B2 | `--shadow-l` | `0 24px 50px -28px …/.28` | `0 30px 60px -30px …/.30` (bigger/darker) | portraits, product-card hover | ◐ |
| B3 | `--shadow-m` | `…-16px …/.20` | `…-14px …/.22` | default card shadow | ○ |
| B4 | `--radius-s` | `8px` | `6px` | form inputs, small chips | ◐ |
| B5 | `--radius-l` | `20px` | `22px` | all large cards 2px rounder | ◐ |
| B6 | body `line-height` | `1.55` | `1.65` | all body copy looser (known prior item — "GCR if needed") | ◐ |
| B7 | `--max` | `1240px` | `--grid-max:1200px` (40px narrower) + name differs | content width | ◐ |

## BUCKET C — live FLOURISHES not in the mockup (DECISION: keep as intentional refinement, or revert to mockup)
| # | where | live extra | note |
|---|-------|-----------|------|
| C1 | t1.css `.vc-bridges` | grid `repeat(3,1fr)` (3-up) vs mockup `1fr 1fr` (2-up) | structural — biggest "feel" delta; mockup wants 2-up |
| C2 | t1.css `.vc-projects` | project cards tilted `rotate(±.x deg)` "strata" flourish | not in mockup |
| C3 | t1.css `.vc-hero-stack .e3` | extra 3rd echo layer (mockup has e1,e2) | not in mockup |
| C4 | t7.css bridges-band | watercolor `::before` wash layers (opacity .11/.08) over mockup's radial | WP004 enhancement (flagged intentional) |
| C5 | t8 About §01 | `.story-block` (serif + drop-cap + `.pullquote`) vs mockup `.about-prose` (sans, no drop-cap) | visible treatment change |
| C6 | t8 About CTA | `.contact-teaser` (serif row) vs mockup `.final-cta` (eyebrow+h2+hero-act) | different end block |

## VERIFY/CONFIRM flags (cheap checks before fixing)
- `--sec-y`/`--sec-y-lg`: one scan said undefined (collapsed rhythm risk), another said resolves via components.css/shell.css → **verify** which is true before assuming a bug.
- wash tokens defined in t2.css/components.css but consumed by system.css `.ph.clean` → **verify** enqueue order on system pages (404/search) so washes don't fall back transparent.

## Locks scan (all CSS): clean except comment-only `recursion` (t7.css:29) + `CDIP` in t8.css:81 comment — both non-rendered (cleanup, A13).

---

## UPDATE 2026-06-01 — Buckets A/B/C IMPLEMENTED + completeness scan
- **A/B/C all landed** (theme v0.7.15, commit a35a67df): module-CSS + template edits only (no inline/no overrides). team_00 rulings applied — B = system-wide token uniformity to mockup; C = mockup style, content preserved. Verified: CDP 20/20 PASS, byte-parity, locks 0.
- **STOP-and-ask completeness scan** (the crash gap, now done) — mockup interface elements missing in live:
  | mockup screen | MISSING element | severity | decision |
  |---|---|---|---|
  | t7 home | **§06 Recent-Posts / blog teaser** (`.posts-grid` + feature `.rp-card.feat` + 4 `.rp-card`) — whole section absent from front-page.php | ●must-have | **team_00 decision (routed in team_35 mandate)** |
  | t1 world | `.world-bg` faint basket watermark (decorative, opacity .05) | ○cosmetic | team_35 may add or skip |
- **NOT a build gap (content/data):** t1 world journal (`vc-posts`) renders empty — no posts tagged to `world` taxonomy yet → content-seeding task, not missing structure.
- **EXTRA live (kept):** /blog/ index template, services archive + BCS gallery, real count states — no mockup screen; sanctioned.
- **Bottom line:** structurally complete vs mockup except the §06 home blog teaser.

*team_100 | drift register | 2026-06-01 | A/B/C done + completeness scan; §06 home blog teaser = only real missing element → team_00 decision*
