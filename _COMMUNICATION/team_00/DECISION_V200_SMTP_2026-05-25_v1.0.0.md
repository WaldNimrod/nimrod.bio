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
**Decision (amended): B — `contact@nimrod.bio`.**
- Site-branded From identity.
- Works natively with Q2 amended (uPress inbox `inbox.co.il`) — mailbox IS this address.
- Original Decision was A (`nimrod@mezoo.co`) but team_00 amended to B 2026-05-25 in chat.

### Q2 — SMTP source server (AMENDED 2026-05-25)
**Original decision:** B — new dedicated Gmail.
**Amended decision:** **C — uPress inbox.co.il native SMTP.**

team_00 caught the omission: "למה לא להשתמש בשרת הדואל inbox.co.il שיש לנו ביופרס עם הדומיין?"

team_100 Decision Brief amendment confirmed: uPress native is strictly superior:
- No external service dependency
- SPF/DKIM auto-managed by uPress
- `contact@nimrod.bio` (Q3=B) is a NATIVE mailbox, not a Gmail send-as alias
- One control panel for everything

team_100 saved memory `feedback_smtp_infra_assumption` — class: always check hosting platform's native offering before proposing external service. Companion to `feedback_lod400_infra_assumptions`.

**Net Q2:** mailbox `contact@nimrod.bio` created in uPress panel; uPress SMTP server (host + port read from panel) used by wp-mail-smtp plugin.

## Sequencing (locked — amended 2026-05-25)

1. **team_00**: open uPress control panel for `nimrod-bio-2026` → Email Accounts → create `contact@nimrod.bio` mailbox + record SMTP host/port + mailbox password (~10 min).
2. **team_10**: install wp-mail-smtp plugin via REST API (`POST /wp/v2/plugins`).
3. **team_00**: log into `https://nimrod-bio-2026.s887.upress.link/wp-admin/admin.php?page=wp-mail-smtp` → enter SMTP credentials (host=uPress SMTP, port=587 or 465, username=`contact@nimrod.bio`, password=mailbox password) + From email=`contact@nimrod.bio` + From name=`nimrod.bio`.
4. **team_10**: verify A12 — submit contact form, confirm email arrives at `nimrod@mezoo.co` (or wherever team_00 routes `contact@nimrod.bio` forwarding).
5. **team_10**: update `_COMMUNICATION/team_10/COMPLETION_NB-S002-P005-WP001.md` with SMTP-fixed addendum; remove SMTP deferral from `docs/CUTOVER_READINESS_REPORT_2026-05-25.md`.
6. **team_190**: resume VALIDATE on updated REPORT.

## Authorization to proceed

team_100 is authorized to:
1. Issue SPEC_AMENDMENT_NB-S002-P005-WP001_SMTP_v1.0.0.md
2. Issue MANDATE_FIX_NB-S002-P005-WP001_v1.1.0.md to team_10
3. PING team_190 to hold VALIDATE pending SMTP completion
4. Update `.env.upress.dev` template with SMTP placeholder vars (for reference only; actual creds stay in WP DB via plugin)

— team_00 (Nimrod) — closed 2026-05-25
