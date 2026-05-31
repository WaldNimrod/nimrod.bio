---
type: UI_DESIGN_REVIEW
from: team_35 (Site Design + Build — Claude Design)
to: team_100 → Claude Code (team_110) for implementation
project: nimrod.bio
milestone: V200 (post-text, pre-cutover)
date: 2026-06-01
version: v1.0.0
scope: DESIGN LAYER ONLY — interface, typography, spacing, templates. Page text is final & locked.
basis: theme source (themes/nimrod-bio-2026 — system/shell/t1–t8 css + template-parts), brand canon (system.css v3.3), COMPLETION_SITE_DELIVERY_V3_FULL, the two super-locks
companion: Precision Mockup v4.html (visual SSoT for every finding below)
---

# UI / DESIGN REVIEW — V200

> This is a **design review + handoff**, not a deploy. team_35 owns the interface/design
> verdict; implementation lands with Claude Code via the README in this folder. Every
> finding below is resolved visually in `Precision Mockup v4.html` — read a finding,
> open the matching screen, lift the spec.

## §0 · Method & posture
Reviewed against the locked canon and the **current theme source** (CSS + PHP template-parts), not a live crawl. Two lenses: **(a) precision** — does the rendered system hold the resolution the prototypes defined; **(b) completeness** — are the templates the build needs actually present and graceful in every state. Severity: ● critical · ◐ high · ○ medium.

The thesis still governs the bar: the site argues that connection defeats dispersion, so **coarseness is an argument failure, not only a cosmetic one**. Precision is the deliverable.

---

## §1 · System-level findings

| ID | Sev | Finding | Resolution (in v4) |
|----|-----|---------|--------------------|
| S-01 | ● | **Lock leakage in surfaced UI.** Hero kicker (`3×`, `4 חממות`, `קומון`), world-page principle block (`CDIP · cross-domain isomorphism`, `אינסטנסים`, `זה ה־3×`), concept caption (`נֶגֶנְטְרוֹפְּיָה`), manifesto (`אנטרופיה`), and `קואופרטיב`/`recursion` tags all still **name** what must only be **demonstrated**. | Rewritten per Fixes A–E + About §03. Lock-scan = 0, incl. alt/aria and the internal `.vc-cdip`→`.vc-principle` class. |
| S-02 | ◐ | **Empty-state coverage is thin.** 404 is a 3-line stub; no designed search-results, no empty-archive. A site whose worlds may launch with 0 posts will render broken-looking gaps. | Full `404` / `search` / empty-archive templates on the **System** screen. |
| S-03 | ◐ | **Media slots fail hard, not soft.** Image placeholders surface `data-cap="TBD …"` to visitors; cards with no thumbnail risk empty boxes. | `.ph.clean` degradation (world-tinted wash + quiet emblem, no visitor-facing "TBD"); pending caption is dev-only; `.collapse` option. Spec on **States** screen. |
| S-04 | ○ | **Count noise.** World cards render `0 פעילויות` when unwired — a literal "zero" reads as "nothing here". | `.more` count with 0/1/many wording (`בקרוב` / `פעילות אחת` / `N פעילויות`). |
| S-05 | ○ | **External destinations hardcoded & unmarked.** SFA/TikTrack URLs live in code with no affordance telling the visitor they leave the site. | `.ext-link` (out-arrow + domain), driven by `_nb_external_url`/`_nb_external_label`; hides when absent. |
| S-06 | ○ | **Unverified facts in the UI.** World lattice showed `מסעדות 12`; About fact-fixes confirm **one** restaurant today + greenhouse **420 מ״ר**. | Facts reconciled to owner-verified numbers across hero/lattice/About. |

## §2 · Per-template verdict

- **T7 Home** — Strong composition; the fixes above are the gating issues. Bridges/Unless/concept-graphic all hold. Keep the spark toggle. ✔ after S-01/S-04/S-05/S-06.
- **T1 World (soil/know/code)** — Lattice + seam bridges are the best-resolved part of the site; only the principle block (S-01) and facts (S-06) needed correction. Verify the strata washes actually render (canon bug G-01: `--*-wash` tokens must exist at the top of `t1.css`).
- **T8 Contact** — Form + side panel are solid in source; **missing a hero** that leads with the two approved primary actions. Added `.hero-act` (WhatsApp primary + jump-to-form). ◐→✔.
- **T8 About** — Highest-touch page; needed the **journey timeline** as a first-class RTL component and the new **"קצת ים"** section. Press §05 stays hidden (no "TBC"). ◐→✔.
- **T2 Services / T3 Project** — Structurally present; main risks are (a) image-less cards (use `.ph.clean`) and (b) external-link affordance on project singles (S-05). Spec provided; no new screen needed.
- **System (404/search/archive)** — was the real gap; now complete (S-02).

## §3 · Type, color, spacing (precision notes for the build)
- **Type:** Frank Ruhl Libre (display/serif) · Assistant (sans/body) · JetBrains Mono (labels/eyebrows). Map every heading to one scale token; tune `letter-spacing` per display step (canon G-08). Body min 17px; never below 14.5px for meta.
- **Color:** worlds use **deep** for H1/anchor/button, **light** for chip/accent. Replace any literal `#fff` on-world with `var(--paper)`. **Spark (`#d23a2e`) ≤ 3–5 uses site-wide** — currently near ceiling; budget it.
- **Spacing:** drive vertical rhythm from `--sec-y`/`--s-*`; remove inline `border-top:none`/padding overrides. RTL: logical properties only (`inset-inline-*`, `margin-inline-*`) — no raw left/right.

## §4 · Prioritised sequence for Claude Code
1. **Lock + facts** (S-01, S-06) — copy/markup only, highest risk, do first.
2. **System templates** (S-02) — 404 → search → empty-archive.
3. **Media degradation** (S-03) — `.ph.clean` + remove visitor-facing TBD.
4. **Contact hero + About timeline/קצת ים** — the two template completions.
5. **Counts + external links** (S-04, S-05) — wire to CPT meta.
6. Token hygiene + RTL/375px sweep (canon G-07/09/10/11).

Each step = atomic commit; verify cache-busted (`?nc=`) per page; honor both super-locks on every change.

*team_35 · design review · 2026-06-01 — companion artifact: Precision Mockup v4.html*
