---
type: INVENTORY_DECISIONS
from: team_110 (Domain Architect / P007 orchestrator · Wave 2)
to: team_00 (Principal — choose option for each)
cc: team_110 (gate sign), team_10 (Wave 3 executor)
project: nimrod-bio
milestone: V200
wp_id: NB-S002-P007-WP002
wave: 2 of 4 (P007)
date: 2026-05-28
version: v1.0.0
mandate_ref: _COMMUNICATION/team_110/MANDATE_NB-S002-P007-WP002_COMPLETION_INVENTORY_v1.0.0.md
predecessor: MCP_QA_REPORT_NB-S002-P007-WP001_2026-05-28_v1.0.0.md
---

# Inventory — Decisions · NB-S002-P007-WP002

## How to use this list

For each decision (D-01..D-07), pick an option or write a custom answer. team_110 will record and route to team_10 in Wave 3.

---

## D-01 — Blog pagination density

| field | value |
|---|---|
| **Question** | Blog index currently shows 10 posts per page (10 of 33). Cutover will have same. Change density or accept? |
| **Context** | Wave 1 finding: `t5-blog-index_1440.png` shows 10 cards. Remaining 23 on page 2+. WP default is 10. Higher density (20-30) means less page-hopping but longer scroll. |
| **Option A** | Keep 10/page (current WP default) — no action needed |
| **Option B** | Change to 20/page — team_10 updates WP Reading settings (2 min) |
| **Option C** | Change to another number (specify) |
| **Recommendation** | A — 10/page is standard; blog is not the primary discovery path (worlds taxonomy is). Low priority to change before cutover. |
| **Blocker for** | Wave 3 MANDATE (team_10 needs this if B/C) |

---

## D-02 — Production Yoast title template

| field | value |
|---|---|
| **Question** | Dev browser tab shows "Unless · V200 dev · Unless" (dev site artifact). What title template should production use? |
| **Context** | The "Unless" tagline is rendered via mu-plugin (locked since P006-WP001). On production, the Yoast site title + separator + tagline need to be set correctly. Current Yoast setting is unknown on dev (may inherit dev-suffix from WP general settings). |
| **Option A** | `%title% · נמרוד ולד` — includes full name |
| **Option B** | `%title% · Unless` — tagline as domain identifier |
| **Option C** | `%title% · nimrod.bio` — domain-based |
| **Option D** | Custom — specify |
| **Recommendation** | B or C. Unless is the brand tagline locked in Intake Q7=A. `%title% · Unless` reinforces brand identity. nimrod.bio as alternative is cleaner for SEO. |
| **Blocker for** | Wave 3 (team_10 sets Yoast site title + separator on dev; verified on cutover). P0 — must resolve. |

---

## D-03 — data-nb-placeholder strip timing

| field | value |
|---|---|
| **Question** | 11 placeholder posts have `data-nb-placeholder="true"` div rendered visibly ("בהכנה" / in-progress marker). When should this be stripped — before cutover, or after content fill post-cutover? |
| **Context** | COMPLETION_CONTENT_PHASE v1.0.0 §6 says team_00 explicitly accepted placeholder posts to ship as-is. Marker is styled for visibility so visitors see "in-progress". Strip = remove the marker div via WP REST content update once real copy is filled. |
| **Option A** | Strip BEFORE cutover — only if Wave 3 fills real content in time (marker removal is part of Wave 3 MANDATE) |
| **Option B** | Strip AFTER cutover — team_00 fills content in WP admin post-cutover; markers auto-removed when real body replaces placeholder |
| **Option C** | Keep markers indefinitely — ship with visible in-progress state on production |
| **Recommendation** | A if Wave 3 delivers content pre-cutover; B if team_00 is filling post-cutover. This decision drives Wave 3 scope. |
| **Blocker for** | Wave 3 MANDATE scope and Wave 4 validation. |

---

## D-04 — Stale SFA link on T7 home

| field | value |
|---|---|
| **Question** | T7 home has a CTA button "הצטרף ל-SFA" linking to `/services/sfa/` — an entity that was deleted in P006. Wave 1 F-005. What should replace it? |
| **Context** | SFA project page was deleted; SFA moved to its own subdomain (`sfa.nimrod.bio` or similar — exact URL not confirmed). T7 home's CTA is currently dead. Wave 1 interactive trace: team_50 could not click through to a valid SFA service page from home. |
| **Option A** | Remove the CTA button entirely — no replacement |
| **Option B** | Replace with link to external SFA URL (provide exact URL) |
| **Option C** | Replace with link to SmallFarmsAgents post (`/blog/smallfarmsagents/`) — internal |
| **Option D** | Replace with link to a world archive (e.g. `/world/code/`) |
| **Recommendation** | B if SFA has a live public URL. Otherwise A (remove). Do not link to a placeholder post. |
| **Blocker for** | Wave 3 MANDATE (team_10 edits the home page block / template). P1. |

---

## D-05 — /about/ page content integration

| field | value |
|---|---|
| **Question** | nimrod-book was dropped from placeholder posts (Intake §2) and designated as source material for `/about/` page. Is the current /about/ content cutover-ready, or does it need nimrod-book material integrated? |
| **Context** | Current /about/ content is from V100 (initial site launch). nimrod-book context: a "personal context substrate" — likely the origin story, values, background that positions nimrod.bio. team_00 said "זה הבסיס לעמוד אודות נימרוד" 2026-05-26. |
| **Option A** | Current /about/ is sufficient — ship as-is for cutover |
| **Option B** | Provide nimrod-book excerpts now → team_10 integrates in Wave 3 |
| **Option C** | Defer to V300 — launch with current, update after cutover |
| **Recommendation** | B if the about page will be a first-impression page for new visitors. C if launch timeline is tight. |
| **Blocker for** | Wave 3 MANDATE (if B). Otherwise no blocker. |

---

## D-06 — harish2021 broken inline asset

| field | value |
|---|---|
| **Question** | Post `/blog/harish2021/` has an inline `<img>` referencing `wp-content/uploads/2026/05/unnamed-file-*` which returns 404. Wave 1 AT-Q8 PASS_WITH_NOTE / F-004. |
| **Context** | 17 of 22 migrated posts have working featured images. The inline asset is an image embedded in the post body (not the featured image slot — harish2021 has featured_media set). The 404 is a console.error, not a layout break. |
| **Option A** | Remove the broken img tag from post body (team_10 REST PATCH, ~10 min) |
| **Option B** | Source the original file, re-upload to library, rewrite URL in post body (team_00 provides file) |
| **Option C** | Accept as-is — console error only, no visible break; fix V300 |
| **Recommendation** | A (quick remove) or C (low visual impact). B only if the image is visually important to the post and team_00 has the original file. |
| **Blocker for** | Wave 3 MANDATE (A/B require team_10 action). |

---

## D-07 — seed-t7-* service entries

| field | value |
|---|---|
| **Question** | Two services with `seed-t7-*` prefix exist (ID 42: seed-t7-produce, ID 43: seed-t7-consulting-hydro). Both have empty content. They appear to be internal seeds or T7 template fixtures, not real service pages. |
| **Context** | Real counterparts already exist: ID 22 (produce), ID 26 (consulting-hydro). The seed- entries show on `/services/` archive and are navigable by direct URL. They pass 200 with empty content — visible to visitors as blank service pages. |
| **Option A** | Delete both seed-t7-* entries (team_10 REST DELETE × 2, ~5 min) |
| **Option B** | Keep them — they serve a template preview purpose |
| **Option C** | Convert to draft (hide from public archive but preserve) |
| **Recommendation** | A — delete. They are duplicates with empty content and would confuse visitors. Real counterparts (IDs 22 + 26) cover the same display slots. |
| **Blocker for** | Wave 3 MANDATE. If A: INVENTORY_MEDIA M-09/M-10 also resolved (no image needed). |

---

## Summary

| # | Question | Blocker level | Recommendation |
|---|---|---|---|
| D-01 | Blog pagination | Low (cosmetic) | A — keep 10/page |
| D-02 | Yoast title template | **P0 — must resolve** | B (Unless) or C (nimrod.bio) |
| D-03 | Placeholder strip timing | High — drives Wave 3 scope | A if Wave 3 fills, else B |
| D-04 | SFA stale home link | P1 | B (external URL) or A (remove) |
| D-05 | /about/ content | P1 if B | C (defer) unless nimrod-book material ready |
| D-06 | harish2021 broken asset | Low | A (remove img tag) |
| D-07 | seed-t7-* entries | P1 | A (delete) |

---

## D-08 — T2 related posts band (informational, no decision)

| field | value |
|---|---|
| **Question** | Service pages (T2) show "posts will appear here after Wave 4" empty band. No decision needed — this auto-populates when real post content (Wave 3) is assigned the correct world taxonomy terms. |
| **Context** | §8 item 5 from Wave 1. The band pulls posts by `world` taxonomy. Once T-01..T-11 placeholder posts are filled with real content and tagged correctly, the bands will populate automatically. |
| **Action** | None in Wave 2. Wave 3 MANDATE will include taxonomy term assignment per post. Verify in Wave 4 QA. |
| **Blocker for** | Wave 3 taxonomy assignment (team_10). Covered automatically. |

---

## D-09 — Lighthouse / performance baseline (informational, V300)

| field | value |
|---|---|
| **Question** | Lighthouse home page regression from Wave 1: Performance 67 (−22 vs baseline 89). No decision needed for cutover — deferred to V300 per COMPLETION_CONTENT_PHASE v1.0.0 §5.2. |
| **Context** | §8 item 12 from Wave 1. Expected from 843-file media weight. Production will benefit from Cloudflare + uPress SuperCache. Wave 4 will re-capture Lighthouse score on dev post-fill as a baseline, but optimization is V300. |
| **Action** | Wave 4 MANDATE will capture post-fill Lighthouse score on dev. Production Lighthouse audit = V300 task. No Wave 2 team_00 action. |
| **Blocker for** | None (V300). |

---

Please respond with choices for D-01 through D-07. For D-02 and D-04 I need a specific value (title template string / SFA URL).

— team_110 (orchestrator · Wave 2) — 2026-05-28
