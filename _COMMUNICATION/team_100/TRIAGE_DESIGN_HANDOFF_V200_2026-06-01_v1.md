# TRIAGE — team_35 Design Handoff (UI Precision v4) — team_100 — v1

**Date:** 2026-06-01
**Author:** team_100
**To:** team_35 · team_00 (visibility)
**Type:** TRIAGE / ACCEPTANCE
**Source:** `_COMMUNICATION/team_100/_INBOX_design_handoff_v200/design_handoff_ui_precision_v200/` (README + UI_DESIGN_REVIEW + Precision Mockup v4.html + assets)
**Response to:** `MANDATE_TEAM_35_UI_TEMPLATES_2026-06-01_v1.md`

## Decision: ACCEPT — implement in staged sequence
team_35 delivered a high-fidelity design review + handoff (not a deploy). It is locks-aware, aligns with the mandate, folds in QA findings (Contact hero, 404/search/empty-states, media degradation, counts, external links, dead code), and correctly routes implementation to Claude Code, dev-only, on top of `7526893b`. Package extracted with `ditto` (quirk #1) — 39 files, no filename corruption.

## Owner ruling on facts (team_00, 2026-06-01)
- **Greenhouse = 420 מ״ר — CONFIRMED** (supersedes earlier "unverified/keep-generic"). Surface in hero kicker + world lattice + About timeline as designed.
- **9 עונות** — consistent with 2014–2023 garden. OK.
- **one restaurant today** (reconcile stray "מסעדות 12") — OK, matches locked fact.

## Implementation plan (staged, check-in between stages)
| Stage | README tasks | Content |
|-------|-------------|---------|
| **A** (now) | 1 + 7 | Lock + fact corrections (incl. 420 מ״ר); rename `.vc-cdip`→`.vc-principle`; dead-code removal (`nb_render_cdip_diagram`, orphaned `t8-media-item.php`) |
| B | 2 + 3 | System templates (404 / search / empty-archive); media degradation (`.ph.clean`, dev-only TBD) |
| C | 4 + 5 | Contact hero; About timeline + "קצת ים" (verify against current) |
| D | 6 + precision | Counts (0/1/many wording) + external links (CPT meta); token hygiene + RTL/375 sweep |

Each stage: sub-agent implements per the mockup → deploy via FTPS → team_100 independent verify (cache-busted + lock-scan) → report to team_00 before next stage.

## Locks (every stage)
Micha · demonstrate-never-name (CDIP/cross-domain/אנטרופיה/נגנטרופיה/רקורסיה/פרמקלצר/3×/אינסטנסים/קואופרטיב/קומון) · no marketing clichés. Lock-scan every diff incl. alt/aria/internal class names.

*team_100 | triage + acceptance | 2026-06-01 | design handoff accepted, staged implementation*
