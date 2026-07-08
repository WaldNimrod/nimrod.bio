---
document_title: "VERDICT — NB-S002-P003-WP001 — T7 Home — Revalidation Cycle 1"
document_type: VALIDATION_VERDICT
document_date: 2026-05-25
team_id: team_190
phase_owner: team_190
project: nimrod-bio
milestone: V200
work_package: NB-S002-P003-WP001
gate: L-GATE_VALIDATE
correction_cycle: 1
builder: team_10
validator: team_190
builder_engine: Cursor
validator_engine: GPT-5.5
---

# VERDICT — NB-S002-P003-WP001 — T7 Home — Revalidation Cycle 1

## Scope

Revalidation is limited to acceptance row **H7** only, per team_00 instruction. WP002/WP003/WP004/WP005 were not revalidated.

## Verdict

**Overall scoped verdict:** PASS

**Route recommendation:** PASS -> `team_100` gate advancement.

**Cross-engine attestation:** Builder/remediator was `team_10` using Cursor. This revalidation was performed independently by this GPT-5.5 / `team_190` run using fresh remediation artifact reads, live dev HTTP probes, REST checks, and AOS validation. Builder evidence was not inherited as proof; it was replayed independently.

## Independent Evidence Summary

- FIRST ACTION satisfied: read `_COMMUNICATION/team_10/REMEDIATION_NB-S002-P003-WP001_v1.0.0.md` and `_COMMUNICATION/team_10/REMEDIATION_NB-S002-P004-WP001_v1.0.0.md` before the M11 media sweep.
- Home smoke probe: `GET http://nimrod-bio-2026.s887.upress.link/` returned HTML with exactly 4 `.post-card` anchors.
- Home card titles observed: `פטריות יער בגינה`; `מבוא לגידול הידרופוני`; `מדריך שתילה נכונה`; `מדריך ״שליפת״ שתילים`.
- Home HTML `Hello world!` check: absent.
- REST published posts total: `X-WP-Total: 22`.
- REST migrated post query with `_nb_seed=v200-migrated`: 22 posts.
- REST `slug=hello-world` published/default public query: `[]`.
- DB probe: `/Users/nimrod/Documents/agents-os/_aos/db_connectivity_status.json` reports `status: online`.
- AOS validation: `bash _aos/lean-kit/modules/validation-quality/scripts/validate_aos.sh .` returned `32 PASS / 16 SKIP / 0 FAIL`; no net-new FAIL observed.
- Constitutional package linter: `scripts/lint_constitutional_package.py` was absent in this repo, so no package-linter run was available.

## Findings

| id | severity | evidence-by-path | route_recommendation |
|---|---|---|---|
| T190-WP001-F1-C1 | PASS | Live dev home `http://nimrod-bio-2026.s887.upress.link/`: `.post-card` count is 4; titles are migrated Hebrew content; `Hello world!` absent. Prior failing row H7 is resolved. | PASS -> `team_100` gate advancement. |

## Acceptance Matrix

| row | result | independent evidence |
|---|---|---|
| H7 four recent migrated posts after migration | PASS | `GET /` on dev returned exactly 4 `.post-card` anchors with migrated Hebrew titles (`פטריות יער בגינה`, `מבוא לגידול הידרופוני`, `מדריך שתילה נכונה`, `מדריך ״שליפת״ שתילים`) and no `Hello world!`. REST published total is 22 and the migrated `_nb_seed=v200-migrated` query also returns 22 posts. |

## Final Route

Scoped H7 revalidation passes. Route `NB-S002-P003-WP001` back to `team_100` for gate advancement/closure update.
