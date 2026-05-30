---
type: CROSS_DOMAIN_RESPONSE
from: team_100 (nimrod-bio spoke)
to: team_100 (SmallFarmsAgents spoke)
for_hub: true
target_domain: SmallFarmsAgents
route_back_to: "SFA _COMMUNICATION/team_100/SFA-S003-P004-WP-CB-1/"
ref_request: "CROSS-DOMAIN ROUTING PROMPT — SFA → nimrod-bio (Crop Book AssumptionField posts)"
date: 2026-05-30
---

# Response — 2 Crop Book AssumptionField blog posts CREATED

Both placeholder posts are created, **published**, and live on the dev site. Hebrew/RTL, with a heading + 3 short placeholder paragraphs + a "תוכן מלא בקרוב" note. Tagged `world=soil` + `flow_style=brief` so they render correctly in the site's post template.

## ⚠️ IMPORTANT — permalink correction (hard-code THESE URLs)
The nimrod-bio permalink structure is **`/blog/%postname%/`** (validated since P001), so posts live under **`/blog/`**, not at the site root. Your request anticipated this ("adjust the path prefix … e.g. `/blog/<slug>/`"). The slugs are exactly as proposed; only the `/blog/` prefix is added.

| Field | germination_rate | bed_width |
|---|---|---|
| **Final canonical URL (hard-code this)** | `https://nimrod.bio/blog/seed-germination-rate/` | `https://nimrod.bio/blog/garden-bed-width-80cm/` |
| **Current temp working URL (preview/QA)** | `http://nimrod-bio-2026.s887.upress.link/blog/seed-germination-rate/` | `http://nimrod-bio-2026.s887.upress.link/blog/garden-bed-width-80cm/` |
| **Slug (permanent)** | `seed-germination-rate` | `garden-bed-width-80cm` |
| **Post ID** | 1051 | 1052 |
| **Status** | publish (HTTP 200) | publish (HTTP 200) |

So in `organic_market_agent/crop_book/assumptions.py`:
```python
germination_rate.post_url = "https://nimrod.bio/blog/seed-germination-rate/"
bed_width.post_url        = "https://nimrod.bio/blog/garden-bed-width-80cm/"
```

## Slug permanence — confirmed
- The slugs are **fixed and permanent**; they will **not** auto-change.
- The temp→main domain move is an **in-place domain swap on the same WordPress install** (the dev site IS the V200 rebuild that becomes `nimrod.bio` at cutover) — the path `/blog/<slug>/` is identical before and after; only the host changes. **No slug change, no auto-redirect** affects these new posts (the site's redirect layer only maps *legacy* pre-V200 URLs).
- Caveat: if an editor manually renames a slug later, WordPress would change the URL — flag us before any rename of these two so we keep them stable for your hard-coded links.

## Notes
- Content is **placeholder** ("תוכן מלא בקרוב"); real content is a later content-precision task. The URLs are stable now, so no rewrite is needed when the site moves to the main domain.
- Until cutover, preview via the temp working URLs above. The `nimrod.bio/blog/...` form goes live the moment the site cuts over to the main domain (P005-WP002, currently deferred per team_00).

*team_100 (nimrod-bio) · 2026-05-30 · for_hub → route to SFA*
