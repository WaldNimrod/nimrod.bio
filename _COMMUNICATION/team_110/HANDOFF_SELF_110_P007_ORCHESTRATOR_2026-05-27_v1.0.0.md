---
type: AOS_HANDOFF
depth: full
from: team_110 (originating session — cursor-composer)
to: team_110 (NEXT SESSION — P007 orchestrator)
project: nimrod-bio
milestone: V200
program: P007 — Pre-Cutover Completion
date: 2026-05-27
version: v1.0.0
status: HANDOFF_READY
handoff_reason: team_00 directive 2026-05-27 — orchestration of 4 P007 waves to be managed by separate sequential session
---

# AOS Handoff — team_110 P007 Orchestrator (full depth)

## 1. Identity

- **Team ID:** `team_110`
- **Engine:** Cursor Composer 2 (IDE)
- **Role:** AOS Domain Architect — owns GATE_2 architecture approval; for P007: ALSO acts as orchestrator (sequences 4 waves, dispatches MANDATEs, signs gates between waves)
- **Domain scope:** universal (DB-authoritative per ADR034)
- **Project:** nimrod-bio (spoke, profile L0)
- **Repo:** `/Users/nimrod/Documents/nimrod-bio`
- **Working tree:** clean post-commit; on `main` (HEAD per latest push)

## 2. Governance contract (inherited)

Read at session start:
- `/Users/nimrod/Documents/agents-os/_aos/governance/team_110.md`
- `/Users/nimrod/Documents/nimrod-bio/CLAUDE.md`

## 3. Mission for this session

Orchestrate the 4 P007 waves to completion, ending with team_110 signing `COMPLETION_CONTENT_PHASE_<date>_v2.0.0.md` that unfreezes cutover.

You are NOT the executor of any wave (those are team_50, team_10, team_190 sub-sessions). You ARE:
- Dispatcher (paste activation prompts to user → user opens sub-sessions)
- Monitor (check COMPLETIONs via /AOS_mail + git fetch loops)
- Gate signer (verify exit criteria + advance to next wave)
- Final integrator (sign v2.0.0 + update cutover MANDATE precondition status)

## 4. State recap (where you start)

### V200 sub-phase 1 — DONE
- P001-P005-WP001 closed via prior sessions
- P006-WP001 (placeholders + SFA cleanup) — PASS_WITH_FINDINGS (commit 2c92ecef)
- P006-WP002 (685 media migration + theme cleanup) — PASS_WITH_FINDINGS (commit 4915a914)
- PR #1 merged to main (commit c150b9cb)
- team_50 functional QA — PASS_WITH_FINDINGS (commit 832f9484)
- F-001 ACCEPTED (commit 326d3f72)
- F-003 WAIVED (in commit aef6fbf7)
- COMPLETION_CONTENT_PHASE v1.0.0 signed (commit aef6fbf7)

### V200 sub-phase 2 — STARTS HERE (your scope)
- P007 program defined: 4 waves
- 4 MANDATEs written + parked (see §5)
- Cutover MANDATE v1.1.0 amended with new precondition
- DB registration BLOCKED (FOLLOW_UP filed; artifact-based ops per ADR034 R8 spirit)

## 5. Parked artifacts ready for your dispatch

| Wave | Path | Owner | Dispatch when |
|---|---|---|---|
| 1 | `_COMMUNICATION/team_50/MANDATE_NB-S002-P007-WP001_MCP_DESIGN_QA_v1.0.0.md` | team_50 + MCP | NOW — your first dispatch |
| 2 | `_COMMUNICATION/team_110/MANDATE_NB-S002-P007-WP002_COMPLETION_INVENTORY_v1.0.0.md` | team_110 (self) | After Wave 1 COMPLETION |
| 3 | `_COMMUNICATION/team_10/MANDATE_NB-S002-P007-WP003_CONTENT_FILL_v1.0.0.md` | team_10 + team_00 | After RESPONSE_INVENTORY signed |
| 4 | `_COMMUNICATION/team_50/MANDATE_NB-S002-P007-WP004_FINAL_VALIDATION_v1.0.0.md` | team_50 + team_190 | After Wave 3 all sub-batches PASS |

PLAN reference: `_COMMUNICATION/team_110/PLAN_P007_PRE_CUTOVER_COMPLETION_2026-05-27_v1.0.0.md`

## 6. Operating rules (must observe)

### 6.1 Iron Rules in force
- IR #1 cross-engine — validators (team_190 Codex, team_99 claude-code) ≠ all Cursor builders. Map enforced in PLAN §3.
- IR #6 canonical communication — every wave handoff via artifact in `_COMMUNICATION/`
- IR #7 API-only for DB mutations (currently impractical — DB block; artifact-based per FOLLOW_UP_aos_domain_db_provisioning)
- IR #13 (team_110 NO write to `_aos/`) — defer all `_aos/` writes to team_191/team_100/team_99 escalation
- HEAD-freeze on `main` during external L-GATE_VALIDATE — Wave 4 invokes; respect freeze on file scope

### 6.2 Routing discipline (learned the hard way)
Per `feedback_team_routing_discipline.md` memory: BUILD goes to team_10 (Cursor), OPS goes to team_99 (claude-code), QA goes to team_50, constitutional VALIDATE goes to team_190 (Codex), architecture (you) goes to team_110.

team_00 conveniences ≠ routing directives. Vet against track + IR#1 + scope before issuing each MANDATE.

### 6.3 Canonical decision briefs to team_00
Per `feedback_canonical_prompts.md`: every team_00 decision request needs identity + governance + task + context + options + response snippet. Inventory exit gates particularly.

### 6.4 Secret handling
Per `feedback_secret_redaction.md`: never echo `WP_REST_APP_PASSWORD` / `AOS_V3_ACTOR_KEYS` / SMTP creds to chat or commits. Confirm presence only.

### 6.5 API base resolution
Per `feedback_aos_api_base_resolution.md`: three-tier chain — env → `core/.env` → 127.0.0.1. On Mac, ALWAYS prefer the server-side `core/.env` value via SSH for actor keys, not the (potentially stale) Mac copy.

## 7. First action

1. **Read** in order:
   - This handoff file (you're reading)
   - `_COMMUNICATION/team_110/PLAN_P007_PRE_CUTOVER_COMPLETION_2026-05-27_v1.0.0.md`
   - The 4 parked MANDATEs (skim — you'll deep-read each at dispatch time)
   - `_COMMUNICATION/team_00/FOLLOW_UP_aos_domain_db_provisioning_2026-05-27_v1.0.0.md` (context for DB block)

2. **Acknowledge to team_00** in chat:
   > "team_110 orchestrator session live. Read handoff + PLAN. Ready to dispatch Wave 1."

3. **Dispatch Wave 1**: paste activation prompt from `MANDATE_NB-S002-P007-WP001_MCP_DESIGN_QA_v1.0.0.md` §9 to user, who opens a NEW Cursor session as team_50.

4. **Monitor** while user runs team_50:
   - `git fetch` + `git log origin/main` periodically
   - `/AOS_mail` for team_50 messages
   - Provide unblocks if team_50 escalates

5. **Sign Wave 1 gate** when team_50 produces `MCP_QA_REPORT_NB-S002-P007-WP001_*.md`. Verify AT-Q1..AT-Q10. Write your acknowledgment + dispatch Wave 2.

## 8. Per-wave dispatch protocol

For each wave:
1. Verify predecessor exit criteria met
2. Paste activation prompt to user (from MANDATE §8/§9)
3. User opens sub-session (new Cursor chat) and runs the wave
4. You monitor + unblock
5. Wave session writes COMPLETION (or VERDICT for Wave 4)
6. You verify acceptance tests
7. You write `ACK_WP00N_PASS_FROM_team_110.md` if needed for clean audit
8. Dispatch next wave OR if Wave 4 → sign COMPLETION_CONTENT_PHASE v2.0.0

## 9. Wave 4 final signature

When team_50 + team_190 both deliver PASS / PASS_WITH_FINDINGS on Wave 4:
1. Write `_COMMUNICATION/team_110/COMPLETION_CONTENT_PHASE_<date>_v2.0.0.md`:
   - Frontmatter: `supersedes: 2026-05-27_v1.0.0.md` + `signed: <date>`
   - Body: full chain of evidence (link to Wave 1, 2, 3 COMPLETIONs + Wave 4 reports + verdicts)
   - Section "V200 sub-phase 2: CLOSED — cutover precondition MET"
2. Commit: `gov(V200): COMPLETION_CONTENT_PHASE v2.0.0 — V200 ready for cutover (post-P007)`
3. Notify team_00 in chat with cutover GO decision request
4. team_00 decides D-day → team_99 reads cutover MANDATE v1.1.0 → executes

## 10. Memories to load (carry forward)

```
/Users/nimrod/.claude/projects/-Users-nimrod-Documents-nimrod-bio/memory/MEMORY.md
```
Index of session-specific lessons. Particularly relevant to your session:
- `feedback_team_routing_discipline.md`
- `feedback_canonical_prompts.md`
- `feedback_secret_redaction.md`
- `feedback_aos_api_base_resolution.md`
- `feedback_scope_discipline.md`

## 11. Open follow-ups (informational; not blocking P007)

| Item | Status |
|---|---|
| nimrod-bio domain DB ULID provisioning | `FOLLOW_UP_aos_domain_db_provisioning_2026-05-27_v1.0.0.md` — out-of-band ops |
| `aos_sync_all.sh` script missing on server | escalated to team_60 / team_99 / team_100 via above FOLLOW_UP |
| CLAT broken on waldhomeserver | minor, not blocking |
| feat/p006-* branches merged — local cleanup | cosmetic |
| `scripts/migration/logs/` untracked | optional `.gitignore` |

## 12. Activation prompt for THIS handoff (paste at next session start)

```
═══════════════════════════════════════════════════════════════
TEAM 110 — Domain Architect / P007 Orchestrator
ACTIVATION — V200 sub-phase 2 (Pre-Cutover Completion)
═══════════════════════════════════════════════════════════════

זהות
────
- Team ID: team_110
- Engine: Cursor Composer 2 (IDE)
- Role: Domain Architect + P007 wave orchestrator
- Repo: /Users/nimrod/Documents/nimrod-bio
- Governance: /Users/nimrod/Documents/agents-os/_aos/governance/team_110.md

הקריאה הראשונה (חובה)
────────────────
1. _COMMUNICATION/team_110/HANDOFF_SELF_110_P007_ORCHESTRATOR_2026-05-27_v1.0.0.md
   (זה החוזה המלא שלך — קרא אותו 100% לפני שום פעולה)
2. _COMMUNICATION/team_110/PLAN_P007_PRE_CUTOVER_COMPLETION_2026-05-27_v1.0.0.md
   (4-wave master plan)
3. /Users/nimrod/.claude/projects/-Users-nimrod-Documents-nimrod-bio/memory/MEMORY.md
   (5 feedback memories — apply each in scope)

המשימה
──────
לתזמן 4 גלים של P007 עד COMPLETION_CONTENT_PHASE v2.0.0 שמשחרר cutover.

צעד ראשון
─────────
1. /AOS_mail (קליטת inbox state)
2. ACK ל-team_00 בצ׳ט "team_110 orchestrator live"
3. Dispatch Wave 1 — paste activation prompt מ-
   _COMMUNICATION/team_50/MANDATE_NB-S002-P007-WP001_MCP_DESIGN_QA_v1.0.0.md §9
   ל-user; user פותח Cursor chat חדש כ-team_50 ומריץ.

State
─────
team=team_110 project=nimrod-bio milestone=V200 program=P007 wave=1 depth=full

═══════════════════════════════════════════════════════════════
```

— team_110 (originating session, signed off) — 2026-05-27
