---
id: MANDATE_NB-S002-P007-WP001_MCP_DESIGN_QA
type: BUILD_MANDATE (QA scope)
from: team_110 (Domain Architect)
to: team_50 (QA & Functional Acceptance, MCP-extended)
cc: team_00, team_110 (orchestrator session)
project: nimrod-bio
milestone: V200
wp_id: NB-S002-P007-WP001 (proposed; DB registration pending per FOLLOW_UP)
wave: 1 of 4 (P007 Pre-Cutover Completion)
date: 2026-05-27
priority: P1
status: PARKED — activated by orchestrator session after handoff
engine: Cursor Composer + MCP (Claude_in_Chrome OR Claude_Preview)
wp_registration_status: PENDING_DB_DOMAIN_PROVISIONING
---

# Wave 1 MANDATE — MCP Browser QA + Design Fidelity Sweep

## 1. Authority

team_00 directive 2026-05-27 — "השלמת פריסה לסביבה הזמנית + בדיקות mcp מלאות כולל בדיקות דיוק עיצוב עי צילומי מסך"
team_110 PLAN_P007_PRE_CUTOVER_COMPLETION_v1.0.0.md §4 Wave 1
COMPLETION_CONTENT_PHASE v1.0.0 (aef6fbf7) — V200 sub-phase 1 closed; sub-phase 2 begins here

## 2. Mission

Validate that the dev URL (`https://nimrod-bio-2026.s887.upress.link`) renders **as intended per team_35 design package** across all templates + viewports + interaction paths. Capture canonical screenshots for before/after comparison in Wave 4.

## 3. Inputs (read before starting)

| # | Path | Purpose |
|---|---|---|
| 1 | `_COMMUNICATION/team_110/PLAN_P007_PRE_CUTOVER_COMPLETION_2026-05-27_v1.0.0.md` | overall context |
| 2 | `_COMMUNICATION/team_110/COMPLETION_CONTENT_PHASE_2026-05-27_v1.0.0.md` | sub-phase 1 close |
| 3 | `_COMMUNICATION/team_50/QA_REPORT_NB-V200-FULL-PRE-CUTOVER_2026-05-27_v1.0.0.md` | prior QA (function-only) |
| 4 | `sources/team_35_design_package/` (gitignored, on disk) | design source of truth |
| 5 | `_COMMUNICATION/team_50/MANDATE_NB-V200-FULL-QA-PRE-CUTOVER_2026-05-26_v1.0.0.md` | prior MANDATE (for AT continuity) |

## 4. Scope — 10 inspection items

### 4.1 Screenshot capture (mandatory)

Use MCP tools (`Claude_in_Chrome` `mcp__Claude_in_Chrome__navigate` + `mcp__Claude_Preview__preview_screenshot` OR equivalent). Save to `docs/qa/screenshots/p007-wp001/<template>_<viewport>.png`.

Templates × viewports to capture:
- **T7 home** — `/` — 3 viewports (375 / 768 / 1440)
- **T1 worlds** — `/world/soil/`, `/world/know/`, `/world/code/` — 1440 viewport
- **T2 services** — sample 3 of 10: `/services/produce/`, `/services/consulting-hydro/`, `/services/teaching/` — 1440 viewport
- **T3 projects** — all 5: `/project/coop-sharon/`, `/project/hagina-shel-nimrod/`, `/project/restaurant-supply/`, `/project/farm-y-bcs/`, `/project/rest-x-greenhouse/` — 1440 viewport
- **T4 single post** — sample 5: 2 migrated (Hebrew slug + ASCII slug) + 3 placeholder (`/blog/agents-os/`, `/blog/israel-microgreens/`, `/blog/back-to-mud/`) — 1440 viewport
- **T5 blog index** — `/blog/` + `/blog/page/2/` — 1440 viewport
- **T8 static** — `/about/`, `/about/heritage/`, `/contact/` — 1440 viewport
- **Error states** — `/blog/non-existent-slug/` (404) + `/contact/?status=ok` — 1440 viewport

**Net:** ~25-30 screenshots minimum.

### 4.2 Design fidelity comparison

For each captured screenshot, compare against the equivalent in `sources/team_35_design_package/` (look in `02-static-mockups/` or similar subdir). Document:
- ✅ Match (within 5px tolerance for spacing, exact for color tokens)
- ⚠ Minor delta (e.g., typography weight off, spacing off)
- ❌ Major delta (missing component, wrong layout, color mismatch)

Save per-template comparison notes in `docs/qa/visual-diffs/p007-wp001/<template>_design-vs-actual.md`.

### 4.3 Responsive QA

Mobile (375px) for T7, T1, T2, T4 minimum. Check:
- Layout doesn't break
- Touch targets ≥44px
- Hebrew RTL still renders correctly
- No horizontal scroll

### 4.4 Hebrew RTL render

For every screenshot: confirm text aligns right, line-height OK, punctuation positions correctly. Special attention to mixed Hebrew + English (e.g., "אלה אם — Unless" post).

### 4.5 Interactive elements

Use MCP click/eval tools:
- T7 hero CTAs → land on expected URLs
- world tiles → world archives
- service tiles → service pages
- project tiles → project pages
- blog post → single post
- contact form submit (re-test from team_50 prior — confirm still works)

### 4.6 Forms — already tested but screenshot record

`/contact/` form happy path + error path → screenshots of each state for record. Verify still PASSes from team_50 prior sweep.

### 4.7 Error states

`/blog/non-existent/` should render 404 page (not WP default). Screenshot.

### 4.8 Network panel inspection (informational only)

For T7 home, use MCP `mcp__Claude_in_Chrome__read_network_requests`:
- Total request count
- Total transfer size (KB)
- Largest 5 assets
- Any 4xx/5xx requests

**Not gating** — V300 territory for optimization. Just inform inventory.

### 4.9 Console errors

For each template captured, run `mcp__Claude_in_Chrome__read_console_messages`. Any JS errors → log with severity.

### 4.10 Accessibility quick-pass

Per-template:
- `<img>` without `alt` attribute count
- Heading hierarchy (h1 → h2 → h3) skips?
- Color contrast on text-on-background (eyeball; full audit V300)

**Not gating** — informational for Wave 2 inventory.

## 5. Deliverable

`_COMMUNICATION/team_50/MCP_QA_REPORT_NB-S002-P007-WP001_<YYYY-MM-DD>_v1.0.0.md`

Required sections:
- **§0 Summary** — PASS / PASS_WITH_FINDINGS / FAIL
- **§1 Methodology** — MCP tool stack used + dev URL session
- **§2 Screenshots index** — list with `docs/qa/screenshots/p007-wp001/` paths
- **§3 Design fidelity table** — per template: status + visual-diff path
- **§4 Responsive findings** — per breakpoint
- **§5 Interactive trace** — happy paths + click results
- **§6 Findings table** — ID / severity / description / evidence path / route_recommendation
- **§7 Acceptance criteria results** — see §6 below
- **§8 Recommendations for Wave 2 inventory** — what content/media/decisions surfaced

Plus commit: `docs/qa/screenshots/p007-wp001/*.png` + `docs/qa/visual-diffs/p007-wp001/*.md`.

## 6. Acceptance tests

| # | Criterion | PASS condition |
|---|---|---|
| AT-Q1 | All 25+ screenshots captured | Files present + visible |
| AT-Q2 | Design fidelity match | ≥90% of templates "✅ Match" or "⚠ Minor" (max 2 "❌ Major") |
| AT-Q3 | Mobile responsive | No layout break; no horizontal scroll on 375px |
| AT-Q4 | Hebrew RTL | All Hebrew text aligned right; punctuation correct |
| AT-Q5 | Interactive paths | All click-throughs land on expected URLs (no 404s except deliberate test) |
| AT-Q6 | Contact form | Happy path PASS (re-verify); status=ok URL returns 200 |
| AT-Q7 | 404 page | Custom 404 renders (not generic WP) |
| AT-Q8 | Console errors | 0 critical JS errors on T7 home + T4 single post (warnings OK) |
| AT-Q9 | Inventory feed | §8 of report contains ≥10 actionable items for Wave 2 |
| AT-Q10 | All ascii + Hebrew slugs reachable | 100% of sampled URLs return 200 |

## 7. STOP conditions — escalate to team_110 (orchestrator)

- Dev URL down or unstable (HTTP non-200 on home for >2 consecutive attempts)
- MCP tools unavailable (Claude_in_Chrome / Claude_Preview not loaded in session)
- `sources/team_35_design_package/` missing — design comparison impossible
- AT-Q2 fails with >2 "❌ Major" deltas (signals deeper template issue, not just content gap)
- AT-Q5 fails on contact form (regression from team_50 prior PASS)

## 8. Out-of-scope

- Performance optimization (V300)
- Accessibility full audit (V300)
- Content/media filling (Wave 3 territory)
- Theme/CSS changes (LOCKED)
- Validate constitutional review (Wave 4 / team_190 territory)

## 9. Activation prompt (paste to new Cursor session as team_50)

```
═══════════════════════════════════════════════════════════════
TEAM 50 — QA & Functional Acceptance (Cursor + MCP)
ACTIVATION — V200 Wave 1 · MCP Browser QA + Design Fidelity
═══════════════════════════════════════════════════════════════

זהות
────
- Team ID: team_50
- Engine: Cursor Composer + MCP tools (Claude_in_Chrome / Claude_Preview)
- Role: QA & Functional Acceptance (GCR-002 binding)
- Governance: /Users/nimrod/Documents/agents-os/_aos/governance/team_50.md
- Wave: 1 of 4 in P007 — Pre-Cutover Completion

קונטקסט
───────
- Project: nimrod-bio · Milestone: V200 (sub-phase 2 active)
- Dev URL: https://nimrod-bio-2026.s887.upress.link
- State: 33 פוסטים · 10 services · 5 projects · 843 media files
- Predecessor: COMPLETION_CONTENT_PHASE v1.0.0 (aef6fbf7) — sub-phase 1 closed
- Design package: sources/team_35_design_package/ (gitignored, on disk)

המנדט
─────
_COMMUNICATION/team_50/MANDATE_NB-S002-P007-WP001_MCP_DESIGN_QA_v1.0.0.md
(קרא במלואו — 10 inspection items, 10 acceptance tests)

המשימה
──────
1. /AOS_mail — קרא ה-MANDATE
2. וודא MCP tools זמינים (Claude_in_Chrome + Claude_Preview) — אם לא — STOP escalate
3. בצע 4.1 (~25-30 screenshots) → 4.10 (a11y quick-pass)
4. הפק MCP_QA_REPORT artifact + commit screenshots + visual-diffs
5. דווח לעצמך orchestrator (team_110 new session) דרך COMPLETION

Out of scope: Lighthouse uplift, a11y full audit, content filling, theme changes.
ETA: ~2-3 שעות.
═══════════════════════════════════════════════════════════════
```

— team_110 (originating session) — 2026-05-27
