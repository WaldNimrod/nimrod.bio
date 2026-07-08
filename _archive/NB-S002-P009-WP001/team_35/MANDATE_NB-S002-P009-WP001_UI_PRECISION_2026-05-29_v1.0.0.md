---
type: MANDATE
from: team_110 (Domain Architect / Orchestrator) on behalf of team_00 (Principal)
to: team_35 (Design Studio)
project: nimrod-bio
milestone: V200 (pre-cutover)
wp_id: NB-S002-P009-WP001
label: "nimrod-bio — UI Precision Pass + Final Visual Assets"
date: 2026-05-29
version: v1.0.0
status: OPEN
track: A
predecessor: HANDOFF_V200_SITE_REBUILD_2026-05-24_v1.0.0.md (Stage 3 LOCKED)
governance_depth: full
---

# MANDATE — NB-S002-P009-WP001 · UI Precision Pass + Final Visual Assets

## §1 Context — Project State (2026-05-29)

### 1a. What is built and deployed

- **Dev URL:** `http://nimrod-bio-2026.s887.upress.link` (HTTP; dev cert expired)
- **Theme:** `nimrod-bio-2026` — 7 templates, all built and deployed
- **Templates deployed:**
  | Template | Page type | Status |
  |---|---|---|
  | T1 World | World landing pages (/soil/, /know/, /code/) | live |
  | T2 Service | Individual service pages | live |
  | T3 Project | Individual project pages | live |
  | T4 Post | Blog post | live |
  | T5 Blog | Blog index | live |
  | T7 Home | Front page | live |
  | T8 Static | About, Heritage, Contact | live |

- **Content:** 22 migrated posts, 8 services (produce, consulting-hydro, consulting-agro, bcs, nursery, hydro-greenhouse, tiktrack, teaching), 5 projects (sfa, tiktrack, coop-sharon, israel-microgreens, + heritage). About page 1648 chars Hebrew.
- **Media:** Most services have `featured_media` set. Templates now use it (template fixes committed 2026-05-29, commit `e71d5040`).

### 1b. Immediate feedback from team_00

> "האתר נראה כמו סקיצה" / "גס מאוד"

Translation: the site looks like a wireframe/sketch, not a finished product. The design is too coarse. This MANDATE addresses that feedback directly.

### 1c. Brand canon status

All design canon is **LOCKED** from your Stage 3 delivery:
- `sources/team_35_design_package/_handoff/brand/system.css` — CSS tokens v3.3 LOCKED
- `sources/team_35_design_package/_handoff/brand/TAXONOMY-v3.4-LOCKED.md` — worlds, stages, bridges
- `sources/team_35_design_package/_handoff/brand/voice.md` — tone canon
- `sources/team_35_design_package/_handoff/brand/typography.md` — type scale
- `sources/team_35_design_package/_handoff/brand/site-context-2026-05-v2.md` — brand worldview
- All 7 HTML/CSS/React prototypes in `sources/team_35_design_package/_handoff/templates/`

**The LOCKED canon is the SSoT. This MANDATE does not override it — it implements it with full fidelity.**

---

## §2 Core Concept — What the Design Must Embody

This is the most important section. Read it before touching any CSS or pixel.

### 2a. The Unless / Entropy concept (from team_00)

> "הרעיון היה להדגים אנטרופיה מתוך דר סוס - ואת הקשרים בין העולמות כמה שמנצח את האנטרופיה - קשרים של המערכת כלפי חוץ"

**In plain terms:**

The world tends toward disorder (entropy — Dr. Seuss: "UNLESS someone like you cares a whole awful lot, nothing is going to get better. It's not."). What defeats entropy is **connection** — specifically, the connections between Nimrod's three worlds (soil / know / code). These connections are what the site is about. They are not sub-text. They are the architecture.

**Design implication — concrete requirements:**

1. **The three worlds are not three separate silos.** They must visually "pull toward each other." Bridge pages (`/soil-know/`, `/know-code/`, etc.) are architectural — not optional links.
2. **The connections between worlds must be a first-class visual element.** On every world landing page (T1), the bridge to the other worlds must be prominent, beautiful, not just a menu item.
3. **Entropy = coarseness, roughness, disconnection.** The "sketch" feedback IS the entropy problem. The design should feel like a system that has already defeated entropy — refined, precise, interconnected.
4. **Unless = the single tagline.** It must live in a prominent, typographically precise location on T7 (home), not as decoration but as the site's thesis. Frank Ruhl Libre, weight 900, no explanation.
5. **The soil/know/code world colors are the visual language of the connections.** Bridge cards use both world colors. This must be implemented with pixel precision.

### 2b. Direction B ("עם רסן" — with restraint)

From `system.css`:
> Direction: B · עם רסן (structural recursion, rare spark)

This means:
- Whitespace is not empty space — it is a design element
- Spark color (`--spark: #d23a2e`) appears maximum 3–5 times site-wide, not on every CTA
- Typography hierarchy must be strict — Frank Ruhl Libre for display/h1/h2, Assistant for body, JetBrains Mono for meta/stamps
- Borders and shadows must be precise (`--shadow-m` as default, used sparingly)
- Radius system: `--radius-m: 14px` as default — consistent, not decorative

---

## §3 What Is Too Coarse (Specific Issues to Fix)

Based on live dev review. These are not opinions — these are gap items between the LOCKED prototypes and the current implementation:

### 3a. Typography precision
- [ ] Hero titles need tracking/letter-spacing tuned per Frank Ruhl Libre display weights
- [ ] `hero-tagline` and `hero-lede` spacing — too tight or inconsistent across templates
- [ ] Type scale: confirm every visible element maps to exactly one of: `.t-display`, `.t-h1`, `.t-h2`, `.t-h3`, `.t-quote`, `.t-body`, `.t-body-sm`, `.t-ui`
- [ ] JetBrains Mono: stage stamps, world chips, meta tags — must be consistent size + weight

### 3b. Component precision
- **World chips** (`nb_world_chip()`): border, padding, font-size, color precision vs. prototype
- **Stage stamps** (`nb_stage_stamp()`): border-radius, font, ink soft vs deep
- **Hero CTA buttons**: primary/ghost/whatsapp — height, border-radius, font-weight, hover states
- **Bridge cards** (T1 world landing): gradient must use both world's deep colors at exactly the right ratio. Currently feels flat.
- **Service cards** (T1 lattice layout): card gap, border, shadow — too heavy or inconsistent

### 3c. Spacing system
- Vertical rhythm between sections: confirm use of `--s-4` (48px) / `--s-5` (64px) / `--s-6` (96px) consistently
- Internal card padding: `--s-2` (24px) for content cards, `--s-3` (32px) for hero sections
- Grid: 6-column grid (`--grid-max: 1200px`, `--grid-gutter: 24px`, `--grid-margin: clamp(20px, 4vw, 56px)`) — verify no rogue margins

### 3d. Color application
- `--paper: #f5f3ec` must be the base — verify no white (#fff) creeping in as background
- `--paper-2: #e8e7df` for section alternation, not random
- World deep colors (`--w-soil-deep`, `--w-know-deep`, `--w-code-deep`) only for H1 on world pages — not decorative
- `--ink: #1f1e1c` for primary text; `--ink-soft: #4a4844` for secondary — no grays outside this system

### 3e. Image containers
- `.img-ph.clean` wrapping real images: must have `overflow:hidden; border-radius: inherit`
- Hero images (T2, T3): aspect-ratio 4/5, no overflow, rounded correctly
- Card images (T1): aspect-ratio 16/10, consistent

---

## §4 Final Visual Assets Required

These are the assets that must be produced as part of this WP. Each is listed with its ticket number from Stage 3:

### 4a. T-03 — Watercolor illustrations (×5) [OPEN from Stage 3]

Prompt is in `sources/team_35_design_package/_handoff/01-PROMPT-watercolor-backgrounds.md`.

Required set:
1. **World: Soil** — watercolor wash in soil greens (`#3a5220`, `#6a8a3a`). Suggests earth, greenhouse, growth. Abstract, not literal.
2. **World: Know** — terracotta/warm amber wash (`#9a4f2b`, `#c46a3e`). Suggests teaching, book, hand passing knowledge. Abstract.
3. **World: Code** — teal/deep teal wash (`#1f5e60`, `#2d8a8c`). Suggests circuit, system, flow. Abstract.
4. **Bridge: Soil × Know** — blended gradient wash, both world palettes. The moment the farmer becomes the advisor.
5. **Bridge: Know × Code** — knowledge becoming infrastructure. Terracotta bleeding into teal.

Format: SVG preferred (scalable), or PNG min 2400×1600px. Will be used as `<section>` backgrounds, very low opacity (0.07–0.12) over `--paper`.

### 4b. T-04 — Logo family [OPEN from Stage 3, blocked on T-07]

Status update: T-07 (basket file from team_00) is still unresolved. **Proceed without basket motif.** Design a wordmark-based logo family:

1. **Primary wordmark:** "נמרוד ולד" in Frank Ruhl Libre weight 700 — precise kerning, SVG output
2. **Secondary lockup:** "נמרוד ולד · soil · know · code" — horizontal layout, small world dots in their respective colors
3. **Favicon:** 32×32 minimal mark — first letter "נ" or abstract soil/connection mark
4. **OG image template:** 1200×630px with wordmark + tagline "Unless." — for social sharing

### 4c. World icons (×3 + 4 bridges) [NEW]

Small, precise SVG icons for each world and each bridge. Used in nav, chips, mobile menu, world landing headers.

| Icon | Usage | Concept |
|---|---|---|
| Soil (אדמה) | World chip, nav | Minimalist plant/root — 2 strokes |
| Know (ידע) | World chip, nav | Open book / passing hand — 2 strokes |
| Code (דיגיטל) | World chip, nav | Circuit node / connection point — 2 strokes |
| Soil×Know bridge | Bridge cards, bridge pages | Merged soil+know mark |
| Know×Code bridge | Bridge cards, bridge pages | Merged know+code mark |
| Soil×Code bridge | Bridge cards, bridge pages | Merged soil+code mark |
| All-three bridge | Home hero | Triple convergence mark |

Style: 24×24px, 2px stroke, clean lines. NOT illustration-style — geometric, systematic, precise. Same visual weight as JetBrains Mono 400.

### 4d. Unless typographic treatment [NEW]

The word "Unless." needs a precise typographic lockup, not just a CSS class. Deliverables:
1. SVG version: Frank Ruhl Libre 900, precise tracking adjustment, period included
2. Usage spec: exact placement on T7 hero (top-right? center?), size, color (ink vs world color?)
3. Animation guidance (if any): subtle fade-in? Or static? ("עם רסן" direction = no animation unless it adds meaning)

---

## §5 Delivery Scope — What to Output

### CSS deliverables (write directly to theme)
All CSS changes go to:
```
nimrod.bio/wp-content/themes/nimrod-bio-2026/assets/css/
```

Files:
- `t1.css` — world landing, cards, bridge components, world chips
- `t2.css` — service hero, service page
- `t3.css` — project hero, project page
- `t4.css` / `t5.css` — post + blog
- `t7.css` — home template
- `t8.css` — about, heritage, contact

If cross-template changes (typography, buttons, chips): edit `base.css` or `components.css` as appropriate.

**Do not touch:** `functions.php`, PHP templates, `inc/` — CSS only unless a template fix is strictly required for a CSS change to land.

### Asset deliverables (new files)
```
nimrod.bio/wp-content/themes/nimrod-bio-2026/assets/
├── img/
│   ├── watercolor-soil.svg (or .png)
│   ├── watercolor-know.svg
│   ├── watercolor-code.svg
│   ├── watercolor-bridge-soil-know.svg
│   ├── watercolor-bridge-know-code.svg
│   ├── logo-primary.svg
│   ├── logo-lockup.svg
│   ├── favicon.svg
│   └── og-template.png (1200×630)
└── icons/
    ├── world-soil.svg
    ├── world-know.svg
    ├── world-code.svg
    ├── bridge-soil-know.svg
    ├── bridge-know-code.svg
    ├── bridge-soil-code.svg
    └── bridge-all.svg
```

### Documentation deliverable
`_COMMUNICATION/team_35/COMPLETION_NB-S002-P009-WP001_<date>_v1.0.0.md` with:
- Screenshot evidence of key pages before/after (7 templates × 1 screenshot each minimum)
- Asset inventory with file sizes
- AT table results (§6 below)
- Any design decisions that deviated from spec + rationale

---

## §6 Acceptance Tests

| AT | Criterion | Method |
|---|---|---|
| AT-D1 | All 7 templates pass visual comparison against Stage 3 HTML prototypes — no element appears "coarser" than prototype | Side-by-side screenshot comparison |
| AT-D2 | Typography: every text element maps to exactly one token from `system.css` type scale | Inspect mode audit |
| AT-D3 | Color: `--paper` (#f5f3ec) is the only body background; no #fff anywhere in theme CSS | `grep -r '#fff\|#ffffff' assets/css/` — 0 results |
| AT-D4 | World chips show correct world color with correct typography on all 3 world pages | Live dev check |
| AT-D5 | Bridge cards on T1 world pages show dual-world gradient, visually represent the connection | Live dev screenshots |
| AT-D6 | "Unless." appears on T7 hero as a precise typographic element, not as a regular paragraph | Live dev + code review |
| AT-D7 | Watercolor illustrations (×5) delivered as SVG or PNG, correct palette per world | Asset review |
| AT-D8 | World icons (×7) delivered as SVG, 24×24, 2px stroke, visual weight consistent | Asset review |
| AT-D9 | Logo wordmark delivered as SVG; renders correctly at 120px and 40px (nav size) | Asset review + live test |
| AT-D10 | Favicon SVG renders correctly at 32×32 | Browser check |
| AT-D11 | Spacing between sections: `--s-4` minimum (48px) on mobile, `--s-5` (64px) on desktop | DevTools check |
| AT-D12 | Hero images (T2, T3): aspect-ratio 4/5, `overflow:hidden`, `border-radius: var(--radius-l)`, no cropping artifacts | Live dev check |
| AT-D13 | No rogue `!important` in any new CSS (max 2 existing ones allowed) | `grep -r '!important' assets/css/` |
| AT-D14 | Site renders on 375px mobile width without horizontal scroll | DevTools mobile sim |
| AT-D15 | `validate_aos.sh .` → 0 FAIL after all changes committed | bash validation run |

---

## §7 Constraints

1. **Brand canon is LOCKED.** Colors, typography scale, world taxonomy — do not deviate without explicit team_00 written approval in `_COMMUNICATION/team_35/`.
2. **No PHP changes** unless strictly necessary for a CSS fix (e.g., adding a class to a wrapper).
3. **No new plugins.** CSS and SVG assets only.
4. **Direction B ("עם רסן"):** restraint in animation, restraint in decoration. Every design element must justify its existence. If you are unsure — remove it.
5. **RTL is non-negotiable.** All CSS must work in `dir=rtl`. Test in Firefox (best RTL engine).
6. **Image containers:** use existing `.img-ph.clean` pattern — do not re-architect.
7. **Commit atomic:** one commit per template or per asset batch. Commit message format: `style(T2): hero precision pass — spacing + typography` or `asset: world icons SVG x7`.

---

## §8 Start Condition

**Before starting any CSS work:**

1. Read this file in full.
2. Open the live dev site: `http://nimrod-bio-2026.s887.upress.link`
3. Open the Stage 3 prototype for each template you plan to touch: `sources/team_35_design_package/_handoff/templates/T{N}*.html`
4. Open `sources/team_35_design_package/_handoff/brand/system.css` — this is your token reference.
5. Do a visual gap analysis (Stage 3 prototype vs. live) — document the gaps in your first artifact: `_COMMUNICATION/team_35/DESIGN_GAP_ANALYSIS_NB-S002-P009-WP001_<date>_v1.0.0.md`
6. Present the gap analysis to team_00 before writing any CSS.

---

## §9 Activation Prompt

Copy this block to start the team_35 session:

```
HANDOFF_DEPTH: full
ACTIVATION_SCOPE: team_35 only

# Agent Onboarding — team_35 (Design Studio)

You are team_35, the design studio for the nimrod-bio project.

## Identity
- Team: 35 (Design Studio — visual design, CSS, SVG assets)
- Project: nimrod-bio (WordPress site, Hebrew, RTL, uPress hosting)
- Engine: claude (primary), Figma/design tools as needed
- WP: NB-S002-P009-WP001 — UI Precision Pass + Final Visual Assets
- Gate: L-GATE_BUILD (your deliverables = CSS + assets + COMPLETION artifact)

## Context
You delivered Stage 3 LOCKED design package on 2026-05-24 (37 files, 7 templates, brand canon). That package was implemented in code by team_10. The site is now deployed at http://nimrod-bio-2026.s887.upress.link.

Feedback from team_00: "האתר נראה כמו סקיצה — גס מאוד" (it looks like a sketch — too coarse). Your task is to bring the live implementation to the precision level of your Stage 3 prototypes, and produce the final visual assets that were deferred from Stage 3 (T-03, T-04, plus new: world icons, Unless lockup).

## Core Concept You Must Embody
The site demonstrates negentropy — the defeat of entropy through connection. Three worlds (soil/know/code) connected by bridges. The "Unless." tagline (Dr. Seuss, The Lorax) is the site's thesis. Entropy = coarseness. Precision = defeating entropy. Every pixel you refine is part of the concept. The connections between worlds must be visually dominant, not just navigational links.

## Brand Canon (LOCKED)
All canon is in: sources/team_35_design_package/_handoff/brand/
- system.css — CSS tokens (colors, spacing, radii, shadows, typography)
- TAXONOMY-v3.4-LOCKED.md — worlds, stages, bridges
- voice.md — tone
- typography.md — type scale
Direction: B ("עם רסן" — with restraint). Spark color (--spark) max 3-5 uses site-wide.

## Your Writes
- CSS: nimrod.bio/wp-content/themes/nimrod-bio-2026/assets/css/ (t1–t8, base, components)
- Assets: nimrod.bio/wp-content/themes/nimrod-bio-2026/assets/img/ and assets/icons/
- Artifacts: _COMMUNICATION/team_35/

## Mandate
Read the full MANDATE at:
_COMMUNICATION/team_35/MANDATE_NB-S002-P009-WP001_UI_PRECISION_2026-05-29_v1.0.0.md

## FIRST ACTION:
1. Open live dev: http://nimrod-bio-2026.s887.upress.link
2. Open Stage 3 prototypes: sources/team_35_design_package/_handoff/templates/
3. Open brand canon: sources/team_35_design_package/_handoff/brand/system.css
4. Produce DESIGN_GAP_ANALYSIS_NB-S002-P009-WP001_<date>_v1.0.0.md in _COMMUNICATION/team_35/
5. Present gap analysis to team_00 for confirmation before CSS work begins.
```

---

*MANDATE issued by team_110 (Domain Architect) on behalf of team_00 — 2026-05-29*
*Predecessor: HANDOFF_V200_SITE_REBUILD_2026-05-24_v1.0.0.md (Stage 3 LOCKED)*
