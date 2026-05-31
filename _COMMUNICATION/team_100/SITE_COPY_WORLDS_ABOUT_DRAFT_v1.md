---
id: SITE_COPY_WORLDS_ABOUT_DRAFT_v1
type: SITE COPY DRAFT — Worlds (soil/know/code) + About — FOR REVIEW (not yet live)
from: team_100
to: team_00 (approval)
date: 2026-05-31
status: 🟡 DRAFT — awaiting team_00 approval before publishing to dev
governs: SITE_HANDOFF_2026-05-31_v1 (voice + locked facts) · SITE_DELIVERY_PACKAGE v2
locks_honored: Micha (no name/methodology) · demonstrate-never-name (no אנטרופיה/נגנטרופיה/רקורסיה/CDIP/פרמקלצר)
targets:
  - world term soil (id 4, "אדמה") → description
  - world term know (id 5, "ייעוץ והוראה") → description
  - world term code (id 6, "דיגיטל") → description
  - page id 37 ("על נמרוד") → revise stale TikTrack + SFA paragraph
---

# Worlds + About — copy draft (for review)

Production order §7: worlds come LAST, after projects + about. SFA (G) is now live, so all
six project pages are text-complete; this is the final content set before media.

---

## A · World page descriptions

Short intro text shown on each world archive (`/world/<slug>/`). Register: Host→Thinker.
Each aligns to the approved Home §01 world-list (SITE_HANDOFF §4).

### אדמה (soil, id 4)
> כאן מתחילה העבודה — בידיים, בבוץ, מול עונה שלא מתפשרת.
> חממה הידרופונית פעילה בתמרת, תוצרת קבועה למסעדה שמוכנה לעבוד בתנאים שלנו, ושירות BCS בתשלום לחלקות קטנות.
> כל מה שלמדנו כאן נכנס בהמשך לייעוץ ולכלים.

### ייעוץ והוראה (know, id 5)
> מה שעבד בשדה הופך לידע שאפשר להעביר.
> שני מסלולי ייעוץ — תכנון חממה הידרופונית מאפס, וייעוץ אגרו שטחי (מסלולי גידול, מחזורים, שיווק) — וכן הוראה מקצועית.
> כל ייעוץ מתחיל בביקור שטח, לא בשאלון. מה שלא עשינו בעצמנו — לא ממליצים.

### דיגיטל (code, id 6)
> כשהידע חוזר בצורת בעיה שאף מוצר מדף לא פתר — הוא הופך לכלי.
> SFA הוא הגשר האמיתי בין השדה לדיגיטל: כלי קהילתי וחינמי לחקלאות הקטנה, שניזון מתיעוד שטח אמיתי.
> TikTrack — כלי בפיילוט, בתחום אחר לגמרי. ופיתוח ממשקים וייעוץ דיגיטלי, לפי הצורך.

---

## B · About page — targeted fix (page id 37, "על נמרוד")

The first three paragraphs stay as-is (field / hydroponics / consulting — all still accurate).
**Only the 4th paragraph is replaced** — it currently mis-describes both products:

**REMOVE (stale, contradicts finalized project pages):**
> TikTrack ו-SFA נבנו מתוך חוסר. TikTrack — מעקב פעילות וזמן שטחי — נבנה כי לא היה כלי שעובד בדפדפן, בכל מכשיר, בלי הכשרה ארוכה. SFA — מערכת ניהול חוות — מקשרת הזמנת לקוח, ניהול גידול ותיעוד קטיף. שני הכלים האלה משמשים אותנו ביום-יום לפני שאנחנו מציעים אותם לאחרים. לא כלים שמוכרים — כלים שנבנו כי הייתה בעיה אמיתית.

**REPLACE WITH:**
> TikTrack ו-SFA נבנו מתוך חוסר, לא מתוך תכנית עסקית. SFA הוא הגשר הישיר בין השדה לדיגיטל — כלי קהילתי וחינמי לחקלאות הקטנה, שמתחיל ממדד מחירים שקוף וניזון מתשע שנות תיעוד שטח אמיתי. TikTrack נבנה בתחום אחר לגמרי — כלי שעוזר לבדוק החלטות מול מה שתוכנן, כרגע בפיילוט. שניהם נבנו כי הייתה בעיה אמיתית, לא כדי למכור.

### Why each change
- **TikTrack:** old text = "מעקב פעילות וזמן שטחי" (field time-tracking). Finalized page = decision-QA tool, pilot. Old text is simply wrong now. New text is functional-only (no Micha, no methodology).
- **SFA:** old text = "מערכת ניהול חוות … הזמנת לקוח/ניהול גידול/תיעוד קטיף" (a per-farm management system). De-claimed in SITE_COPY_SFA_v1 → it's a **community price-index / tools hub**. New text matches.
- **No farm↔TikTrack data link** implied ("בתחום אחר לגמרי") — per locked fact.
- **SFA = the only real farm→digital bridge** — preserved.

---

## Lock-compliance self-check
- אנטרופיה/נגנטרופיה/רקורסיה/CDIP/פרמקלצר: **0** in all drafts above.
- Micha / מיכה / "Micha OS": **0**.
- Forbidden marketing (disruption/game-changer/הפלטפורמה שלנו/אנחנו מאמינים/AI-first): **0**.
- קואופרטיב / coop residue: **0**.

## On approval
- World descriptions → `POST /wp/v2/world/{4,5,6}` `description` field.
- About → `POST /wp/v2/pages/37` with 4th paragraph swapped (Gutenberg `wp:paragraph` block preserved).

*team_100 | worlds + about draft | 2026-05-31 | awaiting approval*
