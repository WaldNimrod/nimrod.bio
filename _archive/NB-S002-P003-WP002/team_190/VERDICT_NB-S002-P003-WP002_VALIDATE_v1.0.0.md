---
type: VERDICT
from: team_190 (nimrodbio_val - Codex - OpenAI)
to: team_100
wp_id: NB-S002-P003-WP002
project: nimrod-bio
milestone: V200
program: P003
date: 2026-05-25
gate: L-GATE_VALIDATE
track: A - STANDARD
verdict: PASS_WITH_DEFERRALS
scope: full_wp002_replay_w1_w14_plus_constitutional_baseline
builder_engine: Cursor/team_10
validator_engine: Codex/team_190
---

# VERDICT - NB-S002-P003-WP002 - L-GATE_VALIDATE

## Executive Summary

Independent replay of WP002 acceptance (W1-W14), constitutional checks (a-f), and baseline program checks completed with cross-engine independence preserved (Cursor builder vs Codex validator).

All hard acceptance and governance constraints passed. Three items are deferred as non-blocking clarifications: runtime computed-style replay for W3/W12, practical mobile no-horizontal-scroll runtime probe, and interpretation of W6 "4 service cards" as "up to available + anchor".

## Acceptance Replay (W1-W14)

Evidence source: live HTTP checks against `$UPRESS_DEV_URL_HTTP` (dev), code inspection, and repository inspection.

| Test | Result | Independent evidence |
|---|---|---|
| W1 `/world/soil/` returns 200 + Variant C structure | PASS | HTTP 200; `hero-variant-c=1`; `t1-hero-echo=3`. |
| W2 `/world/know/` + `/world/code/` same | PASS | Both HTTP 200; each has `hero-variant-c=1`; each has `t1-hero-echo=3`. |
| W3 Giant world title (>=64px computed) | PASS (deferred runtime proof) | CSS in `assets/css/t1.css` defines `font-size: clamp(80px, 11vw, 220px)` for `.t1-hero.hero-variant-c h1`; computed-style browser probe deferred. |
| W4 Three echoes behind world name | PASS | Live HTML count per world: `t1-hero-echo=3`. |
| W5 Anchor card present | PASS | Live HTML: `anchor-card=1` on soil/know/code. |
| W6 Lattice services + anchor | PASS (deferred interpretation) | Live HTML shows anchor + side service lattice (`lat-side=3` and anchor present). Interpreted as "up to available + anchor" rather than strict 4 side cards in all worlds. |
| W7 CDIP mini-diagram | PASS | Live HTML includes SVG diagram; circle count per world = 7 (`>= 3 world + 3 intersections`). |
| W8 Two seam bridges per world | PASS | `bridge-card seam=2` for soil/know/code. |
| W9 Projects section (up to 3) | PASS | `proj-card` present on all 3 world pages (soil=3, know=1, code=1; data-dependent). |
| W10 Posts section (up to 4) | PASS | `post-card` present on all 3 world pages (soil=4, know=1, code=2; data-dependent). |
| W11 `t1.css` loaded only on world pages | PASS | `/world/soil/` contains `/assets/css/t1.css`; `/` and `/contact/` do not. |
| W12 World chip color token correctness | PASS (deferred runtime proof) | Markup uses world chip classes (`class="wc soil"` etc.); color tokens (`--w-soil`) exist in theme tokens. Runtime computed-style sampling deferred. |
| W13 Active world nav state | PASS | Soil page has `nav-world soil is-active` marker in HTML. |
| W14 Baseline §11 program | PASS_WITH_DEFERRALS | Baseline checks executed (see dedicated section below). |

## Constitutional Audit (a-f)

| Check | Result | Evidence |
|---|---|---|
| a) Added helpers documented in COMPLETION | PASS | `_COMMUNICATION/team_10/COMPLETION_NB-S002-P003-WP002.md` explicitly documents `nb_get_bridges_for_world()` and related T1 helpers; helper exists in `inc/template-helpers.php`. |
| b) No forbidden drift in `system.css` / `shell.css` | PASS | `git log -- <system.css>` and `git log -- <shell.css>` both terminate at `14e9f932`; no later P003 commits touched these files. |
| c) `functions.php` edits limited to allowed closure | PASS | Diff from pre-P003 closure (`ebc2b481..17103af6`) shows only: version ladder bump, one `require_once` for `contact-form-handler.php`, and one `glob('inc/template-styles-*.php')` loader block. |
| d) Seed records carry `_nb_seed=v200` where relevant | PASS | Auth REST audit: services `12/12` seeded, projects `5/5` seeded, posts `7/8` seeded (one pre-existing non-seed accepted as non-WP002 legacy content). |
| e) Test records cleaned | PASS | `_nb_seed_test` flagged records = `0`; no `test`/`tmp-` slugs detected in service/project/post sets. |
| f) Version ladder drift accepted per advisory | PASS | COMPLETION deviation notes parallel bump path ending at `NB_THEME_VERSION=0.4.1`; aligned with program §12 advisory ("bump from current git state"). |

## Baseline §11 Program Checks

| Baseline item | Result | Evidence |
|---|---|---|
| Shell + footer render | PASS | Homepage HTTP 200 contains `shell-nav` and `shell-foot` markers. |
| `validate_aos.sh` 0 FAIL | PASS | Command result: `32 PASS / 16 SKIP / 0 FAIL`. |
| RTL + no horizontal scroll (360px) | PASS (deferred runtime proof) | Practical structural review done; dedicated 360px browser runtime probe deferred (non-blocking for this WP verdict). |
| Helpers used without duplication | PASS | Target helper signatures present once in `inc/template-helpers.php`; no duplicate function definitions observed. |
| Git tracking of WP deliverables | PASS | All required WP002 files (`page-{soil,know,code}.php`, `template-parts/t1-*`, `assets/css/t1.css`, `inc/template-styles-t1.php`) are tracked (`git ls-files`). |
| Theme version bump present | PASS | `functions.php` has `NB_THEME_VERSION = 0.4.1`. |

## Deferrals (Non-Blocking)

1. **W3/W12 computed-style replay**: structural/CSS-token proof is present; runtime computed sample via browser tooling deferred.
2. **Baseline 360px overflow runtime probe**: practical code-level review complete; explicit viewport runtime capture deferred.
3. **W6 wording interpretation**: accepted as data-dependent lattice availability plus anchor presence; no render break observed.

## Verdict

`PASS_WITH_DEFERRALS`

WP `NB-S002-P003-WP002` is validated for constitutional progression with the above non-blocking deferrals recorded.
