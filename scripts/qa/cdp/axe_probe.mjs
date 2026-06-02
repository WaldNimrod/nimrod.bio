#!/usr/bin/env node
/**
 * axe_probe.mjs — dependency-light axe-core a11y sweep over raw CDP.
 *
 * WHY: scripts/qa/axe_runner.py needs the Python `playwright` module (often absent).
 *   This injects a locally-cached axe.min.js (from npx cache or node_modules) into a
 *   chrome-headless-shell page over the DevTools Protocol — ZERO npm/pip install.
 *   Mirrors qa_probe.mjs's CDP plumbing. Node 18+.
 *
 * Runs WCAG2A/2AA tags. Reports violations grouped by id with node selectors +
 *   the failureSummary (so contrast ratios / fg / bg are captured verbatim).
 *
 * USAGE:
 *   node axe_probe.mjs --base https://host --paths "/,/about/,/contact/" --out <dir>
 *   node axe_probe.mjs --config <config.json>   (reuses qa_probe config shape: base/pages)
 *
 * TLS: chrome launched with --ignore-certificate-errors (DEV-ONLY, uPress *.upress.link).
 * EXIT: 0 if 0 serious+critical violations across all pages; 1 otherwise.
 */
import { spawn, execSync } from 'node:child_process';
import { mkdirSync, writeFileSync, readFileSync, existsSync } from 'node:fs';

function findChrome() {
  try {
    const home = process.env.HOME;
    const out = execSync(`find "${home}/.cache/puppeteer" -name chrome-headless-shell -type f 2>/dev/null | sort -V | tail -1`, { encoding: 'utf8' }).trim();
    if (out) return out;
  } catch {}
  for (const p of ['/Applications/Google Chrome.app/Contents/MacOS/Google Chrome', '/usr/bin/chromium', '/usr/bin/google-chrome']) {
    try { execSync(`test -x "${p}"`); return p; } catch {}
  }
  throw new Error('No chrome binary found.');
}
function findAxe() {
  try {
    const home = process.env.HOME;
    const out = execSync(`find "${home}/.npm" "${home}/.cache" ./node_modules -name axe.min.js 2>/dev/null | head -1`, { encoding: 'utf8' }).trim();
    if (out && existsSync(out)) return out;
  } catch {}
  throw new Error('axe.min.js not found in npx cache / node_modules.');
}
function parseArgs() {
  const a = process.argv.slice(2); const o = {};
  for (let i = 0; i < a.length; i++) {
    if (a[i] === '--config') o.config = a[++i];
    else if (a[i] === '--base') o.base = a[++i];
    else if (a[i] === '--paths') o.paths = a[++i];
    else if (a[i] === '--out') o.out = a[++i];
  }
  return o;
}

async function main() {
  const args = parseArgs();
  let cfg;
  if (args.config) cfg = JSON.parse(readFileSync(args.config, 'utf8'));
  else cfg = { base: args.base, pages: (args.paths || '/').split(',').map(p => ({ name: p.replace(/\W+/g, '_') || 'root', path: p })) };
  if (!cfg.base) { console.error('ERROR: no --base / config.base'); process.exit(2); }
  const outDir = args.out || cfg.out || 'docs/qa/cdp/axe';
  mkdirSync(outDir, { recursive: true });

  const chrome = findChrome();
  const axeSrc = readFileSync(findAxe(), 'utf8');
  const port = 9100 + Math.floor((Date.now() % 800));
  const proc = spawn(chrome, ['--headless', '--disable-gpu', '--no-sandbox', `--remote-debugging-port=${port}`, '--ignore-certificate-errors', '--hide-scrollbars'], { stdio: 'ignore' });
  await new Promise(r => setTimeout(r, 1800));

  const perPage = []; const agg = {};
  try {
    for (const pg of cfg.pages) {
      const url = cfg.base.replace(/\/$/, '') + pg.path + (pg.path.includes('?') ? '&' : '?') + 'nc=' + Date.now();
      const t = await (await fetch(`http://127.0.0.1:${port}/json/new?about:blank`, { method: 'PUT' })).json();
      const ws = new WebSocket(t.webSocketDebuggerUrl);
      let id = 0; const pend = {};
      ws.addEventListener('message', e => { const m = JSON.parse(e.data); if (m.id && pend[m.id]) pend[m.id](m); });
      await new Promise(r => ws.addEventListener('open', r));
      const send = (method, params = {}) => new Promise(res => { const i = ++id; pend[i] = res; ws.send(JSON.stringify({ id: i, method, params })); });
      await send('Page.enable'); await send('Runtime.enable');
      await send('Emulation.setDeviceMetricsOverride', { width: 1440, height: 900, deviceScaleFactor: 1, mobile: false });
      await send('Page.navigate', { url });
      await new Promise(r => setTimeout(r, 3200));
      await send('Runtime.evaluate', { expression: axeSrc });
      const runExpr = `axe.run(document,{runOnly:{type:'tag',values:['wcag2a','wcag2aa','best-practice']}}).then(r=>JSON.stringify({v:r.violations.map(x=>({id:x.id,impact:x.impact,help:x.help,nodes:x.nodes.map(n=>({target:n.target,summary:(n.failureSummary||'').replace(/\\s+/g,' ')}))}))}))`;
      const r = await send('Runtime.evaluate', { expression: runExpr, awaitPromise: true, returnByValue: true });
      const val = r.result && r.result.result ? JSON.parse(r.result.result.value) : { v: [] };
      const viols = val.v;
      const serious = viols.filter(x => x.impact === 'serious' || x.impact === 'critical');
      perPage.push({ page: pg.name, path: pg.path, total: viols.length, serious: serious.length, violations: viols });
      for (const x of viols) { agg[x.id] = agg[x.id] || { impact: x.impact, count: 0, pages: new Set() }; agg[x.id].count += x.nodes.length; agg[x.id].pages.add(pg.name); }
      ws.close(); await fetch(`http://127.0.0.1:${port}/json/close/${t.id}`).catch(() => {});
    }
  } finally { proc.kill(); }

  const aggOut = Object.fromEntries(Object.entries(agg).map(([k, v]) => [k, { impact: v.impact, nodeCount: v.count, pages: [...v.pages] }]));
  const totalSerious = perPage.reduce((s, p) => s + p.serious, 0);
  const summary = { base: cfg.base, ts: new Date().toISOString(), pages: perPage.length, totalSerious, byRule: aggOut, perPage };
  writeFileSync(`${outDir}/axe_result.json`, JSON.stringify(summary, null, 2));
  console.log('=== AXE SWEEP — by rule (serious/critical first) ===');
  for (const [id, v] of Object.entries(aggOut).sort((a, b) => (b[1].impact === 'critical' || b[1].impact === 'serious' ? 1 : 0) - (a[1].impact === 'critical' || a[1].impact === 'serious' ? 1 : 0)))
    console.log(`${(v.impact || '?').padEnd(9)} ${id.padEnd(28)} nodes:${String(v.nodeCount).padStart(3)}  pages: ${v.pages.join(',')}`);
  console.log(`\nTOTAL serious+critical violation instances: ${totalSerious}`);
  console.log(`Full detail: ${outDir}/axe_result.json`);
  process.exit(totalSerious === 0 ? 0 : 1);
}
main().catch(e => { console.error('FATAL', e); process.exit(2); });
