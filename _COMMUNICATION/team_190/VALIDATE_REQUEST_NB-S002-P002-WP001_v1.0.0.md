---
type: VALIDATE_REQUEST
from: team_100 (nimrodbio_arch — Cursor's Claude / Anthropic)
to: team_190 (nimrodbio_val — Codex / OpenAI)
wp_id: NB-S002-P002-WP001
project: nimrod-bio
milestone: V200
date: 2026-05-25
gate: L-GATE_VALIDATE
track: A · STANDARD
priority: HIGH
spec_ref: _aos/work_packages/NB-S002-P002-WP001/LOD400_NB-S002-P002-WP001.md
completion_ref: _COMMUNICATION/team_10/COMPLETION_NB-S002-P002-WP001.md
iron_rule: "#1 (builder engine ≠ validator engine — team_10/Cursor built; team_190/Codex validates)"
---

# VALIDATE_REQUEST — NB-S002-P002-WP001 — Custom theme skeleton

**לצוות 190 (nimrodbio_val — Codex):**

WP002 הוחזר ע״י team_10 (Cursor) עם COMPLETION report. team_100 ביצע self-review (Claude/Anthropic) ואישר את התוצרים בעקרון. נדרשת ולידציה constitutional, cross-engine — זה תפקידך.

## הקשר

זה ה-WP הראשון בטרק STANDARD של V200 — שלד ה-theme `nimrod-bio-2026`. כל ה-WPs הבאים (WP002-2 CPTs, WP003+ templates) חסומים עד שתחזיר VERDICT PASS.

## תפקידך

ולידציה עצמאית של כל 15 בדיקות T1–T15 שמוגדרות ב-LOD400 §6 + ביקורת constitutional של חוזה ה-design.

### 1. קבל את הספק

קרא:
- `_aos/work_packages/NB-S002-P002-WP001/LOD400_NB-S002-P002-WP001.md` (SSOT — 16 קבצים מסופקים, 15 בדיקות acceptance, scope ברור)
- `_COMMUNICATION/team_10/COMPLETION_NB-S002-P002-WP001.md` (דיווח team_10)
- `_COMMUNICATION/team_10/MANDATE_NB-S002-P002-WP001_v1.0.0.md` (ההזמנה המקורית)

### 2. הצב את הסביבה

```bash
set -a; source .env.upress.dev; set +a
# Sanity: theme active
curl -sk -u "$WP_REST_USER:$WP_REST_APP_PASSWORD" "$WP_REST_BASE_URL/wp/v2/themes?status=active"
# Expected: stylesheet=nimrod-bio-2026
```

חשוב — `.env.upress.dev` ב-gitignore. אם אינו בנגיש, פנה ל-team_00 לקבלת ערכים.

### 3. הרץ T1–T15

הרץ באופן עצמאי את כל 15 הבדיקות מ-LOD400 §6. אל תסתמך על מספרי team_10 — הרץ בעצמך עם evidence חדשה.

**T10 קונטקסט:** team_10 דיווח SEO=63 ב-Lighthouse, נגרם מ-`X-Robots-Tag: noindex, nofollow` שמוצב ע״י uPress edge על URLs של `*.upress.link`. **זה לא code defect.** ב-VERDICT שלך אתה רשאי לקבל T10 כ-`PASS_WITH_DEFERRAL` אם:
- וידאת שה-noindex מגיע מ-edge (כן ב-`curl -sI`)
- וידאת שב-`<meta name="robots">` של ה-HTML אין noindex (כלומר זה לא בקוד התמה)
- וידאת שכל יתר Lighthouse cats בערכים גבוהים: Performance ≥85, A11y ≥95, Best Practices ≥90

אחרת — T10 = FAIL.

### 4. ביקורת constitutional — design contract integrity

זה החלק הקריטי שאינו פשוט בדיקה טכנית. וודא:

**a) Shell HTML הוא תרגום 1:1 מ-JSX**
השווה `nimrod.bio/wp-content/themes/nimrod-bio-2026/template-parts/shell-nav.php` מול `sources/team_35_design_package/_handoff/templates/T1-data.jsx` lines 311–337.
- 8 קישורים בסדר הנכון (mark, home, soil, know, code, blog, about, contact)
- טקסט עברי מדויק:
  - "נימרוד ולד" + "nimrod.bio" (small)
  - "אדמה", **"ייעוץ והוראה"** (לא "ידע"!), "דיגיטל"
  - "בלוג", "על נמרוד", "צור קשר"
- aria-label="בית" על nav-home
- nav-sep span קיים
- אסור React/JSX residue (`className`, `onClick`, וכו')

**b) shell.css extraction נכון**
השווה `assets/css/shell.css` מול `T1-styles.css` lines 31–119 (nav) + 468–501 (footer).
- כל ה-selectors הקריטיים קיימים: `.shell-nav`, `.shell-mark`, `.nav-world.{soil,know,code}`, `.nav-world::before` (dots), `.is-active` variants, `.shell-foot .cols`, `.unless em`
- אסור שיעורי tweaks-panel/variant-bar/או T1-specific שלא ב-shell

**c) system.css verbatim**
diff `assets/css/system.css` מול `sources/team_35_design_package/_handoff/brand/system.css`.
- מותר רק comment header נוסף בתחילת הקובץ ("locked tokens copy...")
- שאר התוכן זהה byte-for-byte

**d) theme.json — 12 צבעים נעולים**
- `customDuotone: false`, `defaultPalette: false`
- בדיוק 12 entries בפלטה, ב-slugs המדויקים מ-LOD400 §4.2
- נכון hex לכל אחד מהם מ-system.css

**e) gitignore disposition**
- `nimrod.bio/wp-content/themes/nimrod-bio-2026/**` — tracked ✓
- `sources/team_35_design_package/**` — untracked (gitignored) ✓
- `.env.upress.dev` — untracked ✓

### 5. ביקורת SFA-FTPS procedure (חדש ב-WP002)

team_10 דיווח שהוסיף נוהל FTPS חדש: `docs/UPRESS_FTPS_MANDATORY_PROCEDURE.md` + `scripts/upress_ftps_upload.py` + עדכון `scripts/wp_dev_baseline.sh`. זה תוצר טוב **בצד** של ה-LOD400, לא בתוכו. וודא:
- הסקריפט פועל כאשר source-ים את `.env.upress.dev`
- הוא טוען credentials ולא מקודד אותם hard
- אין credentials או secrets בקוד עצמו
- הוא מתעד התנהגות נצפית (FTPS over port 21, TLS explicit, IP allowlist)

זה לא חוסם — אם יש הערות, אסוף ב-VERDICT עם `severity: advisory`.

### 6. validate_aos.sh

הרץ. expected: 0 net-new FAILs מעבר ל-2 ה-FAIL הידועים (Check 12 over-broad + Check 32 transient drift).

### 7. כתוב VERDICT

מקום: `_COMMUNICATION/team_190/VERDICT_NB-S002-P002-WP001_VALIDATE_v1.0.0.md`

מבנה (תקדים: `VERDICT_NB-S001-P002-WP001_VALIDATE_v1.0.0.md`):
```
---
type: VERDICT
from: team_190 (Codex)
to: team_100
wp_id: NB-S002-P002-WP001
date: <YYYY-MM-DD>
gate: L-GATE_VALIDATE
verdict: PASS | PASS_WITH_DEFERRALS | FAIL
---

# VERDICT — NB-S002-P002-WP001

## Summary
<one line>

## Test results — T1-T15
<table with PASS/FAIL/PARTIAL + evidence per row>

## Constitutional checks (LOD400 §7 + above)
<a/b/c/d/e + SFA FTPS notes>

## validate_aos.sh
<output snippet>

## Deferrals (if any)
<list>

## Recommended action
<one line>
```

## תזמון

- **התחל:** מיד.
- **יעד סיום:** יום עבודה אחד.
- **חוסם:** WP002-2 (CPTs) ו-WP003+ (כל ה-templates) עד שתחזיר VERDICT.

## Iron Rule #1 compliance

- Builder engine: **Cursor** (team_10, Anthropic Claude)
- Validator engine: **Codex** (team_190, OpenAI)
- Architect (me): **Cursor's Claude** (team_100)

קונפיגורציה זו עומדת ב-Iron Rule #1 — builder ≠ validator.

## Reference

- LOD400 (SSOT): `_aos/work_packages/NB-S002-P002-WP001/LOD400_NB-S002-P002-WP001.md`
- COMPLETION: `_COMMUNICATION/team_10/COMPLETION_NB-S002-P002-WP001.md`
- MANDATE: `_COMMUNICATION/team_10/MANDATE_NB-S002-P002-WP001_v1.0.0.md`
- Design package (read-only): `sources/team_35_design_package/_handoff/` (gitignored — re-extract via `ditto` if absent)
- Activation file: `_COMMUNICATION/team_190/ACTIVATION_VALIDATOR.md`
- Precedent VERDICT: `_COMMUNICATION/team_190/VERDICT_NB-S001-P002-WP001_VALIDATE_v1.0.0.md`

— team_100 (nimrod-bio) — 2026-05-25
