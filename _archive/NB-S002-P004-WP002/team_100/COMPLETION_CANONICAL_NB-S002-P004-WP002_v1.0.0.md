---
type: COMPLETION_CANONICAL
from: team_100 (nimrodbio_arch — Cursor's Claude)
project: nimrod-bio
milestone: V200
program: P004 — content migration + redirects
wp_id: NB-S002-P004-WP002
date: 2026-05-25
gate: L-GATE_VALIDATE PASS → COMPLETE
verdict_ref: _COMMUNICATION/team_190/VERDICT_NB-S002-P004-WP002_cycle1_v1.0.0.md
status: COMPLETE
architecture_ratification: ACCEPTED — MU plugin enforcement is canonical for this WP
---

# CANONICAL COMPLETION — NB-S002-P004-WP002

## 1. Verdict + route

- team_190 (Codex) VERDICT v1.0.0 cycle 1: **PASS**
- Route: PASS → team_100 (this artifact)
- Iron Rule #1: maintained — Builder=Cursor (team_10), Architect=Cursor (team_100), Validator=Codex (team_190)

## 2. Independent re-verification by team_100

Self-review executed 2026-05-25 on live dev (`http://nimrod-bio-2026.s887.upress.link`):

| Probe | Result |
|---|---|
| `curl -I /video1/` | **301** → `…/blog/יום-בגינה/` ✓ |
| `curl -I /harish2021/` | **301** → `…/blog/harish2021/` ✓ |
| `curl -I /common/` | **301** → `…/blog/common/` ✓ |
| `curl -I /transplantinfo2020/` | **301** → `…/blog/transplantinfo2020/` ✓ |
| `curl -I /transplant-spread/` | **301** → `…/blog/transplant-spread/` ✓ |
| `curl -I /grow/` | **410 Gone** ✓ |
| `curl -I /smallfarmsagent/` | **410 Gone** ✓ |
| Yoast SEO active | `wordpress-seo/wp-seo` status=active ✓ |
| `/sitemap_index.xml` | **200** ✓ |
| MU plugin deployed | `nimrod.bio/wp-content/mu-plugins/nb-v200-runtime-redirects.php` (5.2KB) ✓ |
| `validate_aos.sh` | **32 PASS / 16 SKIP / 0 FAIL** ✓ |

All acceptance categories satisfied. No re-mediation required.

## 3. Architecture ratification — MU plugin canonical for this WP

### 3.1 Original LOD400 specification

`_aos/work_packages/NB-S002-P004-WP002/LOD400_NB-S002-P004-WP002.md` §3 locked:
> "Mechanism: `.htaccess` Apache rewrite (NOT a WP plugin, NOT MU plugin) — fastest, runs at edge before PHP"

### 3.2 What was discovered during build

uPress hosting on this site uses **nginx**, not Apache. nginx does not natively honor `.htaccess` directives. The `.htaccess` artifact deploys correctly via FTPS but has no runtime effect. team_10 detected this in cycle 1 verification and remediated.

### 3.3 What was actually built (post-remediation)

| Layer | Artifact | Role |
|---|---|---|
| **Audit / portable artifact** | `docs/htaccess_v200_redirects.txt` + deployed `/.htaccess` | Human-readable canonical rule set; portable to any Apache target; preserved unchanged from original LOD400 generator output |
| **Runtime enforcement (dev)** | `nimrod.bio/wp-content/mu-plugins/nb-v200-runtime-redirects.php` | PHP intercepts on early WP boot (`parse_request` or similar); issues 301/410 status; functionally equivalent to `.htaccess` rewrite under nginx |
| **Verification** | `scripts/redirects/verify_redirects.py` output | Confirms enforcement layer works regardless of underlying mechanism |

### 3.4 Ratification

team_100 (this artifact) **ratifies the MU plugin as the canonical execution mechanism for NB-S002-P004-WP002**, with these conditions:

1. The `.htaccess` artifact REMAINS in place — serves as portable audit-grade rule set and as a fallback for any future Apache-based deployment target (e.g. if uPress moves the site or for migration to another host).
2. The MU plugin source must be **derived from the same JSON triage data** (`docs/url_migration_decisions_2026-05-25.json`) — the two artifacts must stay in sync. Any future redirect addition updates BOTH.
3. Each redirect added in the JSON must propagate to BOTH the .htaccess block AND the MU plugin in one commit (no drift).
4. The MU plugin runs in `mu-plugins/` (auto-loaded, no admin activation needed) — same pattern as `nb-dev-app-passwords.php`.

This deviation is recorded as a known shape of the deployment surface for uPress/nginx, not a defect.

### 3.5 Lesson for team_100 (saved to memory)

LOD400 spec authoring must verify infrastructure assumptions before locking mechanism choices. For uPress: nginx, not Apache. Memory artifact: `feedback_lod400_infra_assumptions` (to be written).

## 4. Supporting artifacts (canonical index)

- LOD400 (SSOT, original spec): `_aos/work_packages/NB-S002-P004-WP002/LOD400_NB-S002-P004-WP002.md`
- MANDATE: `_COMMUNICATION/team_10/MANDATE_NB-S002-P004-WP002_v1.0.0.md`
- COMPLETION (team_10): `_COMMUNICATION/team_10/COMPLETION_NB-S002-P004-WP002.md`
- REMEDIATION (team_10 cycle 1): `_COMMUNICATION/team_10/REMEDIATION_NB-S002-P004-WP002_cycle1_v1.0.0.md`
- VALIDATE_REQUEST (team_190): `_COMMUNICATION/team_190/VALIDATE_REQUEST_NB-S002-P004-WP002_cycle1_v1.0.0.md`
- VERDICT (team_190): `_COMMUNICATION/team_190/VERDICT_NB-S002-P004-WP002_cycle1_v1.0.0.md`
- Triage JSON (source of truth for redirects): `docs/url_migration_decisions_2026-05-25.json`
- .htaccess artifact: `docs/htaccess_v200_redirects.txt`
- MU plugin (runtime enforcement): `nimrod.bio/wp-content/mu-plugins/nb-v200-runtime-redirects.php`
- Build commits: `ceda4535`, `c838a00b`, `c434f77a`, `9cbc73ce`

## 5. Gate advancement (effected by this artifact)

In `_aos/roadmap.yaml`, NB-S002-P004-WP002 advances:
- `status: IN_PROGRESS → COMPLETE`
- `current_lean_gate: L-GATE_BUILD → COMPLETE`
- `lod_status: LOD400 → LOD500`
- Adds L-GATE_BUILD and L-GATE_VALIDATE PASS entries with architecture-ratification reference.

## 6. Carry-forwards to P005-WP002 (cutover)

When the new site goes to production at `nimrod.bio`:
1. The MU plugin `nb-v200-runtime-redirects.php` must be copied to prod `mu-plugins/` directory (or fresh-deployed via the same FTPS workflow).
2. The `.htaccess` block from `docs/htaccess_v200_redirects.txt` should ALSO be inserted into prod `/.htaccess` between `# AOS-V200-redirects-START` / `# AOS-V200-redirects-END` markers — for two reasons: (a) audit/portable archive of the rule set, (b) if uPress ever migrates the site host to Apache, redirects continue working without code intervention.
3. Search Console actions per `docs/search_console_runbook.md` execute after DNS cutover.

## 7. V200 progress after this gate

- 12/13 WPs COMPLETE
- Remaining: P005-WP001 (QA pass), P005-WP002 (cutover)
- All deferrals from prior WPs carry forward to P005-WP001 (mobile probes, SMTP, .tbc content, a11y contrast waiver)

— team_100 (nimrod-bio) — 2026-05-25
