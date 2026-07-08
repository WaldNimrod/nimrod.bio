---
id: SWEEP_REPORT_nimrod-bio_2026-07-09_v1.0.0
type: DOMAIN_DOC_ARCHIVE_SWEEP_REPORT
from: team_120@nimrod-bio
to: team_120
date: 2026-07-09
procedure: DOMAIN_DOC_ARCHIVE_SWEEP_PROCEDURE_v1.0.0
run_status: COMPLETE
---

## Preconditions (§2)
- P-1 CWD: `/Users/nimrod/Documents/AOS_V5/nimrod-bio-sweep` (isolated worktree, confirmed — not the primary checkout).
- P-2 branch/sha: detached HEAD @ `649c80c52ada7039d5bbe9dcf6cf1a765e758142` (tip of `main`). Recorded, not switched.
- P-3 concurrency isolation: satisfied — dedicated git worktree created for this sweep.
- P-4 version marker present: yes (`_aos/roadmap.yaml` `lean_kit_version: "3.3.0+163b156"`, `_aos/AOS_GOVERNANCE_VERSION.yaml` present, synced 2026-07-08 from hub `main`@`65fa386`).
- P-5 `git status --porcelain` at start: clean (no foreign edits).
- **No hard STOP condition present.** Proceeded to Phase 0.

## Baseline (Phase 0)
domain_id: nimrod-bio | branch: main (detached HEAD in worktree) | sha: 649c80c | domain_version_flag: **V5**
version_markers: { governance: "hub_sha 65fa386ca384, main, synced 2026-07-08T15:23:47Z, team_100", lean_kit: "3.3.0+163b156 (matches current hub canonical Lean Kit 3.3.0 — no `LEAN_KIT_VERSION.md` file present in this spoke, version read from `roadmap.yaml`)", active_milestone: "V200 (IN_PROGRESS; V100 COMPLETE 2026-05-11)" }
before: { unarchived_comm_files: 1119, open_wps: 19, archive_dirs: 4 }

**Note on domain_version_flag:** the procedure's literal text defines v5-CURRENT as "version marker = 5.x". This
domain's `lean_kit_version` is `3.3.0` — but that is the hub's own **current canonical** Lean Kit release train
(hub `CLAUDE.md`: "Lean Kit 3.3.0"; the AOS *platform generation* name "v5" and the *lean-kit content package*
semver "3.x" are two independent version tracks in this codebase, confirmed against the freshly-synced
`AOS_GOVERNANCE_VERSION.yaml` anchor, 1 day old). No `AOS-V4*`/`agents_os_v3`-owned WP or config was found (see V-1
below) — flagged **V5**, not `V4_OR_MISSING`.

## Phase 1 — documentation
D-1 DOC_CANON: **N/A-spoke** (no `core/` directory in this domain; `doc_canon` module not applicable)
D-2 as-built: **gaps=2** → ESCALATE (see list) — `NB-S001-P001-WP001` (`spec_ref: TBD`, no as-built artifact ever produced; OPS/Express track) and `NB-S002-P009-WP004` (`spec_ref` points to a path, `sources/team_35_design_package/design_handoff_home/assets/ASSETS_README.md`, that does not exist anywhere in this worktree — broken reference, pre-existing, not introduced by this sweep)
D-3 indexes: **PASS** — one `INDEX.md` found (`sources/tiktrack/INDEX.md`, a content-source index, not an AOS module index); all 3 files it references exist, no dangling refs, fixed=0
D-4 CS-cite: **advisory=N/A** — `validate_aos.sh` (which runs Check 26) is not present in this worktree's `_aos/lean-kit/` (see V-3)

## Phase 2 — classification counts
**ARCHIVE: 21** (WPs) · **KEEP(v5): 2 WPs + 301 loose files** (299 within `_COMMUNICATION/team_*/` scope + `_log/messages.log`, out-of-scope structural log, + roadmap-level KEEP is 2 WPs) · **QUARANTINE-V4: 0** · **ESCALATE: 49 loose files across 13 report rows + 2 WP-level doc-gaps (counted in D-2 above, not double-counted here)**

All 23 `roadmap.yaml` work packages classified:
- 21 × `status: COMPLETE` → **ARCHIVE** (Phase 3)
- `NB-S002-P005-WP002` (`status: PLANNED`, `milestone_ref: V200` = the domain's **active**, still-open milestone) → **KEEP** (v5-current, OPEN; not STALE-MILESTONE-WP because V200 is not closed)
- `NB-S002-P006-PROGRAM` (`status: IN_PROGRESS`, `milestone_ref: V200`) → **KEEP** (v5-current, OPEN)

Loose `_COMMUNICATION/team_*/` files not yet archived at baseline (1119, per the procedure's literal `find
_COMMUNICATION/team_* -type f -not -path '*/_archive/*'`; there is a 1-file discrepancy against a broader
`find _COMMUNICATION -type f` scan — that extra file is `_COMMUNICATION/_log/messages.log`, which lives outside
the `team_*` glob and is the append-only mail-bus fallback log; reviewed and KEEP, not part of any count below):

| Classification | Count | Basis |
|---|---|---|
| ARCHIVE (matched to one of the 21 COMPLETE WP-IDs by filename, or self-declared via a manifest's own `**WP:**` header) | 771 | filename/manifest-header substring match |
| KEEP — path/filename carries the active-milestone token `V200` (or `V100`, the prior closed-but-not-stale milestone) | 129 | mechanical token match against `roadmap.yaml` `active_milestone` |
| KEEP — dated on/after the current milestone's open date (2026-05-25), no WP-ID, no closed-milestone tie | 162 | mechanical date match |
| KEEP — standing team infrastructure (not WP-scoped): `.gitkeep`×2, `ACTIVATION_*.md`×4, `owner_review_intake/{README,WAVE_REVIEW_TEMPLATE.json}`×2 | 8 | structural/standing, reused across WPs |
| ESCALATE — WP-ID-shaped filename token **not present in `roadmap.yaml`** (unregistered WP cluster, 10 distinct IDs) | 38 | cannot read status from SSoT — no row exists |
| ESCALATE — undated, untagged versioned reference pack (`COPY_CONTEXT_PACK_v1.0/*`, 9 files) | 9 | no date/WP/milestone signal, cannot determine currency |
| ESCALATE — pre-milestone-dated loose note, no WP-ID (`team_200/AOS_COWORK_CONTEXT_v1.0.0.md`, dated 2026-05-02) | 1 | dated before milestone open; carries a version tag so doesn't cleanly match the table's explicit no-version-tag row either — catch-all |
| **Total (reconciles to baseline)** | **1119** | 771 + 129 + 162 + 8 + 38 + 9 + 1 = 1119 ✓ |

## Phase 3 — archive
archived_wps: [ NB-S001-P001-WP001 (0 artifacts — manifest-only, documents the empty state), NB-S001-P002-WP001, NB-S002-P001-WP001, NB-S002-P002-WP001, NB-S002-P002-WP002, NB-S002-P003-WP001, NB-S002-P003-WP002, NB-S002-P003-WP003, NB-S002-P003-WP004, NB-S002-P003-WP005, NB-S002-P004-WP001, NB-S002-P004-WP002, NB-S002-P005-WP001, NB-S002-P005-WP001B, NB-S002-P009-WP001, NB-S002-P009-WP002 (completion pass), NB-S002-P009-WP003 (completion pass), NB-S002-P009-WP004 (completion pass), NB-S002-P009-WP005, NB-S002-P009-WP006, NB-S002-P009-WP007 ]
manifests_written: 21 (18 new + 1 zero-file + 3 completion-pass appends to pre-existing manifests)
archive_failures(->ESCALATE): none — every git mv succeeded (0 deletions in `git status`, 771 renames)

**Major finding — prior incomplete archival (P009-WP002/WP003/WP004).** These 3 WPs already had
`_archive/<WP-ID>/ARCHIVE_MANIFEST.md` from an earlier session (2026-05-29/30, team_100, ADR042 closure), but the
manifests only *referenced* source artifacts at their original `_COMMUNICATION/team_*/` paths — the files were
**never physically moved**, and no `## Path redirects` table existed (a direct miss against
`POST_GATE_ARCHIVE_PROCEDURE.md`'s mandatory M.2 + this sweep's own §6 success criterion). This accounted for
**648 of the 771 files moved in this pass** (586 for WP002, 61 for WP003, 1 for WP004 — the bulk of it
`team_50/screenshots/<WP-ID>/*.png` device-check evidence that was sitting live in `_COMMUNICATION/` for 6+ weeks).
Per Phase 2's own enumeration (`find _COMMUNICATION/team_* -type f -not -path '*/_archive/*'`), these files were
still "not yet archived" — i.e. not shielded by the idempotency skip-rule (INV-4 / the "already lives under
`_archive/`" classification row), because the *files themselves*, not just the WP, had never moved. This sweep
treated that as a mechanical **ARCHIVE** completion (status: COMPLETE is unambiguous) rather than an ESCALATE,
appended a `## Path redirects` table to each of the 3 existing manifests (additive, INV-3 — original manifest
content untouched), and moved the remaining files. **Flagging this prominently for team_120 R6 (systemic
finding):** if this "manifest written, files never moved" pattern recurs on other domains, it is a hygiene-gap
signature the fleet audit should check for explicitly (grep for `_archive/*/ARCHIVE_MANIFEST.md` that has no
`## Path redirects` section).

**Also folded in:** `_COMMUNICATION/team_50/_OUTBOX_live_artifacts_part1/` (38 files — screenshots, JSON probes,
a `MANIFEST.md`) carried no WP-ID in any filename, but its own `MANIFEST.md` self-declares `**WP:** NB-S002-P009-WP001`
in its header — a structured, mechanically-readable field (not prose interpretation) — so this directory was
archived into `NB-S002-P009-WP001`'s batch (bringing that WP's total from 7 to 45 files).

**roadmap.yaml M.1 (reference integrity):** `spec_ref` for `NB-S002-P001-WP001` pointed into a file that this
sweep moved (`_COMMUNICATION/team_10/MANDATE_...md`) — updated in place to the new `_archive/NB-S002-P001-WP001/...`
path (verified: `spec_ref` now resolves). All other COMPLETE WPs' `spec_ref` either already pointed at
`_aos/work_packages/<WP-ID>/LOD400_....md` (which this domain's established convention leaves in place during
archival — confirmed against the pre-existing P009-WP002/WP003 manifests, which reference LOD400 at its original,
un-moved location) or at a shared, still-KEEP register file that was not archived. **Not performed:** rewriting
free-text `notes:` fields that embed now-relocated `_COMMUNICATION/...` paths (M.1.3) — there are 15+ such notes
across the 21 archived WPs' `gate_history`. Per M.4, a checker MUST treat these as satisfied via the
`ARCHIVE_MANIFEST.md` Path-redirects table rather than as drift, so this was intentionally left as prose
(INV-3 — additive only, no rewriting of existing narrative text) rather than hand-editing ~40 YAML string values
with attendant corruption risk. No `verdict_path`/`report_path`/`verdict_ref` structured fields exist in this
domain's `roadmap.yaml` schema (grep confirmed) — M.1.2 is N/A.

## Phase 4 — quarantine
quarantined: **none** · index_file: `_aos/V4_QUARANTINE_INDEX.md` — **not created** (0 rows; no v4-legacy OPEN
artifact found — see V-1)

## Phase 5 — anti-drift verification
- **V-1 residual_v4_active_hits: 0.** Raw grep for `AOS-V4|AOS_V4|agents_os_v3|AOS-V4.5-WP` outside `_archive/`
  returned 12 file hits; every one reviewed individually and is a RULE-A exemption (prose/provenance mention, not
  an owning WP-ID): 4 are hub-propagated tooling (`.claude/commands/AOS_{mail,handoff,SendMail,session}.md`,
  `scripts/aos_session_ctl.sh`, `scripts/hooks/pre-push_validation.sh`) citing the **hub** WP that built the tool
  in a provenance comment (identical pattern to the procedure's own worked example: `pytest.ini` citing
  `AOS-V4.5-WP-CI-LOCAL-MINIMAL` → KEEP); the remaining hits are a standard AOS DB-probe command snippet
  (`from agents_os_v3.modules.management.db import probe_database`, this spoke's local package alias, not a v4
  WP) or historical/closed milestone citations (`AOS-V4-MS001 complete`, `AOS-V4-WP-CHARTER` in a governance log
  entry) in `_COMMUNICATION/team_{100,110,190,200}` and `_aos/README.md`. None are OPEN artifacts owned by a
  v4-legacy WP-ID belonging to this domain. **0 QUARANTINE actions needed.**
- **V-2 markers_v5: yes** (see domain_version_flag note in Phase 0).
- **V-3 validate_aos: N/A — script unavailable in this worktree.** `_aos/lean-kit/` does not exist under this
  worktree at all (no lean-kit governance cache present here); `validate_aos.sh` could not be located or run.
  Not treated as a hard STOP per this task's explicit instruction (governance cache may be legitimately stale/absent
  in an isolated worktree; this is not one of the procedure's hard-STOP preconditions).
- **V-4 doc_canon: N/A** (spoke, no `core/`).

## ESCALATE (team_120 decides — DO NOT guess)

**Cluster A — Unregistered WP-IDs (10 distinct WP-IDs, 39 files).** These carry a well-formed `NB-S###-P###-WP###`
token in their filenames, with COMPLETION / PASS-VERDICT evidence, but **none of the 10 IDs exist anywhere in
`_aos/roadmap.yaml`** — the file-SSoT for this spoke (RULE-C/ADR034 R9). Per `roadmap.yaml`'s own header
("REGISTRATION RULE: WP MUST be in roadmap.yaml no later than L-GATE_SPEC"), these are registration gaps, not
determinable-status artifacts — cannot read the deciding `status` field because no row exists. Left untouched.
team_120 to determine: backfill roadmap.yaml (if genuinely closed) then re-run Phase 3 for them, or rule them
KEEP/other.
- `NB-S001-P003-WP001` (2 files: `_COMMUNICATION/team_10/{COMPLETION,MANDATE}_NB-S001-P003-WP001*.md`)
- `NB-S002-P006-WP001` (4 files, `_COMMUNICATION/team_190/VERDICT_NB-S002-P006-WP001_L-GATE_VALIDATE_v1.0.0.md` +
  `team_110/{LOD400_DRAFT,COMPLETION_...2026-05-26_v1.0.0,COMPLETION_...v1.1.0}.md`; cross-ref:
  `team_110/p006_wp001_post_creates_result.json` also names this WP but lacks the `NB-S002-` prefix, folded in here)
- `NB-S002-P006-WP002` (7 files: `team_190/{MANDATE,VALIDATE_REQUEST,VERDICT}_...L-GATE_VALIDATE*.md`,
  `team_10/MANDATE_..._MEDIA_MIGRATION...md`, `team_110/{ACCEPTANCE_F-001,LOD400_DRAFT,COMPLETION}_...md`)
- `NB-S002-P007-WP001` (3 files, team_50 MCP QA mandate/report/completion)
- `NB-S002-P007-WP002` (4 files, team_00 inventory docs + team_110 mandate)
- `NB-S002-P007-WP003` (7 files, team_10 content-fill mandates + team_110 batch-A/B/C/D completions)
- `NB-S002-P007-WP004` (5 files, team_190 verdicts v1.0.0/v2.0.0 + team_50 final-validation mandate/QA)
- `NB-S002-P008-WP001` (2 files, team_10 mandate + team_110 completion)
- `NB-S002-P008-WP002` (1 file, team_110 completion — SEO)
- `NB-S002-P008-WP003` (3 files, team_110 completions — about/media-migration/images)

**Cluster B — undated reference pack.** `_COMMUNICATION/team_100/COPY_CONTEXT_PACK_v1.0/` (9 files:
`000_INDEX.md`, `01_system_overview.md` … `08_copy_worksheet.md`) — a versioned ("v1.0") copy/content context
pack with no date and no WP-ID tie. Cannot mechanically determine whether it is the still-current reference used
for ongoing V200 copywriting or a superseded snapshot. Left untouched (KEEP-in-place, no archive/quarantine
action taken).

**Cluster C — single ambiguous pre-milestone file.** `_COMMUNICATION/team_200/AOS_COWORK_CONTEXT_v1.0.0.md`
(dated 2026-05-02, before the V200 milestone opened 2026-05-25). Reads like a standing cowork-session onboarding
pointer (this pattern — `team_200/AOS_COWORK_CONTEXT_*.md` as a mandatory-read onboarding doc — exists at the hub
level too), but it carries a version tag so it does not cleanly match the procedure's explicit "no version tag"
ESCALATE row either; falls to the catch-all. Left untouched.

**D-2 documentation gaps (Phase 1, cross-referenced, not double-counted in Phase 2 tallies):**
- `NB-S001-P001-WP001` — `spec_ref: TBD`, no as-built record ever produced (OPS/Express track). Archived anyway
  per the pilot's retroactive-archival note (status COMPLETE is the only precondition), but the missing as-built
  is flagged for team_120.
- `NB-S002-P009-WP004` — `spec_ref` points to `sources/team_35_design_package/design_handoff_home/assets/ASSETS_README.md`,
  which does not exist anywhere in this worktree (pre-existing broken reference, not introduced by this sweep).

## After (metrics)
after: { unarchived_comm_files: 348, open_wps: 19, archive_dirs: 22, quarantined: 0 }
delta: { archived: 771, quarantined: 0, comm_files_cleared: 771 }

(`open_wps` unchanged at 19 — this domain's established archival convention, confirmed against the pre-existing
P009-WP002/WP003 manifests, leaves `_aos/work_packages/<WP-ID>/LOD400_*.md` in place after a WP's
`_COMMUNICATION/` artifacts are archived; only `_COMMUNICATION/` moves. `archive_dirs` rose from 4 → 22,
i.e. `_archive/` root + 21 WP subdirectories, +18 net new.)

## Uncommitted — awaiting team_60 / human review
No commit or push was performed. All 771 renames, 21 manifests (18 new + 3 appended), and the 1 `roadmap.yaml`
`spec_ref` fix are staged/present in the working tree of this isolated worktree
(`/Users/nimrod/Documents/AOS_V5/nimrod-bio-sweep`), uncommitted, per instruction.

---
*team_120 (sweep execution) | DOMAIN_DOC_ARCHIVE_SWEEP_PROCEDURE_v1.0.0 | 2026-07-09*
