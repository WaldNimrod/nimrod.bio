---
id: SUBMISSION_DRAFT_ABOUT_CONTACT_T8_2026-06-23_v1
type: TEAM70_DRAFT_SUBMISSION
page_url: /contact/
template: T8c
date: 2026-06-23
status: submitted_for_editorial_review
engine: composer
wave: wave-03-about
---

## Brief
URL: /contact/
Template: T8c · צור קשר
Register: מארח — הזמנה, לא משפך. חם, ישיר, חיכוך נמוך.
Scope: Hero · שדות טופס · עמודה ימנית (זמן תגובה, WhatsApp, מיקום, רשתות) · הודעת הצלחה · מיקרו-קופי. ללא אימייל טכני (team_35). ללא שעות פעילות כ"משפך".
Writing goal: להשאיר את הקאנון של נמרוד כפי שננעל — דרך פשוטה לפתוח שיחה, בלי מכירה ובלי טופס מפחיד.
Known constraints: `SITE_COPY_CONTACT_v1.md` — **owner values FINAL** (2026-05-31). WhatsApp 054-7776770 · wa.me/972547776770 · מגד 5 פרדס חנה · maps link · FB/YouTube handles נעולים.

## Draft

### Hero
Eyebrow: צור קשר · מארח
Title: דבר איתי
Lede:
הדרך הכי טובה להתחיל היא פשוט לכתוב. שיחה ראשונה — ללא התחייבות.
30 דקות: אני מבין על מה אתה עובד, אתה רואה אם יש לי מה לתרום.

**Primary actions (2 — T-09)**
- WhatsApp (primary) → **054-7776770** · `https://wa.me/972547776770` · label: `WhatsApp · 054-7776770`
- טופס → גלילה לטופס · label: `לטופס למטה` · anchor: `#nb-contact`

### Form (טור שמאל)
Section title (h2): טופס פנייה
Form intro:
אין טופס ארוך. בחר נושא, תן לי פרטים, תגיד מה בראש — ואחזור.
אני קורא הכל. בדרך כלל חוזר תוך 48 שעות. *אם זה דחוף — WhatsApp בצד.*

| שדה | סוג | label | הערות |
|-----|-----|-------|-------|
| שם | text (required) | שם · איך לפנות אליך | |
| טלפון | tel | טלפון | אופציונלי — לפי קאנון owner |
| נושא | select | נושא | אדמה · ייעוץ והוראה · דיגיטל · אחר |
| הודעה | textarea (required) | הודעה | min 20 תווים |

נושא — אפשרויות (values → labels):
- `soil` → אדמה
- `know` → ייעוץ והוראה
- `code` → דיגיטל
- `other` → אחר

Placeholder (הודעה):
מה המצב, מה רצית להבין, מה אתה מנסה לעשות...

Submit button: שלח
Submit loading state: שולח…

Form foot:
בלי ספאם · בלי רשימות תפוצה · רק תגובה לפנייה הספציפית

> **הערת מימוש:** התבנית החיה כוללת שדה אימייל (required). קאנון owner מגדיר טלפון בלבד מעבר לשם/נושא/הודעה. אימייל משלוח = team_35 technical — מחוץ לסקופ תוכן.

### Right column (טור ימין)

**כרטיס 1 — תגובה**
Label: תגובה
Headline: תוך 48 שעות. עונות שטח — לפעמים יומיים נוספים.

**כרטיס 2 — WhatsApp · מענה מהיר**
Label: WhatsApp · מענה מהיר
Headline: אם זה דחוף — שלח WhatsApp.
Body:
הודעה קצרה ועניינית, ואני חוזר במהלך היום. גם אם אני בשטח.
CTA: WhatsApp · הודעה → `https://wa.me/972547776770`
Number display: 054-7776770

**כרטיס 3 — מיקום**
Label: מיקום
Body:
מגד 5, פרדס חנה. *אזור פעילות: שרון, חוף הכרמל ועמק חפר. מחוץ לאזור — רק אם זה ספציפי וכדאי, או בזום.*
Maps CTA: פתח ב-Google Maps → `https://maps.app.goo.gl/8ySCEcFw3B8hXtnP6` (Google Maps — "מהגינה של נמרוד")

**כרטיס 4 — רשתות**
Label: רשתות
- Facebook · הגינה → `https://www.facebook.com/NimrodGarden` (`/NimrodGarden`)
- Facebook · אישי → `https://www.facebook.com/Wald.Nimrod` (`/Wald.Nimrod`)
- YouTube · ‎@waldnimrod → `https://www.youtube.com/@waldnimrod`

### Success & error messages (מיקרו-קופי מערכת)
| מצב | טקסט |
|-----|------|
| success (`ok`) | הפנייה נשלחה. אחזור אליך תוך 48 שעות. |
| error (`error`) | משהו לא עבד בשליחה. נסה שוב או שלח WhatsApp. |
| validation (`invalid`) | חסרים שדות חובה או שההודעה קצרה מדי (20 תווים). |

> חלופה לפי T8c slot canon: *"תודה! אגיב תוך 48 שעות."* — owner copy לא נעל ניסוח הצלחה; הניסוח למעלה תואם מימוש JS קיים ואת רוח ה-48 שעות.

### Details block (סיכום קאנון — לעמודה ימנית / meta)
- **WhatsApp / טלפון:** 054-7776770 (עדיפות לוואטסאפ)
- **אימייל:** *(team_35 — כתובת משלוח מוגדרת בטופס; מחוץ לסקופ תוכן)*
- **מיקום:** מגד 5, פרדס חנה

## Media Plan
existing_media:
- אין מדיה חובה ל-T8c — עמוד טקסט + טופס, ללא hero image.

missing_media:
- אין.

recommended_captions:
- N/A

alt_text_notes:
- N/A — אין תמונות בעמוד.

external_references_if_any:
- Google Maps: `https://maps.app.goo.gl/8ySCEcFw3B8hXtnP6`
- WhatsApp deep-link: `https://wa.me/972547776770`

## Integration Push
fields_ready:
- hero.eyebrow
- hero.title
- hero.lede
- hero.cta_primary (WhatsApp label + href)
- hero.cta_secondary (form scroll label + anchor)
- form.title
- form.intro
- form.fields.name (label, required)
- form.fields.phone (label, optional per owner canon)
- form.fields.topic (options ×4)
- form.fields.message (label, placeholder, required, minlength 20)
- form.submit_label
- form.submit_loading
- form.foot
- side.response (label, headline)
- side.whatsapp (label, headline, body, cta, number)
- side.location (label, body, maps_cta, maps_href)
- side.social (label, 3 links)
- messages.ok / messages.error / messages.invalid

fields_tbc:
- form.fields.email — קיים במימוש WP; **לא** בקאנון owner. החלטת team_100/team_35: להשאיר / להסיר / לאחד לטלפון.
- success message — בחירה בין ניסוח JS קיים לבין *"תודה! אגיב תוך 48 שעות."* מ-T8c slot canon.

copy_blocks:
| section_id | slot | ready |
|---|---|---|
| wave03.contact.hero | eyebrow, title, lede, 2 CTAs | yes |
| wave03.contact.form | title, intro, fields, foot, submit | partial (email TBC) |
| wave03.contact.side | 4 cards (response, wa, location, social) | yes |
| wave03.contact.messages | ok, error, invalid | yes |

cta_labels:
- Hero primary: `WhatsApp · 054-7776770`
- Hero secondary: `לטופס למטה`
- Side WhatsApp: `WhatsApp · הודעה`
- Maps: `פתח ב-Google Maps`
- Submit: `שלח`

notes_for_integrator:
- WhatsApp = **primary path** — כפתור hero + כרטיס ימני; `wa.me` בלי 0 מוביל, קידומת 972.
- נושא select ממופה ל-slugs: soil / know / code / other — תואם `t8-contact-form.php`.
- אימייל משלוח = כתובת מוגדרת ב-handler; לא להציג כתובת בפרונט אלא אם owner יבקש.
- עמודה ימנית = "למה לצפות", לא שעות פעילות.
- אין מדיה · אין גלריה · אין SEO copy נוסף מעבר ל-hero.

## QA
facts_used:
- כותרת ו-lede — verbatim מ-`SITE_COPY_CONTACT_v1.md`.
- WhatsApp / טלפון: 054-7776770 · `https://wa.me/972547776770`.
- מיקום: מגד 5, פרדס חנה · maps `https://maps.app.goo.gl/8ySCEcFw3B8hXtnP6`.
- רשתות: Facebook הגינה `/NimrodGarden` · Facebook אישי `/Wald.Nimrod` · YouTube `@waldnimrod`.
- שדות טופס owner: שם, טלפון, נושא (4 אפשרויות), הודעה.
- זמן תגובה: 48 שעות (טון low-friction, ללא התחייבות בשיחה ראשונה).
- אזור פעילות (מימוש קיים, לא ב-owner copy): שרון, חוף הכרמל, עמק חפר — לא עובדה חדשה; קיים ב-`t8-contact-side.php`.

tbc:
- שדה אימייל בטופס — פער owner copy ↔ מימוש WP.
- ניסוח הודעת הצלחה — JS קיים vs T8c canon slot.

unsupported_claims:
אין — לא הוספתי שירותים, מחירים, או הבטחות מעבר לקאנון.

forbidden_terms_check:
pass — ללא אנטרופיה, CDIP, 3×, Unless, disruption, סקייל, hard-sell, "דחוף"/"רק עכשיו".

sales_tone_check:
pass — הזמנה, לא משפך. שיחה ראשונה ללא התחייבות. WhatsApp כנתיב מהיר, לא לחץ.

template_check:
pass — כל חריצי T8c: Hero (+ 2 CTAs), Form (שדות + מיקרו-קופי), Right column (4 כרטיסים), Success/error messages. תואם mandate slots.

ux_clarity_check:
pass — ברור מה הנתיב המהיר (WhatsApp), מה בטופס, מה זמן התגובה, איפה בשטח, לאן ברשתות.

conceptual_balance_check:
pass — מארח בלבד; אין נאום תזה; חיבור לשלושת העולמות דרך select נושא בלבד.

process_compliance_check:
pass — Draft Submission בלבד; ללא owner_approved / ready_for_integration; ללא עריכת NIMROD_BIO_WRITING_STATE.

self_score_1_to_5:
5 — owner copy FINAL; טקסט נשאר קרוב למקור עם מיקרו-קופי מינימלי מתבנית T8c ומימוש קיים.

questions_for_nimrod:
- האם להשאיר שדה אימייל בטופס (כמו במימוש) או לעבור לטלפון בלבד כמו בקאנון owner?
- האם ניסוח הצלחה `הפנייה נשלחה. אחזור אליך תוך 48 שעות.` מתאים, או להעדיף `תודה! אגיב תוך 48 שעות.`?
- האם שורת אזור הפעילות (שרון / כרמל / עמק חפר / זום) נשארת — לא מופיעה ב-owner copy אבל קיימת במימוש?

what_to_review_first:
- Hero lede — נשאר verbatim מ-owner copy?
- איזון WhatsApp (primary) מול טופס — האם הכפתורים והכרטיס הימני מספיקים ברורים?
- פער שדה אימייל — החלטה לפני הטמעה בגל 03.
