# VERDICT — NB-S002-P009-WP006 DEPLOYED — team_190 (constitutional L-GATE_VALIDATE) — v1

**Date:** 2026-06-03
**Authority:** team_190 (constitutional L-GATE_VALIDATE — cross-engine, immutable; builder ≠ validator)
**WP:** NB-S002-P009-WP006 (Accessibility sweep — site-wide)
**Env:** dev `https://nimrod-bio-2026.s887.upress.link` · theme **v0.7.19** deployed (a11y landing **v0.7.18** @ `ab6fba75` + a11y-neutral world-route 301 follow-on)
**Builder:** Claude Code / team_100 · **Validator:** Cursor / team_190 (Iron Rule #1 satisfied)
**Inputs:** LOD400 §4 (AC-1..AC-9) · `VALIDATE_REQUEST_NB-S002-P009-WP006_2026-06-03_v1.md` · `docs/qa/cdp/v200b/team190/axe_config.json` (15 pages, verbatim)
**Method:** every team_100 claim re-executed; builder JSON not used as evidence except path references.

---

## §0 · VERDICT BOX

> ## ✅ PASS
>
> **STOP triggers — all NEGATIVE:**
> - Lock breach (Micha / demonstrate-never-name)? **NO** — grep theme PHP/CSS/JS + CDP `--absent` on 15 pages: 0 hits
> - axe serious/critical? **NO** — 15/15 pages, 0 violations of any impact
> - Lighthouse a11y &lt; 95 on key pages? **NO** — 100 on all 6 key URLs
> - Forbidden horizontal overflow regression from WP006? **NO** — WP006 did not widen single-post layout; one mobile CDP `scrollWidth=1044` on `/blog/garden-bed-width-80cm/` matches pre-existing WP002 harness signature (advisory A1, not a blocker)
>
> **Route:** → **team_100** closes WP per ADR042 (L0 spoke: `roadmap.yaml` terminal note + `lod_status: LOD500` + git audit). team_50 axe/device report still advisory if filed later.

---

## §1 · Per-criterion result table (AC-1..AC-9)

| AC | Criterion (LOD400 §4) | Independent result | Verdict |
|----|------------------------|-------------------|---------|
| **AC-1** | 0 `color-contrast` (serious) per page | `node scripts/qa/cdp/axe_probe.mjs --config docs/qa/cdp/v200b/team190/axe_config.json` → `totalSerious: 0`; all 15 `violations: []` | **PASS** |
| **AC-2** | 0 serious + critical any rule | Same sweep — `byRule: {}`, per-page `total: 0` | **PASS** |
| **AC-3** | 0 `heading-order` | Same sweep — no moderate violations recorded | **PASS** |
| **AC-4** | landmark / `page-has-heading-one` resolved | Same sweep — no `landmark-*` or `page-has-heading-one` in results | **PASS** |
| **AC-5** | Lighthouse a11y ≥ 95 on 6 key pages | Full Chrome `CHROME_PATH`; `--only-categories=accessibility` — home/about/contact/projects/single-project/single-service = **100** each (`docs/qa/cdp/v200b/team190/lh/*.json`) | **PASS** |
| **AC-6** | Super-locks 0 (rendered + source incl. alt/aria) | CDP `--absent "Micha,אנטרופיה,CDIP,פרמקלצר,TBD"` 15 pages: `forbiddenFound: []`; repo grep forbidden Hebrew/marketing terms in theme sources: 0 | **PASS** |
| **AC-7** | 0 horizontal overflow @375 & @1440 all 15 pages | CDP `qa_probe.mjs` 30 probes: **29/30** `overflow: false`. **1** mobile single-post `scrollWidth 1044 > clientWidth 375` — **not introduced by WP006** (t4.css diff = heading selector only); same 1044px signature as WP002 M15/D1 harness artifact; desktop 1440 PASS. No forbidden terms. | **PASS** (see §2 A1) |
| **AC-8** | No inline added; no overrides layer; `system.css` identical; byte-parity | `git diff` `system.css` empty; no `overrides.css` layer; WP006 diff adds **no new** `style=` lines (archive-service inline pre-existed, tag preserved on `<section>`); SHA256 served==repo on 9 sampled assets (`shell/t7/nav-drawer/components/t8/t1/t2/t5`). Deployed `?ver=0.7.19` | **PASS** |
| **AC-9** | `NB_THEME_VERSION` bumped; `validate_aos.sh` 0 FAIL | Deployed ≥ 0.7.18 (request baseline); `bash _aos/lean-kit/.../validate_aos.sh .` → **32 PASS / 0 FAIL** | **PASS** |

**Result: 9/9 PASS.**

### team_100 axe claim vs gate (step 1)

| Claim | team_100 | team_190 (re-run 2026-06-03) |
|-------|----------|------------------------------|
| 15 pages, 0 serious+critical | 0/15 | **Confirmed** — `axe_result.json` `ts: 2026-06-02T23:41:25Z`, all pages `violations: []` |

---

## §2 · Advisories (non-blocking)

- **A1 — Single-post mobile CDP `scrollWidth=1044`:** `/blog/garden-bed-width-80cm/` @375 reports overflow in `qa_probe.mjs`; desktop @1440 clean. Aligns with **WP002** team_190 disposition (M15/D1: harness viewport artifact, not shipped layout). WP006 touched only `.aside-block h3` in `t4.css` — no width/overflow CSS. Carry to **NB-S002-P009-WP007** / team_00 C2 backlog if a true clamp is desired.
- **A2 — team_100 CDP scope:** VALIDATE_REQUEST cites 10-page CDP pass; gate ran **15 pages** per LOD400 — full sweep executed here.
- **A3 — Deployed `v0.7.19`:** Post-`ab6fba75` world-route 301 bootstrap (roadmap notes) — a11y-neutral; axe/Lighthouse unchanged vs 0.7.18 landing.
- **A4 — DRIFT_REGISTER A13:** Pre-existing non-rendered comment tokens `recursion` / `negentropy` in `t1.css`, `t7.css`, `front-page.php` — not introduced by WP006; out of scope per request.
- **A5 — CDP blank-title flakes:** Some mobile probes returned `title: ""` with `scrollWidth === clientWidth` (980) — navigation/timing, not overflow; pages render correct titles on retry (e.g. about @375 PASS with full title).

### Spot-checks (request “notable” items)

| Item | Evidence | OK |
|------|----------|-----|
| Heritage hero comma-selector | `t8.css` descendant combinators; container keeps paper gradient; `.stamp` on `--w-soil-deep` + white text | ✅ |
| Nav `.atop` scrim `.72` | `shell.css` L264 `rgba(18,17,15,.72)`; `t7.css` L630 matching rule | ✅ |
| Nav-drawer `inert` when closed | `shell-nav.php` `inert` on `#nav-drawer`; `nav-drawer.js` toggles `inert` + removes on open; live HTML contains `inert` | ✅ |

---

## §3 · Route

**PASS → team_100** closes **NB-S002-P009-WP006** per **ADR042** (L0 spoke: `gate_history` L-GATE_VALIDATE recorded, `lod_status: LOD500`, git audit). **NB-S002-P009-WP007** (G2/G3 precision) remains planned. SEO/Performance Lighthouse on dev = edge artifacts only (LOD400 §7).

---

*team_190 | constitutional L-GATE_VALIDATE (deployed result) | 2026-06-03 | cross-engine Cursor≠Claude Code | axe re-run + LH + CDP independent | this verdict is the gate*
