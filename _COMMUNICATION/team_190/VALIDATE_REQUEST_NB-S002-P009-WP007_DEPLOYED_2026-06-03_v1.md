# VALIDATE REQUEST — NB-S002-P009-WP007 DEPLOYED RESULT — team_100 → team_190 — v1

**Date:** 2026-06-03
**From:** team_100 (implementation session · **Claude Code** — build engine)
**To:** team_190 (constitutional L-GATE_VALIDATE, cross-engine, immutable — **MUST be Cursor** per Iron Rule #1; Codex unavailable) + team_50 (dev QA)
**Type:** L-GATE_VALIDATE — deployed result (follows L-GATE_SPEC PASS_WITH_FINDINGS, `VERDICT_NB-S002-P009-WP007_LOD400_2026-06-03_v1.md`)
**WP:** NB-S002-P009-WP007 — full design implementation / precision walk vs **Precision Mockup v5**
**Env:** dev `https://nimrod-bio-2026.s887.upress.link` · theme **v0.7.24** · branch **`wp007-design-impl`** @ **cb3f632d** (Phase 1+2 `45096fb4`, Phase 3 `cb3f632d`)
**Design SSoT:** `_COMMUNICATION/team_35/HANDOFF_v5_2026-06-03/Precision_Mockup_v5.html`

## Context
Built by Claude Code (team_100-orchestrated) in 3 deploy+verify phases per LOD400 §6. **Iron Rule #1: build=Claude → this L-GATE_VALIDATE must run on Cursor (≠ author engine).** ADR034 R9 L0 spoke — file-based SSoT; hub DB online but spoke structured mutations are file/git-audit, not DB. Re-verify all independently; do not trust this session's results.

## ⚠ SCOPE EXPANSION on the record (team_00-authorized, beyond gate-approved LOD400 §3)
- **AT-2 (t2s) full v5 rebuild** was authorized by team_00 on 2026-06-03 as a scope expansion. The gate-approved LOD400 §3 listed new content/data-layer as OUT. Implementation: `single-service.php` rewritten to the v5 composition; **4 new service meta fields registered** (`feat_tiles`/`svc_steps`/`svc_pull`/`bridge`, `inc/meta-registration.php`); the **anchor service `consulting-hydro` seeded** with v5-approved copy on **local + dev** (`scripts/seed_wp007_t2s_*`). All other services render via **graceful per-field fallback** (sections appear only when meta present). Per-service t2s content fill for the remaining services is **P008 (SERVICE_CONTENT_FILL) territory** — not done here.

## Build-verification already done (independent re-validate requested)
**Phase 1 (G3a + world variants + heritage):** G3a override lifted from v5 L896–913 into live `t1.css` (selector-mapped `.post-row→.post-card`, `.eyebrow→.s-eyebrow`, `h5→h3`, `.lat-*→.vc-lattice`). Computed-color verified: soil unchanged / know `#9a4f2b`+`#c46a3e` / code `#1f5e60`+`#2d8a8c`; washes per v5. Heritage confirmed at v5 structure (verification-only).
**Phase 2 (t2s + T3):** t2s rebuilt (above). T3 drift-fix — hero title → v5 scale `clamp(34px,5vw,64px)/-.025em` (L822); added the missing `.final-cta` close band.
**Phase 3 (T2 index + T4 + T5):** T2 index (`archive-service.php`) rebuilt to v5 — page-hero (eyebrow·h1·lede·stats) → 2-col `svc-grid` service cards (world-icon + points + CTA, CPT-driven) → `bridges-band` (2 `.svc-bridge`) → `final-cta`; existing locked h1/lede preserved, new sections use v5-approved register; sr-only section h2 keeps heading order. T4 — **fixed pre-existing single-post@375 overflow** (related block reuses t5 post-grid → t5.css now enqueued on posts; scrollWidth 1044→375) + **removed post-body h2 numbering** (`nb_prepare_post_body_html`; heritage-only per F1; h2 ids kept for ToC). T5 — verified to v5 fidelity (page-hero+stats, filter toolbar+view-toggle, featured+grid/flow, blog-end already shipped & a11y-clean; equivalent vocabulary, advisory class-name deltas per §4 — no code change).

## Gates (LOD400 §5) — all green on dev
- **AC-A a11y:** `axe_probe --config …/axe_config.json` → **0 serious/critical + 0 color-contrast** across the full 15-route sweep. Lighthouse a11y **100** on world-know/world-code/heritage/consulting-hydro/rest-x-greenhouse/services/single-post/blog (WP006 baseline 100 held — services briefly hit 98 on a heading-order skip, fixed via sr-only h2 → 100).
- **AC-B overflow:** `qa_probe` **30/30 pass** @375/1440 — incl. the previously-failing **single-post@mobile now 0**.
- **AC-C locks:** 0 forbidden terms (Micha; demonstrate-never-name) in rendered DOM across all routes.
- **AC-D discipline:** edits in `assets/css/*.css` + templates + `inc/` (meta-registration + enqueue + post-body helper) only; **0 inline styles added** (svc-step numeral is a CSS counter; bridge spine uses world-pair modifier classes); no overrides layer; **`system.css` byte-identical** (`git diff` empty).
- **AC-E deploy:** `NB_THEME_VERSION` 0.7.19→**0.7.24**; FTPS per mandate; **byte-parity served==repo** confirmed (t1/t2/t3.css SHA-match).
- **AC-F non-regression:** full 15-route axe + 30-row qa_probe pass; wired galleries + WP002 mobile + WP006 a11y intact.
- **AC-G validate_aos:** **32 PASS / 16 SKIP / 0 FAIL** — L-GATE_BUILD criterion SATISFIED.

## Re-verify against LOD400 §4 ATs (Cursor)
AT-1 T2 index (page-hero h1 `clamp(40px,6.2vw,84px)`, svc-grid 2-col→760, bridges-band 2-up, final-cta) · AT-2 t2s (hero `1.2fr .85fr`→820, feat-grid 2×2, svc-steps, svc-pull, bridge, BCS gallery+meta preserved; anchor seeded, others fallback) · AT-3 T3 (hero `clamp(34px,5vw,64px)`, outcomes 4-up, final-cta present) · AT-4 T4 (`.post-hero-meta-top`, NO numbered body h2, sticky aside, 0 overflow) · AT-5 T5 (header+stats, filter+view-toggle, grid/flow, blog-end) · AT-6 world know=orange/code=teal/soil-base, deep tokens AA on washes · AT-7 heritage numbered h2 + dropcap + AA.

## Process
- **team_50:** full dev QA matrix + screenshots + Lighthouse (dev SEO/Perf are noindex/cache artifacts) + lock-scan → `QA_REPORT`.
- **team_190 (Cursor):** constitutional cross-engine L-GATE_VALIDATE → `VERDICT_NB-S002-P009-WP007_DEPLOYED_*`. On PASS, team_100 closes per ADR042 (roadmap LOD500 + git audit; merge `wp007-design-impl`).

## Carry-forward (not this WP — for team_00 prioritization)
Per-service t2s structured content (feat_tiles/svc_steps/svc_pull/bridge) for the 6 non-anchor services → **P008 SERVICE_CONTENT_FILL** (anchor is the v5 reference; others graceful-fallback today). · T5 class-name parity with v5 (`.rp-card.feat`/`.blog-toolbar`) is an advisory delta (working equivalent shipped). · DRIFT_REGISTER A13 pre-existing comment terms unchanged.

*team_100 (Claude Code) | L-GATE_VALIDATE request (deployed result) | 2026-06-03 | v0.7.24 @ cb3f632d | branch wp007-design-impl | build=Claude → validate=Cursor*
