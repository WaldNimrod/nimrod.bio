# CLAUDE.md — nimrod-bio

<!-- AOS-CANONICAL-TEMPLATE v1.0.0 — rendered by scripts/aos_sync_all.sh. DO NOT hand-edit content between <!-- aos:canonical:start --> and <!-- aos:canonical:end -->. Project-specific additions go in the "Domain rules" section below. -->

<!-- aos:canonical:start -->
## ⚠ AOS Spoke Notice (READ FIRST)

You are working inside an **AOS spoke** — repo `nimrod-bio`, profile `L0`.

- **AOS = multi-domain, multi-engine infrastructure** for managing agents and projects across the organization. It is NOT a product. It governs how agents collaborate across product repos (spokes).
- **AOS hub:** `/Users/nimrod/Documents/agents-os-aos-v4.5-wp-gov-distribution-modelb` — SSOT for governance, lean-kit, canon, directives.
- **`_aos/` in this repo is a READ-ONLY SNAPSHOT** propagated from the hub via `aos_sync_all.sh` / `propagate_governance.sh`.
- **Do NOT edit** `_aos/governance/`, `_aos/lean-kit/`, `_aos/project_identity.yaml`, or any other AOS-layer file directly.
- **To request a governance change:** file `GOVERNANCE_CHANGE_REQUEST` artifact in `_COMMUNICATION/team_XX/` → route to `team_100` in the hub. Template: `/Users/nimrod/Documents/agents-os-aos-v4.5-wp-gov-distribution-modelb/lean-kit/modules/project-governance/config_templates/GOVERNANCE_CHANGE_REQUEST.md.template`
- **Governance procedures are LOCKED to AOS teams** (`team_00`, `team_100`) per Iron Rule #12 / ADR040. Non-AOS teams cannot invoke `/AOS_gov-update` or `/AOS_gov-sync`.

## Identity

- **Repo:** `nimrod-bio`
- **Path:** `/Users/nimrod/Documents/nimrod-bio`
- **Profile:** `L0`
- **AOS hub:** `/Users/nimrod/Documents/agents-os-aos-v4.5-wp-gov-distribution-modelb`
- **Domain:** `nimrod-bio`

## Mandatory session startup (canonical — uniform across all AOS domains)

1. Read `_aos/roadmap.yaml` — current WP and gate position
2. Read `_aos/context/PROJECT_CONTEXT.md` — project background
3. Read `_aos/definition.yaml` (L2) or `_aos/context/ACTIVATION_*.md` (L0) — your role
4. **DB probe (mandatory):** `cat "/Users/nimrod/Documents/agents-os-aos-v4.5-wp-gov-distribution-modelb/_aos/db_connectivity_status.json"` — hub canonical DB status (refreshed by hub session). If `status: online` → all structured mutations go via API (Iron Rule #7 / ADR034). If `status: offline` → **STOP**: report `reason` field to Team 00, wait for Team 00 guidance before proceeding (ADR034 R8 protocol on a named branch — never main). To refresh hub status: run the hub DB probe from a hub session.
5. **Validation:** `bash _aos/lean-kit/modules/validation-quality/scripts/validate_aos.sh .` — expect **0 FAIL** on this spoke
6. **AOS identity onboarding (first session only):** read `/Users/nimrod/Documents/agents-os-aos-v4.5-wp-gov-distribution-modelb/methodology/AOS_IDENTITY_ONBOARDING_v1.0.0.md`

## Iron Rules (uniform across all AOS domains)

1. Cross-engine: builder engine ≠ validator engine
2. Governance snapshots (`_aos/lean-kit/`, `_aos/governance/`, `_aos/methodology/`) are a physical copy or a git-ignored local cache refreshed by sync — never a symlink (Model B / ADR054; version held in tracked `_aos/AOS_GOVERNANCE_VERSION.yaml`)
3. Repo-internal `spec_ref` paths only
4. Single logical writer on `roadmap.yaml` (subject to API-only rule when DB online)
5. Final validation owned by `team_190` (constitutional, cross-engine, immutable)
6. Inter-team communication via canonical artifact in `_COMMUNICATION/`
7. **API-only structured mutations when DB online** (ADR034)
8. **Port canon** — `lean-kit/modules/12-home-server-infrastructure/deployment/port-registry.yaml` is SSOT for all long-running listeners (Team 60)
9. Universal team numbering
10. Governance flows source → snapshot only; no reverse (Iron Rule #11)
11. **Iron Rule #12: `gov-update` + `gov-sync` locked to `team_00` / `team_100` only** (ADR040). Other teams must file canonical GCR.
12. **Iron Rule #13** (ADR041): every deterministic AOS command is a thin orchestrator (≤150 lines + required `summary:` / `category:` frontmatter) over a hub API endpoint in `core/modules/management/`. SSoT Python modules carry data + logic. Cross-engine (Claude Code / Cursor / Codex / Desktop) call same API. Canon: `/Users/nimrod/Documents/agents-os-aos-v4.5-wp-gov-distribution-modelb/methodology/AOS_COMMAND_ARCHITECTURE_v1.0.0.md`.

## Directory Authority (uniform)

| Team | May write to |
|------|-------------|
| `team_00` (Principal) | Anywhere (final human authority) |
| `team_100` (Chief Architect) | `_COMMUNICATION/team_100/`, `_aos/roadmap.yaml`, `_aos/work_packages/` (hub only — SSOT edits) |
| `team_191` (Git/Files) | `_COMMUNICATION/team_191/`, `_archive/`, `_aos/` (bootstrap/propagation under mandate) |
| **All other teams** | `_COMMUNICATION/team_[ID]/` + application source ONLY — NEVER `_aos/` |

## Governance File Protection

- `_aos/governance/team_*.md` files in this repo are READ-ONLY snapshots of the hub SSOT at `/Users/nimrod/Documents/agents-os-aos-v4.5-wp-gov-distribution-modelb/core/governance/team_*.md`
- Any direct edit will be reverted on next `aos_sync_all.sh` run
- Validated by hub `validate_aos.sh` Checks 27–29
- Change-request workflow: GCR artifact → team_100 → Team 00 approval → hub edit + sync

## Dev/Staging TLS & Browser-QA Discipline (uniform)

- **Dev/staging TLS is often invalid BY DESIGN** — many hosts issue a valid certificate only on the primary/production domain. A cert error on a **dev/staging** URL is **expected** and is NOT a defect to fix; a cert error on **production** IS a real defect.
- **Cert-bypass flags are DEV-ONLY:** `curl -k` · chrome `--ignore-certificate-errors` · `requests verify=False`. Never use them in production QA.
- **Never use `curl` alone to validate layout** — curl sees only HTML, never the rendered box model, so horizontal-overflow / RTL / responsive bugs pass curl and ship. For any layout/overflow/visual check, run the dependency-free browser-QA runner: `_aos/lean-kit/modules/validation-quality/scripts/qa/qa_probe.mjs` (Node 18+, no pip/npm). Discipline + curl-vs-CDP-vs-Lighthouse guidance: `_aos/lean-kit/modules/validation-quality/docs/BROWSER_QA_HARNESS_CANON_v1.0.0.md`.
- Dev SEO/Performance scores (noindex edge headers, cache misses) are **artifacts** — re-measure on the production domain.
<!-- aos:canonical:end -->

<!-- aos:project-specific:start -->

## Domain rules

### uPress hosting — operational notes

**Production:** `https://nimrod.bio` (valid SSL via uPress free cert, Cloudflare in front).

**Dev environment (V200 rebuild):** `https://nimrod-bio-2026.s887.upress.link`
- Reachable on both HTTP and HTTPS, but **HTTPS uses an expired/invalid certificate**. Browsers will show a security warning; `curl` requires `-k`. uPress' free SSL is only issued on the primary domain, not on `*.upress.link` dev URLs. **This is by design — the valid cert appears automatically on cutover to the primary domain; it is NOT a defect to fix on dev.**
- **Dev cert-bypass flags are DEV-ONLY:** `curl -k` · chrome `--ignore-certificate-errors` · python-requests `verify=False`. Production QA (primary domain) MUST run WITHOUT these — a cert error on prod is a real defect. Implement and test all dev work accordingly.
- `X-Robots-Tag: noindex, nofollow` is set by uPress at the edge — search engines will not index the dev URL. (Consequence: Lighthouse **SEO** + **Performance** read artificially low on dev — re-measure on the primary domain; don't treat dev scores as blockers.)
- Plan for testing: use HTTP for routine work; for any cookie/SameSite/secure-context check, deploy to staging on the primary domain or run a localhost reverse-proxy with a self-signed cert.
- **QA harness (browser + curl): `docs/QA_HARNESS.md`.** Browser QA runs dependency-free via `node scripts/qa/cdp/qa_probe.mjs` (CDP over cached chrome-headless-shell — no pip/npm). Lighthouse (v13, installed) needs full `Google Chrome.app` via `CHROME_PATH`, not headless-shell. Do NOT fall back to curl-only for layout/overflow checks — curl is blind to rendering.

**Built-in uPress capabilities** (decision matrix in `docs/upress_capabilities_matrix.md`):
- SuperCache (page + object cache) — replaces need for WP Super Cache / W3TC / EzCache
- CDN, HTTP/2, free SSL on primary domain
- Web Firewall — replaces need for Wordfence / Sucuri
- Auto backups (30-day retention) + one-click manual snapshot — replaces UpdraftPlus
- Staging via temp domains (`*.s###.upress.link`) — already in use for V200
- Migration tools: DirectAdmin, cPanel, GIT, BitBucket, Duplicator

### Operational quirks (must read before action)

1. **zip files with Hebrew filenames** — macOS `unzip` corrupts the encoding (filename becomes `????????.html`). Always use `ditto -x -k <zip> <dest>` for design/handoff packages from team_35.
2. **Local DB** — Docker stack on ports 8085 (WP) / 3309 (MySQL). Root password `local_root_only`, WP user password `wordpress` (per `docker-compose.yml`). Production DB table prefix: `qvj_`.
3. **Always commit local DB changes via `restore-production-from-backup.sh`** — never edit live DB directly. The script is idempotent and handles the `qvj_` prefix correctly.

### Active milestones

- **V100** — COMPLETE (AOS init + Docker bootstrap + MU plugin deploy)
- **V200** — Site Rebuild (in planning). LOD300 stage doc: `_aos/work_packages/S002/LOD300_V200_milestone.md`
<!-- aos:project-specific:end -->
