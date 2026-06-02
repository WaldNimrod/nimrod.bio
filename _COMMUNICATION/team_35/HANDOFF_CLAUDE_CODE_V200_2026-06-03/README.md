---
type: HANDOFF (master package)
from: team_35 (Site Design + Build — Claude Design)
to: Claude Code team (live theme build · WordPress · Hebrew RTL · uPress)
cc: team_00 (Nimrod), team_100, team_50
project: nimrod-bio
milestone: V200 (UI precision pass)
wp_id: NB-S002-P009-WP001
theme: nimrod-bio-2026 · baseline commit a35a67df · v0.7.15 → bump on deploy
date: 2026-06-03
status: COMPLETE — ready to land + deploy
---

# HANDOFF — nimrod-bio V200 precision package (Claude Code)

Everything from the precision session in one package: **two new templates** + **every fix/precision**
from the three input sources (team_00/Nimrod comments · team_35 own checks · team_50 live artifacts).
All changes land in **module CSS / template-parts / theme source** — **no inline styles, no overrides
layer** (binding rule, team_00). Lock-scan: **0 forbidden terms** across all diffs (incl. alt/aria/comments).

## 0 · Package contents
```
HANDOFF_CLAUDE_CODE_V200_2026-06-03/
├── README.md                         ← this file (master index)
├── theme/                            ← drop-in theme source (land on a35a67df)
│   ├── front-page.php                ← §06 recent-posts block added
│   ├── archive-project.php           ← NEW · projects archive (/projects/)
│   ├── inc/
│   │   ├── cpt-project.php           ← has_archive false → 'projects'
│   │   └── template-styles-t1.php    ← enqueue t1.css on project archive
│   └── assets/css/
│       ├── t7.css                    ← §06 grid · bridge-title underline · Unless restructure
│       └── t1.css                    ← projects-archive block · lattice crush fix · bridge-title underline
├── S06 Recent-Posts Preview.html     ← standalone render-parity preview
└── Projects Archive Preview.html     ← standalone render-parity preview
```
Companion docs (same `team_35/` folder, full detail):
`COMPLETION_PRECISION_SESSION_V4_2026-06-02_v1.md` · `HANDOFF_TO_CLAUDE_CODE_LIVE_PRECISION_2026-06-02_v1.md`
· `TEMPLATE_COVERAGE_AUDIT_2026-06-02_v1.md` · `PATCH_T7_S06_RECENT_POSTS_2026-06-02_v1/`.

---

## 1 · NEW TEMPLATES

### T-NEW-1 · §06 Recent-Posts / blog teaser (home)
- **File:** `front-page.php` (+ `assets/css/t7.css`).
- **What:** `WP_Query` (post · publish · 5 · newest first) → `section.t7-section.t7-posts > .posts-grid`
  with newest as `.rp-card.feat` (+ excerpt) and 4 `.rp-card` (title + world chips from each post's
  `world` taxonomy terms). Placed **between §05 projects and the manifesto**.
- **CSS:** canonical `.posts-grid` / `.rp-card.feat` block in `t7.css`; **three stale scaffolds retired**
  (`.recent-posts`, `.posts-grid-4`, `.post-card.post-square`).
- **Preview:** `S06 Recent-Posts Preview.html`. (Detail: `PATCH_T7_S06_RECENT_POSTS_2026-06-02_v1/`.)

### T-NEW-2 · Projects archive `/projects/` (the one missing template — G1)
- **Files:** `archive-project.php` (NEW) · `inc/cpt-project.php` · `inc/template-styles-t1.php` · `assets/css/t1.css`.
- **Why:** `project` CPT was `has_archive => false`, yet home §05 + T1 link to `/projects/` → no template.
- **What:**
  - `cpt-project.php`: `has_archive => false` → **`'projects'`**.
  - `archive-project.php`: lists **all published projects** (newest first) as canonical proj-cards
    (`.ph` + `.scope-row` scope chip + `nb_stage_stamp()` + `h3` + summary + world meta) — same markup
    + helpers as front-page §05. Empty-state fallback included.
  - `template-styles-t1.php`: enqueue `t1.css` on `is_post_type_archive('project')` (joins world pages
    + service archive — the t1 style family).
  - `t1.css`: self-contained `.projects-archive` block (3-up → 2-up ≤1000 → 1-up ≤640), scoped so it
    does not disturb the legacy `.proj-card .img-ph/h4` variant.
- **Preview:** `Projects Archive Preview.html`.
- **After deploy:** flush permalinks (Settings → Permalinks → Save) so `/projects/` resolves.

---

## 2 · FIXES + PRECISIONS — by source

### A · From team_00 (Nimrod), 2026-06-02/03
| # | Item | Mockup | Live theme change |
|---|---|---|---|
| A1 | **Unless lockup** — present the English source large, Hebrew translation small beneath it (stacked, not side-by-side) | done | `t7.css` `.unless-lockup`: `.inner` grid → `block`; `.word` margin-block-end; `.gloss` borderless; `.he` → small sans, muted (`clamp(15px,1.5vw,18px)`, `rgba(245,243,236,.62)`); responsive gloss border dropped. **Markup unchanged.** *(The original-language quotation text is owner-supplied — not authored here.)* |
| A2 | **Bridge cards** — underline on the **title only**, body text clean | done | `t7.css` `.t7-bridges .bridge-card h3` + `t1.css` `.bridge-card h3`: add `text-decoration:underline; thickness 1.5px; offset 4px; color rgba(31,30,28,.32)`. (`t1.css` `.bridge-card` also gets `text-decoration:none` for parity; t7 already had it.) |
| A3 | **Δ1 · line-breaks** — full lines, no forced/awkward wrapping (mockup correct, live drifts) | n/a | Remove hard `<br>` from lede/body strings; match measure (~60ch); `text-wrap:pretty` (body) / `balance` (headings). Detail: `HANDOFF_TO_CLAUDE_CODE_LIVE_PRECISION…md` Δ1. |
| A4 | **Δ2 · world-card images** — uniform forced `16/10` crop, equal heights (mockup correct, live drifts) | n/a | `.world-card .wcard-media{aspect-ratio:16/10} > img{position:absolute;inset:0;object-fit:cover}` in the world-card module CSS. Detail: same doc, Δ2. |

### B · From team_35 own checks (mockup walk, all 6 screens)
| # | Item | Mockup | Live theme change |
|---|---|---|---|
| B1 | **T1 lattice crush** — at ≤900px the `.lat-side` inline `grid-column:3/4` (`t1-body.php`) survives the breakpoint, forces a phantom column and crushes the anchor to a one-word-per-line sliver | done | `t1.css` mobile block: `.vc-lattice > *{grid-column:auto!important;grid-row:auto!important}` + `.lat-anchor{grid-column:1/-1!important; order:-1}`. **Same bug exists live** (`t1-body.php` carries the inline). Fix is CSS-only; markup may keep the inline. |
| B2 | **`מֳר` → `מ״ר`** (vocalized → gershayim) | fixed 3× in mockup (hero kicker, T1 lattice facts, about timeline) | **No live change — theme has zero `מֳר` occurrences** (already correct). Mockup-SSOT-only fix. |
| B3 | **Home bridges 3-up** flagged by team_50 as drift | — | **Not a drift.** Mockup home `.bridges-grid` is intentionally 3-up (the three pairwise seams); only the **world-page `.vc-bridges`** is 2-up, which live already satisfies. No change. |

### C · From team_50 (live artifact pack, 2026-06-02)
| # | Item | Status |
|---|---|---|
| C1 | **§06 absent on live + repo** | **Resolved** by T-NEW-1 (deploy to close). |
| C2 | **Sub-document `<img>` overflow** on home (26px @375, 101px @1440; no scrollWidth growth — contained) | **Open · localize.** Likely a `.ph`/`.media` cover image or the manifesto `.mf-bg` watermark exceeding its box. team_50/Claude Code to pin the node and clamp (`max-width:100%` / `object-fit` / overflow). Not blocking. |
| C3 | **Route map** — t1 אדמה = `/world/soil/` (not `/world/adama/`) | Informational; confirmed. |
| C4 | **A/B/C byte-check** — eyebrow mono · WA green `#1f8a4c` · contact form/social · about prose + t8-final-cta · `:root` tokens + line-height 1.55 (26.35px/17px) | **All match mockup** — no change. |

---

## 3 · Deploy (per `docs/UPRESS_FTPS_MANDATORY_PROCEDURE.md`)
1. Land all `theme/` files onto baseline `a35a67df`.
2. Bump `NB_THEME_VERSION` (v0.7.15 → next); deploy via FTPS.
3. **Flush permalinks** (Settings → Permalinks → Save) so `/projects/` (new `has_archive`) resolves.
4. Pull deployed files back (one per connection); verify **byte-parity** repo == deployed; cache-bust (version query on assets).
5. `node scripts/qa/cdp/qa_probe.mjs` — expect: §06 present on `/`; `/projects/` renders the archive
   (real projects, scope+stage stamps); T1 lattice intact at 375/900/1440 (no crushed anchor);
   bridge titles underlined / body clean; Unless stacked (English large, Hebrew small);
   0 horizontal overflow; **lock-scan 0**.
6. Localize + clamp C2 (img overflow).

## 4 · Locks
Every added/changed string — markup, CSS, comments, alt/aria, this package — lock-scanned: **0 forbidden
terms**. §06 + archive copy is the approved mockup/site text; live post & project titles/excerpts are
honored read-only by the queries (no rewriting). The Unless original-language quotation is owner-supplied.

## 5 · Still open (not in this package — for team_00 prioritization)
- **G2** — precision walk for T2 Services (index+single), T3 Project single, T4 Post single, T5 Blog index
  (exist as theme PHP + design files; never in the v4 precision pass).
- **G3** — know/code world-page variants + heritage page parity check.
- **C2** — img-overflow localization (team_50/Claude Code).
(See `TEMPLATE_COVERAGE_AUDIT_2026-06-02_v1.md`.)

---

*team_35 → Claude Code | V200 precision package · 2 new templates + all fixes (3 sources) | 2026-06-03 | locks 0 | on a35a67df*
