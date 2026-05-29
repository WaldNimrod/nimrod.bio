---
type: VERDICT
from: team_190 (Independent Validator · Codex)
to: team_100
wp_id: NB-S002-P009-WP002
project: nimrod-bio
milestone: V200
program: P009
date: 2026-05-29
gate: L-GATE_VALIDATE
track: A · STANDARD
verdict: HOLD
scope: mobile_responsiveness_m1_m20_constitutional_code_audit
builder_engine: Claude Code / team_100 (orchestrated sub-agents)
validator_engine: Codex / team_190
cross_engine: preserved (Iron Rule #1)
---

# VERDICT — NB-S002-P009-WP002 — L-GATE_VALIDATE

## Executive Summary

Independent validation opened per VALIDATE_REQUEST v1.0.0 (2026-05-29). Cross-engine independence preserved (Claude build → Codex validate).

**Gate result: `HOLD`** — pre-condition not met. Dev site `http://nimrod-bio-2026.s887.upress.link` is still serving theme **`0.4.1`** (`shell.css?ver=0.4.1`); live HTML contains **no** `nav-toggle`, `nav-drawer`, `wa-fab`, or `nav-drawer.js`. Runtime acceptance tests **M1–M16 cannot be executed** against the mandated target until FTPS deploy + `wp media regenerate` (COMPLETION §6 / D3).

**Code + constitutional audit (repo commit `87e2322c`, `NB_THEME_VERSION=0.5.0` in git):** all checkable items **PASS** or **PASS per locked deviation D1**. No code-level blockers observed. Re-route for runtime replay immediately after deploy confirms `?ver=0.5.0` on enqueued theme assets.

---

## Pre-condition Check

| Requirement | Result | Evidence |
|---|---|---|
| Dev deploy live @ v0.5.0 | **FAIL — HOLD** | `curl` homepage 2026-05-29: `shell.css?ver=0.4.1`; grep for `nav-toggle` / `nav-drawer` / `wa-fab` / `nav-drawer.js` → **0 matches** |
| `wp media regenerate` | **NOT VERIFIED** | Blocked by deploy; D3 remains deploy-time step |
| Dev site reachable | PASS | HTTP 200 on `/`, `/world/soil/`, `/blog/`, `/about/`, `/contact/` |

**Next unblock:** `python3 scripts/upress_ftps_upload.py` → confirm `?ver=0.5.0` → `wp media regenerate --yes` on dev → re-issue VALIDATE replay (M1–M16 runtime).

---

## Acceptance Tests (M1–M20)

Evidence: code inspection of commit `87e2322c` + live HTTP where deploy permits. Runtime viewport / DevTools / Lighthouse rows marked **HOLD** pending deploy.

| # | Test | Result | Independent evidence |
|---|---|---|---|
| M1 | Shell nav @375px — hamburger visible, desktop nav hidden | **HOLD** | Code: `shell.css` `@media (max-width:640px)` shows `.nav-toggle`, hides `.shell-links`. Live dev: no `nav-toggle` in HTML (0.4.1). |
| M2 | Drawer open/close (toggle, backdrop, ESC) | **HOLD** | Code: `nav-drawer.js` implements all 3 close paths + body scroll lock + `nav-open` class. Live: JS/CSS not enqueued on dev. |
| M3 | Drawer a11y — focus trap + aria | **PASS (code)** / **HOLD (runtime)** | Markup: `shell-nav.php` — `aria-expanded`, `aria-controls="nav-drawer"`, drawer `aria-hidden`, `id="nav-drawer"`. JS: Tab/Shift+Tab trap, focus first link on open, return to toggle on close. Runtime axe/manual deferred. |
| M4 | WhatsApp FAB on service page @375px | **HOLD** | Code: `shell-footer.php` `.wa-fab` 56×56; `shell.css` shows @ ≤900px when `body:not([data-page="contact"])`. Live: element absent. T2 probe URL (post-deploy): `/services/consulting-hydro/`. |
| M5 | WhatsApp FAB absent on `/contact/` | **HOLD** | Code: `header.php` `data-page` attr + CSS suppression rule. Live: FAB markup not deployed. |
| M6 | Footer 1-col @375px, 2-col @768px | **HOLD** | Code: `shell.css` footer reflow @900px / @640px matches spec §2.2. Live runtime deferred. |
| M7 | T1 lattice reflow | **HOLD** | Code: `t1.css` MOBILE block — anchor full-width @900px, 1-col @640px, echoes hidden, rotations removed. Live deferred. |
| M8 | T2 hero stack + CTA full-width | **HOLD** | Code: `t2.css` MOBILE block — hero stack, `hero-cta-row` column, bridge corner 36px. Live deferred. |
| M9 | T3 story / outcomes reflow | **HOLD** | Code: `t3.css` — single-col story, outcomes 2→1 col, gallery 2-col @640px. Live deferred. |
| M10 | T4 aside at bottom | **HOLD** | Code: `t4.css` @1100px surfaces `.post-aside` below body (static, border-top). Pre-existing hide rule superseded by append-only spec block (documented D4). Live deferred. |
| M11 | T5 flow linearize + filter-bar scroll | **HOLD** | Code: `t5.css` — `.flow-item` 1-col @760px; filter-bar `overflow-x:auto` @640px. Live deferred. |
| M12 | T7 worlds / Unless ribbon | **HOLD** | Code: `t7.css` — worlds 2→1 col; `.unless-ribbon` / `.unless-block.ribbon` stack; D5 selector adaptation documented + comma-grouped. Live deferred. |
| M13 | T8 contact inputs ≥16px | **PASS (code)** / **HOLD (runtime)** | Code: `t8.css` `@media (max-width:760px)` — `.field input, .field textarea, .field select { font-size: 16px; }`. Computed-style probe deferred. |
| M14 | T8 about gallery 3-col, hide 4–5 | **HOLD** | Code: `t8.css` — `.about-gallery { grid-template-columns: repeat(3,1fr) }`; `:nth-child(n+4) { display:none }` without rogue `!important`. Live deferred. |
| M15 | Zero horizontal scroll @360px all templates | **HOLD** | Requires post-deploy DevTools on 7 templates + shell. Code review: mobile blocks use `max-width:100%`, logical padding, filter-bar contained scroll. |
| M16 | Touch targets ≥44×44 | **HOLD** | Code: `.nav-toggle` 44×44; `.drawer-link` `min-height:44px`; `.wa-fab` 56×56. **Advisory:** `.drawer-close` is **36×36** per locked spec §2.1 — accept as spec exception if runtime audit flags it. |
| M17 | Lighthouse Performance ≥90 (T7) | **ADVISORY** | Per team_00 / VALIDATE_REQUEST: measure post-deploy; WP001 final assets required for truthful LCP. Not gating this cycle. |
| M18 | Lighthouse A11y (T7) | **ADVISORY → PASS_WITH_FINDINGS when run** | Per team_00 acceptance 2026-05-29: ≥95 acceptable as PASS_WITH_FINDINGS. Deferred post-deploy. |
| M19 | Lighthouse Performance ≥90 (T1,T2,T4) | **ADVISORY** | Same as M17. Deferred post-deploy. |
| M20 | `!important` audit (D1 interpretation) | **PASS** | WP002 commit adds **5** instances (t1:2, t3:1, t5:2). Each spec-derived with intentional comment (`mobile override — intentional` or equivalent spec § citation). No rogue/uncommented additions in commit diff. Pre-existing t1 `@768px` pair (lines 617–618) predates WP002 — out of scope for “added” count. Locked D1 interpretation applied; count >2 **not** failed. |

---

## Constitutional Audit

| Check | Result | Evidence |
|---|---|---|
| APPEND-ONLY mobile CSS (no desktop rule modified) | **PASS** | `git show 87e2322c` on `t1.css`–`t8.css`: additions only at file tail (`MOBILE — P009-WP002` blocks). No `-` lines removing/changing existing desktop rules in template CSS files. |
| `system.css` LOCK intact | **PASS** | `git show 87e2322c -- system.css` → **0 lines**; breakpoint vars relocated to `shell.css` MOBILE BASE block. |
| RTL logical properties in new CSS | **PASS** | `shell.css` mobile shell uses `inset-inline-*`, `border-inline-start`, `padding-inline`; grep for `left:`/`right:`/`margin-left` in `shell.css` → **0 matches**. |
| `nav-drawer.js` enqueued (defer, footer) | **PASS** | `inc/enqueue.php` — `nb-nav-drawer`, `strategy=>defer`, `in_footer=>true`, `NB_THEME_VERSION`. |
| Image sizes registered | **PASS** | `functions.php` — `nb-hero-mobile` (800), `nb-hero-tablet` (1280). |
| Font subset + display=swap | **PASS** | `enqueue.php` URL includes `display=swap&subset=hebrew,latin`. |
| PHP / JS syntax | **PASS** | `php -l` shell-nav.php, header.php; `node --check` nav-drawer.js — clean. |
| `validate_aos.sh` | **PASS (advisory note)** | `31 PASS / 16 SKIP / 1 FAIL` — Check 12 cross-project contamination (known benign content false-positive per VALIDATE_REQUEST). All other checks PASS. |
| Cross-engine independence | **PASS** | Builder = Claude (team_100); validator = Codex (team_190). |
| Locked deviations honored | **PASS** | D1 M20 reinterpretation applied. D2 share-FAB CSS inert (no markup). D4 redundancy acknowledged. D5 T7 selectors adapted with forward-compat comments in `t7.css`. |

---

## Findings (Non-blocking until deploy replay)

| ID | Severity | Finding | Route |
|---|---|---|---|
| F1 | **BLOCKER (deploy)** | Dev theme still **0.4.1**; WP002 assets not live | team_100 / team_00: execute COMPLETION §6 deploy + media regenerate |
| F2 | LOW | `.drawer-close` 36×36 vs M16 44×44 — locked spec §2.1 value | Accept at runtime if visible hit area meets spec; else file GCR to team_35 |
| F3 | INFO | Local Docker (`localhost:8085`) serves legacy production theme, not `nimrod-bio-2026` — not a valid alternate validation target | No action |

---

## Verdict

**`HOLD`**

WP002 **build artifacts in git pass** independent code/constitutional review. **L-GATE_VALIDATE cannot close** until dev deploy pre-condition is satisfied and runtime replay of **M1–M16** (plus advisory M17–M19) completes on `http://nimrod-bio-2026.s887.upress.link` with `?ver=0.5.0`.

**Re-route trigger:** after deploy confirmation, team_100 re-issues VALIDATE replay (or team_190 resumes from §Acceptance Tests without re-reading build).

---

*Validated by team_190 (Codex) · 2026-05-29 · validator engine ≠ builder engine (Iron Rule #1)*
