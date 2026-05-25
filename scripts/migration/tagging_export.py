#!/usr/bin/env python3
"""Phase 3 — build tagging input JSON + interactive triage HTML."""
from __future__ import annotations

import html
import json
import sys
from datetime import date
from pathlib import Path

sys.path.insert(0, str(Path(__file__).resolve().parent))

from _lib import (  # noqa: E402
    REPO,
    WORLDS,
    FLOW_STYLES,
    RAW_DIR,
    ensure_cache_dirs,
    excerpt_from_record,
    importable_posts,
    load_decisions,
    read_cached_raw,
    taxonomy_hints,
)

INPUT_PATH = REPO / "docs" / "content_tagging_input.json"
HTML_PATH = REPO / "docs" / "content_tagging_triage.html"
STORAGE_KEY = "nb_content_tagging_v1"


def build_input_rows() -> list[dict]:
    rows: list[dict] = []
    for row in importable_posts(load_decisions()):
        raw = read_cached_raw(str(row["id"]))
        hints = taxonomy_hints(raw)
        rows.append(
            {
                "id": str(row["id"]),
                "title": row.get("title") or raw.get("title", {}).get("rendered", ""),
                "slug": row.get("slug", raw.get("slug", "")),
                "new_url": row.get("new_url"),
                "date": raw.get("date", "")[:10],
                "excerpt": excerpt_from_record(raw, 200),
                "categories": hints["categories"],
                "tags": hints["tags"],
            }
        )
    return rows


def build_html(data_rows: list[dict]) -> str:
    data_json = json.dumps(data_rows, ensure_ascii=False)
    world_labels = {"soil": "אדמה", "know": "ייעוץ והוראה", "code": "קוד"}
    flow_options = "".join(
        f'<option value="{slug}">{slug}</option>' for slug in FLOW_STYLES
    )
    return f"""<!doctype html>
<html dir="rtl" lang="he">
<head>
<meta charset="utf-8">
<title>nimrod.bio · Content Tagging · V200</title>
<style>
  :root {{
    --paper:#f5f3ec; --paper-2:#e8e7df; --ink:#1f1e1c; --ink-soft:#4a4844;
    --line:#d6d2c2; --soil:#6a8a3a; --know:#c46a3e; --code:#2d8a8c; --spark:#d23a2e;
  }}
  * {{ box-sizing:border-box }}
  body {{
    font-family:"Assistant","Heebo",system-ui,sans-serif;
    background:var(--paper); color:var(--ink); margin:0; padding:0;
    font-size:15px; line-height:1.55;
  }}
  header {{
    background:var(--ink); color:var(--paper); padding:18px 28px;
    position:sticky; top:0; z-index:10; display:flex; align-items:center; gap:24px;
    flex-wrap:wrap; border-block-end:3px solid var(--spark);
  }}
  header h1 {{ font-family:"Frank Ruhl Libre",serif; font-size:24px; margin:0; font-weight:700 }}
  header .stats {{ font-family:"JetBrains Mono",monospace; font-size:13px; opacity:.85 }}
  header .stats b {{ color:var(--spark) }}
  .actions {{ margin-inline-start:auto; display:flex; gap:10px; flex-wrap:wrap }}
  button {{
    font-family:inherit; font-size:14px; font-weight:600;
    padding:10px 18px; border-radius:100px; border:1px solid transparent; cursor:pointer;
    background:var(--paper); color:var(--ink);
  }}
  .btn-copy {{ background:var(--spark); color:#fff }}
  .btn-ghost {{ background:transparent; color:var(--paper); border-color:var(--paper) }}
  main {{ max-width:1400px; margin:0 auto; padding:28px }}
  .progress {{ height:8px; background:var(--paper-2); border-radius:100px; overflow:hidden; margin-block:8px 18px }}
  .progress > div {{ height:100%; background:var(--spark); transition:width .2s }}
  table {{ width:100%; border-collapse:collapse; background:#fff; border-radius:14px; overflow:hidden; box-shadow:0 10px 30px -14px rgba(60,40,10,.18) }}
  th, td {{ padding:11px 14px; text-align:right; border-block-end:1px solid var(--line); vertical-align:top }}
  th {{ background:var(--paper-2); font-size:12px; text-transform:uppercase; letter-spacing:.05em; color:var(--ink-soft); position:sticky; top:74px; z-index:5 }}
  tr.done {{ background:rgba(106,138,58,.06) }}
  tr.invalid {{ background:rgba(210,58,46,.08) }}
  .title {{ font-weight:600; max-width:320px }}
  .excerpt {{ color:var(--ink-soft); font-size:13px; max-width:360px }}
  .slug {{ font-family:"JetBrains Mono",monospace; font-size:12px; color:var(--ink-soft); word-break:break-all }}
  .worlds {{ display:flex; gap:10px; flex-wrap:wrap }}
  .worlds label {{ display:inline-flex; align-items:center; gap:6px; font-size:13px; font-weight:600 }}
  .worlds .soil {{ color:var(--soil) }}
  .worlds .know {{ color:var(--know) }}
  .worlds .code {{ color:var(--code) }}
  select, textarea, input[type=text] {{
    font-family:inherit; font-size:14px; padding:8px 10px;
    border:1.5px solid var(--line); border-radius:8px; background:#fff; width:100%;
  }}
  textarea {{ min-height:54px; resize:vertical }}
  .toast {{
    position:fixed; bottom:24px; inset-inline-start:24px;
    background:var(--ink); color:var(--paper); padding:14px 22px; border-radius:14px;
    transform:translateY(120%); transition:transform .25s; z-index:100; font-weight:600;
  }}
  .toast.show {{ transform:translateY(0) }}
  details summary {{ cursor:pointer; font-weight:600; padding:8px 0 }}
  pre {{ background:var(--paper-2); padding:14px; border-radius:8px; font-size:11px; max-height:280px; overflow:auto; direction:ltr; text-align:left }}
</style>
</head>
<body>
<header>
  <h1>Content Tagging · V200</h1>
  <div class="stats">
    <span id="cnt-total">{len(data_rows)}</span> פוסטים ·
    תויגו: <b id="cnt-done">0</b>/{len(data_rows)}
  </div>
  <div class="actions">
    <button class="btn-copy" onclick="copyJSON()">📋 העתק JSON</button>
    <button class="btn-ghost" onclick="downloadJSON()">💾 הורד</button>
    <button class="btn-ghost" onclick="loadFromClipboard()">📥 טען מ-clipboard</button>
  </div>
</header>
<main>
  <div class="progress"><div id="progress-bar" style="width:0%"></div></div>
  <p style="color:var(--ink-soft);margin-top:0">בחר לפחות עולם אחד + flow_style לכל פוסט. ברירת מחדל: <code>feature</code>. שמור כ-<code>docs/content_tagging_decisions_{date.today().isoformat()}.json</code>.</p>
  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>כותרת / תקציר</th>
        <th>URL חדש</th>
        <th>עולמות</th>
        <th>flow_style</th>
        <th>Featured?</th>
        <th>הערות</th>
      </tr>
    </thead>
    <tbody id="tbody"></tbody>
  </table>
  <details style="margin-top:24px">
    <summary>Preview JSON output</summary>
    <pre id="json-preview"></pre>
  </details>
</main>
<div id="toast" class="toast"></div>
<script>
const DATA = {data_json};
const WORLDS = {json.dumps(list(WORLDS))};
const WORLD_LABELS = {json.dumps(world_labels, ensure_ascii=False)};
const STORAGE_KEY = '{STORAGE_KEY}';

function loadState() {{
  try {{
    const saved = localStorage.getItem(STORAGE_KEY);
    if (saved) return JSON.parse(saved);
  }} catch(e) {{}}
  return {{}};
}}
function saveState(state) {{
  try {{ localStorage.setItem(STORAGE_KEY, JSON.stringify(state)); }} catch(e) {{}}
}}
const STATE = loadState();

function isValid(s) {{
  return s && Array.isArray(s.worlds) && s.worlds.length > 0 && s.flow_style;
}}

function render() {{
  const tb = document.getElementById('tbody');
  tb.innerHTML = '';
  DATA.forEach((r, idx) => {{
    const s = STATE[r.id] || {{ flow_style: 'feature', worlds: [], featured: false, notes: '' }};
    if (!s.flow_style) s.flow_style = 'feature';
    const tr = document.createElement('tr');
    tr.className = isValid(s) ? 'done' : (s.worlds?.length ? 'invalid' : '');
    const worldsHtml = WORLDS.map(w => `
      <label class="${{w}}">
        <input type="checkbox" data-id="${{r.id}}" data-field="world" value="${{w}}"
          ${{ (s.worlds||[]).includes(w) ? 'checked' : '' }}>
        ${{WORLD_LABELS[w] || w}}
      </label>`).join('');
    tr.innerHTML = `
      <td>${{idx+1}}</td>
      <td>
        <div class="title">${{escapeHtml(r.title)}}</div>
        <div class="excerpt">${{escapeHtml(r.excerpt || '')}}</div>
        <small style="color:#888">${{r.date || ''}}</small>
      </td>
      <td><span class="slug">${{escapeHtml(r.new_url || r.slug || '')}}</span></td>
      <td><div class="worlds">${{worldsHtml}}</div></td>
      <td>
        <select data-id="${{r.id}}" data-field="flow_style">
          {flow_options.replace('value="feature"', 'value="feature" selected')}
        </select>
      </td>
      <td style="width:90px;text-align:center">
        <input type="checkbox" data-id="${{r.id}}" data-field="featured" ${{s.featured ? 'checked' : ''}}>
      </td>
      <td><textarea data-id="${{r.id}}" data-field="notes">${{escapeHtml(s.notes || '')}}</textarea></td>
    `;
    const flowSel = tr.querySelector('select[data-field="flow_style"]');
    if (flowSel) flowSel.value = s.flow_style || 'feature';
    tb.appendChild(tr);
  }});

  tb.querySelectorAll('select, textarea, input').forEach(el => {{
    el.addEventListener('change', onFieldChange);
    if (el.tagName === 'TEXTAREA') el.addEventListener('input', onFieldChange);
  }});
  updateCounts();
  updatePreview();
}}

function onFieldChange(e) {{
  const id = e.target.dataset.id;
  const field = e.target.dataset.field;
  if (!STATE[id]) STATE[id] = {{ flow_style: 'feature', worlds: [], featured: false, notes: '' }};
  if (field === 'world') {{
    const checked = Array.from(document.querySelectorAll(`input[data-id="${{id}}"][data-field="world"]:checked`)).map(x => x.value);
    STATE[id].worlds = checked;
  }} else if (field === 'featured') {{
    STATE[id].featured = e.target.checked;
  }} else {{
    STATE[id][field] = e.target.value;
  }}
  saveState(STATE);
  render();
}}

function updateCounts() {{
  const done = DATA.filter(r => isValid(STATE[r.id])).length;
  document.getElementById('cnt-done').textContent = done;
  document.getElementById('progress-bar').style.width = `${{(done/DATA.length)*100}}%`;
}}

function buildOutput() {{
  return {{
    project: 'nimrod-bio',
    milestone: 'V200',
    matrix_version: 'v1',
    filled_by: 'team_00',
    generated_at: new Date().toISOString(),
    posts: DATA.map(r => ({{
      id: r.id,
      title: r.title,
      slug: r.slug,
      new_url: r.new_url,
      worlds: STATE[r.id]?.worlds || [],
      flow_style: STATE[r.id]?.flow_style || 'feature',
      featured: !!STATE[r.id]?.featured,
      notes: STATE[r.id]?.notes || '',
    }})),
  }};
}}

function updatePreview() {{
  document.getElementById('json-preview').textContent = JSON.stringify(buildOutput(), null, 2);
}}

function escapeHtml(s) {{
  if (s === null || s === undefined) return '';
  return String(s).replace(/[&<>"']/g, c => ({{'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}}[c]));
}}

function toast(msg) {{
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.classList.add('show');
  setTimeout(() => t.classList.remove('show'), 2200);
}}

async function copyJSON() {{
  const txt = JSON.stringify(buildOutput(), null, 2);
  await navigator.clipboard.writeText(txt);
  toast('✓ הועתק');
}}

function downloadJSON() {{
  const blob = new Blob([JSON.stringify(buildOutput(), null, 2)], {{type:'application/json'}});
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url;
  a.download = `content_tagging_decisions_${{new Date().toISOString().slice(0,10)}}.json`;
  a.click();
  URL.revokeObjectURL(url);
  toast('✓ הורד');
}}

async function loadFromClipboard() {{
  const txt = await navigator.clipboard.readText();
  const obj = JSON.parse(txt);
  const rows = obj.posts || obj.decisions || [];
  rows.forEach(d => {{
    if (d.id) {{
      STATE[d.id] = {{
        worlds: d.worlds || [],
        flow_style: d.flow_style || 'feature',
        featured: !!d.featured,
        notes: d.notes || '',
      }};
    }}
  }});
  saveState(STATE);
  render();
  toast('✓ נטען');
}}

render();
</script>
</body>
</html>
"""


def main() -> int:
    ensure_cache_dirs()
    if not RAW_DIR.exists() or not any(RAW_DIR.glob("*.json")):
        print("[ERROR] No cached raw JSON. Run fetch_prod_posts.py first.")
        return 1

    rows = build_input_rows()
    if len(rows) != 22:
        print(f"[WARN] Expected 22 posts, got {len(rows)}")

    payload = {
        "project": "nimrod-bio",
        "milestone": "V200",
        "generated_at": date.today().isoformat(),
        "posts": rows,
    }
    INPUT_PATH.write_text(json.dumps(payload, ensure_ascii=False, indent=2), encoding="utf-8")
    HTML_PATH.write_text(build_html(rows), encoding="utf-8")
    print(f"[OK] Wrote {INPUT_PATH}")
    print(f"[OK] Wrote {HTML_PATH} ({len(rows)} rows)")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
