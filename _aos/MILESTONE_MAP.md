# Milestone Map — nimrod.bio — Personal Site (WordPress)
<!-- Populated by Team 100 at LOD200. Update as milestones are defined. -->

| Milestone | Description | Target | Status |
|-----------|-------------|--------|--------|
| V100 | AOS Init + Bootstrap: project scaffold + local dev environment mirroring uPress production | 2026-05-09 | ACTIVE |

<!-- Add rows per milestone: V200, V300, etc. (theme, design, content) -->
<!-- Status values: PLANNED | ACTIVE | COMPLETE | DEFERRED -->

## V100 — AOS Init + Environment Bootstrap

### WPs
| WP ID | Label | Track | Status |
|-------|-------|-------|--------|
| NB-S001-P001-WP001 | AOS Init + Port Registration | OPS/Express | IN_PROGRESS |
| NB-S001-P002-WP001 | Environment Bootstrap (Docker + uPress backup restore) | STANDARD/A | PLANNED |

### Gate
V100 is complete when:
1. NB-S001-P001-WP001 reaches L-GATE_BUILD (OPS Express — no VALIDATE gate)
2. NB-S001-P002-WP001 reaches L-GATE_VALIDATE with Team 190 sign-off
3. validate_aos.sh: 0 FAIL on nimrod-bio
4. http://localhost:8085 serves Hebrew WordPress from production snapshot
