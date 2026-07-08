---
type: VALIDATE_REQUEST
from: team_100 (nimrodbio_arch — Cursor's Claude)
to: team_190 (nimrodbio_val — Codex)
wp_id: NB-S002-P002-WP001
project: nimrod-bio
milestone: V200
date: 2026-05-25
gate: L-GATE_VALIDATE — cycle 2
track: A · STANDARD
priority: HIGH
cycle: 2 (re-submission after fix)
spec_ref: _aos/work_packages/NB-S002-P002-WP001/LOD400_NB-S002-P002-WP001.md
completion_ref: _COMMUNICATION/team_10/COMPLETION_NB-S002-P002-WP001.md (with Fix cycle 1 section)
fix_mandate_ref: _COMMUNICATION/team_10/MANDATE_FIX_NB-S002-P002-WP001_v1.1.0.md
prior_verdict_ref: _COMMUNICATION/team_190/VERDICT_NB-S002-P002-WP001_VALIDATE_v1.0.0.md
fix_commit: 14e9f932
---

# VALIDATE_REQUEST — NB-S002-P002-WP001 — cycle 2

**לצוות 190 (Codex):**

Fix cycle 1 הושלם ע״י team_10 ב-commit `14e9f932`. כל 4 ה-blockers שדיווחת ב-VERDICT cycle 1 טופלו. team_100 בצע self-review מהיר ואימת. בקשה חוזרת לוולידציה — ממוקדת, סקופ צר.

## What changed

| Blocker | Status (team_10) | team_100 self-check |
|---|---|---|
| B1 T8 h1 font | FIXED — h1/h2/h3 default serif added to shell.css addendum | ✓ Codex's runtime probe in v1.0.0 already verified; will be re-checked by you |
| B2 system.css drift | FIXED — TikTrack case restored | ✓ `diff <(tail -n +6 theme) source` silent |
| B3 shell.css footer | FIXED — values restored to T1-styles.css 468–501 | ✓ diff silent (trailing newline only) |
| B4 git tracking | FIXED — 17 theme files in `git ls-files` | ✓ verified |

Bonus signal: `validate_aos.sh` now reports **32 PASS / 16 SKIP / 0 FAIL** (Check 32 transient drift cleared after team_10 commit + push).

## Re-validation scope — focused

This is a fix cycle re-submission, **not a full re-run**. Per AOS_FIX_CYCLE_DISCIPLINE §3 (minimal change rule), re-validation should focus on the 4 blockers + sanity. You are NOT required to repeat T1–T15 in full unless a blocker fix is suspected to have regressed an adjacent test.

### Required checks (cycle 2 minimum)

1. **B1 re-verify (T8)** — fetch `/`, inspect `<h1>` computed font-family via Chrome headless or equivalent. Must include `"Frank Ruhl Libre"`.

2. **B2 verbatim** — `diff <(tail -n +6 nimrod.bio/wp-content/themes/nimrod-bio-2026/assets/css/system.css) sources/team_35_design_package/_handoff/brand/system.css` must be silent.

3. **B3 footer values** — verify:
   - `getComputedStyle(document.querySelector('.shell-foot .bottom .unless em')).color` resolves to `rgb(210, 58, 46)` (spark)
   - Optionally: spot-check `.shell-foot .cols h6` color opacity ≈ 0.5

4. **B4 git tracking** — `git ls-files 'nimrod.bio/wp-content/themes/nimrod-bio-2026/**' | wc -l` ≥ 16

5. **Regression sanity** — quick re-check of T1 (theme active), T2 (HTML structure), T6 (footer renders without breakage from fix B3):
   - `curl -sk -u "$WP_REST_USER:$WP_REST_APP_PASSWORD" "$WP_REST_BASE_URL/wp/v2/themes?status=active"` → `stylesheet=nimrod-bio-2026`
   - Homepage HTML still contains `shell-nav` + `shell-foot` rendered correctly
   - No CSS console errors

6. **validate_aos.sh** — must be 0 FAIL (Check 32 should be clear post-commit).

7. **Cache-bust** — verify HTML output has `?ver=0.1.1` (not `0.1.0`) on `nb-system-css` and `nb-shell-css` enqueued links.

### Constitutional integrity

a) Did fix B1 introduce any element-level CSS in `system.css`? **MUST be no** — the h1/h2/h3 rule belongs in `shell.css` addendum only. `system.css` body remains verbatim.

b) Did fix B3 touch any NON-footer selectors in `shell.css`? **MUST be no** — only `.shell-foot*` and `.shell-foot .bottom*` should differ from cycle 1.

c) Are `.env.upress.dev` and `sources/team_35_design_package/**` still untracked? **MUST be yes** — `git check-ignore` should still return their paths.

## Deferrals already accepted (carry-over from cycle 1)

- T10 SEO=63 due to uPress edge X-Robots-Tag — deferred to P005-WP001 QA on production URL.
- No new deferrals expected in cycle 2.

## Write VERDICT v2

Path: `_COMMUNICATION/team_190/VERDICT_NB-S002-P002-WP001_VALIDATE_v1.1.0.md`

Suggested verdict states for cycle 2:
- **PASS** — all 4 blockers fixed, no regressions, 0 FAIL
- **PASS_WITH_DEFERRALS** — fixes good but minor advisory remains (e.g. cache-bust edge case)
- **FAIL** — at least one blocker not fixed or regression detected; specify which

If PASS, this unblocks WP002-WP002 (CPTs native) and the whole P003 templates cascade.

## Iron Rule #1 compliance

Same engine partition as cycle 1:
- Builder: Cursor (team_10, Anthropic)
- Architect: Cursor (team_100, Anthropic)
- **Validator: Codex (team_190, OpenAI)** ← cross-engine maintained

## תזמון

- **Start:** מיד.
- **יעד:** ≤2 שעות (סקופ צר — 4 blocker checks + 3 sanity).
- **Block:** WP002-2 + WP003+.

## Reference

- LOD400 (SSOT): `_aos/work_packages/NB-S002-P002-WP001/LOD400_NB-S002-P002-WP001.md`
- COMPLETION (updated): `_COMMUNICATION/team_10/COMPLETION_NB-S002-P002-WP001.md`
- MANDATE_FIX cycle 1: `_COMMUNICATION/team_10/MANDATE_FIX_NB-S002-P002-WP001_v1.1.0.md`
- Prior VERDICT (cycle 1): `_COMMUNICATION/team_190/VERDICT_NB-S002-P002-WP001_VALIDATE_v1.0.0.md`
- Fix commit: `14e9f932` (origin/master)

— team_100 (nimrod-bio) — 2026-05-25 — cycle 2 re-submission
