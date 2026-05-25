# CUTOVER READINESS REPORT — NB-S002-P005-WP001

- **Date:** 2026-05-25
- **Team:** team_10 (nimrodbio_build)
- **Spec (SSOT):** `_aos/work_packages/NB-S002-P005-WP001/LOD400_NB-S002-P005-WP001.md`
- **Mandate:** `_COMMUNICATION/team_10/MANDATE_NB-S002-P005-WP001_v1.0.0.md`
- **Environment:** dev uPress (`set -a; source .env.upress.dev; set +a`)

## Scope and guardrails

This QA sweep covers cutover-readiness checks only (responsive, Lighthouse, RTL, axe-core, form/SMTP path, redirects, broken links, visual screenshots, perf baseline).  
No production/content edits were made. Defects that require content or theme changes are documented for V300/fix-cycle routing.

Golden-rules mapping:
- No content changes: respected.
- No production code (beyond QA scripts): respected.
- `.btn-primary` contrast waiver: carried as explicit waiver.
- TBC markers: left intact (no edits).
- Expired HTTPS cert: all relevant checks used ignore-cert strategy.
- SMTP delivery: ⟳ **RETRACTED V300 deferral 2026-05-25** — SMTP scope-expanded via DECISION_V200_SMTP and resolved in fix cycle 1.1 (see addendum at bottom of this report).

## Executed checks and evidence

| Check | Result | Evidence |
|---|---|---|
| Responsive probes (7 templates x 4 viewports = 28) | **PASS** (28/28) | `docs/qa_responsive_matrix_2026-05-25.json` |
| Lighthouse (8 URLs) | **PARTIAL** | `docs/qa_lighthouse_results_2026-05-25.json` |
| RTL bidi audit (Chrome/Firefox/WebKit, 5 URLs) | **PASS** for RTL directionality | `docs/qa_rtl_bidi_audit_2026-05-25.json`, `docs/qa_rtl_bidi_audit_2026-05-25.md` |
| axe-core scan (7 template reps) | **PASS (blocking threshold)** | `docs/qa_a11y_axe_results_2026-05-25.json` |
| Form submit path / validation / honeypot route + SMTP inbox arrival | **PASS** (cycle 1.1) | `docs/qa_form_smtp_test_2026-05-25.md` |
| Redirect verification (23x301 + 6x410 + 2x200) | **PASS** | `docs/qa_redirect_verification_2026-05-25.json` |
| Broken-link crawl | **FAIL (1 item)** | `docs/qa_broken_links_2026-05-25.json` |
| Visual snapshot pack | **CAPTURED** | `docs/qa_visual_screenshots_2026-05-25/` |
| Perf baseline snapshot | **PRESENT** | `docs/perf_baseline_dev_2026-05-25.json` |

## Key metrics snapshot

- Responsive: 28/28 pass with real scrollability check.
- Lighthouse averages: Perf **87.62**, A11y **92.38**, BP **93.25**, SEO **67.5**.
- Lighthouse minimums: Perf **86**, A11y **88**, BP **73**, SEO **66**.
- axe-core: **0 serious/critical**, 14 moderate findings (`landmark-one-main`, `region` repeated).
- Redirect matrix: redirects **23/23**, drops **6/6**, keeps **2/2**.
- Broken links: **1** unresolved internal URL (`/blog/back-to-mud/` -> 404).
- Perf baseline (TTFB): `/` 260.36ms, `/blog/` 266.07ms, `/services/produce/` 258.16ms.

## Findings by severity

### High

None.

### Medium

1. **Broken internal link (content-level):**
   - URL: `https://nimrod-bio-2026.s887.upress.link/blog/back-to-mud/`
   - Status: 404
   - Impact: user can reach dead link if referenced in-site.
   - Rule mapping: no content edits in V200; route to V300 backlog/fix cycle.

2. **Lighthouse target misses on selected URLs:**
   - A11y target (>=95) not met on multiple URLs (range 88-94).
   - Best Practices target (>=90) misses on two post URLs (73).
   - Impact: quality debt; not currently a hard functional blocker.

### Low

1. **axe moderate-only issues repeated across templates:**
   - `landmark-one-main`, `region` (no serious/critical issues).
   - Impact: semantic/a11y polish.

2. **Form anti-spam path ambiguity:**
   - Honeypot submission returns `?status=ok`.
   - Without mailbox verification, cannot prove whether mail is suppressed or still sent.
   - Route: V300 follow-up with mailbox evidence.

## Waivers and deferred items (explicit)

1. **Waiver (default, approved in mandate):** `.btn-primary` contrast ~3.83:1 (locked design token).  
2. ~~**Deferred (allowed by mandate):** SMTP deliverability verification to mailbox/inbox operations if not fully provable in this pass.~~ **RETRACTED 2026-05-25** — SMTP closed in fix cycle 1.1; PASS evidence in `qa_form_smtp_test_2026-05-25.md` cycle 1.1 section.  
3. **Deferred (directive):** TBC markers remain visible on about/heritage; no content updates in V200.  
4. **Deferred (environmental):** Lighthouse SEO on dev remains capped by dev `noindex` behavior; re-verify on cutover/prod URL.

## Risk assessment

- **Launch-blocking functional risk:** low (core rendering, redirects, form submit path, and baseline perf are stable).
- **Quality/compliance risk:** medium (Lighthouse A11y/BP misses, one broken internal link, moderate semantic a11y debt).
- **Operational risk:** medium-low (SMTP inbox evidence not finalized in this QA sweep; allowed defer to V300).

## Recommendation

**Final signature: CONDITIONAL GO** (unchanged after cycle 1.1)

Rationale:
- Mandatory cutover-critical mechanics passed (routing matrix, representative responsive behavior, page availability, perf baseline capture).
- SMTP inbox arrival now PASS (cycle 1.1) — one deferral retracted.
- Remaining issues are non-critical quality/content debt and approved defer/waiver items, with no hard blocker requiring V200 content/code mutation.
- Cutover may proceed if V300 backlog explicitly tracks: broken link remediation, Lighthouse uplift (A11y/BP).

---

## Cycle 1.1 addendum — SMTP scope expansion CLOSED (2026-05-25)

team_00 directive 2026-05-25 retracted SMTP V300-deferral. team_10 + team_100 resolved in fix cycle 1.1 with these changes:

1. `wp-mail-smtp/wp_mail_smtp` plugin installed + active on dev (`POST /wp/v2/plugins`).
2. SMTP configured via plugin UI by team_00:
   - Host `smtp.inbox.co.il` : 587 TLS
   - Auth `agent@nimrod.bio` with rotated password (post-SECURITY_INCIDENT_2026-05-25)
   - From `n@nimrod.bio` (uPress mailbox forwarding to `nimrod@mezoo.co`)
3. WP `admin_email` updated `admin@meoo.co` → `nimrod@mezoo.co` via REST `/wp/v2/settings`.
4. A12 inbox-arrival evidence: real contact-form POST (10-char nonce, valid payload) → 302 `?status=ok` → team_00 confirmed receipt in `nimrod@mezoo.co` ("הגיע פיקס").

Cycle 1.1 surface:
- No theme code edits (no MU plugin written; setting + plugin install only)
- `.env.upress.dev` block 10 reflects routing identities (no secrets)
- `WP_ADMIN_EMAIL=` synced to current DB value
- Plugin DB option holds the rotated password; gitignored

Outstanding items still routed to V300 (unchanged by cycle 1.1):
- Broken link `/blog/back-to-mud/`
- Lighthouse A11y uplift (88-94 → ≥95)
- Lighthouse BP uplift on 2 post URLs (73 → ≥90)
- SPF/DKIM polish if production spam-folder issues observed post-cutover
