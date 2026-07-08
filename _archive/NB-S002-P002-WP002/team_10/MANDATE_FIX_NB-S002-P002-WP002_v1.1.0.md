---
type: MANDATE_FIX
from: team_100 (nimrodbio_arch — Cursor's Claude)
to: team_10 (nimrodbio_build — Cursor)
wp_id: NB-S002-P002-WP002
project: nimrod-bio
milestone: V200
date: 2026-05-25
gate: L-GATE_VALIDATE FAIL cycle 1 → fix cycle iteration 1 → re-submission
track: A · STANDARD
priority: HIGH
cycle: 1
predecessor_artifact: MANDATE_NB-S002-P002-WP002_v1.0.0.md
verdict_ref: _COMMUNICATION/team_190/VERDICT_NB-S002-P002-WP002_VALIDATE_v1.0.0.md
methodology_ref: _aos/methodology/AOS_FIX_CYCLE_DISCIPLINE_v1.0.0.md
---

# MANDATE FIX — NB-S002-P002-WP002 — Cycle 1

**לצוות 10 (Builder — Cursor):**

team_190 (Codex) חזר עם VERDICT FAIL. שני blockers בלבד, שניהם קלים לתיקון. **חשוב:** blocker B1 הוא **defect ב-LOD400 שלי** — לא יישום שלך. עקבת verbatim. הקרדיט שלך, התיקון על שנינו.

## Preconditions

### Reproduction artifact

```bash
# B1 — world taxonomy renders public archive (BUG)
curl -sIk "$UPRESS_DEV_URL_HTTP/?world=soil"
# HTTP 200 OK — should NOT render archive
curl -sk "$UPRESS_DEV_URL_HTTP/?world=soil" | grep -oE 'body[^>]*class="[^"]*"' | head -1
# Observed: body class="archive tax-world term-soil ..."
# Expected: not an archive — taxonomy is data layer only per LOD400 §4

# B2 — leftover test service in DB
set -a; source .env.upress.dev; set +a
curl -sk -u "$WP_REST_USER:$WP_REST_APP_PASSWORD" "$WP_REST_BASE_URL/wp/v2/services/12" | python3 -m json.tool | head -10
# Observed: id=12, slug=wp002-acceptance-service, _nb_tagline=x
# Expected: 404 (deleted) — test records should not survive validation
```

### Minimal failing case

- B1: any GET on `?world=<slug>` renders WP archive template instead of 404/empty
- B2: GET `/wp/v2/services/12` returns 200 with test data

### Impacted surfaces

- `nimrod.bio/wp-content/themes/nimrod-bio-2026/inc/taxonomies.php` (B1 — 2 lines)
- One DB row (B2 — DELETE via REST, no code)

**1 code subsystem + 1 data action. Within fix-cycle scope per AOS_FIX_CYCLE_DISCIPLINE §3.**

---

## Fix B1 — world taxonomy must not be publicly queryable

### Root cause

In `inc/taxonomies.php`, the `register_taxonomy( 'world', ... )` call has:
```php
'public'    => true,
'query_var' => 'world',
```

`query_var => 'world'` is what makes `/?world=soil` load `taxonomy.php` (or fallback to `archive.php` → `index.php`) and emit `body_class="archive tax-world term-soil"`. **This contradicts LOD400 §4 decision:** `world` is a data-layer taxonomy; archive routing happens via the dedicated WP page hierarchy (`/world/`, `/world/soil/`), not via WP's taxonomy archive.

This is a defect in the spec I wrote — `query_var` was redundant since templates will use explicit `tax_query` and never the public query var. team_10 implemented correctly per spec; the spec was wrong.

### Exact change

In `nimrod.bio/wp-content/themes/nimrod-bio-2026/inc/taxonomies.php`, locate the `register_taxonomy( 'world', …)` call. Change the args:

```php
register_taxonomy( 'world', [ 'service', 'project', 'post' ], [
    'labels'            => [
        'name'          => 'עולמות',
        'singular_name' => 'עולם',
        'menu_name'     => 'עולמות',
    ],
    'public'             => true,        // keep — needed for admin UI / show_in_rest
    'publicly_queryable' => false,       // ⬅ ADD — disables /?world=slug archive routing
    'hierarchical'       => false,
    'show_ui'            => true,
    'show_admin_column'  => true,
    'show_in_rest'       => true,
    'rest_base'          => 'world',
    'rewrite'            => false,
    'query_var'          => false,       // ⬅ CHANGE from 'world' to false (no public query var)
] );
```

**Net change:** add `'publicly_queryable' => false`, change `'query_var' => 'world'` to `'query_var' => false`.

⚠️ The `flow_style` taxonomy already has `'public' => false` so it's fine — only `world` needs this fix.

### Why this preserves functionality

- ✓ Admin UI still works (`show_ui=true`, `show_admin_column=true`)
- ✓ REST endpoint `/wp-json/wp/v2/world` still works (`show_in_rest=true`, `rest_base='world'`)
- ✓ Templates can still use `tax_query: [['taxonomy'=>'world','field'=>'slug','terms'=>['soil']]]` — this uses internal taxonomy registration, not the public query var
- ✓ `wp_set_object_terms( $post_id, ['soil'], 'world' )` still works
- ✗ `/?world=soil` no longer renders archive (this is the fix)
- ✗ `get_term_link($term)` may return empty/different — but we never use it; nav links to `/world/soil/` are the WP page URLs, hardcoded in shell-nav.php

### Validation

```bash
set -a; source .env.upress.dev; set +a

# After deploy + cache bust:
curl -sIk "$UPRESS_DEV_URL_HTTP/?world=soil" | head -1
# Expected: 404 (taxonomy not publicly queryable)

curl -sk "$UPRESS_DEV_URL_HTTP/?world=soil" | grep -oE 'body[^>]*class="[^"]*"' | head -1
# Expected: no "archive tax-world" classes (page is 404 or home fallback)

# Regression sanity:
curl -sk "$WP_REST_BASE_URL/wp/v2/world" | python3 -c "import json,sys;d=json.load(sys.stdin);print(f'still {len(d)} terms via REST')"
# Expected: still returns 3 terms (REST unaffected)

curl -sIk "$UPRESS_DEV_URL_HTTP/world/soil/" | head -1
# Expected: still HTTP 200 (the WP page route still works — that's not the taxonomy archive)
```

---

## Fix B2 — delete leftover test service id=12

### Root cause

During L-GATE_BUILD C10 test (REST POST create service), team_10 created `wp002-acceptance-service` with `_nb_tagline=x`. Validation tests should clean up after themselves. team_190 noted that all THEIR test records were cleaned, but this one (created by team_10 during build) remained.

### Exact change

No code change. Single REST DELETE.

```bash
set -a; source .env.upress.dev; set +a

# Verify it exists first
curl -sk -u "$WP_REST_USER:$WP_REST_APP_PASSWORD" \
  "$WP_REST_BASE_URL/wp/v2/services/12" | python3 -c "import json,sys;d=json.load(sys.stdin);print('exists:', d.get('id'), d.get('slug'))"

# Hard delete (force=true skips trash)
curl -sk -X DELETE -u "$WP_REST_USER:$WP_REST_APP_PASSWORD" \
  "$WP_REST_BASE_URL/wp/v2/services/12?force=true" | python3 -m json.tool | head -8

# Verify gone
curl -sIk -u "$WP_REST_USER:$WP_REST_APP_PASSWORD" \
  "$WP_REST_BASE_URL/wp/v2/services/12" | head -1
# Expected: HTTP 404
```

### Going forward

Adopt a discipline: any record created during acceptance tests (C8/C9/C10 etc.) should be cleaned up before COMPLETION is filed. Add this as a note in your team_10 internal checklist.

---

## Re-deploy after fix

```bash
# B1: re-upload taxonomies.php only
set -a; source .env.upress.dev; set +a
python3 scripts/upress_ftps_upload.py \
    nimrod.bio/wp-content/themes/nimrod-bio-2026/inc/taxonomies.php

# Bump theme version: 0.2.0 → 0.2.1 (so rewrites flush via inc/rewrites.php)
# Edit nimrod.bio/wp-content/themes/nimrod-bio-2026/functions.php:
#   - define( 'NB_THEME_VERSION', '0.2.0' );
#   + define( 'NB_THEME_VERSION', '0.2.1' );
python3 scripts/upress_ftps_upload.py \
    nimrod.bio/wp-content/themes/nimrod-bio-2026/functions.php

# Trigger version-keyed flush by visiting any frontend URL once
curl -sk "$UPRESS_DEV_URL_HTTP/" > /dev/null

# B2: REST DELETE per above

# Git
git add nimrod.bio/wp-content/themes/nimrod-bio-2026/inc/taxonomies.php \
        nimrod.bio/wp-content/themes/nimrod-bio-2026/functions.php

git commit -m "fix(theme): WP002-2 fix cycle 1 — world taxonomy not publicly queryable

VERDICT v1.0.0 blockers:
- B1: register_taxonomy('world', ...) had query_var='world' which renders
  WP archive on /?world=slug. Set publicly_queryable=false + query_var=false.
  REST and admin UI unaffected. WP page routing (/world/<slug>/) unaffected.
- B2: deleted leftover test service id=12 via REST.

Note: B1 was a LOD400 spec defect inherited from team_100, not implementation
drift. team_10 followed spec verbatim.

Bumps NB_THEME_VERSION 0.2.0→0.2.1 to trigger rewrite flush.

Ref: _COMMUNICATION/team_190/VERDICT_NB-S002-P002-WP002_VALIDATE_v1.0.0.md"

git push origin main:master
```

---

## Exit criteria — re-submission

עדכן `_COMMUNICATION/team_10/COMPLETION_NB-S002-P002-WP002.md` עם סקציה בסוף:

```markdown
## Fix cycle 1 (2026-05-25)

| Blocker | Status | Evidence |
|---|---|---|
| B1 world archive routing | FIXED | curl /?world=soil → 404; body has no archive/tax-world classes |
| B2 leftover service id=12 | FIXED | curl /wp/v2/services/12 → 404 |
```

ועדכן את ה-checklist המקורי:
- [x] B1 world taxonomy not publicly queryable (verified via curl)
- [x] B2 test service id=12 deleted (verified via REST)
- [x] re-deployed taxonomies.php + functions.php to dev (FTP)
- [x] NB_THEME_VERSION bumped 0.2.0 → 0.2.1
- [x] git commit + push
- [x] `validate_aos.sh` clean
- [x] regression: C1-C16 (except C5/C6 which use the WP page route, not taxonomy archive — still PASS)

## תזמון

- **Start:** מיד.
- **Target:** ≤30 דקות (B1 = 2-line edit + version bump + redeploy; B2 = single curl).
- **Block:** P003 templates cascade × 5 — עד PASS.

## Rollback

אם blocker B1 נכנס למצב שובר:
- restore `query_var => 'world'` ו-`publicly_queryable => true` (or just remove `publicly_queryable` line)
- בנה fallback: ה-`/?world=soil` יעבוד שוב, אבל יחזיר ל-archive
- דווח לטיפ_100 לפני iteration 2

---

— team_100 (nimrod-bio) — 2026-05-25 — fix cycle 1
