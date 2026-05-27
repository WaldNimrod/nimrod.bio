---
id: MSG-HUB-20260526-004
from_team: team_110
to_team: team_99
type: instruction
subject: ".env.upress.dev delivered to server; AT-1 / AT-3 / AT-7 expectations adjusted per your findings"
date: 2026-05-26
related_wp: NB-S002-P006-WP001
expects_response: false
---

# Unblock + acceptance test adjustments

## Block resolved
`.env.upress.dev` נמסר מהמק שלי (team_110 session) ל:
`/data/projects/nimrod-bio/nimrod.bio/.env.upress.dev`
(הנתיב המקונן שיצרת ב-PRE-0 — parent `nimrod-bio/` + nested git clone `nimrod.bio/`)

המשך עם §5.5 (`seed_wp003_instances.py` + `seed_wp006_p006_wp001_placeholders.py`) מיד.

## Findings 4-7 — ruling

### Finding 4 — SFA CTA in CPT meta, not theme PHP
**אישור:** הגישה שלך נכונה. עריכת `_nb_cta_label` ב-CPT meta היא הדרך הקאנונית, לא edit ב-theme PHP. ה-MANDATE שלי הניח tem template-level edit — שגיאה. הפתרון שלך (seed_wp003_instances.py) נכון.

### Finding 5 — AT-1 "Unless" count adjustment
**ROOT CAUSE:** ה-MANDATE שלי כתב "4+ matches". זה היה ספקולציה שלי מבוסס MISSION_BRIEF. בפועל יש 2 occurrences ב-PHP + Yoast meta separate.

**AT-1 חדש:**
- Pass criterion: ≥2 PHP occurrences (page-heritage.php, template-parts/shell-footer.php), כולן literal "Unless"
- Yoast meta: verify separately דרך wp-admin (אם כך — דווח כסעיף-משנה ב-COMPLETION; לא חוסם)

### Finding 6 — AT-3 Mezoo count
**ROOT CAUSE:** הנחתי footer + about. בפועל רק footer.

**AT-3 חדש:**
- Pass criterion: **1 occurrence** ב-shell-footer.php (לא 2)
- אין צורך להוסיף Mezoo ל-page-about.php. Q8=A ("sub-brand mention only") — footer credit מספיק.

### Finding 7 — AT-7 back-to-mud
ידע. ייפתר אוטומטית כש-post 11 (`back-to-mud` slug) ייצור ב-§5.5.

### Finding 8 — CLAT broken on server
ידע. **אל תפתח** ops ticket במסגרת הבאצ' הזה. אעלה את זה כ-FOLLOW_UP ל-team_60 (Infra) בנפרד אחרי COMPLETION. ה-`/etc/hosts` workaround שלך מקובל לסשן הזה.

## Acceptance criteria revised (קופי ל-COMPLETION §5.8)

| # | Old | New |
|---|---|---|
| AT-1 | "Unless tagline 4+ matches" | "Unless tagline ≥2 PHP + Yoast meta noted separately" |
| AT-3 | "Mezoo grep count = 2" | "Mezoo footer count = 1" |
| AT-7 | "back-to-mud HTTP 200" | (unchanged — auto-resolves post §5.5) |

המשך הלאה — צפי ל-COMPLETION תוך ~30 דקות אחרי §5.5 run.

— team_110 — 2026-05-26
