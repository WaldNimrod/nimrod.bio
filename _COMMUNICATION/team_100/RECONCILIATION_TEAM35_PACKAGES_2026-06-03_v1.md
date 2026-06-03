# RECONCILIATION — team_35 packages level-set (pre design-precision pass) — team_100 — v1

**Date:** 2026-06-03 · **Author:** team_100 (Claude Code) · **Method:** filesystem + live-dev + REST ground truth (roadmap notes NOT trusted — several had drifted)
**Trigger:** team_00 directive — verify all team_35 packages are fully closed, no information drift, ready for the design-precision pass against the current mockups.

---

## §0 · Headline
**Every team_35 delivery is implemented and LIVE on dev.** The confusion was **SSoT drift**, not missing work: the roadmap WP005 note still said "BCS deferred / assets pending" while all of it is wired and rendering. Corrected below. Two genuine open items remain (real screenshots; data-layer durability). The design-precision *walk* (G2/G3) is the next phase — **planned (WP007), not yet done**.

## §1 · Delivered → integrated → live (verified this session)

| team_35 delivery | Doc | In repo? | Live on dev? | Verdict |
|---|---|---|---|---|
| **BCS gallery** (code: service `_nb_gallery` meta reg + `single-service` include + t3 enqueue) | COMPLETION_BCS_GALLERY 06-01 | ✅ committed | ✅ 9 imgs render | **DONE** (was falsely "deferred" in roadmap) |
| **BCS gallery photos** (media 1090–1098) + meta | same | data-layer | ✅ service 24 `_nb_gallery` = 9 IDs | **DONE** |
| **Photo gaps** pak-bung (1100) + tiller (1101–1108) | COMPLETION_PHOTO_GAPS 06-01 | data-layer | ✅ service 24 = 17 IDs; project 31 = 14 IDs | **DONE** |
| **Garden gallery** (media 1065–1071) | WP005 progress | data-layer | ✅ project 49 = 7 IDs | **DONE** |
| **Greenhouse + עירית שומית** (1072–1084) | WP005 progress | data-layer | ✅ project 31 | **DONE** |
| **QA V200B fixes** F-002 (/services/ archive) · F-003 (gallery overflow) · F-004 (email removed) | COMPLETION_QA_FIXES_V200B 06-01 | ✅ | ✅ /services/ 200, archive live | **DONE** (folded into WP001 stack) |
| **§06 Recent-Posts + /projects/ archive + 3-source precision fixes** | HANDOFF_CLAUDE_CODE_V200 06-03 | ✅ | ✅ | **DONE** = WP001 (COMPLETE/LOD500) |
| **v6 assets**: logo-master/mark.svg, favicon svg/32/48/180, og-image, watercolor washes | WP004 | ✅ in `assets/img/` | ✅ used | **DONE** = WP004 (COMPLETE) |
| **World icons ×7** (soil/know/code + 3 bridges + negentropy + spark + home) | Stage-3 | ✅ `assets/icons/` | ✅ | **DONE** |

## §2 · V4 precision README (the "complementary package") — task-by-task status

`_INBOX_design_handoff_v200/design_handoff_ui_precision_v200/` (Precision Mockup v4 + README + UI_DESIGN_REVIEW). **All 7 task groups implemented** across WP001/003/004 (+ a11y WP006):

| V4 task | Status | Evidence |
|---|---|---|
| 1 · Lock + fact corrections; `.vc-cdip`→`.vc-principle` | ✅ | 0 `.vc-cdip` remnants; `.vc-principle` live |
| 2 · System templates 404 / search / empty-archive | ✅ | 404.php layout, search.php results-list, `template-parts/empty-state.php` |
| 3 · Media degradation `.ph.clean` / dev-only TBD | ✅ | `.img-ph clean` in cards |
| 4 · Contact hero + WhatsApp primary | ✅ | t8 contact hero + `.btn-wa`/`.wa-btn` (AA-fixed in WP006) |
| 5 · About timeline + "קצת ים" | ✅ | `t8-journey-timeline.php`, principle-grid |
| 6 · Counts + external links (`_nb_external_url`) | ✅ | `nb_world_activity_count`, `.ext-link` helper |
| 7 · Dead code (`nb_render_cdip_diagram`, `t8-media-item.php`) | ✅ removed | both absent |

## §3 · Drift corrected
- **roadmap WP005 note** said BCS gallery "DEFERRED → team_35" + open-asset swap "pending delivery". **FALSE** — BCS + all galleries wired & live; v6 assets integrated (WP004). Note corrected this session.
- team_35 COMPLETIONs (BCS / photo-gaps / QA-fixes) ARE git-tracked but were never folded into a WP closure note. Now reflected in WP005.

## §4 · Genuine OPEN items (small, owner/cutover-bound)
1. **Real SFA / tiktrack screenshots** — `assets/img/sfa-demo.svg` + `tiktrack-demo.svg` are DEMO placeholders (marked). Real app captures pending from owner/team_00. Non-blocking; hot-swap when delivered.
2. **Data-layer durability (cutover risk).** All gallery wiring (`_nb_gallery` meta + media attachments 1065–1108) lives **only on the dev uPress DB** — no repo seed/migration captures it. On cutover (P005-WP002) the prod DB must carry the dev DB content, OR a durable seed must be authored, else galleries unwire. **Flag to P005-WP002.**
3. **Owner photo gaps (DO NOT substitute):** tiller power-harrow (מטחחת אקולוגית), sea/boat, biochar — no source in Drive; `.ph.clean` fallback by design.

## §5 · Readiness for the design-precision pass
**Structure + the V4 task list are complete and live.** What is NOT yet done is the **pixel-fidelity WALK** of the non-home/non-world templates against `Precision Mockup v4.html`:
- **G2** — T2 Services · T3 Project-single · T4 Post-single · T5 Blog-index precision walk (stage-3 structure exists; never formally walked to v4 fidelity).
- **G3** — know/code world-page variants + heritage parity (T1 soil is precision-final; know/code variants + heritage need the same pass).

This is exactly **NB-S002-P009-WP007 (PLANNED)**. Recommended process in §6.

## §6 · Recommended process — WP007 design-precision walk
1. **Canonical ref:** `Precision Mockup v4.html` (the V4 bundle) — current SSoT. Switch screens T7/T1/T8/system/states.
2. **Per-template fidelity diff** (read-only first): for each of T2/T3/T4/T5 + world know/code + heritage, CDP-render live dev vs mockup screen; capture deltas (type scale, --sec-y rhythm, tokens, component precision, RTL logical props, 375px no-scroll, hover lifts, spark budget ≤3–5).
3. **LOD400** scoping the deltas as ATs (module CSS + template-parts only; no inline/no overrides; system.css LOCKED; super-locks).
4. **Build** (team_100 orchestrated) → FTPS → byte-parity → CDP + a11y non-regression (WP006 baseline must hold: axe 0, Lighthouse a11y ≥95).
5. **Cross-engine validate** (Cursor/team_190 + team_50; Codex unavailable — Cursor≠Claude Code preserves Iron Rule #1).
6. **Closure** ADR042.
Pre-req inputs still owner-bound (non-blocking, hot-swap): real screenshots (§4.1).

## §7 · Verdict
- **All old team_35 packages: substantively CLOSED + LIVE.** Drift was in the roadmap note (now corrected), not the build.
- **WP005** (media wiring): scope DONE & live → needs formal closure decision (team_00 acceptance like WP004, or a light validate) + residual = screenshots + durability. **Recommend close with those two as carry-forward.**
- **Ready for the precision pass (WP007)** once team_00 authorizes; the V4 mockup is the reference.

*team_100 · reconciliation ledger · 2026-06-03 · dev v0.7.19 · ground truth = files + live REST*
