# VALIDATE REQUEST — NB-S002-P009-WP001 DEPLOYED RESULT — team_100 → team_190 — v1

**Date:** 2026-06-03
**From:** team_100
**To:** team_190 (constitutional L-GATE_VALIDATE, cross-engine, immutable) + team_50 (dev QA)
**Type:** L-GATE_VALIDATE — deployed result (follows the LOD400 spec gate PASS_WITH_FINDINGS)
**WP:** NB-S002-P009-WP001 · **Env:** dev `https://nimrod-bio-2026.s887.upress.link` · theme **v0.7.16** · commit **ea9105cc**

## Context
The LOD400 spec passed L-GATE_SPEC (`VERDICT_NB-S002-P009-WP001_LOD400_2026-06-03_v1.md`, PASS_WITH_FINDINGS). team_100 then executed the atomic land+deploy (Iron Rule #4 single writer; ADR034 R9 L2 spoke — file-based SSoT, hub DB offline, no DB mutation). This request validates the **deployed result** against the LOD400 §6 acceptance criteria.

## team_100 build-verification already done (independent re-validate requested, do not trust)
- Landed 6 handoff files + version bump 0.7.15→0.7.16; PHP lint clean; CSS braces balanced.
- Deployed via FTPS; **byte-parity 5/5** repo==deployed; permalinks auto-flushed (rewrites.php on version change) → `/projects/` 200.
- **§06** renders on `/` (t7-posts → posts-grid → 1 feat + 4 rp-card, world chips); **/projects/** renders archive (proj-cards, "פרויקטים" heading, real projects).
- A1 Unless stacked · A2 bridge-title underline (served CSS confirmed) · B1 lattice mobile fix (served) · scaffolds retired (comments only).
- **CDP 22/22 PASS** — 11 pages × 375/1440, 0 horizontal overflow, 0 forbidden terms (incl. `/projects/`). Evidence: `docs/qa/cdp/v16/`.

## Validate against LOD400 §6 (11 acceptance criteria)
Re-verify each independently on dev: §06 present + real posts + world chips; `/projects/` 200 + all published projects + empty-state path; has_archive flip + links resolve; t1 enqueued on archive; lattice intact ≤900px (no crushed anchor); bridge underline title-only; Unless stacked (EN large/HE small); Δ1 no awkward breaks; Δ2 world-card 16/10 equal heights; scaffolds retired; CDP 0-overflow + lock-scan 0; byte-parity; no inline/no overrides layer.

## Process for team_50 + team_190
- **team_50:** full dev QA pass — per-page matrix + screenshots + Lighthouse (note dev SEO/Perf are noindex/cache artifacts) + lock-scan → `QA_REPORT`.
- **team_190:** constitutional L-GATE_VALIDATE → `VERDICT_NB-S002-P009-WP001_DEPLOYED_*`. On PASS/PASS_WITH_DEFERRALS, team_100 closes WP per ADR042 (this L2 spoke: roadmap LOD500 + git audit).

## Carry-forward (LOD400 §7, NOT in this WP — for team_00 prioritization)
G2 (T2/T3/T4/T5 precision walk) · G3 (know/code world variants + heritage parity) · C2 (home img-overflow localize, contained/non-blocking).

*team_100 | validate request (deployed result, L-GATE_VALIDATE) | 2026-06-03 | v0.7.16 @ ea9105cc*
