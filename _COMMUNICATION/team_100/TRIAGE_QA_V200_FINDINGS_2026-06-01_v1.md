# TRIAGE + ROUTING — V200 QA Findings — team_100 — v1

**Date:** 2026-06-01
**Author:** team_100
**To:** team_35 (build/UI) · team_00 (visibility)
**Type:** TRIAGE / FINDINGS ROUTING
**Source:** `_COMMUNICATION/team_50/QA_REPORT_V200_2026-06-01_v1.md` (verdict: PASS_WITH_FINDINGS)
**Governs:** extends `MANDATE_TEAM_35_UI_TEMPLATES_2026-06-01_v1.md`

## team_100 decision
**ACCEPT** team_50's PASS_WITH_FINDINGS. Copy + lock compliance verified (0 forbidden-term hits across all 12 pages; one-restaurant fact, SFA-as-community-tool, no retired terms all confirmed). team_100 independently re-confirmed both S2 findings on live dev. No STOP conditions. Build findings routed to team_35 below.

## Findings → team_35 (fold into existing mandate)

### F-001 (S2) — Contact email mismatch — **owner-decided**
- **Observed:** form delivers to `nimrod@mezoo.co` (WP `admin_email`, the owner's real inbox — works); page publicly displays `nimrod@nimrod.bio` (not a provisioned mailbox → would bounce).
- **Team 00 decision (2026-06-01):** email must work correctly; a dedicated site mailbox is wanted **later**, not now. For now WhatsApp is the preferred, fast contact path.
- **Action NOW (team_35):**
  1. Make **WhatsApp the clearly-preferred** contact (prominent primary CTA; `wa.me/972547776770`).
  2. **Remove the displayed `nimrod@nimrod.bio` mailto** from the contact page (and any other surface — about CTA, footer) — do not publish a non-working address.
  3. Keep the contact **form** (it delivers correctly to the owner inbox) as the secondary path; verify delivery still works after changes.
- **DEFERRED (future task, not now):** provision a dedicated `nimrod@nimrod.bio` mailbox/alias on uPress mail (inbox.co.il) forwarding to `nimrod@mezoo.co`; once live, restore a branded email display. (No date set.)

### F-002 (S2) — Home "כל השירותים" → `/services/` returns 404
- The services archive/landing page does not exist. **Action:** create a `/services/` landing/archive template (fits the mandate's "complete missing templates" — T1/T2/archive states), listing the service CPTs (consulting-hydro, consulting-agro, teaching, bcs, nursery, hydro-greenhouse, produce). If a services index is out of scope for now, repoint the home link to an existing destination — but the archive is the correct fix. Confirm with team_100 if scope is unclear.

### F-003 (S3) — Home BCS carousel card routes to `/world/soil/` instead of `/services/bcs/`
- **Action:** fix the BCS card link target → `/services/bcs/`.

### F-004 (S3) — Hero image bleeds ~101px past desktop viewport (no h-scroll)
- **Action:** contain the hero image within the viewport at desktop (1440); no overflow. (No horizontal scroll today, but the bleed is visible — tighten in the UI-precision pass.)

## INFO items (already in team_35 mandate scope — confirm, not new)
- Media placeholders on SFA/TikTrack/projects (await owner/domain photos — do not block).
- World card "0 פעילויות" counts unwired → wire to CPT counts.
- SFA/TikTrack external URLs hardcoded (live, working) → make data-driven via `_nb_external_url` project meta.

## Cutover note (team_00)
Before production cutover: confirm a fresh contact-form test delivery lands in `nimrod@mezoo.co`. Production promotion (`nimrod.bio`) remains a separate step.

*team_100 | triage + routing | 2026-06-01 | QA PASS_WITH_FINDINGS accepted; 4 findings → team_35*
