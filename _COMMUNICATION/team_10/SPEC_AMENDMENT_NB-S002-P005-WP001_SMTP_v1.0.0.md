---
type: SPEC_AMENDMENT
from: team_100 (nimrodbio_arch — Cursor's Claude)
to: team_10 (nimrodbio_build — Cursor)
wp_id: NB-S002-P005-WP001
project: nimrod-bio
milestone: V200
date: 2026-05-25
gate: L-GATE_BUILD (cycle 1 PASS_WITH_CONDITIONS) → amendment scope expansion → L-GATE_VALIDATE cycle 1.1
priority: HIGH
scope_expansion: SMTP setup (was V300 deferral, now V200 in-scope per team_00 directive)
authorization: DECISION_V200_SMTP_2026-05-25_v1.0.0.md (team_00 sign-off)
methodology_ref: _aos/methodology/AOS_FIX_CYCLE_DISCIPLINE_v1.0.0.md
---

# SPEC AMENDMENT — NB-S002-P005-WP001 — SMTP scope expansion

**לצוות 10 (Cursor):**

team_00 הפך את ה-SMTP-deferral-to-V300 → SMTP נדרש ב-V200. זה fix cycle ממוקד (≤1 hour work, 1 subsystem = WP plugins/config).

## Preconditions (AOS_FIX_CYCLE §2)

### Reproduction artifact

```bash
set -a; source .env.upress.dev; set +a
curl -sk -u "$WP_REST_USER:$WP_REST_APP_PASSWORD" "$WP_REST_BASE_URL/wp/v2/plugins" | python3 -c "
import json,sys
d=json.load(sys.stdin)
smtp=[p for p in d if 'smtp' in p.get('plugin','').lower()]
print('wp-mail-smtp installed:', bool(smtp))
print('active:', any(p.get('status')=='active' for p in smtp))"
# Currently: not installed
```

### Minimal failing case

A12 of P005-WP001 (form submit + email arrives at admin inbox) currently fails on email-delivery half. Plugin install + UI config + retest is the bounded fix.

### Impacted surfaces

- WP plugins active list (one new plugin: `wp-mail-smtp`)
- WP options table (DB) — plugin stores its SMTP config there
- `docs/CUTOVER_READINESS_REPORT_2026-05-25.md` — needs amendment removing SMTP deferral
- `docs/qa_form_smtp_test_2026-05-25.md` — needs new evidence row

**1 subsystem (WP plugins+options) + 2 docs. Within fix-cycle scope per §3.**

## Authorized decisions (from DECISION_V200_SMTP_2026-05-25_v1.0.0.md)

| | |
|---|---|
| Mechanism | WP Mail SMTP plugin (slug `wp-mail-smtp`) |
| Gmail account | new dedicated `nimrod.bio.ops@gmail.com` (or close variant — team_00 chose final name) |
| From email | `nimrod@mezoo.co` |
| Credentials enter via | WP admin UI (team_00 hands-on) — NOT via env file (DB-encrypted by plugin) |

## Phase A — team_10 install plugin (preceded by team_00 Gmail creation)

⚠️ **Hold gate:** wait for team_00 confirmation that Gmail account exists AND App Password is in their possession. Do NOT proceed without confirmation.

```bash
set -a; source .env.upress.dev; set +a

# 1. Install wp-mail-smtp plugin via REST
curl -X POST -u "$WP_REST_USER:$WP_REST_APP_PASSWORD" \
     "$WP_REST_BASE_URL/wp/v2/plugins" \
     -H "Content-Type: application/json" \
     -d '{"slug":"wp-mail-smtp","status":"active"}'

# 2. Verify
curl -sk -u "$WP_REST_USER:$WP_REST_APP_PASSWORD" "$WP_REST_BASE_URL/wp/v2/plugins" \
  | python3 -c "
import json,sys
d=json.load(sys.stdin)
plug=[p for p in d if 'wp-mail-smtp' in p.get('plugin','')]
print('installed' if plug else 'MISSING', '| status:', plug[0].get('status') if plug else '-')"
```

After install, hand back to team_00 with link:
> `https://nimrod-bio-2026.s887.upress.link/wp-admin/admin.php?page=wp-mail-smtp`
> פתח, הגדר Mailer=SMTP, Host=smtp.gmail.com, Port=587, Encryption=TLS, Auth=ON, Username=<your Gmail>, Password=<App Password>, From Email=nimrod@mezoo.co, From Name=nimrod.bio

## Phase B — team_00 UI config

(team_00 action — described in DECISION §sequencing step 3. Outside team_10's scope.)

## Phase C — team_10 verify A12

After team_00 confirms SMTP config saved in plugin UI:

```bash
# 1. Use plugin's built-in test-email button (one option):
#    Admin → WP Mail SMTP → Tools → Email Test → "test@nimrod@mezoo.co" → Send
#    Should arrive in inbox within seconds

# 2. OR submit the actual contact form via curl (A12 official path):
curl -X POST -i \
  -d "nb_contact_nonce=$(curl -sk "$UPRESS_DEV_URL_HTTP/contact/" | grep -oP 'name="nb_contact_nonce" value="\K[^"]+')" \
  -d "action=nb_contact_submit" \
  -d "name=A12 Test" \
  -d "email=test-from-a12@example.com" \
  -d "phone=0500000000" \
  -d "topics[]=soil" \
  -d "message=A12 SMTP verification message must have at least 20 chars to pass validation" \
  "$UPRESS_DEV_URL_HTTP/wp-admin/admin-post.php" \
  | head -3
# Expected: 302 redirect to /contact/?status=ok

# 3. team_00 checks nimrod@mezoo.co inbox — confirms arrival
```

team_00 chat confirmation = sufficient evidence ("הגיע" / "did not arrive").

## Phase D — team_10 update docs

1. Append section to `_COMMUNICATION/team_10/COMPLETION_NB-S002-P005-WP001.md`:
   ```markdown
   ## SMTP fix cycle 1 (2026-05-25)
   
   | Item | Status | Evidence |
   |---|---|---|
   | wp-mail-smtp plugin installed + active | DONE | curl REST /wp/v2/plugins |
   | team_00 configured SMTP via admin UI | DONE | team_00 chat confirmation |
   | A12 form submit | DONE | curl POST → 302 to ?status=ok |
   | A12 email arrived at nimrod@mezoo.co | DONE | team_00 confirmed |
   ```

2. Edit `docs/CUTOVER_READINESS_REPORT_2026-05-25.md`:
   - Remove "SMTP deferred to V300" from waivers/deferred section
   - Move A12 to PASS in the evidence table
   - Update overall signature evaluation (CONDITIONAL GO likely still stands due to broken link + Lighthouse, but one fewer deferral)

3. git add + commit + push.

## Exit criteria

- [ ] team_00 confirmed Gmail + App Password ready (chat or note in `_COMMUNICATION/team_00/`)
- [ ] wp-mail-smtp plugin active on dev (REST verifies)
- [ ] team_00 confirmed SMTP plugin UI configured (chat)
- [ ] A12 form submit → 302 status=ok
- [ ] team_00 confirmed email arrived in inbox
- [ ] COMPLETION addendum written
- [ ] CUTOVER_READINESS_REPORT updated (SMTP no longer deferred)
- [ ] git push

## Out of scope

- Site-branded From email (`contact@nimrod.bio`) — V300
- DKIM/SPF records on nimrod.bio DNS — V300 if deliverability needs hardening
- Bounce monitoring / dashboard — V300
- Migration of plugin config to env-driven (B alternative from Decision Brief) — V300 if rotation cadence demands

## Risks

| Risk | Likelihood | Impact | Mitigation |
|---|---|---|---|
| Gmail App Password rejected by uPress firewall outbound | L | M | Test with plugin's test-email button BEFORE checking form path |
| 2FA not enabled on Gmail | L | L | App Password generation requires 2FA per Google's flow — team_00 enables in step 1 |
| uPress nginx + WP collide with plugin's REST setup endpoint | L | L | Plugin v3+ avoids REST setup; use admin UI |
| Email arrives in spam folder | M | L | Check spam; for V200 a single confirmation suffices. Production deliverability hardening (SPF/DKIM) → V300 |

## תזמון

- Start: after team_00 confirms Gmail exists (currently pending)
- Target: ≤1 hour from team_00 confirmation
- Block: team_190 VALIDATE held by PING_NB-S002-P005-WP001_SMTP_HOLD until A12 PASS

— team_100 (nimrod-bio) — 2026-05-25 — fix cycle 1 (SMTP scope expansion)
