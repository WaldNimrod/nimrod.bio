---
id: MANDATE_NB-V200-P006-WP001_CONTENT_BATCH_001
type: CONTENT_BATCH_EXECUTION_MANDATE
from: team_110 (Domain Architect · cursor-composer-2 · nimrod-bio spoke · Mac session)
to: team_99 (Home Server Team · claude-code CLI on waldhomeserver)
project: nimrod-bio
milestone: V200
wp_id_proposed: NB-S002-P006-WP001
date: 2026-05-26
version: v1.0.0
priority: P1 (gates cutover P005-WP002)
status: ACTIVE — pending team_00 delivery to team_99 inbox
delivery: team_00 to either (a) copy this file to `/Users/nimrod/Documents/agents-os/_COMMUNICATION/team_99/` and notify team_99 via SSH-activated session, OR (b) SSH into waldhomeserver and paste the activation prompt below directly
authorization_chain:
  - team_00 directive 2026-05-26 (content phase pre-cutover)
  - team_110 GATE_2 architecture approval (this artifact)
  - CONTENT_PHASE_INTAKE_2026-05-26_v1.0.0.md (Phase A LOCKED, 11/11 questions answered + 4 corrections)
  - LOD400_DRAFT_NB-S002-P006-WP001_v1.0.0.md (Phase B LOD400 v1.0.1)
---

# MANDATE — V200 Content Batch 001 (P006-WP001) · executor: team_99

## 1. Authority

team_00 (Nimrod) directive 2026-05-26: "ההחלפה תבוצע רק אחרי הרחבת ועדכון התוכן של כל האתר בכתובת הזמנית" — content phase MUST complete on dev URL before production cutover (P005-WP002, deferred). team_00 elected (2026-05-26 chat) to route execution via team_99 on the home server.

team_110 (Domain Architect, nimrod-bio) authored Phase A intake + Phase B LOD400, and now issues this MANDATE for build execution per AOS canonical mandate flow.

## 2. Pre-state (verify on activation)

- **Dev URL:** `https://nimrod-bio-2026.s887.upress.link` — should render `wp-theme-nimrod-bio-2026` (sanity command: `curl -sk URL/ | grep wp-theme-nimrod-bio-2026`)
- **Hub canonical API:** `http://100.125.98.56:8090` — should respond on `/api/projects` (you ARE the host; expect 200)
- **Spoke repo:** `/Users/nimrod/Documents/nimrod-bio` on Mac (NOT on server). team_99 will need either (a) SSH-mounted remote, (b) git pull on server clone, OR (c) team_00 will checkout the relevant branch on server-side clone of `WaldNimrod/nimrod-bio`.
- **roadmap.yaml current state:** P006 NOT yet registered. team_99 to register `NB-S002-P006-WP001` as first action.
- **DB online status:** see hub `_aos/db_connectivity_status.json` — if `status: online`, Iron Rule #7 in force (API-only structured mutations).

## 3. Reference artifacts (read in order)

| # | Path | Why |
|---|---|---|
| 1 | `/Users/nimrod/Documents/nimrod-bio/CLAUDE.md` | Spoke rules + operational quirks |
| 2 | `_COMMUNICATION/team_110/MISSION_BRIEF_CONTENT_PHASE_2026-05-26_v1.0.0.md` | Phase context |
| 3 | `_COMMUNICATION/team_110/CONTENT_PHASE_INTAKE_2026-05-26_v1.0.0.md` | Locked answers from team_00 |
| 4 | `_COMMUNICATION/team_110/LOD400_DRAFT_NB-S002-P006-WP001_v1.0.0.md` | **THE BUILD SPEC** (v1.0.1) |
| 5 | `_COMMUNICATION/team_100/REQUEST_REGISTER_P006_WP001_2026-05-26_v1.0.0.md` | Roadmap registration ask (you'll execute, not team_100) |
| 6 | `/Users/nimrod/Documents/agents-os/_aos/governance/team_99.md` | Your own governance contract |

## 4. Mode classification

Per your governance contract (`OUT_OF_GATE_ISOLATED`, OPS-track + ISOLATED_BRANCH for code):

| Sub-task | Mode | Validator |
|---|---|---|
| Roadmap registration (`NB-S002-P006-WP001`) | OPS via hub API | self-attest |
| LOD400 file move to `_aos/work_packages/NB-S002-P006-WP001/` | OPS file op | self-attest |
| 11 post creates via WP REST API (data only) | OPS | self-attest |
| Theme file edit (SFA CTA — task 4.2 in LOD400) | **ISOLATED_BRANCH** | team_190 lightweight validate before merge to `main` |
| Theme verifications (tasks 4.1, 4.3, 4.5) | OPS read-only | self-attest |
| Yoast meta verification + sitemap regen | OPS | self-attest |

**Net:** 1 code-change file → 1 isolated branch + team_190 validate. Everything else OPS self-attest.

## 5. Required actions (in order)

### 5.1 — Roadmap registration (OPS)

Open program `P006 — Content Expansion (pre-cutover)` and WP `NB-S002-P006-WP001` in `_aos/roadmap.yaml`.

Iron Rule #7 enforcement: if `db_connectivity_status.json` says `online`, use hub API to register, not direct YAML edit:

```bash
BASE="http://100.125.98.56:8090"
# 1) open program (if API supports; otherwise fall back to roadmap.yaml edit on hub by team_191)
curl -X POST "$BASE/api/projects/nimrod-bio/programs" \
  -H "Content-Type: application/json" \
  -H "X-Actor-Key: $AOS_ACTOR_API_KEY" \
  -d '{"program_id":"P006","label":"Content Expansion (pre-cutover)","stage_id":"S002"}'

# 2) open WP
curl -X POST "$BASE/api/projects/nimrod-bio/work-packages" \
  -H "Content-Type: application/json" \
  -H "X-Actor-Key: $AOS_ACTOR_API_KEY" \
  -d @- <<JSON
{
  "wp_id":"NB-S002-P006-WP001",
  "program_id":"P006",
  "stage_id":"S002",
  "label":"Content Batch 001 — 3 string locks + 1 template prune + 11 placeholder posts",
  "track":"A_CONTENT",
  "effort":"~5h",
  "predecessor":"NB-S002-P005-WP001",
  "successor":"NB-S002-P005-WP002",
  "status":"PLANNED",
  "owner_team":"team_99",
  "architect_team":"team_110"
}
JSON
```

**If API rejects** (endpoint name drift, profile mismatch, etc.) — fall back: edit `_aos/roadmap.yaml` directly on hub (you have hub write authority for OPS), commit with message `chore(roadmap): register NB-S002-P006-WP001 — P006 content expansion`.

### 5.2 — Move LOD400 to canonical path (OPS file op)

```bash
mkdir -p /Users/nimrod/Documents/nimrod-bio/_aos/work_packages/NB-S002-P006-WP001
cp /Users/nimrod/Documents/nimrod-bio/_COMMUNICATION/team_110/LOD400_DRAFT_NB-S002-P006-WP001_v1.0.0.md \
   /Users/nimrod/Documents/nimrod-bio/_aos/work_packages/NB-S002-P006-WP001/LOD400_NB-S002-P006-WP001.md
# strip the `_DRAFT` from filename + the `_proposed` markers in frontmatter
```

Commit on `main` (this is artifact propagation, not code change): `chore(wp): canonical LOD400 for NB-S002-P006-WP001`.

### 5.3 — Resolve world + flow_style term IDs (OPS pre-flight)

```bash
DEV="https://nimrod-bio-2026.s887.upress.link"
AUTH="-u agent:<APP_PWD>"    # APP_PWD from `.env.upress.dev` — NEVER echo to logs
curl -s $AUTH "$DEV/wp-json/wp/v2/world?per_page=100" | jq '[.[] | {id,slug,name}]'
curl -s $AUTH "$DEV/wp-json/wp/v2/flow_style?per_page=100" | jq '[.[] | {id,slug,name}]'
```

Build a `term_map.json` mapping `{slug → id}` for both taxonomies. Will be used by 5.5 below.

### 5.4 — Theme verifications + SFA CTA edit (ISOLATED_BRANCH for the edit)

Branch off `main`:
```bash
cd /Users/nimrod/Documents/nimrod-bio
git checkout -b feat/p006-wp001-content-batch-001
```

**Verifications (read-only — task 4.1, 4.3, 4.5 from LOD400):**
```bash
# Task 4.1: "Unless" tagline lock
grep -r "Unless" wp-content/themes/nimrod-bio-2026/templates/ wp-content/themes/nimrod-bio-2026/parts/ || echo "NOT FOUND — investigate"
# Task 4.3: Mezoo sub-brand
grep -rE "מיזו|Mezoo" wp-content/themes/nimrod-bio-2026/parts/footer.html wp-content/themes/nimrod-bio-2026/templates/about.html
# Task 4.5: /blog/back-to-mud/ reference in template
grep -rn "back-to-mud" wp-content/themes/nimrod-bio-2026/
```

Report counts in completion artifact. If any verification fails (wrong count / missing) — STOP and escalate to team_110 via `_COMMUNICATION/team_110/` with a CLARIFICATION_REQUEST.

**SFA CTA edit (Task 4.2 — only actual file change):**

Locate the SFA service template (likely `wp-content/themes/nimrod-bio-2026/parts/services-sfa.html` or block in T2 template). Update CTA:
- Label: `השתמש בכלי`
- Copy near CTA (~30–50 words): declared-free positioning, e.g.:
  > "SFA — Smart Field Agent. כלי חופשי לחקלאים. השימוש פתוח לכולם; אם רוצים אינטגרציה לחווה ספציפית — דבר איתנו."
- Remove any "TBC" / pricing placeholder text

Commit: `feat(p006-wp001): SFA service — declared-free CTA + copy lock`.

### 5.5 — 11 post creates via WP REST (OPS)

Per LOD400 §3.2 (v1.0.1 — corrected). Use template from §3.3.

For each row in the metadata table, POST to WP REST:

```bash
DEV="https://nimrod-bio-2026.s887.upress.link"
AUTH="-u agent:<APP_PWD>"

# Example: post 1 — agents-os
curl -X POST "$DEV/wp-json/wp/v2/posts" $AUTH \
  -H "Content-Type: application/json" \
  -d @- <<'JSON'
{
  "slug": "agents-os",
  "title": "Agents-OS — מסגרת ממשל לסוכנים",
  "status": "publish",
  "content": "<!-- placeholder template instantiated per §3.3 of LOD400 -->...",
  "meta": { "_nb_placeholder": true },
  "world": [<id_code>, <id_know>],
  "flow_style": [<id_feature>]
}
JSON
```

**11 posts to create (use the corrected table from LOD400 v1.0.1):**

| # | slug | title | world (slugs) | flow_style |
|---|---|---|---|---|
| 1 | `agents-os` | Agents-OS — מסגרת ממשל לסוכנים | code, know | feature |
| 2 | `eyal-amit-2026` | אייל עמית — אתר 2026 | code | feature |
| 3 | `israel-microgreens` | Israel Microgreens — מכולה הידרופונית (תכנון + חקלאות) | soil, know, code | **lead** |
| 4 | `shaked-wg-agent` | Shaked WG — סוכן חיפוש בזל | code | feature |
| 5 | `smallfarmsagents` | SmallFarmsAgents — מערכת קהילתית לחווה אורגנית | soil, know, code | **lead** |
| 6 | `tiktrack-phoenix` | TikTrack Phoenix | code | brief |
| 7 | `agros-insite` | Agros Insite | soil | feature |
| 8 | `capra-mio` | Capra Mio — סוכן הפלגה | code | feature |
| 9 | `אנטרופיה` | אנטרופיה | know | typo |
| 10 | `אלה-אם-unless` | אלה אם — Unless | code, know | typo |
| 11 | `back-to-mud` | Back to Mud (placeholder) | soil | brief |

**Placeholder body template** (per LOD400 §3.3) — substitute `{{ ... }}` per post from `projects.yaml` for posts 1–8, or use special texts from LOD400 §3.4 for posts 9–11.

### 5.6 — Acceptance tests (OPS self-attest — AT-1 through AT-10 from LOD400 §5)

Run all 10 acceptance tests. Capture output in completion artifact.

### 5.7 — team_190 lightweight validate (ISOLATED_BRANCH merge gate)

Since 5.4 introduced a code change on a branch, request team_190 lightweight validate before merging to `main`:
- Push branch: `git push -u origin feat/p006-wp001-content-batch-001`
- Open PR or write a CLARIFICATION to team_190 (`_COMMUNICATION/team_190/` on the hub OR spoke) requesting cross-engine validation of the single-file SFA CTA edit
- After PASS → merge to `main`

### 5.8 — COMPLETION artifact

Write `_COMMUNICATION/team_110/COMPLETION_NB-S002-P006-WP001_2026-05-26_v1.0.0.md` (return to team_110 for review + sweep) containing:
- Commit hashes
- Acceptance test results (all 10)
- Term ID mapping used
- List of 11 created post IDs + URLs
- Notes/exceptions
- Pointer: "ready for team_110 placeholder sweep before COMPLETION_CONTENT_PHASE"

## 6. Out-of-scope (do NOT touch)

- DNS / cutover (P005-WP002 stays DEFERRED)
- Lighthouse uplift, mobile tweaks, watercolor backgrounds, logo family
- system.css / shell.css / theme.json (LOCKED by team_35)
- Existing 22 migrated posts (no refresh — Q3 LOCKED)
- New services/projects (Q4 LOCKED — 6 seeds enough)
- About page enhancement from `nimrod-book` (FOLLOW-UP, not this batch — see LOD400 §8)
- Production prod URL `nimrod.bio` — dev URL only

## 7. Secret handling

- App Password lives in `.env.upress.dev` block 5 — **never echo to chat / logs / commit messages**
- SMTP password rotation in effect (see `_COMMUNICATION/team_00/SECURITY_INCIDENT_SMTP_PASSWORD_LEAK_2026-05-25_v1.0.0.md`)
- hub `AOS_ACTOR_API_KEY` — load from env, never log

## 8. Rollback plan

| Scenario | Rollback |
|---|---|
| Roadmap registration corrupts state | `git revert` the roadmap commit on hub |
| Post creates partial (e.g., 7/11) | List failed slugs; either retry failed ones OR `DELETE /wp-json/wp/v2/posts/<id>` to clean up + restart |
| Theme branch fails team_190 validate | Stay on branch; iterate on the SFA CTA copy per validator note; never merge until PASS |
| Sitemap regen breaks | Yoast → Tools → SEO Data Optimization → re-run; OR `WP-CLI: wp yoast index --reindex` |

## 9. Heartbeat cadence

For long-running OPS sessions (>30 min), drop heartbeat to `_COMMUNICATION/team_99/HEARTBEAT_P006_WP001_<ts>.md` every ~20 min — same pattern as `HEARTBEAT_PROD_MIGRATION_2026-04-27.md`.

— team_110 (cursor-composer-2 · nimrod-bio · Mac session) — 2026-05-26
