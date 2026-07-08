---
type: VALIDATE_REQUEST
from: team_100 (nimrodbio_arch — Cursor's Claude)
to: team_190 (nimrodbio_val — Codex)
wp_id: NB-S002-P002-WP002
project: nimrod-bio
milestone: V200
date: 2026-05-25
gate: L-GATE_VALIDATE
track: A · STANDARD
priority: HIGH
spec_ref: _aos/work_packages/NB-S002-P002-WP002/LOD400_NB-S002-P002-WP002.md
completion_ref: _COMMUNICATION/team_10/COMPLETION_NB-S002-P002-WP002.md
build_commit: f386c187
iron_rule: "#1 (builder = Cursor/team_10/Anthropic; validator = Codex/team_190/OpenAI)"
---

# VALIDATE_REQUEST — NB-S002-P002-WP002 — CPTs + taxonomies + meta boxes

**לצוות 190 (Codex):**

WP002-2 הוחזר ע״י team_10 (Cursor) עם 9 קבצי `inc/` חדשים + עדכון functions.php (`NB_THEME_VERSION = 0.2.0`). team_100 ביצע self-review עצמאי וכל C1-C6 + regression PASS. וועיצב את ה-git tracking שhem ה-team_10 פספס (commit `f386c187`). נדרשת ולידציה constitutional + replay של C1-C16.

## תפקידך

ולידציה עצמאית של 16 בדיקות C1-C16 שמוגדרות ב-LOD400 §7 + ביקורת constitutional של חוזה ה-CPT.

### 1. קבל את הספק

קרא:
- `_aos/work_packages/NB-S002-P002-WP002/LOD400_NB-S002-P002-WP002.md` (SSOT — 9 קבצים, 11 LOD400 decisions, 16 acceptance tests)
- `_COMMUNICATION/team_10/COMPLETION_NB-S002-P002-WP002.md` (דיווח team_10, כולל הסטייה הידועה של POST /wp/v2/themes/...)
- `_COMMUNICATION/team_10/MANDATE_NB-S002-P002-WP002_v1.0.0.md` (ההזמנה)

### 2. הצב את הסביבה

```bash
git fetch && git log --oneline -3   # should include f386c187
set -a; source .env.upress.dev; set +a
```

### 3. הרץ C1–C16 עצמאית

הרץ באופן עצמאי, אל תסתמך על מספרי team_10 — evidence חדשה.

טיפ: C1-C7 ניתן לבדוק מהירות עם:
```bash
curl -sk "$WP_REST_BASE_URL/wp/v2/services" | python3 -m json.tool | head -20
curl -sk "$WP_REST_BASE_URL/wp/v2/projects" | python3 -m json.tool | head -20
curl -sk "$WP_REST_BASE_URL/wp/v2/world"
curl -sk "$WP_REST_BASE_URL/wp/v2/flow_style"
for w in soil know code; do curl -sIk "$UPRESS_DEV_URL_HTTP/world/$w/" | head -1; done
```

C8/C9 (admin meta box) דורש browser automation או manual screenshot. team_10 השתמש ב-wp-admin automation — אישור פונקציונלי שלהם מקובל אם תוכל לאמת את העובדה שהשדות נשמרים דרך REST GET (כי REST exposes כל `_nb_*` meta).

C10-C12 (REST POST):
```bash
# Create test service
curl -X POST -u "$WP_REST_USER:$WP_REST_APP_PASSWORD" \
  "$WP_REST_BASE_URL/wp/v2/services" \
  -H "Content-Type: application/json" \
  -d '{"title":"validate-test","status":"publish","meta":{"_nb_tagline":"קיבולת בדיקה"}}'
# → expect 201 with id

# Verify meta exposure
curl -sk "$WP_REST_BASE_URL/wp/v2/services/{id}" | python3 -c "import json,sys;d=json.load(sys.stdin);print('_nb_tagline:', d.get('meta',{}).get('_nb_tagline'))"

# Attach world term
curl -X POST -u "$WP_REST_USER:$WP_REST_APP_PASSWORD" \
  "$WP_REST_BASE_URL/wp/v2/services/{id}" \
  -H "Content-Type: application/json" \
  -d '{"world":[<soil_term_id>]}'

# Cleanup test record after (DELETE)
```

C13 (`wp_options.nb_theme_rewrite_version`):
דרך REST אין endpoint ישיר. ניתן לאמת בעקיפין: אם permalinks חדשים עובדים (`/services/x/`, `/project/x/`, `/world/soil/`) זה אומר שהrewrites הוטמעו. team_10 דיווח שזה ב-options.php.

### 4. ביקורת constitutional

**a) אין plugin חדש פעיל**

```bash
curl -sk -u "$WP_REST_USER:$WP_REST_APP_PASSWORD" "$WP_REST_BASE_URL/wp/v2/plugins" | python3 -c "import json,sys;d=json.load(sys.stdin);print([p['plugin'] for p in d if p.get('status')=='active'])"
```
Expected: רק MU plugin אחד (`nb-dev-app-passwords`). אם יש ACF/MetaBox/Pods/CPT-UI — **FAIL**.

**b) `_nb_*` schema תואם design §3**

עבור על `inc/meta-registration.php` ועבור על handoff §3:
- service: tagline, lede, service_type, stage, cta_label, cta_whatsapp_href, is_anchor_for_world, is_free, linked_projects, related_posts, sections, meta_strip
- project: scope, stage, year, location, duration, summary, seeking_note, legacy_of, name_tbc, linked_services, gallery, more_projects_ids, outcomes

ודא: כל שדה ב-spec → `register_post_meta()` ב-meta-registration.php. **אסור** שדות שלא בספק.

**c) slugs מדויקים**

- `services` (plural) — service archive slug
- `project` (singular) — project archive slug
- `world` (singular) — taxonomy slug AND world parent page slug
- `flow_style` (singular underscore) — taxonomy slug

אם יש סטייה — **FAIL**.

**d) `world` taxonomy לא יוצר archive URLs**

```bash
curl -sIk "$UPRESS_DEV_URL_HTTP/?world=soil" | head -1
# צריך לא לרנדר archive — או 404 או redirect
```
ה-`/world/soil/` שעובד הוא WP page (לא term archive). זה לפי spec §4.

**e) world parent page private status**

```bash
curl -sIk "$UPRESS_DEV_URL_HTTP/world/" | head -1
# expected: 404 (private status, not publicly listed)
```
אם רואים 200 ציבורי — סטייה. אם 404 — תקין.

**f) test data ניקוי**

team_10 יצר רשומת בדיקה ב-services (C2 שלי הראה `len=1`). ודא ש-team_10 או ניקה אותה או הסביר ב-COMPLETION למה היא שם. אם זו רשומה תקינה עם תוכן real — flag כadvisory.

### 5. validate_aos.sh

הרץ. expected: 0 FAIL post-commit `f386c187`.

### 6. סטייה ידועה ב-COMPLETION

team_10 דיווח: "POST /wp/v2/themes/... מחזיר rest_no_route — workaround שקול פונקציונלית (bootstrap world pages דרך REST מאומת)".

ודא: zה לא משפיע על תוצאות C1-C16. אם ה-bootstrap עבד דרך הworkaround וכל ה-pages קיימים — מקובל. אם משהו לא עבד — לסמן.

### 7. כתוב VERDICT

מקום: `_COMMUNICATION/team_190/VERDICT_NB-S002-P002-WP002_VALIDATE_v1.0.0.md`

מבנה (תקדים: VERDICT_NB-S002-P002-WP001_VALIDATE_v1.1.1.md):
```
---
verdict: PASS | PASS_WITH_DEFERRALS | FAIL
---

# VERDICT
## Summary
<one line>
## Test results — C1-C16
<table with evidence per row>
## Constitutional checks (a/b/c/d/e/f)
<results>
## validate_aos.sh
<output>
## Deferrals (if any)
## Recommended action
```

## תזמון

- **התחל:** מיד.
- **יעד:** יום עבודה אחד.
- **חוסם:** P003 templates cascade (5 parallel WPs) עד שתחזיר.

## Iron Rule #1

- Builder: Cursor (team_10) ✓
- Architect: Cursor (team_100) ✓
- **Validator: Codex (team_190)** ✓ cross-engine maintained

## Reference

- LOD400: `_aos/work_packages/NB-S002-P002-WP002/LOD400_NB-S002-P002-WP002.md`
- COMPLETION: `_COMMUNICATION/team_10/COMPLETION_NB-S002-P002-WP002.md`
- MANDATE: `_COMMUNICATION/team_10/MANDATE_NB-S002-P002-WP002_v1.0.0.md`
- Design spec: `sources/team_35_design_package/_handoff/00-HANDOFF-claude-code-110.md` §3
- Build commit: `f386c187` (origin/master)
- Prior VERDICT (precedent): `_COMMUNICATION/team_190/VERDICT_NB-S002-P002-WP001_VALIDATE_v1.1.1.md`

— team_100 (nimrod-bio) — 2026-05-25
