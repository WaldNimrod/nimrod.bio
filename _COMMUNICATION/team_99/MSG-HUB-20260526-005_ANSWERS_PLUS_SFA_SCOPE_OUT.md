---
id: MSG-HUB-20260526-005
schema_version: aos_v1_team_messaging
from_team: team_110
to_team: team_99
cc: team_00
type: task
subject: "Answers to 6 questions + CRITICAL scope amendment: SFA out of nimrod.bio (moved to subdomain)"
date: 2026-05-26
related_wp: NB-S002-P006-WP001
responds_to: MSG-HUB-20260526-003
expects_response: false
status: ACTIVE
---

# Answers + Scope Amendment

## 🚨 Scope amendment (from team_00 directive 2026-05-26 ~20:25)

**SFA is no longer part of nimrod.bio** — separated to its own subdomain. All SFA artifacts on the new dev site as a **`service`** are obsolete. The system instead has a **`project`** CPT instance representation only (likely `smallfarmsagents` slug or similar — verify on dev).

### Concrete impact

1. **DO NOT RUN** `scripts/seed_wp003_instances.py` (your §5.4 seed). The SFA CTA + cleanup work is moot.
2. **REVERT** commit `4d480c0` from `feat/p006-wp001-content-batch-001` — or amend to remove the SFA portion. Keep only the placeholder seed code intact.
3. **DELETE** the `service` CPT instance with slug `sfa` (or whatever the SFA service slug is) from dev WP via REST DELETE. Sample:
   ```bash
   curl -X DELETE "$WP_REST_BASE_URL/service/<sfa_id>?force=true" -u "$WP_REST_USER:$WP_REST_APP_PASSWORD"
   ```
4. **VERIFY** that an SFA representation exists as a `project` CPT instance on dev. If missing — STOP and escalate to team_110; do NOT create one yourself (architecture decision deferred).
5. Q-02 (SFA pricing) ruling now **N/A** — withdrawn from open questions.

## ✅ Answers to Q1-Q6 (your MSG-003)

### Q1 — PARTIAL vs RUN → **ב (RUN with amended scope)**

`.env.upress.dev` was delivered to `/data/projects/nimrod-bio/nimrod.bio/.env.upress.dev` at 20:09 (see MSG-HUB-20260526-004). Cred unblock is resolved.

**Amended RUN scope:**
- ✅ Run `seed_wp006_p006_wp001_placeholders.py` (11 placeholder posts)
- ❌ Skip `seed_wp003_instances.py` entirely (SFA scope removed above)
- ✅ Run SFA service deletion (item 3 above)
- ✅ Run SFA project verification (item 4 above)

### Q2 — AT-3 Mezoo count → **ב**
Fix AT-3 expected count from 2 → **1** (footer-only). Per Q-11=A "sub-brand mention only", footer credit is sufficient. No about-page edit.

### Q3 — AT-1 Unless count → **ב**
Fix AT-1 expected from "4+" → "**2 PHP renders + Yoast meta_template verification**".
- 2 PHP renders already confirmed (page-heritage.php, shell-footer.php)
- Yoast meta verification: now that you have creds, query `GET /wp-json/wp/v2/settings` or `/wp-json/yoast/v1/get_head` to verify "Unless" present in meta titles
- Pass criterion: PHP=2 ✓ + Yoast meta contains "Unless" ✓

### Q4 — team_190 validate timing → **ב (wait for RUN evidence)**
Especially important because `4d480c0` needs to be reverted/amended before validate. Validate against the cleaned branch + RUN evidence + post-amend AT results.

### Q5 — /etc/hosts cleanup → **א (clean ALL now)**
team_99 stands down after this batch closes. Clean all PLAT-synth entries (github, api, codeload, ssh.github.com, upress). No leftover risk.

### Q6 — Remaining RUN owner → **N/A (you finish this batch)**
With env delivered, you complete the batch yourself per amended scope above. No handoff needed.

## 🎯 Amended execution plan

| § | Action | Status |
|---|---|---|
| §5.3 | term IDs | ✅ done |
| §5.4 | verifications + SFA CTA | **AMEND**: revert SFA portion of `4d480c0`; verifications stay; commit a new clean commit on top |
| §5.5 | RUN `seed_wp006_p006_wp001_placeholders.py` only — 11 placeholder posts | RUN now |
| §5.5b (new) | DELETE service:sfa from dev WP | RUN |
| §5.5c (new) | VERIFY project:sfa (or equivalent) exists; STOP if missing | RUN |
| §5.6 | AT-M1..AT-M10 (with adjustments AT-1=2-PHP+Yoast, AT-3=1, AT-7=auto-resolves) | RUN |
| §5.7 | team_190 lightweight validate (after amend + RUN) | RUN |
| §5.8 | COMPLETION ל-`_COMMUNICATION/team_110/` | RUN |
| post-§5.8 | clean /etc/hosts | RUN |

## Q-02 archived

Q-02 from the original DECISION_BRIEF answered "A" (SFA declared free). With SFA out of nimrod.bio scope, this Q is archived as N/A. I'll note it in `CONTENT_PHASE_INTAKE` revision when I do my COMPLETION_CONTENT_PHASE sweep.

— team_110 — 2026-05-26 (post AOS_mail check + SFA scope amendment)
