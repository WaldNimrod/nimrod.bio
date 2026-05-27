---
type: LOD400_SPEC (draft pending team_100 registration)
wp_id_proposed: NB-S002-P006-WP002
project: nimrod-bio
milestone: V200
program_proposed: P006 — Content Expansion (pre-cutover)
label: "Content Batch 002 — Media migration (694 files) from old prod to new dev"
track: A_CONTENT (data migration, no theme/code changes)
author: team_110 (Domain Architect · cursor-composer-2)
date: 2026-05-26
predecessor: NB-S002-P006-WP001 (in_progress on team_99)
successor: COMPLETION_CONTENT_PHASE → P005-WP002 (cutover unfreeze)
estimated_effort: ~45 minutes (REST mechanism) OR ~10 minutes (FTPS if allowlist permits)
authorization_chain:
  - team_00 directive 2026-05-26 — "לבצע מיגרציה לכלל התוכן כולל מדיה מהאתר הישן"
  - team_110 GATE_2 architecture authority (this artifact)
---

# LOD400 — NB-S002-P006-WP002 — Media Migration

## 1. Mission

להעביר את כל **694 קבצי המדיה** מהאתר הישן (`https://www.nimrod.bio`) ל-dev החדש (`https://nimrod-bio-2026.s887.upress.link`), כולל יצירת attachment records ב-DB החדש, כך שכל הפנייה ל-`/wp-content/uploads/` ב-22 הפוסטים המהוגרים תחזיר HTTP 200 לאחר cutover.

## 2. Pre-state (verified 2026-05-26 19:30)

| מצב | Old prod | New dev |
|---|---|---|
| Media items (WP REST `/media`) | 694 | 0 |
| Posts | 24 (2 dropped per Q4 triage) | 22 |
| Pages | 7 | 10 (+ heritage/about/contact) |
| Inline `<img>` URLs in posts | point to `nimrod-bio-2026.s887.upress.link/wp-content/uploads/YYYY/MM/` | **תוכן פונה לנתיב NEW אבל הקבצים לא שם → 404** |

**הקריטיות:** בלי Batch 002, כל ה-22 פוסטים יציגו תמונות שבורות אחרי cutover.

## 3. Scope

### 3.1 In scope (חובה)

1. **694 קבצי מדיה** מכל הסוגים (image/*, application/json, video/*, etc.) — copy from old → new
2. **694 attachment records** ב-WP DB החדש — יצירה דרך REST `POST /wp-json/wp/v2/media`
3. **שמירה מדויקת על נתיבי YYYY/MM** — קובץ ב-`/uploads/2023/03/foo.jpg` במקור → חייב להיות באותו נתיב ביעד
4. **Featured image relinking** — אם פוסטים מהוגרים שמרו `featured_media: 0`, לקשור את ה-attachment החדש ל-post המתאים (אם זמין via slug/title match)
5. **Audit:** verify 0 × 404 על URLs של `/wp-content/uploads/` ב-HTML של 22 הפוסטים

### 3.2 Out of scope

- שינויי theme / CSS / templates
- פוסטים חדשים (Batch 001 territory)
- 2 הפוסטים שלא הוגרו (כוונה תחילה — drop per Q4)
- העברת comments / users / categories מהאתר הישן
- שינוי דומיין production (P005-WP002 cutover — בנפרד)
- ה-archive snapshot של האתר הישן ל-`/archive/` (V200 Q3=C — חוץ ל-batch)
- **🆕 SFA media files** (per team_00 directive 2026-05-26): SFA הופרד מ-nimrod.bio לסאב-דומיין נפרד. **filter exclusion**: כל URL שמכיל `/sfa/`, `/SFA/`, `Small.?Farms.?Agents`, `smartfieldagent` או דומה — לא להעביר. team_10 לזהות + לדלג בלוג של המיגרציה. סף סביר: ~5-15 קבצים מתוך 694.

## 4. Mechanism — REST primary, FTPS optional accelerator

### Mechanism A (PRIMARY) — REST API media upload + URL rewrite

**שלבים:**

1. **Enumerate old media:**
   ```python
   # paginate 7 × 100
   for page in range(1, 8):
       items = GET https://www.nimrod.bio/wp-json/wp/v2/media?per_page=100&page={page}
       # collect: source_url, slug, title, alt_text, caption, mime_type, date, post (parent)
   ```

2. **For each item — download + re-upload:**
   ```python
   for item in items:
       blob = GET item['source_url']  # public, no auth
       resp = POST $WP_REST_BASE_URL/wp/v2/media
           Headers: Authorization: Basic (WP_REST_USER : WP_REST_APP_PASSWORD)
                    Content-Type: <mime_type>
                    Content-Disposition: attachment; filename=<orig basename>
                    Body: blob
       # capture: new_id, new_source_url
   ```

3. **Build URL map** `{old_source_url → new_source_url}` (paths will likely differ because WP uses current YYYY/MM by default — accept this).

4. **Rewrite post content** (22 פוסטים):
   ```python
   for post_id in dev_posts:
       content = GET /wp/v2/posts/{post_id}?context=edit  # raw
       for old, new in url_map.items():
           content = content.replace(old.replace('/uploads/YYYY/MM/', '/uploads/YYYY/MM/'), new)
       POST /wp/v2/posts/{post_id}  # update content + meta
   ```

5. **Featured image relink** (where applicable — match by slug or by old URL appearing in content):
   ```python
   for post in dev_posts:
       if not post['featured_media'] and post['_legacy_featured_url']:
           new_id = url_map[post['_legacy_featured_url']].get('new_id')
           if new_id: POST /wp/v2/posts/{post_id} {"featured_media": new_id}
   ```

**Time est:** 694 × ~1s upload + ~0.5s post update overhead = ~25-30 minutes.

### Mechanism B (OPTIMIZATION — if FTPS allowlist permits)

- FTPS allowed IP: `147.235.197.125` (likely team_00 home / team_10 Mac).
- If team_10 runs from a machine matching this IP:
  1. Download all 694 files via HTTPS (parallel, ~5 min)
  2. FTPS upload preserving exact YYYY/MM tree (parallel, ~5 min)
  3. POST WP REST attachment metadata only (without file body — endpoint TBD by team_10)
- **Wins:** no URL rewrites needed (paths match source exactly).
- **Risk:** WP attachment registration without file body may require `wp media import` via WP-CLI or mu-plugin trick.

team_10 selects mechanism during impl. **No preference enforced** — whatever yields the AT below.

### Mechanism C (FALLBACK if A+B fail) — uPress migration tool

- Use uPress control panel (`UPRESS_CONTROL_PANEL_URL` from `.env.upress.dev`) to trigger Duplicator-style migration of `/uploads/` only.
- team_10 to evaluate during impl if A+B encounter blockers.

## 5. Tasks (in order)

### Task 5.1 — Pre-flight verify
- `curl -sIk $UPRESS_DEV_URL_HTTP/wp-json/wp/v2/media?per_page=1 | grep x-wp-total` → expected `0` (or current count)
- `curl -sIk https://www.nimrod.bio/wp-json/wp/v2/media?per_page=1 | grep x-wp-total` → expected `694`
- Load `.env.upress.dev` for `WP_REST_USER` + `WP_REST_APP_PASSWORD`

### Task 5.2 — Run migration script
- Located in `scripts/migration/` (create new directory if needed)
- Filename: `scripts/migration/migrate_media_v200_p006_wp002.py`
- Logs to `scripts/migration/logs/migrate_media_<ts>.log`
- Stateful: writes `scripts/migration/state/migrate_media_progress.json` with `{old_id: new_id, status}` — resumable if interrupted
- Output: `scripts/migration/state/url_map.json` (full map)

### Task 5.3 — Rewrite post HTML
- 22 פוסטים — apply `url_map` via REST POST
- Backup: write pre-state to `scripts/migration/state/pre_rewrite_posts_backup.json` (full content of all 22 before edit)
- Resume-safe: skip posts already rewritten (`_nb_url_rewritten: true` post meta marker)

### Task 5.4 — Acceptance tests
| # | Test | Expected |
|---|---|---|
| AT-M1 | `curl -sIk $UPRESS_DEV_URL_HTTP/wp-json/wp/v2/media?per_page=1 | grep x-wp-total` | `694` (or 694 + previously uploaded — track delta) |
| AT-M2 | Sample 30 random `<img src>` URLs מ-22 הפוסטים — `curl -sI` each | 30 × HTTP 200, zero 404s |
| AT-M3 | Featured images — check each migrated post has `featured_media != 0` (where source had one) | `≥90%` coverage (5% tolerance for genuine no-image posts) |
| AT-M4 | Sitemap regeneration (Yoast) — verify media URLs present | sitemap_index.xml includes media sitemap |
| AT-M5 | DB size delta — `du -sh /wp-content/uploads/` between pre and post | reasonable (~order of source ~500MB-2GB depending on media) |
| AT-M6 | 🆕 SFA-related media count uploaded | **0** (all SFA URLs skipped per §3.2 out-of-scope) — log shows `sfa_skipped_count` ≥ 1 |

### Task 5.5 — COMPLETION artifact
- `_COMMUNICATION/team_110/COMPLETION_NB-S002-P006-WP002_*.md`
- Include: migration stats (X/694 success), URL map summary, exceptions list (any 404s on source side), pointer to log

## 6. Risks

| Risk | Likelihood | Mitigation |
|---|---|---|
| Some files >50MB blocked by WP upload limit | MEDIUM | log exceptions; team_110 decides per-file (most likely none — typical photos are <10MB) |
| MIME type mismatch on upload | LOW | preserve `Content-Type` from source response |
| Cloudflare rate-limiting on bulk download from old | MEDIUM | 0.5s sleep between downloads; abort+retry on 429 |
| Old WP returns 404 for some "missing" registered media | LOW | log per-file; report in COMPLETION as `source_404_count` |
| Post HTML has URL variations (with/without `-300x300` thumbnail suffix) | MEDIUM | map source includes ALL size variants; thumb URLs `-NNNxNNN.jpg` auto-resolve via WP if main image exists |
| Disk space on new uPress install | LOW | uPress L0 plan likely has 5GB+; 694 files ~1GB typical |
| URL rewrite breaks unrelated content | LOW | only rewrite if exact prefix `https://nimrod-bio-2026.s887.upress.link/wp-content/uploads/` |

## 7. Builder selection

**routing per `feedback_team_routing_discipline` memory:**
- BUILD track = **team_10** (Cursor on Mac)
- OPS infra not required (REST + HTTPS + local script)
- team_99 was used for Batch 001 in one-time exception; Batch 002 routes correctly to team_10

## 8. Validation chain

- team_10 implements + self-tests via AT-M1 → AT-M5
- team_190 lightweight validate (if any code commits to `main` — yes, migration script under `scripts/migration/`)
- team_110 placeholder sweep + COMPLETION review
- team_00 final approval → COMPLETION_CONTENT_PHASE signature

## 8.5 🆕 Bundled cleanup tasks (from Batch 001 COMPLETION findings)

### 8.5.1 Theme SFA references removal (dead code — 7 locations)
team_99 הופיע ב-Batch 001 COMPLETION §8 — 7 references ב-theme PHP ל-SFA service שכבר לא קיים. dead code, won't trigger, אבל ראוי לניקוי:

| File | Line(s) | Pattern |
|---|---|---|
| `single-service.php` | 42, 65, 68-70, 112, 117 | 6 × `'sfa' === $slug` conditionals |
| `template-parts/t2-hero.php` | 68 | SFA-specific image caption |
| `inc/template-helpers.php` | 137-138 | Static seed entry for `sfa` service |

**Task:** `git rm`/edit each — ISOLATED_BRANCH theme touch. team_190 lightweight validate.

### 8.5.2 Yoast meta template "Unless" inclusion (AT-1 PARTIAL finding)
team_99 Batch 001 AT-1: PHP renders ✓ (2), אבל Yoast `wpseo_titles` meta_template = `%title% - nimrod.bio · V200 dev` — חסר "Unless".

**Task:** עדכון Yoast settings דרך admin UI או REST. שיטה לבחירת team_10. Acceptance: home + 5 משטחים נוספים — Yoast meta title או description כולל "Unless".

## 9. Out-of-scope reminders

- אסור לגעת ב-system.css / shell.css / theme.json
- אסור לתקן Lighthouse miss
- אסור לפתוח cutover MANDATE
- אסור לעצב את `/archive/` snapshot של האתר הישן (Q3=C — work item נפרד)

— team_110 (cursor-composer-2) — 2026-05-26
