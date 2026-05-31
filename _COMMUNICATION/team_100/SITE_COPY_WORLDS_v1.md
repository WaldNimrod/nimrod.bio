---
id: SITE_COPY_WORLDS_v1
type: SITE COPY DELIVERABLE — 3 world pages (T1)
from: team_100
date: 2026-05-31
pages: /world/soil/ · /world/know/ · /world/code/
register: 🎙 חושב (framing) + מארח (activities)
status: ✅ DRAFT — best-effort full text; fixes applied
basis: live T1 pages + locked facts + taxonomy v3.4 (coop removed)
---

# nimrod.bio — 3 World pages (T1)

## Fixes applied to ALL three (vs live)
- **REMOVE the "זה ה-3×" / "אינסטנסים של אותה מערכת" lecture** → demonstrate via the real flow line (no CDIP/3×/entropy as terms).
- Keep the anchor pattern + the bridge ("seam") cards + "מה נכתב ב..." blog teaser.
- Fix facts: one restaurant (המחתרת), BCS service, SFA live, TikTrack pilot/separate, **no coop**.
- Keep the strong existing lines ("מה שעבר בוץ, לא רק מצגות", "ידע שעבר בוץ — לא רק PDF").

The shared **connectivity line** (use on all three — this IS the thesis demonstrated, no naming):
> החממה מזינה את התוצרת. התוצרת מאמתת את הייעוץ. הייעוץ מקודד ל-SFA. ו-SFA חוזרת לקהילה — לחממה הבאה.

---

## 🌱 /world/soil/ — אדמה
**Hero:** אדמה — *איפה שהאדמה פוגשת ידיים.*
> כאן אני מגדל בפועל. לא תיאוריה — ידיים, חומר, עונה.

**עוגן:** החממה ההידרופונית — התשתית שעליה יושב כל השאר.
**פעילויות:**
- תוצרת מקצועית — למסעדת המחתרת התאילנדית (עירית שומית · פאטבונג), בהזמנה מראש.
- **BCS** — שירותי שטח לפי יום עבודה.
- משתלה — רקע; הידע קיים (לא ערוץ הכנסה).

**גשר (אדמה × ידע):** *אין ייעוץ שלא נוסה בבוץ.* מה שאני מגדל הוא הבסיס לכל מה שאני מלמד.
**פרויקטים:** הגינה של נמרוד (מורשת) · חממת המחתרת · BCS.
**בלוג:** *מה נכתב באדמה* — תצפיות, מקרים מהשטח, רעיונות שעוד לא הבשילו.

---

## 📐 /world/know/ — ייעוץ והוראה
**Hero:** ייעוץ והוראה — *איפה שהניסיון הופך לכלי.*
> ייעוץ חממה, אגרו ו-market garden — **מה שעבר בוץ, לא רק מצגות.**

**עוגן:** הניסיון בשטח — תשע עונות גינה + החממה הפעילה — הוא המקור לכל ייעוץ.
**פעילויות:**
- ייעוץ · הידרופוניקה
- ייעוץ · אגרו + market garden
- תכנון גידול — מסלולים שכבר נוסו
- הוראה מקצועית — בשטח, לא בכיתה

**גשר (ידע × דיגיטל):** הידע לא נשאר אצלי — הוא מקודד ל-SFA, וחוזר לקהילה.
**גשר (ידע × אדמה):** *ידע שעבר בוץ — לא רק PDF.*
**פרויקטים:** מקרי ייעוץ נבחרים (תכנון/אבחון/ליווי חממה). *(להסיר tiktrack מהעולם הזה — שייך לדיגיטל.)*
**בלוג:** *מה נכתב בייעוץ והוראה.*

---

## 💻 /world/code/ — דיגיטל
**Hero:** דיגיטל — *איפה שהידע הופך למערכת חיה.*
> SFA, TikTrack, כלים קהילתיים — נבנה מהשטח, חוזר לשטח. לא מיזם לשם מיזם — כלי שעובד.

**עוגן:** **SFA** — הליבה החיה והקהילתית של העולם הזה. *(פותר את ה-"TBD anchor" — קובע SFA כעוגן `_nb_is_anchor_for_world` ל-code.)*
**פעילויות:**
- **SFA / SmallFarmsAgents** — מדד מחירים שקוף + ספר גידולים. חי ב-sfa.nimrod.bio.
- **TikTrack** — מערכת QA למסחר. בפיילוט סגור; משווק בנפרד. חי ב-tt.nimrod.bio.
- פיתוח ממשקים · ייעוץ דיגיטלי.

**גשר (אדמה × דיגיטל):** החווה מזינה את **SFA** בנתונים אמיתיים — השטח מאמת את הקוד. *(SFA בלבד — אין גשר-דאטה ל-TikTrack.)*
**פרויקטים:** SFA · TikTrack.
**בלוג:** *מה נכתב בדיגיטל.*

---

## Build notes (team_35)
- Replace the "זה ה-3×"/"אינסטנסים" sentence on all three world pages with the shared connectivity line above (or omit — the flow line carries it).
- `code` world anchor = **SFA** (set `_nb_is_anchor_for_world`).
- Remove `קואופרטיב חממות` from soil/code if it still appears.
- Move/remove `tiktrack` card from the `know` world (belongs to `code`).
- Activity counts on cards ("0 פעילויות") — wire to real CPT counts.
