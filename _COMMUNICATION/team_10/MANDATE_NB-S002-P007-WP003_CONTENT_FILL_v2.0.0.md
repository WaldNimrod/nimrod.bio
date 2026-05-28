---
id: MANDATE_NB-S002-P007-WP003_CONTENT_FILL
type: BUILD_MANDATE
from: team_110 (orchestrator · Wave 2 complete)
to: team_10 (Builder · Cursor Composer)
cc: team_00 (content owner), team_110 (orchestrator)
project: nimrod-bio
milestone: V200
wp_id: NB-S002-P007-WP003
wave: 3 of 4 (P007)
date: 2026-05-28
version: v2.0.0
supersedes: MANDATE_NB-S002-P007-WP003_CONTENT_FILL_v1.0.0.md
priority: P1
status: ACTIVE — all blockers resolved
engine: Cursor Composer (REST + WP admin)
dev_url: http://nimrod-bio-2026.s887.upress.link
wp_rest_base: http://nimrod-bio-2026.s887.upress.link/wp-json/wp/v2
content_method: GENERATE (best-effort first draft per team_10)
cutover_gate: ALL content complete + 0 placeholder markers before Wave 4
---

# Wave 3 MANDATE — Full Content Fill + Architecture Completion (v2.0.0)

## 1. Mission

Fill ALL content on dev site to production-ready state. **Cutover is gated on this wave — the site ships only when complete.** No placeholder content goes to production.

Content method: **GENERATE** — team_10 produces first-draft copy "כמיטב יכולתכם" per team_00 directive 2026-05-28. team_00 reviews and edits post-fill in WP admin before signing off Wave 4.

## 2. Prerequisites (all met before dispatch)

| # | Item | Status |
|---|---|---|
| RESPONSE_INVENTORY_P007_2026-05-28_v1.0.0.md | team_00 decisions D-01..D-07 | ✅ |
| SFA URL | `https://sfa.nimrod.bio/` | ✅ |
| TikTrack URL | `https://tt.nimrod.bio/` | ✅ |
| SFA page type | project CPT | ✅ |
| content_method | GENERATE (team_10) | ✅ |
| Wave 1 QA report | baseline for AT-Q comparison | ✅ |

## 3. Inputs (read before starting)

| # | Path |
|---|---|
| 1 | `_COMMUNICATION/team_00/RESPONSE_INVENTORY_P007_2026-05-28_v1.0.0.md` |
| 2 | `_COMMUNICATION/team_00/INVENTORY_TEXTS_NB-S002-P007-WP002_2026-05-28_v1.0.0.md` |
| 3 | `_COMMUNICATION/team_00/INVENTORY_MEDIA_NB-S002-P007-WP002_2026-05-28_v1.0.0.md` |
| 4 | `_COMMUNICATION/team_00/INVENTORY_DECISIONS_NB-S002-P007-WP002_2026-05-28_v1.0.0.md` |
| 5 | `_COMMUNICATION/team_50/MCP_QA_REPORT_NB-S002-P007-WP001_2026-05-28_v1.0.0.md` |

## 4. Scope — 4 sub-batches

### Sub-batch A — Structural / Architecture (FIRST, blocks B+C)

Execute on branch `feat/p007-wp003-batch-a`.

#### A-1: DELETE harish2021 post

```
DELETE /wp-json/wp/v2/posts/67?force=true
```
Verifies: `GET /wp-json/wp/v2/posts/67` → 404. Post count: 33 → 32.

---

#### A-2: CREATE SFA project CPT entry

```
POST /wp-json/wp/v2/projects
{
  "title": "SmallFarmsAgents — מערכת ניהול חווה קהילתית",
  "slug": "sfa",
  "status": "publish",
  "content": "<GENERATED — see §4 A-2 context below>",
  "meta": {
    "_nb_external_url": "https://sfa.nimrod.bio/",
    "_nb_cta_label": "כנס למערכת"
  }
}
```

**Context for generation (A-2):**
SmallFarmsAgents (SFA) is a community management system for small organic farms and market gardeners. It includes: knowledge base for farmers and gardeners (growing guides, pest management, scheduling), farm operations management (tasks, harvests, sales), community coordination between small farms (equipment sharing, logistics, knowledge exchange). The vision is a full-stack system that grows from a single farm tool into a community platform. The system is live at `https://sfa.nimrod.bio/`. This is one of two flagship shelf software products.

**Content target:** 3–4 paragraphs in Hebrew. Tone: technical but accessible. Mention: קהילה, חוות קטנות, ניהול ידע, קוד פתוח. Include `data-nb-external-cta="true"` marker + "כנס למערכת →" CTA linking to `https://sfa.nimrod.bio/`.

**World taxonomy:** assign terms `soil`, `code` (by term slug).
**flow_style:** assign `lead`.
**featured_media:** 0 for now (media fill track).

Verify: `GET /wp-json/wp/v2/projects?slug=sfa` returns 200 with content.

---

#### A-3: UPDATE T7 home — SFA link replacement

The current T7 home has a stale `<a href="/services/sfa/">הצטרף ל-SFA</a>` (Wave 1 F-005).

**Find the location:** inspect theme files OR FSE blocks for the SFA CTA. Likely in:
- `wp-content/themes/unless/templates/home.html` (FSE block)
- OR a WP navigation menu item
- OR a hardcoded block in the home page post/page

**Replace with double-link pattern:**
- CTA 1: "ראה פרויקט SFA" → `/project/sfa/` (internal page — created in A-2)
- CTA 2: "כנס למערכת" → `https://sfa.nimrod.bio/` (external, opens in new tab)

If the CTA is in a theme file: EDIT the file. If in FSE blocks: use WP REST to update the home page post blocks. If in a menu: REST update the nav menu item.

**Verify:** Wave 1 screenshot `t7-home_1440.png` baseline → new screenshot shows double-link where single dead link was. No 404 on click to `/project/sfa/`.

---

#### A-4: UPDATE TikTrack service — generate content + double-link

TikTrack service ID 29 (slug: `tiktrack`) — currently body = 0 chars, featured_media = 0.

```
PATCH /wp-json/wp/v2/services/29
{
  "content": "<GENERATED — see §4 A-4 context below>",
  "meta": {
    "_nb_external_url": "https://tt.nimrod.bio/",
    "_nb_cta_label": "כנס למערכת"
  }
}
```

**Context for generation (A-4):**
TikTrack (TikTrack Phoenix) is a time and activity tracking platform. Originally built for field work and agricultural operations tracking, now serving as a general-purpose activity logger with reporting. Live at `https://tt.nimrod.bio/`. One of two flagship shelf software products alongside SFA.

**Content target:** 2–3 paragraphs Hebrew. Mention: מעקב פעילות, דיווח, שטח. Include "כנס למערכת →" CTA → `https://tt.nimrod.bio/`. Same double-link pattern as SFA.

**On T7 home:** check if TikTrack has a home CTA (similar to SFA). If so, apply same double-link update. If not: no home change needed for TikTrack (service page is `/services/tiktrack/`).

---

#### A-5: SET Yoast title template

D-02: `%title% · נמרוד ולד`

In Yoast SEO settings (WP admin: SEO → General → Site Basics):
- Site title: `נמרוד ולד`
- Separator: `·`
- Title template for posts: `%%title%% · נמרוד ולד`
- Title template for pages: `%%title%% · נמרוד ולד`

If configurable via REST: use `POST /wp-json/wp/v2/settings` to set `title` = `נמרוד ולד`. Yoast templates may require direct DB option update (wp_options: `wpseo_titles`).

Verify: browser title on T7 home shows `nimrod.bio · נמרוד ולד` or equivalent.

---

**Sub-batch A deliverable:** `_COMMUNICATION/team_110/COMPLETION_NB-S002-P007-WP003_BATCH-A_<date>_v1.0.0.md`

Merge to `main` after team_110 ACKs.

---

### Sub-batch B — Post content fill (12 posts + 1 new)

Execute on branch `feat/p007-wp003-batch-b`. Depends on Sub-batch A merge.

For EACH post: `PATCH /wp-json/wp/v2/posts/{id}` with generated Hebrew content. Remove `data-nb-placeholder` div from body. Strip `_nb_placeholder` post_meta flag.

**Idempotency check:** before patching, verify current body still has placeholder marker. If already filled → skip + log.

---

#### B-01: agents-os (ID 120)

**Context:** Agents-OS (AOS) is the governance and orchestration infrastructure underlying all of nimrod's projects. It coordinates multiple AI agent engines (Cursor, Claude, Codex) across different project domains. It defines team roles, work packages, gates, and inter-team communication protocols via a lean-kit methodology. Not a product — the methodology itself. `world: code + know`, `flow_style: lead`.

**Content target:** 3–4 paragraphs. Topics: ריבוי סוכנים, ממשל, קוד פתוח, תשתית. Hebrew.

---

#### B-02: eyal-amit-2026 (ID 121)

**Context:** WordPress site rebuild for Eyal Amit (2026 edition). A web presence project — design, content, and technical build in WordPress FSE. `world: code`, `flow_style: feature`.

**Content target:** 1–2 paragraphs. Topics: WordPress, עיצוב ובנייה, פרויקט לקוח. Hebrew.

---

#### B-03: israel-microgreens (ID 122)

**Context:** Container hydroponics project — a shipping container converted to a hydroponic growing unit for microgreens and specialty greens. Combines agriculture, engineering, and digital systems. One of the most important projects, connecting soil + know + code worlds. `world: soil + know + code`, `flow_style: lead`.

**Content target:** 3–4 paragraphs. Topics: מכולה הידרופונית, מיקרו-ירוקים, טכנולוגיה חקלאית. Hebrew.

---

#### B-04: shaked-wg-agent (ID 123)

**Context:** AI search agent for Basel watch group (שייקד WG) — searches for watch listings, tracks prices, sends alerts. Purpose-built agent for a niche domain. `world: code`, `flow_style: feature`.

**Content target:** 1–2 paragraphs. Topics: סוכן חיפוש, שעונים, אוטומציה. Hebrew.

---

#### B-05: smallfarmsagents (ID 124)

**Context:** Same system as SFA (see A-2 above) — blog post angle is the VISION and community story, not the product description. The blog post explores the conceptual journey: from a single farm tool to a community intelligence platform. `world: soil + know + code`, `flow_style: lead`.

**Content target:** 3–4 paragraphs. Narrative/vision tone — different from the project CPT page (A-2). Topics: חזון, קהילה, חקלאות קטנה, בינה מלאכותית. Hebrew.

---

#### B-06: tiktrack-phoenix (ID 125)

**Context:** TikTrack Phoenix — the story/history of the TikTrack project and its evolution. Blog post angle: the development journey, lessons learned, what "Phoenix" means (rebuilt). Different from the service page (A-4). `world: code`, `flow_style: feature`.

**Content target:** 2–3 paragraphs. Topics: פיתוח תוכנה, מעקב, לקחים. Hebrew.

---

#### B-07: agros-insite (ID 126)

**Context:** Agricultural data intelligence project — collects and analyzes farm operational data to surface insights. Connects field work to data-driven decisions. `world: soil`, `flow_style: feature`.

**Content target:** 2–3 paragraphs. Topics: נתוני שטח, ניתוח, תובנות חקלאיות. Hebrew.

---

#### B-08: capra-mio (ID 127)

**Context:** AI sailing trip planning agent — plans sailing itineraries, handles weather routing, manages provisioning lists. Purpose-built for Mediterranean sailing. Named after the Capra Mio project (Nimrod's sailing context). `world: code`, `flow_style: feature`.

**Content target:** 1–2 paragraphs. Topics: שיט, תכנון מסלול, סוכן AI. Hebrew.

---

#### B-09: אנטרופיה (ID 136)

**Context:** A reflective/philosophical post about entropy — order and disorder in agricultural and technical systems. Why things tend toward chaos and what that means for building resilient systems (both farms and code). `world: know`, `flow_style: typo`.

**Content target:** 3–4 paragraphs. Philosophical/essay tone. Topics: אנטרופיה, סדר ואי-סדר, מערכות. Hebrew. More literary than technical.

---

#### B-10: אלה-אם-unless (ID 137)

**Context:** Body already migrated from original blog. Verify: (a) Yoast title renders "Unless" correctly on this specific post, (b) `data-nb-placeholder` marker is NOT present (original migrated content, not a placeholder). If placeholder marker present → remove. If body is real content → SKIP body update.

`world: know`, `flow_style: feature`.

**Action:** verify only. If placeholder → replace with proper migration content or essay. If real → skip.

---

#### B-11: back-to-mud (ID 138)

**Context:** A post about "returning to the soil" — reflections on the relationship between digital work and physical farming. The slug was preserved as a stub (Q5=D decision from Intake). Now needs real content. `world: soil`, `flow_style: typo`.

**Content target:** 2–3 paragraphs. Tone: personal/reflective. Topics: קרקע, חזרה לאדמה, איזון דיגיטל-פיזי. Hebrew.

---

#### B-12: NEW POST — "הספר עלי — קונטקסט כבסיס לעבודה נכונה עם LLM"

```
POST /wp-json/wp/v2/posts
{
  "title": "הספר עלי — ארכיון קונטקסט לעבודה נכונה עם LLM",
  "slug": "nimrod-context-book",
  "status": "publish",
  "content": "<GENERATED — see context below>",
  "data-nb-placeholder": false
}
```

**Context:** A post about the concept of building a personal "context book" — a structured document about oneself (background, projects, values, working style) that serves as context injection for LLM sessions. The idea: instead of re-explaining yourself each session, maintain a living document (the "book") that bootstraps any AI collaboration. The nimrod-book project embodies this. `world: know + code`, `flow_style: feature`.

**Content target:** 3–4 paragraphs. Topics: הנדסת קונטקסט, זיכרון LLM, תיעוד עצמי, ספר הקשר. Hebrew. Technical + personal tone.

**World taxonomy:** `know + code`. `flow_style: feature`.

---

**Sub-batch B deliverable:** `_COMMUNICATION/team_110/COMPLETION_NB-S002-P007-WP003_BATCH-B_<date>_v1.0.0.md`

List each post ID: status (FILLED / SKIP / FAIL) + char count before/after.

---

### Sub-batch C — Service + seed content

Execute on branch `feat/p007-wp003-batch-c`. Can run in parallel with sub-batch B.

#### C-1: seed-t7-produce (ID 42)

```
PATCH /wp-json/wp/v2/services/42
{ "content": "<GENERATED>" }
```

**Context:** Produce service — marketing description of Nimrod's professional produce offering (specialty greens, microgreens, seasonal vegetables). Mirrors the real `produce` service (ID 22) but this is a T7 template variant. If ID 42 duplicates ID 22 functionally → clone ID 22 body.

**Content target:** 2–3 paragraphs. Topics: תוצרת מקצועית, עונתי, איכות. Hebrew.

---

#### C-2: seed-t7-consulting-hydro (ID 43)

```
PATCH /wp-json/wp/v2/services/43
{ "content": "<GENERATED>" }
```

**Context:** Hydroponic consulting service — greenhouse design consultation, hydroponic system planning, crop selection for controlled environments. Mirror of ID 26 (`consulting-hydro`). If ID 43 duplicates ID 26 → clone body.

**Content target:** 2–3 paragraphs. Topics: ייעוץ חממות, הידרופוניקה, תכנון. Hebrew.

---

**Sub-batch C deliverable:** `_COMMUNICATION/team_110/COMPLETION_NB-S002-P007-WP003_BATCH-C_<date>_v1.0.0.md`

---

### Sub-batch D — /about/ placeholder + media pass

Execute on branch `feat/p007-wp003-batch-d`. Can run in parallel with B+C.

#### D-1: /about/ page — placeholder update

**/about/ final content** is coming from a nimrod-book domain session (parallel track). team_10 does NOT generate the about page body. Instead:

1. Ensure current /about/ body does NOT have `data-nb-placeholder` marker (it's a migrated page).
2. Add a `<!-- nimrod-book-session-pending -->` HTML comment at the top of the body as a marker for easy identification when the nimrod-book session delivers content.
3. Do NOT change the visible content — keep existing V100 body intact until nimrod-book session delivers.

Verify: `GET /wp-json/wp/v2/pages?slug=about` → body present, comment injected.

---

#### D-2: Media — set featured images from existing library

From INVENTORY_MEDIA — 843 files are in the library. For posts/services/projects where a relevant image MAY already exist in the library from the 685-file migration:

Use `GET /wp-json/wp/v2/media?search={keyword}&per_page=5` to search. If a relevant image is found → `PATCH` the `featured_media` field on the post/service/project.

Priority targets (search keywords):
- Posts with `world: soil` → search `גינה`, `שדה`, `ירוק`
- Posts with `world: code` → search `מסך`, `קוד`
- Projects → search by project name fragment
- Services → search by service name fragment

If no match found → leave `featured_media=0` (team_00 provides images post-fill review).

Log: `docs/qa/p007-wp003-media-assignment.json` — per-item: slug → searched → found? → media_id (or null).

---

**Sub-batch D deliverable:** `_COMMUNICATION/team_110/COMPLETION_NB-S002-P007-WP003_BATCH-D_<date>_v1.0.0.md`

---

## 5. Acceptance tests

| # | Criterion | PASS condition |
|---|---|---|
| AT-F1 | All T-01..T-12 + NEW post filled | WP REST body length > 300 chars per post; no placeholder body |
| AT-F2 | 0 × `data-nb-placeholder` markers remaining | `GET /wp-json/wp/v2/posts?per_page=100` → 0 posts with placeholder div in content |
| AT-F3 | SFA project page live | `GET /wp-json/wp/v2/projects?slug=sfa` → 200 + body > 200 chars |
| AT-F4 | TikTrack service body filled | `/services/tiktrack/` body > 200 chars; external CTA present |
| AT-F5 | T7 home double-link working | `/project/sfa/` returns 200; `https://sfa.nimrod.bio/` reachable |
| AT-F6 | harish2021 deleted | `GET /wp-json/wp/v2/posts/67` → 404 |
| AT-F7 | Yoast title | Browser tab on T7 home shows `· נמרוד ולד` suffix |
| AT-F8 | Dev URL stable | `GET http://nimrod-bio-2026.s887.upress.link/` → 200, no 5xx across all 4 batches |
| AT-F9 | New context-book post live | `GET /wp-json/wp/v2/posts?slug=nimrod-context-book` → 200 + body |
| AT-F10 | Media assignment log | `docs/qa/p007-wp003-media-assignment.json` committed |

## 6. STOP conditions — escalate to team_110 (orchestrator)

- WP REST 3× consecutive failures on same endpoint → STOP + rollback sub-batch branch
- T7 home CTA location not identifiable in theme or FSE blocks → STOP, escalate (team_110 will consult team_00)
- Yoast title settings inaccessible via REST → STOP, team_110 routes to team_00 for WP admin manual set
- Dev URL 5xx after any batch → STOP, revert + investigate
- SFA project CPT registration returns 4xx (CPT REST disabled) → STOP, escalate

## 7. Out-of-scope

- Featured image photo sourcing (team_00 provides post-review; team_10 only assigns from existing library)
- /about/ final copy (nimrod-book session, parallel track)
- Theme CSS / PHP edits beyond the SFA CTA block update (LOCKED)
- Wave 4 validation (team_50 + team_190 territory)
- Production cutover (team_99)

## 8. Branching + commit pattern

```
feat/p007-wp003-batch-a  → PR to main (team_110 reviews)
feat/p007-wp003-batch-b  → PR to main (team_110 reviews after B)
feat/p007-wp003-batch-c  → PR to main (can merge with B)
feat/p007-wp003-batch-d  → PR to main (can merge with C)
```

Commit format: `content(V200-P007-WP003/batch-{A..D}): {item} — {status}`

## 9. Activation prompt (paste to new Cursor session as team_10)

```
═══════════════════════════════════════════════════════════════
TEAM 10 — Builder (Cursor Composer)
ACTIVATION — V200 Wave 3 · Full Content Fill + Architecture
═══════════════════════════════════════════════════════════════

זהות
────
- Team ID: team_10
- Engine: Cursor Composer
- Role: Builder (GCR-001 binding)
- Governance: /Users/nimrod/Documents/agents-os/_aos/governance/team_10.md
- Wave: 3 of 4 in P007 — Pre-Cutover Completion

קונטקסט
───────
- Project: nimrod-bio · Milestone: V200 · sub-phase 2 · Wave 3 ACTIVE
- Dev URL: http://nimrod-bio-2026.s887.upress.link
- State: 33 posts (11 placeholder) · 10 services · 5 projects · 843 media
- content_method: GENERATE (best-effort first draft in Hebrew, per team_00 directive)
- Cutover gate: site ships ONLY when all content complete + 0 placeholder markers

המנדט
─────
_COMMUNICATION/team_10/MANDATE_NB-S002-P007-WP003_CONTENT_FILL_v2.0.0.md
(קרא 100% — 4 sub-batches, 10 ATs, explicit STOP conditions)

המשימה
──────
1. /AOS_mail
2. קרא MANDATE v2.0.0 במלואו + RESPONSE_INVENTORY_P007
3. בצע Sub-batch A (structural/architecture) FIRST — blocks B+C
4. בצע B (posts), C (services), D (media/about) — B+C+D parallel
5. הפק COMPLETION per batch → report ל-team_110

Out of scope: theme CSS, Wave 4 QA, /about/ final copy, media sourcing.
ETA: ~4-6 שעות.
═══════════════════════════════════════════════════════════════
```

— team_110 (orchestrator · Wave 2 complete) — 2026-05-28
