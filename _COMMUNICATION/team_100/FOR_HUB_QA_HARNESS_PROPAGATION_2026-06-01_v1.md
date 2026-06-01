---
for_hub: true
to: AOS-hub team_100 (Chief System Architect)
from: nimrod-bio team_100 (spoke session)
type: METHODOLOGY_IMPROVEMENT / TOOLING_PROPAGATION
date: 2026-06-01
urgency: P2
routing: team_00 → AOS hub session (spoke cannot write to hub — Iron Rule #6 / domain write isolation)
---

# FOR HUB — Portable browser-QA harness for all AOS domains

## Problem observed (nimrod-bio, recurring across sessions)
QA sub-agents repeatedly **fell back to `curl`** for visual/layout checks because the project's
browser harness (`scripts/qa/*.py`) depends on the **Python `playwright` module**, which is often
NOT pip-installed in agent shells — it `ImportError`s and the agent silently degrades to curl.
**curl cannot see rendered layout**, so a real horizontal-overflow bug (gallery blew out to 4294px)
passed every curl check and was only caught once a real browser measured `scrollWidth`. Agents also
"reasoned about" layout instead of rendering it, and missed that **Lighthouse was already installed**.

Root causes (likely common to many spokes):
1. Browser *binaries* are cached (puppeteer `chrome-headless-shell`, full Chrome) but the Python/JS
   *driver* binding isn't reliably present → harness unrunnable.
2. The QA harness had **no discoverable entry point** (no README, not referenced in CLAUDE.md) → agents
   didn't know it existed.
3. No documented split of "what curl can prove" vs "what needs a rendering engine."

## Fix built in nimrod-bio (candidate for all domains)
1. **`scripts/qa/cdp/qa_probe.mjs`** — dependency-free browser QA. Drives a cached
   `chrome-headless-shell` over the DevTools Protocol via Node's built-in `WebSocket` (**no npm/pip**,
   Node 18+). Per page×viewport: rendered `scrollWidth` vs `clientWidth` (overflow detection),
   forbidden-substring scan in the rendered DOM (incl. alt/aria), document title, optional full-page
   screenshots. Config- or flag-driven; **hardcodes nothing site-specific**; chrome auto-discovery
   walks `~/.cache/puppeteer` then falls back to system Chrome/Chromium. Exit 0/1 = gate-friendly.
2. **`docs/QA_HARNESS.md`** — discoverable entry point: when to use curl vs CDP vs Lighthouse;
   Lighthouse needs full Chrome via `CHROME_PATH` (not headless-shell); the `python3`-off-PATH gotcha.
3. **CLAUDE.md** dev-hosting note hardened: dev TLS is invalid BY DESIGN (valid on cutover), cert-bypass
   flags are DEV-ONLY (prod must run without them), dev SEO/Perf scores are artifacts, + QA-harness pointer.

## Requested hub action
1. Review `qa_probe.mjs` + `QA_HARNESS.md` as a **lean-kit / methodology** addition (validation-quality
   module) so every spoke gets a dependency-free browser-QA path + the curl-vs-browser discipline.
2. Add a canonical **CLAUDE.md template** clause: dev-TLS-by-design + DEV-ONLY cert-bypass flags +
   "do not curl-only for layout" + QA-harness pointer — propagate via `aos_sync_all.sh`.
3. Consider a standard **`scripts/qa/cdp/` location** + a `validate_aos.sh` advisory check that browser-QA
   tooling is present/discoverable in spokes that ship a frontend.

## Files (in nimrod-bio repo, on main)
- `scripts/qa/cdp/qa_probe.mjs`
- `docs/QA_HARNESS.md`
- `CLAUDE.md` (Domain rules → uPress hosting block)

*nimrod-bio team_100 | for_hub | 2026-06-01 | left in spoke _COMMUNICATION for team_00 to route to AOS hub*
