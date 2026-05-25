---
type: SPEC_AMENDMENT
from: team_100 (nimrodbio_arch — Cursor's Claude)
to: team_10 (nimrodbio_build — Cursor)
wp_id: NB-S002-P005-WP001
project: nimrod-bio
milestone: V200
date: 2026-05-25
gate: L-GATE_BUILD (cycle 1 PASS_WITH_CONDITIONS) → amendment v1.1.0 → L-GATE_VALIDATE cycle 1.1
priority: HIGH
supersedes: _COMMUNICATION/team_10/SPEC_AMENDMENT_NB-S002-P005-WP001_SMTP_v1.0.0.md
scope_change_from_v1.0.0: SMTP source amended from external Gmail → uPress native (inbox.co.il)
authorization: DECISION_V200_SMTP_2026-05-25_v1.0.0.md (amended sequencing — Q2=C, Q3=B)
methodology_ref: _aos/methodology/AOS_FIX_CYCLE_DISCIPLINE_v1.0.0.md
---

# SPEC AMENDMENT v1.1.0 — NB-S002-P005-WP001 — SMTP via uPress native

**לצוות 10 (Cursor):**

זה SUPERSEDES של v1.0.0. שינוי יחיד: SMTP source הוא **uPress native** (`inbox.co.il` mailbox `contact@nimrod.bio`), לא Gmail. כל השאר (wp-mail-smtp plugin, fix-cycle pattern, A12 retest, doc updates) נשאר.

## What changed from v1.0.0

| | v1.0.0 (deprecated) | **v1.1.0 (active)** |
|---|---|---|
| SMTP server | smtp.gmail.com:587 | uPress SMTP (host TBD from control panel) |
| Username | Gmail account (e.g. nimrod.bio.ops@gmail.com) | **`contact@nimrod.bio`** |
| Password | Gmail App Password (16-char no spaces) | uPress mailbox password (generated in panel) |
| From email | `nimrod@mezoo.co` | **`contact@nimrod.bio`** |
| Setup pre-req | team_00 creates Gmail + 2FA + App Password | team_00 creates uPress mailbox |
| External deps | Gmail (external service) | None — fully on uPress |

## Preconditions (AOS_FIX_CYCLE §2)

### Reproduction artifact

Same as v1.0.0 — A12 currently fails on email-delivery half.

### Minimal failing case

Same.

### Impacted surfaces

- WP plugins active list (one new plugin: `wp-mail-smtp`) — same as v1.0.0
- WP options table (DB) — plugin stores SMTP config
- `docs/CUTOVER_READINESS_REPORT_2026-05-25.md` — needs amendment
- `docs/qa_form_smtp_test_2026-05-25.md` — needs new evidence row
- `.env.upress.dev` — block 10 SMTP fields populated by team_00 with uPress values

**Still 1 subsystem + 2 docs + env. Within fix-cycle scope per §3.**

## Phase A — team_10 install plugin

⚠️ **Hold gate:** wait for team_00 confirmation that uPress mailbox `contact@nimrod.bio` exists AND password is in their possession AND `.env.upress.dev` SMTP block has SMTP_HOST + SMTP_PORT filled.

```bash
set -a; source .env.upress.dev; set +a

# Verify env block 10 was filled by team_00
[[ -n "$SMTP_HOST" && -n "$SMTP_PORT" ]] || { echo "❌ SMTP_HOST / SMTP_PORT not set in env"; exit 1; }
echo "✓ SMTP target: $SMTP_USERNAME @ $SMTP_HOST:$SMTP_PORT"

# Install wp-mail-smtp plugin via REST
curl -X POST -u "$WP_REST_USER:$WP_REST_APP_PASSWORD" \
     "$WP_REST_BASE_URL/wp/v2/plugins" \
     -H "Content-Type: application/json" \
     -d '{"slug":"wp-mail-smtp","status":"active"}'

# Verify
curl -sk -u "$WP_REST_USER:$WP_REST_APP_PASSWORD" "$WP_REST_BASE_URL/wp/v2/plugins" \
  | python3 -c "
import json,sys
d=json.load(sys.stdin)
plug=[p for p in d if 'wp-mail-smtp' in p.get('plugin','')]
print('installed' if plug else 'MISSING', '| status:', plug[0].get('status') if plug else '-')"
```

After install, hand back to team_00 with link + summary of values from env:

> פתח: `https://nimrod-bio-2026.s887.upress.link/wp-admin/admin.php?page=wp-mail-smtp`
> הזן:
> - Mailer: **SMTP** (Other SMTP)
> - SMTP Host: `<SMTP_HOST from env>`
> - Encryption: `<SMTP_ENCRYPTION from env — TLS or SSL>`
> - SMTP Port: `<SMTP_PORT>`
> - Authentication: **ON**
> - SMTP Username: `contact@nimrod.bio`
> - SMTP Password: <מהקופסה של uPress mailbox — לא ב-env>
> - From Email: `contact@nimrod.bio`
> - From Name: `nimrod.bio`

## Phase B — team_00 UI config

(team_00 action — outside team_10's scope.)

## Phase C — team_10 verify A12

Same workflow as v1.0.0:

```bash
# Option 1: built-in test email (faster path)
# Admin → WP Mail SMTP → Tools → Email Test → "nimrod@mezoo.co" → Send
# Expected: arrives within seconds

# Option 2: A12 official path — actual form submit
NONCE=$(curl -sk "$UPRESS_DEV_URL_HTTP/contact/" | grep -oP 'name="nb_contact_nonce" value="\K[^"]+')
curl -X POST -i \
  -d "nb_contact_nonce=$NONCE" \
  -d "action=nb_contact_submit" \
  -d "name=A12 Test (cycle 1.1)" \
  -d "email=a12-test@example.com" \
  -d "phone=0500000000" \
  -d "topics[]=soil" \
  -d "message=A12 SMTP via uPress mailbox verification — must be at least 20 chars to pass validation" \
  "$UPRESS_DEV_URL_HTTP/wp-admin/admin-post.php" \
  | head -3
# Expected: 302 → /contact/?status=ok

# team_00 checks the destination inbox (either contact@nimrod.bio itself,
# or wherever it's forwarded — nimrod@mezoo.co per Q3 amended)
```

team_00 chat confirmation ("הגיע" / "did not arrive") = sufficient evidence.

## Phase D — team_10 update docs

Same as v1.0.0 §Phase D:

1. Append section to `_COMMUNICATION/team_10/COMPLETION_NB-S002-P005-WP001.md`:

   ```markdown
   ## SMTP fix cycle 1.1 (2026-05-25)

   | Item | Status | Evidence |
   |---|---|---|
   | uPress mailbox contact@nimrod.bio created | DONE | team_00 chat confirmation |
   | .env.upress.dev block 10 populated | DONE | grep SMTP_HOST $ENV |
   | wp-mail-smtp plugin installed + active | DONE | curl REST /wp/v2/plugins |
   | team_00 configured SMTP via admin UI | DONE | team_00 chat confirmation |
   | A12 form submit | DONE | curl POST → 302 to ?status=ok |
   | A12 email arrived in destination inbox | DONE | team_00 confirmed |
   ```

2. Edit `docs/CUTOVER_READINESS_REPORT_2026-05-25.md`:
   - Remove SMTP from Waivers/Deferred section
   - Move A12 to PASS
   - Note: From identity = `contact@nimrod.bio` (native uPress mailbox, no Gmail)
   - Reaffirm overall signature

3. Edit `docs/qa_form_smtp_test_2026-05-25.md` — add cycle 1.1 evidence row.

4. git add + commit + push (commit message: `fix(smtp): WP002 P005 cycle 1.1 — uPress native SMTP via contact@nimrod.bio mailbox; A12 PASS`).

## Exit criteria

- [ ] team_00 confirmed uPress mailbox `contact@nimrod.bio` exists + SMTP_HOST/PORT filled in .env.upress.dev
- [ ] wp-mail-smtp plugin active on dev (REST verifies)
- [ ] team_00 confirmed SMTP plugin UI configured (chat)
- [ ] A12 form submit → 302 status=ok
- [ ] team_00 confirmed email arrived in destination inbox
- [ ] COMPLETION addendum written
- [ ] CUTOVER_READINESS_REPORT updated (SMTP no longer deferred)
- [ ] git push

## Out of scope

- SPF/DKIM DNS records — auto-managed by uPress, no manual DNS edits needed
- Bounce monitoring — V300 if deliverability needs hardening
- `nimrod@nimrod.bio` mailbox — V300 if multiple inbound addresses wanted

## Risks (revised for uPress native)

| Risk | Likelihood | Impact | Mitigation |
|---|---|---|---|
| uPress plan does not include email service | L | M | team_00 verifies in step 1 of UPRESS_MAILBOX_REQUEST; fallback to Gmail (v1.0.0 path) if needed |
| uPress SMTP host varies per plan/region | M | L | team_00 records exact host from control panel into env |
| Mailbox password complexity rejected by plugin | L | L | Use uPress "Generate" button; standard chars only |
| Outbound port 587/465 blocked from PHP context | L | M | Test with plugin's "Email Test" tool first; if fails, escalate to uPress support |
| Email arrives in spam folder of nimrod@mezoo.co (if forwarded) | L | L | Whitelist `contact@nimrod.bio` in destination inbox; uPress SPF/DKIM should mitigate |

## תזמון

- Start: after team_00 confirms uPress mailbox exists (currently pending)
- Target: ≤1 hour from team_00 confirmation
- Block: team_190 VALIDATE held by PING_HOLD until A12 PASS

— team_100 (nimrod-bio) — 2026-05-25 — fix cycle 1.1 (SMTP via uPress native, supersedes v1.0.0)
