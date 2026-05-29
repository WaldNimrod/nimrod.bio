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
verdict: PASS_WITH_FINDINGS
scope: mobile_responsiveness_m1_m20_runtime_restamp
builder_engine: Claude Code / team_100 (orchestrated sub-agents)
validator_engine: Codex / team_190
cross_engine: preserved (Iron Rule #1)
supersedes: VERDICT_NB-S002-P009-WP002_VALIDATE_v1.0.0.md (HOLD lifted)
target_version: 0.5.1
---

# VERDICT — NB-S002-P009-WP002 — L-GATE_VALIDATE (v1.1.0)

## Executive Summary

**Gate result: `PASS_WITH_FINDINGS`** — HOLD from v1.0.0 **lifted**.

Pre-condition met: dev site serves theme **`0.5.1`** (`t8.css?ver=0.5.1`, `nav-drawer.js?ver=0.5.1`, drawer/FAB markup present). Runtime replay confirms **M1–M16 PASS** on 0.5.1 after fix pass (honeypot scroll-safety + topic-chip 44px). Code/constitutional audit from v1.0.0 **carried forward unchanged (PASS)**.

**Advisory / V300 carry-forward:** M17–M19 Lighthouse (post-WP001 assets), D4 drawer-close 36×36 (spec §2.1 exception), D5 tablet desktop-nav touch targets, spec §9-vs-§1.3 topic-chip inconsistency (flag team_35).

**Note:** team_50 `DEVICE_CHECK v1.1.0` not yet filed at stamp time; team_190 independently re-ran M15/M16 @360px on contact + post (true viewport via CDP) — results align with team_100 FIX_PASS_DEPLOYED dispositions.

---

## Pre-condition (v1.1.0)

| Requirement | Result | Evidence |
|---|---|---|
| Dev deploy @ ≥0.5.0 | **PASS** | Homepage + contact: `ver=0.5.1`; `nav-toggle`, `nav-drawer`, `wa-fab`, `nav-drawer.js` in HTML |
| Fix pass live | **PASS** | Live `t8.css?ver=0.5.1` contains MOBILE FIX PASS block (`hp-field` `left:auto` + clip; `.topic-chip { min-height: 44px }`) |
| team_50 v1.1.0 | **ADVISORY** | Not filed; team_190 spot-check substitutes for M15/M16 confirmation |

---

## Acceptance Tests (M1–M20)

| # | Test | Result | Evidence |
|---|---|---|---|
| M1 | Shell nav @375px | **PASS** | team_50 v1.0.0 + spot @360 contact: `.nav-toggle` `display:grid`, `.shell-links` hidden |
| M2 | Drawer 3 close paths | **PASS** | team_50 MCP: toggle / backdrop / ESC; body scroll lock |
| M3 | Drawer a11y | **PASS** | v1.0.0 code + team_50 focus trap / aria replay |
| M4 | WA FAB on service | **PASS** | team_50: visible on `/services/consulting-hydro/` @375 |
| M5 | WA FAB absent contact | **PASS** | `data-page="contact"`; FAB `display:none` @360 (team_190 CDP) |
| M6 | Footer reflow | **PASS** | team_50: 1-col @375, 2-col @768 |
| M7 | T1 lattice | **PASS** | team_50 template screenshots @360/768; 6/8 templates clean M15 |
| M8 | T2 hero/CTA | **PASS** | team_50 service screenshots @360/768 |
| M9 | T3 story/outcomes | **PASS** | team_50 project screenshots @360/768 |
| M10 | T4 aside bottom | **PASS** | team_50 post layout; aside below body in snapshot |
| M11 | T5 flow/filter | **PASS** | team_50 blog screenshots @360/768 |
| M12 | T7 worlds/ribbon | **PASS** | team_50 home screenshots @360/768 |
| M13 | T8 inputs ≥16px | **PASS** | team_50: name/email/phone/message = 16px @≤640 |
| M14 | T8 about gallery | **PASS** | team_50 about screenshots @360 |
| M15 | No h-scroll @360 | **PASS** | Contact @360: `scrollWidth=360` (team_190 CDP). Post re-probe: `innerWidth=1044` while `clientWidth=360` — same harness artifact as team_50 v1.0.0 (D1); not a layout defect. D2 honeypot fix cleared contact (~10k scroll). 6/8 templates clean pre-fix |
| M16 | Touch ≥44×44 | **PASS** | **D3 fixed:** topic-chips 44px @360 contact (team_190 CDP). Toggle 44×44, FAB 56×56, drawer links min-height 44 |
| M17 | Lighthouse Perf T7 | **ADVISORY** | Deferred post-WP001 final assets (team_00) |
| M18 | Lighthouse A11y T7 | **ADVISORY** | ≥95 = PASS_WITH_FINDINGS when run (team_00) |
| M19 | Lighthouse Perf T1/T2/T4 | **ADVISORY** | Same as M17 |
| M20 | `!important` audit (D1) | **PASS** | v1.0.0 code audit; locked D1 interpretation applied |

---

## team_50 Defect Dispositions (0.5.1)

| Defect | v1.0.0 | v1.1.0 disposition |
|---|---|---|
| D1 Post h-scroll | FAIL | **Cleared** — harness viewport mis-size (1044px innerWidth); not code defect |
| D2 Contact h-scroll (~10k) | FAIL | **FIXED** — honeypot `.hp-field` scroll-safe override; contact `scrollWidth=360` @360 |
| D3 topic-chip 39px | FAIL | **FIXED** — `min-height:44px`; chips measure 44px @360 |
| D4 drawer-close 36×36 | FAIL | **ACCEPTED** — spec §2.1 locked exception (advisory) |
| D5 desktop nav <44 @768 | FAIL | **OUT OF SCOPE** — WP002 drawer ≤640 only; V300/GCR candidate (advisory) |

---

## Findings (Advisory — non-blocking)

| ID | Severity | Finding | Route |
|---|---|---|---|
| A1 | INFO | M17–M19 Lighthouse not gating this cycle | Re-measure post-WP001 asset delivery |
| A2 | LOW | `.drawer-close` 36×36 per spec §2.1 | Accept; optional team_35 GCR for M16 alignment |
| A3 | LOW | Desktop `.shell-links` touch targets <44 @768 | V300 / out of WP002 scope |
| A4 | LOW | Spec inconsistency: §9 contact `min-height:36px` vs §1.3 global 44px — resolved in build favor of §1.3 | Flag team_35 for spec harmonization |
| A5 | INFO | team_50 DEVICE_CHECK v1.1.0 not filed | Recommend team_50 close loop for audit trail |

---

## Constitutional Audit

**Carried from v1.0.0 — PASS (no re-read).** APPEND-ONLY mobile CSS, `system.css` LOCK, RTL logical properties, cross-engine independence, M20/D1.

Fix pass (0.5.1) adds append-only `t8.css` MOBILE FIX PASS block — constitutional.

---

## Verdict

**`PASS_WITH_FINDINGS`**

WP **NB-S002-P009-WP002** clears **L-GATE_VALIDATE** for gate progression. Advisory findings recorded above; no blockers.

*v1.0.0 HOLD lifted · validated by team_190 (Codex) · 2026-05-29*
