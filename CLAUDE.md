## AOS Spoke Notice — AOS Identity: nimrod.bio — Personal Site (WordPress) L0 Spoke

<!-- AOS canonical invariants (ADR040 / Iron Rule #12):
     - DB probe: check hub _aos/db_connectivity_status.json (probe_database)
       before any structured mutations. ADR034 / Iron Rule #7.
     - gov-update / gov-sync: Team 00 / Team 100 ONLY. ADR040 / Iron Rule #12.
-->

# CLAUDE.md — nimrod.bio — Personal Site (WordPress)

## Identity
**Repo:** nimrod-bio
**Profile:** L0 (Lean/Manual)
**Hub:** /Users/nimrod/Documents/agents-os

## What this repo is
nimrod.bio — אתר אישי/פורטפוליו בוורדפרס, מאוחסן על uPress.
פרויקט זה מנהל את התבנית, העיצוב והתוכן של האתר.
האתר בעברית. השרת מארח גם פרויקטים אחרים בסאבדומיינים — פרויקט זה עוסק ב-WordPress root בלבד.

## Mandatory session startup
1. Read `_aos/roadmap.yaml` — active WPs and current gate position
2. Read `_aos/context/PROJECT_CONTEXT.md` — domain background and team entry
3. Read `_aos/project_identity.yaml` — boundary declarations and allowed write roots
4. Read governance contract for your team: `_aos/governance/team_[ID].md`

## Team model
Universal numbering (Iron Rule #9). See `_aos/definition.yaml`.
Active team assignments: `_aos/team_assignments.yaml`.

## Directory Authority

| Team | May write to |
|------|-------------|
| Team 00 (Principal) | Anywhere |
| Team 100 (Architect) | `_COMMUNICATION/team_100/`, `_aos/roadmap.yaml`, `_aos/work_packages/` |
| Team 191 (Git/Files) | `_COMMUNICATION/team_191/`, `_archive/`, `_aos/` (bootstrap/propagation, under mandate) |
| ALL OTHER TEAMS | `_COMMUNICATION/team_[ID]/` and application source ONLY |

`_aos/` is the governance layer — **OFF LIMITS for all non-governance teams**.
Non-AOS teams route required roadmap/gate updates via report artifact to Team 100.
Canonical authority table: `methodology/AOS_DIRECTORY_CANON_v1.0.0.md` Part 5 (see hub).

## §BOUNDARY — Cross-Project Isolation
- **This repository is:** nimrod.bio — Personal Site (WordPress) (L0 spoke)
- **Cross-project handoff:** `~/Documents/_agent_comm/outbox/` or route to Team 10
- NEVER create files that belong to another project in this repo
- `_aos/project_identity.yaml` is the machine-readable boundary SSoT

## AOS Hub reference
Hub: /Users/nimrod/Documents/agents-os
Methodology: hub `methodology/` directory
Canon: hub `methodology/AOS_DIRECTORY_CANON_v1.0.0.md`
Lean Kit: `_aos/lean-kit/` (physical snapshot — read-only)

---
*Template: lean-kit/modules/project-governance/config_templates/CLAUDE.md.template*
*Instantiated: 2026-05-09 | Profile: L0*
