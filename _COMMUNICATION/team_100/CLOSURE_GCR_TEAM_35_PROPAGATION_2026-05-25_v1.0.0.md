# CLOSURE / FEEDBACK — GCR_NIMROD_BIO_TEAM_35_PROPAGATION_v1.0.0

**Date:** 2026-05-25
**From:** team_200 (Cowork, AOS hub) — acting on team_00 directive
**To:** team_100 (nimrod-bio, spoke instance) — original GCR filer
**CC:** team_00 (Principal)
**Project:** nimrod-bio
**Re:** `GOVERNANCE_CHANGE_REQUEST_NIMROD_BIO_TEAM_35_PROPAGATION_v1.0.0`
**Verdict:** **REPLACED + EXECUTED** (GCR rejected as written; intent fulfilled via corrected scope)

---

## 1. Summary of disposition

Your GCR was filed in good faith but contained three factual errors that would have caused execution failures or wrong outcomes if accepted verbatim:

1. **Wrong target paths.** All `target_files` referenced `projects/nimrod-bio/_aos/...`. The hub has **no `projects/` tree** — spokes live as siblings under `/Users/nimrod/Documents/`. Hub-side propagation cannot land at the paths you specified.
2. **Snapshot already present.** Your Scope (c) requested propagating `core/governance/team_35.md` into the spoke. **It was already there** — full hub governance library snapshot already includes team_35.md (along with team_10/20/30/40/50/60/70/80/90/98/99/170/191/200). Step (c) was a no-op.
3. **Domain-specific actor key was wrong-framed.** You proposed `nimrodbio_design + role_type=design` in `team_assignments.yaml`. **team_35 is a UNIVERSAL team (cross-domain)** — the canonical id `team_35` is used identically in every spoke. There is no spoke-local actor; the engine is `claude-design` everywhere. Additionally, the team_assignments.yaml schema does not include a `design` role_type. The correct activation pattern is `_aos/definition.yaml` only; team_assignments.yaml is for builder/validator/etc actors.

Team_00 directed team_200 (cowork) to execute the intent of your GCR with corrected scope. Below is the full disposition.

---

## 2. What was actually executed

### 2.1 Spoke-side (this project)

| File | Action | Status |
|---|---|---|
| `_aos/definition.yaml` | Added `team_35:` block (verbatim copy from hub `core/definition.yaml:145-197`) | ✅ Done |
| `_aos/team_assignments.yaml` | **NOT modified** — team_35 is universal, has no spoke-local actor | ✅ Intentional |
| `_aos/governance/team_35.md` | **NOT touched** — already present from prior snapshot | ✅ No-op |
| `_COMMUNICATION/team_100/V200_SITE_REBUILD/BRIEF_*.md` | Created (retroactive — closes audit gap for direct-invocation delivery) | ✅ Done |
| `_COMMUNICATION/team_100/V200_SITE_REBUILD/MANDATE_TO_TEAM-35_*.md` | Created (retroactive) | ✅ Done |
| `_COMMUNICATION/team_35/V200_SITE_REBUILD/HANDOFF_*.md` | Created — canonical index pointing at `sources/team_35_design_package/_handoff/` (37-file package preserved in place) | ✅ Done |

### 2.2 Hub-side (incidental fixes triggered by your GCR)

Your GCR exposed a broader registry-hygiene gap that team_00 directed team_200 to fix at the same time:

| Hub file | Issue found | Fix |
|---|---|---|
| `_aos/project_identity.yaml.managed_projects` | Listed only 3 spokes (tiktrack, aos-sandbox-lean, aos-sandbox-full) — stale since hub creation | Synced to all 12 current spokes |
| `CLAUDE.md` (2 prose sections) | Same stale list | Updated to reference `_aos/projects.yaml` as canonical, plus full current list |
| `lean-kit/modules/team-messaging/scripts/msg_preflight.sh` | Tier-3 static fallback missing `nimrod-book`, `shaked-wg-agent`, `carpa-mio` | Added 3 entries |

### 2.3 What was NOT changed (and why)

- **Spoke `_aos/project_identity.yaml.forbidden_patterns`** — your spoke's `forbidden_patterns` lists `tiktrack`, `hobbithome`, `eyalamit`, `smallfarmsagents`, `agros-insite`. This causes Check 12 to FAIL on `sources/team_35_design_package/_handoff/02-PROMPT-logo-family.md` which contains the literal string "tiktrack". **Team 00 ruling (2026-05-25):** the forbidden_patterns config is CORRECT and stays as-is. nimrod.bio is a brand/content site that **links** to other systems but **does not contain** them — all other systems live or will live at subdomains or separate domains, and it is treated in the hub registry as a regular spoke like any other. **Required action:** team_100 (nimrod-bio) or team_35 sanitizes `02-PROMPT-logo-family.md` to replace the literal codename "tiktrack" with a non-codename description (e.g., "אפליקציית מעקב הידרופוני" or similar). After sanitization, Check 12 will PASS.
- **Lean-kit version drift** — spoke `team_assignments.yaml` declares `3.3.0` while hub canon cites `3.2.0+`. Pre-existing; not in scope.

### 2.4 validate_aos.sh result

Ran on this spoke after activation: **30 PASS / 14 SKIP / 2 FAIL**.
- Check 12 FAIL — pre-existing config issue (see §2.3 above), NOT caused by activation.
- Check 32 FAIL — uncommitted `_aos/definition.yaml` drift — expected; resolves when you `git add _aos/ && git commit`.

Checks 13, 27-29 (governance integrity) all PASS — the activation itself is canonical.

---

## 3. team_35 essence — the canonical primer

Per team_00 directive, this section gives you the complete operational picture so future spoke-side decisions about team_35 are grounded.

### 3.1 What team_35 IS

- **Universal team** — Iron Rule #10 — same id `team_35` everywhere.
- **Engine:** `claude-design` — Anthropic's HTML-first design sandbox.
- **Environment:** `claude-design-sandbox` — hosted; project-based filesystem; live HTML preview; React/JSX inline; **no shell, no git, no API, no spoke-repo write access**.
- **Track (ADR044):** **CONTENT** — non-code artifact production. Default writer for CONTENT-track WPs.
- **Gate participation:** `PIPELINE_FEEDER` — produces inputs; **never operates gates**.
- **Invocation:** `on_demand_by_team_00` — NEVER auto-inserted into a pipeline. Activation is always explicit team_00 direction or team_100 mandate under team_00 pre-approval.
- **LOD phases:** LOD200 (3-5 wireframe directions per screen) + LOD300 (hi-fi mockups + design book).
- **Authority:** Producer only. No gate verdicts. No `_aos/` writes. No LOD400 authoring. No production code.

### 3.2 What team_35 produces

| Stage | Artifact | Format | Count |
|---|---|---|---|
| LOD200 main | Wireframe exploration | HTML (design_canvas / tabs / deck) | 3-5 variants/screen |
| LOD200 exit | Clickable prototype | HTML (React/JSX with tweaks) | 1 chosen direction |
| LOD300 main | Hi-fi mockup | HTML (grounded in design system) | 1 final/screen |
| LOD300 exit | Screen-by-screen narrative | Markdown | 1 per flow |
| LOD300 exit | State diagram | HTML/SVG | 1 if non-trivial flow |
| Any stage | Gate-review deck | HTML deck | on request |
| Any stage | Handoff package | Markdown index + HTML bundle | 1 per delivery |

### 3.3 Operational constraints

- **No production code.** Designs are HTML/JSX for preview only — never shippable code.
- **Design system is hard input.** If brief names a design system, team_35 must work inside it. New tokens require `DESIGN_SYSTEM_EXTENSION_REQUEST` (IR#12).
- **No silent assumptions.** If brief is under-specified, emit `CLARIFICATION_REQUEST` and stop (IR#13).
- **Human courier.** team_35 sandbox cannot write to spoke filesystem — team_00 manually transports files. Asynchronous artifact delivery is normal.
- **Cannot read live spoke state** between mandates — each engagement begins with what the brief provides.

### 3.4 Communication patterns

- **Mandate path:** `_COMMUNICATION/team_100/[WP-ID]/MANDATE_TO_TEAM-35_*.md` (must include `brief_artifact_id`).
- **Brief path:** `_COMMUNICATION/team_100/[WP-ID]/BRIEF_*.md` (template: `lean-kit/modules/design-studio/templates/BRIEF.template.md`).
- **Handoff path:** `_COMMUNICATION/team_35/[WP-ID]/HANDOFF_{WP_ID}_{SCOPE}_{DATE}_v{VERSION}.md` (canonical index — SSoT for the package).
- **Iteration cap:** 3 revision rounds per WP. Beyond round 3, team_100 must re-author the brief or escalate to team_00.

### 3.5 How activation should work in future spokes

If a spoke needs team_35 mid-lifecycle and didn't have it activated at bootstrap:

1. Spoke team_100 emits a **`MANDATE_REQUEST`** or **`DOMAIN_PROTOCOL_PROPOSAL`** artifact in `_COMMUNICATION/team_100/`, signaling intent.
2. team_00 approves in a session.
3. team_00 (or team_200 cowork under team_00 mandate) copies the `team_35:` block from hub `core/definition.yaml` to spoke `_aos/definition.yaml`. The governance snapshot (`_aos/governance/team_35.md`) is already there if `aos_sync_all.sh` has ever been run.
4. team_100 issues the actual `MANDATE_TO_TEAM-35` with a complete `BRIEF`.
5. team_35 executes in `claude-design-sandbox`; team_00 couriers the package to spoke.
6. team_100 files canonical `HANDOFF` index pointing at the package; cascades to team_110 via LOD400.

**Do NOT file a GCR** for the activation itself — it's a routine spoke-local action under team_00 authorization. GCR is reserved for changes to hub governance content (the team_35 contract itself, the universal definition.yaml block, etc.).

---

## 4. What the spoke should do next

1. ✅ `git add _aos/ _COMMUNICATION/` and commit — resolves Check 32.
2. 🔧 Sanitize `sources/team_35_design_package/_handoff/02-PROMPT-logo-family.md` — remove the literal codename "tiktrack"; replace with a generic description. After sanitization, re-run validate → Check 12 PASS expected. team_00 ruled (2026-05-25) that `forbidden_patterns` stays as-is; nimrod.bio is a brand/content site like any other spoke.
3. Cascade the design package to team_110 — the `00-HANDOFF-claude-code-110.md` inside the package is the entry point.
4. team_100 authors LOD400 executable spec on top of the design package before team_110 starts implementation.

---

## 5. Process feedback (for spoke team_100 future filings)

- **GCR target audit first.** Before filing a GCR, verify all `target_files` paths exist in the hub. Run `ls -la <hub_path>/<target_file>` for each. Saved this round of churn.
- **Snapshot vs activation.** A team's governance file being already present on the spoke is the NORMAL case — `aos_sync_all.sh` propagates the full governance library, not selectively. Don't re-request propagation; request activation.
- **Universal vs domain teams.** Before proposing a spoke-local actor for a universal team, check `domain_scope:` in the hub definition.yaml. `universal` means no spoke-local actor key.
- **Direct invocation is a documented path.** team_35 governance contract §Trigger Protocol explicitly permits direct team_00 invocation as a parallel path to team_100 mandate. The 2026-05-24 delivery was canonical under this clause; the missing BRIEF/MANDATE was paperwork, not authority.

---

## 6. Closure

This closure record + the three retroactive artifacts (BRIEF + MANDATE + HANDOFF) + the spoke definition.yaml entry collectively close the audit gap opened by the 2026-05-24 design-package delivery. team_35's role on V200 is **CLOSED**. Cascade to team_110 may now proceed under team_100 LOD400 authoring.

---

*Closure artifact — team_200 cowork on team_00 mandate | 2026-05-25 | nimrod-bio*
