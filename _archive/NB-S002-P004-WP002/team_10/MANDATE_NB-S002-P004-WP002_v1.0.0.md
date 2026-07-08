---
type: MANDATE
from: team_100 (nimrodbio_arch)
to: team_10 (nimrodbio_build — Cursor)
wp_id: NB-S002-P004-WP002
project: nimrod-bio
milestone: V200
program: P004
date: 2026-05-25
gate: L-GATE_SPEC PASS → L-GATE_BUILD
track: A · STANDARD
priority: HIGH
predecessor: NB-S002-P004-WP001 (COMPLETE)
successor: NB-S002-P005-WP001 (QA)
spec_ref: _aos/work_packages/NB-S002-P004-WP002/LOD400_NB-S002-P004-WP002.md
---

# MANDATE — NB-S002-P004-WP002 — 301 enforcement + Yoast

**לצוות 10 (Cursor):**

WP001 סגור — 22 פוסטים על dev. עכשיו אכיפת ה-301s + הכנת SEO לקאטאובר.

## הקשר

- triage decisions: 23 redirects + 2 keeps + 6 drops (`docs/url_migration_decisions_2026-05-25.json`)
- id_mapping מ-WP001: `.migration-cache/id_mapping.json`
- מנגנון: **.htaccess additive block** (אופציה A · נעולה)
- drops: **410 Gone** (לא 301)
- sitemap: **Yoast SEO** (התקנה + regen אוטומטי)

## המפרט המלא

🎯 **`_aos/work_packages/NB-S002-P004-WP002/LOD400_NB-S002-P004-WP002.md`**

- §4 — 4 scripts + 2 docs
- §5 — מבנה ה-`.htaccess` block (markers START/END)
- §6 — idempotent deploy
- §7 — verify_redirects.py per-row
- §8 — Yoast install via REST + sitemap regen
- §9 — `docs/search_console_runbook.md` (לביצוע ב-P005-WP002, לא עכשיו)
- §10 — 17 בדיקות R1-R17

## כללי-זהב

1. **Additive block**: `# AOS-V200-redirects-START` ... `# AOS-V200-redirects-END` — לעולם לא דורסים את כל ה-.htaccess
2. **URL encoding**: Python `urllib.parse.quote(slug, safe='/')` ל-slugs עבריים → `%d7%XX`
3. **Idempotent deploy**: רצה פעמיים = .htaccess זהה
4. **Backup pre-deploy**: `.migration-cache/htaccess.<ts>.bak`
5. **`keep` rows (shook, blog)** — לא ב-.htaccess בכלל. WP מטפל
6. **Search Console = runbook בלבד**, לא ביצוע
7. **Yoast מותקן ופעיל על dev** — REST POST `/wp/v2/plugins` → `{"slug":"wordpress-seo","status":"active"}`
8. **git push לפני COMPLETION**

## Activation flow

```bash
set -a; source .env.upress.dev; set +a

# Phase A: generate
python3 scripts/redirects/generate_htaccess_block.py
# → docs/htaccess_v200_redirects.txt

# Phase B: deploy
python3 scripts/redirects/deploy_htaccess.py
# → reads docs/htaccess_v200_redirects.txt, FTPS to dev

# Phase C: verify
python3 scripts/redirects/verify_redirects.py
# → 23 redirects + 6 410 + 2 keeps tested, report to docs/redirect_verification_<date>.json

# Phase D: Yoast + sitemap
curl -X POST -u "$WP_REST_USER:$WP_REST_APP_PASSWORD" \
     "$WP_REST_BASE_URL/wp/v2/plugins" \
     -H "Content-Type: application/json" \
     -d '{"slug":"wordpress-seo","status":"active"}'

# Trigger sitemap (lazy regen if endpoint not present)
curl -sk "$UPRESS_DEV_URL_HTTP/sitemap_index.xml" > /dev/null

# Phase E: validate + git
bash _aos/lean-kit/modules/validation-quality/scripts/validate_aos.sh .
git add scripts/redirects/ docs/htaccess_v200_redirects.txt docs/search_console_runbook.md docs/redirect_verification_*.json
git commit -m "..."
git push
```

## Exit criteria

ב-COMPLETION:
- [ ] 4 scripts ב-`scripts/redirects/` tracked
- [ ] `docs/htaccess_v200_redirects.txt` נוצר ומכיל 30 RewriteRule lines (23 + 6 + 1)
- [ ] R1-R17 PASS עם evidence
- [ ] `verify_redirects.py` output: 23/23 redirects + 6/6 drops + 2/2 keeps תקינים
- [ ] Yoast active, sitemap_index.xml זמין, post-sitemap מכיל 22 פוסטים
- [ ] `docs/search_console_runbook.md` ≥5 צעדים לcutover
- [ ] uPress sections ב-.htaccess נשמרו (diff before/after — רק AOS block changed)
- [ ] `validate_aos.sh` 0 net-new FAILs
- [ ] git push

## תזמון

- Start: מיד
- Target: 1.5-2 ימי עבודה
- VALIDATE: cross-engine team_190 בסיום

— team_100 (nimrod-bio) — 2026-05-25
