---
document_title: "VERDICT — NB-S002-P003-WP001 — T7 Home"
document_type: VALIDATION_VERDICT
document_date: 2026-05-25
team_id: team_190
phase_owner: team_190
project: nimrod-bio
milestone: V200
work_package: NB-S002-P003-WP001
gate: L-GATE_VALIDATE
builder: team_10
validator: team_190
engine_used: "GPT-5.5 subagent context; independent cross-engine validation, not Cursor builder evidence"
builder_engine: Cursor
validator_engine: GPT-5.5
---

# VERDICT — NB-S002-P003-WP001 — T7 Home

## Verdict

**Overall verdict:** FAIL

**Cross-engine attestation:** Builder was team_10 using Cursor. This validation was performed independently in a GPT-5.5 subagent/team_190 context using fresh reads, live HTTP/REST probes, code inspection, Lighthouse, browser/CDP checks, git evidence, and `validate_aos.sh`; builder evidence was treated only as a claim to replay.

**Route recommendation:** Return to `team_10` for a remediation artifact; after the home recent-post query excludes the default post or the default post is removed, route back to `team_100` for gate advancement.

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
| T190-WP001-F1 | BLOCKER | Live `/` HTTP probe and browser snapshot: `.post-card` count is 4, but the visible cards are `Hello world!`, `פטריות יער בגינה`, `מבוא לגידול הידרופוני`, `מדריך שתילה נכונה`; this violates the requested post-migration check that all 4 home cards are migrated content. | `team_10` remediation artifact: remove/exclude default `Hello world!` from T7 recent posts, then request revalidation. |
| T190-WP001-N1 | MEDIUM | Browser/CDP contrast sample on `/`: `.btn-primary` measured 3.83:1 against its orange background; normal-text AA target is 4.5:1. | `team_10` accessibility polish or `team_100` explicit waiver before production QA. |
| T190-WP001-N2 | LOW | `NB_THEME_VERSION` is live as `0.4.1`, while the P003 version ladder planned WP001 at `0.3.0`; program §12 allows parallel bump drift if current version is checked. | `team_100` note; no remediation required. |

## Acceptance Matrix

| row | result | independent evidence |
|---|---|---|
| H1 `/` 200 + `front-page.php`/T7 hero | PASS | HTTP 200; `t7 t7-hero hero-statement` present. |
| H2 hero sentence verbatim | PASS | `פיזיקה, אקולוגיה, קוד וחקלאות` present. |
| H3 inline SVG diagram | PASS | Home HTML contains inline SVG/circles. |
| H4 three world cards | PASS | `world-soil`, `world-know`, `world-code` world cards present. |
| H5 featured projects | PASS-with-notes | Project cards render; available seed/project set drives count. |
| H6 Unless ribbon | PASS | `<em>אלא אם כן</em>` present in `unless-ribbon`. |
| H7 four recent migrated posts after migration | FAIL | Four `.post-card` nodes render, but one is default `Hello world!`, not migrated content. |
| H8 final CTA buttons | PASS | `btn-primary` and `btn-spark` present. |
| H9 `t7.css?ver=*` loaded | PASS | `t7.css?ver=0.4.1` present. |
| H10 LOD300 §11 baseline | PASS-with-notes | Shell/footer and `validate_aos.sh` pass; 360px/admin-toolbar and `.btn-primary` contrast carried as notes. |

## Known Deviations Classified

- Version ladder drift is classified as non-blocking: actual `0.4.1` is acceptable under parallel WP rules.
- HTTP-only dev URL is accepted; HTTPS certificate is not a failure.
- The default `Hello world!` post is not accepted for T7 home’s “4 migrated cards” requirement.
