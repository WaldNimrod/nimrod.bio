/**
 * wave-review.js — per-section owner notes + JSON export for nimrod.bio content waves
 */
(function () {
  const cfg = window.WAVE_REVIEW_CONFIG || {};
  const waveId = cfg.waveId || 'wave-unknown';
  const pageId = cfg.pageId || 'unknown';
  const pageUrl = cfg.pageUrl || '';
  const storageKey = `nimrod-bio-wave-review:${waveId}`;

  function loadAll() {
    try {
      return JSON.parse(localStorage.getItem(storageKey) || '{}');
    } catch {
      return {};
    }
  }

  function saveNote(sectionId, value) {
    const all = loadAll();
    all[sectionId] = {
      note: value,
      updated_at: new Date().toISOString(),
      page_id: pageId,
      page_url: pageUrl,
    };
    localStorage.setItem(storageKey, JSON.stringify(all));
  }

  function initNotes() {
    const all = loadAll();
    document.querySelectorAll('.review-note[data-section-id]').forEach((ta) => {
      const id = ta.dataset.sectionId;
      if (all[id]?.note) ta.value = all[id].note;
      ta.addEventListener('input', () => saveNote(id, ta.value));
    });
  }

  function collectPageSections() {
    const sections = [];
    document.querySelectorAll('.copy-section[data-section-id]').forEach((sec) => {
      const sectionId = sec.dataset.sectionId;
      const label = sec.querySelector('h2')?.textContent?.trim() || sectionId;
      const copyEl = sec.querySelector('.copy-block');
      const copyText = copyEl ? copyEl.innerText.trim() : '';
      const noteEl = sec.querySelector('.review-note[data-section-id="' + sectionId + '"]');
      const note = noteEl ? noteEl.value.trim() : (loadAll()[sectionId]?.note || '');
      sections.push({
        section_id: sectionId,
        section_label: label,
        copy_preview: copyText.slice(0, 500),
        owner_note: note,
      });
    });
    return sections;
  }

  function buildExportPayload() {
    const all = loadAll();
    const pagesMap = {};

    // Current page from DOM
    const currentPage = {
      page_id: pageId,
      page_url: pageUrl,
      sections: collectPageSections(),
    };
    pagesMap[pageId] = currentPage;

    // Other pages from localStorage only (notes entered on other tabs)
    Object.entries(all).forEach(([sectionId, meta]) => {
      const pid = sectionId.split('.')[1] || 'unknown';
      if (!pagesMap[pid]) {
        pagesMap[pid] = {
          page_id: pid,
          page_url: meta.page_url || `/services/${pid}/`,
          sections: [],
        };
      }
      const exists = pagesMap[pid].sections.some((s) => s.section_id === sectionId);
      if (!exists && meta.note) {
        pagesMap[pid].sections.push({
          section_id: sectionId,
          section_label: sectionId,
          copy_preview: '',
          owner_note: meta.note,
        });
      }
    });

    return {
      export_type: 'nimrod_bio_wave_review',
      wave_id: waveId,
      wave_label: cfg.waveLabel || waveId,
      exported_at: new Date().toISOString(),
      exporter_page: pageId,
      instruction:
        'הדבק JSON זה בצ׳אט team_100. כל section_id מזהה בדיוק סקשן בעמוד.',
      pages: Object.values(pagesMap).sort((a, b) =>
        a.page_url.localeCompare(b.page_url)
      ),
      notes_only_count: Object.values(all).filter((m) => m.note?.trim()).length,
    };
  }

  async function copyJson() {
    const payload = buildExportPayload();
    // Enrich from manifest if present (all wave sections + notes)
    try {
      const res = await fetch('wave-manifest.json');
      if (res.ok) {
        const manifest = await res.json();
        const all = loadAll();
        const byPage = {};
        (manifest.sections || []).forEach((sid) => {
          const parts = sid.split('.');
          const pid = parts[1] || 'unknown';
          if (!byPage[pid]) {
            const pg = (manifest.pages || []).find((p) => p.page_id === pid);
            byPage[pid] = {
              page_id: pid,
              page_url: pg?.page_url || '',
              sections: [],
            };
          }
          const domSec = document.querySelector(
            '.copy-section[data-section-id="' + sid + '"]'
          );
          const copyText = domSec
            ? (domSec.querySelector('.copy-block')?.innerText.trim() || '')
            : '';
          byPage[pid].sections.push({
            section_id: sid,
            section_label:
              domSec?.querySelector('h2')?.textContent?.trim() || sid,
            copy_preview: copyText.slice(0, 500),
            owner_note: (all[sid]?.note || '').trim(),
          });
        });
        payload.pages = Object.values(byPage).filter((p) => p.page_id !== 'index' || byPage.index.sections.some((s) => s.owner_note));
        payload.manifest_sections = manifest.sections.length;
      }
    } catch (_) {
      /* file:// or offline — use DOM-only payload */
    }
    const json = JSON.stringify(payload, null, 2);
    try {
      await navigator.clipboard.writeText(json);
      const el = document.getElementById('export-status');
      if (el) {
        el.textContent = 'הועתק ללוח ✓';
        el.classList.add('show');
        setTimeout(() => el.classList.remove('show'), 2500);
      }
    } catch (e) {
      prompt('העתק ידנית:', json);
    }
  }

  function highlightNav() {
    const here = location.pathname.split('/').pop() || 'index.html';
    document.querySelectorAll('.wave-nav a[data-page]').forEach((a) => {
      a.classList.toggle('is-active', a.dataset.page === here);
    });
  }

  document.addEventListener('DOMContentLoaded', () => {
    initNotes();
    highlightNav();
    const btn = document.getElementById('btn-export-json');
    if (btn) btn.addEventListener('click', copyJson);
  });
})();
