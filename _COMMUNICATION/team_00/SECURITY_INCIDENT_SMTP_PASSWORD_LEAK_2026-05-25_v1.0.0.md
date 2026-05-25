---
type: SECURITY_INCIDENT
from: team_100 (nimrodbio_arch — Cursor's Claude)
to: team_00 (Nimrod · Principal)
project: nimrod-bio
milestone: V200
date: 2026-05-25
severity: HIGH
category: secret disclosure (chat exposure)
status: ACKNOWLEDGED — rotation required
---

# SECURITY INCIDENT — SMTP password leaked in chat

## What happened

While searching `.env` files in sibling projects on the local filesystem for `agent@nimrod.bio` SMTP credentials, team_100 echoed the current SMTP password value to chat output as part of a "findings summary" table. The password value is now in the conversation transcript.

**Affected credential:**
- Account: `agent@nimrod.bio` (uPress mailbox, `smtp.inbox.co.il`)
- Used by: SmallFarmsAgents site notifications (legacy + active env) + intended for nimrod-bio V200 SMTP

**team_00 detection:** "שימו לב שחשפתם סיסמה בצ׳ט!!! אנחנו נצטרך לעדכן סיסמה חדשה."

## Root cause

team_100 used `grep` + chat-echo pattern when reading env files, instead of confirm-presence-without-display pattern. The agent's mental model treated "found credentials" as something to report by value rather than by location.

Memory saved to prevent recurrence: `feedback_secret_redaction.md` (severity: CRITICAL).

## Required actions

### 1. team_00 — rotate password in uPress (~5 min)

URL: uPress control panel → Email Accounts → `agent@nimrod.bio` → Change Password.

- Use uPress "Generate" button OR password manager to create new 14+ char password
- ⚠️ **Do NOT type the new password to me in chat.** Save it directly to the env files below (file write, not chat echo)

### 2. team_00 — update env files with new password (cross-project)

Files holding the OLD password that need rotation:

```
/Users/nimrod/Documents/SmallFarmsAgents/.env
  → EMAIL_PASSWORD=<new>

/Users/nimrod/Documents/SmallFarmsAgents/.env.pre_rotation_20260523_2314
  → ARCHIVE (do not update; pre-rotation snapshot — note: contained older password)

/Users/nimrod/Documents/SmallFarmsAgents/.env.legacy_2026-05-23
  → ARCHIVE (older rotation snapshot)
```

After rotation:
- The two `.env.{pre_rotation,legacy}` snapshots become double-stale (older + the now-leaked one). Recommend `git rm` or move to a separate archive directory outside the active project.

### 3. team_00 — verify SmallFarmsAgents still sends mail after rotation

```bash
cd /Users/nimrod/Documents/SmallFarmsAgents
# whatever its smoke test is — confirm email alerts still arrive at nimrod@mezoo.co
```

If SmallFarmsAgents notifications break — fix that first, then continue with nimrod-bio.

### 4. team_00 — set SMTP password in WP Mail SMTP UI (nimrod-bio)

This is the original sequencing step 5 of `DECISION_V200_SMTP_2026-05-25_v1.0.0.md`, but with the **new** password — never the leaked one.

URL: `https://nimrod-bio-2026.s887.upress.link/wp-admin/admin.php?page=wp-mail-smtp`

Use:
- SMTP Host: `smtp.inbox.co.il`
- Port: `587`
- Encryption: `TLS`
- Auth username: `agent@nimrod.bio`
- Auth password: `<the new rotated password>`
- From email: `n@nimrod.bio` (existing mailbox per team_00 confirmation, forwards to nimrod@mezoo.co)
- From name: `nimrod.bio`

### 5. team_100 — adjust contact form handler

Per team_00 directive: recipient = `nimrod@mezoo.co`. Currently `contact-form-handler.php` uses `get_option('admin_email')` which was set in WP002 to `admin@meoo.co`.

Two equivalent fixes:
- (A) `wp_mail` constant override in handler: set `$to = 'nimrod@mezoo.co'` directly (hardcoded, version-controlled)
- (B) Change WP admin_email to `nimrod@mezoo.co` via REST setting (DB, runtime)

team_10 to pick during fix cycle 1.1. team_100 recommends **A** — explicit and version-tracked.

## Verification gate (before declaring closed)

- [ ] New password generated in uPress + saved to env file (NOT echoed to chat)
- [ ] SmallFarmsAgents `.env` updated with new password
- [ ] SmallFarmsAgents email alerts still work (confirmed by team_00)
- [ ] wp-mail-smtp plugin installed + configured with new password
- [ ] Test email from nimrod-bio contact form arrives at nimrod@mezoo.co
- [ ] Old `.env.legacy_2026-05-23` and `.env.pre_rotation_*` removed or archived outside active path
- [ ] team_100 self-review: chat transcripts (this session) contain leaked value at known location; no further references planned

## Acknowledgment of agent error

team_100 (Cursor's Claude) acknowledges:
- The leak was the agent's fault, not the user's.
- The cost (one rotation cycle + downstream env updates) is the agent's responsibility to drive to closure.
- The pattern is now memorized and will be enforced in future operations.

— team_100 (nimrod-bio) — 2026-05-25
