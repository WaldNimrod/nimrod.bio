---
type: VALIDATE_REQUEST
from: team_10 (nimrodbio_build — Cursor Codex)
to: team_190 (nimrodbio_val — Codex)
wp_id: NB-S002-P004-WP002
project: nimrod-bio
milestone: V200
program: P004
date: 2026-05-25
gate: L-GATE_VALIDATE
track: A · STANDARD
priority: HIGH
correction_cycle: 1
spec_ref: _aos/work_packages/NB-S002-P004-WP002/LOD400_NB-S002-P004-WP002.md
completion_ref: _COMMUNICATION/team_10/COMPLETION_NB-S002-P004-WP002.md
remediation_ref: _COMMUNICATION/team_10/REMEDIATION_NB-S002-P004-WP002_cycle1_v1.0.0.md
trigger_verdict_ref: _COMMUNICATION/team_190/VERDICT_NB-S002-P004-WP002_v1.0.0.md
build_commit: c434f77a
---

# VALIDATE_REQUEST — NB-S002-P004-WP002 — cycle 1 resubmission

Cycle 1 remediation is complete and pushed. Requesting independent constitutional replay for the previously failing runtime contract (R5/R6/R8/R9).

## What changed in cycle 1

- Added runtime enforcement path for nginx-served dev via MU plugin (without removing `.htaccess` additive block).
- New generator: `scripts/redirects/generate_runtime_mu_plugin.py`
- Generated/deployed plugin: `nimrod.bio/wp-content/mu-plugins/nb-v200-runtime-redirects.php`
- Refreshed verification JSON now shows:
  - redirect `23/23`
  - drop `6/6`
  - keep `2/2`
  - `all_pass=true`
- Explicit probes now show:
  - `/video1/` -> `301`
  - `/grow/` -> `410`
  - heritage encoded slug -> `301 /about/heritage/`

## Evidence pointers

- Spec (SSOT): `_aos/work_packages/NB-S002-P004-WP002/LOD400_NB-S002-P004-WP002.md`
- Initial completion package: `_COMMUNICATION/team_10/COMPLETION_NB-S002-P004-WP002.md`
- Cycle 1 remediation package: `_COMMUNICATION/team_10/REMEDIATION_NB-S002-P004-WP002_cycle1_v1.0.0.md`
- Trigger verdict: `_COMMUNICATION/team_190/VERDICT_NB-S002-P004-WP002_v1.0.0.md`
- Runtime evidence JSON: `docs/redirect_verification_2026-05-25.json`
- Runtime implementation files:
  - `scripts/redirects/generate_runtime_mu_plugin.py`
  - `nimrod.bio/wp-content/mu-plugins/nb-v200-runtime-redirects.php`
  - `scripts/redirects/verify_redirects.py`

## Exact validation ask

Please issue:

`_COMMUNICATION/team_190/VERDICT_NB-S002-P004-WP002_cycle1_v1.0.0.md`

with:

1. Independent R1-R17 results table.
2. Explicit determination for previously failing requirements:
   - R5: 23/23 redirects return 301
   - R6: 6/6 drops return 410
   - R8: `/video1/` -> `/blog/יום-בגינה/`
   - R9: heritage old slug -> `/about/heritage/`
3. Regression confirmation for R10-R13 and R16.
4. Route recommendation (if any remaining blockers).

## Routing prompt (copy/paste)

```text
Validate NB-S002-P004-WP002 cycle 1 remediation (L-GATE_VALIDATE) independently.

Read:
1) _COMMUNICATION/team_190/VERDICT_NB-S002-P004-WP002_v1.0.0.md
2) _COMMUNICATION/team_10/REMEDIATION_NB-S002-P004-WP002_cycle1_v1.0.0.md
3) _aos/work_packages/NB-S002-P004-WP002/LOD400_NB-S002-P004-WP002.md
4) docs/redirect_verification_2026-05-25.json

Re-run runtime probes at minimum for:
- /video1/
- /grow/
- /הזמנת-סל-ירקות-אורגאני-פרדס-חנה-2-2-2-2-2/

Required output:
_COMMUNICATION/team_190/VERDICT_NB-S002-P004-WP002_cycle1_v1.0.0.md
Include R1-R17 evidence table and clear verdict.
```
