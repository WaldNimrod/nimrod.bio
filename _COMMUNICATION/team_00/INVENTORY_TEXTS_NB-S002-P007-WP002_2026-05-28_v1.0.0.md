---
type: INVENTORY_TEXTS
from: team_110 (Domain Architect / P007 orchestrator · Wave 2)
to: team_00 (Principal — fill or downgrade each row)
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

# Inventory — Texts · NB-S002-P007-WP002

## How to use this list

For each row, mark one of:
- **FILL** — provide content (paste below the row or in a reply)
- **ACCEPT-AS-IS** — current state is good enough for cutover
- **DEFER-V300** — publish current state; address post-cutover

team_110 will record your responses in RESPONSE_INVENTORY_P007.

---

## Group A — Placeholder post bodies (11 posts)

These posts were created in P006-WP001 with `data-nb-placeholder="true"` marker and lorem-style body copy. Each needs either real copy from you or explicit ACCEPT-AS-IS for cutover.

All 11 posts: featured_media=0 (grey thumbnail on blog index — see INVENTORY_MEDIA for that track).

| # | slug | page URL | current state | required | priority | effort |
|---|---|---|---|---|---|---|
| T-01 | agents-os | /blog/agents-os/ | Placeholder: lorem body, title "Agents-OS — Governance Framework" | Real 2-3 paragraph body. World: code + know. flow_style: lead. Source: team_00 bullets → team_10 expands | P1 | M |
| T-02 | eyal-amit-2026 | /blog/eyal-amit-2026/ | Placeholder body | Real 1-2 paragraph body. World: code. flow_style: feature. Source: team_00 | P1 | S |
| T-03 | israel-microgreens | /blog/israel-microgreens/ | Placeholder body | Real body — הידרופוניקה, מכולה, קהילה. World: soil+know+code. flow_style: lead | P1 | M |
| T-04 | shaked-wg-agent | /blog/shaked-wg-agent/ | Placeholder body | Real 1-2 paragraph body. World: code. flow_style: feature | P1 | S |
| T-05 | smallfarmsagents | /blog/smallfarmsagents/ | Placeholder body | Real body — חזון מערכת + knowledge base. World: soil+know+code. flow_style: lead | P1 | M |
| T-06 | tiktrack-phoenix | /blog/tiktrack-phoenix/ | Placeholder body | Real 1-2 paragraph body. World: code. flow_style: feature | P1 | S |
| T-07 | agros-insite | /blog/agros-insite/ | Placeholder body | Real 1-2 paragraph body. World: soil. flow_style: feature | P1 | S |
| T-08 | capra-mio | /blog/capra-mio/ | Placeholder body | Real 1-2 paragraph body. World: code. flow_style: feature | P1 | S |
| T-09 | אנטרופיה | /blog/אנטרופיה/ | Placeholder body | Real essay/prose body. World: know. flow_style: typo or feature | P1 | M |
| T-10 | אלה-אם-unless | /blog/אלה-אם-unless/ | Body exists (migrated). Tagline "Unless" locked via mu-plugin. | Verify Yoast title renders "Unless" on this post specifically (AT-1 resolved — confirm) | P2 | S |
| T-11 | back-to-mud | /blog/back-to-mud/ | Placeholder stub (Q5=D, stub created to preserve slug) | Real body or ACCEPT-AS-IS stub for cutover | P2 | S |

**Content sourcing question (from Intake Q2, not locked):**
Which method for T-01..T-09?
- A: `bullets_we_expand_together` — you send bullets per post, team_10 expands to paragraphs
- B: `full_co_authoring` — you and team_110 write in real-time
- C: `drafts_ready_in_doc` — you have drafts somewhere; point to them

---

## Group B — Pages

| # | slug | page URL | current state | required | priority | effort |
|---|---|---|---|---|---|---|
| T-12 | about | /about/ | Existing content from V100. nimrod-book material (team_00 2026-05-26: "זה הבסיס לעמוד אודות") not yet integrated | Provide nimrod-book excerpt or confirm current /about/ text is acceptable for cutover | P1 | M |
| T-13 | heritage | /about/heritage/ | Existing content | Review and confirm current text is cutover-ready, or supply corrections | P2 | S |
| T-14 | contact | /contact/ | Functional. Error state shows `?status=error` — not `?status=invalid` | Docs alignment only (no code change needed). Confirm `?status=error` is correct and we update any public-facing copy | P3 | S |

---

## Group C — SEO meta / Yoast title template

| # | context | current state | required | priority | effort |
|---|---|---|---|---|---|
| T-15 | Site title / Yoast separator | Dev shows "V200 dev · Unless" suffix in browser tab | Confirm production Yoast title template. Recommended: `%title% · nimrod.bio` or `%title% · Unless`. **Decision T-15 overlaps with D-02 in INVENTORY_DECISIONS.** | P0 (must resolve before cutover) | S |
| T-16 | SFA home link copy | T7 home CTA: "הצטרף ל-SFA" → `/services/sfa/` (deleted entity, stale) | Copy for replacement CTA or confirm removal. If replaced: what URL and what label? Decision in INVENTORY_DECISIONS D-04. | P1 | S |

---

## Group D — Service / Project page bodies

From Wave 1: T2 services show TBD placeholder on hero image area but body text is present. No text-inventory action needed UNLESS team_00 wants to update service descriptions.

| # | slug | current body state | action |
|---|---|---|---|
| T-17 | seed-t7-produce (ID 42) | Empty content | Decision item D-07 in INVENTORY_DECISIONS: delete or keep? |
| T-18 | seed-t7-consulting-hydro (ID 43) | Empty content | Decision item D-07: delete or keep? |

---

## Summary

| Group | Rows | Min fills needed for cutover |
|---|---|---|
| A — Placeholder posts | 11 | T-01..T-09 need real copy (or DEFER-V300 explicitly) |
| B — Pages | 3 | T-12 about-page integration is P1 |
| C — SEO/meta | 2 | T-15 must resolve before cutover |
| D — Seed services | 2 | Depends on D-07 decision |
| **Total** | **18** | |

— team_110 (orchestrator · Wave 2) — 2026-05-28
