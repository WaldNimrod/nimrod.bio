# VERDICT — NB-S002-P007-WP004 — team_190 — v1.0.0

**Date:** 2026-05-28
**Author:** team_190
**WP:** NB-S002-P007-WP004
**Type:** VERDICT

## §0 Verdict Box

| Field | Value |
|---|---|
| **Verdict** | **FAIL** |
| **Gate** | L-GATE_VALIDATE |
| **Round** | 1 |
| **Validated scope** | Cumulative P007: WP001 + WP002 + WP003, Wave 4b constitutional validation |
| **One-line next step** | Route to team_10/team_110 for remediation of live SFA/TikTrack double-link delivery and clean AOS validation, then re-submit for Team 190 revalidation. |

## §1 Identity + Independence

Validator: team_190 / OpenAI Codex, independent from Cursor builder sessions (team_10, team_50, team_110).

Mandate: `_COMMUNICATION/team_50/MANDATE_NB-S002-P007-WP004_FINAL_VALIDATION_v1.0.0.md` §4.

`/AOS_mail` was attempted first. The canonical API path promoted to `http://100.125.98.56:8090`, but inbox read returned `INVALID_ACTOR_KEY` for `team_190`, so validation used local branch-safe fallback. Local Team 190 inbox contains one older P006 request, already answered by an existing P006 verdict; no local P007 MSG file was present.

## §2 Evidence Reviewed

- Wave 1 QA baseline: `_COMMUNICATION/team_50/MCP_QA_REPORT_NB-S002-P007-WP001_2026-05-28_v1.0.0.md`
- Wave 2 inventories and team_00 responses:
  - `_COMMUNICATION/team_00/INVENTORY_TEXTS_NB-S002-P007-WP002_2026-05-28_v1.0.0.md`
  - `_COMMUNICATION/team_00/INVENTORY_MEDIA_NB-S002-P007-WP002_2026-05-28_v1.0.0.md`
  - `_COMMUNICATION/team_00/INVENTORY_DECISIONS_NB-S002-P007-WP002_2026-05-28_v1.0.0.md`
  - `_COMMUNICATION/team_00/RESPONSE_INVENTORY_P007_2026-05-28_v1.0.0.md`
- Wave 3 mandate and completions:
  - `_COMMUNICATION/team_10/MANDATE_NB-S002-P007-WP003_CONTENT_FILL_v2.0.0.md`
  - `_COMMUNICATION/team_110/COMPLETION_NB-S002-P007-WP003_2026-05-28_v1.0.0.md`
  - `_COMMUNICATION/team_110/COMPLETION_NB-S002-P007-WP003_BATCH-A_2026-05-28_v1.0.0.md`
  - `_COMMUNICATION/team_110/COMPLETION_NB-S002-P007-WP003_BATCH-B_2026-05-28_v1.0.0.md`
  - `_COMMUNICATION/team_110/COMPLETION_NB-S002-P007-WP003_BATCH-C_2026-05-28_v1.0.0.md`
  - `_COMMUNICATION/team_110/COMPLETION_NB-S002-P007-WP003_BATCH-D_2026-05-28_v1.0.0.md`
- Haiku pre-check: `_COMMUNICATION/team_110/VERDICT_WP003_HAIKU_QA_2026-05-28_v1.0.0.md`
- Git range: `f3882615..HEAD`, including Wave 3 commits `34b33242`, `7f0ce0ac`, `e7311f0d`, `08d07731`.
- Independent live probes against `http://nimrod-bio-2026.s887.upress.link`.
- AOS validation: `bash _aos/lean-kit/modules/validation-quality/scripts/validate_aos.sh .`

## §3 Findings

| ID | Severity | Finding | Evidence | Route recommendation |
|---|---|---|---|---|
| T190-P007-WP004-F1 | **BLOCKER** | T7 home SFA double-link is not live. The local committed `front-page.php` contains `/project/sfa/` and `https://sfa.nimrod.bio/`, but the live dev home page still renders the stale `/services/sfa/` CTA. This fails the core Batch A remediation and AT-F5 as observed by an external visitor. | Cache-busted probes for `/`, `/?nocache=1`, and `/?team190_cache_bust=202605281520` all returned `has_project_sfa=false`, `has_sfa_external=false`, `has_services_sfa=true`, with link text `הצטרף ל-SFA` pointing to `/services/sfa/`. Local diff shows only the intended `front-page.php` edit, so the failure is deploy/cache/runtime state, not missing local code. | team_10/team_110: deploy the updated theme file or purge the relevant cache; re-probe anonymous home HTML until `/project/sfa/` and `https://sfa.nimrod.bio/` are visible and `/services/sfa/` is gone. |
| T190-P007-WP004-F2 | **BLOCKER** | TikTrack double-link pattern is not visible on the live service page. REST content for service ID 29 is filled and contains the external URL, but `/services/tiktrack/` did not render `https://tt.nimrod.bio/` in the live HTML. The current T2 template does not call `the_content()` and `t2-final-cta.php` does not use `_nb_external_url`, so updating only post content/meta is insufficient for the visitor-facing acceptance criterion. | Independent REST probe: service `tiktrack` exists, ID 29, rendered content length 615 chars, external marker true. Independent live HTML probe for `/services/tiktrack/`: status 200, `has_tt_external=false`. Code inspection: `single-service.php` delegates to template parts; `template-parts/t2-three-col.php` renders `sections` meta, and `template-parts/t2-final-cta.php` renders contact/WhatsApp fields, not `_nb_external_url` or post content. | team_10/team_110: either render service post content for product/service pages or map the TikTrack external CTA into the meta fields consumed by T2 templates. Revalidate against anonymous live HTML. |
| T190-P007-WP004-F3 | **BLOCKER** | AOS validation is red in the current working tree: `31 PASS / 16 SKIP / 1 FAIL`. L-GATE_VALIDATE expects 0 FAIL. | Check 12 reports tracked cross-project contamination in `scripts/seed_wp006_p006_wp001_placeholders.py` for `tiktrack`, `smallfarmsagents`, `agros-insite`, and `microgreens`. Git history shows this file predates P007 (`4d480c0c`, `0ffd8074`), so it is not newly introduced by P007, but it is still a current constitutional gate failure. | Team 100/team_10: fix, waive through a canonical governance artifact, or adjust validation scope if these strings are intentionally allowed content slugs for `nimrod-bio`. Re-run `validate_aos.sh` to 0 FAIL before revalidation. |
| T190-P007-WP004-F4 | Medium | VC-3 "theme PHP/CSS/theme.json empty diff" is not literally true because Batch A edited `front-page.php`. No CSS or `theme.json` drift was found, and no other theme PHP file changed in `f3882615..HEAD`, but the locked theme-scope check needs explicit interpretation: either Batch A's `front-page.php` exception is authorized, or VC-3 must fail literally. | `git diff f3882615..HEAD -- nimrod.bio/wp-content/themes/nimrod-bio-2026/...` shows only `front-page.php`. CSS and `theme.json` diff are empty. | Team 110: clarify VC-3 wording for revalidation. If `front-page.php` is the sole authorized exception, state that explicitly in the revalidation request. |
| T190-P007-WP004-F5 | Low | P007 WP registration remains governance-soft in this spoke snapshot. The Wave 4 mandate itself says `wp_registration_status: PENDING_DB_DOMAIN_PROVISIONING`, and `_aos/roadmap.yaml` has no `NB-S002-P007-*` entries. | Roadmap search found no `NB-S002-P007` entries. DB status is online, so structured registration should normally be API-authoritative per Iron Rule #7. | Team 100: register or reconcile P007 work packages through the authorized API/deploy cascade path, or record why P007 remains communication-artifact scoped. |

## §4 Passing Evidence

The FAIL verdict is not a broad rejection of all P007 work. Several core content checks passed independently:

- Published post count is 33 after `harish2021` deletion and `nimrod-context-book` creation.
- Placeholder marker scan across published REST posts returned zero `data-nb-placeholder` / `_nb_placeholder` hits.
- Target REST post bodies sampled by slug are filled above the 300-character threshold where the API returned normally; observed minimum among returned targets was 581 chars.
- SFA project CPT exists as one project (`ID 1006`), has rendered REST content, `featured_media=859`, and contains `sfa.nimrod.bio`.
- TikTrack service exists (`ID 29`), has rendered REST content, `featured_media=856`, and contains `tt.nimrod.bio` in REST-rendered content.
- Seed services `seed-t7-produce` and `seed-t7-consulting-hydro` have filled REST content.
- `/project/sfa/`, `/services/tiktrack/`, `/blog/nimrod-context-book/` returned HTTP 200 in live probes; `/blog/harish2021/` returned HTTP 404 as expected.
- Batch D media assignment log exists and records 7/16 assignments, matching the declared scope and known remaining team_00 image gaps.

## §5 Iron Rule Review

| Rule | Result | Notes |
|---|---|---|
| IR#1 — Cross-engine validation | PASS | Builder/QA artifacts are Cursor-based; this verdict is Team 190 / OpenAI Codex. Haiku pre-check was treated as supporting evidence only, not as a substitute for this validation. |
| IR#6 — Artifact communication | PASS_WITH_FINDING | Core mandate/completion/verdict artifacts exist under `_COMMUNICATION/`. No P007 MSG was visible in Team 190 local inbox due API auth fallback, but explicit activation and mandate were sufficient to proceed. |
| IR#7 — Data authority | PASS_WITH_FINDING | DB status is online. Team 190 made no structured governance mutation. However, P007 WP registration is absent from `_aos/roadmap.yaml` and the mandate marks registration pending; Team 100 should reconcile via API/deploy cascade if P007 is to be treated as a formal WP sequence. |
| IR#13 — Command architecture | PASS | No deterministic AOS command implementation was changed by P007. Spoke command checks were skipped by `validate_aos.sh` because local `.claude/commands/` are absent. |

## §6 Conclusion

P007 cannot pass L-GATE_VALIDATE in this round. The cumulative content layer is substantially improved and most REST-level content-fill objectives are satisfied, but visitor-facing live behavior still contradicts the SFA/TikTrack double-link acceptance path, and the constitutional validation harness is not clean.

Revalidation should be requested only after:

1. The anonymous live home page renders the new SFA internal + external double-link and no longer renders `/services/sfa/`.
2. The anonymous live TikTrack service page renders its external system CTA, or the acceptance criterion is explicitly amended.
3. `validate_aos.sh .` returns 0 FAIL, or a canonical waiver is filed and referenced.
4. VC-3 is clarified as either "no theme drift except authorized `front-page.php`" or "strict empty theme diff."

— team_190 — 2026-05-28
