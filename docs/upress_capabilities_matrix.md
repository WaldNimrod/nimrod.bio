---
type: research_note
author: team_100 (nimrodbio_arch)
date: 2026-05-25
status: v1.0-initial
sources:
  - https://www.upress.io/managed-wordpress-hosting-features/
  - https://support.upress.io/
  - https://www.upress.co.il/ (Hebrew docs not fully scrapeable via WebFetch; complete audit deferred to NB-S002-P001-WP001 when dev env is hands-on)
---

# uPress capabilities — decision matrix vs. custom plugins

## Purpose

For each common WordPress concern, decide whether to (a) rely on uPress' built-in capability, (b) install a community plugin, or (c) write custom code. Goal: keep the new site's plugin stack lean (target ≤ 8 active plugins).

## Decision matrix

| Concern | uPress native | Decision | Rationale |
|---|---|---|---|
| **Page cache** | uPress SuperCache (page + DB level) | **Use native** | Battle-tested at uPress scale, no plugin conflicts. Avoids EzCache (currently active on prod) and W3TC/WP Super Cache |
| **Object cache** | Included in SuperCache (Redis or Memcached at infra level) | **Use native** | Same |
| **CDN** | Built-in, transparent | **Use native** | No CF Worker / BunnyCDN config needed |
| **SSL on primary domain** | Free, auto-renewed | **Use native** | — |
| **SSL on dev URLs (`*.s###.upress.link`)** | Not provided (expired cert observed 2026-05-25) | **Workaround** | Use HTTP for dev; document in CLAUDE.md (done) |
| **Web Application Firewall** | uPress Web Firewall (edge) | **Use native** | Replaces Wordfence / Sucuri / iThemes Security |
| **Automatic backups** | Every few days, 30-day retention | **Use native + manual snapshot before cutover** | Replaces UpdraftPlus / BlogVault |
| **One-click manual snapshot** | Via uPress control panel | **Use for cutover safety** | Document in cutover runbook |
| **Staging environment** | Sandboxed temp domain (in use: `nimrod-bio-2026.s887.upress.link`) | **Use native** | Replaces WP Staging / Local |
| **Site duplication / publish-to-live** | Via uPress control panel | **TBD — decide at cutover** | Could use this to swap dev→prod; alternative is DB+files migration |
| **Auto WP core updates** | Yes (uPress manages) | **Use native, but verify cadence** | Set theme + ACF Pro to manual; let WP core + uPress-managed plugins auto |
| **DNS management** | Advanced DNS tools in panel | **Use native** | — |
| **Migration in** | Supports cPanel / DA / GIT / BitBucket / Duplicator | **Not needed** (we're already on uPress; rebuilding in place) | — |
| **Logging + alerts** | Error logs, alerts, reports | **Use native** | Plus configure WP_DEBUG_LOG to a project-specific path |
| **Multisite** | Supported with SSO | **Not needed** | Single site |
| **Email (transactional + boxes)** | Yes (email boxes per account) | **TBD** | Decide whether contact form uses uPress mailbox SMTP or external SendGrid/Postmark. Default: uPress SMTP via `wp-config.php` |
| **HTTP/2** | Yes | **Use native** | — |
| **PHP version** | 8.3 (verified on dev URL: `X-Powered-By: PHP/8.3.31`) | **Pin to 8.3** | Modern, matches dev |
| **WP version** | Latest stable | **6.7+** | — |

## Plugins to install (target list — final stack)

| Plugin | Purpose | Why this one |
|---|---|---|
| **ACF Pro** | CPT field UIs for service + project (matches design CPT spec exactly: repeaters for who/how/what/outcomes/gallery, conditional fields for stage variants) | Industry standard; design spec written in ACF dialect |
| **Yoast SEO** | Sitemap, meta tags, schema, breadcrumbs | Already on prod; keeps SEO continuity |
| **Custom Post Type UI** | (Optional) — only if we want admin UI for CPT management instead of code-only registration | Likely **skip** — register in theme code for version control |
| **Redirection** | Manage 301s for the URL migration matrix | Plugin-managed, exportable to .htaccess if needed |
| **WPForms Lite** or **Contact Form 7** | T8 contact form (topic chips + name + email + phone + message) | Pick CF7 (lighter, mature, RTL-friendly, no premium upsell) |
| **WP Multilingual** | NO — single language site | Not needed |
| (Possibly) **Safe SVG** | Allow SVG uploads in media library (Shell icons inline; logo when delivered) | Light plugin; decision in P002-WP001 |

**Target active plugin count:** 4–5 (vs. current 13 on prod).

## Plugins to explicitly DROP from current prod stack

| Currently active | Drop reason |
|---|---|
| All-in-One SEO | Duplicate of Yoast — currently both active (SEO conflict) |
| EzCache | Replaced by uPress SuperCache |
| Tiny Compress Images | uPress should serve WebP automatically; revisit if not |
| Types + WP-Views | Replaced by ACF Pro + native WP_Query in theme |
| Admin Menu Editor | Cosmetic only — drop |
| Duplicate Post | Re-add if Nimrod uses it editorially; default drop |
| Booter — Bots & Crawlers Manager | Web Firewall handles this |
| Google Analytics (MonsterInsights) | Move to direct GA4 tag in theme `<head>` (or skip if no analytics needed) |
| Flatsome theme | Replaced by `nimrod-bio-2026` custom theme |
| Validator Pizza | Unclear purpose — verify with Nimrod, default drop |
| WPConsent Cookies Banner | Drop unless GDPR/CCPA exposure — Israeli site, low risk; revisit |
| WP Accessibility | Our theme is built A11y-first; this is patch-over-bad-themes — drop |

## Open questions (raised to Team 00 in V200 §9)

1. ACF Pro licence available, or use Meta Box / Pods?
2. Email transactional path: uPress SMTP or external?
3. Cookie banner needed for Hebrew/Israeli market?
4. Analytics: GA4 / Plausible / none?

## Verification action in NB-S002-P001-WP001

When dev env is in hand:
- Log into uPress control panel for `nimrod-bio-2026`
- Audit pre-installed plugins (uPress may ship some by default)
- Verify SuperCache config UI
- Test "one-click backup" UI
- Test "publish to live" UI (will be used in P005-WP002)
- Document control panel screenshots in `docs/upress_control_panel_audit.md`
