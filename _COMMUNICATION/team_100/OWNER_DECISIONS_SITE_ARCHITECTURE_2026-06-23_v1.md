---
id: OWNER_DECISIONS_SITE_ARCHITECTURE_2026-06-23_v1
type: OWNER_DECISIONS
owner: nimrod
date: 2026-06-23
scope: site_structure · waves_04+
---

# החלטות ארכיטקטורה — מבנה עמודים (נמרוד 2026-06-23)

## 1 · BCS — שירות + דוגמת חווה (T3)

| שכבה | URL | תבנית | תפקיד |
|------|-----|--------|--------|
| **שירות** | `/services/bcs/` | T2 | מה זה BCS, כלים, מחירון, CTA — `SITE_COPY_BCS_v1` (גל 01) |
| **דוגמה מהשטח** | `/project/bcs-client-case/` [slug סופי TBC] | T3 · client-case | חווה אחת שתספק — נרטיב עבודה, לא קייס-סטדי שיווקי |

**מימוש:**
- לפרוש `/project/farm-y-bcs/` (תוכן שגוי היום) → slug חדש כשם החווה מאושר.
- קישור דו-כיווני: שירות BCS ↔ עמוד הדוגמה.
- **עכשיו:** מנדט + שלד תבנית T3 — [`MANDATE_WRITE_BCS_CASE_T3_2026-06-23_v1.md`](../team_70/MANDATE_WRITE_BCS_CASE_T3_2026-06-23_v1.md). קופי `[TBC]` עד פרטי החווה.

---

## 2 · SFA — פרויקט (קוד) + שירות (אדמה)

| שכבה | URL | תבנית | עולם | תפקיד |
|------|-----|--------|------|--------|
| **פרויקט** | `/project/sfa/` | T3 | דיגיטל (`code`) | המיזם, מודולים, קהילה, סטטוס rollout |
| **שירות** | `/services/sfa/` | T2 | אדמה (`soil`) | מה SFA נותן לחקלאי/חווה קטנה — זווית שטח |

**הערה לגל 01:** `sfa` בחבילת wave-01-services = זווית **שירות/אדמה**. עמוד הפרויקט → **גל 04**. ליישר `/services/sfa/` (כיום 404 בחלק מה-QA) בהטמעה.

---

## 3 · שירותים — איחוד ופיצול

| החלטה | עמוד(ים) | פעולה |
|--------|----------|--------|
| **הוראה בנפרד** | `/services/teaching/` | נשאר עמוד T2 עצמאי |
| **חקלאות — לאחד** | `/services/consulting-agro/` (עוגן) | למזג: `consulting-agro` + `hydro-greenhouse` (תשתית/חממה כחלק, לא עמוד נפרד ריק) |
| **משתלה — לאחד** | בתוך עמוד החקלאות המאוחד | סקשן/פסקה — רקע, לא עסק; לא `/services/nursery/` עצמאי |
| **ללא שינוי בגל 01 הנוכחי** | `consulting-hydro`, `produce`, `bcs` | נשארים כפי שב-wave-01 |

**לפרוש:** redirect מ-`/services/nursery/`, `/services/hydro-greenhouse/` → `/services/consulting-agro/` (או 410 אם אין תנועה).

---

## 4 · שוק הירוקה (`/shook/`) — לשלב

**לא** עמוד שורש עצמאי לטווח ארוך.

| פעולה | יעד |
|--------|-----|
| תוכן | לשלב ב-**מורשת הגינה** — §03 לקוחות / גלריה / משפט על ימי שוק |
| SEO | 301 מ-`/shook/` → `/about/heritage/` (עוגן `#shook` או סקשן ייעודי) |
| גל 06 (אופציונלי) | פוסט בלוג «יום בשוק» אם רוצים URL נפרד לזיכרון |

---

## השפעה על גלים

| גל | עדכון |
|----|--------|
| **01** | SFA = שירות אדמה בלבד; consulting-agro merge — גל המשך או רוויזיה post-approval |
| **04** | garden · rest-x-greenhouse · **sfa project** · tiktrack · **bcs-client-case** |
| **03 heritage** | שילוב shook בתוכן (ביקורת HTML — הערה לנמרוד) |
| **06** | shook כחומר אופציונלי לפוסט |

---

## TBC מנמרוד

- שם החווה + slug סופי לעמוד BCS case
- אישור מיזוג consulting-agro: האם `hydro-greenhouse` נכנס כסקשן או רק redirect
- עוגן shook ב-heritage: §03 vs גלריה vs שניהם
