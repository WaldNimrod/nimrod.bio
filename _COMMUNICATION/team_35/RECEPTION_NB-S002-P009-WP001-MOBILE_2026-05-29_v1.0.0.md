---
type: RECEPTION
from: team_110 (Domain Architect · Orchestrator)
to: team_35 (Design Studio)
project: nimrod-bio
milestone: V200 (pre-cutover)
subject: "Mobile Responsiveness Spec — 04-MOBILE-spec.md — closes T-02"
date: 2026-05-29
version: v1.0.0
package_version: "Nimrod.bio AOS Design System (4).zip"
status: RECEIVED · DOCUMENTED · NEW WP DEFINED
---

# Reception — team_35 Mobile Spec Delivery (Package v4)

## §1 What was received

**Package:** `Nimrod.bio  AOS Design System (4).zip`
**New file:** `_handoff/04-MOBILE-spec.md` — Mobile Responsive Spec v1.0 · 25.05.2026
**Updated file:** `_handoff/SESSION_HANDOFF.md` — T-02 now marked closed

This document closes **T-02 (Mobile screens · Stage 5)** — the last major deferred item from Stage 3.

The spec is implementation-ready: it includes exact CSS (breakpoints, media queries, logical properties), HTML structure (drawer nav, WhatsApp FAB), JS (drawer toggle), and a Definition of Done with 11 acceptance criteria.

## §2 Scope summary (17 sections)

| Section | Component | Key changes |
|---|---|---|
| §2 | Shell — Mobile Nav | Hamburger drawer (RTL) + full a11y (focus trap, aria, ESC) |
| §2 | Shell — Mobile Footer | 4-col → 2-col (tablet) → 1-col (mobile) |
| §3 | T1 World page | Grid → stack; echoes hidden; lattice reflows; bridges 1-col |
| §4 | T2 Service page | Hero stack; hero-cta full-width; WhatsApp mobile CTA; meta-strip 2-col |
| §5 | T3 Project page | Story single-col; outcomes 2→1 col; gallery 2-col; hero image 16:10 |
| §6 | T4 Post page | Aside hidden; ToC collapsible; floating share FAB; drop-cap scale |
| §7 | T5 Blog index | Flow items linearize; filter-bar horizontal scroll (no wrap) |
| §8 | T7 Home page | All grids stack; worlds 2→1; featured projects; Unless ribbon reflow |
| §9 | T8 Static | About/Heritage/Contact mobile layouts |
| §10 | WhatsApp FAB | Fixed bottom-right on all pages except /contact (with safe-area-inset) |
| §11 | Responsive images | 3 srcsets per hero (800 / 1280 / 1920px) + 15 watercolor files |
| §12 | Forms mobile UX | font-size ≥ 16px (iOS zoom), inputmode, autocomplete |
| §13 | Touch patterns | Swipe-to-close, active states, hover: hover guard |
| §14 | Performance budgets | Lighthouse mobile ≥ 90; LCP ≤ 2.5s; total weight ≤ 800KB (T7) |
| §15 | RTL logical properties | inset-inline, margin-inline, padding-inline throughout |
| §16 | Definition of Done | 11 criteria — 7 templates × 4 viewports + iOS Safari + Android |
| §17 | Package update notes | 01-PROMPT-watercolor updated: 3 sizes per illustration (5×3=15 files) |

### Breakpoints (LOCKED)
```
≤ 640px    → mobile
641–900px  → tablet
901–1100px → tablet-wide
≥ 1101px   → desktop
```

### New deliverables required from implementation (team_10 scope)
1. CSS: `@media` blocks in all 7 template CSS files + `base.css` (nav)
2. HTML: `.nav-drawer` + `.nav-backdrop` + `.nav-toggle` in `header.php`
3. HTML: `.wa-fab` in `footer.php`
4. JS: ~80 lines drawer toggle (vanilla JS) → `assets/js/nav-drawer.js`
5. PHP: `data-page` attribute on `<body>` for WA FAB suppression on contact page
6. Image srcsets: update `get_the_post_thumbnail()` calls to use `<picture>` or `wp_get_attachment_image()` with sizes attr
7. Font: add `font-display: swap` + Hebrew+Latin subset to theme enqueues

## §3 Tickets status update (from SESSION_HANDOFF.md)

| Ticket | Was | Now |
|---|---|---|
| T-02 Mobile screens | ⏳ Stage 5 deferred | ✅ CLOSED — spec in `04-MOBILE-spec.md` |
| T-03 Watercolor illustrations | ⏳ | Prompt updated: 3 resolutions required (5×3=15 files) |
| T-04 Logo family | ⏳ Stage 6 | Still open — T-07 (basket file) still blocking |
| T-05 WordPress archive | 🟡 Next | Still open |

## §4 Action taken

1. `04-MOBILE-spec.md` copied to `sources/team_35_design_package/_handoff/` (local, gitignored)
2. `SESSION_HANDOFF.md` updated with T-02 closure
3. New WP defined: **NB-S002-P009-WP002** — Mobile Responsiveness (see §5)
4. Process plan updated to include mobile as a required pre-cutover phase

## §5 New WP — NB-S002-P009-WP002 (DEFINED, NOT YET MANDATED)

| Field | Value |
|---|---|
| WP ID | NB-S002-P009-WP002 |
| Label | nimrod-bio — Mobile Responsiveness (T-02 implementation) |
| Input | `sources/team_35_design_package/_handoff/04-MOBILE-spec.md` |
| Builder | team_10 (Cursor) |
| Validator | team_190 (Codex) + team_50 (MCP browser, real device check) |
| Track | A · STANDARD |
| Gate sequence | L-GATE_SPEC (already PASS — spec is team_35 doc) → L-GATE_BUILD → L-GATE_VALIDATE |
| Start condition | P009-WP001 (design precision) ≥ L-GATE_BUILD PASS |
| Effort estimate | 3 builder-days (CSS + JS + HTML + PHP) + 1 validation day |
| Definition of Done | §16 of `04-MOBILE-spec.md` — 11 criteria |
| Pre-cutover? | YES — mandatory before P005-WP002 |

**Scope boundary (what is NOT in this WP):**
- Logo family (T-04) — still blocked on T-07
- Watercolor illustrations (T-03) — handled in P009-WP001 (team_35)
- Performance optimization beyond CSS (no build pipeline, no webpack)

---

*Received and documented by team_110 — 2026-05-29*
*Source package: `Nimrod.bio  AOS Design System (4).zip` delivered by team_35*
