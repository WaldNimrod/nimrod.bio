# VALIDATE REQUEST — NB-S002-P009-WP006 (a11y sweep) — team_100 → team_190 — v1

**Date:** 2026-06-03
**From:** team_100 (build engine = Claude Code)
**To:** team_190 (constitutional L-GATE_VALIDATE — cross-engine, immutable) + team_50 (dev QA / axe re-run)
**Type:** L-GATE_VALIDATE — deployed result
**WP:** NB-S002-P009-WP006 · **Env:** dev `https://nimrod-bio-2026.s887.upress.link` · theme **v0.7.18**
**Spec:** `_aos/work_packages/NB-S002-P009-WP006/LOD400_NB-S002-P009-WP006.md` (L-GATE_SPEC PASS, team_100)

## Iron Rule #1 (cross-engine) — MANDATORY here
Build engine = **Claude Code (team_100)**. Therefore validation **must** be a different engine: **team_190 (Codex)** constitutional verdict + **team_50** independent axe/device check. Do **not** trust the team_100 self-measurements below — re-execute every claim.

## Context
Origin: team_190's own L-GATE_VALIDATE advisory on P009-WP001 (`VERDICT_NB-S002-P009-WP001_DEPLOYED_2026-06-03_v1` §2) flagged pre-existing, site-wide `color-contrast` / `heading-order` / `aria-hidden-focus`. This WP is the dedicated remediation. All edits in **module CSS (`assets/css/*.css`) + template-parts/templates only** — **no inline, no overrides layer, `system.css` (LOCKED) byte-identical.**

## team_100 self-measurement (re-validate independently — do NOT trust)
- **axe-core** (cached, injected over CDP via `scripts/qa/cdp/axe_probe.mjs`, WCAG 2A/2AA + best-practice) across **15 pages**: **0 violations of ANY impact** (was 78 serious color-contrast + 24 heading-order + landmark/aria at baseline). Result JSON: `docs/qa/cdp/v200b/team190/axe_result.json`.
- **Lighthouse accessibility = 100** on all 6 key pages (home, /about/, /contact/, /projects/, /project/tiktrack/, /services/bcs/). JSON: `docs/qa/cdp/v200b/team190/lh/*.json`.
- **CDP** (`qa_probe.mjs`): 0 horizontal overflow @375/1440, 0 forbidden terms (rendered DOM) across 10 pages.
- **Byte-parity 8/8** served==repo (changed CSS+JS); **`system.css` git-unchanged**; version 0.7.16→0.7.18.
- **CSS balanced / `php -l` clean / `node --check` clean** on all changed files. **Super-locks**: 0 forbidden terms in any changed byte (incl. alt/aria/comments).

## How the contrast was fixed WITHOUT touching locked tokens
The locked "light" world tokens + spark fail AA for small text on paper (`--w-soil` 3.56 · `--w-know` 3.45 · `--w-code` 3.69 · `--spark` 4.31). Fix points **text / solid-chip / CTA** uses at the existing **deep** variants in the consuming module rules; adds two theme-local a11y-safe spark derivatives (`--spark-on-dark #e8645a` 5.08 on ink, `--spark-on-paper #c22f25` 5.07 on paper) for the contexts where deep/locked spark can't pass. Verified numerically in `docs/qa/cdp/contrast_verify.mjs`.

## Pages to validate (15)
home · /about/ · /contact/ · /projects/ · /services/ · /world/soil/ · /world/know/ · /world/code/ · /about/heritage/ · /project/tiktrack/ · /services/bcs/ · /blog/ · /blog/garden-bed-width-80cm/ · /?s=garden · 404.
**Config:** `docs/qa/cdp/v200b/team190/axe_config.json` (reuse for the axe re-run).

## Acceptance to check (LOD400 §4)
AC-1/2 axe 0 serious + 0 color-contrast per page · AC-3 0 heading-order · AC-4 landmark/page-h1 resolved · AC-5 Lighthouse a11y ≥95 key pages · AC-6 0 forbidden (rendered + source incl. alt/aria) · AC-7 0 overflow @375/1440 · AC-8 no inline/no overrides, system.css identical, byte-parity · AC-9 validate_aos 0 FAIL (after this commit lands).

## Notable beyond the advisory (please spot-check)
- Fixed a **pre-existing comma-selector bug** (`.t8-heritage-hero, .heritage-hero .stamp …`) that painted the **heritage hero** soil-deep green with inherited dark text (1.9 contrast) → corrected to descendant combinators (AA).
- Nav `.atop` scrim was overridden by a **second rule in t7.css** (not just shell.css) — both lifted .5→.72.
- `nav-drawer` gets `inert` while closed (toggled by `nav-drawer.js`) for `aria-hidden-focus`.

## Process
- **team_50:** re-run axe (`axe_probe.mjs --config …`) + Lighthouse-a11y + per-page spot check + lock-scan → `QA_REPORT`.
- **team_190:** constitutional L-GATE_VALIDATE → `VERDICT_NB-S002-P009-WP006_*`. On PASS, team_100 closes per ADR042 (L2 spoke: roadmap LOD500 + git audit).

## Out of scope (carry-forward)
SEO/Perf Lighthouse (dev edge artifacts, re-measure at cutover) · pre-existing non-rendered comment terms `recursion`/`negentropy` (DRIFT_REGISTER A13 cleanup) · NB-S002-P009-WP007 (G2/G3 precision walk).

*team_100 | validate request (deployed result, L-GATE_VALIDATE) | 2026-06-03 | v0.7.18 | build=Claude → validate=Codex+team_50*
