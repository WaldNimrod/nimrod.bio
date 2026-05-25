---
type: DECISION
from: team_00 (Nimrod · Principal)
to: team_100 (nimrod-bio · Chief Architect)
project: nimrod-bio
milestone: V200
wp_affected: NB-S002-P005-WP001
date: 2026-05-25
version: v1.0.0
status: CLOSED
mechanism: AOS_decide Decision Brief
brief_ref: in-session 2026-05-25 by team_100 (canonical form)
related_iron_rules: [#4, #10]
---

# DECISION — V200 · SMTP setup scope expansion

## Context

team_00 directive 2026-05-25 reversed prior V300 deferral of SMTP. team_100 produced canonical Decision Brief with 3 sub-decisions (mechanism, Gmail account, From email). team_00 confirmed the bundled recommendation.

## Decisions

### Q1 — Mechanism
**Decision: A — WP Mail SMTP plugin (`wp-mail-smtp`).**
- Mature solution; credentials in WP DB (encrypted); UI for rotation.
- Same authority class as P002-WP002 Q5=D — plugin acceptable for infrastructure (not for CPT field UI).
- Iron Rule #4: single writer (DB option) for credentials.

### Q2 — Gmail account
**Decision: B — New dedicated `nimrod.bio.ops@gmail.com` (or equivalent).**
- Clean separation from sibling-project ops mailbox.
- team_00 creates account + generates Gmail App Password (~10 min).
- Avoids any cross-project username leakage in tracked files.

### Q3 — From email displayed to recipients
**Decision: A — `nimrod@mezoo.co`.**
- Reuses Nimrod's existing personal address.
- No additional email forwarding setup needed in V200.
- B/C (`contact@nimrod.bio`, `nimrod@nimrod.bio`) deferred to V300 if site-branded From is desired later.

## Sequencing (locked)

1. **team_00**: create Gmail account `nimrod.bio.ops@gmail.com` (or chosen name) + enable 2FA + generate 16-char App Password (~10 min).
2. **team_10**: install WP Mail SMTP plugin via REST API (`POST /wp/v2/plugins`).
3. **team_00**: log into `https://nimrod-bio-2026.s887.upress.link/wp-admin/options-general.php?page=wp-mail-smtp` → enter SMTP credentials via plugin UI (5 min).
4. **team_10**: verify A12 — submit contact form, confirm email arrives at `nimrod@mezoo.co`.
5. **team_10**: update `_COMMUNICATION/team_10/COMPLETION_NB-S002-P005-WP001.md` with SMTP-fixed addendum; remove SMTP deferral from `docs/CUTOVER_READINESS_REPORT_2026-05-25.md`.
6. **team_190**: resume VALIDATE on updated REPORT.

## Authorization to proceed

team_100 is authorized to:
1. Issue SPEC_AMENDMENT_NB-S002-P005-WP001_SMTP_v1.0.0.md
2. Issue MANDATE_FIX_NB-S002-P005-WP001_v1.1.0.md to team_10
3. PING team_190 to hold VALIDATE pending SMTP completion
4. Update `.env.upress.dev` template with SMTP placeholder vars (for reference only; actual creds stay in WP DB via plugin)

— team_00 (Nimrod) — closed 2026-05-25
