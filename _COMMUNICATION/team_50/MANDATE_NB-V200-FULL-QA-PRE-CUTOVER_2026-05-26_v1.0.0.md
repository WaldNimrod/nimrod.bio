---
id: MANDATE_NB-V200-FULL-QA-PRE-CUTOVER
type: QA_MANDATE
from: team_110 (Domain Architect · cursor-composer-2)
to: team_50 (QA & Functional Acceptance · Cursor Composer)
cc: team_00, team_190
project: nimrod-bio
milestone: V200
wp_id_proposed: NB-S002-P006-WP003 (TBD — pending team_100 registration)
date: 2026-05-26
priority: P1 (gates COMPLETION_CONTENT_PHASE → cutover P005-WP002)
status: PARKED — activates after Batch 001 + Batch 002 both PASS team_190 validate
delivery: lives in _COMMUNICATION/team_50/ — pick up via /AOS_mail when team_50 session starts
authorization_chain:
  - team_00 directive 2026-05-26 — "qa לאתר החדש עי צוות 50"
  - team_110 GATE_2 architecture (this artifact, per team_50 GCR-002 mandatory L2 UI coverage)
predecessor_wps:
  - NB-S002-P006-WP001 (Batch 001 — 11 placeholders + SFA cleanup; team_190 validate IN FLIGHT)
  - NB-S002-P006-WP002 (Batch 002 — media migration + theme SFA cleanup + Yoast Unless; PARKED)
successor: COMPLETION_CONTENT_PHASE signature by team_110 → P005-WP002 cutover unfreeze
---

# MANDATE — V200 Pre-Cutover Full QA Sweep · executor: team_50

## 1. Authority + activation conditions

team_00 directive 2026-05-26: "נדרש qa לאתר החדש עי צוות 50" — confirms team_50 QA as part of pre-cutover gate.

per team_50 GCR-002 (2026-04-19): every L2 WP with user interface requires full UI sweep + DB round-trip verification + scenario matrix coverage. V200 = L2 WP with full UI surface.

### Activation prerequisites (all required before team_50 starts)

| # | Condition | Check via |
|---|---|---|
| 1 | Batch 001 — team_190 PASS verdict | `_COMMUNICATION/team_190/VERDICT_NB-S002-P006-WP001_L-GATE_VALIDATE_*.md` |
| 2 | Batch 002 (media migration) — COMPLETION delivered | `_COMMUNICATION/team_110/COMPLETION_NB-S002-P006-WP002_*.md` |
| 3 | Batch 002 — team_190 PASS verdict (lightweight) | `_COMMUNICATION/team_190/VERDICT_NB-S002-P006-WP002_*.md` |
| 4 | dev URL responsive | `curl -sI https://nimrod-bio-2026.s887.upress.link/` → 200 |

**אל תתחיל לפני שכל 4 התנאים מתקיימים.** team_110 יעדכן את ה-MANDATE לסטטוס `ACTIVE` כשהם מתקיימים.

## 2. Scope — QA sweep matrix

### 2.1 Templates (7 active per V200 spec)

| QA # | Template | URL pattern | What to verify |
|---|---|---|---|
| QA-1 | T7 home | `/` | hero render; 3 worlds tiles (soil/know/code) clickable; footer with "דיגיטל / מיזו" + "Unless"; no broken sections |
| QA-2 | T1 × 3 worlds | `/world/{soil,know,code}/` | each renders distinct content; posts filtered by world; no empty states fail |
| QA-3 | T2 services × 10 | `/services/{slug}/` | each of 10 services renders; SFA URLs (`/services/sfa/`, `/services/seed-t7-sfa/`) → 404 ✓ |
| QA-4 | T3 projects × 5 | `/project/{slug}/` | each renders; (verify: project:sfa decision — currently 5 projects; team_00 X1/X2/X3 ruling pending) |
| QA-5 | T4 single post (33 posts) | `/blog/{slug}/` | sample 10 from migrated 22 + all 11 from Batch 001; Hebrew slugs work end-to-end via URL bar |
| QA-6 | T5 blog index | `/blog/` | shows 33 posts; pagination if >page-size; no rendering errors |
| QA-7 | T8 static × 3 | `/about/`, `/about/heritage/`, `/contact/` | render; contact form sends mail (SMTP round-trip — see QA-7b) |

### 2.2 Scenario matrix per GCR-002 (mandatory for L2 UI)

| # | Scenario | Coverage |
|---|---|---|
| S-1 | Happy path | navigate from home → world → post → contact form submit → success message |
| S-2 | Error/validation | contact form: missing fields, invalid email; expected: error messages render |
| S-3 | Edge case | Hebrew slugs in URL bar (manually type `/blog/אנטרופיה/`); empty world (if any); pagination edge |
| S-4 | Duplicate/conflict | submit contact form twice in 5s; expect: no double-send |
| S-5 | Cancellation | start contact form fill, navigate away; expect: clean state |

### 2.3 Specific verifications (cross-cutting)

| QA # | Test | Pass criterion |
|---|---|---|
| QA-7b | Contact form SMTP round-trip | submission lands in nimrod@mezoo.co inbox within ~30s |
| QA-8 | Placeholder marker visibility | 11/11 placeholder posts show `data-nb-placeholder="true"` div; team_00 awareness (may accept publish-with-marker for launch) |
| QA-9 | Inline images post Batch 002 | sample 30 random `<img src>` URLs from 22 migrated posts — 30 × HTTP 200, **zero 404s** |
| QA-10 | Yoast metas | home + 5 surfaces — meta title + description visible; "Unless" present in at least 1 surface meta |
| QA-11 | Redirects sweep | full 23 × 301 + 6 × 410 from `_aos/work_packages/NB-S002-P004-WP002/` mapping — verify all enforce |
| QA-12 | Lighthouse non-regression | run Lighthouse on home + 2 sample posts; compare to P005-WP001 baseline — no metric WORSE by >5 points (NOT requiring uplift — V300 territory) |
| QA-13 | Services count + SFA deletion | services count = 10; `/wp-json/wp/v2/services/{28,44}` → 404 |
| QA-14 | Sitemap integrity | sitemap_index.xml + post-sitemap renders; 33 posts present; no broken refs |

## 3. NOT in scope (out of QA's mandate)

- Code quality / style → team_90
- Security review → team_190
- Architecture correctness → team_100/110
- Lighthouse UPLIFT (only non-regression) → V300

## 4. Execution mechanics

- **Engine:** Cursor Composer (same as team_110 but different session/role)
- **Activation:** open new Cursor chat, paste activation prompt (see §6 below)
- **Tools:** WP REST API for data verification, `curl` for HTTP checks, Cursor's built-in browser (or open dev URL in regular browser for visual QA)
- **Deliverable:** `_COMMUNICATION/team_50/QA_REPORT_NB-V200-FULL-PRE-CUTOVER_<date>_v1.0.0.md`
- **Verdict format:** PASS / PASS_WITH_FINDINGS / FAIL per AC; aggregate verdict for overall sweep

## 5. STOP conditions

- More than 3 ACs FAIL → STOP, escalate to team_110, route corrective MANDATE to team_10
- Contact form completely broken → STOP, this is a launch blocker
- Inline images >10% 404 rate → STOP, Batch 002 needs rework
- Hebrew slugs broken → STOP, encoding regression
- Otherwise: produce PASS_WITH_FINDINGS with list of minor items for team_00 to accept/reject

## 6. Activation prompt for team_50 session

When all prerequisites in §1 are met, paste this into a new Cursor session:

```
═══════════════════════════════════════════════════════════════
TEAM 50 — QA & Functional Acceptance (Cursor Composer)
ACTIVATION — V200 pre-cutover full QA sweep
═══════════════════════════════════════════════════════════════

זהות
────
- Team ID: team_50
- Engine: Cursor Composer
- Role: QA & Functional Acceptance
- Governance: /data/projects/agents-os/_aos/governance/team_50.md
- GCR-002 binding: full UI sweep + DB round-trip + 5-scenario matrix

קונטקסט
───────
- Project: nimrod-bio (spoke L0)
- Milestone: V200 site rebuild
- State: Batch 001 + Batch 002 PASS team_190 validate
  Dev: https://nimrod-bio-2026.s887.upress.link
  33 posts (22 migrated + 11 placeholder)
  Services 10 (after 2 SFA deletes)
  Projects 5
  694 media files migrated (post Batch 002)
- Production cutover P005-WP002 = next step, GATED on your QA PASS + team_00 approval

המשימה
──────
1. /AOS_mail — קרא MANDATE_NB-V200-FULL-QA-PRE-CUTOVER ב-inbox שלך
2. בצע sweep מלא לפי §2 של ה-MANDATE — 14 QA items + 5 scenarios
3. צור QA_REPORT_NB-V200-FULL-PRE-CUTOVER_<date>_v1.0.0.md
4. הנפק verdict aggregate (PASS / PASS_WITH_FINDINGS / FAIL)
5. STOP per §5 stop conditions אם needed

Out of scope (אסור לגעת):
- code quality (team_90)
- security (team_190)
- Lighthouse uplift (V300)
- nimrod.bio prod URL (cutover not yet)

═══════════════════════════════════════════════════════════════
```

— team_110 — 2026-05-26 (parked; activates post Batch 002)
