---
type: VERDICT
document_title: "VERDICT — NB-S002-P005-WP001B — V200 pre-cutover QA — L-GATE_VALIDATE"
document_version: "v1.0.0"
document_date: "2026-06-01"
date: 2026-06-01
team_id: team_190
phase_owner: team_190
project: nimrod-bio
milestone: V200
program: P005
work_package: NB-S002-P005-WP001B
gate: L-GATE_VALIDATE
cycle: "1.0"
builder: "team_35 + team_100"
builder_engine: "Cursor"
architect: team_100
validator: team_190
validator_engine: "Composer (Cursor)"
spec_ref: "_aos/work_packages/S002/LOD300_V200_milestone.md"
qa_report_ref: "_COMMUNICATION/team_50/QA_REPORT_V200B_2026-06-01_v1.md"
fixes_ref: "_COMMUNICATION/team_35/COMPLETION_QA_FIXES_V200B_2026-06-01_v1.md"
tooling_ref: "_COMMUNICATION/team_100/COMPLETION_QA_TOOLING_V200B_2026-06-01_v1.md"
triage_ref: "_COMMUNICATION/team_100/TRIAGE_QA_V200B_FINDINGS_2026-06-01_v1.md"
env: "https://nimrod-bio-2026.s887.upress.link"
theme_version: "0.7.13"
verdict: PASS_WITH_DEFERRALS
route_recommendation: "PASS_WITH_DEFERRALS → Team 00 may authorize NB-S002-P005-WP002 production cutover; carry deferred items below (non-blocking)"
---

# VERDICT — NB-S002-P005-WP001B — V200 pre-cutover QA — L-GATE_VALIDATE

## 1. Verdict

**Result: PASS_WITH_DEFERRALS.**

Team_190 independently re-ran browser and HTTP validation on dev (`https://nimrod-bio-2026.s887.upress.link`, theme **v0.7.13**). All constitutional STOP criteria are clear: twelve content pages HTTP 200, system 404/search render correctly, super-lock terms and anonymous TBD are zero in rendered DOM (CDP scan incl. alt/aria targets), no horizontal overflow at 375×812 and 1440×900 on thirteen probed routes (incl. post-fix gallery pages), F-002/F-003/F-004 are closed on live dev, galleries on hagina/greenhouse render real uploads with Hebrew `alt`, activity counts and external links behave as specified, locked facts honored (420 מ״ר on About, restaurant framing on greenhouse, SFA community language).

Remaining items are **explicit pre-cutover deferrals** (owner media, BCS gallery section, primary-domain Lighthouse) — not regressions and not cutover blockers under this gate.

**Team 00:** NB-S002-P005-WP002 (production cutover) **may be authorized** subject to owner acceptance of deferrals in §6.

## 2. Cross-Engine Attestation

| Role | Team | Engine | Artifact |
|------|------|--------|----------|
| QA (initial) | team_50 | Cursor | `QA_REPORT_V200B_2026-06-01_v1.md` |
| Build fixes | team_35 | Cursor | `COMPLETION_QA_FIXES_V200B_2026-06-01_v1.md` |
| Tooling + re-verify | team_100 | Cursor | `COMPLETION_QA_TOOLING_V200B_2026-06-01_v1.md` |
| **Validator** | **team_190** | **Composer (Cursor)** | **this verdict** |

Iron Rule #1 maintained: validator did not implement theme changes; independent replay only (`curl -k`, `node scripts/qa/cdp/qa_probe.mjs`, `validate_aos.sh`).

## 3. Independent Replay Summary

| Check | Method | Result |
|-------|--------|--------|
| 12 pages HTTP 200 | `curl -k -L` + cache-bust `?nc=` | **PASS** — all 200 |
| `/services/` archive (F-002) | `curl -k -L` | **PASS** — 200; 7 service slugs linked |
| Gallery overflow (F-003) | `qa_probe.mjs` CDP | **PASS** — hagina + greenhouse scrollWidth == viewport at 375 & 1440 (was ~4294/2082 pre-fix) |
| Public email (F-004) | `curl` + CDP `absent` | **PASS** — 0 `mailto:` / 0 `nimrod@nimrod.bio` on `/contact/` and home footer |
| Super-locks (12 terms) | Python HTML scan × 12 pages + CDP `absent` | **PASS** — 0 hits |
| Anonymous TBD | HTML scan + CDP | **PASS** — 0 |
| Horizontal overflow 375/1440 | CDP 13 pages × 2 viewports = 26 combos | **PASS** — `failures: 0`, exit 0 |
| 404 template | `curl` bogus path | **PASS** — HTTP 404 + copy "השביל הזה לא מוביל" |
| Search hit / no-hit | `curl` `?s=nimrod` / `?s=zzzznb190empty` | **PASS** — HTTP 200; `.results-list` / `.empty-state` present |
| Galleries + Hebrew alt | HTML parse `.gallery` | **PASS** — hagina 8 imgs; greenhouse 14 imgs, 14 Hebrew alts |
| "0 פעילויות" site-wide | `curl` home | **PASS** — 0 |
| External links | `curl` + HEAD | **PASS** — SFA `.ext-link`; `sfa.nimrod.bio` 200; `tt.nimrod.bio` 200 |
| Locked facts | `curl` grep | **PASS** — `420` on `/about/`; restaurant terms on greenhouse; community terms on SFA |
| F-003 CSS deployed | `curl` `t3.css?ver=0.7.13` | **PASS** — `.gallery img.img-ph` rule live |
| Theme version | page source | **PASS** — `ver=0.7.13` |
| Contact form contract | POST `admin-post.php` | **PASS** — bad nonce → `?status=error`; valid nonce → `?status=ok` |
| AOS baseline | `validate_aos.sh .` | **PASS** — 32 PASS / 16 SKIP / 0 FAIL |

**Evidence paths (validator-run):**

- CDP JSON: `docs/qa/cdp/v200b/team190/qa_probe_result.json` (ts 2026-06-01T20:26:08Z)
- CDP config: `docs/qa/cdp/v200b/team190_config.json`
- Prior team_100 CDP + screenshots (corroborating): `docs/qa/cdp/v200b/qa_probe_result.json`, `docs/qa/cdp/v200b/screenshots/`

## 4. Findings

| id | severity | evidence-by-path | route_recommendation |
|---|---|---|---|
| T190-P005-WP001B-F1 | INFO | F-002 closed: `/services/` HTTP 200; seven `/services/{slug}/` cards in archive HTML (`curl` 2026-06-01). | PASS; no action. |
| T190-P005-WP001B-F2 | INFO | F-003 closed: CDP scrollWidth 375/1440 on `/project/hagina-shel-nimrod/` and `/project/rest-x-greenhouse/`; `t3.css` gallery rule live (`ver=0.7.13`). | PASS; no action. |
| T190-P005-WP001B-F3 | INFO | F-004 closed: 0 public `nimrod@nimrod.bio` on contact + sitewide (`curl` + CDP absent list). | PASS; no action. |
| T190-P005-WP001B-F4 | INFO | Galleries: hagina 8 tiles, greenhouse 14 tiles, all sampled alts Hebrew (`curl` gallery parse). | PASS; no action. |
| T190-P005-WP001B-F5 | INFO | Locks/TBD: 0 across 12 pages (Python term scan + CDP). | PASS; no action. |
| T190-P005-WP001B-D1 | DEFERRAL | **Lighthouse Perf/SEO** — team_100 dev scores (Perf 58–69, SEO 69) are dev artifacts (`noindex`, cache-bust, no Cloudflare). **Re-measure on primary domain post-cutover** per `docs/QA_HARNESS.md`. | Post-cutover; not WP001B blocker. |
| T190-P005-WP001B-D2 | DEFERRAL | **5 owner photo gaps** — remain `.ph.clean` placeholders (expected per OPEN_ITEMS_REGISTER B1). | Owner supply; P009 follow-on. |
| T190-P005-WP001B-D3 | DEFERRAL | **BCS gallery section** — team_35 explicitly skipped (`COMPLETION_QA_FIXES` §6); service hero only. | Follow-on increment; not cutover blocker. |
| T190-P005-WP001B-D4 | DEFERRAL | **Contact inbox delivery** — server contract PASS (nonce); message delivery to `nimrod@mezoo.co` = owner-verify. | Owner-verify post-cutover if needed. |
| T190-P005-WP001B-A1 | ADVISORY | Services archive `<title>` contains English "Archive" (`פעילויות Archive`); cosmetic i18n. | Non-blocking; fix in content pass if desired. |

## 5. Disposition of Prior S2 Findings

| Finding | team_50 (initial) | team_35 fix | team_190 replay |
|---------|-------------------|-------------|-----------------|
| F-002 `/services/` 404 | FAIL | archive-service.php + `has_archive` | **CLOSED** |
| F-003 gallery overflow | FAIL | `t3.css` gallery-scoped rule | **CLOSED** |
| F-004 public email | FAIL | contact card + footer removed | **CLOSED** |

Initial `QA_REPORT_V200B` verdict PASS_WITH_FINDINGS is superseded for gate purposes by this L-GATE_VALIDATE after fixes.

## 6. Pre-Cutover Carry-Forward (non-blocking)

1. **Lighthouse** performance + SEO on **primary domain** `https://nimrod.bio` (not dev `*.upress.link`).
2. **Five owner-supplied photos** (sea/boat, pak-bung, BCS tools, biochar, HEIC batch) — no substitutes.
3. **BCS `_nb_gallery` section** on `/services/bcs/`.
4. **Contact form** live mailbox confirmation (server path verified; inbox = owner).
5. **P006 content backlog** (blog refresh, broken `/blog/back-to-mud/`, etc.) per OPEN_ITEMS_REGISTER — post-cutover program work.

## 7. Constitutional Assessment

- No lock-term or TBD leakage detected in independent replay (STOP criteria clear).
- Dev TLS bypass (`curl -k`, CDP `--ignore-certificate-errors`) used per project canon — **DEV-ONLY**; production cutover QA must run without bypass on `nimrod.bio`.
- Validator made no theme/DB/AOS-layer mutations.
- `validate_aos.sh` → 0 FAIL.

## 8. Route Recommendation

**PASS_WITH_DEFERRALS → authorize NB-S002-P005-WP002 (production cutover) for Team 00 execution**, with §6 deferrals documented as accepted carry-forward (not gate failures).

*team_190 | L-GATE_VALIDATE | 2026-06-01 | WP001B pre-cutover QA*
