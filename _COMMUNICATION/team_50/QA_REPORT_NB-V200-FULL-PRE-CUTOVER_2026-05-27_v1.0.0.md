# QA_REPORT — V200 Pre-Cutover Full QA Sweep — team_50 — v1.0.0

**Date:** 2026-05-27
**Author:** team_50
**WP:** NB-S002-P006-WP003 (proposed) / mandate `MANDATE_NB-V200-FULL-QA-PRE-CUTOVER`
**Type:** QA_REPORT
**Environment:** `http://nimrod-bio-2026.s887.upress.link` (uPress dev, HTTP)
**Mandate:** `_COMMUNICATION/team_50/MANDATE_NB-V200-FULL-QA-PRE-CUTOVER_2026-05-26_v1.0.0.md`
**Evidence bundle:** `docs/qa_v200_pre_cutover_sweep_2026-05-27.json`

## §0 Verdict Box

| Field | Value |
|---|---|
| Aggregate verdict | **PASS_WITH_FINDINGS** |
| Mandate | V200 pre-cutover full QA sweep (14 QA + 5 scenarios) |
| STOP triggered | No (>3 AC FAIL not met; contact form path works; images 30/30; Hebrew slugs OK) |
| One-line next step | team_00 may accept findings for content-phase closeout; route Lighthouse home regression + duplicate-submit hygiene to team_10/110 before cutover if strict non-regression required. |

## 1. Activation prerequisites (post-merge)

| # | Condition | Result |
|---|---|---|
| 1 | WP001 team_190 validate | **PASS_WITH_FINDINGS** — verdict on branch history (`2c92ecef`); live state on dev matches Batch 001 expectations |
| 2 | WP002 COMPLETION | **Present** on merged stack (`feat/p006-wp002` content live on dev) |
| 3 | WP002 team_190 validate | **PASS_WITH_FINDINGS** — `_COMMUNICATION/team_190/VERDICT_NB-S002-P006-WP002_L-GATE_VALIDATE_v1.0.0.md` on `main` |
| 4 | Dev URL responsive | **PASS** — `curl -sI https://nimrod-bio-2026.s887.upress.link/` → HTTP 200 |

## 2. Entity baseline (REST)

| Entity | Expected | Actual |
|---|---|---|
| Posts | 33 | **33** (22 `_nb_seed=v200-migrated` + 11 placeholders) |
| Services | 10 | **10** |
| Projects | 5 | **5** |
| SFA services 28/44 | 404 | **404** (REST/network error consistent with deleted) |

## 3. QA matrix results

| QA | Description | Verdict | Evidence |
|---|---|---|---|
| QA-1 | T7 home | **PASS** | HTTP 200; world links `/world/{soil,know,code}/`; footer/title includes **Unless** |
| QA-2 | T1 ×3 worlds | **PASS** | `/world/soil/`, `/world/know/`, `/world/code/` all HTTP 200 with distinct rendered content |
| QA-3 | T2 services ×10 + SFA 404 | **PASS** | All 10 service slugs HTTP 200 via REST `link`; `/services/sfa/`, `/services/seed-t7-sfa/` → 404 |
| QA-4 | T3 projects ×5 | **PASS_WITH_NOTE** | All 5 project URLs HTTP 200 on retry; `coop-sharon` showed one transient timeout in batch run |
| QA-5 | T4 posts sample | **PASS** | 10/10 migrated sample + 11/11 placeholders HTTP 200 using REST `link` (avoids double-encoding Hebrew slugs) |
| QA-6 | T5 blog index | **PASS_WITH_NOTE** | REST 33 posts; index shows 10 `flow-item` cards + `rel=next` to `/blog/page/2/` (paginated, not single-page 33) |
| QA-7 | T8 static ×3 | **PASS** | `/about/`, `/about/heritage/`, `/contact/` → 200 |
| QA-7b | SMTP round-trip | **PASS_WITH_NOTE** | Valid POST → `302` → `/contact/?status=ok` (handler path). Inbox delivery not re-checked by team_50 (prior team_190/team_00 cycle accepted) |
| QA-8 | Placeholder markers | **PASS** | 11/11 placeholder slugs render `data-nb-placeholder="true"` |
| QA-9 | Inline images | **PASS** | 30/30 sampled image URLs from migrated post content → HTTP 200 (REST-rendered HTML) |
| QA-10 | Yoast / Unless meta | **PASS** | Unless present on home + 5 surfaces (`<title>` includes Unless via MU fallback) |
| QA-11 | Redirects 23+6+2 | **PASS** | Prior artifact `docs/qa_redirect_verification_2026-05-25.json` `all_pass=true`; spot-check 3/3 live |
| QA-12 | Lighthouse non-regression | **FINDING** | See §4 — home **Perf 67** (−22 vs baseline 89), **BP 81** (−19 vs 100); sample posts within tolerance |
| QA-13 | Services + SFA delete | **PASS** | 10 services; IDs 28/44 not reachable |
| QA-14 | Sitemap integrity | **PASS_WITH_FINDINGS** | `sitemap_index.xml` 200; `post-sitemap` present; **media-sitemap absent** (known WP002 F-002) |

## 4. Lighthouse (QA-12)

Baseline: `docs/qa_lighthouse_results_2026-05-25.json` (2026-05-25).

| URL | Perf | A11y | BP | SEO | Δ vs baseline (same URL) |
|---|---|---|---|---|---|
| `/` (2026-05-27) | **67** | 94 | **81** | 69 | Perf **−22**, BP **−19** → **regression >5** |
| `/` (baseline) | 89 | 93 | 100 | 66 | — |
| `/blog/יום-בגינה/` (encoded URL) | 85 | 93 | 78 | 69 | Within ±5 vs similar post baseline |
| `/blog/harish2021/` | 81 | 89 | 67 | 69 | BP −6 vs post baseline 73 (borderline) |

**Assessment:** QA-12 **FAIL** on home Perf/BP vs mandate threshold. Sample posts mostly within non-regression band. Likely mixed factors (dev load, media migration payload, Lighthouse run variance). **Not a STOP** per mandate §5 (contact/images/Hebrew OK), but must be logged for team_00 acceptance before cutover.

## 5. Scenario matrix (GCR-002)

| # | Scenario | Verdict | Evidence |
|---|---|---|---|
| S-1 | Happy path | **PASS** | Home → world → post (200) → contact valid submit → `?status=ok` |
| S-2 | Error/validation | **PASS** | Empty + invalid email → `?status=invalid` |
| S-3 | Edge: Hebrew slug | **PASS_WITH_NOTE** | `/blog/אנטרופיה/` → 301 → encoded canonical → 200 |
| S-4 | Duplicate submit | **FINDING** | Two valid submits within 5s both returned `?status=ok` (no dedupe) |
| S-5 | Cancellation | **PASS** | Server-rendered form; navigate-away returns clean form on reload (no client state leak observed) |

## 6. Findings table

| ID | Severity | Finding | evidence-by-path | route_recommendation |
|---|---|---|---|---|
| Q50-F-001 | Medium | Lighthouse home regression: Performance 67 (−22) and Best Practices 81 (−19) vs 2026-05-25 baseline | `docs/qa_lighthouse_results_2026-05-25.json`; live Lighthouse run 2026-05-27 | team_10 investigate dev perf (media weight, cache); re-run on cutover URL; team_00 accept variance or defer uplift to V300 |
| Q50-F-002 | Low | Yoast media sitemap not exposed on dev | `docs/qa_v200_pre_cutover_sweep_2026-05-27.json` QA-14; team_190 F-002 | Carry to cutover SEO checklist; non-blocking for render |
| Q50-F-003 | Low | Contact form allows duplicate rapid submits (both `status=ok`) | `inc/contact-form-handler.php`; curl replay S-4 | Optional idempotency token or rate-limit — team_10 |
| Q50-F-004 | Low | Blog index paginates (10 per page) — not all 33 visible on page 1 | Live `/blog/` HTML `rel=next` | Acceptable if pagination works; verified REST count 33 |
| Q50-F-005 | Info | QA-7b inbox not independently verified this run | Prior `VERDICT_NB-S002-P005-WP001` SMTP PASS | team_00 spot-check inbox if cutover requires fresh proof |

## 7. STOP condition check

| Condition | Triggered |
|---|---|
| >3 AC FAIL | **No** (1 strict: QA-12 home; others PASS/PASS_WITH_NOTE) |
| Contact broken | **No** (`status=ok` on valid submit) |
| Images >10% 404 | **No** (0/30) |
| Hebrew slugs broken | **No** |

## 8. Aggregate verdict rationale

**PASS_WITH_FINDINGS.** Functional surface for V200 pre-cutover is **ready for content-phase signature** from a QA perspective: templates render, entities match spec counts, placeholders marked, media images resolve, redirects hold, SFA removed, contact path works.

Findings are **non-blocking** for content-phase unless team_00 requires strict Lighthouse non-regression on dev home before cutover.

## 9. Evidence commands (representative)

```bash
# Entity counts
python3 -c "..."  # REST via scripts/migration/_lib.py → posts=33 services=10 projects=5

# Automated sweep
python3 scripts/qa_v200_pre_cutover_sweep.py
# → docs/qa_v200_pre_cutover_sweep_2026-05-27.json

# Contact happy path (correct field names: name, email, message)
curl -X POST http://nimrod-bio-2026.s887.upress.link/wp-admin/admin-post.php \
  -d 'action=nb_contact_submit&nb_contact_nonce=...&name=Team50+QA&email=qa-test@example.com&message=...'
# → 302 Location: .../contact/?status=ok

# Lighthouse home
npx lighthouse http://nimrod-bio-2026.s887.upress.link/ --only-categories=performance,accessibility,best-practices,seo
```

— team_50 · 2026-05-27
