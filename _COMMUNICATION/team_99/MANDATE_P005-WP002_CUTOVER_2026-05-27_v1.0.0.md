---
id: MANDATE_P005-WP002_CUTOVER
type: CUTOVER_MANDATE
from: team_110 (Domain Architect · cursor-composer)
to: team_99 (Home Server Team · claude-code · waldhomeserver)
cc: team_00 (Principal — final go-no-go), team_190, team_50
project: nimrod-bio
milestone: V200
wp: NB-S002-P005-WP002 (PRODUCTION CUTOVER)
date: 2026-05-27
priority: P0 (final V200 step)
status: SUPERSEDED by v1.1.0
predecessor: NB-S002-P006-WP002 (PR #1 merged c150b9cb) + COMPLETION_CONTENT_PHASE signature
authorization_chain:
  - team_00 directive 2026-05-26 (content phase + cutover sequencing)
  - team_00 DECISION 2026-05-27 (Q1=A closeout option approved)
  - team_110 COMPLETION_CONTENT_PHASE_2026-05-27_v1.0.0.md (gate unfrozen)
  - team_190 PASS_WITH_FINDINGS × 2 + team_50 PASS_WITH_FINDINGS (validation chain complete)
lod400_ref: _aos/work_packages/NB-S002-P005-WP002/LOD400_NB-S002-P005-WP002.md
---

# MANDATE — P005-WP002 — Production cutover to nimrod.bio

## 1. Authority

team_110 unfreezes P005-WP002 per signed COMPLETION_CONTENT_PHASE. team_00 issues final go-no-go on D-day before team_99 executes the cutover sequence per the LOD400 runbook.

team_99 must NOT execute cutover steps until receiving explicit "go" from team_00 in chat or via `_COMMUNICATION/team_99/GO_SIGNAL_*.md` artifact.

## 2. Pre-conditions verified by team_110 (COMPLETION §7)

All 8 cutover pre-conditions met. See `_COMMUNICATION/team_110/COMPLETION_CONTENT_PHASE_2026-05-27_v1.0.0.md` §7.

## 3. Reference artifacts (read in order)

1. `_aos/work_packages/NB-S002-P005-WP002/LOD400_NB-S002-P005-WP002.md` — THE RUNBOOK
2. `_COMMUNICATION/team_110/COMPLETION_CONTENT_PHASE_2026-05-27_v1.0.0.md` — closeout context
3. `_COMMUNICATION/team_00/DECISION_COMPLETION_CONTENT_PHASE_2026-05-27_v1.md` — closeout decision
4. `docs/CUTOVER_READINESS_REPORT_2026-05-25.md` — prior P005-WP001 readiness
5. `_aos/governance/team_99.md` — your own governance (OPS authority on waldhomeserver)

## 4. Mode

OPS track (per ADR044 §1 — your canonical track). No code changes anticipated; if any required during cutover (e.g., post-cutover hotfix), ISOLATED_BRANCH + team_190 lightweight validate before merge.

## 5. Cutover sequence (high-level — full detail in LOD400)

| Step | Action | Authority | Rollback |
|---|---|---|---|
| D-1 | DNS TTL → 300s (if currently higher) | team_99 | DNS revert |
| D-1 | uPress backup snapshot | team_99 (uPress UI) | uPress restore |
| D-1 | `wget --mirror` of old Flatsome site → static archive | team_99 | n/a (just files) |
| **D-0 GO** | team_00 chat: "GO cutover" | team_00 | — |
| D-0 | uPress domain remap: dev URL ↔ primary domain | team_99 (uPress UI) | uPress UI swap back |
| D-0 | Deploy `/archive/` snapshot to new install | team_99 | remove dir |
| D-0 | Post-cutover smoke: `scripts/cutover/post_cutover_smoke.py` | team_99 | rollback |
| D-0 | Visual ack | team_00 (browser) | — |
| D+0 | Search Console: submit new sitemaps | team_99 | re-submit |
| D+1..D+7 | Monitor (404 logs, traffic, search rank) | team_99 + team_00 | — |

## 6. Rollback plan

uPress backup restore + DNS revert. Per LOD400 §3 strategy. team_99 owns rollback execution; team_00 owns rollback decision authority.

## 7. STOP conditions — escalate to team_00

- Post-cutover smoke fails on any of: home / blog index / 5 random posts / contact form
- 5xx rate on prod > 1% for 5 consecutive minutes
- Any of 23 × 301 / 6 × 410 redirects return 404 or 5xx
- Mail (SMTP) round-trip fails post-cutover
- Yoast indexer error blocking sitemap submission

## 8. Findings carried into cutover (from COMPLETION §5.2)

- Q50-F-001 Lighthouse regression — accept, V300 (post-cutover Lighthouse retest recommended on prod for data-driven V300 priority)
- Q50-F-002 / F-002 media sitemap absent on dev — verify on prod post-cutover; Yoast may auto-resolve with CDN warm
- Q50-F-003 duplicate submit on contact — V300 polish
- mu-plugin rename — V300 hygiene

## 9. Out of scope for team_99 in this mandate

- Content fills in placeholder posts (team_00 in WP admin)
- V300 Lighthouse uplift / mobile / watercolor / logo work
- Code changes to nimrod-bio theme (LOCKED by team_35)
- AOS hub DB writes (out of team_99 scope per IR#13)

## 10. Deliverable

`_COMMUNICATION/team_110/COMPLETION_NB-S002-P005-WP002_<date>_v1.0.0.md` — full cutover log + smoke test results + screenshots + final state attestation.

## 11. Estimated effort

- D-1: ~1 hour (DNS + backup + archive mirror)
- D-0: ~30 min cutover + ~30 min smoke + monitor
- Total wall-time: ~1 working day + 2 days passive monitoring

— team_110 (cursor-composer) — 2026-05-27 (parked; activates on team_00 GO)
