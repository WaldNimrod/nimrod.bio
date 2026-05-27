---
type: VALIDATE_REQUEST
from: team_10 (nimrodbio_build — Cursor Codex)
to: team_190 (nimrodbio_val — Codex)
wp_id: NB-S002-P004-WP002
project: nimrod-bio
milestone: V200
program: P004
date: 2026-05-25
gate: L-GATE_VALIDATE
track: A · STANDARD
priority: HIGH
spec_ref: _aos/work_packages/NB-S002-P004-WP002/LOD400_NB-S002-P004-WP002.md
completion_ref: _COMMUNICATION/team_10/COMPLETION_NB-S002-P004-WP002.md
build_commit: ceda4535
---

# VALIDATE_REQUEST — NB-S002-P004-WP002 — Redirect enforcement + Yoast

**לצוות 190 (Codex):**

WP002 הושלם בצד build ונשלח לולידציה constitutional עצמאית. חשוב: דווח חסם ריצה בסביבת dev (`Server: nginx`) שבו כללי `.htaccess` לא נאכפים בפועל, ולכן R5/R6/R8/R9 מסומנים כ-BLOCKED למרות deploy additive תקין.

## Scope of validation

1. **Replay independent validation** מול LOD400 §10 (`R1-R17`).
2. **Validate evidence pack** מתוך `COMPLETION_NB-S002-P004-WP002.md`:
   - generated block: `docs/htaccess_v200_redirects.txt`
   - verification JSON: `docs/redirect_verification_2026-05-25.json`
   - scripts: `scripts/redirects/*.py`
3. **Constitutional determination required**:
   - האם חסם nginx/.htaccess נחשב FAIL מחייב מחזור תיקון נוסף,
   - או PASS_WITH_DEFERRALS עם נימוק מפורש וסגירת סיכונים לשלב P005.
4. **Verify Yoast/sitemap outcomes** (`R10-R13`) independent of team_10 report.
5. **Confirm current AOS baseline** (`validate_aos.sh` expected clean: 32 PASS / 16 SKIP / 0 FAIL).

## Evidence pointers

- Spec (SSOT): `_aos/work_packages/NB-S002-P004-WP002/LOD400_NB-S002-P004-WP002.md`
- Build completion: `_COMMUNICATION/team_10/COMPLETION_NB-S002-P004-WP002.md`
- Build mandate: `_COMMUNICATION/team_10/MANDATE_NB-S002-P004-WP002_v1.0.0.md`
- Build commit: `ceda4535`
- Redirect block artifact: `docs/htaccess_v200_redirects.txt`
- Runtime verification snapshot: `docs/redirect_verification_2026-05-25.json`

## Exact validation ask

Return a formal verdict artifact at:

`_COMMUNICATION/team_190/VERDICT_NB-S002-P004-WP002_VALIDATE_v1.0.0.md`

with:

- verdict: `PASS` / `PASS_WITH_DEFERRALS` / `FAIL`
- test table for `R1-R17` with independent evidence-by-path
- constitutional assessment of the nginx/.htaccess blocker
- route recommendation (if FAIL) with exact next owner and artifact target

## Routing prompt (copy/paste)

```text
Validate WP NB-S002-P004-WP002 (L-GATE_VALIDATE) independently.
Read:
1) _aos/work_packages/NB-S002-P004-WP002/LOD400_NB-S002-P004-WP002.md
2) _COMMUNICATION/team_10/COMPLETION_NB-S002-P004-WP002.md
3) docs/htaccess_v200_redirects.txt
4) docs/redirect_verification_2026-05-25.json

Required output:
_COMMUNICATION/team_190/VERDICT_NB-S002-P004-WP002_VALIDATE_v1.0.0.md
Include R1-R17 evidence table, constitutional determination on nginx/.htaccess runtime blocker, and clear route recommendation if verdict is FAIL.
```

## Timing

- Start: immediate
- Target: same-day constitutional verdict
- Downstream dependency: final closure message to `team_100` waits on this verdict

---

*VALIDATE routing package — team_10 to team_190 | 2026-05-25*
