/**
 * owner-supply.js — טופס אספקה לנמרוד · localStorage + JSON export
 */
(function () {
  const STORAGE_KEY = 'nimrod-bio-owner-supply:v1';
  const DEFAULTS_KEY = 'nimrod-bio-owner-supply-defaults-applied:v1';

  /** ערכי ברירת מחדל מהחלטות נעולות — רק בשדות ריקים בביקור ראשון */
  const LOCKED_DEFAULTS = {
    'supply.wave02.garden_slug': 'nimrodsgarden',
    'supply.wave03.mezoo': 'B',
    'supply.press.display_mode': 'skeleton',
  };

  function load() {
    try {
      return JSON.parse(localStorage.getItem(STORAGE_KEY) || '{}');
    } catch {
      return {};
    }
  }

  function save(data) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
    flashSaved();
    updateProgress();
  }

  function flashSaved() {
    const el = document.getElementById('save-hint');
    if (!el) return;
    el.classList.add('show');
    clearTimeout(flashSaved._t);
    flashSaved._t = setTimeout(() => el.classList.remove('show'), 1200);
  }

  function fieldValue(el) {
    if (el.type === 'checkbox') return el.checked;
    if (el.type === 'radio') return el.checked ? el.value : null;
    return el.value;
  }

  function collectField(el, all) {
    const id = el.dataset.fieldId;
    if (!id) return;
    if (el.type === 'radio') {
      if (el.checked) all[id] = el.value;
      return;
    }
    all[id] = fieldValue(el);
  }

  function persistFromDom() {
    const all = load();
    document.querySelectorAll('[data-field-id]').forEach((el) => {
      if (el.type === 'radio' && !el.checked) return;
      collectField(el, all);
    });
    save(all);
  }

  function restoreToDom() {
    const all = load();
    document.querySelectorAll('[data-field-id]').forEach((el) => {
      const id = el.dataset.fieldId;
      if (all[id] === undefined) return;
      if (el.type === 'checkbox') {
        el.checked = !!all[id];
      } else if (el.type === 'radio') {
        el.checked = el.value === all[id];
      } else {
        el.value = all[id];
      }
      markFilled(el);
    });
    updateProgress();
  }

  function markFilled(el) {
    const filled =
      el.type === 'checkbox'
        ? el.checked
        : el.type === 'radio'
          ? el.checked
          : String(el.value || '').trim().length > 0;
    el.classList.toggle('filled', filled && el.tagName !== 'INPUT');
    if (el.tagName === 'TEXTAREA' || (el.tagName === 'INPUT' && el.type !== 'checkbox' && el.type !== 'radio')) {
      el.classList.toggle('filled', String(el.value || '').trim().length > 0);
    }
  }

  function updateProgress() {
    const fields = [...document.querySelectorAll('[data-field-id]')].filter(
      (el) => el.type !== 'radio' || el.checked
    );
    const radioNames = new Set();
    const countable = fields.filter((el) => {
      if (el.type === 'radio') {
        const n = el.name;
        if (radioNames.has(n)) return false;
        radioNames.add(n);
        return document.querySelector(`input[name="${n}"]:checked`);
      }
      return true;
    });

    let done = 0;
    countable.forEach((el) => {
      if (el.type === 'checkbox' && el.checked) done++;
      else if (el.type === 'radio') done++;
      else if (String(el.value || '').trim()) done++;
    });

    const pct = countable.length ? Math.round((done / countable.length) * 100) : 0;
    const bar = document.getElementById('progress-fill');
    const label = document.getElementById('progress-label');
    if (bar) bar.style.width = pct + '%';
    if (label) label.textContent = pct + '% מולא';
  }

  function buildExport() {
    persistFromDom();
    const values = load();
    const sections = [];

    document.querySelectorAll('.supply-section[data-section-id]').forEach((sec) => {
      const sectionId = sec.dataset.sectionId;
      const title = sec.querySelector('h2')?.textContent?.trim() || sectionId;
      const fields = [];
      const seen = new Set();

      sec.querySelectorAll('[data-field-id]').forEach((el) => {
        const fid = el.dataset.fieldId;
        if (seen.has(fid)) return;
        if (el.type === 'radio') {
          const checked = sec.querySelector(`[data-field-id="${fid}"]:checked`);
          if (!checked && !values[fid]) return;
          seen.add(fid);
          fields.push({
            field_id: fid,
            label: el.closest('.field-group')?.querySelector('.field-label')?.textContent?.trim() || fid,
            value: values[fid] ?? (checked ? checked.value : ''),
          });
          return;
        }
        seen.add(fid);
        fields.push({
          field_id: fid,
          label: el.closest('.field-group')?.querySelector('.field-label')?.textContent?.trim() ||
            el.closest('.checklist-item')?.querySelector('.item-title')?.textContent?.trim() ||
            fid,
          value: values[fid] ?? fieldValue(el),
        });
      });

      sections.push({
        section_id: sectionId,
        section_title: title,
        fields: fields.filter((f) => {
          const v = f.value;
          if (typeof v === 'boolean') return v;
          return String(v ?? '').trim().length > 0;
        }),
      });
    });

    return {
      export_type: 'nimrod_bio_owner_supply',
      version: '1',
      exported_at: new Date().toISOString(),
      instruction: 'הדבק JSON זה בצ׳אט team_100 — אספקת חומרים חסרים לכתיבה והטמעה.',
      entry_point: 'content-drafts/owner-supply/index.html',
      sections: sections,
      values_flat: values,
      filled_count: Object.values(values).filter((v) =>
        typeof v === 'boolean' ? v : String(v ?? '').trim().length > 0
      ).length,
    };
  }

  async function copyJson() {
    const payload = buildExport();
    const json = JSON.stringify(payload, null, 2);
    try {
      await navigator.clipboard.writeText(json);
      const el = document.getElementById('export-status');
      if (el) {
        el.textContent = 'הועתק ללוח ✓';
        el.classList.add('show');
        setTimeout(() => el.classList.remove('show'), 2500);
      }
    } catch {
      prompt('העתק ידנית:', json);
    }
  }

  function clearAll() {
    if (!confirm('למחוק את כל מה שמולא בטופס? (לא ניתן לבטל)')) return;
    localStorage.removeItem(STORAGE_KEY);
    document.querySelectorAll('[data-field-id]').forEach((el) => {
      if (el.type === 'checkbox' || el.type === 'radio') el.checked = false;
      else el.value = '';
      el.classList.remove('filled');
    });
    updateProgress();
  }

  function applyLockedDefaults() {
    if (localStorage.getItem(DEFAULTS_KEY)) return;
    const all = load();
    let changed = false;
    Object.entries(LOCKED_DEFAULTS).forEach(([fid, val]) => {
      if (all[fid] !== undefined && String(all[fid]).trim()) return;
      all[fid] = val;
      changed = true;
    });
    if (changed) save(all);
    localStorage.setItem(DEFAULTS_KEY, '1');
  }

  document.addEventListener('DOMContentLoaded', () => {
    applyLockedDefaults();
    restoreToDom();

    document.querySelectorAll('[data-field-id]').forEach((el) => {
      const ev = el.type === 'checkbox' || el.type === 'radio' ? 'change' : 'input';
      el.addEventListener(ev, () => {
        persistFromDom();
        markFilled(el);
      });
    });

    document.getElementById('btn-export-json')?.addEventListener('click', copyJson);
    document.getElementById('btn-clear')?.addEventListener('click', clearAll);

    document.querySelectorAll('.supply-toc a').forEach((a) => {
      a.addEventListener('click', (e) => {
        e.preventDefault();
        document.querySelector(a.getAttribute('href'))?.scrollIntoView({ behavior: 'smooth' });
      });
    });
  });
})();
