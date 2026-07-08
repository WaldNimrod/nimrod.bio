---
id: REVISION_INTAKE_PROTOCOL_2026-06-24_v1
type: PROTOCOL
from: team_100
date: 2026-06-24
scope: owner_review_json · revision_mandates
---

# פרוטוקול קליטת ביקורת — JSON מנמרוד → רוויזיה

מסמך זה מגדיר איך team_100 מעבד **שני סוגי JSON** מנמרוד:

1. **הערות ביקורת גל** — מכפתור «העתק JSON הערות» בחבילות wave-01/02/03
2. **אספקת חומרים** — מכפתור «העתק JSON לצ׳אט» ב-`owner-supply`

---

## 1. קליטה

| מקור | `export_type` | תיקיית קליטה |
|------|---------------|--------------|
| גל HTML | `nimrod_bio_wave_review` | `owner_review_intake/wave-{NN}-*.json` |
| אספקה | `nimrod_bio_owner_supply` | `owner_review_intake/owner-supply-*.json` |

**הוראה לנמרוד:** הדבק JSON בצ׳אט team_100. הסוכן שומר עותק ב-`owner_review_intake/` עם תאריך.

---

## 2. עיבוד הערות גל (wave review)

### קלט לדוגמה

```json
{
  "export_type": "nimrod_bio_wave_review",
  "wave_id": "wave-02-worlds",
  "page_id": "soil",
  "sections": [
    { "section_id": "wave02.soil.hero", "note": "לקצר את הליד" }
  ]
}
```

### שלבי team_100

1. **סיווג** — לכל `section_id`:
   - `copy_edit` — שינוי טקסט
   - `factual` — דורש אספקה / TBC
   - `structural` — שינוי תבנית (נדיר — דורש החלטת נמרוד)
   - `approve_as_is` — הערה ריקה או «מאושר»
2. **מיזוג עם TBC** — אם ההערה חופפת `TBC_REGISTRY` → קישור לשדה owner-supply
3. **מנדט רוויזיה** — `_COMMUNICATION/team_70/MANDATE_REVISION_{SCOPE}_{DATE}_v1.md`
4. **עדכון HTML** — אחרי רוויזיה או ישירות אם שינוי קטן
5. **עדכון סטטוס** — `NIMROD_BIO_WRITING_STATE.md`

### פלט מנדט רוויזיה (תבנית)

```markdown
---
type: REVISION_MANDATE
from: team_100
source_json: owner_review_intake/wave-02-2026-06-24.json
wave: wave-02-worlds
page_url: /world/soil/
---

## שינויים מחויבים
| section_id | פעולה | הערת נמרוד |
|------------|--------|------------|

## אסור לשנות
(רשימה ממנדט מקורי)

## QA אחרי רוויזיה
- [ ] facts locked
- [ ] forbidden terms
```

---

## 3. עיבוד אספקה (owner supply)

### שלבים

1. שמירת JSON ב-`owner_review_intake/owner-supply-{date}.json`
2. מיפוי `field_id` → TBC ב-`TBC_REGISTRY`
3. עדכון `NIMROD_BIO_WRITING_STATE` — סגירת TBCים
4. אם `supply.bcs_case` מלא → שחרור `MANDATE_WRITE_BCS_CASE` מ-`placeholder_until_farm_details`
5. יישום החלטות ארכיטקטורה (shook, agro) בתוכנית גל 04 / רוויזיית heritage

---

## 4. אישור גל

גל עובר ל-`editorial_stack` / `final_approval_round` כאשר:

- [ ] כל עמודי הגל עברו ביקורת JSON (או «מאושר ללא הערות»)
- [ ] TBCים חוסמים סגורים או מסומנים `[TBC]` מפורש ב-HTML
- [ ] team_100 אישר QA סופי

אחר כך: `FINAL_APPROVAL_STACK_CONTENT` → אישור נמרוד מרוכז → `Implementation Push` לגל.

---

## 5. מצב קליטה נוכחי

| גל | JSON התקבל | סטטוס עיבוד |
|----|------------|-------------|
| wave-01-services | — | ממתין לנמרוד |
| wave-02-worlds | — | ממתין לנמרוד |
| wave-03-about | — | ממתין לנמרוד |
| owner-supply | — | ממתין לנמרוד |

**תבנית קובץ ריק:** [`owner_review_intake/WAVE_REVIEW_TEMPLATE.json`](owner_review_intake/WAVE_REVIEW_TEMPLATE.json)
