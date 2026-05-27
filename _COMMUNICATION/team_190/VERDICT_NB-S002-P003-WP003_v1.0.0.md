---
document_title: "VERDICT — NB-S002-P003-WP003 — T2 Services + T3 Projects"
document_type: VALIDATION_VERDICT
document_date: 2026-05-25
team_id: team_190
phase_owner: team_190
project: nimrod-bio
milestone: V200
work_package: NB-S002-P003-WP003
gate: L-GATE_VALIDATE
builder: team_10
validator: team_190
engine_used: "GPT-5.5 subagent context; independent cross-engine validation, not Cursor builder evidence"
builder_engine: Cursor
validator_engine: GPT-5.5
---

# VERDICT — NB-S002-P003-WP003 — T2 Services + T3 Projects

## Verdict

**Overall verdict:** PASS-with-notes

**Cross-engine attestation:** Builder was team_10 using Cursor. This validation was performed independently in a GPT-5.5 subagent/team_190 context using fresh reads, live HTTP/REST probes, code inspection, Lighthouse, browser/CDP checks, git evidence, and `validate_aos.sh`; builder evidence was treated only as a claim to replay.

**Route recommendation:** Route to `team_100` for gate record update; no `team_10` remediation required for this WP.

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
| T190-WP003-N1 | LOW | Shared browser 360px and `.btn-primary` contrast notes apply globally but did not block T2/T3 functional acceptance. | Carry to `team_100`/P005 QA. |

## Acceptance Matrix

| row | result | independent evidence |
|---|---|---|
| S1 three services published | PASS | `/services/produce/`, `/services/sfa/`, `/services/consulting-hydro/` all HTTP 200; REST seed slugs available. |
| S2 three projects published | PASS | `/project/hagina-shel-nimrod/`, `/project/rest-x-greenhouse/`, `/project/coop-sharon/` all HTTP 200. |
| S3 produce T2 layout | PASS | `single-hero`/T2 structure present. |
| S4 consulting-hydro bridge hero | PASS | T2 bridge structure present; no heritage strip. |
| S5 SFA origin flow only on SFA | PASS | `sfa-origin-flow` present on `/services/sfa/` only. |
| S6 heritage strip only on produce | PASS | `t2-heritage-strip` present on produce. |
| S7 no heritage on consulting-hydro | PASS | No `t2-heritage-strip` on consulting-hydro. |
| S8 rest-x project layout/outcomes | PASS | T3 structure renders on `/project/rest-x-greenhouse/`. |
| S9 seeking ribbon | PASS | `t3-seeking-ribbon` included conditionally by `single-project.php`; live coop-sharon renders it. |
| S10 seeking project shows plan path | PASS | coop-sharon rendered seeking-specific path. |
| S11 own/legacy project relation section | PASS | hagina-shel-nimrod renders T3 layout with legacy ribbon. |
| S12 breadcrumbs | PASS | T2/T3 templates call `nb_breadcrumb()`. |
| S13 conditional CSS | PASS | T2 routes enqueue `t2.css`; T3 routes enqueue `t3.css`. |
| S14 stage stamps/ribbons | PASS | Seeking and legacy partials exist and are conditionally included by stage. |
| S15 baseline | PASS-with-notes | AOS/shell/footer pass; global viewport/contrast notes remain. |

## Known Deviations Classified

- Seeking/legacy class strings also appear in related cards, so verdict uses code inspection plus route-specific partial checks rather than raw class counts alone.
- Seed services/projects are expected to remain; P004 cleanup only removed blog `_nb_seed=v200` posts.
