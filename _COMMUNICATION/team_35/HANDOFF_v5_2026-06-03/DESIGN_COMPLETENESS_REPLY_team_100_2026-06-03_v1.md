---
type: REPLY (design-completeness · rows 1–5)
from: team_35 (Site Design + Build — Claude Design)
to: team_100, team_00 (Principal)
cc: Claude Code team (live build), team_50 (live-run validation)
project: nimrod-bio
wp_id: NB-S002-P009-WP001
re: REQUEST — design completeness across ALL interfaces/page-types — team_100 → team_35 — v1 (2026-06-03)
date: 2026-06-03
version: v1
status: ANSWERED — precision authority now exists for EVERY page-type. SSoT consolidated into Precision Mockup v5.
---

# Design-completeness reply — confirm-stage3 vs supply-v4, per page-type

## TL;DR
The audit was run against **v4**, which only ever carried T7 / T1-soil / T8(about,contact) / system.
Since then the precision pass was **continued into a v5 mockup**. With this session, **v5 now
contains a v4-level precision screen for every page-type the audit flagged** — including the three
true sub-gaps (T2 single-service, T1 know/code variants, heritage). The answer to the audit is
therefore **supply-v4, and it's done** — not "confirm stage3."

**Canonical screen SSoT for the full-implementation WP = `Precision Mockup v5.html`.**
The HANDOFF_V200 increment (theme parts, archive-project, §06, G2/G3/C2 review) stays as the
**build/delta layer** that maps those screens onto the live theme.

## Reply table (rows 1–5)

| # | Page-type | Audit ask | **team_35 answer** | Authority |
|---|---|---|---|---|
| **1** | T2 index · T3-single · T4 · T5 | confirm stage3 **or** supply v4 | **SUPPLY-V4 — DONE.** Precision screens exist in v5 (full dedicated CSS, same vocabulary as the v4 home/world). They **supersede** the stage3 `T2…T5 *.html` as the precision authority. | `Precision Mockup v5.html` → screens `t2`,`t3`,`t4`,`t5` |
| **1b** | **T2 single-service** (the one real sub-gap) | — | **SUPPLY-V4 — DONE (new this session).** Added a dedicated single-service precision screen: hero + meta-strip, "what's included" feature grid, 4-step process, field-proof projects, cross-world bridge, CTA. So T2 = **archive + single**, both precision. | v5 → screen `t2s` |
| **2** | T1 **know / code** variants | confirm accent-only **or** supply screens | **CONFIRM — accent-recolor IS the complete spec**, and now **proven visually**. Locked taxonomy: worlds share ONE layout; only accent surfaces recolour (know=orange, code=teal). Added an in-mockup **world switcher** on the T1 screen that live-swaps soil↔know↔code so you can verify the divergence is accent-only — no layout fork. The matching CSS override block is the design-side twin of the G3a fix. | v5 → screen `t1` (switcher) |
| **3** | **Heritage** (T8) | confirm G3b PASS **or** supply screen | **SUPPLY-V4 — DONE (new this session).** Added a heritage long-read precision screen (dropcap, numbered `h2`, pullquotes, blockquote+cite, entity-links, `heritage-end` CTA) — the visual authority that G3b's code-review never had. Confirms `page-heritage.php` + `t8.css` are correct **and** gives the screen to verify pixel parity against. | v5 → screen `heritage` |
| **4** | Package scope / SSoT | distributed set **or** consolidated bundle | **CONSOLIDATED.** Single canonical **screen** SSoT = `Precision Mockup v5.html` (T1·T2·T2-single·T3·T4·T5·T7·T8-about·T8-contact·T8-heritage·system·states). Increment docs (HANDOFF_V200 + G2/G3/C2 + §06 patch) = the **build/delta layer**. Build & verify against v5; map via the increment. | this reply + v5 |
| **5** | G3a world-accent CSS fix | land in WP **or** hot-patch | **LAND IT INSIDE THE FULL-IMPLEMENTATION WP.** Do **not** hot-patch standalone. The +25-line `t1.css` override ships in `HANDOFF_CLAUDE_CODE_V200_2026-06-03/`; v5's T1 switcher is its design twin (same selectors, same know/code remap). Verify together. | HANDOFF_V200 `t1.css` |

## What changed in v5 this session (so the diff is auditable)
1. **T2 single-service screen** added (`data-screen="t2s"`) + its module CSS (`.svc-single-hero`, `.feat-grid/.feat-tile`, `.svc-steps/.svc-step`, `.svc-pull`). Reuses crumbs / svc-meta-strip / projects-row / bridge-card / final-cta.
2. **T1 world-accent variant block** (`.t1-world-know` / `.t1-world-code`) — recolours hero stack/echo, gloss rule+word, intro marker, lattice anchor + side borders, post hover, eyebrow, and the page-wash gradient (via the `--soil-wash` tokens). Mirrors the live G3a override.
3. **T1 in-mockup world switcher** (`.world-switch` + small script) — soil/know/code, swaps hero word, gloss line, and marker; proves accent-only divergence.
4. **Heritage screen** added (`data-screen="heritage"`) + long-read CSS (`.heritage-hero/.heritage-meta/.heritage-cover/.heritage-reading/.heritage-body` dropcap·numbered h2·pullquote·blockquote·entity-link, `.heritage-end`).
5. **Switcher nav** extended with `שירות · single` and `מורשת · T8`.
6. **D3 typo swept in the SSoT:** both remaining `מֳר` → `מ״ר` (T1 hero kicker + about timeline). Live was already correct.

## Locks / constraints honoured
- All added copy is the brand's own approved register (heritage lifted from the stage3 `T8 Static.html`, services from `T2 Services.html`). **No new claims, no `TBD` filler.** Both super-locks respected on every added byte (markup, CSS comments, this reply).
- RTL logical properties throughout (`inset-inline-*`, `padding-block`, `margin-inline`).
- v5 is a **mockup/SSoT** — it carries the design authority; the +25-line G3a CSS for *live* still lands via the WP (row 5). No `system.css` touch; no overrides layer.
- a11y: contrast on the recoloured know/code accents uses the existing `--w-*-deep` tokens already cleared at WP006 baseline; verify in the live qa_probe pass (axe 0 / Lighthouse a11y ≥95) — non-regression expected.

## What the full-implementation WP should build/verify against (LOD400 scope)
- **Map each v5 screen → its theme template** (the coverage table in `TEMPLATE_COVERAGE_AUDIT_2026-06-02`).
- **G1 projects archive** (`archive-project.php` + `has_archive=>'projects'`) — still the one genuinely *missing* live template; design already exists (HANDOFF_V200 "Projects Archive Preview" + reuse `.proj-card` grid). Build it.
- **G3a** override lands here (row 5).
- **qa_probe @ 375 + 1440** per the G2/G3/C2 checklist — now with v5 screens as the pixel-parity target for T2(both)/T3/T4/T5/heritage and know/code accents.

*team_35 → team_100 · design-completeness reply · 2026-06-03 · every page-type now has a current precision authority (v5) · 1 live template still to build (projects archive)*
