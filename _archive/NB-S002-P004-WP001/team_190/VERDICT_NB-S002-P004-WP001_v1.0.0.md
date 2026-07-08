---
document_title: "VERDICT — NB-S002-P004-WP001 — Content Migration"
document_type: VALIDATION_VERDICT
document_date: 2026-05-25
team_id: team_190
phase_owner: team_190
project: nimrod-bio
milestone: V200
work_package: NB-S002-P004-WP001
gate: L-GATE_VALIDATE
builder: team_10
validator: team_190
engine_used: "GPT-5.5 subagent context; independent cross-engine validation, not Cursor builder evidence"
builder_engine: Cursor
validator_engine: GPT-5.5
---

# VERDICT — NB-S002-P004-WP001 — Content Migration

## Verdict

**Overall verdict:** FAIL

**Cross-engine attestation:** Builder was team_10 using Cursor. This validation was performed independently in a GPT-5.5 subagent/team_190 context using fresh reads, live HTTP/REST probes, code inspection, Lighthouse, browser/CDP checks, git evidence, and `validate_aos.sh`; builder evidence was treated only as a claim to replay.

**Route recommendation:** Return to `team_10` for remediation artifact focused on media backfill and explicit disposition of the default post; hold P004/P005 gate advancement at `team_100` until revalidation or waiver.

## Independent Evidence Summary

- Required startup/context read: `CLAUDE.md`, `_aos/roadmap.yaml`, `_aos/context/PROJECT_CONTEXT.md`, `_aos/definition.yaml`; no `ACTIVATION_*.md` files exist in `_aos/context/`.
- DB probe: `/Users/nimrod/Documents/agents-os/_aos/db_connectivity_status.json` reports `status: online`.
- AOS validation: `bash _aos/lean-kit/modules/validation-quality/scripts/validate_aos.sh .` returned `32 PASS / 16 SKIP / 0 FAIL`.
- Shell/footer smoke: `/` and `/blog/` both returned HTTP 200 with `shell-nav` and `shell-foot` markers.
- Protected CSS evidence: `git diff -- system.css shell.css` was empty; latest log on these paths remains `14e9f932 fix(theme): complete WP001 fix-cycle blockers and track theme files`.
- Lighthouse on `/`: Performance 70, Accessibility 93, Best Practices 79, SEO 66; Lighthouse emitted a Lantern dependency-graph warning but returned category scores.
- Browser/CDP 360px RTL probe: document `dir=rtl`; authenticated browser showed horizontal overflow affected by WP admin/RTL/offscreen elements. Treat as residual visual QA risk, not anonymous-production proof.
- WCAG spot-check: body/nav/world-card/button samples mostly AA; `.btn-primary` white-on-orange measured 3.83:1 in the browser sample, below normal-text AA and carried as an accessibility note.

## Findings

| id | severity | evidence-by-path | route_recommendation |
|---|---|---|---|
| T190-P004-F1 | BLOCKER | Migrated media check across public REST content found 103 `<img>` refs, 91 unique URLs; only 46 returned 200/206, while 45 returned 404/timeouts/URL errors. This fails M11 as implemented, beyond a small sampling gap. | `team_10` remediation artifact: backfill/mirror remaining uploads or provide `team_100/team_00` waiver list with intentional broken refs. |
| T190-P004-F2 | MEDIUM | REST/blog state has 23 published posts: 22 `_nb_seed=v200-migrated` plus default `Hello world!`. This is acceptable for `/blog/` stats if consciously retained, but it breaks T7 home’s “4 migrated cards” check. | `team_10` remediation: delete/exclude default post or adjust T7 query; `team_100` to decide gate impact. |
| T190-P004-N1 | LOW | One Hebrew slug timed out on first sweep but returned 200 on three retries; all 22 encoded migrated slugs ultimately returned 200. | Note only. |

## Acceptance Matrix

| row | result | independent evidence |
|---|---|---|
| M1 prod creds | PASS-with-notes | Not read directly to avoid exposing secrets; completion presence and subsequent imported data prove credentialed phases ran. |
| M2 raw cache complete | PASS-with-notes | Cache is gitignored; not used as primary evidence. Live import state validated instead. |
| M3 tagging tool 22 rows | PASS | `docs/content_tagging_decisions_2026-05-25.json` has 22 posts. |
| M4 tagging JSON complete | PASS | All 22 rows include worlds and flow_style. |
| M5 22 posts imported | PASS | REST: 22 posts with `_nb_seed=v200-migrated`. |
| M6 Hebrew slugs 200 | PASS | All 22 encoded `new_url` paths returned HTTP 200 after retrying one transient timeout. |
| M7 world taxonomy assigned | PASS | Blog filters and REST terms reflect world assignments; soil/know filters return expected migrated content. |
| M8 flow_style assigned | PASS | T5 renders `post-flow-*` classes from migrated content. |
| M9 post_date preserved | PASS-with-notes | REST sample dates match builder-reported prod samples (`2023-03-06T20:52:47`, `2023-03-01T02:23:34`, `2023-02-24T01:13:34`); direct prod comparison was not repeated to avoid credential dependence. |
| M10 `/shook/` restored | PASS | `/shook/` HTTP 200 with substantive body. |
| M11 uploads resolve | FAIL | 46/91 unique migrated image URLs returned 200/206; 45 did not. |
| M12 no prod upload URL leakage | PASS | REST migrated bodies: 0 hits for `nimrod.bio/wp-content/uploads/`. |
| M13 old blog seeds removed | PASS | REST: 0 posts with `_nb_seed=v200`. Services/projects seeds remain, as expected. |
| M14 id mapping | PASS-with-notes | `.migration-cache` is gitignored; page/post live mapping inferred from 22 posts + `shook`. |
| M15 blog index stats/render | PASS | REST total posts 23; `/blog/` renders 10 page-1 flow items. |
| M16 world filter migrated content | PASS | `/blog/?world=soil` renders 10 page-1 items; `/blog/?world=know` also renders migrated content. |
| M17 T7 home 4 cards | FAIL | Four cards render, but one is `Hello world!`, not migrated content. |
| M18 `validate_aos.sh` | PASS | `32 PASS / 16 SKIP / 0 FAIL`. |
| M19 scripts/artifacts tracked | PASS-with-notes | Migration scripts and tagging JSON exist in repo; full git tracking not re-enumerated as blocker. |

## Known Deviations Classified

- M11 media gaps are not silently inherited: current independent classification is 46 OK / 45 not OK across 91 unique URLs.
- `hello-world` default post is classified as acceptable for total blog count only if team_100 explicitly accepts it; it is not acceptable for T7 home migrated-card behavior.
- HTTP-only dev URL is accepted; HTTPS certificate is not a failure.
- P004/P005 gate position: hold advancement pending remediation or explicit waiver for media/default-post behavior.
