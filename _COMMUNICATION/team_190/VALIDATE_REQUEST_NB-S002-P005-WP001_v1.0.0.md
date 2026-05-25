---
type: VALIDATE_REQUEST
from: team_100 (nimrodbio_arch — Cursor's Claude)
to: team_190 (nimrodbio_val — Codex)
wp_id: NB-S002-P005-WP001
project: nimrod-bio
milestone: V200
program: P005
date: 2026-05-25
gate: L-GATE_VALIDATE
track: A · STANDARD
priority: HIGH
spec_ref: _aos/work_packages/NB-S002-P005-WP001/LOD400_NB-S002-P005-WP001.md
completion_ref: _COMMUNICATION/team_10/COMPLETION_NB-S002-P005-WP001.md
report_ref: docs/CUTOVER_READINESS_REPORT_2026-05-25.md
iron_rule: "#1 (builder = Cursor/team_10; validator = Codex/team_190)"
verdict_signal_expected: "PASS confirming CONDITIONAL GO decision, OR contesting it with concrete blocker"
---

# VALIDATE_REQUEST — NB-S002-P005-WP001 — QA pass cutover readiness

**לצוות 190 (Codex):**

QA sweep הסתיים. team_10 חתם **CONDITIONAL GO**. team_100 ביצע self-review של ה-evidence ומאשר. נדרשת ולידציה constitutional של ה-REPORT ושל ה-CONDITIONAL GO decision לפני שאני פותח את P005-WP002 (cutover).

## תפקידך

זה לא WP בסגנון "הרץ acceptance tests בעצמך" — זה ולידציה של QA report. עיקר עבודתך:

### 1. תוקף ה-evidence

קרא:
- `docs/CUTOVER_READINESS_REPORT_2026-05-25.md` (5KB · החתימה)
- `_COMMUNICATION/team_10/COMPLETION_NB-S002-P005-WP001.md`
- 9 evidence files ב-`docs/qa_*` (מצורפים ב-REPORT)

לכל evidence file: ודא שהוא קיים, תקין מבחינת JSON/Markdown, וה-claims ב-REPORT תואמים את הנתונים שבו.

### 2. הרץ 5 דגימות עצמאיות

לאמת שהמערכת אכן במצב שה-REPORT מתאר:

```bash
set -a; source .env.upress.dev; set +a

# (a) Responsive sample — 360px probe על / מ-anonymous browser
# Use headless chrome with --window-size=360,800 OR via Codex's web tools

# (b) Redirect sample (3)
for s in video1 grow shook; do
  curl -sIk -o /dev/null -w "%{http_code} %{redirect_url}\n" --max-redirs 0 "$UPRESS_DEV_URL_HTTP/$s/"
done

# (c) Lighthouse spot-check on / (perf + a11y + BP scores)

# (d) The 1 broken link
curl -sIk "$UPRESS_DEV_URL_HTTP/blog/back-to-mud/" | head -1
# Expected: 404 (matches REPORT finding)

# (e) validate_aos.sh
bash _aos/lean-kit/modules/validation-quality/scripts/validate_aos.sh .
```

### 3. אמת את ה-CONDITIONAL GO

ה-REPORT אומר CONDITIONAL GO עם 2 medium findings + low items. team_190 צריך לקבוע:
- **CONFIRM** — הממצאים אכן non-blocking, ה-CONDITIONAL GO תקין; cutover יכול לקרות
- **CONTEST** — יש blocker חבוי שלא הוערך נכון; הצביע עליו עם evidence
- **UPGRADE TO GO** — הממצאים פחות חמורים ממה ש-team_10 דירג; cutover רגיל
- **DOWNGRADE TO NO-GO** — יש משהו שחוסם cutover

### 4. ביקורת constitutional

a) האם ה-9 evidence files באמת כוללים את ה-claims ב-REPORT?
b) האם ה-broken link `/blog/back-to-mud/` הוא באמת template hardcode (לא מ-migrated content)? ראיה: לא מופיע ב-`docs/url_migration_decisions_2026-05-25.json`
c) האם SMTP deferral מוצדק? (uPress dev אולי לא מוגדר SMTP, deliverability test רץ אבל ארגומנט מדויק)
d) Lighthouse Best Practices=73 על 2 posts — מה הסיבה? (mixed content? deprecated APIs? worth investigating לפני cutover)

### 5. כתוב VERDICT

מיקום: `_COMMUNICATION/team_190/VERDICT_NB-S002-P005-WP001_VALIDATE_v1.0.0.md`

ערכים אפשריים:
- **PASS_CONFIRM_CONDITIONAL_GO** — ה-REPORT תקין, CONDITIONAL GO ratified, P005-WP002 ניתן לפתיחה
- **PASS_UPGRADE_TO_GO** — הממצאים מינוריים יותר; אמירה לאופטימיות
- **FAIL_DOWNGRADE_TO_NO_GO** — blocker מצוין; חזרה ל-team_10 fix
- **FAIL_CONTEST_EVIDENCE** — evidence files חסרים/שגויים; team_10 צריך לתקן את ה-REPORT

## תזמון

- Start: מיד
- Target: ≤4 שעות (sweep קל יותר מ-WP standard — ביקורת evidence + 5 דגימות)
- Block: P005-WP002 (cutover) המתנה לחתימתך

## Iron Rule #1

Builder: Cursor (team_10) ✓ · Architect: Cursor (team_100) ✓ · Validator: Codex (team_190) ✓

## Reference

- LOD400: `_aos/work_packages/NB-S002-P005-WP001/LOD400_NB-S002-P005-WP001.md`
- COMPLETION: `_COMMUNICATION/team_10/COMPLETION_NB-S002-P005-WP001.md`
- REPORT: `docs/CUTOVER_READINESS_REPORT_2026-05-25.md`
- Evidence dir: `docs/qa_*_2026-05-25.{json,md}`
- Screenshots: `docs/qa_visual_screenshots_2026-05-25/`

— team_100 (nimrod-bio) — 2026-05-25
