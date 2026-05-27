---
type: FOLLOW_UP_TASK (infrastructure)
from: team_110 (Domain Architect)
to: team_00 (Principal) → team_100 (Chief Architect) + team_60 (Infra) + team_99 (Home Server)
project: nimrod-bio
date: 2026-05-27
priority: P3 (not blocking V200; blocks proper canonical DB audit)
estimated_effort: ~30 minutes (admin + sync + verify)
---

# Follow-up — AOS domain DB provisioning for nimrod-bio

## Symptoms

When team_110 attempted to register `NB-S002-P007-WP001..004` in canonical DB:

1. `GET /api/projects/nimrod-bio` → 200 (project IS in projects.yaml)
2. `POST /api/projects` (re-register) → 409 CONFLICT "already registered in _aos/projects.yaml"
3. `GET /api/health/domains` → lists nimrod-bio with `domain_id=nimrod-bio slug=None status=FAIL`
4. `POST /api/work-packages` with `domain_id: "nimrod-bio"` → 404 DOMAIN_NOT_FOUND (needs ULID, not slug)
5. `POST /api/governance/sync` scope=full → 503 `script_not_found: /data/projects/agents-os/lean-kit/modules/project-governance/scripts/aos_sync_all.sh`

## Diagnosis

The hub API has two domain registries:
- **projects.yaml registry** — nimrod-bio is here (entry created 2026-05-09)
- **DB domain registry (ULIDs)** — nimrod-bio is NOT here (only `agents_os` and `tiktrack` have ULIDs)

The bridge mechanism (`aos_sync_all.sh` invoked via `POST /api/governance/sync`) is broken — the script file is missing on the server. This blocks new spokes from getting DB ULIDs.

Even team_100 (with proper key authority) cannot register WPs for nimrod-bio because the `domain_id` validator requires ULID lookup against the DB, and the DB has no ULID for nimrod-bio.

## Impact

- Cannot register canonical WP rows in DB for nimrod-bio
- Hub-side audit trail (gate_history, lifecycle tracking) is incomplete
- V200 work is filesystem-only canonical (artifact-based per ADR034 R8 spirit)
- Does NOT affect: WP execution, validation, content delivery, cutover

## Recommended fix sequence (out-of-band, post-V200 cutover)

1. **team_60 (Infra) or team_99 (OPS):** locate or restore `aos_sync_all.sh` script on waldhomeserver at `/data/projects/agents-os/lean-kit/modules/project-governance/scripts/aos_sync_all.sh`. Check git history of agents-os repo for the canonical version.

2. **team_100 (Chief Architect):** after script is back, invoke `POST /api/governance/sync` with `scope=full`. This should provision missing domain ULIDs for nimrod-bio (and any other L0 spokes in same boat).

3. **team_100:** retroactively register completed V200 WPs (NB-S002-P006-WP001, NB-S002-P006-WP002) and the 4 P007 WPs (NB-S002-P007-WP001..004) in the DB once domain has a ULID.

4. **team_191 (Git/Files):** ensure `_aos/projects.yaml` and DB registry stay in sync going forward (this incident is the second time the bridge has been observed broken — first was the P006 registration attempt earlier in 2026-05-26).

## V200 / P007 workaround

Operating artifact-based per ADR034 R8 (offline workflow pattern):
- WP IDs defined in MANDATEs without DB registration
- `wp_registration_status: PENDING_DB_DOMAIN_PROVISIONING` in each MANDATE frontmatter
- Roadmap tracking via filesystem `_aos/work_packages/NB-S002-P007-WPNNN/` directories (team_191 / team_100 to bootstrap when authority allows)

— team_110 — 2026-05-27
