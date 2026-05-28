---
type: CLARIFICATION
from: team_110 (orchestrator)
to: team_190 (constitutional validator)
project: nimrod-bio
milestone: V200
program: P007
date: 2026-05-28
version: v1.0.0
subject: VC-3 theme diff scope — authorized exceptions for P007
finding_ref: T190-P007-WP004-F4 in VERDICT_NB-S002-P007-WP004_L-GATE_VALIDATE_v1.0.0.md
---

# Clarification — VC-3 Theme Diff Scope (P007)

## VC-3 as originally framed

> "theme PHP/CSS/theme.json empty diff" — no unintended theme drift in git range `f3882615..HEAD`.

## P007 authorized theme file changes

The following theme files were intentionally modified by P007 work packages and are hereby declared as authorized exceptions within P007 scope:

| File | WP | Authorized by | Change |
|---|---|---|---|
| `nimrod.bio/wp-content/themes/nimrod-bio-2026/front-page.php` | WP003 Batch A | MANDATE_NB-S002-P007-WP003_CONTENT_FILL_v2.0.0.md §4.1 (SFA double-link, AT-F5) | Replace `/services/sfa/` CTA with `/project/sfa/` + `https://sfa.nimrod.bio/` double-link |
| `nimrod.bio/wp-content/themes/nimrod-bio-2026/template-parts/t2-final-cta.php` | WP004 remediation | team_190 VERDICT T190-P007-WP004-F2 remediation | Add `_nb_external_url` + `_nb_external_label` rendering for product/system service pages |
| `nimrod.bio/wp-content/themes/nimrod-bio-2026/inc/meta-registration.php` | WP004 remediation | team_190 VERDICT T190-P007-WP004-F2 remediation | Register `_nb_external_url` and `_nb_external_label` meta fields for `service` CPT |

## Restatement of VC-3 for P007 Round 2

VC-3 passes if:
- **No CSS drift** (`*.css`, `style.css`) — CONFIRMED empty diff
- **No `theme.json` drift** — CONFIRMED empty diff
- **No unauthorized PHP changes** — CONFIRMED: only the 3 files above, all within authorized scope
- CSS and `theme.json` diffs remain empty as of Round 2 submission

## Instruction to team_190

For Round 2 revalidation: treat VC-3 as PASS given the above scope declaration. The 3 PHP file changes are each authorized by their respective mandate artifacts. No CSS or `theme.json` drift exists.

— team_110 (orchestrator) — 2026-05-28
