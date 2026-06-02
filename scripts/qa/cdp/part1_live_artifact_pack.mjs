#!/usr/bin/env node
/**
 * part1_live_artifact_pack.mjs — team_50 live artifact pack for Part 1 fidelity scan.
 * Captures: full-page PNGs, overflow detail, lock scan, computed-style proofs, §06 DOM, env proofs.
 */
import { spawn, execSync } from 'node:child_process';
import { mkdirSync, writeFileSync, readFileSync } from 'node:fs';
import { join } from 'node:path';

const BASE = 'https://nimrod-bio-2026.s887.upress.link';
const OUT = process.argv[2] || '_COMMUNICATION/team_50/_OUTBOX_live_artifacts_part1';
const REPO_SHA = (() => {
  try { return execSync('git rev-parse HEAD', { encoding: 'utf8' }).trim(); } catch { return 'unknown'; }
})();
const BASELINE_SHA = 'a35a67df';

const LOCK_TERMS = [
  'Micha', 'Micha OS', 'CDIP', 'Cross-Domain Isomorphism', 'cross-domain',
  'אנטרופיה', 'נגנטרופיה', 'רקורסיה', 'פרמקלצר', '3×', 'אינסטנסים',
  'קואופרטיב', 'קומון', 'TBD', 'TBC', 'recursion',
];

const SCREENS = [
  { id: 't7', name: 'home', path: '/' },
  { id: 't1', name: 'world_soil', path: '/world/soil/', note: 'mockup t1 אדמה → live /world/soil/' },
  { id: 'contact', name: 'contact', path: '/contact/' },
  { id: 'about', name: 'about', path: '/about/' },
  { id: 'sys404', name: 'sys_404', path: '/nb-precision-404-probe-2026/' },
  { id: 'syssearch', name: 'sys_search', path: '/?s=' + encodeURIComponent('נימרוד') },
];

const VIEWPORTS = [
  { label: '375', w: 375, h: 812, dpr: 1 },
  { label: '1440', w: 1440, h: 900, dpr: 1 },
];

function findChrome() {
  try {
    const home = process.env.HOME;
    const out = execSync(
      `find "${home}/.cache/puppeteer" -name chrome-headless-shell -type f 2>/dev/null | sort -V | tail -1`,
      { encoding: 'utf8' }
    ).trim();
    if (out) return out;
  } catch {}
  for (const p of [
    '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',
    '/usr/bin/chromium', '/usr/bin/google-chrome',
  ]) { try { execSync(`test -x "${p}"`); return p; } catch {} }
  throw new Error('No chrome binary found');
}

function cs(el, props) {
  if (!el) return null;
  const s = getComputedStyle(el);
  const o = {};
  for (const p of props) o[p] = s.getPropertyValue(p) || s[p];
  return o;
}

const PROBE_JS = `(() => {
  const lockTerms = ${JSON.stringify(LOCK_TERMS)};
  const text = document.documentElement.outerHTML;
  const lockHits = [];
  for (const t of lockTerms) {
    if (text.includes(t)) lockHits.push({ term: t, context: 'dom' });
  }
  const overflow = [];
  const de = document.documentElement;
  const vw = de.clientWidth;
  document.querySelectorAll('body *').forEach(el => {
    const r = el.getBoundingClientRect();
    if (r.right > vw + 2 && r.width > 20) {
      const sel = el.tagName.toLowerCase() +
        (el.id ? '#' + el.id : '') +
        (el.className && typeof el.className === 'string' && el.className.trim()
          ? '.' + el.className.trim().split(/\\s+/).slice(0, 2).join('.') : '');
      const ox = Math.round(r.right - vw);
      if (!overflow.find(x => x.selector === sel)) overflow.push({ selector: sel, overflowPx: ox, clientWidth: vw, scrollWidth: de.scrollWidth });
    }
  });
  const themeLink = document.querySelector('link[href*="nimrod-bio-2026"][href*="ver="]');
  const themeVer = themeLink?.href?.match(/ver=([\\d.]+)/)?.[1] || null;
  const assetSample = themeLink?.href || null;
  function pick(sel, props) {
    const el = document.querySelector(sel);
    if (!el) return { selector: sel, found: false };
    const s = getComputedStyle(el);
    const o = { selector: sel, found: true };
    for (const p of props) o[p] = s.getPropertyValue(p) || s[p];
    return o;
  }
  const root = document.documentElement;
  const rootStyle = getComputedStyle(root);
  const tokenNames = ['--radius-s','--radius-m','--radius-l','--shadow-s','--shadow-m','--shadow-l'];
  const tokens = {};
  for (const n of tokenNames) tokens[n] = rootStyle.getPropertyValue(n).trim();
  const bodyLh = getComputedStyle(document.body).lineHeight;
  const sec = document.querySelector('section');
  const secLh = sec ? getComputedStyle(sec).lineHeight : null;
  const wa = document.querySelector('.wa-btn');
  let waHover = null;
  if (wa) {
    wa.dispatchEvent(new MouseEvent('mouseenter', { bubbles: true }));
    waHover = getComputedStyle(wa).backgroundColor;
  }
  const contactDump = {
    form: !!document.querySelector('.contact-form'),
    social: !!document.querySelector('.contact-social'),
    formSelectors: [...document.querySelectorAll('.contact-form, .contact-form *')].slice(0, 12).map(e => e.tagName + (e.className ? '.' + String(e.className).split(' ')[0] : '')),
    socialSelectors: [...document.querySelectorAll('.contact-social a')].map(a => ({ href: a.getAttribute('href'), text: (a.textContent||'').trim().slice(0,40) })),
  };
  const bridges = pick('.bridges-grid', ['gridTemplateColumns','display','gap']);
  const vcBridges = pick('.vc-bridges', ['gridTemplateColumns','display']);
  const aboutProse = pick('.about-prose', ['maxWidth','fontFamily','lineHeight','fontSize']);
  const finalCta = pick('.t8-final-cta, .final-cta', ['display']);
  const postsGrid = document.querySelector('.posts-grid, .posts-grid-4');
  const rpCards = [...document.querySelectorAll('.rp-card')];
  const section06 = {
    postsGrid: postsGrid ? postsGrid.outerHTML.slice(0, 8000) : null,
    postsGridClass: postsGrid?.className || null,
    rpCardCount: rpCards.length,
    rpFeatCount: document.querySelectorAll('.rp-card.feat').length,
    cards: rpCards.slice(0, 6).map(c => ({
      classes: c.className,
      href: c.getAttribute('href'),
      title: (c.querySelector('h4,h3')?.textContent || '').trim(),
      date: (c.querySelector('time')?.getAttribute('datetime') || c.querySelector('.meta')?.textContent || '').trim().slice(0, 40),
      dataPostId: c.getAttribute('data-post-id'),
    })),
    placement: (() => {
      const secs = [...document.querySelectorAll('section, aside')].map(s => ({
        tag: s.tagName,
        cls: (s.className || '').split(' ').filter(Boolean).slice(0, 3).join(' '),
      }));
      const proj = document.querySelector('.t7-projects, .t7-section.t7-projects');
      const manifesto = document.querySelector('.manifesto');
      const fcta = document.querySelector('.final-cta');
      const grid = document.querySelector('.posts-grid, .posts-grid-4');
      return { sectionOrder: secs.slice(0, 25), hasProjects: !!proj, hasPostsGrid: !!grid, hasManifesto: !!manifesto, hasFinalCta: !!fcta,
        betweenProjectsAndFinalCta: !!(proj && grid && fcta && proj.compareDocumentPosition(grid) === 4 && grid.compareDocumentPosition(fcta) === 4) };
    })(),
  };
  return JSON.stringify({
    path: location.pathname + location.search,
    title: document.title,
    lockHits,
    lockPass: lockHits.length === 0,
    overflow,
    overflowPass: overflow.length === 0 && de.scrollWidth <= de.clientWidth + 1,
    scrollWidth: de.scrollWidth,
    clientWidth: de.clientWidth,
    themeVersion: themeVer,
    assetSample,
    computed: {
      eyebrow: pick('.eyebrow, .t7-eyebrow, .page-hero .eyebrow', ['fontFamily','fontSize','letterSpacing','textTransform','color']),
      waBtn: pick('.wa-btn', ['backgroundColor']),
      waBtnHover: waHover,
      bridgesGrid: bridges,
      vcBridges,
      contactDump,
      aboutProse,
      finalCta,
      bodyLineHeight: bodyLh,
      sectionLineHeight: secLh,
      tokens,
    },
    section06,
  });
})()`;

async function main() {
  const shotsDir = join(OUT, 'screenshots');
  const cdpDir = join(OUT, 'cdp_probe');
  mkdirSync(shotsDir, { recursive: true });
  mkdirSync(cdpDir, { recursive: true });

  const chrome = findChrome();
  const port = 9100 + Math.floor((Date.now() % 500));
  const proc = spawn(chrome, [
    '--headless', '--disable-gpu', '--no-sandbox',
    `--remote-debugging-port=${port}`,
    '--ignore-certificate-errors', '--hide-scrollbars',
  ], { stdio: 'ignore' });
  await new Promise(r => setTimeout(r, 1800));

  const captureTs = new Date().toISOString();
  const shotMeta = [];
  const probeResults = [];
  const styleProofs = {};

  try {
    for (const vp of VIEWPORTS) {
      for (const sc of SCREENS) {
        const url = BASE.replace(/\/$/, '') + sc.path + (sc.path.includes('?') ? '&' : '?') + 'nc=' + Date.now();
        const t = await (await fetch(`http://127.0.0.1:${port}/json/new?about:blank`, { method: 'PUT' })).json();
        const ws = new WebSocket(t.webSocketDebuggerUrl);
        let id = 0; const pend = {};
        ws.addEventListener('message', e => { const m = JSON.parse(e.data); if (m.id && pend[m.id]) pend[m.id](m); });
        await new Promise(r => ws.addEventListener('open', r));
        const send = (method, params = {}) => new Promise(res => { const i = ++id; pend[i] = res; ws.send(JSON.stringify({ id: i, method, params })); });
        await send('Page.enable'); await send('Runtime.enable');
        await send('Emulation.setDeviceMetricsOverride', {
          width: vp.w, height: vp.h, deviceScaleFactor: vp.dpr, mobile: vp.w < 768,
        });
        await send('Page.navigate', { url });
        await new Promise(r => setTimeout(r, 3500));
        const ev = await send('Runtime.evaluate', { expression: PROBE_JS, returnByValue: true });
        const data = ev.result?.result?.value ? JSON.parse(ev.result.result.value) : null;
        const cap = await send('Page.captureScreenshot', { format: 'png', captureBeyondViewport: true });
        const fname = `live_${sc.id}_${vp.label}.png`;
        const fpath = join(shotsDir, fname);
        if (cap.result?.data) writeFileSync(fpath, Buffer.from(cap.result.data, 'base64'));
        shotMeta.push({
          filename: fname,
          screen: sc.id,
          mockup: sc.id.startsWith('sys') ? 'sys' : sc.id,
          viewport: vp.label,
          url,
          devicePixelRatio: vp.dpr,
          captureTimestamp: captureTs,
          themeVersion: data?.themeVersion,
          repoShaAtCapture: REPO_SHA,
          baselineSha: BASELINE_SHA,
        });
        probeResults.push({
          screen: sc.id, viewport: vp.label, url: sc.path, ...data,
          screenshot: fname,
        });
        if (vp.label === '1440') styleProofs[sc.id] = data?.computed;
        ws.close();
        await fetch(`http://127.0.0.1:${port}/json/close/${t.id}`).catch(() => {});
      }
    }
  } finally { proc.kill(); }

  writeFileSync(join(OUT, 'live_probe_details.json'), JSON.stringify(probeResults, null, 2));
  writeFileSync(join(OUT, 'shot_metadata.json'), JSON.stringify(shotMeta, null, 2));
  writeFileSync(join(OUT, 'computed_style_proofs_1440.json'), JSON.stringify(styleProofs, null, 2));
  writeFileSync(join(OUT, 'section06_dom_proof.json'), JSON.stringify(styleProofs.t7?.section06 || probeResults.find(r => r.screen === 't7' && r.viewport === '1440')?.section06, null, 2));

  const statesNote = {
    screen: 'states',
    status: 'design-spec-only',
    note: 'Mockup states screen (t8 error/empty UI variants) has no dedicated live route; see Precision Mockup v4.html #states panel.',
    mockupRef: 'design_handoff_ui_precision_v200/Precision Mockup v4.html',
  };
  writeFileSync(join(OUT, 'states_design_spec_only.md'), `# states — design-spec only\n\n${statesNote.note}\n\nCaptured: ${captureTs}\n`);

  const lockAggregate = [];
  let lockPass = true;
  for (const r of probeResults) {
    if (r.lockHits?.length) {
      lockPass = false;
      lockAggregate.push({ screen: r.screen, viewport: r.viewport, hits: r.lockHits });
    }
  }
  writeFileSync(join(OUT, 'lock_scan_aggregate.json'), JSON.stringify({
    ts: captureTs, pass: lockPass, totalHits: lockAggregate.reduce((n, x) => n + x.hits.length, 0), hits: lockAggregate, termsScanned: LOCK_TERMS,
  }, null, 2));

  const env = {
    base: BASE,
    NB_THEME_VERSION_deployed: probeResults.find(r => r.themeVersion)?.themeVersion || null,
    cacheBustSample: probeResults.find(r => r.assetSample)?.assetSample,
    repoShaAtCapture: REPO_SHA,
    mandateBaselineSha: BASELINE_SHA,
    shaDelta: REPO_SHA.startsWith(BASELINE_SHA) ? 'matches prefix' : (REPO_SHA === BASELINE_SHA ? 'exact match' : `local repo ${REPO_SHA} vs mandate baseline ${BASELINE_SHA}`),
    themeVersionInRepo: (() => {
      try {
        const fn = readFileSync('nimrod.bio/wp-content/themes/nimrod-bio-2026/functions.php', 'utf8');
        return fn.match(/NB_THEME_VERSION',\s*'([^']+)'/)?.[1];
      } catch { return null; }
    })(),
    captureTimestamp: captureTs,
    lockScanPass: lockPass,
  };
  writeFileSync(join(OUT, 'environment_integrity.json'), JSON.stringify(env, null, 2));

  console.log(JSON.stringify({ out: OUT, shots: shotMeta.length, lockPass, env }, null, 2));
}

main().catch(e => { console.error('FATAL', e); process.exit(2); });
