---
type: MANDATE
from: team_100 (nimrodbio_arch)
to: team_10 (nimrodbio_build — Cursor)
wp_id: NB-S002-P005-WP001
project: nimrod-bio
milestone: V200
program: P005
date: 2026-05-25
gate: L-GATE_SPEC PASS → L-GATE_BUILD
track: A · STANDARD
priority: HIGH
predecessor: NB-S002-P004-WP002 (COMPLETE)
successor: NB-S002-P005-WP002 (cutover)
spec_ref: _aos/work_packages/NB-S002-P005-WP001/LOD400_NB-S002-P005-WP001.md
strategic_note: "team_00 directive 2026-05-25 — V200 closes with migrated content only. New content → V300."
---

# MANDATE — NB-S002-P005-WP001 — QA pass

**לצוות 10 (Cursor):**

לפני האחרון. 12/13 WPs נסגרו. עכשיו ה-QA המקיף — לוודא שהמערכת מוכנה ל-cutover.

## הקשר

- 22 פוסטים live על dev, 7 templates עובדים, 23 301s + 6 410s אכופים
- כל ה-deferrals שהצטברו ב-V200 (mobile, SMTP, a11y, RTL, computed-style probes) — מסוכמים ב-LOD400 §2
- **לא תוכן חדש** — team_00 הורה: התוכן החדש ל-V300 אחרי launch

## המפרט המלא

🎯 **`_aos/work_packages/NB-S002-P005-WP001/LOD400_NB-S002-P005-WP001.md`**

- §3 — QA sweep scope: 9 sections (responsive × 28, Lighthouse × 8, RTL × 3 browsers, axe-core, form/SMTP, redirects, broken-links, visual regression, perf baseline)
- §4 — deliverables: 10 docs + 5 scripts + COMPLETION
- §5 — Acceptance: **CUTOVER_READINESS_REPORT** — GO / CONDITIONAL GO / NO-GO

## כללי-זהב

1. **לא לגעת בתוכן** — אם בדיקה מגלה defect ב-CPT, פתח ticket ל-V300, אל תקן
2. **לא לכתוב קוד מעבר ל-QA scripts** — אם נמצא bug, פתח רשימה ל-V300 או fix-cycle נפרד
3. **`.btn-primary` contrast 3.83:1** — דיפולט WAIVER (color נעול ע״י team_35). אל תשנה token
4. **TBC markers ב-about/heritage** — להישאר. לא תוכן חדש
5. **HTTPS עם cert expired** — `--ignore-certificate-errors` בכל הtools
6. **SMTP fail = DEFER ל-V300** — לא חוסם, ה-form submission עצמו עובד
7. **CUTOVER_READINESS_REPORT** הוא המסמך החשוב — הפוקוס הוא איכותו, לא הכמות

## Activation flow

```bash
set -a; source .env.upress.dev; set +a

# Phase A — responsive (28 probes)
python3 scripts/qa/responsive_probe.py
# → docs/qa_responsive_matrix_2026-05-25.json

# Phase B — Lighthouse (8 URLs)
python3 scripts/qa/lighthouse_batch.py
# → docs/qa_lighthouse_results_2026-05-25.json

# Phase C — axe-core a11y
python3 scripts/qa/axe_runner.py
# → docs/qa_a11y_axe_results_2026-05-25.json

# Phase D — RTL bidi (Chrome/Safari/Firefox manual)
# document in docs/qa_rtl_bidi_audit_2026-05-25.md

# Phase E — form + SMTP
# manual submit + check inbox
# document in docs/qa_form_smtp_test_2026-05-25.md

# Phase F — redirect re-verify
python3 scripts/redirects/verify_redirects.py
# → docs/qa_redirect_verification_2026-05-25.json

# Phase G — broken-link crawl
python3 scripts/qa/crawl_links.py
# → docs/qa_broken_links_2026-05-25.json

# Phase H — visual screenshots (Selenium/Puppeteer)
# → docs/qa_visual_screenshots_2026-05-25/

# Phase I — perf baseline
python3 scripts/qa/perf_snapshot.py
# → docs/perf_baseline_dev_2026-05-25.json

# Synthesize all → CUTOVER_READINESS_REPORT_2026-05-25.md
```

## Exit criteria

ב-COMPLETION:
- [ ] 5 scripts ב-`scripts/qa/` tracked
- [ ] 9 docs in `docs/qa_*` כולם עם evidence
- [ ] `CUTOVER_READINESS_REPORT_2026-05-25.md` חתום עם GO / CONDITIONAL GO / NO-GO
- [ ] רשימת אישורי deferrals to V300 (SMTP, TBCs, .btn-primary contrast)
- [ ] `validate_aos.sh` 0 net-new FAILs
- [ ] git push לפני COMPLETION

## תזמון

- Start: מיד
- Target: 3 ימי עבודה
- VALIDATE: cross-engine team_190 — אם REPORT הוא GO → unblock P005-WP002

## מהותית

זה ה-pre-launch QA. אם משהו רציני נמצא — fix-cycle. אם הכל פחות-מ-מצוין-אבל-יציב — CONDITIONAL GO ועוברים ל-cutover. אם blocker אמיתי — NO-GO, עוצרים, מנפיק MANDATE_FIX, חוזרים.

— team_100 (nimrod-bio) — 2026-05-25
