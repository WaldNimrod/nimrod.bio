---
id: MANDATE_WAVE_04_PROJECTS_T3_2026-06-24_v1
type: WAVE_MANDATE
from: team_100
wave: wave-04-projects
template: T3
date: 2026-06-24
status: planned
prerequisite: waves_01-03_owner_approved
owner_decisions: OWNER_DECISIONS_SITE_ARCHITECTURE_2026-06-23_v1
---

# מנדט גל 04 — פרויקטים (T3)

## מטרה

גל חמישי במודל 6 הגלים — **5 עמודי פרויקט** (תבנית T3). כל עמוד = סיפור/מערכת קונקרטית, לא שירות.

**תנאי פתיחה:** גלים 01–03 עברו ביקורת JSON + אישור נמרוד. BCS case דורש `owner-supply` מלא.

**חבילת שלד:** `content-drafts/waves/wave-04-projects/`

---

## עמודים

| page_id | URL | עולם | מקור | סטטוס כתיבה |
|---------|-----|------|------|-------------|
| bcs-case | `/project/bcs-client-case/` [slug TBC] | soil | `MANDATE_WRITE_BCS_CASE_T3` | **חסום** — פרטי חווה |
| sfa-project | `/project/sfa/` | code | SITE_COPY_SFA | ממתין dispatch |
| garden | `/project/nimrodsgarden/` | soil | SITE_COPY_PROJECT_GARDEN | ממתין dispatch |
| greenhouse | `/project/rest-x-greenhouse/` | soil | SITE_COPY_FIXES | ממתין dispatch |
| tiktrack | `/project/tiktrack/` | code | SITE_COPY_TIKTRACK | ממתין dispatch |

---

## זרימת גל (כמו 02–03)

```
מנדט גל 04 → 5× (Composer + gpt-5.5) → COMPARISON → MERGED
    → HTML bundle wave-04-projects → ביקורת נמרוד → רוויזיה → אישור
```

**חריג:** BCS case — סשן יחיד אחרי אספקת חווה (לא dual-engine עד שיש עובדות).

---

## מנדטים per-page (ליצירה ב-dispatch)

| עמוד | קובץ מנדט |
|------|-----------|
| BCS case | `MANDATE_WRITE_BCS_CASE_T3_2026-06-23_v1.md` (קיים) |
| SFA project | `MANDATE_WRITE_SFA_PROJECT_T3` — ליצור ב-dispatch |
| Garden | `MANDATE_WRITE_PROJECT_GARDEN_T3` — ליצור |
| Greenhouse | `MANDATE_WRITE_PROJECT_GREENHOUSE_T3` — ליצור |
| TikTrack | `MANDATE_WRITE_PROJECT_TIKTRACK_T3` — ליצור |

---

## נעילות גל

- TikTrack ≠ חקלאות
- BCS case = נרטיב יום עבודה, לא קייס שיווקי
- SFA project (T3) ≠ SFA service (T2 · גל 01)
- slug גינה: `nimrodsgarden` (נעול)
- אין המצאת מספרים — `[TBC]` עד אספקה

---

## פרישה / redirects (בהטמעה)

| מקור | יעד |
|------|-----|
| `/project/farm-y-bcs/` | slug סופי BCS case |
| `/shook/` | `/about/heritage/#shook` |
| `restaurant-supply` | גל עתידי או 410 |

---

## משימות team_100 (מיידי — שלד)

- [x] מנדט גל (מסמך זה)
- [x] `wave-04-projects/index.html` + `wave-manifest.json`
- [ ] מנדטים per-page (ב-dispatch)
- [ ] dispatch team_70 — **אחרי** אישור גלים 01–03 + BCS supply
