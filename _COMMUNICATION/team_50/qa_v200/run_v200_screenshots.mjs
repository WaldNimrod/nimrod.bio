import { chromium } from '../screenshots/NB-S002-P009-WP002/node_modules/playwright/index.mjs';
import { mkdirSync, writeFileSync } from 'fs';
import { fileURLToPath } from 'url';

const BASE = 'https://nimrod-bio-2026.s887.upress.link';
const OUT = fileURLToPath(new URL('./screenshots/', import.meta.url));
const EVIDENCE = fileURLToPath(new URL('./evidence/', import.meta.url));

const PAGES = [
  ['home', '/'],
  ['about', '/about/'],
  ['about-heritage', '/about/heritage/'],
  ['contact', '/contact/'],
  ['world-soil', '/world/soil/'],
  ['world-know', '/world/know/'],
  ['world-code', '/world/code/'],
  ['project-sfa', '/project/sfa/'],
  ['project-tiktrack', '/project/tiktrack/'],
  ['project-hagina', '/project/hagina-shel-nimrod/'],
  ['project-greenhouse', '/project/rest-x-greenhouse/'],
  ['service-bcs', '/services/bcs/'],
];

const VIEWPORTS = [
  { w: 1440, h: 900, label: '1440' },
  { w: 375, h: 812, label: '375' },
];

function pageAudit() {
  const de = document.documentElement;
  const ov = [];
  document.querySelectorAll('body *').forEach((el) => {
    const r = el.getBoundingClientRect();
    if (r.right > de.clientWidth + 2 && r.width > 20) {
      const id = el.tagName + (el.className ? '.' + String(el.className).split(' ')[0] : '');
      if (!ov.find((x) => x.id === id)) ov.push({ id, right: Math.round(r.right), vw: de.clientWidth });
    }
  });
  const nav = document.querySelector('.shell-nav');
  const footer = document.querySelector('.shell-foot');
  const worlds = [...document.querySelectorAll('.shell-links a[href*="/world/"]')].map((a) => a.getAttribute('href'));
  return {
    path: location.pathname,
    dir: document.documentElement.dir,
    lang: document.documentElement.lang,
    hScroll: de.scrollWidth > de.clientWidth + 1,
    scrollW: de.scrollWidth,
    clientW: de.clientWidth,
    navLinks: worlds,
    hasBlog: !!document.querySelector('a[href*="/blog/"]'),
    hasAbout: !!document.querySelector('a[href*="/about/"]'),
    hasContact: !!document.querySelector('a[href*="/contact/"]'),
    fab: document.querySelector('.wa-fab') ? getComputedStyle(document.querySelector('.wa-fab')).display : null,
    overflowEls: ov.slice(0, 8),
    themeVersion: document.querySelector('link[href*="nimrod-bio-2026"]')?.href?.match(/ver=([\d.]+)/)?.[1] || null,
  };
}

mkdirSync(OUT, { recursive: true });
mkdirSync(EVIDENCE, { recursive: true });

const browser = await chromium.launch({
  headless: true,
  args: ['--ignore-certificate-errors'],
});

const results = [];

for (const [slug, path] of PAGES) {
  for (const { w, h, label } of VIEWPORTS) {
    const ctx = await browser.newContext({
      viewport: { width: w, height: h },
      locale: 'he-IL',
      isMobile: w <= 640,
      hasTouch: w <= 640,
      ignoreHTTPSErrors: true,
    });
    const page = await ctx.newPage();
    const url = BASE + path;
    const filename = `${slug}_${label}.png`;
    const outPath = `${OUT}${filename}`;
    const row = {
      slug,
      path,
      viewport: label,
      url,
      file: `qa_v200/screenshots/${filename}`,
      http_status: null,
      audit: null,
      pass: false,
      error: null,
    };
    try {
      const response = await page.goto(url, { waitUntil: 'networkidle', timeout: 90000 });
      row.http_status = response?.status() ?? null;
      await page.waitForTimeout(400);
      row.audit = await page.evaluate(pageAudit);
      await page.screenshot({ path: outPath, fullPage: true });
      row.pass = row.http_status === 200 && row.audit?.dir === 'rtl' && !row.audit?.hScroll;
    } catch (e) {
      row.error = String(e.message || e);
    } finally {
      await ctx.close();
    }
    results.push(row);
    console.log(`[${row.pass ? 'OK' : 'FAIL'}] ${filename} status=${row.http_status} hScroll=${row.audit?.hScroll}`);
  }
}

await browser.close();
writeFileSync(`${EVIDENCE}screenshot_manifest.json`, JSON.stringify({ generated_at: new Date().toISOString(), pages: results }, null, 2));
console.log(`[OK] wrote screenshot_manifest.json (${results.length} captures)`);
