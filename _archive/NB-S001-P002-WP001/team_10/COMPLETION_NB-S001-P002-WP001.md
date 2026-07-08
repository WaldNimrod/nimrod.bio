---
type: COMPLETION
from: team_10 (nimrodbio_build)
to: team_100 (nimrodbio_arch)
wp_id: NB-S001-P002-WP001
date: "2026-05-10"
gate: L-GATE_BUILD
status: DONE
---

# COMPLETION — NB-S001-P002-WP001 — Environment Bootstrap

## סיכום

סביבת פיתוח מקומית לאתר nimrod.bio הוקמה לפי LOD400 §3 + MANDATE.
כל deliverables נכתבו, staged ב-git, ו-validate_aos.sh מסיים 0 FAIL.

---

## Deliverables שנוצרו

| קובץ | סטטוס |
|------|-------|
| `docker-compose.yml` | ✓ created — ports 8085/3309, WordPress 6.7-php8.3-apache, WPLANG=he_IL |
| `scripts/restore-production-from-backup.sh` | ✓ created — restore + WP-CLI search-replace |
| `scripts/verify-connections.sh` | ✓ created — smoke test per LOD400 §3.5 |
| `.gitignore` | ✓ created — LOD400 §3.4 canonical patterns |
| `nimrod.bio/wp-content/themes/.gitkeep` | ✓ staged |
| `nimrod.bio/wp-content/plugins/.gitkeep` | ✓ staged |
| `sources/.gitkeep` | ✓ staged |

---

## validate_aos.sh — תוצאה

```
RESULT: 32 PASS / 14 SKIP / 0 FAIL
L-GATE_BUILD EXIT CRITERION: SATISFIED
```

תאריך הרצה: 2026-05-10

---

## verify-connections.sh — תוצאה בפועל

> **סטטוס:** הורץ בהצלחה ב-2026-05-10 לאחר restore מ-`nimrod.bio_bm1778274742dm.zip`.

```
=== nimrod.bio — verify-connections (2026-05-10T15:14Z) ===
[PASS] Docker: nimrod-bio-wp is running
[PASS] Docker: nimrod-bio-db is running
[PASS] MySQL (container): SELECT 1 as wordpress
[PASS] HTTP local http://localhost:8085/ → 200
[PASS] REST http://localhost:8085/wp-json/ → 200 (JSON body)
[PASS] validate_aos.sh → 0 FAIL
=== Result: ALL_CHECKS_PASSED ===
```

**הפקודות שהורצו:**
```bash
docker compose up -d
bash scripts/restore-production-from-backup.sh
bash scripts/verify-connections.sh
```

**הערות restore:**
- ZIP: `sources/nimrod.bio_bm1778274742dm.zip` (3.2GB, full hosting backup)
- SQL: `databases/nimrodbi_sdblba.sql` (153MB, production DB snapshot 2026-05-09)
- Table prefix גולה: `qvj_` (לא `wp_` ← הוסף `WORDPRESS_TABLE_PREFIX=qvj_` ל-docker-compose)
- `agents/` + `Agents/` הוחרגו מה-rsync (cross-project boundary)
- WP-CLI search-replace: https://nimrod.bio → http://localhost:8085 ✓

---

## Local Dev URL

`http://localhost:8085`

---

## גרסת PHP

`PHP 8.3` (image: `wordpress:6.7-php8.3-apache` — מיושר עם uPress production)

---

## שמות תבניות ב-nimrod.bio/wp-content/themes/

```
flatsome/           ← תבנית ראשית (Flatsome)
flatsome-child/     ← תבנית ילד (8 קבצים: _style.css, footer.php, functions.php,
                       functions.php.bak, header.php, screenshot.png, sfagent-base.css, style.css)
twentytwentyfive/   ← תבנית ברירת מחדל WordPress
index.php           ← placeholder
```

**הערה:** `flatsome-child` בגיבוי מכיל 9 פריטים — 2 יותר מ-GitHub (Oct 2025):
- `functions.php.bak` — backup ישן של functions.php
- `sfagent-base.css` — CSS שנוסף ישירות על השרת (לא דרך ה-repo)

---

## Docker Stack

```yaml
name: nimrod-bio
services:
  db:   mysql:8.0      → port 3309
  wordpress: wordpress:6.7-php8.3-apache → port 8085
volumes: nimrod_bio_db_data
network: nimrod_bio_net
```

---

## חריגות מהמפרט

| נושא | חריגה | הסבר |
|------|-------|-------|
| WORDPRESS_TABLE_PREFIX | `qvj_` (לא `wp_`) | prefix ייצור שאינו ברירת מחדל — הוסף לdocker-compose |
| agents/, Agents/ | נוספו exclude לrsync ול-.gitignore | backup מלא של hosting מכיל תתיקיות של פרויקטים אחרים |

---

## הערות

- **Restore logic:** הסקריפט מחפש SQL בתוך ה-ZIP (כמו HobbitHome) ומציע fallback לקובץ `sources/*.sql` נפרד — לפי LOD400 §2.
- **אין patch-oshin:** nimrod.bio משתמש בתבנית שונה מ-Oshin — ה-patch הוסר ביחס ל-HobbitHome.
- **Iron Rules:** ports 8085/3309 נרשמו ב-docker-compose.yml. credentials לא נשמרים ב-git. `sources/` מוגדר ב-.gitignore.

---

*nimrod-bio | Team 10 → Team 100 | 2026-05-10*
