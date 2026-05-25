---
type: REQUEST
from: team_100 (nimrodbio_arch)
to: team_00 (Nimrod)
project: nimrod-bio
milestone: V200
wp_id: NB-S002-P004-WP001
date: 2026-05-25
priority: HIGH (blocks WP001 start)
---

# REQUEST — Production Application Password for migration

צוות 10 צריך גישת READ ONLY ל-prod כדי למשוך פוסטים. נדרשת ממך פעולה ידנית של ~5 דקות.

## פעולה נדרשת

1. כנס: `https://nimrod.bio/wp-admin/profile.php`
2. גלול ל-**"Application Passwords"** (בתחתית הדף)
3. שם האפליקציה: `aos-migration-readonly`
4. לחץ "Add New Application Password"
5. WordPress יציג סיסמה בפורמט: `abcd EFGH 1234 wxyz 5678 ijkl`
   ⚠️ **עם הרווחים** וזה מוצג **פעם אחת בלבד**.

## צור קובץ חדש `.env.upress` בשורש הפרויקט

`/Users/nimrod/Documents/nimrod-bio/.env.upress` (לא `.env.upress.dev`!)

עם התוכן:

```bash
# Production credentials — READ ONLY for migration
PROD_URL=https://nimrod.bio
PROD_REST_BASE=https://nimrod.bio/wp-json
PROD_REST_USER=<your-prod-admin-username>
PROD_REST_APP_PASSWORD='abcd EFGH 1234 wxyz 5678 ijkl'

# FTP (for media transfer fallback)
PROD_FTP_HOST=
PROD_FTP_USER=
PROD_FTP_PASS=
```

הקובץ כבר ב-.gitignore (`.env.upress` נוסף יחד עם `.env.upress.dev`).

## אימות

לאחר יצירה, אריץ בעצמי:
```bash
set -a; source .env.upress; set +a
curl -sk -u "$PROD_REST_USER:$PROD_REST_APP_PASSWORD" \
  "$PROD_REST_BASE/wp/v2/users/me" | head -c 200
```

תגובה תקינה תכיל את שם המשתמש שלך → אאשר ב-COMPLETION של team_10.

## למה הסיסמה צריכה להיות חדשה (ולא לעשות reuse של dev)

Application Passwords הן per-site. הסיסמה של dev (`T4nT gKoe MWpf EdST iWty oiGE`) עובדת רק על `nimrod-bio-2026.s887.upress.link`. עבור prod (`nimrod.bio`) צריך סיסמה חדשה מ-prod.

## נימוק זמן

ה-WP חוסם רק לאחר Phase 2 (fetch). team_10 יכול להתחיל Phase 1 הכנות + לכתוב את הסקריפטים במקביל. אבל ללא הסיסמה — Phase 2 לא יוצא.

תגיד "מולא" כשהסיסמה ב-`.env.upress`. עד אז — team_10 יכין infrastructure ולא ימשך לפעולות נטוורק.

— team_100 (nimrod-bio) — 2026-05-25
