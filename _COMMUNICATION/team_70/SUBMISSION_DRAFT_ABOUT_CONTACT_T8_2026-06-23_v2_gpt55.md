---
id: SUBMISSION_DRAFT_ABOUT_CONTACT_T8_2026-06-23_v2_gpt55
type: TEAM70_DRAFT_SUBMISSION
page_url: /contact/
template: T8c
page_name: צור קשר
date: 2026-06-23
status: draft_submission
engine: gpt-5.5-high
from: team_70
to: team_100
source: _COMMUNICATION/team_100/SITE_COPY_CONTACT_v1.md
source_status: FINAL
protocol: sources/writers_context_pack/09_WRITING_ORCHESTRATION_PROTOCOL.md §3
---

# Draft Submission — `/contact/`

## Intake

understanding:
עמוד T8c עבור `/contact/`. מקור הקופי הוא `SITE_COPY_CONTACT_v1.md`, שמסומן FINAL. המשימה כאן אינה לפתוח מחדש את הטקסט, אלא למפות אותו להגשת team_70 קאנונית: Brief, Draft, Media Plan, Integration Push ו-QA עצמי. הטון צריך להישאר מארח: קצר, חם, ישיר, בלי hard-sell ובלי להפוך את העמוד למשפך.

canon_read:
- `_COMMUNICATION/team_100/SITE_COPY_CONTACT_v1.md`
- `sources/writers_context_pack/00_INDEX.md`
- `sources/writers_context_pack/01_VOICE_AND_STYLE.md`
- `sources/writers_context_pack/03_FACTS_LOCKED.md`
- `sources/writers_context_pack/08_DO_AND_DONT.md`
- `sources/writers_context_pack/09_WRITING_ORCHESTRATION_PROTOCOL.md`
- `_COMMUNICATION/team_100/COPY_CONTEXT_PACK_v1.0/05_templates_copy_slots.md`

open_questions:
- האם נושא הטופס נשאר כ-select אחד לפי מקור FINAL, או ש-team_100 רוצה לתרגם אותו לשבבי נושא כפי שתואר בתבנית T8c המקורית?
- האם להציג "טלפון" כטלפון ממשי לצד WhatsApp, או לשמור בפועל רק על WhatsApp כערוץ מועדף ולהציג את המספר תחת אותו כרטיס?

options_if_any:
- אופציה א': לשמר את מקור FINAL בדיוק: WhatsApp primary, טופס כנתיב שני, ללא אימייל ציבורי.
- אופציה ב': להתאים מעט לתבנית T8c הישנה: שבבי נושא במקום select, עדיין בלי אימייל ציבורי חדש.

blocking_missing_info:
אין חסם לכתיבה. כתובת האימייל נשארת מחוץ לקופי הציבורי בהתאם למקור FINAL: להשתמש בכתובת שכבר מוגדרת בטופס.

ready_to_draft: yes

## Brief

URL: `/contact/`

Template: `T8c · צור קשר`

Register: מארח — חם, ישיר, low-friction, בלי לחץ מכירתי.

Scope:
מיפוי קופי FINAL לעמוד קשר: Hero, lead, פעולות ראשיות, פרטי קשר, שדות טופס, microcopy לטופס, הודעות מצב, Media Plan, Integration Push ו-QA. אין שינוי סטטוס, אין הטמעה, אין המצאת כתובת אימייל או פרטים נוספים.

Writing goal:
להוריד חיכוך. המבקר צריך להבין מיד איך מתחילים: WhatsApp כנתיב מהיר, או טופס קצר למי שרוצה לתת הקשר. העמוד צריך להרגיש כמו הזמנה לשיחה ראשונה, לא כמו עמוד מכירה.

Known constraints:
- מקור הקופי `SITE_COPY_CONTACT_v1.md` הוא FINAL.
- WhatsApp הוא הערוץ הראשי לפי העדפת owner.
- אין לפרסם כתובת אימייל חדשה; האימייל הוא טכני ומשויך להגדרת הטופס.
- לא להבטיח זמני תגובה מעבר למה שנעול במקור או בתבנית.
- לא להשתמש ב-hard-sell, דחיפות, מחסור או ניסוחי "סגירה".
- לא לסמן סטטוס אישור או מוכנות להטמעה במסגרת הגשת team_70.

## Draft

### Hero

Title:
דבר איתי

Lead:
הדרך הכי טובה להתחיל היא פשוט לכתוב. שיחה ראשונה — ללא התחייבות.

Secondary lead:
30 דקות: אני מבין על מה אתה עובד, אתה רואה אם יש לי מה לתרום.

Primary action:
- Label: WhatsApp
- Target: `https://wa.me/972547776770`
- Note: הערוץ המועדף והמהיר.

Secondary action:
- Label: טופס
- Target: גלילה לטופס למטה
- Note: למי שרוצה להשאיר הקשר מסודר לפני שיחה.

### Contact body

#### Left column — Form

Form title:
כתוב כמה מילים

Form intro:
אין טופס ארוך. שם, דרך חזרה, נושא, ומה חשוב שאבין לפני שאנחנו מדברים.

Fields:

| שדה | סוג | חובה |
|---|---|---|
| שם | text | כן |
| טלפון | tel | לא |
| נושא | select: אדמה · ייעוץ והוראה · דיגיטל · אחר | לא |
| הודעה | textarea | כן |

Submit button:
שלח הודעה

Success message:
תודה! אגיב תוך 48 שעות.

Invalid message:
חסר שם או הודעה. אפשר להשלים ולשלוח שוב.

Error message:
משהו לא עבר כמו שצריך. אפשר לנסות שוב או לכתוב לי ב-WhatsApp.

Honeypot note:
שדה אנטי-ספאם נשאר טכני בלבד, לא מוצג למשתמש.

#### Right column — What to expect

Card title:
למה לצפות

Card text:
שיחה ראשונה היא לא התחייבות. בדרך כלל מספיקות 30 דקות כדי להבין על מה אתה עובד, איפה התקיעה, ואם יש לי דרך טובה לעזור.

Response time:
בד״כ אגיב תוך 48 שעות.

WhatsApp card:
- Title: WhatsApp / טלפון
- Text: עדיפות לוואטסאפ. זה הנתיב הכי קצר להתחיל.
- Number display: `054-7776770`
- Link: `https://wa.me/972547776770`

Location card:
- Title: מיקום
- Text: מגד 5, פרדס חנה
- Link label: מהגינה של נמרוד · Google Maps
- Link target: `https://maps.app.goo.gl/8ySCEcFw3B8hXtnP6`

Email note:
לא להציג אימייל ציבורי חדש. הטופס נשלח לכתובת שכבר מוגדרת באתר.

### Social links

Section title:
עוד מקומות למצוא אותי

Links:
- Facebook — הגינה: `/NimrodGarden`
- Facebook — אישי: `/Wald.Nimrod`
- YouTube: `@waldnimrod`

Integrator note:
אם אין מקום ויזואלי לכל הרשתות בטור הימני, עדיף להציג אותן כקישורי טקסט שקטים מתחת לכרטיסי הקשר, לא להפוך אותן ל-CTA ראשי.

### Microcopy / labels

- Page nav label: צור קשר
- Primary CTA label: WhatsApp
- Secondary CTA label: טופס
- Form section label: כתוב כמה מילים
- Topic label: נושא
- Topic options: אדמה · ייעוץ והוראה · דיגיטל · אחר
- Submit label: שלח הודעה
- Response label: בד״כ אגיב תוך 48 שעות
- No-hard-sell reassurance: שיחה ראשונה — ללא התחייבות

## Media Plan

existing_media:
- אין מדיה חובה לעמוד הקשר לפי מקור FINAL.
- אם התבנית דורשת אלמנט ויזואלי קטן, להשתמש באלמנט עיצובי קיים של האתר ולא בתמונה חדשה.

missing_media:
- אין חוסר מדיה שחוסם את העמוד.

recommended_captions:
- לא נדרש.

alt_text_notes:
- אם משולב אייקון WhatsApp, alt/aria צריך לתאר פעולה: "שליחת הודעה ב-WhatsApp".
- אם משולבת מפת קישור או אייקון מיקום, alt/aria צריך להיות תפקודי: "פתיחת מיקום בגוגל מפות".
- לא להוסיף מטאפורות או טענות תוכן דרך alt.

external_references_if_any:
- WhatsApp deep link: `https://wa.me/972547776770`
- Google Maps: `https://maps.app.goo.gl/8ySCEcFw3B8hXtnP6`
- Facebook — הגינה: `/NimrodGarden`
- Facebook — אישי: `/Wald.Nimrod`
- YouTube: `@waldnimrod`

## Integration Push

fields_ready:
- `hero.title`
- `hero.lead`
- `hero.secondary_lead`
- `hero.primary_action`
- `hero.secondary_action`
- `contact.form.title`
- `contact.form.intro`
- `contact.form.fields`
- `contact.form.submit_label`
- `contact.form.status_messages`
- `contact.side.expectation_card`
- `contact.side.response_time`
- `contact.side.whatsapp_card`
- `contact.side.location_card`
- `contact.social_links`
- `contact.microcopy`
- `media_plan`

fields_tbc:
- החלטת UI: select לנושא לפי מקור FINAL או topic chips לפי תבנית T8c הישנה.
- החלטת הצגה: האם המספר מוצג גם כטלפון רגיל או רק כחלק מכרטיס WhatsApp.
- הכתובת הטכנית שאליה הטופס נשלח — קיימת בקונפיגורציית הטופס, לא בקופי.

copy_blocks:
- Hero lead:
  "הדרך הכי טובה להתחיל היא פשוט לכתוב. שיחה ראשונה — ללא התחייבות."
- Hero secondary:
  "30 דקות: אני מבין על מה אתה עובד, אתה רואה אם יש לי מה לתרום."
- Form intro:
  "אין טופס ארוך. שם, דרך חזרה, נושא, ומה חשוב שאבין לפני שאנחנו מדברים."
- Expectation card:
  "שיחה ראשונה היא לא התחייבות. בדרך כלל מספיקות 30 דקות כדי להבין על מה אתה עובד, איפה התקיעה, ואם יש לי דרך טובה לעזור."
- Success message:
  "תודה! אגיב תוך 48 שעות."

cta_labels:
- WhatsApp
- טופס
- שלח הודעה
- מהגינה של נמרוד · Google Maps

notes_for_integrator:
- WhatsApp link must be `https://wa.me/972547776770`.
- Do not add a public email address. Use the existing form-configured address for delivery.
- Keep WhatsApp primary and the form secondary.
- Keep the page low-friction: no long qualification sequence, no sales pressure.
- If the implementation still contains an old direct email card, replace it with the FINAL source rule: form-configured email only, out of content scope.

## QA

facts_used:
- Page URL: `/contact/`.
- Heading: "דבר איתי".
- WhatsApp / phone: `054-7776770`.
- WhatsApp deep link: `https://wa.me/972547776770`.
- Location: מגד 5, פרדס חנה.
- Maps link: `https://maps.app.goo.gl/8ySCEcFw3B8hXtnP6`.
- Active social links: Facebook הגינה `/NimrodGarden`, Facebook אישי `/Wald.Nimrod`, YouTube `@waldnimrod`.
- Form fields from FINAL source: שם, טלפון, נושא, הודעה.
- Email is technical/form-configured and out of content scope.

tbc:
- UI representation of topic field: select vs chips.
- Whether to display the phone number as a normal phone contact in addition to WhatsApp.
- Technical form destination address, if integrator needs to verify it.

unsupported_claims:
אין טענות מכוונות ללא מקור. לא נוספו שעות פעילות, זמני תגובה חדשים, כתובת אימייל, ערוצי רשת נוספים, הבטחות שירות או מסגרת מחיר.

forbidden_terms_check:
PASS — אין מונחי תזה אסורים, אין פבריקציות, אין שמות רגישים, אין שפת SaaS/קורפורייט, ואין hard-sell.

sales_tone_check:
PASS — הטון הוא הזמנה שקטה לשיחה ראשונה. אין דחיפות, אין מחסור, אין "לסגור", ואין לחץ להשאיר פרטים.

template_check:
PASS_WITH_NOTE — חריצי T8c מכוסים: Hero, Form, side panel, WhatsApp, response expectation, social/contact details, status messages. הערה: מקור FINAL מחליף את תבנית T8c הישנה בשני מקומות — אין אימייל ציבורי, ושדה נושא מוגדר כ-select ולא בהכרח כשבבי נושא.

ux_clarity_check:
PASS — למשתמש יש שני נתיבים ברורים: WhatsApp מהיר או טופס קצר. ההיררכיה לא מעמיסה ולא מסתירה את הפעולה הראשית.

conceptual_balance_check:
PASS — העמוד נשאר שימושי ופשוט. אין ניסיון להכניס תזה, סיפור או מסגור עודף לעמוד שתפקידו לפתוח שיחה.

self_score_1_to_5:
4.6

questions_for_nimrod:
- האם להשאיר את "נושא" כ-select פשוט, או להפוך לשבבי נושא לפי העולמות?
- האם המספר `054-7776770` צריך להופיע גם כטלפון רגיל, או רק כנתיב WhatsApp?
- האם להציג את שלושת קישורי הרשתות בעמוד עצמו, או להשאיר אותם רק בכרטיס צד/פוטר?

what_to_review_first:
Hero + שני נתיבי הפעולה + ההחלטה על אימייל ציבורי. אלה המקומות שבהם עמוד קשר יכול להישאר קל וברור, או בטעות להפוך לטופס מכירה ארוך מדי.
