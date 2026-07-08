---
id: WAVE_ORCHESTRATION_SERVICES_T2_2026-06-15_v1
type: WAVE_ORCHESTRATION
owner: team_100
wave: services_t2
date: 2026-06-15
status: active
---

# גל שירותים (T2) — אורקסטרציית צי סוכנים

## עקרון (team_00 2026-06-15)

- **team_100** = עורך יציב אחד. **לא כותב** את כל הגל בסשן אחד (הקונטקסט מתמלא מהר).
- **סוכן משנה אחד = עמוד אחד**: קריאת מנדט → כתיבה → בדיקה עצמית → הגשת `Draft Submission` לקובץ.
- **team_100** מקבל הגשות, מבצע ביקורת עורכת, מעדכן סטטוס, מציג לנמרוד, ומכניס למכסנית.
- **אישור סופי + הטמעה** — רק בסוף גל משמעותי (חבילה אחת), לא עמוד-עמוד.

פרוטוקול מלא: `sources/writers_context_pack/09_WRITING_ORCHESTRATION_PROTOCOL.md` §7.

---

## תור הגל

| # | URL | סטטוס | סוכן | הגשה | הערות |
|---|---|---|---|---|---|
| 1 | `/services/bcs/` | `editorial_stack` | (סשן קודם) | `SUBMISSION_REVISION_SERVICES_BCS_T2_2026-06-13_v1.md` | ביקורת עורך + החלטות נמרוד — במכסנית |
| 2 | `/services/produce/` | `owner_review` | fleet-002 | `SUBMISSION_DRAFT_SERVICES_PRODUCE_T2_2026-06-15_v1.md` | ביקורת עורך → `EDITORIAL_REVIEW_PRODUCE_*` |
| 3 | `/services/consulting-hydro/` | `not_started` | — | — | מנדט ייווצר אחרי produce |
| 4 | `/services/sfa/` | `not_started` | — | — | חומר בסיס: `SITE_COPY_SFA_v1.md` |

**קריטריון "גל מוכן לאישור סופי":** לפחות BCS + produce + consulting-hydro + sfa עברו `editorial_stack` (או `owner_review` → stack).

---

## תבנית הפעלת סוכן משנה (חוזר)

כל סוכן מקבל:

1. **מנדט** — `_COMMUNICATION/team_70/MANDATE_WRITE_SERVICES_{SLUG}_T2_*.md` (או PROMPT מקביל)
2. **קאנון** — `sources/writers_context_pack/` + `COPY_CONTEXT_PACK_v1.0/`
3. **רפרנס טון** — הגשת BCS המתוקנת (לא להעתיק עובדות)
4. **פלט חובה** — קובץ אחד ב-`_COMMUNICATION/team_70/SUBMISSION_DRAFT_SERVICES_{SLUG}_T2_{DATE}_v1.md`
5. **איסורים** — לא לערוך `NIMROD_BIO_WRITING_STATE.md` · לא Implementation Push · לא `approved`

### צ'קליסט team_100 אחרי הגשה

- [ ] קובץ קיים בנתיב הנכון
- [ ] פורמט מנדט §7 (Intake/Brief/Draft/Media/QA)
- [ ] `process_compliance_check` — Draft Submission בלבד
- [ ] ביקורת עורכת → `EDITORIAL_REVIEW_*`
- [ ] עדכון `NIMROD_BIO_WRITING_STATE.md` + מכסנית (אם מוכן)

---

## לוג צי

| ts | agent_id | page | result |
|---|---|---|---|
| 2026-06-15 | fleet-002 | `/services/produce/` | submitted → `owner_review` |

---
*team_100 · wave orchestration · services T2*
