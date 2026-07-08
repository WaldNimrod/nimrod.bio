# QA REPORT — NB-S002-P009-WP001 DEPLOYED — team_50 (dev QA) — v1

**Date:** 2026-06-03
**From:** team_50 (dev QA — independent, cross-engine)
**To:** team_190 (constitutional L-GATE_VALIDATE) · cc team_100, team_00
**WP:** NB-S002-P009-WP001 (V200 UI precision package — 2 new templates + 3-source fixes)
**Env:** dev `https://nimrod-bio-2026.s887.upress.link` · theme **v0.7.16** · landing commit `ea9105cc`
**Method:** independent re-verification (prior reports NOT trusted). CDP browser-real probe + curl HTML/CSS inspection + FTPS byte-parity + Lighthouse. Dev TLS invalid BY DESIGN → cert-bypass DEV-ONLY. Every fetch cache-busted `?nc=`.

---

## 0 · Headline
All 11 LOD400 §6 acceptance criteria **independently verified PASS**. CDP 22/22 (11 pages × 375/1440) — 0 horizontal overflow, 0 forbidden terms. Byte-parity 6/6 (deployed == repo == handoff source). No STOP trigger. Dev Lighthouse Perf/SEO are noindex/cache artifacts (per QA_HARNESS), not blockers.

---

## 1 · CDP probe — per-page matrix (`docs/qa/cdp/v16-team190/`)
Runner: `node scripts/qa/cdp/qa_probe.mjs` · viewports 375 (mobile) + 1440 (desktop) · `--absent` 13 lock terms. **22/22 PASS.**

| Page | HTTP/render | 375 overflow | 1440 overflow | forbidden | title |
|------|------|------|------|------|------|
| `/` | rendered | none | none | 0 | בית - נמרוד ולד · Unless |
| `/projects/` | rendered (200) | none | none | 0 | פרויקטים Archive … |
| `/about/` | rendered | none | none | 0 | על נמרוד … |
| `/contact/` | rendered | none | none | 0 | צור קשר … |
| `/world/soil/` | rendered | none | none | 0 | אדמה … |
| `/world/know/` | rendered | none | none | 0 | ייעוץ והוראה … |
| `/world/code/` | rendered | none | none | 0 | דיגיטל … |
| `/project/sfa/` | rendered | none | none | 0 | SmallFarmsAgents … |
| `/project/rest-x-greenhouse/` | rendered | none | none | 0 | חממה הידרופונית … |
| `/services/bcs/` | rendered | none | none | 0 | BCS … |
| `/services/` | rendered | none | none | 0 | פעילויות Archive … |

scrollWidth == clientWidth on every page/viewport (375==375, 1440==1440).

## 2 · Screenshot index (`docs/qa/cdp/v16-team190/screenshots/`, 22 PNG)
2 per page (`<page>_mobile.png`, `<page>_desktop.png`). Visually confirmed:
- `__desktop.png` — home flow §05 → **Unless lockup stacked** (EN large / HE small-muted) → **§06 posts-grid** (1 feat + 4 cards) → manifesto → CTA.
- `_projects__desktop.png` / `_projects__mobile.png` — archive: 3-up desktop / 1-up mobile, scope chips + stage stamps, heading "פרויקטים · מהשטח".
- `_world_soil__mobile.png` — **T1 lattice anchor full-width at ≤900px, NOT crushed**; lat-side cards stack clean; bridge title underline visible.

## 3 · curl structural verification (cache-busted)
- **§06 home:** 1 `.posts-grid`, 1 `.rp-card.feat` (with excerpt), 5 `.rp-card` total (1+4). Section order `t7-projects → t7-posts → manifesto`. Real post titles pulled live (e.g. "למה ערוגה ברוחב 80 ס״מ…", "אלה אם — Unless"). World chips render from each post's `world` terms (`wc soil`/אדמה, `wc code`/דיגיטל, `wc know`/ידע); posts without terms correctly show no chip.
- **`/projects/`:** HTTP 200. 4 `.proj-card`, 4 `.scope-row`, stage stamps `pilot/live/legacy/live`, heading "פרויקטים · מהשטח". REST cross-check `wp/v2/projects` → **X-WP-Total = 4** = exactly all published projects, newest-first. Empty-state path present in template (`עדיין אין פרויקטים שפורסמו.`).
- **Home links:** `href=".../projects/"` + "לכל הפרויקטים" present → target returns **200**.

## 4 · Served CSS verification (downloaded `?ver=0.7.16`)
- **t1.css enqueued on `/projects/`** (1 link tag, ver=0.7.16); enqueue condition in `template-styles-t1.php` includes `is_post_type_archive('project')`.
- **Bridge underline title-only:** t7 `.t7-bridges .bridge-card h3 { text-decoration:underline; thickness 1.5px }`; card root `text-decoration:none`. t1 `.bridge-card h3` same underline; `.bridge-card` body `text-decoration:none`. Parity confirmed.
- **Unless lockup:** `.unless-lockup .inner{display:block}` (stacked); `.word` clamp(64-168px) paper; `.he` clamp(15-18px) Assistant sans `rgba(245,243,236,.62)` muted. EN-large / HE-small-muted confirmed.
- **Lattice mobile fix @≤900px:** `.vc-lattice > *{grid-column:auto!important;grid-row:auto!important}` + `.lat-anchor{grid-column:1/-1!important;order:-1}`. Phantom 3/4 column killed; anchor full-width.
- **Δ2 world-card:** `.t7-worlds .world-card .wcard-media{aspect-ratio:16/10}` + `>img{position:absolute;inset:0;object-fit:cover}` → uniform crop, equal heights.
- **projects-archive responsive:** 3-up → 2-up @1000px → 1-up @640px; `.ph{aspect-ratio:16/10;object-fit:cover}`.
- **Stale scaffolds retired:** `.posts-grid-4` / `.post-card.post-square` / `.recent-posts` appear ONLY in comment lines (t7 590/1613/1661) — no active selectors. 0 live scaffold rules in t7 or t1.

## 5 · Byte-parity (FTPS, one file per connection)
`scripts/upress_ftps_download.py` → SHA-256 diff deployed vs repo vs handoff. **6/6 PARITY:**

| File | sha256 (12) | bytes | deployed==repo | ==handoff |
|------|------|------|------|------|
| front-page.php | 58b00efe8e22 | 25473 | ✔ | ✔ |
| archive-project.php | 924a87446445 | 2867 | ✔ | ✔ |
| inc/cpt-project.php | 7127d7b8bc86 | 1143 | ✔ | ✔ |
| inc/template-styles-t1.php | 58f83950f718 | 360 | ✔ | ✔ |
| assets/css/t7.css | a0e24ad54c52 | 70274 | ✔ | ✔ |
| assets/css/t1.css | 349553562b69 | 32229 | ✔ | ✔ |

- `NB_THEME_VERSION` = `0.7.16` (functions.php).
- `cpt-project.php`: `has_archive=>'projects'`, `rest_base=>'projects'`, `show_in_rest=>true`.
- **No inline-style introduced:** front-page.php inline `style=` count is 4 at baseline `a35a67df` AND 4 now — all pre-existing (bridge CSS-var bindings + one `aspect-ratio` placeholder); `git diff a35a67df→HEAD` shows **0 inline-style lines added**. §06 block introduces none. No overrides layer.

## 6 · Lock-scan (full, incl. alt/aria/comments)
13 forbidden terms (`TBD·CDIP·cross-domain·אנטרופיה·נגנטרופיה·רקורסיה·פרמקלצר·3×·אינסטנסים·קואופרטיב·קומון·Micha·מיכה`) scanned across live home HTML, projects HTML, served t7.css, served t1.css, and alt/aria attributes → **0 hits**. CDP `--absent` independently confirms 0 across all 11 pages.

## 7 · Lighthouse (`docs/qa/cdp/v16-team190/lighthouse/`, full Google Chrome via CHROME_PATH)
| Page | Perf | A11y | Best-Pract | SEO |
|------|------|------|------|------|
| `/` | 68 | 90 | 100 | 69 |
| `/projects/` | 70 | 88 | 100 | 69 |
| `/about/` | 66 | 90 | 100 | 69 |

- **SEO 69** — dev edge `X-Robots-Tag: noindex` artifact (documented in QA_HARNESS); re-measure on primary domain at cutover.
- **Perf 66-70** — SuperCache miss on `?nc=` + no Cloudflare on dev; artifact, re-measure on prod.
- **A11y 88-90** — non-zero audits: `color-contrast`, `heading-order`, `aria-hidden-focus`. These are **site-wide, pre-existing** (present on `/about/` which this WP did not touch) — NOT introduced by P009-WP001. Advisory for a future a11y sweep, not a WP defect.
- **Best-practices 100** on all three.

## 8 · Defect list (severity-ranked)
- **BLOCKER:** none.
- **MAJOR:** none.
- **MINOR (advisory, pre-existing, out of WP scope):** site-wide Lighthouse a11y items (color-contrast / heading-order / aria-hidden-focus) on all pages incl. untouched `/about/`. Recommend a dedicated a11y carry-forward.
- **INFO:** 4 `<br>` in the home **manifesto** narrative cascade ("הידע הפך לייעוץ.<br>הייעוץ הפך לכלי…") — intentional rhetorical line-breaks matching the mockup, NOT awkward mid-measure breaks. 0 of 9 lede/body paragraphs contain `<br>`. Δ1 criterion satisfied.

## 9 · Tooling caveats (executed vs not)
- **Executed:** CDP 22/22 + screenshots; curl HTML/CSS structural + REST count; FTPS byte-parity 6/6; full Lighthouse on /, /projects/, /about/; full lock-scan.
- **Notes:** Lighthouse required absolute `node`+CHROME_PATH + non-compound invocation (npx/tail off-PATH inside compound shells in this env — known QA_HARNESS caveat). All three runs completed successfully.

---
*team_50 | dev QA report (deployed result) | 2026-06-03 | v0.7.16 @ ea9105cc | independent / cross-engine*
