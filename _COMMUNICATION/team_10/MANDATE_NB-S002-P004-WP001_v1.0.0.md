---
type: MANDATE
from: team_100 (nimrodbio_arch — Cursor's Claude)
to: team_10 (nimrodbio_build — Cursor)
wp_id: NB-S002-P004-WP001
project: nimrod-bio
milestone: V200
program: P004 — content migration + redirects
date: 2026-05-25
gate: L-GATE_SPEC PASS → L-GATE_BUILD
track: A · STANDARD
priority: HIGH
predecessor: NB-S002-P003-WP005 (COMPLETE)
spec_ref: _aos/work_packages/NB-S002-P004-WP001/LOD400_NB-S002-P004-WP001.md
prod_creds: .env.upress (verified auth 2026-05-25 — NimrodAdmin, 24 posts + 7 pages on prod)
---

# MANDATE — NB-S002-P004-WP001 — Content migration

**לצוות 10 (Builder — Cursor):**

P003 cascade נסגר. כל 7 ה-templates עובדים. עכשיו ממלאים אותם בתוכן אמיתי.

## הקשר

- `.env.upress` קיים, מאומת. NimrodAdmin authenticated, 24 posts + 7 pages זמינים על prod.
- triage decisions ב-`docs/url_migration_decisions_2026-05-25.json` כולל 23 redirects (כולם post או 1 page heritage) + 2 keeps (pages: shook, blog) + 6 drops.
- ⚠️ **חובה להשתמש ב-`https://www.nimrod.bio/wp-json`** (לא `nimrod.bio`) — see `feedback_prod_www_redirect`.

## המפרט המלא

🎯 **`_aos/work_packages/NB-S002-P004-WP001/LOD400_NB-S002-P004-WP001.md`**

קרא מקצה לקצה. הוא מכיל:
- §3 — 7 deliverables (6 scripts ב-`scripts/migration/` + HTML tagging tool)
- §4 — 6 phases (Phase 1 כבר בוצע by team_00 — provision creds)
- §5 — 19 בדיקות acceptance M1-M19
- §7 — risk register (CF rate-limit, Hebrew slugs, page collision /blog/)

## פעולות מוכנות לפניך

✅ Phase 1 — prod creds (`.env.upress`) — DONE  
🔧 Phase 2 — fetch from prod (write `scripts/migration/fetch_prod_posts.py`)  
🔧 Phase 3 — generate tagging triage tool (`docs/content_tagging_triage.html`)  
⏸ Phase 4 — wait for team_00 to fill tagging triage (~10 דק׳)  
🔧 Phase 5 — import to dev + uploads transfer  
🔧 Phase 6 — cleanup 4 P003-WP004 seed posts  

## כללי-זהב ספציפיים ל-P004

1. **prod REST URL חייב להיות `www.nimrod.bio`** — אחרת 308→401 (תיעדתי ב-`feedback_prod_www_redirect`)
2. **READ ONLY מ-prod** — אסור POST/PATCH/DELETE לעולם על `nimrod.bio`
3. **חיסכון media** — wget רק תמונות שמתייחסים בגוף הפוסטים (לא כל 991MB)
4. **שמור post_date מקורי** — לא להחליף ל-now()
5. **slug חדש = `new_url` מ-triage JSON** (e.g. `/blog/יום-בגינה/` במקום `video1`)
6. **מארק `_nb_seed='v200-migrated'`** — מבדיל מ-seeds הישנים של WP004 (`_nb_seed='v200'`)
7. **page /blog/ — אל תייבא** (collision עם WP posts archive). page /shook/ — כן.
8. **page heritage (id 2516) — אל תייבא** (תוכן hardcoded ב-T8 page-heritage.php). רק 301 ימפה את ה-URL הישן.
9. **2 ה-keeps + 22 ה-redirects = 23 ייבואים בפועל** (drop כל 6 ה-drops)
10. **WP002-2 lesson: git add + commit + push לפני COMPLETION**
11. **WP004 lesson: cleanup test records before COMPLETION** (test artifacts, NOT the migrated content!)

## Activation flow מומלץ

```bash
# Day 1
set -a; source .env.upress; set +a
set -a; source .env.upress.dev; set +a
# Write phase 2 + run
python3 scripts/migration/fetch_prod_posts.py
# Write phase 3
python3 scripts/migration/tagging_export.py
# Open docs/content_tagging_triage.html — verify rendering, await team_00 input

# Hand back to team_100 with tagging_input.json + HTML, request team_00 to tag

# Day 2 (after team_00 returns tagging JSON)
python3 scripts/migration/import_to_dev.py
python3 scripts/migration/uploads_transfer.py
python3 scripts/migration/cleanup_seeds.py

# Day 3
# Verify M1-M19, write COMPLETION, git push
```

## Exit criteria

ב-`_COMMUNICATION/team_10/COMPLETION_NB-S002-P004-WP001.md`:
- [ ] 6+ scripts ב-`scripts/migration/` tracked + תוצרי run
- [ ] `docs/content_tagging_triage.html` קיים, נפתח, נטען עם 22 פוסטים (ראיה: screenshot או curl)
- [ ] `docs/content_tagging_decisions_<date>.json` מ-team_00 (אחרי tagging)
- [ ] M1-M19 PASS עם evidence
- [ ] `.migration-cache/id_mapping.json` קיים — כל prod_id → dev_id
- [ ] 22 פוסטים + 1 page (shook) על dev
- [ ] 4 ה-WP004 seeds נמחקו
- [ ] uploads referenced resolved (תמונות עובדות)
- [ ] `validate_aos.sh` 0 net-new FAILs
- [ ] git push

## L-GATE_VALIDATE

cross-engine ע״י team_190. אחרי PASS — נפתח את WP002 (301 enforcement).

## תזמון

- Start: מיד (Phase 1 קרה כבר).
- Target: 3 ימי עבודה.
- Block: P004-WP002 + P005 ממתינים על PASS.

— team_100 (nimrod-bio) — 2026-05-25
