---
id: MANDATE_NB-S002-P006-WP002_MEDIA_MIGRATION
type: BUILD_MANDATE
from: team_110 (Domain Architect · cursor-composer-2)
to: team_10 (Builder · cursor canonical engine on Mac)
project: nimrod-bio
milestone: V200
wp_id_proposed: NB-S002-P006-WP002
date: 2026-05-26
priority: P1 (gates COMPLETION_CONTENT_PHASE → cutover P005-WP002)
status: PARKED — activates when Batch 001 (P006-WP001) signs COMPLETION
delivery: lives in _COMMUNICATION/team_10/ — pick up via /AOS_mail when team_10 session starts
authorization_chain:
  - team_00 directive 2026-05-26 — "לבצע מיגרציה לכלל התוכן כולל מדיה מהאתר הישן"
  - team_110 GATE_2 architecture (LOD400_DRAFT_NB-S002-P006-WP002_v1.0.0.md)
predecessor_wp: NB-S002-P006-WP001 (must be COMPLETE before this starts)
---

# MANDATE — V200 Content Batch 002 · Media Migration · executor: team_10

## 1. Activation conditions

הפעלה כאשר:
1. ✅ `_COMMUNICATION/team_110/COMPLETION_NB-S002-P006-WP001_*.md` קיים (Batch 001 גמר)
2. ✅ team_10 cursor session פעיל על המק של נמרוד
3. ✅ אישור team_00 לפתיחת batch 002 (אם נדרש — לרוב לא, ה-mandate הזה כבר תחת ה-directive המקורי שלו)

## 2. ה-LOD400

קרא במלואו (8 §§):
`_COMMUNICATION/team_110/LOD400_DRAFT_NB-S002-P006-WP002_v1.0.0.md`

## 3. TL;DR

- **694 media files** מ-`https://www.nimrod.bio` → `https://nimrod-bio-2026.s887.upress.link`
- Mechanism A מומלץ (REST API)
- Mechanism B (FTPS) optimization אם ה-IP שלך = `147.235.197.125`
- AT-M2 הוא הגייט הקריטי: sample 30 inline `<img>` URLs → 30 × HTTP 200

## 4. Required actions

| § | Action | Mode | Estimated time |
|---|---|---|---|
| 5.1 | Pre-flight verify counts + load `.env.upress.dev` | OPS | 2 min |
| 5.2 | Write + run `scripts/migration/migrate_media_v200_p006_wp002.py` | ISOLATED_BRANCH (script is new code) | 25-30 min |
| 5.3 | Rewrite 22 posts HTML via REST | OPS (data only) | 5 min |
| 5.4 | AT-M1 → AT-M5 | OPS self-attest | 5 min |
| 5.5 | COMPLETION ל-`_COMMUNICATION/team_110/` | OPS | 5 min |

## 5. Governance

- **Branch:** `feat/p006-wp002-media-migration` (script + any tooling)
- **Validator:** team_190 lightweight (single new script, low blast radius)
- **Iron Rule #7:** the migration touches WP DB (POSTs to media + posts) — that's WP DB, not AOS DB. No hub API auth needed.
- **Secret handling:** `WP_REST_APP_PASSWORD` from `.env.upress.dev` — never echo to logs / commits / chat
- **Idempotency:** script must be safely re-runnable. Track state in `scripts/migration/state/migrate_media_progress.json`

## 6. STOP conditions — escalate to team_110

- Source download fails for >10 files consecutively → likely rate limit or Cloudflare block
- Upload fails with 5xx >3 times → uPress quota or disk issue
- AT-M2 sample shows >5% 404 rate → URL rewrite logic wrong
- Any file >50MB blocked by WP upload limit
- post HTML edit causes regressions on `/blog/` index render

## 7. Notes / Findings expected (will land in COMPLETION)

ה-Batch 001 חשפו דפוס שאני מצפה ש-Batch 002 גם יחשוף:
- ה-MANDATE שלי הזה עלול לכלול הנחות שגויות לגבי mechanism / endpoints / file paths
- כשאתה מגלה — תקן, תעד, ותמשיך לבצע. לא תחזור אליי על כל schema/path correction
- escalations רק לדברים שאתה לא יכול להמשיך בלעדיהם (כמו authority / blocker / scope creep)

— team_110 — 2026-05-26 (parked; activates post Batch 001 COMPLETION)
