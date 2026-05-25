---
type: MISSION_BRIEF
from: team_100 (nimrodbio_arch — Cursor's Claude · Anthropic)
to: team_110 (Domain Architect · cursor-composer-2)
project: nimrod-bio
milestone: V200
phase: Content Expansion (pre-cutover)
date: 2026-05-26
companion_to: HANDOFF_SELF_110_GENERAL_2026-05-25_v1.md (canonical AOS_handoff artifact)
authorization: team_00 directive 2026-05-26 "ההחלפה תבוצע רק אחרי הרחבת ועדכון התוכן של כל האתר בכתובת הזמנית"
priority: HIGH
---

# MISSION BRIEF — Content Expansion Phase (pre-cutover)

**שלום team_110.** ה-handoff הקאנוני (`HANDOFF_SELF_110_GENERAL_2026-05-25_v1.md`) טוען אותך עם זהות + governance contract. המסמך הזה משלים אותו עם **המשימה הספציפית**: לנהל איתrod (team_00) שלב איסוף ושילוב תוכן באתר nimrod.bio **לפני שה-cutover יבוצע**.

## 1. סטטוס V200 — מה כבר נבנה (ולוקד)

12 מתוך 13 WPs של V200 הושלמו. האתר חי על dev URL `https://nimrod-bio-2026.s887.upress.link` עם:

| שכבה | מצב |
|---|---|
| Theme `nimrod-bio-2026` v0.4.1 | פעיל · custom theme מבוסס system.css v3.3 (locked from team_35 design package Stage 3) |
| **7 templates** | T7 Home (`/`), T1 Worlds × 3 (`/world/{soil,know,code}/`), T2 Service (`/services/{slug}/`), T3 Project (`/project/{slug}/`), T4 Post (`/blog/{slug}/`), T5 Blog index (`/blog/`), T8 Static (`/about/`, `/about/heritage/`, `/contact/`) — כולם רנדרים תוכן אמיתי |
| **CPTs** | `service` + `project` עם 15+ שדות כל אחד (native PHP meta boxes, no plugin) |
| **Taxonomies** | `world` (soil/know/code) + `flow_style` (lead/wide/tall/typo/quote/feature/brief) |
| **Content migrated** | 22 פוסטים (Hebrew slugs preserved under `/blog/`) + 1 page `/shook/` + 6 seed CPT instances (3 services + 3 projects) + 4 sample posts |
| **Redirects** | 23 × 301 + 6 × 410 Gone + 2 keeps — אכיפה דרך MU plugin runtime (`nb-v200-runtime-redirects.php`) + `.htaccess` portable audit block |
| **SEO** | Yoast SEO active, `sitemap_index.xml` רנדר 22 פוסטים |
| **SMTP** | wp-mail-smtp via `smtp.inbox.co.il`:587 TLS · auth `agent@nimrod.bio` · From `n@nimrod.bio` (forwards to `nimrod@mezoo.co`) · contact form A12 PASS |
| **QA** | CUTOVER_READINESS_REPORT signed CONDITIONAL GO by team_190 |

## 2. למה אתה (team_110) ולא team_10 (builder) ישירות

המשימה היא **architecture-led** content phase, לא pure build:
- decide WHAT new content goes where (info architecture)
- decide HOW it fits into existing CPTs/templates (template fit)
- decide WHETHER new templates / sections are needed (delta to design)
- THEN issue LOD400 to team_10 for implementation

זה ה-GATE_2 שלך (architecture approval) מוחל על תוסף תוכן, לא על קוד חדש בלבד.

## 3. המשימה שלך — Content Expansion Phase

### 3.1 קונטקסט מ-team_00

team_00 (Nimrod, principal) ביקש (2026-05-26):
> "יש לבצע aos_handoff 110 עם משימה לממש מולי איסוף תכנים נוספים ושילוב שלהם באתר... ההחלפה תבוצע רק אחרי הרחבת ועדכון התוכן של כל האתר בכתובת הזמנית."

Translation:
- Execute a content gathering session with team_00 interactively
- Integrate the collected content into the existing site (at the dev URL)
- The production cutover (P005-WP002) is **DEFERRED** until this phase completes

### 3.2 What's in scope

1. **Fill 5 TBC content blocks** (carry-over from team_35 design package §4):
   - **Q-05** — 3–5 anchor restaurants for T2 produce service + T8 about factrow
   - **Q-NEW-03** — Confirmation that "Unless" tagline is final (appears 4+ times on the site)
   - **Q-11** — Treatment of "מיזו" (Mezoo) brand on T7 footer ("דיגיטל / מיזו") and T8 about — sub-brand vs full brand
   - **Q-02** — SFA pricing model — declared-free vs commercial-free? affects T2 sfa CTA + T1 know
   - **Q-03** — Where Nimrod teaches regularly (T2 know + T8 about specifics)

2. **New posts** — team_00 has new posts to add (unknown count). Each gets:
   - Hebrew title + body
   - One or more `world` terms (soil/know/code)
   - One `flow_style` term (lead/wide/tall/typo/quote/feature/brief)
   - Featured image (or marker for V300 image generation)

3. **Content updates to existing 22 migrated posts** — team_00 may want to refresh language, add cross-references, update facts. Per-post pass.

4. **Possible new services / projects** — beyond the 6 seeded instances. Each requires CPT field fill via REST POST or admin UI.

5. **Possible new pages** — T8 layout extension? New about subpage? team_110 to determine fit.

6. **Fix broken link `/blog/back-to-mud/`** — exists as hardcoded reference in template (T7 hero or T1 lead post related-entities). Either remove or create the post.

### 3.3 What's out of scope (V300 territory unless team_00 reverses)

- Lighthouse A11y/BP uplift (88-94 → ≥95; 73 → ≥90 on 2 posts)
- Mobile-specific template adjustments (Stage 5 of design package)
- T-03 watercolor backgrounds (separate image engine)
- T-04 logo family (separate image engine, blocks on T-07 original basket from team_00)
- DNS / cutover execution (P005-WP002 — frozen until you say "done")

## 4. Sequencing — how to run this with team_00

### Phase A — Discovery (your first session)

Open an interactive session with team_00 asking:
1. *"Of the 5 TBC content blocks (Q-05 / Q-NEW-03 / Q-11 / Q-02 / Q-03), which can you fill now? In what order?"*
2. *"How many new posts are queued? Do you have drafts elsewhere or are we writing from scratch with you?"*
3. *"Do any of the 22 migrated posts need refresh? Which ones priority?"*
4. *"New services / projects beyond the 6 seeds?"*
5. *"Anything else you want before launch?"*

Capture the answers in `_COMMUNICATION/team_110/CONTENT_PHASE_INTAKE_2026-XX-XX_v1.0.0.md`.

### Phase B — Architecture pass

For each content item team_00 brings, decide:
- **Fits existing template / CPT?** → LOD400 short-form to team_10 (just data, no code)
- **Needs template extension?** → LOD400 long-form with design impact (may need team_35 GCR if outside their locked scope)
- **Needs new template?** → GCR to team_35 for new design

Most items should be "data only" — the templates are rich enough.

### Phase C — Build cycle(s) per batch

Issue MANDATEs to team_10 in batches (~5-10 content items per batch to avoid massive cycles). For each batch:
1. MANDATE → team_10
2. COMPLETION → team_100 review
3. VALIDATE → team_190 cross-engine (lightweight for content-only batches)
4. PASS → next batch

### Phase D — Final gate

When team_00 says "done — ready for cutover":
1. Re-run CUTOVER_READINESS_REPORT sweep (P005-WP001 scripts in `scripts/qa/`) to confirm no regressions
2. Unfreeze P005-WP002 cutover MANDATE
3. Execute cutover per `_aos/work_packages/NB-S002-P005-WP002/LOD400_NB-S002-P005-WP002.md`
4. V200 milestone COMPLETE

## 5. Operational guardrails (Iron Rules carried in)

1. **No content changes via direct DB edits.** All content goes through WP admin UI OR REST API (use the `wp-mail-smtp` REST pattern from prior WPs as reference)
2. **No design system changes.** system.css + shell.css + theme.json palette are LOCKED. Any color/typography change requires GCR to team_35
3. **No new plugins** unless infrastructure-class (like wp-mail-smtp was). Content-management plugins are forbidden — native CPT is enough
4. **Hebrew slugs preserved** — WP URL-encodes them automatically; never override
5. **Carry-forwards to V300 stay separate** — broken link fix is the ONE exception you may address (it's a content item, not a Lighthouse fix)
6. **SMTP password leak rotation in effect** — see `_COMMUNICATION/team_00/SECURITY_INCIDENT_SMTP_PASSWORD_LEAK_2026-05-25_v1.0.0.md`. Never echo secrets to chat (`feedback_secret_redaction` memory)
7. **Canonical prompts to team_00** — every decision request must follow AOS canon (identity + governance + task + context + options + response snippet). See `feedback_canonical_prompts` memory

## 6. Key paths to read first (in order)

```
1. CLAUDE.md                                                                  (project rules)
2. _aos/roadmap.yaml                                                          (12/13 WPs COMPLETE state)
3. _aos/work_packages/S002/LOD300_V200_milestone.md                           (overall plan)
4. _aos/work_packages/NB-S002-P005-WP002/LOD400_NB-S002-P005-WP002.md         (the deferred cutover)
5. docs/CUTOVER_READINESS_REPORT_2026-05-25.md                                (current quality state)
6. sources/team_35_design_package/_handoff/00-HANDOFF-claude-code-110.md       (design canon — gitignored, on disk)
7. _COMMUNICATION/team_00/DECISION_V200_OPEN_QUESTIONS_2026-05-25_v1.0.0.md   (prior strategic decisions)
8. .env.upress.dev                                                            (creds, gitignored)
```

## 7. Memory files (team_100 lessons saved — apply to your work)

Read these from `/Users/nimrod/.claude/projects/-Users-nimrod-Documents-nimrod-bio/memory/`:

- `feedback_scope_discipline.md` — don't drag upstream teams' governance into product sessions
- `feedback_canonical_prompts.md` — every decision request needs full canon (CRITICAL)
- `feedback_secret_redaction.md` — never echo secrets (CRITICAL)
- `feedback_lod400_infra_assumptions.md` — verify hosting platform before locking mechanism
- `feedback_lod400_self_consistency.md` — seeds vs tests must agree
- `feedback_lod400_taxonomy_query_var.md` — taxonomy flags subtle
- `feedback_smtp_infra_assumption.md` — check native offering before external
- `feedback_prod_www_redirect.md` — `nimrod.bio` 308→`www.nimrod.bio`
- `feedback_gcr_authoring.md` — GCR canonical form errors

## 8. Questions you should ask team_00 in your first message

Bundle these into a canonical Decision Brief (see `feedback_canonical_prompts`):

**Q1 — Content readiness:** Of the 5 TBC blocks (Q-05/Q-NEW-03/Q-11/Q-02/Q-03), which can you commit content for now?

**Q2 — New posts queue:** Approximately how many new posts are queued? Are drafts ready elsewhere, or will we write together?

**Q3 — Existing post refresh:** Which of the 22 migrated posts need editorial update before launch?

**Q4 — New services/projects:** Beyond the 6 seeded instances (produce / consulting-hydro / sfa / 3 projects), are there new services or projects to add?

**Q5 — Broken link `/blog/back-to-mud/`:** Was this supposed to be a real post? If yes, content; if no, where to remove the reference?

**Q6 — Anchor restaurants (Q-05):** 3-5 names + permission to display on site

**Q7 — Tagline lock (Q-NEW-03):** "Unless" final, or alternative?

**Q8 — Mezoo branding (Q-11):** Where does Mezoo appear, as what?

**Q9 — SFA pricing (Q-02):** Declared-free vs commercial-free?

**Q10 — Teaching locations (Q-03):** Where does Nimrod teach regularly?

**Q11 — Effort window:** Estimate calendar time for content phase. Cutover is on hold until you say go.

## 9. Deliverables you should produce

```
_COMMUNICATION/team_110/
├── CONTENT_PHASE_INTAKE_<date>_v1.0.0.md          # Q1-Q11 answers + content inventory
├── LOD400_CONTENT_BATCH_001.md                    # First content batch LOD400 to team_10
├── LOD400_CONTENT_BATCH_002.md                    # And so on as needed
└── COMPLETION_CONTENT_PHASE_<date>_v1.0.0.md      # Final closure when ready for cutover

_COMMUNICATION/team_10/
├── MANDATE_CONTENT_BATCH_001.md                   # Builder activation per batch
└── ...

_aos/work_packages/                                # Optional: open a new WP if scope warrants
└── NB-S002-P006-WP001/                            # e.g. NB-S002-P006-WP00N "Content Expansion"
    └── LOD400_*.md
```

Recommendation: open one new program **P006 — Content Expansion** with WPs as batches mature. roadmap.yaml entry pattern follows P003/P004 (team_100 will register on your behalf via roadmap commit).

## 10. Tools available

- Existing scripts in `scripts/` (qa, migration, redirects)
- `wp-mail-smtp` for outbound mail (operational)
- WP admin UI (`https://nimrod-bio-2026.s887.upress.link/wp-admin/`) — use App Password from `.env.upress.dev` block 5
- REST API (`https://nimrod-bio-2026.s887.upress.link/wp-json/wp/v2/...`) for programmatic content updates
- FTPS upload via `scripts/upress_ftps_upload.py` (file deploys)
- AOS canonical commands: `/AOS_decide` for Decision Briefs, `/AOS_handoff` for next-session preparation

## 11. First action (override of canonical handoff first_action)

The canonical `HANDOFF_SELF_110_GENERAL` says "Check roadmap, confirm with team_00 before starting." This brief refines:

**Your real first action:**
1. Read sections 1-6 of this brief
2. Greet team_00 with the canonical Decision Brief on Q1-Q11 from §8 above (per `feedback_canonical_prompts`)
3. Capture answers to `CONTENT_PHASE_INTAKE_<date>_v1.0.0.md`
4. Acknowledge to team_00 with phase A plan + estimated content batch count
5. Begin phase B (architecture pass per item)

## 12. team_100 handoff status

I (team_100, Anthropic Claude) am closing my session on V200 architecture and validation work for the moment. P005-WP002 cutover LOD400 is authored and PLANNED; cutover MANDATE will be issued AFTER you signal "content phase complete."

If during your work you uncover blockers requiring architecture or governance decisions beyond your gate authority (GATE_2 architecture only), route them to team_100 via `_COMMUNICATION/team_100/`.

---

**Reach me at:** `_COMMUNICATION/team_100/` ·
**Reach team_00 (principal) at:** `_COMMUNICATION/team_00/` ·
**Reach team_10 (builder) at:** `_COMMUNICATION/team_10/` ·
**Reach team_190 (validator) at:** `_COMMUNICATION/team_190/`

— team_100 (nimrod-bio) — 2026-05-26
