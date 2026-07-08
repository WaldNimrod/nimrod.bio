---
id: WAVE_REGISTRY_CONTENT_2026-06-15_v1
type: WAVE_REGISTRY
owner: team_100
date: 2026-06-15
status: active
---

# רישום גלים — תוכן nimrod.bio

**עקרון:** האתר מחולק ל**גלים מצומצמים**. כל גל = חבילת ביקורת HTML אחת + אישור מרוכז + הטמעה מרוכזת.

לא עובדים עמוד-עמוד לאישור/הטמעה.

---

## גלים

| גל | ID | תבניות | עמודים | סטטוס | חבילת ביקורת |
|---|---|---|---|---|---|
| **01** | `wave-01-services` | T2 | bcs, produce, consulting-hydro, sfa | **ready_for_owner_html_review** | `content-drafts/waves/wave-01-services/` |
| **02** | `wave-02-worlds` | T1 | soil, know, code | **ready_for_owner_html_review** | `content-drafts/waves/wave-02-worlds/` |
| **03** | `wave-03-about` | T8 | about, heritage, contact | **ready_for_owner_html_review** | `content-drafts/waves/wave-03-about/` |
| **04** | `wave-04-projects` | T3 | sfa, garden, greenhouse, bcs-case, tiktrack | **planned** (שלד) | `content-drafts/waves/wave-04-projects/` |
| 05 | `wave-05-home` | T7 | / | מתוכנן | — |
| 06 | `wave-06-blog` | T4/T5 | blog index + רענון פוסטים נבחרים | מתוכנן | — |

---

## זרימת גל

```
מנדט גל → סוכני משנה (עמוד/עמוד) → team_100 מרכיב HTML bundle
    → נמרוד בודק בדפדפן (הערות לסקשן)
    → העתק JSON → team_100
    → תיקונים / רוויזיה → אישור גל → מכסנית → הטמעה
```

---

## גל 01 — פירוט

| page_id | URL | מקור קופי | הערה |
|---|---|---|---|
| bcs | /services/bcs/ | revision + כותרת נעולה | במכסנית |
| produce | /services/produce/ | team_70 draft | לביקורת |
| consulting-hydro | /services/consulting-hydro/ | טיוטת team_100 לגל | גשר אדמה×ידע |
| sfa | /services/sfa/ | SITE_COPY_SFA → T2 | גשר אדמה×דיגיטל |

**פתיחה:** `content-drafts/review-hub/index.html` (מרכז) · או ישירות לגל

**מרכז ביקורת:** `content-drafts/review-hub/index.html`

---

## גל 02 — פירוט

| page_id | URL | מקור קופי | הערה |
|---|---|---|---|
| soil | /world/soil/ | SITE_COPY_WORLDS + מיזוג v1+v2 | owner_review |
| know | /world/know/ | SITE_COPY_WORLDS + מיזוג v1+v2 | בלי TikTrack |
| code | /world/code/ | SITE_COPY_WORLDS + מיזוג v1+v2 | עוגן SFA |

**פתיחה:** `content-drafts/review-hub/index.html` · `content-drafts/waves/wave-02-worlds/index.html`

---

## גל 04 — פירוט (שלד 2026-06-24)

**מנדט גל:** `MANDATE_WAVE_04_PROJECTS_T3_2026-06-24_v1.md`  
**חבילת שלד:** `content-drafts/waves/wave-04-projects/`

| page_id | URL | תבנית | עולם | מקור | הערה |
|---------|-----|--------|------|------|------|
| bcs-case | `/project/bcs-client-case/` [TBC] | T3 client-case | soil | מנדט שלד | מחליף `farm-y-bcs` · פרטי חווה TBC |
| sfa-project | `/project/sfa/` | T3 | code | SITE_COPY_SFA | פרויקט דיגיטל — לא שירות |
| garden | `/project/nimrodsgarden/` | T3 legacy | soil | SITE_COPY_PROJECT_GARDEN | מקביל ל-heritage T8b |
| greenhouse | `/project/rest-x-greenhouse/` | T3 live | soil | SITE_COPY_FIXES | מחתרת |
| tiktrack | `/project/tiktrack/` | T3 pilot | code | SITE_COPY_TIKTRACK | מינימלי |

**פרישה:** `restaurant-supply` · `coop-sharon` (או גל נפרד) · redirect `farm-y-bcs`

**ארכיטקטורה:** `OWNER_DECISIONS_SITE_ARCHITECTURE_2026-06-23_v1.md`

---

## שירותים — מבנה לאחר איחוד (החלטת נמרוד)

| URL | סטטוס |
|-----|--------|
| `/services/bcs/` | T2 · גל 01 |
| `/services/produce/` | T2 · גל 01 |
| `/services/consulting-hydro/` | T2 · גל 01 |
| `/services/sfa/` | T2 · אדמה · גל 01 (זווית שטח) |
| `/services/teaching/` | T2 · **נפרד** · גל עתידי |
| `/services/consulting-agro/` | T2 · **עוגן מאוחד** (agro + hydro-greenhouse + משתלה) · גל עתידי |
| `/shook/` | **301 → heritage** · תוכן משולב |

---

## section_id — סכימה

`{wave_id}.{page_id}.{section}`

דוגמאות: `wave01.bcs.hero` · `wave01.produce.s03` · `wave01.sfa.cta`

JSON מיוצא מכפתור **העתק JSON הערות** בכל עמוד בגל.
