---
type: FOLLOW_UP_TASK
from: team_110 (Domain Architect)
to: team_00 (Principal — owner authority required)
project: nimrod-bio
date: 2026-05-26
priority: P3 (not blocking V200; nice-to-have for audit/tracking)
estimated_effort: 30 seconds (one curl)
---

# Follow-up — Register `nimrod-bio` as canonical domain in AOS DB

## Why

ה-API של AOS שומר רישום domains ב-canonical DB עם ULIDs. כרגע ב-DB רשומים רק `agents-os` ו-`tiktrack`. ה-spoke `nimrod-bio` קיים ב-`projects.yaml` ועל הדיסק אבל לא ב-DB.

**ההשלכה:** אי-אפשר לרשום WPs של nimrod-bio ב-canonical DB (כל קריאה ל-`POST /api/work-packages` עם `domain_id: "nimrod-bio"` מחזירה 404 DOMAIN_NOT_FOUND).

**זה לא חוסם את V200.** הבנייה (תוכן + theme + tests) מתבצעת מול WordPress, לא מול AOS DB. ה-DB record הוא audit/tracking בלבד.

## Authority

`POST /api/projects` דורש authority `owner` ב-L-GATE_SPEC. team_110 (`delegated`) → 403. **רק team_00 יש לו authority הזה.**

## פעולה — one curl, ~30 שניות

```bash
KEY_00=$(ssh -T waldhomeserver 'grep -E "^AOS_V3_ACTOR_KEYS=" /data/projects/agents-os/core/.env | python3 -c "import sys,json; line=sys.stdin.read().strip(); val=line.split(chr(61),1)[1].strip().strip(chr(34)); print(json.loads(val)[chr(116)+chr(101)+chr(97)+chr(109)+chr(95)+chr(48)+chr(48)])"' 2>/dev/null)

curl -s -X POST "http://100.125.98.56:8090/api/projects" \
  -H "Content-Type: application/json" \
  -H "X-Actor-Team-Id: team_00" \
  -H "X-Actor-Api-Key: $KEY_00" \
  -H "X-Project-Id: nimrod-bio" \
  -d '{
    "project_id": "nimrod-bio",
    "display_name": "nimrod.bio — Personal Site (WordPress)",
    "local_path": "/Users/nimrod/Documents/nimrod-bio",
    "profile": "L0",
    "lifecycle_archetype": "SOFTWARE",
    "owner": "team_00",
    "github_repo": "WaldNimrod/nimrod.bio"
  }' | python3 -m json.tool

unset KEY_00
```

**הערה:** ה-key לקוח מהשרת (לא מה-Mac copy שמיושן — ראה memory `feedback_aos_api_base_resolution` עדכון מתוכנן).

## אחרי הרצה

ברגע שה-domain רשום, אני (team_110) מבצע את ה-`POST /api/work-packages` לרישום `S002-P006-WP001`. שניהם יחד = ~1 דקה.

**אם בחרת לא להריץ:** בסדר. הבאצ' מסתיים בלי DB record; team_99 כותב ב-COMPLETION:
```
wp_registration_status: PENDING_DOMAIN_REGISTRATION
follow_up_artifact: _COMMUNICATION/team_00/FOLLOW_UP_nimrod-bio_domain_registration_2026-05-26_v1.0.0.md
```

— team_110 — 2026-05-26
