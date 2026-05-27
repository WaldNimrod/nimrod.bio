---
document_title: "VERDICT — NB-S002-P003-WP004 — T4 Post + T5 Blog"
document_type: VALIDATION_VERDICT
document_date: 2026-05-25
team_id: team_190
phase_owner: team_190
project: nimrod-bio
milestone: V200
work_package: NB-S002-P003-WP004
gate: L-GATE_VALIDATE
builder: team_10
validator: team_190
engine_used: "GPT-5.5 subagent context; independent cross-engine validation, not Cursor builder evidence"
builder_engine: Cursor
validator_engine: GPT-5.5
---

# VERDICT — NB-S002-P003-WP004 — T4 Post + T5 Blog

## Verdict

**Overall verdict:** PASS-with-notes

**Cross-engine attestation:** Builder was team_10 using Cursor. This validation was performed independently in a GPT-5.5 subagent/team_190 context using fresh reads, live HTTP/REST probes, code inspection, Lighthouse, browser/CDP checks, git evidence, and `validate_aos.sh`; builder evidence was treated only as a claim to replay.

**Route recommendation:** Route to `team_100` for gate record update with notes; P004 migration superseded seed-post-specific B1/B2/B7 conditions.

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
| T190-WP004-N1 | MEDIUM | Original B1/B2 seed-post assertions are no longer current after P004 cleanup: REST shows 0 blog posts with `_nb_seed=v200`, and 22 posts with `_nb_seed=v200-migrated`. | `team_100` should record that migration superseded seed-specific checks. |
| T190-WP004-N2 | LOW | `?world=know` no longer tests empty state after migration; it returns 10 migrated know/soil posts on page 1. Empty state remains verified via `?world=code` because tagging JSON has no code posts. | `team_100` note; no remediation. |

## Acceptance Matrix

| row | result | independent evidence |
|---|---|---|
| B1 four sample posts | PASS-with-notes | Superseded by P004 cleanup; 22 migrated posts + default post now exist. |
| B2 sample flow_style terms | PASS-with-notes | Migrated content carries flow_style; old seed samples removed as required by P004. |
| B3 `/blog/` T5 flow + filter chips | PASS | HTTP 200; `t5-flow`, `filter-chip`, 10 flow items on page 1. |
| B4 `/blog/?view=grid` | PASS | `t5-grid`/grid view renders. |
| B5 `?world=soil` server-side fallback | PASS | HTTP 200; 10 page-1 items, no JS required. |
| B6 multi-world filter | PASS | `?world=soil,code` returns filtered page. |
| B7 reverify know after migration | PASS-with-notes | `?world=know` returns migrated content; empty state verified separately with `?world=code`. |
| B8 flow classes | PASS | `post-flow-*` classes render for migrated content. |
| B9 single post T4 layout | PASS | Encoded Hebrew single URLs return 200 with `t4-body`. |
| B10 ToC in PHP/no flash | PASS | `single.php` prepares body and calls `nb_extract_toc()` before render; ToC links present. |
| B11 share buttons | PASS | `template-parts/t4-share.php` renders copy/WhatsApp/email `.share-btn`. |
| B12 t4/t5 CSS conditional | PASS | `t4.css` on single; `t5.css` on blog. |
| B13 `t5-filter.js` on blog only | PASS | Blog loads `t5-filter.js?ver=*`; sampled single does not. |
| B14 baseline | PASS-with-notes | AOS/shell/footer pass; global viewport/contrast notes remain. |

## Known Deviations Classified

- B7 builder failure was independently rechecked: current `?world=know` has migrated content, so the former empty-state premise is obsolete.
- Server-side filtering is implemented in `inc/template-styles-t4-t5.php` using `pre_get_posts`, so JS-disabled fallback is present.
