# REQUEST — design completeness across ALL interfaces/page-types (pre full-implementation WP) — team_100 → team_35 — v1

**Date:** 2026-06-03 · **From:** team_100 (on behalf of team_00) · **To:** team_35 (Site Design)
**Context:** before opening the new WP for **full implementation of all mockups**, team_00 requires confirmation that a **current (v4-level) precision design authority exists for every page-type** — not a guess. We audited the delivered package ("Nimrod.bio AOS Design System (10).zip" = the `HANDOFF_CLAUDE_CODE_V200_2026-06-03` increment + `G2_G3_C2_PRECISION_2026-06-03_v1.md`) against the live theme's page-types.

## What we have (verified)
| Page-type | Theme template | Current precision authority | Status |
|---|---|---|---|
| Home (T7) | front-page.php | **Precision Mockup v4** (בית·T7) | ✅ precision-final |
| World soil (T1) | page-soil.php | **Precision Mockup v4** (עולם·T1) | ✅ |
| Contact (T8) | page-contact.php | **Precision Mockup v4** (צור קשר·T8) | ✅ |
| About (T8) | page-about.php | **Precision Mockup v4** (אודות·T8) | ✅ |
| 404 / search / empty-states | 404.php / search.php / states | **Precision Mockup v4** (404·חיפוש / מצבים) | ✅ |
| Projects archive | archive-project.php | HANDOFF_V200 "Projects Archive Preview" | ✅ |
| §06 Recent-Posts (home) | front-page.php | HANDOFF_V200 "S06 Preview" | ✅ |
| World **know / code** (T1) | page-know.php / page-code.php | only **G3a accent-recolor** (this package); no dedicated v4 screen (soil is the sample) | ⚠ confirm |
| Heritage (T8) | page-heritage.php | **G3b "PASS" (code-review only)**; no v4 mockup screen | ⚠ confirm |
| **Services T2** (archive + single) | archive-service.php / single-service.php | only **stage3 `T2 Services.html`** + **G2 code-review** | ⚠ no v4 precision |
| **Project single T3** | single-project.php | only **stage3 `T3 Project.html`** + G2 code-review | ⚠ no v4 precision |
| **Post single T4** | single.php | only **stage3 `T4 Post.html`** + G2 code-review | ⚠ no v4 precision |
| **Blog index T5** | home.php | only **stage3 `T5 Blog.html`** + G2 code-review | ⚠ no v4 precision |

## The gap (why we're asking, not guessing)
The **v4 precision mockup covers only T7 / T1-soil / T8(about,contact) / system**. For **T2, T3-single, T4, T5, the T1 know/code variants, and heritage**, the only design authority is the **pre-precision stage3 prototypes** + your **G2/G3 code-review** (which found "no concrete defects" but explicitly deferred *pixel parity* to a live qa_probe checklist). We will not assume stage3 == final precision.

## Requests (please answer per row)
1. **T2 / T3-single / T4 / T5 — precision authority:** for each, either
   (a) **confirm** the stage3 `T2…T5 *.html` (+ your G2 code-review) **IS** the final precision authority — we build/verify to those; **or**
   (b) **supply v4-level precision screens** for them (same fidelity as the v4 home/world/contact/about), as additional screens in the v4 mockup or a delta doc.
2. **T1 know / code variants:** confirm the **G3a accent-recolor is the complete variant spec** (i.e., know/code = soil layout with world accents only), or supply know/code precision screens if they differ beyond accent.
3. **Heritage (T8):** confirm the current `page-heritage.php` + `t8.css` is the **intended final** (your G3b PASS), or supply a heritage precision screen.
4. **Package scope / SSoT:** the delivered zip is an **increment**, not a consolidated design system. Confirm the **canonical design SSoT** for the full-implementation WP is the **distributed set** (Precision Mockup v4 + stage3 T2–T5 + this G2/G3/C2 increment + components), **or** provide a single consolidated bundle.
5. **G3a code fix:** the world-accent override in this package's `theme/assets/css/t1.css` (+25 lines vs our live `t1.css`) is **not yet landed**. Confirm it should land as part of the full-implementation WP (we will not hot-patch it standalone unless you advise).

## Constraints (unchanged)
Edits land in module CSS + template-parts only (no inline / no overrides layer / `system.css` LOCKED). Both super-locks (Micha; demonstrate-never-name) on every byte incl. alt/aria/comments. RTL logical properties. a11y non-regression: WP006 baseline must hold (axe 0, Lighthouse a11y ≥95).

## Response requested
A short reply table (rows 1–5) — confirm-stage3 vs supply-v4 per page-type — so team_100 can scope the LOD400 for the full-implementation WP against a complete, current design SSoT.

*team_100 → team_35 · design-completeness request · 2026-06-03 · audited vs live theme page-types*
