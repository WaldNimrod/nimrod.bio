# HANDOFF — V200 Site Rebuild — team_35 — v1.0.0

**Date (effective):** 2026-05-24 (delivery date)
**Authored (retroactive index):** 2026-05-25 by team_200 under team_00 directive
**Author:** team_35 (Design Studio / claude-design) — package authored 2026-05-22…24
**WP / Scope:** V200 — Full Site Rebuild
**Type:** HANDOFF (canonical index — Stage 3 LOCKED)
**Mandate:** [`MANDATE_TO_TEAM-35_V200_SITE_REBUILD_2026-05-24_v1.0.0.md`](../../team_100/V200_SITE_REBUILD/MANDATE_TO_TEAM-35_V200_SITE_REBUILD_2026-05-24_v1.0.0.md)
**Brief:** [`BRIEF_V200_SITE_REBUILD_2026-05-24_v1.0.0.md`](../../team_100/V200_SITE_REBUILD/BRIEF_V200_SITE_REBUILD_2026-05-24_v1.0.0.md)
**Revision round:** 1 (initial delivery)
**Retroactive: YES.** This canonical index is filed AFTER team_35 delivery (2026-05-24, Stage 3 LOCKED, 37 files) to close the audit trail per team_35 governance contract §Handback Protocol. The package contents themselves are authentic team_35 output dated 2026-05-22…24; only this index artifact is retroactive.

---

## 1. Package location

The full deliverable lives at:

```
/Users/nimrod/Documents/nimrod-bio/sources/team_35_design_package/_handoff/
```

This is NOT the canonical inbox path. Deviation reason: the package is a self-contained 37-file bundle with relative HTML/CSS/JSX links that would break if moved. The package is preserved in place; this canonical index points at it.

## 2. Package contents (authoritative inventory)

```
sources/team_35_design_package/_handoff/
├── 00-HANDOFF-claude-code-110.md         ← downstream entry-point for team_110
├── 01-PROMPT-watercolor-backgrounds.md   ← prompt for T-03 illustrations
├── 02-PROMPT-logo-family.md              ← prompt for T-04 logo family
├── SESSION_HANDOFF.md                    ← session context + open tickets
├── README.md                              ← package self-map
│
├── brand/                                ← LOCKED design canon (SSoT)
│   ├── system.css                        ← CSS tokens v3.3 LOCKED
│   ├── TAXONOMY-v3.4-LOCKED.md           ← entities, scope, stage, worlds
│   ├── TAXONOMY-v3.3-LOCKED.md           ← historical
│   ├── voice.md                          ← tone of voice canon
│   ├── typography.md                     ← typography spec
│   ├── site-context-2026-05-v2.md        ← brand & worldview canon
│   └── HANDOFF-Stage3.md                 ← Stage 3 summary
│
├── components/                           ← design components
│   ├── Foundations.html                  ← tokens reference page
│   ├── Components.html                   ← v2 atoms LOCKED
│   └── Components v3 - Bridge.html       ← v3 Bridge card (T-06 closed)
│
└── templates/                            ← 7 templates with React+CSS prototypes
    ├── T1 World - אדמה.html  + T1-styles.css + T1-data.jsx + T1-variants.jsx
    ├── T2 Services.html      + T2-styles.css + T2-data.jsx + T2-instances.jsx
    ├── T3 Project.html       + T3-styles.css + T3-data.jsx + T3-instances.jsx
    ├── T4 Post.html          + T4-styles.css
    ├── T5 Blog.html          + T5-styles.css + T4-T5-data.jsx (shared)
    ├── T7 Home.html          + T7-styles.css
    ├── T8 Static.html        + T8-styles.css
    └── tweaks-panel.jsx      ← Tweaks framework (T7)
```

T6 (Portfolio) intentionally absent — removed in Sitemap v3.1.

## 3. Open tickets / assumptions log

| Ticket | Item | Owner |
|---|---|---|
| T-01 | BOOM A/B variant in T7 | team_35 (deferred) |
| T-02 | Mobile screens (Stage 5) | team_35 (deferred) |
| T-03 | Watercolor illustrations (×5) | external engine via prompt in package |
| T-04 | Logo family | external engine, blocked by T-07 |
| T-05 | WordPress archive import | team_110 |
| T-07 | Original basket file | Nimrod (team_00) |
| T-09 | ✅ closed — WhatsApp `wa.me/972547776770` | — |
| T-12 | coop-sharon approval | Nimrod (team_00) |
| Q-02 / Q-03 / Q-05 / Q-09 / Q-10 / Q-11 | TBC content (markers in code) | Nimrod (team_00) |

## 4. Acceptance posture

**Stage 3 LOCKED** as of 2026-05-24 per team_35 self-mark + team_00 acceptance via direct invocation. L-GATE_DESIGN closure recorded retroactively by this artifact set (BRIEF + MANDATE + HANDOFF). team_35's role on V200 is CLOSED unless team_100 issues a `REVISION_REQUEST`.

## 5. Downstream cascade

- Next consumer: **team_110** (Domain Builder) — see `00-HANDOFF-claude-code-110.md` inside the package for the team_110-specific entry point.
- team_100 (nimrod-bio) authors LOD400 executable spec on top of this package before team_110 begins.
- Production code path: WordPress + custom theme on uPress, dev URL `nimrod-bio-2026.s887.upress.link`, prod `nimrod.bio`.

## 6. Notes

- Package was produced under direct team_00 invocation; collapsed LOD200 + LOD300 into one delivery cycle (permitted under direct-invocation mode).
- 37 files total. Self-contained — opens locally without internet (except React+Babel+Google Fonts CDN).
- Preview HTML files are advisory; the `.md` artifacts in `brand/` are SSoT for canon decisions.

---

*Canonical handoff index — team_35 nimrod-bio | retroactively filed 2026-05-25 by team_200 cowork on team_00 mandate*
