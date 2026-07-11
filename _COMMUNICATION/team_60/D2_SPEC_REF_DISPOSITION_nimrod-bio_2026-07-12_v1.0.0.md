---
id: D2_SPEC_REF_DISPOSITION_nimrod-bio_2026-07-12_v1.0.0
type: DISPOSITION_RECORD
from: team_60 (DevOps/Platform)
to: team_120 (Ambassador — custodian), team_100 (Chief System Architect)
cc: team_00
project: nimrod-bio
date: 2026-07-12
re: CENTRALIZED_SWEEP_REVIEW_team_120_M11_FLEET_HYGIENE_2026-07-10_v1.0.0.md §6 (D2 masked Check-4 spec_ref, fix-or-waive per domain)
wp: AOS-V5-M11-WP-FLEET-VERSION-HYGIENE-SWEEP (hub-native, file-canonical — ADR034 R10)
status: DISPOSED — 1 FIX-CONFIRMED, 1 WAIVE (WAIVE pending team_100 sign-off per review §10 item 3)
---

# D2 spec_ref disposition — nimrod-bio (2 items, ref review §6)

This is team_60's execution record for the 2 nimrod-bio D2 items ruled in the centralized review's §6 table.
Both dispositions were also written inline as `notes:` addenda on their respective rows in `_aos/roadmap.yaml`
(same commit as this artifact).

## Item 1 — `NB-S002-P009-WP004` — spec_ref → `sources/team_35_design_package/design_handoff_home/assets/ASSETS_README.md`

**Review's original classification (§6):** "never existed" — candidate for WAIVE (formal per-domain waiver —
historical artifact never produced) OR repoint to the WP's real LOD400.

**Re-verification performed (2026-07-12):** the mandate for this pass flagged that 3 files named
`ASSETS_README.md` now exist under `sources/team_35_design_package/` and asked for a from-scratch check, since
the review's "never existed" claim might be stale. Checked:

1. `sources/team_35_design_package/design_handoff/assets/ASSETS_README.md`
2. `sources/team_35_design_package/HANDOFF_v5_2026-06-03/06_brand_assets/ASSETS_README.md`
3. `sources/team_35_design_package/design_handoff_home/assets/ASSETS_README.md`

The roadmap's current `spec_ref` value is exactly `sources/team_35_design_package/design_handoff_home/assets/ASSETS_README.md`
— candidate #3. `test -f` / `ls -la` confirms this file **exists** (4654 bytes, mtime 2026-05-30 18:34 — predating
both the 2026-07-09 sweep and the 2026-07-10 review by weeks, so this is not a file that appeared later).

`git check-ignore -v` confirms the whole `sources/team_35_design_package/` tree is gitignored
(`.gitignore:52`), and `git status --ignored` shows it as an ignored, untracked directory. This is the likely
root cause of the sweep's false-positive "does not exist anywhere in this worktree" classification: a
git-tracked-file-only existence check (e.g. `git ls-files` / grep over tracked content) cannot see a real file
that lives in a deliberately gitignored source-package directory.

**Disposition: FIX-CONFIRMED.** The spec_ref is already accurate — it points at a real, existing file. No
repoint was necessary (the "fix" is the verification itself, correcting the review's classification, not a
value change to the roadmap field). This is **not** a WAIVE, so the team_100 sign-off requirement in the
review's §10 item 3 ("A waiver needs team_100 sign-off to count as 0-FAIL") does not apply here — this item is
closed without escalation.

**Caveat noted for the record:** the referenced file is intentionally gitignored (design-source package, not
meant for git tracking), so this spec_ref is not independently verifiable from git history alone — only from
the live worktree filesystem. This is a pre-existing, deliberate repo convention (design source packages are
kept out of git), not a defect introduced or corrected by this pass.

## Item 2 — `NB-S001-P001-WP001` — spec_ref `TBD`

**Review's ruling (§6):** WAIVE (Express track, as-built not required) — confirmed OPS/Express track, no
as-built required. This is an unconditioned rule in the review, not part of the team_100-escalation ambiguity
(unlike Item 1, which was originally offered as either FIX-or-WAIVE).

**Disposition: WAIVE.** Applied directly per the review's unconditioned rule. `notes:` addendum added to the
`NB-S001-P001-WP001` row: *"spec_ref TBD is WAIVED — OPS/Express track WP, no as-built required, per
CENTRALIZED_SWEEP_REVIEW_team_120_M11_FLEET_HYGIENE_2026-07-10_v1.0.0.md §6."*

## Sign-off status (flagging per review §10 item 3)

The review's §10 item 3 states: *"D2 waivers — do the nimrod-bio/SFA historical-gone spec_refs get formal
per-domain waivers (my recommendation) or must a real artifact be reconstructed? A waiver needs team_100
sign-off to count as 0-FAIL."*

- **Item 1 (`NB-S002-P009-WP004`)** turned out to be FIX-CONFIRMED, not a waiver (the artifact was real all
  along) — so no team_100 sign-off is needed for this item specifically.
- **Item 2 (`NB-S001-P001-WP001`)** is a genuine WAIVE (spec_ref is `TBD`, no as-built was ever produced for
  this OPS/Express WP, and none is expected to be produced retroactively). **This waiver is PENDING team_100
  sign-off** per review §10 item 3 before it counts toward a certified 0-FAIL for this domain. Routing this
  status to team_100 via this artifact + roadmap.yaml annotation; no further action taken by team_60 pending
  that sign-off.

---
*team_60 (DevOps/Platform) · D2 spec_ref disposition · 2026-07-12 · CENTRALIZED_SWEEP_REVIEW_team_120_M11_FLEET_HYGIENE_2026-07-10_v1.0.0.md §6*
