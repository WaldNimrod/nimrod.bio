---
id: MANDATE_NB-S002-P007-WP003_CONTENT_FILL
type: BUILD_MANDATE
from: team_110 (orchestrator session)
to: team_10 (Builder · Cursor Composer)
cc: team_00 (content owner), team_110 (orchestrator)
project: nimrod-bio
milestone: V200
wp_id: NB-S002-P007-WP003 (proposed)
wave: 3 of 4 (P007)
date: 2026-05-27
priority: P1
status: PARKED — activates after Wave 2 RESPONSE_INVENTORY signed by team_00
engine: Cursor Composer (build) + WP admin UI (team_00 direct)
wp_registration_status: PENDING_DB_DOMAIN_PROVISIONING
predecessor: NB-S002-P007-WP002 (RESPONSE_INVENTORY)
---

# Wave 3 MANDATE — Content Fill + Integration

## 1. Mission

Apply team_00's filled content from RESPONSE_INVENTORY into the dev site. Three sub-batches operating independently or in sequence per team_00 cadence.

## 2. Inputs

| # | Path | Purpose |
|---|---|---|
| 1 | `_COMMUNICATION/team_00/RESPONSE_INVENTORY_P007_*.md` | team_00 fill commitment |
| 2 | `_COMMUNICATION/team_00/INVENTORY_TEXTS_*.md` | text items spec |
| 3 | `_COMMUNICATION/team_00/INVENTORY_MEDIA_*.md` | media items spec |
| 4 | `_COMMUNICATION/team_00/INVENTORY_DECISIONS_*.md` | decision items |
| 5 | Live dev WP REST + admin UI | execution surface |

## 3. Sub-batches (run independently)

### 3.1 TEXTS fill — `feat/p007-wp003-texts-fill` branch
- For each text item in inventory: WP REST POST/PATCH to update post body / title / SEO meta
- Remove `_nb_placeholder=true` from posts where placeholder div is removed
- Backup pre-state: `scripts/p007/state/pre_texts_fill_backup.json`
- Idempotent: skip items already filled

### 3.2 MEDIA fill — `feat/p007-wp003-media-fill` branch
- For each media slot: upload (REST POST /media) or set featured_media on post
- Track in `scripts/p007/state/media_fill_progress.json`
- Verify each uploaded file resolves on dev (HTTP 200)

### 3.3 DECISIONS application — `feat/p007-wp003-decisions-apply` branch
- For each decision: execute its operation (e.g., create project:sfa CPT instance if X1)
- Log to `_COMMUNICATION/team_00/DECISIONS_APPLIED_P007_*.md`

## 4. Per-sub-batch deliverable

`_COMMUNICATION/team_110/COMPLETION_NB-S002-P007-WP003_BATCH-<sub-batch>_<date>_v1.0.0.md`

Must include:
- list of items applied (item_id → before/after)
- skipped items + reason
- failure log
- Wave 4 readiness flag

## 5. Acceptance tests (cumulative per batch + overall WP003)

| # | Criterion | PASS |
|---|---|---|
| AT-F1 | All inventory items closed | each row: applied OR explicitly accepted-as-is |
| AT-F2 | 0 × `_nb_placeholder=true` | (or team_00 explicit acceptance for any remaining) |
| AT-F3 | All media slots filled | (or empty-by-intent flagged) |
| AT-F4 | All decisions applied | RESPONSE_DECISIONS shows status: APPLIED for each |
| AT-F5 | Dev URL still 200 | sanity post each batch |

## 6. Cross-engine note

team_10 = Cursor builder; downstream Wave 4 validator team_190 = Codex → ✓ Iron Rule #1

## 7. STOP conditions

- RESPONSE_INVENTORY missing or incomplete (some items lack team_00 ruling) → STOP, escalate to team_110
- WP REST failures >3 consecutive on same endpoint → STOP, escalate
- Any sub-batch causes dev URL 5xx → STOP, rollback via backup

## 8. Activation prompt (paste to new Cursor session as team_10)

```
═══════════════════════════════════════════════════════════════
TEAM 10 — Builder (Cursor Composer)
ACTIVATION — V200 Wave 3 · Content Fill + Integration
═══════════════════════════════════════════════════════════════

זהות
────
- Team ID: team_10
- Engine: Cursor Composer
- Role: Builder
- Governance: /Users/nimrod/Documents/agents-os/_aos/governance/team_10.md

קונטקסט
───────
- Project: nimrod-bio · Milestone: V200 · P007 Wave 3
- Predecessor: Wave 2 RESPONSE_INVENTORY signed by team_00

המנדט
─────
_COMMUNICATION/team_10/MANDATE_NB-S002-P007-WP003_CONTENT_FILL_v1.0.0.md

המשימה
──────
1. /AOS_mail
2. קרא RESPONSE_INVENTORY + 3 inventory files
3. בצע 3 sub-batches (texts / media / decisions) על branches נפרדים
4. הפק COMPLETION per sub-batch
5. report ל-team_110 orchestrator על completion ה-overall

ETA: ~3-5 שעות (תלוי בנפח inventory)
═══════════════════════════════════════════════════════════════
```

— team_110 (originating session) — 2026-05-27
