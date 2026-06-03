# CLEAN BASELINE — pre final-team_35-package landing — team_100 — v1

**Date:** 2026-06-03 · **Author:** team_100 · **Purpose:** pinned known-good starting line so the final team_35 design package (full mockup implementation) lands on a clean, aligned table.

## Pinned state
- **Commit:** `55f57ea3` on `main` · **git: aligned** (`main...origin/main`, 0 ahead / 0 behind) · **working tree CLEAN**.
- **Theme:** `nimrod-bio-2026` **v0.7.19**.
- **validate_aos.sh:** 32 PASS / 16 SKIP / **0 FAIL**.

## Environments — alignment
| Env | State | Aligned? |
|---|---|---|
| **git / repo** | main @ 55f57ea3, tree clean, pushed to origin | ✅ |
| **dev (uPress)** `nimrod-bio-2026.s887.upress.link` | v0.7.19 live, HTTP 200 | ✅ **byte-parity 14/14** (all CSS+JS) repo==dev |
| **local Docker** `nimrod-bio-wp:8085` / `nimrod-bio-db:3309` | up 17h; theme from repo; **DB is an independent volume — NOT synced to dev** (galleries wired on dev only) | ⚠ data-layer divergent (see below) |

## Data-layer (durability captured)
- Live gallery/featured wiring captured to **`docs/data-seed/gallery_wiring_seed_2026-06-03.md`** (durable; was dev-DB-only).
- Wired & live on dev: project 49 (גינה, 7) · project 31 (חממה+שומית, 14) · service 24 (BCS, 17 incl. tiller/pak-bung). featured_media for all CPTs captured.

## Build/QA convention (unchanged)
Canonical = **build → FTPS deploy to dev → CDP/Lighthouse/axe QA on dev**. Local Docker is available but its DB is not the SSoT; if local-first testing is wanted for the incoming package, sync the dev DB first (`scripts/restore-production-from-backup.sh` workflow) — otherwise keep the dev pattern.

## Open items carried INTO the new package (non-blocking)
1. **Real SFA/tiktrack screenshots** — `assets/img/{sfa-demo,tiktrack-demo}.svg` are DEMO placeholders; hot-swap when owner delivers.
2. **Data-layer cutover durability** — dev-DB-only gallery meta; the seed above + the cutover plan (P005-WP002) must carry it to prod.
3. **WP005** (media wiring): scope DONE+live → awaiting formal closure decision (team_00 acceptance like WP004, or light validate).
4. **DRIFT_REGISTER A13** — non-rendered comment terms (`recursion`/`negentropy`) cleanup.

## Reference SSoT for the incoming work
- Reconciliation: `_COMMUNICATION/team_100/RECONCILIATION_TEAM35_PACKAGES_2026-06-03_v1.md`
- Current canonical mockup: `_INBOX_design_handoff_v200/.../Precision Mockup v4.html` (until the new package supersedes it)
- Design-precision walk gap (G2/G3): roadmap **NB-S002-P009-WP007 (PLANNED)**.

## Status
**READY for the final team_35 package.** Clean table, all environments aligned (dev↔repo↔git), data-layer captured, no SSoT drift. Awaiting team_00 to supply the package → then author the new WP (full mockup implementation).

*team_100 · clean baseline · 2026-06-03 · @55f57ea3 · v0.7.19*
