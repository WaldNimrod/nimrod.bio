---
type: DECISION_BRIEF
from: team_110 (Domain Architect · cursor-composer-2)
to: team_00 (Nimrod, Principal)
project: nimrod-bio
milestone: V200
phase: Content Expansion (pre-cutover)
date: 2026-05-26
version: v1.0.0
status: SUPERSEDED
superseded_by: DECISION_BRIEF_V200_CONTENT_PHASE_INTAKE_2026-05-26_v1.1.0.md
superseded_reason: team_00 requested Hebrew rendering per AOS_decide canon (display rule)
mechanism: AOS_decide brief (file-based, canonical form per feedback_canonical_prompts)
companion_to:
  - _COMMUNICATION/team_110/MISSION_BRIEF_CONTENT_PHASE_2026-05-26_v1.0.0.md
  - _COMMUNICATION/team_110/HANDOFF_SELF_110_GENERAL_2026-05-25_v1.md
authorization: team_00 directive 2026-05-26 — content expansion on dev URL before cutover
priority: HIGH
---

# DECISION BRIEF — V200 Content Expansion · Intake (Q1–Q11)

## 1. Identity

- **From:** `team_110` — Domain Architect, engine `cursor-composer-2`, GATE_2 architecture authority.
- **To:** `team_00` — Principal (Nimrod).
- **Project:** `nimrod-bio`. **Milestone:** `V200` (Site Rebuild).
- **Phase:** Content Expansion — pre-cutover (P006 program candidate, batches under P006-WPnn).
- **Scope of this brief:** 11 discovery questions blocking start of Phase B (architecture pass per content item) and gating P005-WP002 cutover unfreeze.

## 2. Governance

- **Gate open:** GATE_2 (architecture approval) on a *content-as-architecture* phase: I must decide which items fit existing CPT/templates as data-only vs. which require template extension or new design (which would trigger a GCR to team_35).
- **Iron Rules in force:**
  - #4 — Single logical writer on `roadmap.yaml` (team_100 will register any new P006 WPs on my behalf).
  - #6 — Inter-team communication via canonical artifacts in `_COMMUNICATION/`.
  - #7 — API-only structured mutations (db online — confirmed by hub canonical status). All new posts/CPT instances go through WP REST or admin UI, never direct DB.
  - Domain rule — no design-system changes (`system.css` / `shell.css` / `theme.json` locked by team_35); no new plugins unless infrastructure-class; Hebrew slugs preserved.
- **Authority for this decision:** team_00 — content is principal-owned; team_110 frames options and architecture impact only.
- **What this unblocks:** authoring `CONTENT_PHASE_INTAKE_*.md`, opening P006 WPs, issuing MANDATE batches to team_10, and (downstream) the deferred P005-WP002 cutover.

## 3. Task

Provide directional answers (or "skip / V300") to the 11 intake questions below so I can produce a sequenced content-batch plan with calendar estimate, and identify which questions need their own dedicated Decision Brief vs. which I can absorb into LOD400 directly.

## 4. Context

**State of V200 (2026-05-26):**

- 12/13 WPs COMPLETE on dev `https://nimrod-bio-2026.s887.upress.link` (sanity check this session: theme stamp `wp-theme-nimrod-bio-2026` rendered; validate_aos 32 PASS / 0 FAIL).
- 7 templates active (T7/T1×3/T2/T3/T4/T5/T8). 22 migrated posts under `/blog/`, page `/shook/`, 6 seed CPT instances (3 services + 3 projects), 4 sample posts.
- Redirect layer: 23 × 301 + 6 × 410 enforced via MU plugin + portable `.htaccess` audit block.
- SMTP operational via `smtp.inbox.co.il`:587 TLS (post password-rotation cycle 1.1, signed CONDITIONAL GO 2026-05-25).
- P005-WP002 cutover LOD400 authored, DEFERRED — frozen until team_110 issues `COMPLETION_CONTENT_PHASE`.

**Carry-forward backlog (the seed of this brief):**

5 TBC content blocks from team_35 design package §4: **Q-05** restaurants · **Q-NEW-03** "Unless" tagline · **Q-11** Mezoo branding · **Q-02** SFA pricing model · **Q-03** teaching locations — plus broken link `/blog/back-to-mud/` and Lighthouse uplift (V300 territory — explicitly NOT in this brief).

## 5. Questions

Each question marked **[closed]** (discrete options with tradeoffs) or **[open]** (free-text content; format guidance only). Closed questions get a full options block; open questions request raw content under a structured response format.

---

### Q1 — TBC readiness order *[closed]*

**Question:** Of the 5 TBC content blocks below, which can you commit content for **now (this phase)**, vs. defer to V300, vs. drop entirely? In what order do you want to tackle them?

| Block | Touches | Fit option default |
|---|---|---|
| Q-05 anchor restaurants | T2 produce service + T8 about factrow | data-only, existing template |
| Q-NEW-03 "Unless" tagline | 4+ surface points across site | string-level edit, low cost |
| Q-11 Mezoo branding | T7 footer + T8 about copy | string + possibly logo asset |
| Q-02 SFA pricing | T2 sfa CTA + T1 know copy | string-level + CTA label |
| Q-03 teaching locations | T2 know + T8 about | data, possibly new factrow |

**Architecture impact:** all 5 fit existing templates as data-only IF answers stay within current copy slots. If Q-11 expands Mezoo to its own about subpage → new T8 instance, still no new template (cheap). If Q-05 expands to a dedicated "where to find produce" page → potential new template (medium cost, may need team_35 GCR).

**Recommendation:** answer Q-NEW-03 + Q-02 first (string-level, ~1h builder cost); Q-05 + Q-03 + Q-11 in a single second batch (data + possible factrow). Defer none to V300 — they are blockers for design coherence at launch.

---

### Q2 — New posts queue *[open]*

**Question:** Approximately **how many new posts** do you want to publish before cutover, and what is the source?

**Response format requested:**
- count: integer (or range "5–10")
- source: one of `[drafts_ready_in_doc | bullets_we_expand_together | full_co_authoring | mix]`
- topics (best-effort list, even rough): `["…", "…"]`
- featured_image_status: `[have_for_all | have_for_some | none_use_V300_placeholder]`

**Architecture impact:** posts are pure data on existing T4 template — no architecture decision unless count > ~30 posts (then we'd reconsider blog index pagination tuning, currently set for ≤50). featured_image_status of `none` is acceptable: T4 has a no-image fallback layout. If `mix` with sources differing in length/style, I'll batch by source not by topic to keep builder cycles efficient.

---

### Q3 — Existing post refresh *[open]*

**Question:** Of the 22 migrated posts, which need **editorial refresh** (rewrite, fact update, cross-link insertion) before launch?

**Response format requested:** list of `/blog/<slug>/` URLs (or "none — migration text is good enough for launch") with one-line reason per URL. Optional: tag with `[language | facts | cross_link | imagery]`.

**Architecture impact:** none if refresh is data-only. If you ask to add cross-references between posts and *that introduces a new "related posts" UI element*, that is a template-level change (T4 currently has no related-posts strip) — would need team_35 GCR. Default assumption: any cross-links are inline anchor tags inside post body, no template change.

---

### Q4 — New services / projects *[closed]*

**Question:** Beyond the 6 seeded instances (services: `produce` / `consulting-hydro` / `sfa`; projects: 3 seed slugs), are there **new** services or projects to add now?

**Options:**

#### Option A — no new instances, keep 6
- *What:* publish with the 6 seeds; revisit in V300.
- Advantages: zero CPT data work; cleanest cutover scope; design package §3 said "6 anchors enough for launch".
- Disadvantages: site may feel thin if portfolio is broader in reality; you lose the chance to land first-impression breadth.
- Work cost: 0.
- Risk: low.

#### Option B — add 1–3 services and/or 1–3 projects
- *What:* one batch of new CPT instances via REST POST or admin UI; reuses existing T2/T3 template; data-only.
- Advantages: meaningful breadth uplift for ~half-day builder cost; no template change.
- Disadvantages: requires you to fill ~15 fields per instance (or delegate to me to draft from your bullets); featured images needed (or V300 placeholder).
- Work cost: ~0.25 day per instance for builder + your time to provide content.
- Risk: low.

#### Option C — large catalog import (>3 of each)
- *What:* bulk authoring; consider extending T2/T3 with optional sections.
- Advantages: site reads as fully populated portfolio.
- Disadvantages: triggers an architecture pass on T2/T3 to verify field set still covers the variants; possible team_35 design GCR if any new section type emerges; significant calendar time.
- Work cost: ≥1 day; pushes cutover by 3–5 days.
- Risk: medium (design-spec drift if not coordinated with team_35).

**Recommendation:** Option B (1–3 of each) — best ratio of breadth gain to schedule risk. If C is the real answer, please confirm explicitly so I open a separate Decision Brief on field-set adequacy first.

---

### Q5 — Broken link `/blog/back-to-mud/` *[closed]*

**Question:** This URL is referenced in T7 hero (or T1 lead related-entities — to be confirmed during build). It currently 404s. What is the correct resolution?

**Options:**

#### Option A — write the post now
- *What:* publish a `/blog/back-to-mud/` post; reference becomes valid.
- Advantages: preserves the editorial intent that put the reference there; one more migration-era story closed.
- Disadvantages: requires content from you (subject, body, image); +1 builder item.
- Work cost: small (~1h builder + your authoring).
- Risk: low.

#### Option B — repoint to an existing post
- *What:* edit the template/data so the reference points to a real existing slug under `/blog/`.
- Advantages: zero content cost; quick.
- Disadvantages: the new target must be semantically appropriate — needs you to nominate a substitute.
- Work cost: minutes.
- Risk: low.

#### Option C — remove the reference
- *What:* delete the link from the template/source data.
- Advantages: cleanest; no orphan to maintain.
- Disadvantages: loses a featured cross-reference; may visibly reduce hero density depending on where it was wired.
- Work cost: minutes.
- Risk: low.

**Recommendation:** A if you have a story to tell at `back-to-mud`; otherwise B with you naming the substitute slug. C is last resort — the reference exists because someone meant it to.

---

### Q6 — Anchor restaurants (Q-05) *[open]*

**Question:** Names of **3–5 anchor restaurants** that buy from the produce service. Confirm you have **permission to display each** on the public site (logo / name / city).

**Response format requested:** list of `{name, city, display_permission: yes/no, logo_available: yes/no/V300}`. If permission is not yet obtained, mark `pending` and we'll author the section with placeholders until you confirm.

**Architecture impact:** if all 5 are name-only (no logo), existing T2 factrow takes them as-is — zero template change. If logos are required → new image grid section on T2/T8 — minor template extension, no team_35 GCR needed (logos are content, not design system).

---

### Q7 — "Unless" tagline lock (Q-NEW-03) *[closed]*

**Question:** Is **"Unless"** the final tagline (currently rendered in 4+ places: T7 hero, T8 about, page metas, footer)?

**Options:**

#### Option A — confirm "Unless" final
- *What:* lock the string; no further edits.
- Work cost: zero.
- Advantages: closes the longest-standing TBC; SEO metas stabilize.
- Disadvantages: none, unless you have second thoughts.
- Risk: zero.

#### Option B — alternative tagline
- *What:* you supply the replacement; I run a global replace via WP REST + template edit + meta box update + Yoast meta sync.
- Work cost: ~1h builder.
- Advantages: lands the right brand voice for launch.
- Disadvantages: any change after this becomes more expensive once sitemap is indexed in production.
- Risk: low.

#### Option C — variant per surface (different tagline on T7 vs T8 vs footer)
- *What:* template-aware tagline variants.
- Advantages: tonal precision.
- Disadvantages: adds 4 strings to maintain instead of 1; complicates Yoast meta; may dilute brand recall.
- Work cost: ~2h builder + content authoring.
- Risk: medium (brand consistency).

**Recommendation:** A. If B, please send the new string verbatim in the response snippet. C is discouraged at this scale.

---

### Q8 — Mezoo branding (Q-11) *[closed]*

**Question:** How should **"מיזו" (Mezoo)** appear?

**Options:**

#### Option A — sub-brand mention only (current default)
- *What:* "דיגיטל / מיזו" stays as footer credit + one-line about reference.
- Work cost: zero.
- Risk: zero.

#### Option B — full brand presence with link
- *What:* footer + about link to a Mezoo URL (you supply); possibly small Mezoo logo on T7 footer.
- Work cost: ~30min builder.
- Disadvantages: external-link policy needed (open new tab? noopener?); SEO juice leak from internal links.
- Risk: low.

#### Option C — dedicated Mezoo subpage on this site
- *What:* `/about/mezoo/` T8 subpage describing the relationship.
- Work cost: ~half-day (content + page provision + nav integration).
- Disadvantages: increases site scope; mixes two brand stories.
- Risk: medium (brand muddiness).

**Recommendation:** A unless you actively want Mezoo to be more visible. If B, please supply: target URL, logo path or "use text only", link policy.

---

### Q9 — SFA pricing model (Q-02) *[closed]*

**Question:** What is the **commercial model for SFA** as represented on the site?

**Options:**

#### Option A — declared free (open-source / freely available)
- *What:* T2 sfa CTA reads "Use it" or similar; no pricing copy; no purchase flow.
- Advantages: simplest; no commerce plumbing; matches an open-toolset story.
- Disadvantages: leaves revenue model invisible (or absent).
- Work cost: zero (current default-ish).
- Risk: zero.

#### Option B — commercial-free (free to use, paid services around it)
- *What:* T2 sfa CTA reads "Talk to us"; copy on T1 know explains the "free tool + paid integration" model.
- Advantages: realistic if you provide services *around* SFA; preserves monetization narrative.
- Disadvantages: ~50 words of new copy required; CTA destination = contact form.
- Work cost: ~1h builder + content.
- Risk: low.

#### Option C — paid product
- *What:* pricing page + purchase CTA.
- Disadvantages: requires commerce infra (WooCommerce or equivalent) — out of V200 scope by Iron Rule "no new plugins unless infrastructure-class"; would push cutover by ≥1 week.
- Recommendation: explicitly out of scope for V200 unless you reverse the no-commerce stance.

**Recommendation:** B — most truthful and lowest-cost. A acceptable if you want maximum simplicity at launch.

---

### Q10 — Teaching locations (Q-03) *[open]*

**Question:** Where do you **teach regularly**? (institutions / programs / informal cohorts you want to surface on T2 know + T8 about.)

**Response format requested:** list of `{name, type: [academic | private_program | self_run], city_or_remote, frequency, public_mention_ok: yes/no}`.

**Architecture impact:** if 1–3 locations, fits T8 about factrow + T2 know body — data only. If >5 locations or recurring schedule grid is needed → consider a small "teaching timeline" component on T8 (template extension, ~half-day builder + possibly team_35 design GCR if visual treatment differs from existing factrow).

---

### Q11 — Effort window *[closed]*

**Question:** What is the **calendar window** you want this content phase to occupy? Cutover (P005-WP002) is on hold until you signal complete.

**Options:**

#### Option A — tight (≤1 week)
- *What:* I batch aggressively (2 large batches), defer anything that needs lookup/coordination to V300.
- Advantages: cutover lands fast.
- Disadvantages: less room for back-and-forth on copy quality.
- Risk: low (we already have CONDITIONAL GO; another week doesn't change quality much).

#### Option B — measured (1–3 weeks)
- *What:* 3–4 batches; each ~3–5 content items; full architecture pass per batch.
- Advantages: best content quality; comfortable cadence.
- Disadvantages: cutover slips proportionally.
- Risk: low.

#### Option C — open-ended ("when it's right")
- *What:* no calendar pressure; content drives schedule entirely.
- Advantages: maximum quality.
- Disadvantages: cutover indefinitely deferred; risk of drift / scope expansion.
- Risk: medium (V200 may bleed into V300 imagery work and lose its discrete milestone identity).

**Recommendation:** B — matches the volume implied by Q1+Q2+Q4 if you populate them moderately. I'll plan for ~2 weeks calendar and adjust on Q2/Q4 returns.

---

## 6. Comparison matrix (closed questions only)

| Q | Recommendation | Work cost (builder) | Risk | Iron Rule check |
|---|---|---|---|---|
| Q1 | tackle Q-NEW-03 + Q-02 first; rest in batch 2 | ~3h | low | ✓ all data-only by default |
| Q4 | B (1–3 of each) | ~0.5–1.5 day | low | ✓ data-only |
| Q5 | A or B depending on story availability | ≤1h | low | ✓ |
| Q7 | A (confirm "Unless") | 0 | zero | ✓ |
| Q8 | A (sub-brand only) | 0 | zero | ✓ |
| Q9 | B (commercial-free) | ~1h | low | ✓ no commerce plugin |
| Q11 | B (1–3 weeks measured) | n/a | low | ✓ |

## 7. Open parameters (not yet collapsed; mentioned for awareness)

- **OP-1** — featured-image strategy for new/refreshed posts: any post without an image either uses the no-image T4 layout (works) or waits for V300 image-engine. Default I'll assume: no-image layout. Confirm if you want me to *block* a post from publishing until an image exists.
- **OP-2** — sitemap/Yoast resubmission cadence: after each content batch I'll regenerate the sitemap; if you want a Google Search Console resubmission between batches I'll add it to the cycle.
- **OP-3** — open new program `P006 — Content Expansion` in `_aos/roadmap.yaml`: I'll request team_100 to register it once you confirm Q11 window (so WP count is informed). Default plan: `NB-S002-P006-WP001` through `NB-S002-P006-WPnn` per batch.

## 8. Response snippet

Please copy the block below into chat (or paste into a reply file) and fill. Everything is optional except the questions you have answers for — leave unknowns as `TBD` and I'll re-ask.

```yaml
# DECISION_BRIEF_V200_CONTENT_PHASE_INTAKE_2026-05-26_v1.0.0 — team_00 response
date: 2026-05-26
from: team_00

# ---------- Q1 — TBC readiness order ----------
q1_order: []          # ordered list of [Q-NEW-03, Q-02, Q-05, Q-03, Q-11] (omit any to defer/drop)
q1_deferred_to_v300: []
q1_dropped: []

# ---------- Q2 — New posts ----------
q2_count: 0           # integer
q2_source: ""        # drafts_ready_in_doc | bullets_we_expand_together | full_co_authoring | mix
q2_topics: []
q2_featured_image_status: ""   # have_for_all | have_for_some | none_use_V300_placeholder

# ---------- Q3 — Refresh of 22 migrated posts ----------
q3_refresh:
  - { slug: "", reason: "", tags: [] }
# or:
q3_none: false

# ---------- Q4 — New services / projects ----------
q4_choice: ""        # A | B | C
q4_new_services: []  # list of slugs or labels
q4_new_projects: []

# ---------- Q5 — Broken link /blog/back-to-mud/ ----------
q5_choice: ""        # A | B | C
q5_substitute_slug: ""   # only if B
q5_post_topic: ""        # only if A

# ---------- Q6 — Anchor restaurants ----------
q6_restaurants:
  - { name: "", city: "", display_permission: "", logo_available: "" }

# ---------- Q7 — Tagline ----------
q7_choice: ""        # A (lock Unless) | B (replace) | C (variant)
q7_new_tagline: ""    # only if B
q7_variants: {}       # only if C: { t7_hero: "", t8_about: "", footer: "", meta: "" }

# ---------- Q8 — Mezoo ----------
q8_choice: ""        # A | B | C
q8_mezoo_url: ""     # only if B/C
q8_logo: ""          # only if B/C: path or "text_only"

# ---------- Q9 — SFA pricing ----------
q9_choice: ""        # A | B | (C only if you reverse no-commerce policy)
q9_around_paid_services_copy: ""  # ~50 words if B

# ---------- Q10 — Teaching locations ----------
q10_locations:
  - { name: "", type: "", city_or_remote: "", frequency: "", public_mention_ok: "" }

# ---------- Q11 — Effort window ----------
q11_choice: ""       # A (≤1 week) | B (1–3 weeks) | C (open-ended)

# ---------- Open parameters ----------
op1_block_post_without_image: false
op2_gsc_resubmission_between_batches: false
op3_open_p006_program: true   # default true; set false to keep flat under S002

# ---------- Free-text notes ----------
notes: ""
```

---

## 9. What happens after your response

1. I save your response as `_COMMUNICATION/team_110/CONTENT_PHASE_INTAKE_2026-05-26_v1.0.0.md` (intake artifact).
2. I publish a Phase Plan (A → B → C → D) with batch count and calendar estimate to `_COMMUNICATION/team_00/`.
3. I open the first content batch as `LOD400_CONTENT_BATCH_001.md` under team_110 outputs and issue `MANDATE_CONTENT_BATCH_001.md` to team_10 via canonical artifact.
4. P005-WP002 cutover stays DEFERRED — unblocked only by `COMPLETION_CONTENT_PHASE_*.md` from team_110 after the final batch passes.

— team_110 (cursor-composer-2) — 2026-05-26
