---
type: VERDICT
from: team_190 (Independent Validator · Codex)
to: team_100
wp_id: NB-S002-P009-WP003
project: nimrod-bio
milestone: V200
program: P009
date: 2026-05-29
gate: L-GATE_VALIDATE
track: A · STANDARD
verdict: PASS_WITH_FINDINGS
builder_engine: Claude Code / team_100 (orchestrated sub-agents)
validator_engine: Codex / team_190
cross_engine: preserved (Iron Rule #1)
target_version: 0.6.1
---

# VERDICT — NB-S002-P009-WP003 — L-GATE_VALIDATE (v1.0.0)

## Executive Summary

**Gate result: `PASS_WITH_FINDINGS`**

Pre-condition met: dev site serves theme **`0.6.1`** (`components.css?ver=0.6.1`, `t7.css?ver=0.6.1`, `nb-nav-atop.js?ver=0.6.1`, `nb-carousel.js?ver=0.6.1` on homepage). Constitutional audit **PASS** (WP002-protected blocks intact, `system.css` LOCK, cross-engine preserved). Core precision deliverables **PASS**: G-01 world washes restored, bridges band + Unless lockup live, `.img-ph.clean` fallback, nav `.atop`, carousels functional, WP002 mobile regression spot-check **PASS**.

**Blocking finding:** **AT-D13** — home page exhibits ~12–13px horizontal overflow @360/@375 (world pages clean). Route fix before production cutover.

**Advisory:** team_50 `DEVICE_CHECK` not filed (team_190 MCP substitute for structural/functional replay); AT-D19 Lighthouse deferred; minor spec drift on h1 class naming (AT-D2).

---

## Pre-condition

| Requirement | Result | Evidence |
|---|---|---|
| Dev deploy @ 0.6.1 | **PASS** | `curl` homepage link tags → sole `ver=0.6.1`; `functions.php` `NB_THEME_VERSION` = `0.6.1` |
| LOD400 AT-D1…D19 in scope | **PASS** | `_aos/work_packages/NB-S002-P009-WP003/LOD400_NB-S002-P009-WP003.md` §6 |
| Cross-engine (build ≠ validate) | **PASS** | Build: Claude/team_100; validate: Codex/team_190 |

---

## Acceptance Tests (AT-D1…AT-D19)

| # | Test | Result | Evidence |
|---|---|---|---|
| AT-D1 | World strata washes (G-01) | **PASS** | `t1.css` `:root` restored (`--soil-wash` … `--code-wash`, lines 17–24). Live `/world/soil/`: `--soil-wash=#eef0e0`; `article.variant.c.vc-shell` computed `linear-gradient(… rgb(238,240,224) …)` — not flat paper |
| AT-D2 | Display headings → type tokens + tracking | **PASS_WITH_FINDING** | Hero h1 uses `.poster-h1` (not `.t-display`) but FRL 900 + `letter-spacing:-.035em` (`t7.css` L669–676); computed @375 `letterSpacing:-1.68px`, `fontWeight:900`. `components.css` adds tracking on `.t-display/.t-h2…` for section headings |
| AT-D3 | No literal `#fff` backgrounds in t1/t7 | **PASS** | `grep #fff` in t7/t1 → text-on-badge uses only (e.g. `.fp-card .scope.venture` text color); backgrounds use `var(--paper)` / wash tokens. One t7 line is comment-only |
| AT-D4 | World cards full profile + chips | **PASS** | Live snapshot: 3 world cards with num·title·tagline·list·more; `.wc` chips in markup/CSS (`components.css`, `nb_world_chip()`) |
| AT-D5 | Bridges band on home (3 dual-world cards) | **PASS** | `front-page.php` `.t7-bridges.bridges-band`; live 3× `.bridge-card` with dual-color spines |
| AT-D6 | Unless typographic lockup (FRL 900, spark period) | **PASS** | `.unless-lockup` live; computed `.word` → FRL 900, 64px; spark period `.pd { color: var(--spark) }` |
| AT-D7 | Basket emblems | **PASS** | World cards use basket PNG emblems + `.nb-emblem` CSS; live world section renders emblems |
| AT-D8 | World icons (2-stroke SVG) | **PASS** | Nav world icons + card glyphs; `assets/icons/world-*.svg` wired in shell/t7 |
| AT-D9 | Negentropy concept SVG | **PASS** | Worlds section backdrop label present in snapshot (“נֶגֶנְטְרוֹפְּיָה…”) |
| AT-D10 | IconPark strip | **PASS** | `front-page.php` includes `icon-sprite.php`; `<use href="#ip-*">` on hero/world rows |
| AT-D11 | Vertical rhythm; no abusive inline overrides | **PASS_WITH_FINDING** | Bridge cards use intentional inline CSS vars (`--bridge-a/b`) + `.img-ph` aspect-ratio inline per components contract; no inline border hacks |
| AT-D12 | `.img-ph.clean` fallback; aspect-ratio on container | **PASS** | SFA/tiktrack rows use `.img-ph.clean`; computed placeholder `height:180px` (not collapsed); `aspect-ratio` on container via CSS/default + inline where specified |
| AT-D13 | No h-scroll @375 on T7 + world pages | **FAIL (home)** / **PASS (world)** | Home @375 CDP: `scrollWidth=388`, `clientWidth=375`, `scrollX≈-13.5`. @360: `scrollWidth=372`, `clientWidth=360`, `scrollX≈-12`. Offender chain: `.shell-nav-inner` `padding:14px` (`shell.css` L14–15) → content box 375px inset → extends past viewport. `/world/soil/` @375: `scrollWidth=clientWidth=375` ✓ |
| AT-D14 | RTL logical properties in new CSS | **PASS** | `grep '(left\|right):'` in `t7.css` + `components.css` → 0 physical edge props; carousel JS RTL-aware |
| AT-D15 | Nav `.atop` transparency (load+scroll) | **PASS** | @375 top: `.shell-nav.atop`, `background:rgba(18,17,15,.5)`. After `scrollY=900`: `.atop` removed, solid bar |
| AT-D16 | Carousels scroll-snap + arrows | **PASS** | `.products-grid.carousel`: `scroll-snap-type:x mandatory`, `overflow-x:auto`. Arrow click + `scrollBy(-320)` → `scrollLeft` 0→-256; `nb-carousel.js` enqueued |
| AT-D17 | WP002 mobile NOT regressed | **PASS** | @375: `.nav-toggle` + `.nav-drawer` present; drawer open/close via toggle ✓; `body.nav-open` hides `.wa-fab` ✓; FAB visible when drawer closed ✓ |
| AT-D18 | `system.css` LOCK intact | **PASS** | `git diff 87e2322c..HEAD -- system.css` → empty (0 bytes) |
| AT-D19 | Lighthouse ≥90 perf / ≥95 a11y | **ADVISORY** | Not run this cycle; page image-heavy — documented follow-on per LOD400 §6 note |

---

## Constitutional Audit

| Check | Result | Evidence |
|---|---|---|
| WP002 `t7.css` `@media` block | **PASS** | Byte-identical from `/* MOBILE — P009-WP002 */` marker vs commit `87e2322c` (`diff -u` → 0 lines) |
| WP002 `shell.css` MOBILE SHELL/BASE/`.wa-fab` | **PASS** | From `/* MOBILE SHELL — P009-WP002 */` marker vs `87e2322c` → 0 lines changed. (Desktop shell edits above block are WP003-permitted nav/footer fidelity — not inside protected block.) |
| APPEND-ONLY discipline | **PASS** | WP003 rules inserted **above** protected WP002 block in `t7.css` (comments L605, L886, L1215) |
| `system.css` LOCK | **PASS** | No commits touching file since WP002; empty diff |
| `!important` audit | **PASS** | `t7.css`=0, `shell.css`=0, `components.css`=0 actual declarations (spec note cited 1 — build achieved zero; comment L16 documents intent) |
| `validate_aos.sh` | **PASS (Check 12 benign)** | 31 PASS / 16 SKIP / 1 FAIL. Check 12 = cross-project pattern false-positive in docs/scripts (`tiktrack`, `smallfarmsagents`, etc.) — known benign per mandate |
| Cross-engine | **PASS** | Iron Rule #1 satisfied |

---

## team_50 / Fidelity Evidence

| Item | Status | Notes |
|---|---|---|
| `DEVICE_CHECK_NB-S002-P009-WP003_*.md` | **NOT FILED** | Only `DEVICE_CHECK_REQUEST` present |
| team_190 MCP substitute | **PARTIAL** | Section stack present (hero→worlds→systems→services→bridges→unless→projects→manifesto→CTA→footer); functional checks above. Pixel fidelity vs `Precision Mockup.html` **not independently scored** — await team_50 or team_100 gate-sign disposition |

---

## Findings

| ID | Severity | Finding | evidence-by-path | route_recommendation |
|---|---|---|---|---|
| F1 | **MEDIUM** | AT-D13 home h-scroll ~12–13px @360/@375 from shell padding vs full-bleed hero width | Live CDP @375/@360; `shell.css` L14–15 `.shell-nav-inner{padding:14px…}` | team_10: add `overflow-x:clip` on `html/body` or zero horizontal shell padding on front-page / widen hero negative margin to absorb inset; re-deploy ≥0.6.2 |
| F2 | LOW | AT-D2 hero h1 uses `.poster-h1` not `.t-display` (tracking equivalent) | `front-page.php` L36; `t7.css` L669–676 | Optional: add `.t-display` class for spec literalism — non-blocking |
| F3 | INFO | team_50 DEVICE_CHECK deliverable absent | `_COMMUNICATION/team_50/` — request only | team_50: file DEVICE_CHECK + screenshots for audit trail |
| F4 | INFO | AT-D19 Lighthouse not measured | LOD400 §6 advisory | Re-measure post final asset delivery |
| F5 | INFO | `validate_aos.sh` Check 12 FAIL (benign) | docs/qa + seed scripts pattern match | No action — pre-existing spoke debt |

---

## Verdict

**`PASS_WITH_FINDINGS`**

WP **NB-S002-P009-WP003** clears **L-GATE_VALIDATE** for gate progression **conditional on F1 disposition** (home h-scroll fix before production cutover). Constitutional + core precision ACs satisfied; cross-engine validation complete.

*Validated by team_190 (Codex) · 2026-05-29*
