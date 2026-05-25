---
type: DECISION
from: team_00 (Nimrod, Principal)
to: team_100 (nimrod-bio, Chief Architect)
project: nimrod-bio
milestone: V200
date: 2026-05-25
version: v1.0.0
status: CLOSED
mechanism: AOS_decide brief (file-based, no API)
brief_ref: in-session presentation 2026-05-25 by team_100
---

# DECISION — V200 Site Rebuild · 5 open questions closed

## Context

LOD300 milestone draft (`_aos/work_packages/S002/LOD300_V200_milestone.md` §9) listed 5 open questions blocking the first MANDATEs. team_100 presented a Decision Brief per AOS_decide skill on 2026-05-25; team_00 responded with the following directives.

## Decisions

### Q1 — Actor key for team_35 spoke activation
**Decision:** **WITHDRAWN from scope.**
team_35 governance (universal team status, actor keys, propagation) is meta-plumbing and not relevant to product build sessions. team_100 to consume design package as-is and not raise governance questions about producer teams unless they actively block the build. Saved as feedback memory `feedback_scope_discipline.md`.

### Q2 — Cutover timing
**Decision:** **A — Event-driven, but fast.** No calendar deadline. Cutover gated on team_00 sign-off that the new site is 100% ready. team_100 to optimize WP parallelism in P003 to minimize wall-clock without forcing artificial deadline pressure on QA.

### Q3 — Old site preservation
**Decision:** **C — Static `/archive/` snapshot.** The old WordPress instance is to be backed up and turned off after cutover. team_110 will produce an HTML mirror (`wget --mirror`) into `nimrod.bio/archive/` on the new site as transitional backup. No `legacy.nimrod.bio` subdomain. No link discovery from the live site.

### Q4 — Triage method
**Decision:** **B — HTML triage UI (used).** Tool delivered at `docs/url_migration_triage.html`. team_00 filled it and returned JSON 2026-05-25. Final stats:
- **keep:** 2 (root-level pages `/shook/`, `/blog/`)
- **redirect:** 23 (1 page → `/about/heritage/`; 22 posts → `/blog/<slug>/`)
- **drop:** 6 (3 obsolete grow pages, smallfarmsagent page, 2 short-lived partner posts)
- **TBD:** none — `video1` resolved to `/blog/יום-בגינה/` per team_00.

Final decisions: `docs/url_migration_decisions_2026-05-25.json`.

### Q5 — CPT field UI plugin
**Decision:** **D — Native CPT + custom meta boxes (no paid plugin).**
- Driver: most content publishing will be agent-driven via REST API; admin UI polish has low ROI.
- No paid plugin, no annual licence, no `ACF Pro` / `Meta Box Pro` dependency.
- Free plugin alternatives (Pods, ACF Free) explicitly rejected to avoid plugin lock-in for what amounts to ~1.5 days of theme code (15 fields × 2 CPTs).
- Spec translation cost is absorbed by team_100 in LOD400 of NB-S002-P002-WP002.

## URL prefix policy (closed as side-effect of Q4)

**Decision:** **All posts on the new site live under `/blog/<slug>/`.** Old site's root-level post URLs (`/<slug>/`) become 301 redirects to the new pattern. This is the only post-URL policy across V200. Pages remain at root (`/<slug>/`). Subpaths like `/about/heritage/` and `/services/{slug}/`, `/project/{slug}/` follow design package §6.

## Impact on LOD300

- §3 (Stack decisions): replace `ACF Pro` row with `Native CPT + custom meta boxes`.
- §9 (Open questions): replace with this DECISION artifact reference.
- §4 (Work package roster): no changes — D adds ~1.5 days to NB-S002-P002-WP002 but does not introduce or remove WPs.

## Authorization to proceed

team_100 is authorized to:
1. Update LOD300 with this DECISION.
2. Register V200 work packages in `_aos/roadmap.yaml`.
3. Issue `MANDATE_NB-S002-P001-WP001` to team_10 (Builder) for dev environment preparation.

— team_00 (Nimrod) — closed 2026-05-25
