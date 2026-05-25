---
document_title: "REMEDIATION — NB-S002-P003-WP001 — T7 Home"
document_type: REMEDIATION
document_date: 2026-05-25
team_id: team_10
phase_owner: team_10
project: nimrod-bio
milestone: V200
work_package: NB-S002-P003-WP001
gate: L-GATE_VALIDATE
correction_cycle: 1
builder: team_10
validator: team_190
prior_verdict: _COMMUNICATION/team_190/VERDICT_NB-S002-P003-WP001_v1.0.0.md
---

# REMEDIATION — NB-S002-P003-WP001 — T7 Home

## Summary

Remediation cycle 1 addresses **T190-WP001-F1** (default `Hello world!` occupied a T7 home card). Default post removed from dev; T7 recent-posts query now restricts to `_nb_seed=v200-migrated` only. Ready for team_190 revalidation of acceptance row **H7**.

## Findings addressed

| id | severity | remediation action | status |
|---|---|---|---|
| T190-WP001-F1 | BLOCKER | REST `DELETE /wp/v2/posts/1?force=true` removed default `hello-world`; `front-page.php` T7 query adds `meta_query` on `_nb_seed=v200-migrated`; FTPS deploy of `front-page.php` to dev theme | RESOLVED |

Notes **T190-WP001-N1** (btn-primary contrast) and **T190-WP001-N2** (version ladder) unchanged — out of scope for this remediation cycle.

## Evidence

### H7 — four migrated home cards (no Hello world!)

```bash
curl -sS http://nimrod-bio-2026.s887.upress.link/ | grep -Eo 'class="post-card|<h4>[^<]+</h4>'
```

Result (2026-05-25):

```
class="post-card
<h4>פטריות יער בגינה</h4>
class="post-card
<h4>מבוא לגידול הידרופוני</h4>
class="post-card
<h4>מדריך שתילה נכונה</h4>
class="post-card
<h4>מדריך ״שליפת״ שתילים</h4>
```

- `.post-card` count: **4**
- `Hello world!` absent in `/` HTML (grep `-i 'hello world'` → no match)

### REST blog state (aligned with P004 remediation)

```bash
curl -sSI -u "$WP_REST_USER:$WP_REST_APP_PASSWORD" \
  "$WP_REST_BASE_URL/wp/v2/posts?per_page=1&status=publish" | grep -i x-wp-total
# X-WP-Total: 22

curl -sS -u "$WP_REST_USER:$WP_REST_APP_PASSWORD" \
  "$WP_REST_BASE_URL/wp/v2/posts?slug=hello-world&status=any"
# []
```

### Code change

- `nimrod.bio/wp-content/themes/nimrod-bio-2026/front-page.php` — T7 `$recent` `WP_Query` filters `_nb_seed = v200-migrated`
- FTPS: `front-page.php` uploaded to `wp-content/themes/nimrod-bio-2026/` on dev

### validate_aos.sh

```
RESULT: 32 PASS / 16 SKIP / 0 FAIL
```

## Git

- **Commit:** `PLACEHOLDER` (updated after commit)
- **Branch:** main

## Ready for revalidation

- **Scope:** NB-S002-P003-WP001 acceptance row **H7** only (plus confirm no regression on H1–H6, H8–H10)
- **Exit criteria:** `/` shows exactly 4 `.post-card` elements; all titles are migrated Hebrew content; no `Hello world!`; REST published post total remains 22 migrated posts
