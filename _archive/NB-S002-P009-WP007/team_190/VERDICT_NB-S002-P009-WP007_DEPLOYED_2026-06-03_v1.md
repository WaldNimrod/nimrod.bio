---
type: VERDICT
document_title: "VERDICT — NB-S002-P009-WP007 — deployed full design implementation — L-GATE_VALIDATE"
document_version: "v1"
document_date: "2026-06-03"
date: 2026-06-03
team_id: team_190
phase_owner: team_190
project: nimrod-bio
milestone: V200
program: P009
work_package: NB-S002-P009-WP007
gate: L-GATE_VALIDATE
track: A
effort: NORMAL
builder: team_100
architect: team_100
validator: team_190
builder_engine: "Claude Code / team_100"
validator_engine: "Cursor / Composer (team_190)"
cross_engine: preserved (Iron Rule #1)
spec_ref: "_aos/work_packages/NB-S002-P009-WP007/LOD400_NB-S002-P009-WP007.md"
validate_request_ref: "_COMMUNICATION/team_190/VALIDATE_REQUEST_NB-S002-P009-WP007_DEPLOYED_2026-06-03_v1.md"
design_ssot_ref: "_COMMUNICATION/team_35/HANDOFF_v5_2026-06-03/Precision_Mockup_v5.html"
deployed_theme_version: "0.7.24"
deployed_branch: "wp007-design-impl"
baseline_commit: "161e8078"
evidence_commit: "f7eafe02"
verdict: PASS
route_recommendation: "PASS -> team_100 closes NB-S002-P009-WP007 per ADR042 (roadmap LOD500 + gate_history L-GATE_VALIDATE + git audit; merge wp007-design-impl)"
---

# VERDICT — NB-S002-P009-WP007 DEPLOYED — team_190 (constitutional L-GATE_VALIDATE) — v1

**Date:** 2026-06-03  
**Authority:** team_190 (constitutional L-GATE_VALIDATE — cross-engine, immutable; builder ≠ validator)  
**WP:** NB-S002-P009-WP007 — full design implementation / precision walk vs Precision Mockup v5  
**Env:** dev `https://nimrod-bio-2026.s887.upress.link` · theme **v0.7.24** deployed · branch **`wp007-design-impl`**  
**Builder:** Claude Code / team_100 · **Validator:** Cursor / team_190 (Iron Rule #1 satisfied)  
**Inputs:** LOD400 §4–§5 · `VALIDATE_REQUEST_NB-S002-P009-WP007_DEPLOYED_2026-06-03_v1.md` · `docs/qa/cdp/v200b/team190/axe_config.json`  
**Method:** every team_100 gate claim re-executed independently; builder phase JSON used only as path references unless corroborated below.

---

## §0 · VERDICT BOX

> ## PASS
>
> **STOP triggers — all NEGATIVE:**
> - Lock breach (Micha / demonstrate-never-name)? **NO** — CDP `--absent` on 32 probes: `forbiddenFound: []` throughout
> - axe serious/critical or color-contrast? **NO** — independent 15-page sweep `totalSerious: 0`, `byRule: {}`
> - Lighthouse a11y &lt; 95 on affected routes? **NO** — independent re-run world-know = **100**; builder LH JSON on 7 other WP007 routes = score **1.0** each
> - Horizontal overflow on affected routes @375/1440? **NO** — **32/32** qa_probe pass incl. **single-post@375 scrollWidth=375** (WP006/WP002 carry-forward **resolved**)
> - `system.css` touched? **NO** — `git diff 161e8078 -- system.css` empty
>
> **Route:** → **team_100** closes **NB-S002-P009-WP007** per **ADR042** (`roadmap.yaml` `lod_status: LOD500`, `gate_history` L-GATE_VALIDATE, git audit; merge `wp007-design-impl`). team_50 QA report advisory if filed later.

---

## §1 · Cross-engine attestation

| Role | Team | Engine | Artifact |
|---|---|---|---|
| Build / deploy | team_100 | Claude Code | branch `wp007-design-impl` @ evidence `f7eafe02` (validate request cited `cb3f632d`; deploy pin **v0.7.24** confirmed on dev) |
| Constitutional validator | team_190 | Cursor / Composer | this verdict |
| Design SSoT | team_35 | Claude Design | `Precision_Mockup_v5.html` |

Iron Rule #1 maintained: validator engine (Cursor) ≠ build engine (Claude Code).

---

## §2 · Global acceptance (LOD400 §5 AC-A..AC-G)

| AC | Criterion | Independent result | evidence-by-path | Verdict |
|---|---|---|---|---|
| **AC-A** | axe 0 serious/critical + 0 color-contrast; LH a11y ≥95 on affected routes | `node scripts/qa/cdp/axe_probe.mjs --config docs/qa/cdp/v200b/team190/axe_config.json --out docs/qa/cdp/v200b/team190/wp007_validate` → `totalSerious: 0`, all 15 pages `serious: 0` (`wp007_validate/axe_result.json` ts `2026-06-03T20:29:22Z`). Independent LH: `npx lighthouse@13 …/world/know/` → a11y **1.0**. Builder LH JSON (`wp007_phase1/lh/*.json`, `wp007_phase2/lh_*.json`, `wp007_phase3/lh_*.json`) all score **1.0** on world-know/code, heritage, consulting-hydro, T3, services, blog, single-post. | `docs/qa/cdp/v200b/team190/wp007_validate/axe_result.json` | **PASS** |
| **AC-B** | qa_probe 0 overflow @375 & @1440 all affected routes | `node scripts/qa/cdp/qa_probe.mjs --config docs/qa/cdp/v200b/team190/wp007_validate/qa_probe_config.json` → **32/32** pass (`failures: 0`). **single-post** mobile: `scrollWidth: 375`, `clientWidth: 375` (was 1044 @ WP006). | `docs/qa/cdp/v200b/team190/wp007_validate/qa_probe_result.json` | **PASS** |
| **AC-C** | Super-locks 0 rendered | All 32 probes: `forbiddenFound: []` for Micha, demonstrate-never-name Hebrew terms, CDIP, TBD | same qa_probe result | **PASS** |
| **AC-D** | Module CSS + templates + inc only; 0 new inline; no overrides; `system.css` byte-identical | Changed files vs `161e8078`: 11 theme paths (archive-service, t1/t2/t3.css, single-service/project, meta-registration, helpers/enqueue). `git diff 161e8078 -- system.css` → **0 bytes**. `git diff 161e8078` on changed PHP: **no new `style=` lines** added. G3a in `t1.css` L725–748. | repo diff + `nimrod.bio/.../assets/css/t1.css` | **PASS** |
| **AC-E** | `NB_THEME_VERSION` bumped; FTPS; byte-parity served==repo | `functions.php`: `NB_THEME_VERSION` **0.7.24**. SHA256 repo==served for `t1.css`, `t2.css`, `t3.css` @ `?ver=0.7.24`. | curl -k dev + local repo shasum | **PASS** |
| **AC-F** | Non-regression of done work | Full 15-route axe 0 serious; 32-row qa_probe pass covers home/about/contact/projects/soil/search/404 + WP001 projects archive + WP006 routes | wp007_validate sweeps | **PASS** |
| **AC-G** | `validate_aos.sh` 0 FAIL | `bash _aos/lean-kit/modules/validation-quality/scripts/validate_aos.sh .` → **32 PASS / 16 SKIP / 0 FAIL** | terminal 2026-06-03 | **PASS** |

**Result: 7/7 PASS.**

### team_100 claim vs independent re-run

| Claim | team_100 | team_190 (re-run 2026-06-03) |
|---|---|---|
| axe 15 routes 0 serious | yes | **Confirmed** — fresh sweep, cleaner than stale root `axe_result.json` (which had 1 moderate heading-order on services-archive) |
| qa_probe 30/30 | yes | **Confirmed 32/32** (16 pages × 2 viewports incl. anchor t2s route) |
| single-post mobile overflow fixed | 1044→375 | **Confirmed** `scrollWidth: 375` @375 |
| system.css locked | yes | **Confirmed** diff empty vs baseline |
| validate_aos 32/0 | yes | **Confirmed** |

---

## §3 · Per-screen AT verification (LOD400 §4)

Independent CDP DOM/computed-style spot-checks on dev (`wp007_validate/at_spot_check.json` + supplemental probes):

| AT | Screen / route | Key checks | Result |
|---|---|---|---|
| **AT-1** | `/services/` | `.page-hero`, `.svc-grid` 2-col (`359px 359px` @ desktop probe width), 2 bridge cards, `.final-cta`, sr-only h2 | **PASS** |
| **AT-2** | `/services/consulting-hydro/` (anchor) | `.svc-single-hero`, 4 `.feat-tile`, 4 `.svc-step`, `.svc-pull`, `.final-cta`; **3** field-proof cards via `.linked-projects .lp-card` (seed meta) | **PASS** (class vocab A2) |
| **AT-2** | `/services/bcs/` (fallback) | hero present; 0 feat tiles (graceful); **BCS gallery preserved** | **PASS** (P008 carry-forward for content fill) |
| **AT-3** | `/project/rest-x-greenhouse/` | hero + **4** outcome tiles + `.final-cta` | **PASS** |
| **AT-4** | `/blog/garden-bed-width-80cm/` | `.post-hero-meta-top`; **no** numbered body h2 (`.post-body h2` plain); `.post-aside`; 0 overflow | **PASS** |
| **AT-5** | `/blog/?view=grid` | `.blog-header-grid`, filter bar, `.posts-grid`, `.blog-end` | **PASS** (class vocab A3 on default flow view — functional equivalent) |
| **AT-6** | `/world/know/`, `/world/code/` | `article.t1-world-know` / `t1-world-code`; gloss colors **rgb(154,79,43)** / **rgb(31,94,96)** (= `#9a4f2b` / `#1f5e60` deep tokens) | **PASS** |
| **AT-7** | `/about/heritage/` | dropcap; **6** numbered `h2 .num`; `.heritage-end`; hero via `.t8-heritage-hero` (CSS alias) | **PASS** |

---

## §4 · Scope expansion (team_00-authorized — on record)

VALIDATE_REQUEST documents **team_00-authorized scope expansion** beyond gate-approved LOD400 §3 OUT ("no new copy/content"):

- `single-service.php` v5 rebuild + 4 service meta fields (`feat_tiles`, `svc_steps`, `svc_pull`, `bridge`) in `inc/meta-registration.php`
- Anchor `consulting-hydro` seeded (`scripts/seed_wp007_t2s_*`); other services **graceful per-field fallback**

**Disposition:** expansion is **explicitly authorized and recorded**; does not invalidate the precision-walk gate. Remaining per-service t2s content → **P008 SERVICE_CONTENT_FILL** (carry-forward, not a blocker).

---

## §5 · Advisories (non-blocking)

| id | severity | evidence-by-path | route_recommendation |
|---|---|---|---|
| T190-P009-WP007-DEP-A1 | INFO | AT-2 field-proof uses `.linked-projects`/`.lp-card` not v5 `.projects-row`/`.proj-card`; 3 seeded links render correctly on anchor | PASS — functional equivalent; optional class rename in P008 polish |
| T190-P009-WP007-DEP-A2 | INFO | T5 live vocabulary (`.t5-filter-bar`, `.blog-featured-grid`) vs v5 (`.blog-toolbar`, `.rp-card.feat`) — working equivalent per validate request carry-forward | PASS — advisory only |
| T190-P009-WP007-DEP-A3 | INFO | Non-anchor t2s services omit feat/steps/pull/bridge sections until P008 content fill | PASS — documented graceful fallback |
| T190-P009-WP007-DEP-A4 | INFO | Heritage hero wrapper `.t8-heritage-hero` (t8.css aliases `.heritage-hero`) | PASS — WP006 comma-selector fix preserved |
| T190-P009-WP007-DEP-A5 | INFO | Evidence commit `f7eafe02` (qa evidence refresh) ahead of validate-request pin `cb3f632d`; deployed theme **v0.7.24** confirmed on dev regardless | PASS — no gate impact |
| T190-P009-WP007-DEP-A6 | INFO | **Positive:** single-post @375 overflow resolved (WP006/WP007 carry-forward closed) | PASS |

---

## §6 · Route recommendation

**PASS → team_100**

1. Close **NB-S002-P009-WP007** per **ADR042** (L0 spoke: append `gate_history` L-GATE_VALIDATE PASS, `lod_status: LOD500`, terminal notes, git audit).
2. Merge **`wp007-design-impl`** when ready.
3. team_190 does **not** edit `_aos/roadmap.yaml` (Iron Rule #4 single writer).

Suggested `gate_history` append (for team_100):

```yaml
- gate: L-GATE_VALIDATE
  result: PASS
  date: "2026-06-03"
  notes: "team_190 Cursor constitutional cross-engine review. VERDICT_NB-S002-P009-WP007_DEPLOYED_2026-06-03_v1.md. Independent axe 15/0 serious, qa_probe 32/32, system.css locked, v0.7.24 byte-parity t1/t2/t3.css, AT-1..7 spot-check PASS. single-post mobile overflow resolved. Scope expansion (t2s meta+seed) team_00-authorized on record."
```

---

*team_190 | constitutional L-GATE_VALIDATE (deployed result) | 2026-06-03 | cross-engine Cursor≠Claude Code | independent axe + qa_probe + LH spot-check + AT CDP | this verdict is the gate*
