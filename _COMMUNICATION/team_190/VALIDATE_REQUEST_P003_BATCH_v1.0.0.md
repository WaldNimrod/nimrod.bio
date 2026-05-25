---
type: VALIDATE_REQUEST_BATCH
from: team_100 (nimrodbio_arch — Cursor's Claude)
to: team_190 (nimrodbio_val — Codex)
project: nimrod-bio
milestone: V200
program: P003
date: 2026-05-25
gate: L-GATE_VALIDATE (5 WPs)
batch_scope: 5 WPs ready for cross-engine validation in parallel
priority: HIGH
iron_rule: "#1 (builder = Cursor/team_10; validator = Codex/team_190)"
---

# VALIDATE_REQUEST batch — P003 templates cascade

**לצוות 190 (Codex):**

ה-5 WPs של P003 חזרו מ-team_10 עם COMPLETIONs. team_100 ביצע self-review של git tracking + pushed all 10 commits. ה-validation יכול לרוץ במקביל (multi-agent ב-Codex אם מתאים).

| WP | Status @ team_10 | COMPLETION | Key seeds/files | Notes |
|---|---|---|---|---|
| **P003-WP001 T7 Home** | ✓ PASS H1-H10 | `_COMMUNICATION/team_10/COMPLETION_NB-S002-P003-WP001.md` | front-page.php, t7.css | Owns `glob()` in functions.php — first to add |
| **P003-WP002 T1 Worlds** | ✓ PASS W1-W14 | `_COMMUNICATION/team_10/COMPLETION_NB-S002-P003-WP002.md` | page-{soil/know/code}.php, t1.css, helper `nb_get_bridges_for_world()` | Variant C + seam locked |
| **P003-WP003 T2+T3** | ✓ PASS S1-S15 | `_COMMUNICATION/team_10/COMPLETION_NB-S002-P003-WP003.md` | single-service.php, single-project.php, 6 seed instances (3 services + 3 projects), t2/t3.css | Largest WP — 6 seeds verbatim from JSX instances |
| **P003-WP004 T4+T5** | ⚠ PASS_W/AMEND on B7 | `_COMMUNICATION/team_10/COMPLETION_NB-S002-P003-WP004.md` | single.php, home.php, t4/t5.css, t5-filter.js, 4 seed posts, `nb_extract_toc()` | **B7 was LOD400 spec defect by team_100. SPEC_AMENDMENT issued — see below.** |
| **P003-WP005 T8 Static** | ✓ PASS A1-A18 | `_COMMUNICATION/team_10/COMPLETION_NB-S002-P003-WP005.md` | page-{about/heritage/contact}.php, t8.css, contact handler, 3 static pages bootstrap | NB_THEME_VERSION → 0.4.0 (then 0.4.1 from WP002 parallel) |

## WP004 special handling — B7 SPEC_AMENDMENT

Read: `_COMMUNICATION/team_10/SPEC_AMENDMENT_NB-S002-P003-WP004_B7_v1.0.0.md`

**Summary:** Original LOD400 §6 mandated lead post seed with `worlds:[soil,know,code]`. Original LOD400 §7 B7 mandated `/blog/?world=know shows empty`. Internal contradiction in spec (lead has know → not empty). team_100 acknowledges spec defect (see memory `feedback_lod400_self_consistency`). B7 retracted; replaced with code-inspection test: "empty-state markup present in home.php".

When validating WP004:
- Skip the original B7 curl test.
- Run the amended B7: inspect `home.php` (or `template-parts/t5-post-flow.php`) for the empty-state markup string "אין פוסטים תחת הסינון הנוכחי" or equivalent. If present in source → PASS.
- All other B1-B6, B8-B14 — replay as in LOD400.

## Per-WP validation scope

For each WP, the standard cycle from prior WPs:
1. Read its LOD400 + COMPLETION + LOD300 program (shared)
2. Replay acceptance tests independently (4 sessions × ~15 tests = ~60 tests total)
3. Constitutional checks:
   - **a) helpers added are documented** in COMPLETION (cross-check `template-helpers.php` diff)
   - **b) no edits to system.css or shell.css** — `git log --follow nimrod.bio/wp-content/themes/nimrod-bio-2026/assets/css/system.css` shows no commits past `ebc2b481` for system; shell.css same
   - **c) functions.php edits limited to glob() once + contact handler require_once** — verify exactly 2 lines added between WP001 baseline and WP005 closure
   - **d) seed records marked `_nb_seed=v200`** — REST query each CPT type, verify meta
   - **e) test records cleaned** — any artifacts of acceptance tests removed pre-COMPLETION
   - **f) version ladder** — accept the drift (0.3.0/0.4.0/0.4.1 sequence per team_10 advisory)
4. Baseline §11 of LOD300 program (Shell still renders, validate_aos.sh, etc.)
5. Write VERDICT per WP at `_COMMUNICATION/team_190/VERDICT_NB-S002-P003-WP00{N}_VALIDATE_v1.0.0.md`

## Recommended execution order (if not parallel)

If you can't dispatch all 5 simultaneously, order by independence/risk:
1. **WP001 T7** (simplest, 10 tests) — quickest signal that base infra works
2. **WP005 T8** (form handler isolated, 18 tests) — exercises form submission path
3. **WP002 T1** (Variant C lock, 14 tests) — exercises CPT queries by world
4. **WP003 T2+T3** (largest, 15 tests + 6 seeds REST verification) — most surface
5. **WP004 T4+T5** (with B7 amendment, 14 tests) — last because of amendment

But ideal is full parallel via Codex multi-agent.

## אזכור עצמאי לבדיקה (sanity helper)

```bash
git fetch
git log --oneline -15  # should include 9b1076a4 + all P003 work commits
set -a; source .env.upress.dev; set +a
bash _aos/lean-kit/modules/validation-quality/scripts/validate_aos.sh .
# expect: 32 PASS / 16 SKIP / 0 FAIL

# Per-WP entry points:
curl -sk "$UPRESS_DEV_URL_HTTP/"                                # T7
curl -sk "$UPRESS_DEV_URL_HTTP/world/soil/"                     # T1
curl -sk "$UPRESS_DEV_URL_HTTP/services/produce/"               # T2
curl -sk "$UPRESS_DEV_URL_HTTP/blog/"                           # T5
curl -sk "$UPRESS_DEV_URL_HTTP/about/"                          # T8
```

## תזמון

- **Start:** מיד לאחר team_10 push לאישור COMPLETION של WP004 B7 (קצר — 5 דקות עבודה).
- **Yet:** אפשר להתחיל את הולידציה של 4 WPs הראשונים מיד; WP004 ימתין לעדכון ה-COMPLETION.
- **Target:** יום עבודה אחד לכל 5 (parallel).

## Iron Rule #1

Builder: Cursor (team_10) ✓ · Architect: Cursor (team_100) ✓ · Validator: Codex (team_190) ✓

## Reference

- LOD300 program: `_aos/work_packages/S002/P003/LOD300_P003_program.md`
- 5 LOD400s: `_aos/work_packages/NB-S002-P003-WP00{1-5}/LOD400_*.md`
- 5 MANDATEs: `_COMMUNICATION/team_10/MANDATE_NB-S002-P003-WP00{1-5}_v1.0.0.md`
- B7 amendment: `_COMMUNICATION/team_10/SPEC_AMENDMENT_NB-S002-P003-WP004_B7_v1.0.0.md`
- All build commits: 9b1076a4 (HEAD) and predecessors per `git log`

— team_100 (nimrod-bio) — 2026-05-25
