---
type: COMPLETION (precision — G2/G3/C2)
from: team_35 (Site Design + Build — Claude Design)
to: Claude Code team (live theme build)
cc: team_00 (Nimrod), team_100, team_50
project: nimrod-bio
wp_id: NB-S002-P009-WP001
date: 2026-06-03
status: COMPLETE — code-level review done; world-accent fix applied; live-verify checklist attached
method: code parity review (theme CSS / template-parts ↔ design-system SSOT). Final pixel parity
        needs the live WP render — see the per-item qa_probe checklist (team_50 / Claude Code).
---

# G2 / G3 / C2 — precision completion

Closes the three items left open after the V200 package. Fixes land in the **same handoff
package** (`theme/assets/css/t1.css`). Lock-scan: **0**.

## C2 · home sub-document img overflow — RESOLVED (benign)
- **Localised:** `.manifesto .mf-bg img` — the decorative basket watermark
  (`width:min(560px,48vw)`, `inset-inline-start:-7%`, `opacity:.055`).
- **Why it reads as overflow:** it is deliberately oversized + offset to bleed off the edge, and is
  **clipped by `.manifesto{overflow:hidden}`** → no document scrollWidth growth (matches team_50's
  note). The 26px@375 → 101px@1440 scaling tracks the `48vw` width exactly.
- **Verdict:** cosmetic-by-design, no horizontal scroll, no layout impact. **No change required.**
  If the QA flag must read zero, clamp the watermark (`width:min(560px,40vw)`) — but this trims the
  intended bleed; recommend leaving as-is.

## G3 · world pages (know / code) + heritage
### G3a · World-accent theming — FIXED ✅
- **Finding:** `page-soil/know/code.php` all render `template-parts/t1-body.php` with a `world` arg
  (`t1-world-{world}`) — structure is correctly parametrized. **But `t1.css` hard-codes
  `--w-soil-deep` for every accent** (`.s-eyebrow`, `.vc-lattice .lat-anchor` background,
  `.anchor-card` border + `.anchor-eye` + `.facts .v`, `.post-card:hover h5`, `.vc-hero .gloss .rule`)
  with **no `.t1-world-know` / `.t1-world-code` override** → the know (orange) and code (teal) pages
  render their accents in **soil green**.
- **Fix (applied, `t1.css`):** scoped, additive override block remapping those accent surfaces to
  `--w-know-deep` / `--w-code-deep` under `.t1-world-know` / `.t1-world-code`. Soil keeps the base.
  → **verify on live:** `/world/know/` accents = orange, `/world/code/` = teal; `/world/soil/` unchanged.
### G3b · Heritage page — PASS
- `page-heritage.php` + `t8.css` complete: `.heritage-reading` measure, `.heritage-body` (dropcap,
  numbered `h2`, `blockquote`, `.pullquote`, `.entity-link`), `.heritage-end` CTA, responsive. No gap.

## G2 · T2 services / T3 project / T4 post / T5 blog — code-review PASS (+ live checklist)
These templates exist (theme PHP + design-system files) but were never in the v4 precision pass.
Code-level review against the bug-classes found earlier:
| Check | Result |
|---|---|
| Inline `grid-column/grid-row` not reset at breakpoint (the lattice bug) | **Confined to the lattice** (`t1-body.php`, `t1-anchor-card.php`) — already fixed. T2–T5 template-parts carry none. |
| Link-card missing `text-decoration:none` (the bridge bug) | All T2–T5 card `<a>`s set `text-decoration:none` (t2/t3/t4/t5.css). Clean. |
| Vocalised `מֳר` unit typo | **Zero** occurrences anywhere in the theme. |
| Card image crop uniformity | T2–T5 cards use `.img-ph`/`.ph` with `aspect-ratio` + `object-fit:cover`. Consistent. |
- **No concrete defects found in code.** Final pixel parity vs the design-system files
  (`T2 Services.html` … `T5 Blog.html`) must be confirmed on the **live render** — checklist below.

### Live-verify checklist (qa_probe @ 375 + 1440 — team_50 / Claude Code)
- **T2** `/services/` + `/services/{slug}/` — svc-card grid alignment; single-service hero + body measure.
- **T3** `/project/{slug}/` — project hero image ratio; scope/stage stamps; related-projects row.
- **T4** `/{post}/` — post body measure (~66ch), TOC anchors, `h2` numbering, related posts.
- **T5** `/blog/` — featured post + grid; world-tag filter (`t5-filter.js`); empty/▸states.
- **G3a** world accents recolour per world (orange / teal); soil unchanged.

## Files touched (this round)
- `theme/assets/css/t1.css` — **world-accent override block** appended (G3a). (Also already carries the
  G1 projects-archive block + the B1 lattice crush fix from the main package.)

## Net status
- **C2** benign (no change) · **G3a** fixed · **G3b** pass · **G2** code-clean + live checklist.
- Everything ships inside `HANDOFF_CLAUDE_CODE_V200_2026-06-03/`.

---

*team_35 | G2/G3/C2 precision completion | 2026-06-03 | world-accent fixed · T2–T5 code-clean · C2 benign · locks 0*
