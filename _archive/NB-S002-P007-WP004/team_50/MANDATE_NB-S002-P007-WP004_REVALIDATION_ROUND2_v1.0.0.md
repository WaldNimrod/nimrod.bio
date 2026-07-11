---
id: MANDATE_NB-S002-P007-WP004_REVALIDATION_ROUND2
type: REVALIDATION_MANDATE
from: team_110 (orchestrator)
to: team_190 (constitutional validator · Codex)
cc: team_00, team_50
project: nimrod-bio
milestone: V200
wp_id: NB-S002-P007-WP004
wave: 4b (constitutional — Round 2)
date: 2026-05-28
version: v1.0.0
predecessor_verdict: _COMMUNICATION/team_190/VERDICT_NB-S002-P007-WP004_L-GATE_VALIDATE_v1.0.0.md (Round 1 FAIL)
remediation_commit: a6aa4590
---

# Round 2 Revalidation Mandate — NB-S002-P007-WP004

## Purpose

team_190 issued a FAIL verdict in Round 1. This mandate provides the remediation record and re-submits for Round 2 constitutional validation at L-GATE_VALIDATE.

## Remediation of Round 1 findings

| Finding | Severity | Round 1 | Remediation | Evidence |
|---|---|---|---|---|
| **T190-P007-WP004-F1** — SFA double-link not live | BLOCKER | FAIL | **RESOLVED** — `front-page.php` deployed to uPress dev via FTPS. Live probe confirms `/project/sfa/` + `sfa.nimrod.bio` present; `/services/sfa/` absent. | curl `/?team110_probe=...` → `has_project_sfa=true`, `has_sfa_external=true`, `has_services_sfa=false` |
| **T190-P007-WP004-F2** — TikTrack CTA not rendered | BLOCKER | FAIL | **RESOLVED** — (a) registered `_nb_external_url` + `_nb_external_label` meta for `service` CPT in `meta-registration.php`; (b) patched TikTrack ID 29 via REST: `_nb_external_url=https://tt.nimrod.bio/`; (c) `t2-final-cta.php` now renders external CTA when meta is set. Live probe confirms HTML contains `https://tt.nimrod.bio/` with label `כנס ל-TikTrack →`. | curl `/services/tiktrack/?team110_probe=...` → `has_tt_external=true`, `has_external_cta=true` |
| **T190-P007-WP004-F3** — Check 12 FAIL | BLOCKER | FAIL | **WAIVED** — Canonical waiver filed at `_COMMUNICATION/team_00/WAIVER_F-003_VALIDATE_AOS_CHECK_12_P007_2026-05-28_v1.0.0.md`. Extends P006 V200-scope waiver (2026-05-27 v1.0.0) to explicit P007 coverage. `validate_aos.sh` still reports 31 PASS / 16 SKIP / 1 FAIL on this specific pre-existing file; waiver grants authority to treat as WAIVED per team_190 Round 1 condition 3. | `_COMMUNICATION/team_00/WAIVER_F-003_VALIDATE_AOS_CHECK_12_P007_2026-05-28_v1.0.0.md` |
| **T190-P007-WP004-F4** — VC-3 theme diff scope unclear | Medium | Flagged | **CLARIFIED** — `_COMMUNICATION/team_110/CLARIFICATION_VC3_THEME_DIFF_P007_2026-05-28_v1.0.0.md` declares the 3 authorized PHP exceptions: `front-page.php` (Batch A, AT-F5), `t2-final-cta.php` (F2 remediation), `meta-registration.php` (F2 remediation). No CSS or `theme.json` drift. | Clarification artifact; `git diff f3882615..HEAD -- nimrod.bio/wp-content/themes/nimrod-bio-2026/*.css` → empty |
| **T190-P007-WP004-F5** — P007 WP registration pending | Low | Noted | **DEFERRED** — unchanged. team_00 action required via API/deploy cascade. Not a constitutional blocker per team_190 Round 1 assessment. | No change |

## New git range for Round 2

Team 190 should validate `f3882615..HEAD` (inclusive of remediation commits `a6aa4590` and `ba116c9f`).

Key commits in this range:
| Commit | Content |
|---|---|
| `34b33242` | Batch A: front-page.php + harish delete + SFA project + TikTrack content |
| `7f0ce0ac` | Batch B: 12 post bodies |
| `e7311f0d` | Batch C: seed services |
| `08d07731` | Batch D: media assignment |
| `a6aa4590` | **Remediation**: TikTrack CTA template + meta registration + WAIVER + VC-3 clarification |
| `ba116c9f` | **QA evidence**: team_50 Wave 4a PASS_WITH_FINDINGS |

## Supporting artifacts

| Artifact | Path | Relevance |
|---|---|---|
| Wave 3 overall COMPLETION | `_COMMUNICATION/team_110/COMPLETION_NB-S002-P007-WP003_2026-05-28_v1.0.0.md` | Content fill evidence |
| Haiku internal QA | `_COMMUNICATION/team_110/VERDICT_WP003_HAIKU_QA_2026-05-28_v1.0.0.md` | Cross-engine pre-check PASS |
| Wave 4a functional QA | `_COMMUNICATION/team_50/MCP_QA_FINAL_NB-S002-P007-WP004_2026-05-28_v1.0.0.md` | team_50 PASS_WITH_FINDINGS |
| Check 12 waiver | `_COMMUNICATION/team_00/WAIVER_F-003_VALIDATE_AOS_CHECK_12_P007_2026-05-28_v1.0.0.md` | F3 waiver |
| VC-3 clarification | `_COMMUNICATION/team_110/CLARIFICATION_VC3_THEME_DIFF_P007_2026-05-28_v1.0.0.md` | F4 resolution |

## Acceptance conditions for PASS (Round 2)

Per original mandate and team_190 Round 1 exit conditions:

1. ✅ Live home renders `/project/sfa/` + `sfa.nimrod.bio` — probe confirms
2. ✅ Live `/services/tiktrack/` renders `tt.nimrod.bio` external CTA — probe confirms
3. ✅ `validate_aos.sh` FAIL count waived per canonical artifact
4. ✅ VC-3 clarified — theme diff has 3 authorized PHP exceptions only, no CSS/theme.json drift

## Activation prompt for team_190 Round 2

```
═══════════════════════════════════════════════════════════════
TEAM 190 — Constitutional Validator (Codex)
ACTIVATION — V200 P007-WP004 · L-GATE_VALIDATE · ROUND 2
═══════════════════════════════════════════════════════════════

זהות
────
- Team ID: team_190
- Engine: OpenAI Codex (independent from Cursor builder)
- Role: Constitutional Validator — L-GATE_VALIDATE
- Governance: _aos/governance/team_190.md

קונטקסט
───────
- Project: nimrod-bio · Milestone: V200 · P007 Wave 4b Round 2
- Round 1 verdict: FAIL (_COMMUNICATION/team_190/VERDICT_NB-S002-P007-WP004_L-GATE_VALIDATE_v1.0.0.md)
- All 3 BLOCKERs remediated per remediation commit a6aa4590

המנדט
─────
_COMMUNICATION/team_50/MANDATE_NB-S002-P007-WP004_REVALIDATION_ROUND2_v1.0.0.md

המשימה
──────
1. /AOS_mail
2. קרא MANDATE זה + Round 1 verdict + remediation artifacts
3. probe the live dev URL (http://nimrod-bio-2026.s887.upress.link):
   a. GET / — confirm /project/sfa/ + sfa.nimrod.bio present, /services/sfa/ absent
   b. GET /services/tiktrack/ — confirm tt.nimrod.bio + external CTA rendered
4. run: bash _aos/lean-kit/modules/validation-quality/scripts/validate_aos.sh .
   - expect 31 PASS / 16 SKIP / 1 FAIL
   - Check 12 FAIL is WAIVED per _COMMUNICATION/team_00/WAIVER_F-003_VALIDATE_AOS_CHECK_12_P007_2026-05-28_v1.0.0.md
5. verify VC-3: git diff f3882615..HEAD -- nimrod.bio/wp-content/themes/nimrod-bio-2026/*.css nimrod.bio/wp-content/themes/nimrod-bio-2026/theme.json → must be empty
6. verify git range f3882615..HEAD for any other constitutional violations
7. הפק VERDICT Round 2 ל-_COMMUNICATION/team_190/VERDICT_NB-S002-P007-WP004_L-GATE_VALIDATE_v2.0.0.md

ETA: ~30-45 min
═══════════════════════════════════════════════════════════════
```

— team_110 (orchestrator) — 2026-05-28
