# QA REPORT — NB-S002-P009-WP007 DEPLOYED — team_50 (dev QA) — v1

**Date:** 2026-06-04  
**From:** team_50 (Validation / dev QA — independent, cross-engine · **Cursor**)  
**To:** team_100 · cc team_190, team_00  
**WP:** NB-S002-P009-WP007 — full design implementation / precision walk vs Precision Mockup v5  
**Env:** dev `https://nimrod-bio-2026.s887.upress.link` · theme **v0.7.24** (served `?ver=0.7.24`) · `main` @ `4a84e800` (deploy pin `043b6391`)  
**Design SSoT:** `_COMMUNICATION/team_35/HANDOFF_v5_2026-06-03/Precision_Mockup_v5.html`  
**LOD400:** `_aos/work_packages/NB-S002-P009-WP007/LOD400_NB-S002-P009-WP007.md` §4 (AT-1..AT-7) + §5 (AC-A..AC-G)  
**Method:** Independent re-measurement from scratch (team_100 build claims and team_190 constitutional verdict **not** trusted as evidence). axe 17-route sweep · Lighthouse a11y ×9 WP007 routes · qa_probe overflow + lock-scan @375/1440 · CDP AT spot-checks · visual screenshots @375 + @1440. Dev TLS invalid BY DESIGN → cert-bypass DEV-ONLY. Cache-bust `?nc=` on all fetches.

---

## 0 · Headline

**Verdict: PASS** — dev-QA track complete; no STOP-class finding on closed WP.

17/17 axe pages: **0** serious/critical violations (incl. **0** `color-contrast`). Nine WP007 Lighthouse a11y scores **100** (≥95 AC-A). Lock-scan **0** forbidden terms on **34/34** rendered DOM probes (17 routes × 2 viewports). Horizontal overflow **0** on all affected + non-regression routes incl. **single-post@375 `scrollWidth=375`**. Per-AT spot-checks **PASS** (class-vocabulary advisories only, on record). Constitutional L-GATE_VALIDATE already **PASS** (team_190, 2026-06-03); this report closes the standard two-track external cycle.

---

## 1 · Cross-engine attestation

| Role | Team | Engine | Artifact |
|---|---|---|---|
| Build / deploy | team_100 | Claude Code | theme v0.7.24 @ deploy pin `043b6391` |
| Constitutional validator | team_190 | Cursor | `VERDICT_NB-S002-P009-WP007_DEPLOYED_2026-06-03_v1.md` (PASS) |
| Dev QA (this report) | team_50 | **Cursor** | this file |

Iron Rule #1 preserved: QA engine (Cursor) ≠ build engine (Claude Code).

---

## 2 · Axe sweep — by-rule + per-page matrix

**Runner:** `node scripts/qa/cdp/axe_probe.mjs --config docs/qa/cdp/v200b/team50/wp007/axe_config.json --out docs/qa/cdp/v200b/team50/wp007`  
**Exit:** 0 · **Artifact:** `docs/qa/cdp/v200b/team50/wp007/axe_result.json` · **ts:** 2026-06-03T21:22:44Z

| Rule (impact) | Node instances | Pages affected |
|---------------|---------------:|----------------|
| *(none)* | 0 | — |

**TOTAL serious+critical violation instances: 0** · **`byRule`: {}**

### Per-page axe matrix (17 pages)

| Page | Path | Total viol. | Serious+critical |
|------|------|------------:|-----------------:|
| home | `/` | 0 | 0 |
| about | `/about/` | 0 | 0 |
| contact | `/contact/` | 0 | 0 |
| projects-archive | `/projects/` | 0 | 0 |
| services-archive | `/services/` | 0 | 0 |
| world-soil | `/world/soil/` | 0 | 0 |
| world-know | `/world/know/` | 0 | 0 |
| world-code | `/world/code/` | 0 | 0 |
| heritage | `/about/heritage/` | 0 | 0 |
| single-project | `/project/rest-x-greenhouse/` | 0 | 0 |
| single-service-bcs | `/services/bcs/` | 0 | 0 |
| single-service-anchor | `/services/consulting-hydro/` | 0 | 0 |
| single-service-teaching | `/services/teaching/` | 0 | 0 |
| blog-index | `/blog/` | 0 | 0 |
| single-post | `/blog/garden-bed-width-80cm/` | 0 | 0 |
| search | `/?s=garden` | 0 | 0 |
| notfound-404 | `/no-such-page-xyz/` | 0 | 0 |

**AC-A (axe): PASS**

---

## 3 · Lighthouse accessibility — 9 WP007 routes

**Runner:** `npx lighthouse@13` · `CHROME_PATH=/Applications/Google Chrome.app/...` · `--only-categories=accessibility` · DEV cert-bypass.  
**Artifacts:** `docs/qa/cdp/v200b/team50/wp007/lighthouse/*.json`

| Page | Path | A11y score | Notable failing audits |
|------|------|----------:|------------------------|
| services (t2) | `/services/` | **100** | — |
| consulting-hydro (t2s) | `/services/consulting-hydro/` | **100** | — |
| bcs | `/services/bcs/` | **100** | — |
| greenhouse (t3) | `/project/rest-x-greenhouse/` | **100** | — |
| blog (t5) | `/blog/` | **100** | — |
| single-post (t4) | `/blog/garden-bed-width-80cm/` | **100** | — |
| world-know (t1) | `/world/know/` | **100** | — |
| world-code (t1) | `/world/code/` | **100** | — |
| heritage | `/about/heritage/` | **100** | — |

**AC-A (Lighthouse): PASS** (min **100** ≥ 95; WP006 baseline held). Perf/SEO/Best-Practices not scored (dev edge artifacts per `docs/QA_HARNESS.md`).

---

## 4 · CDP overflow + lock-scan

**Config:** `docs/qa/cdp/v200b/team50/wp007/qa_probe_config.json`  
**Absent (rendered DOM incl. alt/aria):** `Micha`, `מיכה`, `אנטרופיה`, `נגנטרופיה`, `רקורסיה`, `CDIP`, `cross-domain`, `פרמקלצר`, `TBD`  
**Result:** **34/34 PASS** · `failures: 0` · **Artifact:** `docs/qa/cdp/v200b/team50/wp007/qa_probe_result.json` · **ts:** 2026-06-03T21:33:26Z

### Overflow highlights (WP007 + carry-forward)

| Route | @375 scrollW / clientW | @1440 scrollW / clientW | Lock hits |
|-------|:---:|:---:|:---:|
| `/services/` | 375 / 375 | 1440 / 1440 | 0 |
| `/services/consulting-hydro/` | 375 / 375 | 1440 / 1440 | 0 |
| `/project/rest-x-greenhouse/` | 375 / 375 | 1440 / 1440 | 0 |
| `/blog/garden-bed-width-80cm/` | **375 / 375** | 1440 / 1440 | 0 |
| `/blog/` | 375 / 375 | 1440 / 1440 | 0 |
| `/world/know/`, `/world/code/` | 375 / 375 | 1440 / 1440 | 0 |
| `/about/heritage/` | 375 / 375 | 1440 / 1440 | 0 |
| `/services/teaching/` (graceful fallback) | 375 / 375 | 1440 / 1440 | 0 |
| Non-regression (home, about, contact, projects, soil, search, 404, bcs) | all pass | all pass | 0 |

**AC-B · AC-C: PASS**

### Visual spot-check screenshots (8 screens × 2 viewports)

**Config:** `docs/qa/cdp/v200b/team50/wp007/qa_probe_shots_config.json` · **16/16** overflow/lock pass  
**Dir:** `docs/qa/cdp/v200b/team50/wp007/screenshots/`

| Screen | v5 ref | mobile PNG | desktop PNG |
|--------|--------|------------|-------------|
| t2 services index | `t2` | `t2-services_mobile.png` | `t2-services_desktop.png` |
| t2s consulting-hydro | `t2s` | `t2s-consulting-hydro_mobile.png` | `t2s-consulting-hydro_desktop.png` |
| t3 greenhouse | `t3` | `t3-greenhouse_mobile.png` | `t3-greenhouse_desktop.png` |
| t4 post | `t4` | `t4-post_mobile.png` | `t4-post_desktop.png` |
| t5 blog | `t5` | `t5-blog_mobile.png` | `t5-blog_desktop.png` |
| heritage | `heritage` | `heritage_mobile.png` | `heritage_desktop.png` |
| t1 know | `t1` | `t1-know_mobile.png` | `t1-know_desktop.png` |
| t1 code | `t1` | `t1-code_mobile.png` | `t1-code_desktop.png` |

**Visual review (team_50):** Section order and block structure match v5 intent on all eight screens. World accents: know orange (`rgb(154,79,43)` deep gloss) · code teal (`rgb(31,94,96)`). No horizontal bleed on reviewed PNGs. Class-vocabulary deltas vs mockup selectors documented as advisories (§7) — functional equivalents confirmed.

---

## 5 · Per-AT spot-checks (LOD400 §4)

**Artifact:** `docs/qa/cdp/v200b/team50/wp007/at_spot_check.json` · **ts:** 2026-06-03T21:26:40Z  
**Runner:** `node docs/qa/cdp/v200b/team50/wp007/at_spot_check.mjs` (+ supplemental CDP probes)

| AT | Route | Key checks | Result |
|---|---|---|---|
| **AT-1** | `/services/` | `.page-hero` ✓ · `.svc-grid` 2-col (`550px 550px` @1440) ✓ · `.bridges-band` present · **2** `.svc-bridge` cards (`.b-soil-know`, `.b-know-code`) ✓ · `.final-cta` ✓ · h1 `84px` (= v5 max clamp) | **PASS** (bridge class vocab ADV-1) |
| **AT-2** | `/services/consulting-hydro/` | `.svc-single-hero` 2-col `627.5px 444.484px` ✓ · 4 `.feat-tile` ✓ · `.svc-pull` ✓ · 4 `.svc-step` ✓ · **3** `.linked-projects .lp-card` ✓ · `.final-cta` ✓ | **PASS** (`.linked-projects` vs v5 `.projects-row` ADV-2) |
| **AT-2** | `/services/teaching/` | hero + `.final-cta` ✓ · 0 feat/steps/pull (graceful) ✓ · 0 broken empty sections | **PASS** (P008 content fill carry-forward) |
| **AT-2** | `/services/bcs/` | hero ✓ · 0 feat tiles (graceful) ✓ · **17** gallery images rendered ✓ · meta-strip ✓ | **PASS** |
| **AT-3** | `/project/rest-x-greenhouse/` | h1 `64px` / `-1.6px` letter-spacing ✓ · **4** `.oc-tile` outcomes ✓ · `.final-cta` ✓ | **PASS** |
| **AT-4** | `/blog/garden-bed-width-80cm/` | `.post-hero-meta-top` ✓ · **0** numbered body h2 ✓ · `.post-aside` ✓ · `.post-layout` present · **0 overflow @375** | **PASS** |
| **AT-5** | `/blog/` | `.blog-header-grid` ✓ · `.filter-bar` + world chips ✓ · `.t5-flow` default view ✓ · `.blog-end` ✓ | **PASS** (v5 `.blog-toolbar`/`.posts-grid` naming ADV-3; grid view via `?view=grid` not default) |
| **AT-6** | `/world/know/` | `article.t1-world-know` ✓ · gloss `rgb(154,79,43)` = `#9a4f2b` deep ✓ · `.lat-anchor` ✓ | **PASS** |
| **AT-6** | `/world/code/` | `article.t1-world-code` ✓ · gloss `rgb(31,94,96)` = `#1f5e60` deep ✓ | **PASS** |
| **AT-7** | `/about/heritage/` | `.t8-heritage-hero` / `.heritage-hero` ✓ · **6** numbered `h2 .num` ✓ · CSS `::first-letter` dropcap (`87.4px`) ✓ · `.heritage-end` ✓ | **PASS** |

---

## 6 · LOD400 §5 global acceptance mapping

| AC | Criterion | team_50 result |
|----|-----------|----------------|
| **AC-A** | axe 0 serious/critical + 0 color-contrast; LH a11y ≥95 | **PASS** (axe 17/0; LH min 100) |
| **AC-B** | qa_probe 0 overflow @375 & @1440 | **PASS** (34/34) |
| **AC-C** | Super-locks 0 rendered | **PASS** (0/34) |
| **AC-D** | Module CSS + templates only; system.css locked | **Out of team_50 scope** — team_190 confirmed (`git diff 161e8078 -- system.css` empty) |
| **AC-E** | NB_THEME_VERSION bumped; byte-parity | **Spot-check:** served `?ver=0.7.24` on dev ✓ — full parity team_190 evidence |
| **AC-F** | Non-regression done work | **PASS** (17-route axe + 34-probe sweep covers home/about/contact/projects/soil/search/404) |
| **AC-G** | validate_aos.sh 0 FAIL | **Out of team_50 scope** — team_190 confirmed 32 PASS / 0 FAIL |

**Result: 5/5 in dev-QA scope PASS.** No STOP trigger for WP reopen (ADR042).

---

## 7 · Advisories (non-blocking — on record)

| ID | Severity | Finding | Disposition |
|----|----------|---------|-------------|
| ADV-1 | INFO | Bridges band uses `.svc-bridge` / `.svc-bridges-grid` not v5 `.bridge-card` / `.bridges-grid` — 2 cards render correctly | PASS — functional equivalent |
| ADV-2 | INFO | Anchor t2s field-proof via `.linked-projects .lp-card` not v5 `.projects-row .proj-card` | PASS — P008 polish optional |
| ADV-3 | INFO | T5 live vocabulary (`.filter-bar`, `.t5-flow`) vs v5 (`.blog-toolbar`, `.posts-grid`) — default flow view; grid via `?view=grid` | PASS — team_190 A2 carry-forward |
| ADV-4 | INFO | Non-anchor t2s services omit feat/steps/pull until P008 content fill | PASS — graceful fallback verified on `/services/teaching/` |
| ADV-5 | INFO | Heritage dropcap via CSS `::first-letter` not `.dropcap` class | PASS — visual + AA confirmed |
| ADV-6 | INFO | Post layout uses `.post-layout` wrapper; `.article-shell.has-aside` not present in DOM | PASS — aside sticky behavior present |

---

## 8 · Defect list

| ID | Severity | Finding | Route |
|----|----------|---------|-------|
| — | — | None blocking | — |

---

## 9 · Verdict & routing

| Field | Value |
|-------|-------|
| **Verdict** | **PASS** |
| **Gate impact** | WP **already closed** LOD500 (team_190 L-GATE_VALIDATE PASS, 2026-06-03). No reopen route required. |
| **Two-track cycle** | Dev-QA track (team_50) now **complete** — complements constitutional verdict. |
| **Evidence bundle** | `docs/qa/cdp/v200b/team50/wp007/` (axe, qa_probe, lighthouse/, screenshots/, at_spot_check.json) |

---

## 10 · Tooling log

| Step | Command / tool | Outcome |
|------|----------------|---------|
| 1 | `axe_probe.mjs` + `team50/wp007/axe_config.json` | 0 serious, exit 0 |
| 2 | `qa_probe.mjs` + `qa_probe_config.json` | 34/34 overflow/lock PASS |
| 3 | `qa_probe.mjs` + `qa_probe_shots_config.json --shots` | 16/16 + 16 PNG |
| 4 | Lighthouse ×9 (full Chrome) | 100 a11y each |
| 5 | `at_spot_check.mjs` | AT-1..7 PASS (see §5) |

---

*team_50 · dev QA · NB-S002-P009-WP007 DEPLOYED · 2026-06-04 · independent / cross-engine · theme v0.7.24 on dev · build=Claude Code → QA=Cursor*
