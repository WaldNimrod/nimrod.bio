---
id: MANDATE_NB-S002-P007-WP002_COMPLETION_INVENTORY
type: ARCHITECT_MANDATE (inventory build)
from: team_110 (originating session) → team_110 (orchestrator session, self-mandate)
cc: team_00
project: nimrod-bio
milestone: V200
wp_id: NB-S002-P007-WP002 (proposed)
wave: 2 of 4 (P007)
date: 2026-05-27
priority: P1
status: PARKED — activates after Wave 1 COMPLETION
engine: Cursor Composer
wp_registration_status: PENDING_DB_DOMAIN_PROVISIONING
predecessor: NB-S002-P007-WP001 (Wave 1 MCP_QA_REPORT)
---

# Wave 2 MANDATE — Completion Inventory Build (3 lists)

## 1. Mission

Generate **3 structured inventory artifacts** for team_00 to fill, covering:
- Texts (placeholder bodies, TBC, copy)
- Media (featured images, visuals, logos)
- Decisions (open questions, brand choices)

## 2. Inputs

| # | Path | Purpose |
|---|---|---|
| 1 | `_COMMUNICATION/team_50/MCP_QA_REPORT_NB-S002-P007-WP001_*.md` | Wave 1 findings — feeds inventory items |
| 2 | `_COMMUNICATION/team_110/CONTENT_PHASE_INTAKE_2026-05-26_v1.0.0.md` | Original intake (Q1-Q11 + 4 corrections) |
| 3 | `_COMMUNICATION/team_110/COMPLETION_CONTENT_PHASE_2026-05-27_v1.0.0.md` | Sub-phase 1 close (carry-forwards) |
| 4 | Live dev URL via WP REST | Current state truth |
| 5 | `_aos/work_packages/NB-S002-P006-WP001/LOD400_*` (placeholder design) | 11 placeholder posts spec |

## 3. Deliverables (3 separate artifacts)

### 3.1 INVENTORY_TEXTS_NB-S002-P007-WP002_<date>_v1.0.0.md

Path: `_COMMUNICATION/team_00/INVENTORY_TEXTS_NB-S002-P007-WP002_<date>_v1.0.0.md`

Required structure:
- Per-item rows: `slug/page → field → current state → required (description) → priority (P0/P1/P2) → effort (S/M/L)`
- Minimum 15 rows expected (11 placeholders + 5 TBC + about page from nimrod-book + service descriptions + others)
- Group by: posts / pages / templates / SEO meta

### 3.2 INVENTORY_MEDIA_NB-S002-P007-WP002_<date>_v1.0.0.md

Path: `_COMMUNICATION/team_00/INVENTORY_MEDIA_NB-S002-P007-WP002_<date>_v1.0.0.md`

Required structure:
- Per-asset rows: `slot location → current state → required (resolution / format / count) → suggested source → priority`
- Categories: featured images / hero images / service icons / project portfolio / brand assets / logo family

### 3.3 INVENTORY_DECISIONS_NB-S002-P007-WP002_<date>_v1.0.0.md

Path: `_COMMUNICATION/team_00/INVENTORY_DECISIONS_NB-S002-P007-WP002_<date>_v1.0.0.md`

Required structure:
- Per-question rows: `question → context → options (A/B/C) → recommendation → blocker_for`
- Minimum 5 questions (SFA project entry, Mezoo about-page, domain registration, taxonomy finalization, V300 priority handoff)

## 4. Acceptance tests

| # | Criterion | PASS |
|---|---|---|
| AT-I1 | All 3 inventory files exist | files on disk + committed |
| AT-I2 | Texts inventory ≥15 rows | structured per §3.1 |
| AT-I3 | Media inventory ≥10 rows | structured per §3.2 |
| AT-I4 | Decisions inventory ≥5 rows | structured per §3.3 |
| AT-I5 | All Wave 1 findings represented | cross-check MCP_QA_REPORT §8 → at least one inventory row per finding |
| AT-I6 | team_00 can act on each row | no ambiguous "investigate" rows — each is fillable |

## 5. STOP conditions

- Wave 1 MCP_QA_REPORT missing or fails AT-Q1 → STOP
- Dev URL state significantly diverges from Wave 1 baseline (someone touched dev) → STOP

## 6. Exit gate

team_00 reads 3 lists in chat and either:
- (a) commits to filling each item (signs RESPONSE artifact)
- (b) downgrades items to "publish-as-is" with explicit acceptance

team_110 records the response as: `_COMMUNICATION/team_00/RESPONSE_INVENTORY_P007_<date>_v1.0.0.md`

## 7. Activation prompt (paste to orchestrator session when Wave 2 starts)

```
═══════════════════════════════════════════════════════════════
TEAM 110 — Domain Architect (Cursor)
ACTIVATION — V200 Wave 2 · Completion Inventory Build
═══════════════════════════════════════════════════════════════

זהות
────
- Team ID: team_110
- Engine: Cursor Composer
- Role: Domain Architect (originating self-mandate for inventory)
- Governance: /Users/nimrod/Documents/agents-os/_aos/governance/team_110.md

קונטקסט
───────
- Project: nimrod-bio · Milestone: V200 · sub-phase 2 (P007) Wave 2
- Wave 1 (Wave 1 MCP_QA_REPORT) — input
- Dev state: 33 posts (11 placeholder), 10 services, 5 projects, 843 media

המנדט
─────
_COMMUNICATION/team_110/MANDATE_NB-S002-P007-WP002_COMPLETION_INVENTORY_v1.0.0.md

המשימה
──────
1. /AOS_mail
2. קרא Wave 1 MCP_QA_REPORT
3. בנה 3 inventory artifacts ב-_COMMUNICATION/team_00/ (texts/media/decisions)
4. הצג ל-team_00 בצ׳ט — בקש fill commitment
5. כתוב RESPONSE_INVENTORY_P007 לפי תגובת team_00

Out of scope: filling content (Wave 3), QA (Wave 4)
ETA: ~1-2 שעות שלי + תלוי בtime to-respond של team_00
═══════════════════════════════════════════════════════════════
```

— team_110 (originating session) — 2026-05-27
