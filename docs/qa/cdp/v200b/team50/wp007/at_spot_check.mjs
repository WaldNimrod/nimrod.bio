#!/usr/bin/env node
/** AT-1..AT-7 CDP spot checks for WP007 dev-QA (team_50). */
import { spawn, execSync } from 'node:child_process';
import { mkdirSync, writeFileSync } from 'node:fs';

const BASE = 'https://nimrod-bio-2026.s887.upress.link';
const OUT = 'docs/qa/cdp/v200b/team50/wp007/at_spot_check.json';

function findChrome() {
  try {
    const home = process.env.HOME;
    const out = execSync(`find "${home}/.cache/puppeteer" -name chrome-headless-shell -type f 2>/dev/null | sort -V | tail -1`, { encoding: 'utf8' }).trim();
    if (out) return out;
  } catch {}
  return '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome';
}

const CHECKS = [
  { name: 'AT-1-services', path: '/services/', expr: `(() => {
    const ph = document.querySelector('.page-hero');
    const grid = document.querySelector('.svc-grid');
    const gc = grid ? getComputedStyle(grid).gridTemplateColumns : '';
    const bridges = document.querySelectorAll('.bridges-band .bridge-card, .bridges-grid .bridge-card').length;
    return { pageHero: !!ph, svcGrid: !!grid, gridCols: gc, bridges, finalCta: !!document.querySelector('.final-cta'), srOnlyH2: !!document.querySelector('.sr-only h2, h2.sr-only'), h1FontSize: ph ? getComputedStyle(ph.querySelector('h1')||ph).fontSize : null };
  })()` },
  { name: 'AT-2-consulting-hydro', path: '/services/consulting-hydro/', expr: `(() => {
    const hero = document.querySelector('.svc-single-hero');
    const hg = hero ? getComputedStyle(hero).gridTemplateColumns : '';
    return { svcSingleHero: !!hero, heroGrid: hg, featTiles: document.querySelectorAll('.feat-grid .feat-tile, .feat-tile').length, svcSteps: document.querySelectorAll('.svc-steps .svc-step, .svc-step').length, svcPull: !!document.querySelector('.svc-pull'), linkedProjects: document.querySelectorAll('.linked-projects .lp-card, .projects-row .proj-card').length, finalCta: !!document.querySelector('.final-cta') };
  })()` },
  { name: 'AT-2-teaching-fallback', path: '/services/teaching/', expr: `(() => {
    return { svcSingleHero: !!document.querySelector('.svc-single-hero'), featTiles: document.querySelectorAll('.feat-tile').length, svcSteps: document.querySelectorAll('.svc-step').length, svcPull: !!document.querySelector('.svc-pull'), finalCta: !!document.querySelector('.final-cta'), brokenSections: document.querySelectorAll('.feat-grid:empty, .svc-steps:empty').length };
  })()` },
  { name: 'AT-2-bcs-fallback', path: '/services/bcs/', expr: `(() => {
    return { svcSingleHero: !!document.querySelector('.svc-single-hero'), featTiles: document.querySelectorAll('.feat-tile').length, gallery: !!document.querySelector('.t3-gallery, .proj-gallery, .svc-gallery'), metaStrip: !!document.querySelector('.svc-meta-strip, .ssh-meta') };
  })()` },
  { name: 'AT-3-greenhouse', path: '/project/rest-x-greenhouse/', expr: `(() => {
    const h1 = document.querySelector('.t3-hero h1, .proj-hero h1');
    return { outcomes: document.querySelectorAll('.outcomes .oc-tile, .oc-tile').length, finalCta: !!document.querySelector('.final-cta'), h1FontSize: h1 ? getComputedStyle(h1).fontSize : null, letterSpacing: h1 ? getComputedStyle(h1).letterSpacing : null };
  })()` },
  { name: 'AT-4-single-post', path: '/blog/garden-bed-width-80cm/', expr: `(() => {
    const body = document.querySelector('.article-body, .post-body');
    let numbered = 0, plainH2 = 0;
    if (body) body.querySelectorAll('h2').forEach(h => { if (h.querySelector('.num')) numbered++; else plainH2++; });
    return { metaTop: !!document.querySelector('.post-hero-meta-top'), numberedH2: numbered, plainH2, hasAside: !!document.querySelector('.post-aside'), articleShell: !!document.querySelector('.article-shell.has-aside') };
  })()` },
  { name: 'AT-5-blog', path: '/blog/', expr: `(() => {
    return { blogHeader: !!document.querySelector('.blog-header-grid, .page-hero .blog-header-grid'), filter: !!document.querySelector('.blog-toolbar, .t5-filter-bar, .blog-filter'), postsGrid: !!document.querySelector('.posts-grid, .blog-featured-grid'), blogEnd: !!document.querySelector('.blog-end'), srOnlyH2: !!document.querySelector('.sr-only h2, h2.sr-only') };
  })()` },
  { name: 'AT-6-world-know', path: '/world/know/', expr: `(() => {
    const art = document.querySelector('article.t1-world-know, .t1-world-know');
    const gloss = document.querySelector('.vc-hero .gloss .word, .gloss .word');
    return { t1WorldKnow: !!art, bodyClass: document.body.className, glossColor: gloss ? getComputedStyle(gloss).color : null, latAnchor: !!document.querySelector('.lat-anchor') };
  })()` },
  { name: 'AT-6-world-code', path: '/world/code/', expr: `(() => {
    const art = document.querySelector('article.t1-world-code, .t1-world-code');
    const gloss = document.querySelector('.vc-hero .gloss .word, .gloss .word');
    return { t1WorldCode: !!art, glossColor: gloss ? getComputedStyle(gloss).color : null };
  })()` },
  { name: 'AT-7-heritage', path: '/about/heritage/', expr: `(() => {
    return { heritageHero: !!(document.querySelector('.heritage-hero, .t8-heritage-hero')), dropcap: !!document.querySelector('.heritage-body .dropcap, .dropcap'), numberedH2: document.querySelectorAll('.heritage-body h2 .num, .heritage-reading h2 .num').length, heritageEnd: !!document.querySelector('.heritage-end') };
  })()` },
];

async function main() {
  const chrome = findChrome();
  const port = 9200 + Math.floor((Date.now() % 800));
  const proc = spawn(chrome, ['--headless', '--disable-gpu', '--no-sandbox', `--remote-debugging-port=${port}`, '--ignore-certificate-errors'], { stdio: 'ignore' });
  await new Promise(r => setTimeout(r, 1800));
  const checks = [];
  try {
    for (const c of CHECKS) {
      const url = BASE + c.path + '?nc=' + Date.now();
      const t = await (await fetch(`http://127.0.0.1:${port}/json/new?about:blank`, { method: 'PUT' })).json();
      const ws = new WebSocket(t.webSocketDebuggerUrl);
      let id = 0; const pend = {};
      ws.addEventListener('message', e => { const m = JSON.parse(e.data); if (m.id && pend[m.id]) pend[m.id](m); });
      await new Promise(r => ws.addEventListener('open', r));
      const send = (method, params = {}) => new Promise(res => { const i = ++id; pend[i] = res; ws.send(JSON.stringify({ id: i, method, params })); });
      await send('Page.enable'); await send('Runtime.enable');
      await send('Emulation.setDeviceMetricsOverride', { width: 1440, height: 900, deviceScaleFactor: 1, mobile: false });
      await send('Page.navigate', { url });
      await send('Runtime.evaluate', { expression: 'document.readyState', returnByValue: true });
      await new Promise(r => setTimeout(r, 4500));
      const ev = await send('Runtime.evaluate', { expression: c.expr, returnByValue: true, awaitPromise: false });
      const val = ev.result?.result?.value ?? ev.result?.value ?? null;
      if (ev.exceptionDetails) checks.push({ name: c.name, path: c.path, error: ev.exceptionDetails.text, value: null });
      else checks.push({ name: c.name, path: c.path, value: val });
      ws.close();
    }
  } finally {
    proc.kill();
  }
  const out = { ts: new Date().toISOString(), base: BASE, checks };
  mkdirSync('docs/qa/cdp/v200b/team50/wp007', { recursive: true });
  writeFileSync(OUT, JSON.stringify(out, null, 2));
  console.log(JSON.stringify(out, null, 2));
}

main().catch(e => { console.error(e); process.exit(1); });
