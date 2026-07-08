import { chromium } from 'playwright';
import { mkdirSync } from 'fs';

const BASE = 'http://nimrod-bio-2026.s887.upress.link';
const OUT = new URL('.', import.meta.url).pathname;
const PAGES = [
  ['home', '/'],
  ['world-soil', '/world/soil/'],
  ['service', '/services/consulting-hydro/'],
  ['project', '/project/restaurant-supply/'],
  ['post', '/blog/%d7%a4%d7%98%d7%a8%d7%99%d7%95%d7%aa-%d7%99%d7%a2%d7%a8-%d7%91%d7%92%d7%99%d7%a0%d7%94/'],
  ['blog', '/blog/'],
  ['about', '/about/'],
  ['contact', '/contact/'],
];
const WIDTHS = [360, 414, 768];

function audit() {
  const de = document.documentElement;
  const sm = [];
  const ck = (el) => {
    if (!el) return;
    const st = getComputedStyle(el);
    if (st.display === 'none' || st.visibility === 'hidden') return;
    const r = el.getBoundingClientRect();
    if (r.width < 1) return;
    if (r.width < 44 || r.height < 44) {
      sm.push({
        cls: String(el.className || el.tagName).slice(0, 40),
        w: Math.round(r.width),
        h: Math.round(r.height),
        t: (el.textContent || '').trim().slice(0, 24),
      });
    }
  };
  ck(document.querySelector('.nav-toggle'));
  document
    .querySelectorAll(
      '.drawer-link,.shell-links a,.wa-fab,.topic-chip,.contact-form button[type=submit],.hero-cta,a.contact,.final-cta a,.cta-path a,.proj-card a,.chip'
    )
    .forEach(ck);
  const ov = [];
  document.querySelectorAll('body *').forEach((el) => {
    const r = el.getBoundingClientRect();
    if (r.right > de.clientWidth + 2 && r.width > 30) {
      const id = el.tagName + (el.className ? '.' + String(el.className).split(' ')[0] : '');
      if (!ov.find((x) => x.id === id)) ov.push({ id, right: Math.round(r.right), vw: de.clientWidth });
    }
  });
  const inp = [...document.querySelectorAll('.contact-form input:not(.hp-field), .contact-form textarea')]
    .filter((e) => e.type !== 'hidden' && getComputedStyle(e).display !== 'none')
    .map((e) => ({ id: e.id, fs: parseFloat(getComputedStyle(e).fontSize) }));
  return {
    path: location.pathname,
    innerWidth: window.innerWidth,
    hScroll: de.scrollWidth > de.clientWidth + 1,
    scrollW: de.scrollWidth,
    clientW: de.clientWidth,
    dir: document.documentElement.dir,
    adminBar: !!document.getElementById('wpadminbar'),
    match640: matchMedia('(max-width: 640px)').matches,
    toggle: document.querySelector('.nav-toggle')
      ? getComputedStyle(document.querySelector('.nav-toggle')).display
      : 'n/a',
    shell: document.querySelector('.shell-links')
      ? getComputedStyle(document.querySelector('.shell-links')).display
      : 'n/a',
    fab: document.querySelector('.wa-fab')
      ? getComputedStyle(document.querySelector('.wa-fab')).display
      : 'n/a',
    footerCols: document.querySelector('.shell-foot .cols')
      ? getComputedStyle(document.querySelector('.shell-foot .cols')).gridTemplateColumns
      : null,
    smallTargets: sm.slice(0, 8),
    overflowEls: ov.slice(0, 5),
    inputs: inp,
  };
}

mkdirSync(OUT, { recursive: true });
const browser = await chromium.launch({ headless: true });
const results = {};

for (const w of WIDTHS) {
  results[w] = {};
  const ctx = await browser.newContext({
    viewport: { width: w, height: 900 },
    locale: 'he-IL',
    isMobile: w <= 640,
    hasTouch: w <= 640,
  });
  const page = await ctx.newPage();
  for (const [slug, path] of PAGES) {
    await page.goto(BASE + path, { waitUntil: 'networkidle', timeout: 60000 });
    await page.waitForTimeout(300);
    results[w][slug] = await page.evaluate(audit);
    if (w === 360 || w === 768) {
      const shot = `${OUT}${slug}-${w}.png`;
      await page.screenshot({ path: shot, fullPage: false });
    }
  }
  await ctx.close();
}

await browser.close();
console.log(JSON.stringify(results, null, 2));
