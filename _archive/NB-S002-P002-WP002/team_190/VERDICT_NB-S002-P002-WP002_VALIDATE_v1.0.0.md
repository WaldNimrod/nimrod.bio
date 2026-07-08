---
type: VERDICT
from: team_190 (Codex)
to: team_100
wp_id: NB-S002-P002-WP002
date: 2026-05-25
gate: L-GATE_VALIDATE
verdict: FAIL
---

# VERDICT — NB-S002-P002-WP002

## Summary
FAIL: the CPT/tax/meta layer is mostly functional and C1-C16 substantially replayed, but two constitutional blockers remain: `/?world=soil` renders a public taxonomy archive, and a published WP002 test service was left on the dev site.

## Test results — C1-C16

| Test | Result | Independent evidence |
|---|---:|---|
| C1 `service` CPT registered, REST exposed | PASS_WITH_NOTE | `GET /wp/v2/services` returned HTTP 200. It returned count=1 because leftover `wp002-acceptance-service` exists; endpoint itself is registered/exposed. |
| C2 `project` CPT registered, REST exposed | PASS | `GET /wp/v2/projects` returned HTTP 200 with count=0 before validator-created project test. |
| C3 `world` taxonomy has 3 terms | PASS | `GET /wp/v2/world` returned exactly `soil`, `know`, `code` (ids include `soil=4`). |
| C4 `flow_style` taxonomy has 7 terms | PASS | `GET /wp/v2/flow_style` returned `brief`, `feature`, `lead`, `quote`, `tall`, `typo`, `wide`. |
| C5 `/world/soil/` accessible | PASS | `HEAD /world/soil/` returned HTTP 200 and body class identifies it as a page, not a taxonomy archive. |
| C6 `/world/know/`, `/world/code/` accessible | PASS | `HEAD /world/know/` and `HEAD /world/code/` returned HTTP 200. |
| C7 parent `/world/` private | PASS | `HEAD /world/` returned HTTP 404 for public guest. |
| C8 admin can create `service` via UI | PASS_WITH_EVIDENCE | Per validate request, team_10 UI automation is acceptable when REST persistence is verified. Validator REST create returned 201; follow-up GET showed `_nb_tagline = "קיבולת בדיקה"` and all service `_nb_*` meta keys exposed. Validator-created service was deleted. |
| C9 admin can create `project` via UI | PASS_WITH_EVIDENCE | Validator REST create returned 201; follow-up GET showed `_nb_summary = "סיכום בדיקה"`, `world=[4]`, and all project `_nb_*` meta keys exposed. Validator-created project was deleted. |
| C10 REST POST creates a service | PASS | Authenticated `POST /wp/v2/services` returned 201 with id `18` during validation run. |
| C11 REST returns meta for a service | PASS | `GET /wp/v2/services/18` returned `meta._nb_tagline = "קיבולת בדיקה"` and all expected service `_nb_*` keys. |
| C12 World term applied via REST | PASS | Authenticated update with `world:[4]` returned HTTP 200; follow-up response showed `world=[4]`. |
| C13 Rewrite flush version stored | PASS_INDIRECT | Local code defines `NB_THEME_VERSION = 0.2.0` and `rewrites.php` updates `nb_theme_rewrite_version`; runtime `/services/{slug}/` and `/project/{slug}/` permalinks for validator-created records both returned HTTP 200. Direct `wp_options` read was not available through REST. |
| C14 `validate_aos.sh` | PASS | Independent run: `RESULT: 32 PASS / 16 SKIP / 0 FAIL`. |
| C15 No PHP errors on activation/save paths | PASS | `php -l` passed for all 9 new `inc/` files plus `functions.php`; front routes and REST save flows completed without fatal interruption. |
| C16 Existing WP002/WP001 regression | PASS | Theme remains active; homepage has RTL/Shell/Footer/local markers and `ver=0.2.0`; Chrome computed `h1` font is Frank Ruhl Libre, body background is `rgb(245, 243, 236)`, no horizontal scroll at mobile width, and console event count is 0. |

## Constitutional checks

| Check | Result | Evidence |
|---|---:|---|
| a) No new active plugin | PASS | Authenticated `GET /wp/v2/plugins` returned no active standard plugins. No ACF/MetaBox/Pods/CPT-UI references found in changed theme code. MU plugin is outside this REST plugins list and was pre-existing. |
| b) `_nb_*` schema matches design §3 | PASS | REST OPTIONS exposes service meta: `_nb_tagline`, `_nb_lede`, `_nb_service_type`, `_nb_stage`, `_nb_cta_label`, `_nb_cta_whatsapp_href`, `_nb_is_anchor_for_world`, `_nb_is_free`, `_nb_linked_projects`, `_nb_related_posts`, `_nb_sections`, `_nb_meta_strip`; project meta: `_nb_scope`, `_nb_stage`, `_nb_year`, `_nb_location`, `_nb_duration`, `_nb_summary`, `_nb_seeking_note`, `_nb_legacy_of`, `_nb_name_tbc`, `_nb_linked_services`, `_nb_gallery`, `_nb_more_projects_ids`, `_nb_outcomes`. Core fields cover slug/title/story/worlds/hero image per WordPress conventions. |
| c) Slugs exact | PASS | Code/runtime confirm `services` REST/archive rewrite for service, `project` rewrite for project, `world` taxonomy/rest base, and `flow_style` rest base. |
| d) `world` taxonomy must not create archive URLs | FAIL | `GET /?world=soil` returned HTTP 200 with `<body class="archive tax-world term-soil term-4 ...">` and title `אדמה`. This is a public taxonomy archive render, explicitly disallowed by VALIDATE_REQUEST §4(d). `/world/soil/` correctly renders as a page, but the query-var archive remains exposed. |
| e) `/world/` parent private | PASS | Public `HEAD /world/` returned HTTP 404; child `/world/soil/` returned 200 as a page. |
| f) Test record cleanup | FAIL | A published service test record remains: id `12`, slug `wp002-acceptance-service`, title `WP002 acceptance service`, meta `_nb_tagline = "x"`. COMPLETION does not document why this record remains as real content. Validator-created records id `18` and id `21` were cleaned up successfully. |

## validate_aos.sh

Command:

```bash
bash _aos/lean-kit/modules/validation-quality/scripts/validate_aos.sh .
```

Observed result:

```text
RESULT: 32 PASS / 16 SKIP / 0 FAIL
L-GATE_BUILD EXIT CRITERION: SATISFIED
[PASS] Check 32: _aos/ tree committed (no propagation drift) — IR#11
```

## Deferrals

- Carry-over only: T10 SEO remains deferred to P005-WP001 on a production/indexable URL because uPress adds `X-Robots-Tag: noindex, nofollow` on `*.upress.link`.
- C13 was accepted with indirect runtime evidence because `wp_options` is not directly exposed through REST; permalinks and source code confirm the rewrite/version path.

## Required remediations

1. Prevent public taxonomy archive rendering for `world`, including the query-var URL `/?world=soil`. Expected result: 404 or redirect; body must not be `archive tax-world term-soil`.
2. Remove or explicitly convert/document the leftover `wp002-acceptance-service` record. If it is test data, delete it; if it is real seed content, rename/document it as such in COMPLETION.

## Recommended action

Return to team_10/team_100 for a focused fix cycle on the two blockers above, then resubmit for revalidation. P003 templates remain blocked until WP002-2 receives PASS or PASS_WITH_DEFERRALS.
