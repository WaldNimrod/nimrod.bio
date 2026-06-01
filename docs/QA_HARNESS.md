# QA Harness — nimrod.bio (browser + curl)

**Status:** active · **Owner:** team_50 (validation) / team_100 (orchestration) · **Updated:** 2026-06-01

This is the discoverable entry point for QA on nimrod.bio. Agents kept falling back to
`curl` (blind to layout) because the browser harness wasn't documented or runnable —
this file + the CDP runner fix that.

## TL;DR — run browser QA with ZERO pip/npm installs
```bash
# 13 pages × 2 viewports: overflow + forbidden-term scan + screenshots
node scripts/qa/cdp/qa_probe.mjs --config <config.json>
# ad-hoc:
node scripts/qa/cdp/qa_probe.mjs --base https://nimrod-bio-2026.s887.upress.link \
     --paths "/,/about/,/contact/" --absent "TBD,CDIP,אנטרופיה" --shots
```
Exit 0 = all pass; exit 1 = overflow / forbidden substring / blank title on any page.
Output: JSON summary to stdout + `<out>/qa_probe_result.json` + `<out>/screenshots/*.png`.

## Why a CDP runner (not the legacy Python harness)
- `scripts/qa/*.py` (responsive_probe, lighthouse_batch, axe_runner, crawl_links) require the
  **Python `playwright` module**, which is frequently NOT pip-installed in agent shells →
  `ImportError` → silent fallback to curl. The Chromium *browser* is cached, but the Python
  *binding* isn't. Net effect: layout bugs (e.g. horizontal overflow) ship undetected because
  curl only sees HTML, never the rendered box model.
- `scripts/qa/cdp/qa_probe.mjs` talks to a cached **`chrome-headless-shell`** over the DevTools
  Protocol via Node's built-in `WebSocket` — **no npm/pip dependency**, Node 18+ only. It is the
  technique that caught & confirmed the F-003 gallery overflow (4294px → contained).

## What each tool is for
| Need | Tool | Notes |
|------|------|-------|
| HTTP status, locks, exact HTML/alt/meta, mailto presence | `curl -k ?nc=` | deterministic, cheap, reliable — most checks |
| Layout / horizontal overflow / RTL / screenshots | `scripts/qa/cdp/qa_probe.mjs` | renders the page; curl cannot |
| Performance / a11y / best-practices / SEO | `lighthouse` (v13, installed) | needs **full Chrome**, not headless-shell (see below) |
| Accessibility rules / link health (legacy) | `scripts/qa/*.py` | only if Python `playwright` is pip-installed |

## Lighthouse
```bash
export CHROME_PATH="/Applications/Google Chrome.app/Contents/MacOS/Google Chrome"   # NOT chrome-headless-shell
npx --no-install lighthouse "<url>" --quiet \
  --chrome-flags="--headless=new --ignore-certificate-errors --no-sandbox" \
  --only-categories=performance,accessibility,best-practices,seo \
  --output=json --output-path=out.json
```
- **Use full `Google Chrome.app`**, not `chrome-headless-shell` (LH needs features the shell lacks).
- **`python3` may be off-PATH** inside compound shell commands in some agent envs — read result JSON
  with `node -e` instead, or run lighthouse and the JSON read as **separate** commands.

## ⚠ TLS — dev has NO valid certificate (documented, by design)
- **Dev** `https://nimrod-bio-2026.s887.upress.link` → uPress issues free SSL **only on the primary
  domain**, never on `*.upress.link` dev URLs. So the dev cert is invalid/expired. This is EXPECTED
  and is NOT a defect to fix — it resolves automatically when the site moves to the primary domain.
- **Production** `https://nimrod.bio` → valid SSL (uPress free cert + Cloudflare).
- **Consequence for all dev tooling:** pass the cert-bypass flag —
  `curl -k` · chrome `--ignore-certificate-errors` · requests `verify=False`.
  These flags are **DEV-ONLY**; production QA must run WITHOUT them (a cert error on prod = real defect).
- Dev also sets `X-Robots-Tag: noindex` at the edge → Lighthouse **SEO** scores are artificially low
  on dev; re-measure on the primary domain. Likewise **Performance** (SuperCache miss on `?nc=` +
  no Cloudflare) reads lower on dev than prod.

## Config shape (`qa_probe.mjs --config`)
```json
{ "base": "https://...", "out": "docs/qa/cdp/<run>",
  "viewports": [{"name":"mobile","w":375,"h":812},{"name":"desktop","w":1440,"h":900}],
  "pages": [{"name":"home","path":"/"}, ...],
  "absent": ["TBD","CDIP","אנטרופיה","nimrod@nimrod.bio", ...],
  "shots": true }
```

## Portability (serves all AOS domains)
`qa_probe.mjs` hardcodes nothing site-specific — base/pages/absent/viewports come from the config or
flags, and chrome discovery walks `~/.cache/puppeteer` then falls back to system Chrome/Chromium. Copy
the script + this doc into any spoke. A hub propagation notice (file `for_hub: true`) requests rollout
to all projects via AOS-hub team_100.
