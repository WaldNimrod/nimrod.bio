---
type: VALIDATE_REQUEST
from: team_10 (Builder · Cursor Codex)
to: team_190 (Validator)
wp_id: NB-S002-P006-WP002
project: nimrod-bio
milestone: V200
program: P006
date: 2026-05-27
gate: L-GATE_VALIDATE
track: A · STANDARD
priority: HIGH
lod400_ref: _COMMUNICATION/team_110/LOD400_DRAFT_NB-S002-P006-WP002_v1.0.0.md
completion_ref: _COMMUNICATION/team_110/COMPLETION_NB-S002-P006-WP002_2026-05-27_v1.0.0.md
branch: feat/p006-wp002-media-migration
---

# VALIDATE_REQUEST — NB-S002-P006-WP002 — Media migration

team_10 completed Batch 002 media migration and requests independent validation.

## Validate scope

1. Review migration implementation and hardening in:
   - `scripts/migration/migrate_media_v200_p006_wp002.py`
   - `scripts/migration/_lib.py`
2. Verify acceptance evidence from generated artifacts:
   - `scripts/migration/state/migrate_media_report.json`
   - `scripts/migration/state/migrate_media_progress.json`
   - `scripts/migration/state/url_map.json`
   - `scripts/migration/state/pre_rewrite_posts_backup.json`
3. Validate completion package:
   - `_COMMUNICATION/team_110/COMPLETION_NB-S002-P006-WP002_2026-05-27_v1.0.0.md`
4. Independently replay core outcomes:
   - migrated posts image URLs return 200 sample check
   - featured media coverage
   - SFA media exclusion

## Key results to confirm

- old media header total: `694`
- dev media total now above source baseline (delta-aware)
- AT-M2: `30/30` HTTP 200
- AT-M3: `17/17` featured coverage (`100%`)
- AT-M6: SFA skipped count `9`
- known source exception: WooCommerce placeholder URL returns source 404

## Extra notes for validator

- Build required resumable reruns due transient network issues (`Errno 51`, timeout, broken pipe) but stateful progress is preserved.
- SVG upload failures were resolved by adding SVG MIME allowlist in:
  - `nimrod.bio/wp-content/mu-plugins/sfagent-allow-json.php`
- No `_aos/` writes were made.

## Expected verdict artifact

Please return:

`_COMMUNICATION/team_190/VERDICT_NB-S002-P006-WP002_L-GATE_VALIDATE_v1.0.0.md`

with PASS/PASS_WITH_FINDINGS/FAIL + evidence-by-path and route recommendation.

---

team_10 -> team_190 · 2026-05-27
