# QA REQUEST — NB-S002-P009-WP007 DEPLOYED — team_100 → team_50 — v1

**Date:** 2026-06-04
**From:** team_100 (build engine · **Claude Code**)
**To:** team_50 (Validation / dev QA — independent, cross-engine · **Cursor**) · cc team_190, team_00
**Type:** dev-QA dispatch — independent re-measurement (do NOT trust team_100 build claims or the team_190 verdict; re-run from scratch)
**WP:** NB-S002-P009-WP007 — full design implementation / precision walk vs Precision Mockup v5
**Env:** dev `https://nimrod-bio-2026.s887.upress.link` · theme **v0.7.24** (served `?ver=0.7.24`) · `main` @ `043b6391`
**Design SSoT:** `_COMMUNICATION/team_35/HANDOFF_v5_2026-06-03/Precision_Mockup_v5.html`
**LOD400:** `_aos/work_packages/NB-S002-P009-WP007/LOD400_NB-S002-P009-WP007.md` §4 (AT-1..AT-7) + §5 (AC-A..AC-G)

## Why this request
Constitutional L-GATE_VALIDATE (team_190 / Cursor) already returned **PASS** (`VERDICT_NB-S002-P009-WP007_DEPLOYED_2026-06-03_v1.md`) and the WP is closed (LOD500). This request completes the **standard two-track external cycle** — the independent **dev-QA** track (team_50) was routed in the validate-request §Process but no `QA_REPORT_…WP007` is on file yet. Iron Rule #1 preserved: build = Claude Code → QA + validation = Cursor.

## Method (independent — cross-engine)
Re-measure on dev with cache-bust (`?nc=`/`?cb=`). Dev TLS invalid BY DESIGN → cert-bypass is DEV-ONLY (`--ignore-certificate-errors`). Dev SEO/Perf are noindex/cache artifacts — judge a11y only.
1. **axe sweep:** `node scripts/qa/cdp/axe_probe.mjs --config docs/qa/cdp/v200b/team190/axe_config.json` → expect 0 serious/critical + 0 color-contrast across 15 routes.
2. **Lighthouse a11y** (full Chrome via `CHROME_PATH`) on the 8 WP007-affected routes: `/services/`, `/services/consulting-hydro/`, `/services/bcs/`, a project (`/project/rest-x-greenhouse/`), `/blog/`, `/blog/garden-bed-width-80cm/`, `/world/know/`, `/world/code/`, `/about/heritage/` → expect ≥95 (WP006 baseline 100).
3. **qa_probe** overflow + lock-scan @375/1440 on all affected + non-regression routes → expect 0 overflow (incl. single-post@375), 0 forbidden terms.
4. **Visual screenshots** @375 + @1440, diff vs the matching v5 screens: `t2` (services index), `t2s` (consulting-hydro), `t3` (project), `t4` (post), `t5` (blog), `heritage`, `t1` know/code.
5. **Lock-scan** (Micha; demonstrate-never-name) on rendered DOM incl. alt/aria.

## Per-AT spot-checks (dev)
- **AT-1** services index: page-hero → svc-grid 2-col (→1 @760) → bridges-band (2 cards) → final-cta.
- **AT-2** t2s: anchor `consulting-hydro` shows full v5 (hero `1.2fr .85fr`→820, feat-grid 2×2, svc-pull, svc-steps, bridge); **services without the new meta degrade gracefully** (e.g. `/services/teaching/` = hero + final-cta, no broken sections); BCS gallery + meta-strip preserved.
- **AT-3** t3 (greenhouse): hero `clamp(34px,5vw,64px)`, 4 outcomes, **final-cta present**.
- **AT-4** t4: `.post-hero-meta-top`, **NO numbered body h2**, sticky aside, single-post@375 **0 overflow** (WP006 carry-forward).
- **AT-5** t5: header+stats, filter toolbar + view-toggle, grid/flow, blog-end.
- **AT-6** world: know=orange / code=teal / soil=base; `--w-*-deep` on washes ≥4.5.
- **AT-7** heritage: numbered h2 + dropcap + AA.

## Advisories (already on record — non-blocking)
- **t2s scope expansion** (team_00-authorized): new service meta fields + anchor seeded; per-service content for the other 6 services → **P008 SERVICE_CONTENT_FILL** (graceful-fallback today).
- **Class-vocabulary deltas:** live `.linked-projects` vs v5 `.projects-row`; T5 filter/grid naming vs mockup — functional equivalents (§4 advisory).

## Output
`_COMMUNICATION/team_50/QA_REPORT_NB-S002-P009-WP007_DEPLOYED_2026-06-04_v1.md` — headline verdict + axe by-rule/per-page matrix + Lighthouse table + overflow/lock matrix + per-AT spot-check results + screenshots dir. cc team_190 + team_100. Any STOP-class finding on a closed WP → reopen route to team_100 (ADR042).

*team_100 (Claude Code) → team_50 (Cursor) · dev-QA dispatch · 2026-06-04 · v0.7.24 @ 043b6391 · build=Claude → QA=Cursor*
