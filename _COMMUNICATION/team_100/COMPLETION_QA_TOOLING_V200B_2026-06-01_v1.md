# COMPLETION — QA tooling + browser validation (V200B) — team_100 — v1

**Date:** 2026-06-01
**Author:** team_100
**Type:** COMPLETION REPORT
**Env:** dev `https://nimrod-bio-2026.s887.upress.link` · theme v0.7.13

## What was done
Per team_00 directive: (1) document the dev-TLS situation, (2) fix the "no browser for QA" gap (which was actually a *discoverability/dependency* gap — tooling existed), (3) make the fix serve all domains + file a hub propagation notice, (4) run real browser validation.

### 1 · TLS documented
- `CLAUDE.md` uPress block hardened: dev cert invalid **by design** (valid on cutover to primary domain — not a defect); cert-bypass flags (`curl -k`, chrome `--ignore-certificate-errors`, requests `verify=False`) are **DEV-ONLY**, prod must run without them; dev `noindex` → SEO/Perf artifacts; QA-harness pointer.

### 2 · Root cause of "no browser QA" (corrected)
Browser tooling DID exist — agents just couldn't find/run it:
- `scripts/qa/*.py` needs the Python `playwright` MODULE (not pip-installed) → ImportError → silent curl fallback.
- No README / CLAUDE.md pointer → undiscovered.
- Lighthouse v13 was installed all along; agents never invoked it.
Net effect: layout bugs (F-003 overflow) shipped because curl is blind to rendering.

### 3 · Fix (portable, dependency-free — serves all domains)
- **`scripts/qa/cdp/qa_probe.mjs`** — CDP over cached `chrome-headless-shell` via Node built-in WebSocket. No npm/pip. Per page×viewport: scrollWidth-vs-clientWidth (overflow), forbidden-substring scan (rendered DOM incl. alt/aria), title, optional screenshots. Config/flag-driven, nothing site-specific, exit 0/1.
- **`docs/QA_HARNESS.md`** — discoverable entry point + curl-vs-CDP-vs-Lighthouse split + gotchas (Lighthouse needs full Chrome via `CHROME_PATH`; `python3` off-PATH in some compound shells).
- **`_COMMUNICATION/team_100/FOR_HUB_QA_HARNESS_PROPAGATION_2026-06-01_v1.md`** (`for_hub: true`) — requests AOS-hub team_100 propagate the harness + CLAUDE.md template clause to all domains (spoke cannot write to hub; team_00 routes).

### 4 · Real browser validation (V200B, dev)
- **CDP probe — 13 pages × 2 viewports = 26 combos: VERDICT PASS.** Zero horizontal overflow (incl. the two galleries that were 4294px before F-003 fix — now scrollWidth==clientWidth at 375 & 1440). Zero forbidden terms (TBD/CDIP/אנטרופיה/…/nimrod@nimrod.bio) in rendered DOM. 26 screenshots: `docs/qa/cdp/v200b/screenshots/`.
- **Lighthouse (full Chrome, dev cert ignored):**
  | Page | Perf | A11y | Best-Practices | SEO |
  |------|------|------|----------------|-----|
  | home | 69 | 90 | 100 | 69 |
  | project-sfa | 58 | 90 | 100 | 69 |
  | about | 67 | 90 | 100 | 69 |
  A11y 90 / BP 100 strong. **Perf 58–69 + SEO 69 are DEV ARTIFACTS** (SuperCache miss on `?nc=`, no Cloudflare, `noindex` header) — re-measure on primary domain; not cutover blockers. Reports: `docs/qa/cdp/v200b/lighthouse/`.

## QA fixes confirmed live (from prior step, re-verified here)
F-002 `/services/` archive (200, 7 cards), F-003 gallery overflow GONE (browser-measured), F-004 email removed (0 mailto on /contact/ + footer). Theme v0.7.13.

## Status / next
P005-WP001B QA substantially complete: browser + Lighthouse now real, not reasoned. Remaining before cutover: **team_190 constitutional L-GATE_VALIDATE** (cross-engine). Validation prompts for AOS-hub team_100 (tooling propagation) + team_190 (L-GATE_VALIDATE) presented to team_00.

*team_100 | completion | 2026-06-01 | QA tooling fixed + browser-real validation; hub notice filed*
