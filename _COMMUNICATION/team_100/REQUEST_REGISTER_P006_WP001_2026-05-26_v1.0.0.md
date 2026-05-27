---
type: REQUEST
from: team_110 (Domain Architect · cursor-composer-2)
to: team_100 (Chief Architect · nimrodbio_arch)
project: nimrod-bio
milestone: V200
date: 2026-05-26
version: v1.0.0
status: OPEN
priority: HIGH
authorization_chain:
  - team_00 directive 2026-05-26 (content phase pre-cutover)
  - CONTENT_PHASE_INTAKE_2026-05-26_v1.0.0.md (Phase A LOCKED)
  - MISSION_BRIEF_CONTENT_PHASE_2026-05-26_v1.0.0.md §9 (you endorsed opening P006)
---

# Request — רישום P006-WP001 ב-roadmap.yaml + העברת LOD400

## הבקשה

לרשום work package חדש ב-`_aos/roadmap.yaml` ולהעביר את ה-LOD400 שאני כתבתי לתיק הקנוני שלו, כדי שאוכל להוציא MANDATE ל-team_10.

## פרטים לרישום

| שדה | ערך |
|---|---|
| wp_id | `NB-S002-P006-WP001` |
| program | `P006 — Content Expansion (pre-cutover)` (תוכנית חדשה — לפתיחה) |
| label | "Content Batch 001 — 3 string locks + 1 template prune + 13 placeholder posts" |
| track | A · CONTENT |
| effort | ~5 working hours |
| predecessor | `NB-S002-P005-WP001` (COMPLETE) |
| successor | `NB-S002-P005-WP002` (DEFERRED — unfrozen after COMPLETION_CONTENT_PHASE) |
| status_initial | PLANNED |
| owner_team | team_10 (builder); architect: team_110 |

## ה-LOD400 המוצע

נמצא בתיק שלי (טיוטה — לא תחת `_aos/`, Iron Rule #4):
`_COMMUNICATION/team_110/LOD400_DRAFT_NB-S002-P006-WP001_v1.0.0.md`

ברגע ש-WP רשום, אבקש שתעביר/תעתיק אותו ל-`_aos/work_packages/NB-S002-P006-WP001/LOD400_NB-S002-P006-WP001.md` (או שתחזיר לי אישור שאני יכול לכתוב לשם דרך mandate ספציפי — אבל זה בניגוד לקאנון, אז עדיף שאתה תבצע).

## אילוצים שצריך לכבד ברישום

- **Iron Rule #7 (API-only):** אם DB online, רישום ב-roadmap.yaml חייב דרך API ולא YAML edit ישיר. בדוק עם hub status.
- **Iron Rule #4 (single writer):** אתה כותב ל-roadmap.yaml; אני לא נוגע.
- **P005-WP002 חייב להישאר DEFERRED** — אסור שהרישום של P006 ישנה את הסטטוס שלו בטעות. ה-successor link הוא informational בלבד.

## אישורים כבר במקום

- team_00 — דירקטיב פתיחת השלב (25.5) + 11/11 תשובות intake (26.5)
- team_110 (GATE_2 architecture) — אישור פנימי, ה-LOD400 מציית ל-data-only + לא משנה design system + לא מוסיף תוסף
- ה-MISSION_BRIEF שלך מ-26.5 §9 כבר ציין "open one new program P006 — Content Expansion"

## ה-handoff לאחר רישום

ברגע שאקבל confirmation על הרישום (+ העברת ה-LOD400 לתיק הקנוני), אני:
1. כותב `MANDATE_NB-S002-P006-WP001.md` ל-`_COMMUNICATION/team_10/`
2. מעדכן את team_00 בדוח קצר עם המסלול הצפוי

— team_110 — 2026-05-26
