---
type: COMPLETION
from: team_35 (Site Design + Build — Claude Design)
to: team_100 (re-verification), team_00 (Principal)
cc: team_50 (live-run validation)
project: nimrod-bio
milestone: V200 (UI precision pass)
wp_id: NB-S002-P009-WP001
parent_mandate: MANDATE_PRECISION_SESSION_V4_2026-06-01_v1.md
companion: MANDATE_TEAM50_LIVE_ARTIFACTS_PART1_2026-06-02_v1.md (artifact request)
date: 2026-06-02
version: v1
status: PART 1 SCANNED · §06 BUILT (PATCH READY — AWAITING team_35 DEPLOY)
env: dev https://nimrod-bio-2026.s887.upress.link · theme v0.7.15 → (bump on deploy) · baseline a35a67df
---

# COMPLETION — Precision session v4 · Part 1 scan + §06 build

## §0 What this covers
Part 1 (live-vs-mockup fidelity scan) using team_50's live artifact pack, and Part 2's one
standing build item (§06 Recent-Posts) produced as a **ready-to-apply patch**. team_35 runs in
Claude Design and cannot FTPS-deploy; deploy/version/cache-bust/lock-scan steps are specified in
§4 for the build-side landing.

## §1 Part 1 — fidelity scan (per screen)

Source of truth: `Precision Mockup v4.html`. Live evidence: team_50 pack
(`_OUTBOX_live_artifacts_part1/`) — harness 12/12 PASS, forbidden-terms 0, A/B/C byte-check
match. Mockup-internal walk by team_35 (T7 full, section-by-section at desktop).

| Screen | Buckets A/B/C (team_50 byte-check) | team_35 mockup walk | Verdict |
|---|---|---|---|
| **t7 home** | eyebrow mono ✓ · WA green #1f8a4c ✓ · :root tokens + line-height 1.55 (26.35px/17px) ✓ | Hero→§01→§07 all consistent | **PASS** — except §06 (see §2) |
| **t1 world** (`/world/soil/`) | tokens ✓ · `.vc-bridges` 2-up ✓ | walk pending (next) | **PASS (byte)** · visual walk queued |
| **contact** | contact form + social block ✓ | — | **PASS (byte)** |
| **about** | about prose measure + `t8-final-cta` ✓ | — | **PASS (byte)** |
| **sys 404/search** | — | — | **PASS (harness)** |
| **states** | design-spec only (no live route) | reference screen | **N/A (spec)** |

### Drift reconciliations (two team_50 flags resolved)
1. **Home bridges 3-up — NOT a drift.** team_50 flagged live `.bridges-grid` as 3-up vs an
   expected 2-up. The mockup home `.bridges-grid` is **intentionally 3-up** (`repeat(3,1fr)`,
   the three pairwise seams: soil×know, know×code, soil×code). The "2-up" target applies only
   to the **world page** `.vc-bridges` (`1fr 1fr`), which live already satisfies. Live home →
   **matches mockup.** No change.
2. **Route map** — t1 אדמה lives at `/world/soil/` (200), not the assumed `/world/adama/`.
   Informational; mockup unaffected.

### Open drifts after scan
- **D1 · §06 absent on live + repo** — the approved build item. **Resolved by this patch** (§2);
  awaits team_35 deploy.
- **D2 · sub-document img overflow** (team_50 minor: 26px @375, 101px @1440, no scrollWidth
  growth) — contained, non-breaking. **Hand to team_50/team_35 to localize** the offending
  `<img>` (candidates: a `.ph`/`.media` cover image or the manifesto `.mf-bg` watermark);
  out of scope for this patch.
- **D3 · mockup-side typo (SSOT)** — hero kicker in `Precision Mockup v4.html` reads `מֳר`
  (vocalized) where the unit is `מ״ר`. **Live front-page.php is already correct (`מ״ר`).**
  Fixed in the mockup SSOT this session (`.hero-poster .kc`, `מֳר → מ״ר`). Live unchanged.

## §2 Part 2 — §06 Recent-Posts / blog teaser (BUILT to mockup structure)

**Decision honored:** team_00 2026-06-01 — build §06 into `front-page.php` to the Precision
Mockup v4 structure (`.posts-grid` → 1 `.rp-card.feat` + 4 `.rp-card`), pulling **real** latest
posts, newest featured, placed between §05 projects and the manifesto. No inline styles; no
overrides layer — markup in the template, styles in the module CSS.

Patch dir: `_COMMUNICATION/team_35/PATCH_T7_S06_RECENT_POSTS_2026-06-02_v1/`
- `theme/front-page.php` — patched template (drop-in).
- `theme/assets/css/t7.css` — patched module CSS (drop-in).
- `S06 Preview.html` — standalone render-parity preview (verified against the mockup).

### Change log (file · selector/anchor · old → new)
| # | File | Anchor / selector | Change |
|---|---|---|---|
| 1 | `front-page.php` | between `.t7-projects </section>` and `/* MANIFESTO */` | **ADD** §06 block: `WP_Query` (post, publish, 5, newest first) → `section.t7-section.t7-posts > .posts-grid > a.rp-card[.feat]`. Newest = `.feat` with excerpt; rest title-only. Date `d·m·y`. World chips from each post's `world` taxonomy terms (soil/know/code → אדמה/ידע/דיגיטל), self-contained (no cross-template helper dependency). Section guarded by `have_posts()`. |
| 2 | `t7.css` | `.recent-posts` / `.rp-card` (4-col scaffold) | **REPLACE** with canonical mockup block: `.posts-grid` (repeat(4,1fr), grid-auto-rows:1fr) + `.rp-card` + `.rp-card.feat` (grid 1/3 · 1/3) + `.ph`/`.body`/`.meta`/`h4`/`p`/`.chips`/`.wc[.soil/.know/.code]` + responsive (≤1000 → 2-up + feat full; ≤640 → 1-up). Byte-faithful to mockup §06 CSS. |
| 3 | `t7.css` | `.posts-grid-4` / `.post-card.post-square` block | **DELETE** (stale, never had markup; diverged from mockup). |
| 4 | `t7.css` | `@media` refs to `.recent-posts` / `.posts-grid-4` (×3 blocks) + spec-map comment | **DELETE / RETIRE** — responsive now lives inside the canonical block; comment map updated. |

**Result:** one canonical `.posts-grid` path; three divergent scaffolds retired. Render-parity
verified standalone (`S06 Preview.html`) — feat 2×2 + 4 cards, chips, mono meta, serif titles.

## §3 Locks
Every added string (markup, comments, the two mockup edits, this report) lock-scanned —
**0 forbidden terms.** §06 copy is the mockup's own approved blog text; live post titles/excerpts
are honored read-only by the query (no rewriting).

## §4 Deploy steps (team_35 build-side — required to close D1)
1. Land `front-page.php` + `assets/css/t7.css` from the patch dir onto baseline `a35a67df`.
2. Deploy via `docs/UPRESS_FTPS_MANDATORY_PROCEDURE.md`; bump `NB_THEME_VERSION` (theme v0.7.15 → next).
3. Pull deployed files back (one per connection); verify byte-parity repo == deployed.
4. Cache-bust; confirm asset URL carries the new version query.
5. `node scripts/qa/cdp/qa_probe.mjs` → expect §06 present on `/` (`.posts-grid` + `.rp-card.feat`
   + 4 `.rp-card`), 0 overflow, lock-scan 0.
6. Confirm cards are **real** recent posts (newest featured) and world chips reflect each post's
   `world` terms.

## §5 Hand-back
- **team_100** — independent re-verification of Part 1 scan + §06 patch.
- **team_50** — post-deploy re-run (D1 closure proof) + localize D2 (img overflow).
- **team_00** — visual approval of §06 (`S06 Preview.html`; target = mockup §06, already approved)
  and the mockup typo fix (D3). Precision session continues (next screen: T1 world).

---

*team_35 | precision session v4 · Part 1 scan + §06 build patch | 2026-06-02 | locks 0 | awaiting deploy*
