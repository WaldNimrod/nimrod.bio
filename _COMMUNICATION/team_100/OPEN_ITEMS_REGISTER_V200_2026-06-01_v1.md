# OPEN ITEMS REGISTER — nimrod.bio V200 — team_100 — v1

**Date:** 2026-06-01
**Author:** team_100
**Type:** OPEN ITEMS / COMPLETION REGISTER
**Env:** dev `https://nimrod-bio-2026.s887.upress.link` · theme **v0.7.12** · production cutover NOT done
**Purpose:** single source of "what's left to finish the site" — content, code, testing — canonical WPs + untracked items.

---

## A · Canonical WPs (roadmap.yaml)

| WP | State | Remaining |
|----|-------|-----------|
| **NB-S002-P009-WP001** — UI Precision + Final Visual Assets | IN_PROGRESS / L-GATE_BUILD | Design build A–D done on dev → needs team_100 COMPLETION + L-GATE_BUILD sign. Sub-block: T-04 logo (on T-07 basket file). |
| **NB-S002-P009-WP005** — Media wiring (CPT galleries) + asset swap | IN_PROGRESS / L-GATE_BUILD | **NEW (this register).** Wire galleries; hot-swap open assets. *Started now.* |
| **NB-S002-P006-PROGRAM** — Content Expansion Phase | IN_PROGRESS / L-GATE_DISCOVERY | No WP batch opened yet; backlog seed below. team_110 + owner interactive. |
| **NB-S002-P005-WP001B** — Pre-cutover full QA re-run + Lighthouse | PLANNED / L-GATE_SPEC | **NEW (this register).** QA over A–D + media + new templates; Lighthouse; F-001 close-out. |
| **NB-S002-P005-WP002** — Production Cutover | PLANNED / L-GATE_SPEC | LOD400 ready; deferred until content+design complete → then MANDATE team_10. |

All other roadmap WPs COMPLETE.

---

## B · CONTENT — open

### B1 · Owner-supplied media — gaps (NO substitutes)
1. ים / סירה (sailing) — About §06 "קצת ים" — ⬜ **STILL OPEN**
2. ~~פאטבונג close-up — greenhouse~~ — ✅ **CLOSED 2026-06-01** (owner dropbox; media 1100 → greenhouse gallery, project 31)
3. ~~מתחחת + Power-Harrow — BCS tools~~ — ✅ **CLOSED 2026-06-01** (owner dropbox; 8 tiller shots, media 1101–1108 → BCS gallery, service 24)
4. ביוצ'ר — process / workshop / field — ⬜ **STILL OPEN**
5. ~~HEIC originals — convert + inspect~~ — ✅ **RESOLVED 2026-06-01** (owner dropbox 8 HEIC converted; were the tiller + pak-bung shots above)

**Remaining media gaps: #1 sea/boat + #4 biochar** (stay `.ph.clean` until owner supplies).

### B2 · Owner facts / decisions
- SFA **calculator (S004)** — what it computes (page says "planned")
- Greenhouse spec detail (240m²/NFT?) — generic now; **420 מ"ר confirmed & live**
- About **§05 press** — verified links, or stays hidden
- **hero-code** image — SFA/tt screenshot or abstract
- **Brand logo** — transparent PNG ready; needs theme logo-slot decision

### B3 · Content-expansion backlog (P006 seed)
- Broken link `/blog/back-to-mud/` (referenced, no post)
- Refresh the **22 migrated blog posts**
- New posts incl. **biochar**; BOOM post series drafts
- Possible new services/projects pages
- Extended TikTrack marketing text (NotebookLM) — optional

---

## C · CODE — open

| # | Item | Owner | WP |
|---|------|-------|-----|
| C1 | **CPT galleries unwired** — BCS ×2, עירית שומית ×2, Garden ×7, Greenhouse ×13 → map `_nb_gallery` attachment IDs (now feasible) | team_100/35 | P009-WP005 ← started |
| C2 | **Open visual assets** hot-swap — logo SVG master, favicon, OG, 5 washes, real SFA/TikTrack screenshots | team_35 | P009-WP005 |
| C3 | **Spark-budget refactor** — ~39 `#d23a2e` refs → canon 3–5 (cross-CSS) | team_35 | deferred |
| C4 | **Contact email** — provision `nimrod@nimrod.bio` → forward `nimrod@mezoo.co`, restore branded display | owner/team_35 | deferred |
| C5 | **T4 floating share FAB** (`.post-share-fab`) — CSS shipped, markup not added | — | **V300** |
| C6 | **Image-weight optimization** pass (pre-cutover) | team_35 | P009-WP005/cutover |
| C7 | Carry-forward: a11y contrast waiver · SMTP config · remaining `.tbc` blocks · system.css line-height 1.65 vs spec 1.55 (LOCKED — GCR if changed) | team_35 | P006/cutover |

---

## D · TESTING — open

| # | Item | WP |
|---|------|-----|
| D1 | **team_100 L-GATE_BUILD sign** on P009-WP001 (design build) | P009-WP001 |
| D2 | **team_50 full QA re-run** over A–D + media + 404/search/empty-state | P005-WP001B |
| D3 | **Lighthouse / performance** pass | P005-WP001B |
| D4 | **375px mobile re-verify** on new components (Stage C/D) | P005-WP001B |
| D5 | **Contact form live-delivery** confirmation to `nimrod@mezoo.co` (F-001 close) | P005-WP001B |
| D6 | **team_190 constitutional L-GATE_VALIDATE** — cross-engine final gate (gates cutover) | pre-cutover |

---

## E · Gating sequence to "site complete"
```
C1 galleries + C2 assets  ─┐
B1 owner media + B2 facts ─┤→ D1 team_100 L-GATE_BUILD (P009-WP001)
B3 P006 content batches   ─┘            ↓
                            D2–D5 QA re-run + Lighthouse (P005-WP001B)
                                         ↓
                            D6 team_190 L-GATE_VALIDATE (constitutional)
                                         ↓
                            P005-WP002 Production Cutover → nimrod.bio
```

## F · Locks (apply to every remaining item, incl. alt/aria/meta)
1. **Micha / "Micha OS"** — never published.
2. **Demonstrate, never name** — אנטרופיה · נגנטרופיה · רקורסיה · CDIP · cross-domain isomorphism · פרמקלצר · "3×" · אינסטנסים · קואופרטיב · קומון; no marketing clichés.

*team_100 | open-items register | 2026-06-01 | 2 new WPs opened (P009-WP005, P005-WP001B); gallery wiring started*
