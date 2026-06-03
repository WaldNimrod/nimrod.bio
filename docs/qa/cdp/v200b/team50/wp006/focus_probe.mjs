#!/usr/bin/env node
/** WP006 keyboard/focus spot-check — home + contact @375 */
import { spawn, execSync } from 'node:child_process';
import { writeFileSync } from 'node:fs';

function findChrome() {
  const home = process.env.HOME;
  const out = execSync(`find "${home}/.cache/puppeteer" -name chrome-headless-shell -type f 2>/dev/null | sort -V | tail -1`, { encoding: 'utf8' }).trim();
  if (out) return out;
  return '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome';
}

async function cdpPage(port, url, w, h) {
  const t = await (await fetch(`http://127.0.0.1:${port}/json/new?about:blank`, { method: 'PUT' })).json();
  const ws = new WebSocket(t.webSocketDebuggerUrl);
  let id = 0; const pend = {};
  ws.addEventListener('message', e => { const m = JSON.parse(e.data); if (m.id && pend[m.id]) pend[m.id](m); });
  await new Promise(r => ws.addEventListener('open', r));
  const send = (method, params = {}) => new Promise(res => { const i = ++id; pend[i] = res; ws.send(JSON.stringify({ id: i, method, params })); });
  await send('Page.enable'); await send('Runtime.enable');
  await send('Emulation.setDeviceMetricsOverride', { width: w, height: h, deviceScaleFactor: 1, mobile: true });
  await send('Page.navigate', { url });
  await new Promise(r => setTimeout(r, 3500));
  const eval_ = async (expr) => {
    const r = await send('Runtime.evaluate', { expression: expr, awaitPromise: true, returnByValue: true });
    if (r.result?.exceptionDetails) throw new Error(JSON.stringify(r.result.exceptionDetails));
    return r.result?.result?.value;
  };
  const click = async (sel) => eval_(`(()=>{const el=document.querySelector(${JSON.stringify(sel)}); if(!el) return {ok:false}; el.click(); return {ok:true};})()`);
  const key = async (key, code) => send('Input.dispatchKeyEvent', { type: 'keyDown', key, code, windowsVirtualKeyCode: code });
  const keyUp = async (key, code) => send('Input.dispatchKeyEvent', { type: 'keyUp', key, code, windowsVirtualKeyCode: code });
  const tab = async () => { await key('Tab', 9); await keyUp('Tab', 9); await new Promise(r => setTimeout(r, 80)); };
  const esc = async () => { await key('Escape', 27); await keyUp('Escape', 27); await new Promise(r => setTimeout(r, 200)); };
  return { send, eval_, click, tab, esc, close: async () => { ws.close(); await fetch(`http://127.0.0.1:${port}/json/close/${t.id}`).catch(() => {}); } };
}

async function probeHome(port, base) {
  const url = base + '/?nc=' + Date.now();
  const p = await cdpPage(port, url, 375, 812);
  const checks = [];
  const skip = await p.eval_(`(()=>{const a=document.querySelector('a.skip-link,a[href="#main"],a[href="#content"]'); return {exists:!!a, href:a?.getAttribute('href'), text:(a?.textContent||'').trim().slice(0,40)};})()`);
  checks.push({ id: 'skip-link-present', pass: !!skip.exists, detail: skip });
  await p.tab();
  const afterTab = await p.eval_(`(()=>({activeTag:document.activeElement?.tagName, activeClass:document.activeElement?.className, activeHref:document.activeElement?.getAttribute?.('href')}))()`);
  checks.push({ id: 'first-tab-focus', pass: true, detail: afterTab });
  const closedDrawer = await p.eval_(`(()=>{const d=document.querySelector('.nav-drawer,#nav-drawer'); const links=d?Array.from(d.querySelectorAll('a[href]')):[]; return {inert:d?.hasAttribute('inert'), ariaHidden:d?.getAttribute('aria-hidden'), linkCount:links.length, tabIndices:links.map(a=>a.getAttribute('tabindex')), focusableWhileClosed:links.filter(a=>{const ti=a.getAttribute('tabindex'); return ti!== '-1' && !d?.hasAttribute('inert');}).length};})()`);
  checks.push({ id: 'drawer-closed-not-focusable', pass: closedDrawer.inert && closedDrawer.ariaHidden === 'true' && closedDrawer.focusableWhileClosed === 0, detail: closedDrawer });
  await p.click('.nav-toggle');
  await new Promise(r => setTimeout(r, 400));
  const openState = await p.eval_(`(()=>{const d=document.querySelector('.nav-drawer,#nav-drawer'); return {isOpen:d?.classList.contains('is-open'), inert:d?.hasAttribute('inert'), ariaHidden:d?.getAttribute('aria-hidden'), activeInDrawer:!!d?.contains(document.activeElement)};})()`);
  checks.push({ id: 'drawer-opens', pass: openState.isOpen && openState.ariaHidden === 'false' && !openState.inert, detail: openState });
  await p.tab(); await p.tab();
  const trap = await p.eval_(`(()=>({activeInDrawer:document.querySelector('.nav-drawer,#nav-drawer')?.contains(document.activeElement), activeTag:document.activeElement?.tagName}))()`);
  checks.push({ id: 'focus-in-drawer-when-open', pass: trap.activeInDrawer, detail: trap });
  await p.eval_(`document.dispatchEvent(new KeyboardEvent('keydown',{key:'Escape',code:'Escape',bubbles:true}));`);
  await new Promise(r => setTimeout(r, 350));
  const afterEsc = await p.eval_(`(()=>{const d=document.querySelector('.nav-drawer,#nav-drawer'); const t=document.querySelector('.nav-toggle'); return {isOpen:d?.classList.contains('is-open'), inert:d?.hasAttribute('inert'), toggleFocused:document.activeElement===t};})()`);
  checks.push({ id: 'esc-closes-drawer', pass: !afterEsc.isOpen && afterEsc.inert && afterEsc.toggleFocused, detail: afterEsc });
  await p.close();
  return checks;
}

async function probeContact(port, base) {
  const url = base + '/contact/?nc=' + Date.now();
  const p = await cdpPage(port, url, 375, 812);
  const checks = [];
  const direct = await p.eval_(`(()=>{const el=document.querySelector('#nb-contact-name,#nb-contact input[name="name"]'); if(!el) return {exists:false}; el.focus(); return {exists:true, focused:document.activeElement===el, id:el.id};})()`);
  checks.push({ id: 'contact-form-focusable', pass: direct.exists && direct.focused, detail: direct });
  let foundForm = false;
  for (let i = 0; i < 55; i++) {
    await p.tab();
    const st = await p.eval_(`(()=>{const el=document.activeElement; const inForm=!!el?.closest('#nb-contact,.contact-form'); return {tag:el?.tagName, name:el?.name||el?.id, inForm, type:el?.type};})()`);
    if (st.inForm) { foundForm = true; checks.push({ id: 'contact-form-tab-reachable', pass: true, detail: { tabs: i + 1, focus: st } }); break; }
  }
  if (!foundForm) checks.push({ id: 'contact-form-tab-reachable', pass: false, detail: 'no #nb-contact focus within 55 CDP Tab events (manual Tab may still work)' });
  await p.close();
  return checks;
}

async function main() {
  const base = 'https://nimrod-bio-2026.s887.upress.link';
  const chrome = findChrome();
  const port = 9200 + Math.floor((Date.now() % 800));
  const proc = spawn(chrome, ['--headless', '--disable-gpu', '--no-sandbox', `--remote-debugging-port=${port}`, '--ignore-certificate-errors'], { stdio: 'ignore' });
  await new Promise(r => setTimeout(r, 1800));
  try {
    const home = await probeHome(port, base);
    const contact = await probeContact(port, base);
    const all = [...home, ...contact];
    const out = { base, ts: new Date().toISOString(), checks: all, pass: all.every(c => c.pass) };
    writeFileSync('docs/qa/cdp/v200b/team50/wp006/focus_probe_result.json', JSON.stringify(out, null, 2));
    console.log(JSON.stringify(out, null, 2));
    process.exit(out.pass ? 0 : 1);
  } finally { proc.kill(); }
}
main().catch(e => { console.error(e); process.exit(2); });
