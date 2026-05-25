---
type: research_note
author: team_100
date: 2026-05-25
status: v1.0-stage1-collection
---

# URL migration matrix — V200 site rebuild

## Files

- `url_migration_matrix_v1.csv` — every published `post` and `page` from the legacy site (31 rows: 7 pages + 24 posts).

## Stage 1 (done — this file)

Collected all 31 published URLs from the local DB (`qvj_posts` table, `post_status='publish'`). Slugs are stored URL-encoded in WordPress (`%d7%9e...`); the CSV holds the decoded Hebrew form for human triage. Matrix processing must re-encode when generating Redirection plugin rules or .htaccess directives.

## Triage protocol (Nimrod input — Stage 2)

For each row, fill the `decision` column with one of:

| Decision | Meaning | Action in Stage 3 |
|---|---|---|
| `keep` | URL stays identical on new site, same content | No 301 needed; verify content imports cleanly |
| `redirect` | URL changes; preserve SEO value | Fill `new_url`; row generates a 301 |
| `drop` | URL retires permanently | Generates a 410 Gone (or 301 to nearest topic page if SEO is high) |

For posts that don't transfer at all (the ~480 historical archive items confirmed out of scope), they remain `drop` by default — no row exists for them in this CSV.

## Stage 2 (blocked on Nimrod)

Nimrod hand-fills `decision` and `new_url`. Suggested flow:
1. Open CSV in spreadsheet (Numbers / Sheets / Excel — UTF-8 safe)
2. For each row: review `old_title`, decide keep / redirect / drop
3. If `redirect`, fill `new_url` with the new path (e.g. `/blog/petriot-yaar-bagina/` instead of URL-encoded Hebrew)
4. Save back as `url_migration_matrix_v1_FILLED.csv` and notify team_100

## Stage 3 (team_110 — part of NB-S002-P004-WP002)

team_100 will convert the filled CSV to:
1. **Redirection plugin export** (JSON or CSV) for import in the new site
2. **Backup .htaccess rules** in case the plugin is disabled
3. **Validator script** — fetches every `old_url` and asserts the expected outcome (200 for keep, 301 with correct Location for redirect, 410 for drop)

## Counts (Stage 1 snapshot)

```
post / publish   : 24
page / publish   :  7
─────────────────────
total            : 31
```

Plus drafts/private (not in CSV but noted for Nimrod awareness):
```
post / draft     :  7
post / private   :  1
page / draft     :  5
page / private   : 11
```

Privates may include SEO-load-bearing landing pages (e.g. shop drafts) — review at Stage 2.

## Special slugs flagged for SEO load-bearing review

These appeared in the prior `llms.txt` listing with high topical traffic patterns — prioritize their `decision`:

- `transplantinfo2020` — landing page, ranks for "מועדי שתילה"
- `direct-seeding` — guide
- `transplants2020` — historical campaign
- `transpantphotoindex` — visual guide (typo in slug, intentional)
- `transplant-spread` — campaign
- `harish2021` — campaign
- `common` — partner page
- `video1` — promo video
- `crop-book` — page resource
- `blog` — root archive
- `grow/*` — guides subtree (2 pages)
- `shook` — market page (sub-path under `/הזמנת-סל-ירקות-.../shook/`)

## Notes on hierarchical pages

The CSV currently shows pages as `/<slug>/`, but WordPress stores parent-child relationships in `post_parent`. For pages like `shook` whose actual URL is `/הזמנת-סל-ירקות-.../shook/`, the full path will be reconstructed at Stage 3 from `post_parent` lookups. Stage 1 keeps the flat slug for triage readability.
