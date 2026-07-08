---
type: MANDATE
from: team_100 (nimrodbio_arch — Cursor's Claude)
to: team_10 (nimrodbio_build — Cursor)
wp_id: NB-S002-P002-WP002
project: nimrod-bio
milestone: V200
date: 2026-05-25
gate: L-GATE_SPEC PASS → entering L-GATE_BUILD
track: A · STANDARD
priority: HIGH
predecessor: NB-S002-P002-WP001 (COMPLETE — VERDICT v1.1.1 PASS_WITH_DEFERRALS)
successors_unblocked: NB-S002-P003-WP001..WP005 (5 parallel templates after this PASS)
authorization: team_00 approval 2026-05-25 (in-session)
spec_ref: _aos/work_packages/NB-S002-P002-WP002/LOD400_NB-S002-P002-WP002.md
---

# MANDATE — NB-S002-P002-WP002 — CPTs + taxonomies + meta boxes (native)

**לצוות 10 (Builder — Cursor):**

WP002 (theme skeleton) חתום ב-COMPLETE לאחר fix cycle 1 ו-PASS_WITH_DEFERRALS מצוות 190. עכשיו ה-WP השני בטרק STANDARD של V200 — שכבת הנתונים: 2 CPTs, 2 taxonomies, native meta boxes, REST exposure, ו-world page bootstrap.

## הקשר

ה-theme פעיל על dev (`http://nimrod-bio-2026.s887.upress.link`). Shell ו-Footer רנדרים. עכשיו צריך לתת לו נתונים — מה זה service, מה זה project, איך מסמנים world, ומה ההבדל בין flow_style. **בלי תוסף בתשלום** (Q5=D מ-team_00) — native PHP בלבד.

## המפרט המלא

🎯 **`_aos/work_packages/NB-S002-P002-WP002/LOD400_NB-S002-P002-WP002.md`**

זה ה-SSOT למשימה. קרא מקצה לקצה לפני שאתה כותב שורת קוד. הוא מכיל:

- §3 — 9 קבצים חדשים תחת `inc/` (כל אחד עם תפקיד אחד מובהק)
- §4 — 11 החלטות locked (slugs, repeater pattern, REST exposure, world page status)
- §5 — תוכן file-by-file (PHP מלא לקבצים 5.1-5.6 + 5.8-5.10; 5.7 spec עם דפוס מ-5.6)
- §6 — procedure פריסה + sanity checks
- §7 — 16 בדיקות acceptance (C1–C16)
- §8 — תהליך VALIDATE ע״י team_190
- §9 — out of scope מפורט
- §10 — risk register
- §11 — אומדן: 2.5 ימי עבודה

## כללי-זהב

1. **אסור תוסף.** לא ACF, לא Meta Box, לא Pods, לא CPT-UI. native PHP + WP API.
2. **שמירה ב-`_nb_*` post_meta** — לא serialized arrays, לא columns חדשים ב-DB.
3. **Repeater fields = JSON strings** — אין UI דינמי. team_00 יערוך JSON ידנית במקרה הנדיר שיערוך אז בכלל. רוב התוכן יעלה ע״י agent דרך REST.
4. **`world` taxonomy אינו מייצר archive URLs** (`rewrite => false`) — `/world/soil/` הוא **WP page**, לא term archive. ה-bootstrap script ב-§5.8 יוצר 4 pages.
5. **conditional fields תמיד מוצגים** — אין JS בכלל. labels בלבד מסבירים מתי הם רלוונטיים.
6. **NB_THEME_VERSION 0.1.1 → 0.2.0** — חתימה לכלל ה-flush ול-cache bust.
7. **REST exposure חובה** — כל `_nb_*` field עם `register_post_meta()` ו-`show_in_rest`. אחרת ה-agent לא יוכל לפרסם.
8. **בדיקת רגרסיה: C16 דורש שכל WP002 tests T1-T15 ימשיכו לעבוד.** אם משהו שובר את Shell/Footer/Fonts — סטייה ממשמעת.

## Activation flow (תזכורת מ-LOD400 §6)

```bash
# 1. כתוב 9 קבצים תחת inc/ + עדכן functions.php (require_once + bump version)
# 2. העלה ב-FTPS:
set -a; source .env.upress.dev; set +a
python3 scripts/upress_ftps_upload.py \
    nimrod.bio/wp-content/themes/nimrod-bio-2026/functions.php \
    nimrod.bio/wp-content/themes/nimrod-bio-2026/inc/

# 3. הפעל מחדש את ה-theme (כדי לטרגר after_switch_theme bootstrap):
curl -X POST -u "$WP_REST_USER:$WP_REST_APP_PASSWORD" \
     "$WP_REST_BASE_URL/wp/v2/themes/nimrod-bio-2026" \
     -H "Content-Type: application/json" \
     -d '{"status":"active"}'

# 4. הרץ את 16 בדיקות C1–C16
```

## Exit criteria — L-GATE_BUILD

חזרה לטיפול team_100 כש-COMPLETION report ב-`_COMMUNICATION/team_10/COMPLETION_NB-S002-P002-WP002.md` כולל:

- [ ] 9 קבצי `inc/` קיימים על השרת + tracked ב-git
- [ ] functions.php עודכן (9 require_once + `NB_THEME_VERSION = '0.2.0'`)
- [ ] 16 בדיקות C1–C16 כולן PASS, עם evidence (curl outputs, REST responses, screenshots ל-admin UI)
- [ ] `wp_options.nb_theme_rewrite_version = '0.2.0'`
- [ ] `validate_aos.sh` — 0 net-new FAILs
- [ ] git: commit + push (כולל היפוך תקין של theme version וכל הקבצים החדשים)
- [ ] לוג קצר של כל סטיות מ-LOD400

## L-GATE_VALIDATE

**STANDARD track — דרוש.** team_190 (Codex) יקבל VALIDATE_REQUEST לבדיקת C1–C16 + ביקורת constitutional של:
- אין plugin חדש פעיל (`wp plugin list` או REST equivalent)
- ה-`_nb_*` schema תואם design §3 (כל שדה ב-spec → field ב-meta-registration)
- slugs מדויקים: `services` (plural), `project` (singular), `world` (singular)
- REST endpoints נחשפים בדיוק עם הסכמה הצפויה

## תזמון

- **Start:** מיד.
- **Target completion:** 2.5 ימי עבודה (LOD400 §11).
- **Status updates:** ב-`_COMMUNICATION/team_10/` אם חוסם משהו מעבר ל-4 שעות.

## תלות פתוחה — לא חוסמת

- 5 screenshots של uPress control panel (audit) — team_00 carry-over
- Basic auth ב-edge — team_00 carry-over
- T10 SEO carry-over ל-P005-WP001

## Reference

- LOD400 (SSOT): `_aos/work_packages/NB-S002-P002-WP002/LOD400_NB-S002-P002-WP002.md`
- LOD300 milestone: `_aos/work_packages/S002/LOD300_V200_milestone.md`
- WP002 COMPLETION (תקדים): `_COMMUNICATION/team_10/COMPLETION_NB-S002-P002-WP001.md`
- Design package CPT spec: `sources/team_35_design_package/_handoff/00-HANDOFF-claude-code-110.md` §3
- Team 00 Q5 decision (native, no plugin): `_COMMUNICATION/team_00/DECISION_V200_OPEN_QUESTIONS_2026-05-25_v1.0.0.md`
- DEV env: `.env.upress.dev`
- FTPS canonical: `docs/UPRESS_FTPS_MANDATORY_PROCEDURE.md`

— team_100 (nimrod-bio) — 2026-05-25
