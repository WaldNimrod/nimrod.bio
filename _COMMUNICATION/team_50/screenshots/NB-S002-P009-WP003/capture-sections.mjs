import { chromium } from '../NB-S002-P009-WP002/node_modules/playwright/index.mjs';
import { fileURLToPath } from 'url';

const OUT = fileURLToPath(new URL('.', import.meta.url));
const BASE = 'http://nimrod-bio-2026.s887.upress.link/';

const sections = [
  ['02-worlds', '#worlds'],
  ['03-systems', '.t7-systems'],
  ['04-services', '.t7-services'],
  ['05-bridges', '.t7-bridges'],
  ['06-unless', '.unless-lockup'],
  ['07-projects', '.t7-projects'],
  ['08-manifesto', '.manifesto'],
  ['09-final-cta', '.final-cta'],
];

const browser = await chromium.launch({ headless: true });
const carouselResults = {};

for (const { w, label } of [
  { w: 1440, label: '1440' },
  { w: 375, label: '375' },
  { w: 768, label: '768' },
]) {
  const ctx = await browser.newContext({
    viewport: { width: w, height: 900 },
    locale: 'he-IL',
    isMobile: w <= 640,
  });
  const page = await ctx.newPage();
  await page.goto(BASE, { waitUntil: 'load', timeout: 120000 });
  await page.waitForTimeout(800);

  for (const [slug, sel] of sections) {
    const el = await page.$(sel);
    if (el) {
      await el.evaluate((n) => n.scrollIntoView({ block: 'start' }));
      await page.waitForTimeout(250);
      await page.screenshot({ path: `${OUT}section-${slug}-${label}.png` });
    }
  }

  carouselResults[label] = await page.evaluate(async () => {
    const test = async (sel) => {
      const t = document.querySelector(sel);
      if (!t) return { missing: true };
      const before = t.scrollLeft;
      t.scrollBy({ left: 320, behavior: 'auto' });
      await new Promise((r) => setTimeout(r, 100));
      const afterScrollBy = t.scrollLeft;
      const btn = t.closest('section')?.querySelector('.pb-arrow[data-dir="1"]');
      btn?.click();
      await new Promise((r) => setTimeout(r, 400));
      return {
        overflowX: getComputedStyle(t).overflowX,
        snap: getComputedStyle(t).scrollSnapType,
        scrollW: t.scrollWidth,
        clientW: t.clientWidth,
        before,
        afterScrollBy,
        afterBtn: t.scrollLeft,
        movedScrollBy: Math.abs(afterScrollBy - before) > 5,
        movedBtn: Math.abs(t.scrollLeft - afterScrollBy) > 5,
      };
    };
    return {
      services: await test('.products-grid.carousel'),
      projects: await test('.projects-row'),
    };
  });

  await ctx.close();
}

await browser.close();
console.log(JSON.stringify(carouselResults, null, 2));
