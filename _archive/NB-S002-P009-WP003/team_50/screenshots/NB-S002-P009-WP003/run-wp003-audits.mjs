import { chromium } from '../NB-S002-P009-WP002/node_modules/playwright/index.mjs';
import { mkdirSync, writeFileSync } from 'fs';
import { fileURLToPath } from 'url';

const BASE = 'http://nimrod-bio-2026.s887.upress.link';
const OUT = fileURLToPath(new URL('.', import.meta.url));
const WIDTHS = [
  { w: 1440, h: 900, label: '1440' },
  { w: 768, h: 1024, label: '768' },
  { w: 375, h: 812, label: '375' },
  { w: 360, h: 740, label: '360' },
];

const SECTIONS = [
  ['01-nav-hero', '.hero-poster, .shell-nav'],
  ['02-worlds', '.worlds-section, section.worlds'],
  ['03-systems', '.systems-section'],
  ['04-services', '.services-section, .svc-carousel'],
  ['05-bridges', '.bridges-section, .bridges-band'],
  ['06-unless', '.unless-ribbon, .unless-lockup'],
  ['07-projects', '.projects-section, .proj-carousel'],
  ['08-manifesto', '.manifesto-section, .t7-manifesto'],
  ['09-final-cta', '.final-cta-section, .cta-paths'],
  ['10-footer', '.shell-foot'],
];

function homeAudit() {
  const de = document.documentElement;
  const nav = document.querySelector('.shell-nav');
  const hero = document.querySelector('.hero-poster');
  const sections = [
    '.shell-nav',
    '.hero-poster',
    '.worlds-section',
    '.systems-section',
    '.services-section',
    '.bridges-section',
    '.unless-ribbon',
    '.projects-section',
    '.manifesto-section',
    '.final-cta-section',
    '.shell-foot',
  ].map((sel) => ({ sel, found: !!document.querySelector(sel) }));

  const ov = [];
  document.querySelectorAll('body *').forEach((el) => {
    const r = el.getBoundingClientRect();
    if (r.right > de.clientWidth + 2 && r.width > 20) {
      const id = el.tagName + (el.className ? '.' + String(el.className).split(' ')[0] : '');
      if (!ov.find((x) => x.id === id)) ov.push({ id, right: Math.round(r.right), vw: de.clientWidth });
    }
  });

  const svcTrack = document.querySelector('.svc-carousel .track, .services-carousel .track');
  const projTrack = document.querySelector('.proj-carousel .track, .projects-carousel .track');

  return {
    path: location.pathname,
    innerWidth: window.innerWidth,
    dir: document.documentElement.dir,
    lang: document.documentElement.lang,
    themeVersion: document.querySelector('link[href*="nimrod-bio-2026"]')?.href?.match(/ver=([\d.]+)/)?.[1] || null,
    hScroll: de.scrollWidth > de.clientWidth + 1,
    scrollW: de.scrollWidth,
    clientW: de.clientWidth,
    navAtop: nav?.classList.contains('atop'),
    navBg: nav ? getComputedStyle(nav).backgroundColor : null,
    heroH: hero ? Math.round(hero.getBoundingClientRect().height) : null,
    sections,
    carousel: {
      services: {
        track: !!svcTrack,
        snap: svcTrack ? getComputedStyle(svcTrack).scrollSnapType : null,
        scrollW: svcTrack?.scrollWidth,
        clientW: svcTrack?.clientWidth,
      },
      projects: {
        track: !!projTrack,
        snap: projTrack ? getComputedStyle(projTrack).scrollSnapType : null,
        scrollW: projTrack?.scrollWidth,
        clientW: projTrack?.clientWidth,
      },
    },
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
    overflowEls: ov.slice(0, 8),
    resources: performance.getEntriesByType('resource').filter((e) => e.initiatorType === 'img' || /\.(png|jpe?g|webp|svg)/i.test(e.name)).length,
    transferMB: Math.round((performance.getEntriesByType('resource').reduce((a, e) => a + (e.transferSize || 0), 0) / 1048576) * 100) / 100,
  };
}

async function scrollToSection(page, selector) {
  const el = await page.$(selector);
  if (!el) return false;
  await el.evaluate((node) => node.scrollIntoView({ block: 'start', behavior: 'instant' }));
  await page.waitForTimeout(200);
  return true;
}

mkdirSync(OUT, { recursive: true });
const browser = await chromium.launch({ headless: true });
const results = { home: {}, inner: {} };

for (const { w, h, label } of WIDTHS) {
  const ctx = await browser.newContext({
    viewport: { width: w, height: h },
    locale: 'he-IL',
    isMobile: w <= 640,
    hasTouch: w <= 640,
  });
  const page = await ctx.newPage();
  await page.goto(BASE + '/', { waitUntil: 'load', timeout: 120000 });
  await page.waitForTimeout(400);

  results.home[label] = await page.evaluate(homeAudit);

  if (label === '1440' || label === '375' || label === '768') {
    await page.screenshot({ path: `${OUT}home-top-${label}.png` });
    for (const [slug, sel] of SECTIONS) {
      const ok = await scrollToSection(page, sel.split(',')[0].trim());
      if (ok) await page.screenshot({ path: `${OUT}section-${slug}-${label}.png` });
    }
    await page.evaluate(() => window.scrollTo(0, 0));
    await page.waitForTimeout(200);
    await page.screenshot({ path: `${OUT}home-full-${label}.png`, fullPage: true });
  }

  if (label === '375') {
    const navScroll = await page.evaluate(async () => {
      const nav = document.querySelector('.shell-nav');
      const before = nav?.classList.contains('atop');
      window.scrollTo(0, document.body.scrollHeight);
      await new Promise((r) => requestAnimationFrame(() => requestAnimationFrame(r)));
      const after = nav?.classList.contains('atop');
      const bg = nav ? getComputedStyle(nav).backgroundColor : null;
      window.scrollTo(0, 0);
      return { beforeAtop: before, afterScrollAtop: after, bgAfterScroll: bg };
    });
    results.home[label].navScrollTest = navScroll;

    await page.click('.nav-toggle', { timeout: 5000 }).catch(() => {});
    await page.waitForTimeout(300);
    results.home[label].drawerOpen = await page.evaluate(() => ({
      drawer: document.querySelector('.shell-drawer')?.classList.contains('is-open'),
      backdrop: document.querySelector('.drawer-backdrop')?.classList.contains('is-open'),
      bodyLock: document.body.classList.contains('nav-open'),
      fab: document.querySelector('.wa-fab') ? getComputedStyle(document.querySelector('.wa-fab')).display : null,
    }));
    await page.screenshot({ path: `${OUT}drawer-open-375-home.png` });
    await page.keyboard.press('Escape');
    await page.waitForTimeout(200);
    await page.screenshot({ path: `${OUT}drawer-closed-375-home.png` });
  }

  await ctx.close();
}

// WP002 regression: inner page + contact FAB
for (const [slug, path] of [
  ['world-soil', '/world/soil/'],
  ['contact', '/contact/'],
]) {
  const ctx = await browser.newContext({ viewport: { width: 375, height: 812 }, locale: 'he-IL', isMobile: true });
  const page = await ctx.newPage();
  try {
    await page.goto(BASE + path, { waitUntil: 'load', timeout: 120000 });
  } catch (e) {
    results.inner[slug] = { error: String(e.message || e) };
    await ctx.close();
    continue;
  }
  await page.waitForTimeout(300);
  results.inner[slug] = await page.evaluate(() => {
    const de = document.documentElement;
    return {
      path: location.pathname,
      dir: document.documentElement.dir,
      hScroll: de.scrollWidth > de.clientWidth + 1,
      scrollW: de.scrollWidth,
      clientW: de.clientWidth,
      fab: document.querySelector('.wa-fab') ? getComputedStyle(document.querySelector('.wa-fab')).display : null,
      toggle: document.querySelector('.nav-toggle') ? getComputedStyle(document.querySelector('.nav-toggle')).display : null,
    };
  });
  await page.screenshot({ path: `${OUT}inner-${slug}-375.png` });
  await ctx.close();
}

// Carousel arrow test @1440
{
  const ctx = await browser.newContext({ viewport: { width: 1440, height: 900 }, locale: 'he-IL' });
  const page = await ctx.newPage();
  await page.goto(BASE + '/', { waitUntil: 'load', timeout: 120000 });
  await page.waitForTimeout(400);
  results.carouselTest = await page.evaluate(async () => {
    const test = (rootSel) => {
      const root = document.querySelector(rootSel);
      if (!root) return { error: 'no root' };
      const track = root.querySelector('.track');
      const next = root.querySelector('.carousel-next, .nb-carousel-next, button[name="הבא"], button[aria-label="הבא"]');
      if (!track) return { error: 'no track' };
      const before = track.scrollLeft;
      next?.click();
      return new Promise((resolve) => {
        requestAnimationFrame(() => {
          requestAnimationFrame(() => {
            resolve({
              before,
              after: track.scrollLeft,
              moved: Math.abs(track.scrollLeft - before) > 5,
              snap: getComputedStyle(track).scrollSnapType,
            });
          });
        });
      });
    };
    const services = await test('.services-section .svc-carousel, .services-section [class*="carousel"]');
    const projects = await test('.projects-section .proj-carousel, .projects-section [class*="carousel"]');
    return { services, projects };
  });
  await ctx.close();
}

await browser.close();
writeFileSync(`${OUT}audit-results.json`, JSON.stringify(results, null, 2));
console.log(JSON.stringify(results, null, 2));
