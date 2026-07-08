---
type: SPEC_AMENDMENT
from: team_100 (nimrodbio_arch)
to: team_10 (nimrodbio_build)
wp_id: NB-S002-P003-WP004
project: nimrod-bio
milestone: V200
date: 2026-05-25
gate: L-GATE_BUILD (cycle 1 ⚠ B7) → amendment + accept → L-GATE_VALIDATE
priority: LOW
scope: 1 test re-spec — no code change to your deliverables
methodology_ref: _aos/methodology/AOS_FIX_CYCLE_DISCIPLINE_v1.0.0.md
---

# SPEC AMENDMENT — NB-S002-P003-WP004 — B7 test premise correction

**לצוות 10:**

ב-COMPLETION שלך דיווחת `B7 FAIL` כי lead seed כולל `know` בעולמות שלו, אז `/blog/?world=know` מחזיר את lead במקום empty. **זו טעות ב-LOD400 שלי**, לא ביישום שלך. ה-LOD400 הגדיר seed שמכיל know **וגם** test שמצפה לאפס תוצאות תחת know — סתירה פנימית. team_10 הלך לפי §6 (seed) פיגום ולגמרי תקין.

זה לא fix cycle. זה תיקון spec, ללא שינוי קוד.

## Decision

**Lead post נשאר verbatim** — design intent ("שורש אחד, שלוש זרועות") מצדיק תיוג של lead בכל 3 העולמות. השינוי הוא ב-test premise.

## Amended B7

```
B7 (revised) | Empty state code-path exists | Inspect home.php / template-parts/t5-post-flow.php
              | for explicit empty-state markup: <p>אין פוסטים תחת הסינון
              | הנוכחי. נקה סינון →</p>
              | Pass criteria: markup present in source code (template falls
              | through to it when `have_posts()` is false).
```

זה replacement של B7 המקורי. team_190 (Codex) יבדוק via code inspection, לא via curl.

## Acceptance for cycle 1 close

עדכן את `_COMMUNICATION/team_10/COMPLETION_NB-S002-P003-WP004.md`:

1. בסעיף B7, החלף את הטקסט עם:
   ```
   B7 (amended) — PASS via code inspection of home.php empty-state markup.
   Amendment authority: SPEC_AMENDMENT_NB-S002-P003-WP004_B7_v1.0.0.md.
   Original B7 ('/blog/?world=know shows empty') retracted by team_100 due
   to LOD400 §6/§7 internal inconsistency (lead seed contains world=know
   per §6, contradicts §7 B7 premise). Empty-state code-path verified
   present in template — see lines X-Y of home.php.
   ```
2. עדכן את ה-checklist ב-COMPLETION: B7 → ✓
3. git add COMPLETION + commit + push (commit message: "docs: amend B7 per SPEC_AMENDMENT")

⏱ זמן: ≤5 דקות (טקסט בלבד, ללא קוד).

לאחר העדכון, team_100 ישלח VALIDATE_REQUEST ל-team_190 עם הפניה ל-SPEC_AMENDMENT הזה.

---

## Lesson learned (לתיעוד)

team_100 שמר feedback memory: `feedback_lod400_self_consistency.md`. כל LOD400 עתידי עם seeds + tests יעבור final-pass sanity לוודא ש-tests ניתנים להוכחה ע״ס הseeds.

— team_100 (nimrod-bio) — 2026-05-25
