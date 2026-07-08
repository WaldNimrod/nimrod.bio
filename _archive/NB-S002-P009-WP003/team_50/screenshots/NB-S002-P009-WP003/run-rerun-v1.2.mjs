import { chromium } from '../NB-S002-P009-WP002/node_modules/playwright/index.mjs';
import { writeFileSync } from 'fs';
import { fileURLToPath } from 'url';

const BASE = 'http://nimrod-bio-2026.s887.upress.link/';
const OUT = fileURLToPath(new URL('.', import.meta.url));

async function auditHome(page) {
  return page.evaluate(() => {
    const de = document.documentElement;
    let maxR = 0;
    let culprit = null;
    document.querySelectorAll('body *').forEach((el) => {
      if (el.classList?.contains('skip-link')) return;
      const st = getComputedStyle(el);
      if (st.display === 'none' || st.visibility === 'hidden' || st.position === 'fixed') return;
      const r = el.getBoundingClientRect();
      if (r.width < 2) return;
      if (r.right > maxR) {
        maxR = r.right;
        culprit = (el.tagName + (el.className ? '.' + String(el.className).split(' ')[0] : '')).slice(0, 60);
      }
    });
    const worlds = document.querySelector('.t7-worlds .worlds-grid');
    const worldsIntro = document.querySelector('.t7-worlds .worlds-intro');
    const heroPoster = document.querySelector('.hero-poster');
    const hpBg = document.querySelector('.hp-bg');
    return {
      scrollW: de.scrollWidth,
      clientW: de.clientWidth,
      hScroll: de.scrollWidth > de.clientWidth,
      maxRight: Math.round(maxR),
      culprit,
      worldsCols: worlds ? getComputedStyle(worlds).gridTemplateColumns : null,
      worldsIntroOverflowX: worldsIntro ? getComputedStyle(worldsIntro).overflowX : null,
      heroOverflowX: heroPoster ? getComputedStyle(heroPoster).overflowX : null,
      hpBgOverflowX: hpBg ? getComputedStyle(hpBg).overflowX : null,
      dir: document.documentElement.dir,
    };
  });
}

async function testCarousel(page, trackSel, sectionSel) {
  return page.evaluate(async ({ trackSel, sectionSel }) => {
    const track = document.querySelector(trackSel);
    if (!track) return { error: 'no track' };
    const section = document.querySelector(sectionSel);
    const arrows = [...(section?.querySelectorAll('.pb-arrow') || [])];
    const run = async (btn) => {
      const before = track.scrollLeft;
      btn?.click();
      await new Promise((r) => setTimeout(r, 500));
      return {
        label: btn?.getAttribute('aria-label'),
        before,
        after: track.scrollLeft,
        moved: Math.abs(track.scrollLeft - before) > 5,
        disabled: btn?.disabled,
      };
    };
    const prev = arrows.find((b) => b.getAttribute('aria-label') === 'הקודם');
    const next = arrows.find((b) => b.getAttribute('aria-label') === 'הבא');
    const nextClick = await run(next);
    const prevClick = await run(prev);
    return {
      snap: getComputedStyle(track).scrollSnapType,
      nextMoved: nextClick.moved,
      prevMoved: prevClick.moved,
      nextClick,
      prevClick,
    };
  }, { trackSel, sectionSel });
}

const results = {
  deviceCheckVersion: 'v1.2.0',
  themeVersion: '0.6.3',
  home: {},
  carousels: {},
  worlds: {},
  regression: {},
  drawer: {},
};

const browser = await chromium.launch({ headless: true });

for (const w of [360, 375, 768, 1440]) {
  const ctx = await browser.newContext({
    viewport: { width: w, height: 900 },
    locale: 'he-IL',
    isMobile: w <= 640,
    hasTouch: w <= 640,
  });
  const page = await ctx.newPage();
  await page.goto(BASE, { waitUntil: 'load', timeout: 120000 });
  await page.waitForTimeout(600);
  results.home[w] = await auditHome(page);
  if (w === 375) {
    results.worlds[375] = results.home[375].worldsCols;
  }
  if (w === 1440) {
    results.carousels.services = await testCarousel(page, '.products-grid.carousel', '.t7-services');
    results.carousels.projects = await testCarousel(page, '.projects-row', '.t7-projects');
  }
  if (w === 375) {
    await page.locator('#worlds').scrollIntoViewIfNeeded();
    await page.waitForTimeout(200);
    await page.screenshot({ path: `${OUT}rerun-d2-worlds-375.png` });
  }
  await ctx.close();
}

for (const [slug, path] of [
  ['world-soil', '/world/soil/'],
  ['contact', '/contact/'],
]) {
  const ctx = await browser.newContext({ viewport: { width: 375, height: 812 }, locale: 'he-IL', isMobile: true });
  const page = await ctx.newPage();
  await page.goto(BASE + path.replace(/^\//, ''), { waitUntil: 'load', timeout: 120000 });
  await page.waitForTimeout(400);
  results.regression[slug] = await page.evaluate(() => {
    const de = document.documentElement;
    return {
      path: location.pathname,
      hScroll: de.scrollWidth > de.clientWidth,
      scrollW: de.scrollWidth,
      clientW: de.clientWidth,
      dir: document.documentElement.dir,
      fab: document.querySelector('.wa-fab') ? getComputedStyle(document.querySelector('.wa-fab')).display : null,
      toggle: document.querySelector('.nav-toggle') ? getComputedStyle(document.querySelector('.nav-toggle')).display : null,
    };
  });
  await ctx.close();
}

{
  const ctx = await browser.newContext({ viewport: { width: 375, height: 812 }, locale: 'he-IL', isMobile: true });
  const page = await ctx.newPage();
  await page.goto(BASE, { waitUntil: 'load', timeout: 120000 });
  await page.waitForTimeout(400);
  await page.click('.nav-toggle');
  await page.waitForTimeout(300);
  results.drawer = await page.evaluate(() => ({
    bodyLock: document.body.classList.contains('nav-open'),
    fab: document.querySelector('.wa-fab') ? getComputedStyle(document.querySelector('.wa-fab')).display : null,
    drawerOpen: document.querySelector('.nav-drawer')?.classList.contains('is-open'),
  }));
  await page.keyboard.press('Escape');
  await page.waitForTimeout(200);
  results.drawerAfterEsc = await page.evaluate(() => ({
    bodyLock: document.body.classList.contains('nav-open'),
    drawerOpen: document.querySelector('.nav-drawer')?.classList.contains('is-open'),
  }));
  await ctx.close();
}

{
  const ctx = await browser.newContext({ viewport: { width: 375, height: 812 } });
  const page = await ctx.newPage();
  await page.goto(BASE, { waitUntil: 'load', timeout: 120000 });
  results.transferMB = await page.evaluate(
    () => Math.round((performance.getEntriesByType('resource').reduce((a, e) => a + (e.transferSize || 0), 0) / 1048576) * 100) / 100
  );
  await ctx.close();
}

await browser.close();
writeFileSync(`${OUT}audit-results-v1.2.0.json`, JSON.stringify(results, null, 2));
console.log(JSON.stringify(results, null, 2));
