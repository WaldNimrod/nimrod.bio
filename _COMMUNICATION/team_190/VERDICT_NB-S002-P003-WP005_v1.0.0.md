---
document_title: "VERDICT — NB-S002-P003-WP005 — T8 Static"
document_type: VALIDATION_VERDICT
document_date: 2026-05-25
team_id: team_190
phase_owner: team_190
project: nimrod-bio
milestone: V200
work_package: NB-S002-P003-WP005
gate: L-GATE_VALIDATE
builder: team_10
validator: team_190
engine_used: "GPT-5.5 subagent context; independent cross-engine validation, not Cursor builder evidence"
builder_engine: Cursor
validator_engine: GPT-5.5
---

# VERDICT — NB-S002-P003-WP005 — T8 Static

## Verdict

**Overall verdict:** PASS-with-notes

**Cross-engine attestation:** Builder was team_10 using Cursor. This validation was performed independently in a GPT-5.5 subagent/team_190 context using fresh reads, live HTTP/REST probes, code inspection, Lighthouse, browser/CDP checks, git evidence, and `validate_aos.sh`; builder evidence was treated only as a claim to replay.

**Route recommendation:** Route to `team_100` for P003/P005 gate-position update with residual QA notes for content TBCs and real mail deliverability.

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
| T190-WP005-N1 | LOW | Contact handler redirects correctly, but real inbox/SMTP deliverability was not verified; this remains an operational QA item. | `team_100` carry to P005 production QA/uPress SMTP check. |
| T190-WP005-N2 | LOW | `.tbc` markers remain visible on `/about/` and `/about/heritage/`, as expected pending team_00 content. | `team_00` content follow-up before go-live. |

## Acceptance Matrix

| row | result | independent evidence |
|---|---|---|
| A1 `/about/` T8 about | PASS | HTTP 200; `class="t8 t8-about"`, `t8.css`. |
| A2 about hero/avatar/name | PASS | About hero content renders. |
| A3 journey timeline | PASS | Journey markup renders. |
| A4 value tiles | PASS | Value tile markup renders. |
| A5 media grid | PASS | Media item markup renders. |
| A6 `/about/heritage/` | PASS | HTTP 200; `class="t8 t8-heritage"`. |
| A7 heritage link to produce | PASS | `/services/produce/` link present. |
| A8 `/contact/` form | PASS | `<form id="nb-contact">` present. |
| A9 required fields + nonce | PASS | `name`, `email`, `phone`, `topics[]`, `message`, `nb_contact_nonce` present. |
| A10 topic chips | PASS | Soil/know/code chips present. |
| A11 WhatsApp link | PASS | `https://wa.me/972547776770` present. |
| A12 valid POST redirect | PASS | Nonce POST returned 302 to `/contact/?status=ok`. |
| A13 invalid POST redirect | PASS | Short/missing data returned 302 to `?status=invalid`. |
| A14 honeypot | PASS | `website=spam` returned 302 to `?status=ok` silent rejection path. |
| A15 nonce required | PASS | No nonce returned 302 to `?status=error`. |
| A16 T8 assets conditional | PASS | `t8.css` on T8 pages; `t8-contact.js` on contact. |
| A17 pages bootstrapped | PASS | REST pages include `about`, `heritage`, `contact`. |
| A18 baseline | PASS-with-notes | AOS/shell/footer pass; viewport/contrast notes remain. |

## Known Deviations Classified

- `.tbc` markers are correctly classified as content-pending, not template defects.
- HTTP dev URL is accepted; HTTPS certificate is not evaluated as a failure.
