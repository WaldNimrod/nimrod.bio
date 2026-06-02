# VERDICT — NB-S002-P009-WP001 DEPLOYED — team_190 (constitutional L-GATE_VALIDATE) — v1

**Date:** 2026-06-03
**Authority:** team_190 (constitutional L-GATE_VALIDATE — cross-engine, immutable, builder≠validator)
**WP:** NB-S002-P009-WP001 (V200 UI precision package — 2 new templates + 3-source fixes)
**Env:** dev `https://nimrod-bio-2026.s887.upress.link` · theme **v0.7.16** · landing commit `ea9105cc`
**Inputs validated:** LOD400 §6 (11 criteria) · deployed result · team_50 QA_REPORT (`_COMMUNICATION/team_50/QA_REPORT_NB-S002-P009-WP001_DEPLOYED_2026-06-03_v1.md`)
**Method:** independent re-verification — builder reports (team_100) explicitly NOT trusted; every claim re-executed (CDP, curl, FTPS byte-parity, Lighthouse, lock-scan).

---

## §0 · VERDICT BOX

> ## ✅ PASS
>
> **STOP triggers — all NEGATIVE:**
> - Lock breach? **NO** (0 forbidden terms across HTML + CSS + alt/aria + CDP --absent, all 11 pages)
> - Non-200 on required route? **NO** (`/projects/` = 200; all 11 pages render)
> - Horizontal overflow? **NO** (CDP 22/22, scrollWidth==clientWidth @375 & @1440)
> - Missing template? **NO** (`archive-project.php` deployed; `has_archive=>'projects'`; byte-parity)
>
> **Byte-parity:** 6/6 deployed == repo == handoff source. **No inline styles / no overrides layer introduced.**
> **Route:** → team_100 to **close WP** per ADR042 (L2 spoke: roadmap LOD500 note + git audit). Carry-forward G2/G3/C2 → team_00 prioritization.

---

## §1 · Per-criterion result table (11 rows — independent verification)

| # | LOD400 §6 criterion | Independent result | Verdict |
|---|---|---|---|
| 1 | §06 recent-posts on `/`: `.posts-grid` 1 `.rp-card.feat` + ≤4 `.rp-card`, real titles + world chips, between §05 and manifesto | 1 grid, 1 feat (w/ excerpt) + 4 cards = 5; real live post titles; chips from each post's `world` terms; order t7-projects→t7-posts→manifesto confirmed (curl + screenshot) | **PASS** |
| 2 | `/projects/` 200, lists every published project newest-first (scope+stage+world), empty-state sane | HTTP 200; 4 proj-cards w/ scope-row + stage stamps (pilot/live/legacy/live); REST X-WP-Total=4 = all published; empty-state present in template | **PASS** |
| 3 | `cpt-project.php has_archive='projects'`; permalinks flushed; §05 + T1 "לכל הפרויקטים" resolve (no 404) | `has_archive=>'projects'` (byte-parity); home link + "לכל הפרויקטים" → `/projects/` 200; no 404 | **PASS** |
| 4 | t1.css enqueued on project archive (via template-styles-t1.php) | enqueue cond. `is_post_type_archive('project')` confirmed; served `t1.css?ver=0.7.16` present on `/projects/` | **PASS** |
| 5 | T1 lattice intact @375/900/1440 — no crushed anchor; no phantom `grid-column:3/4` ≤900px; anchor full-width mobile | served t1.css `@media(max-width:900px)`: `.vc-lattice>*{grid-column:auto!important}` + `.lat-anchor{grid-column:1/-1!important;order:-1}`; screenshot world/soil mobile shows anchor full-width, not crushed | **PASS** |
| 6 | Bridge cards: underline on `h3` title ONLY, body no underline (t7 + t1) | t7 `.bridge-card h3` underline + root `none`; t1 `.bridge-card h3` underline + body `none`. Parity confirmed (served CSS) | **PASS** |
| 7 | Unless lockup: EN source large, HE translation small/muted, stacked | `.unless-lockup .inner{display:block}`; `.word` clamp(64-168px); `.he` clamp(15-18px) muted `rgba(245,243,236,.62)`; screenshot confirms stacking | **PASS** |
| 8 | Δ1: no awkward forced `<br>` mid-measure on ledes/bodies | 0 of 9 lede/body paragraphs contain `<br>`; the 4 `<br>` are intentional manifesto cascade (mockup-matched) | **PASS** |
| 9 | Δ2: world-card images uniform 16/10 crop, equal heights | `.wcard-media{aspect-ratio:16/10}` + `>img{absolute;inset:0;object-fit:cover}` (served t7.css) | **PASS** |
| 10 | Stale scaffolds retired — no LIVE `.posts-grid-4`/`.post-card.post-square`/`.recent-posts` rules (comments OK) | all three appear ONLY in comment lines; 0 active selectors in t7 or t1 | **PASS** |
| 11 | CDP 0 horizontal overflow @375/1440 all pages + lock-scan 0 (incl alt/aria); byte-parity repo==deployed; no inline/no overrides | CDP 22/22 (0 overflow); lock-scan 0 across HTML+CSS+alt/aria + CDP --absent; byte-parity 6/6; 0 inline-style lines added vs `a35a67df`; no overrides layer | **PASS** |

**Result: 11/11 PASS.**

---

## §2 · Advisories (non-blocking, do NOT gate this WP)
- **A11y (pre-existing, site-wide):** Lighthouse flags `color-contrast`, `heading-order`, `aria-hidden-focus` on `/`, `/projects/`, AND untouched `/about/` → NOT introduced by P009-WP001. Recommend a dedicated a11y carry-forward (new WP).
- **Dev Lighthouse artifacts:** SEO 69 (`X-Robots-Tag: noindex` edge), Perf 66-70 (SuperCache miss on `?nc=` + no Cloudflare). Expected on dev per QA_HARNESS; re-measure on primary domain at cutover (P005-WP002). Best-practices = 100.
- **Manifesto `<br>` cascade:** intentional rhetorical breaks matching the mockup — confirmed NOT a Δ1 drift.

## §3 · Carry-forward acknowledgment (LOD400 §7 — NOT in this WP)
- **G2** — precision walk T2 Services / T3 Project single / T4 Post single / T5 Blog index. Acknowledged, deferred → team_00.
- **G3** — know/code world-page variants + heritage parity. Acknowledged, deferred → team_00.
- **C2** — home sub-document `<img>` overflow (contained, non-blocking; no scrollWidth growth — CDP confirms 0 overflow). Acknowledged, deferred → team_00 localize/clamp.

## §4 · Route (one line)
**PASS → team_100 closes NB-S002-P009-WP001 per ADR042 (L2 spoke: roadmap LOD500 note + git audit); G2/G3/C2 to team_00 backlog.**

---
*team_190 | constitutional L-GATE_VALIDATE (deployed result) | 2026-06-03 | v0.7.16 @ ea9105cc | cross-engine · immutable · this verdict is the gate*
