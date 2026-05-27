---
id: MSG-HUB-20260526-006
from_team: team_110
to_team: team_99
type: instruction
subject: "SFA cleanup — precise slug list (delete + skip create pending team_00 clarification)"
date: 2026-05-26
related_wp: NB-S002-P006-WP001
expects_response: false
---

# SFA cleanup — exact instructions

הסקירה שלי על dev מצאה:

## Services CPT (10 entries) — 2 × SFA to DELETE

| slug | title | action |
|---|---|---|
| `seed-t7-sfa` | SFA | **DELETE** |
| `sfa` | SFA · Small Farms Agents | **DELETE** |
| seed-t7-consulting-hydro | ייעוץ הידרו | keep |
| seed-t7-produce | תוצרת מקצועית | keep |
| teaching | הוראה | keep |
| tiktrack | tiktrack | keep |
| consulting-agro | ייעוץ · אגרו | keep |
| consulting-hydro | ייעוץ · תכנון חממה | keep |
| nursery | משתלה | keep |
| bcs | BCS · שירותי שטח | keep |

**Mechanism:** REST DELETE per slug:
```bash
set -a; source /data/projects/nimrod-bio/nimrod.bio/.env.upress.dev; set +a
for slug in seed-t7-sfa sfa; do
  id=$(curl -sk -u "$WP_REST_USER:$WP_REST_APP_PASSWORD" \
       "$WP_REST_BASE_URL/services?slug=$slug" | python3 -c "import sys,json; d=json.load(sys.stdin); print(d[0]['id'] if d else '')")
  if [ -n "$id" ]; then
    curl -sk -X DELETE -u "$WP_REST_USER:$WP_REST_APP_PASSWORD" \
      "$WP_REST_BASE_URL/services/$id?force=true"
    echo "deleted service slug=$slug id=$id"
  fi
done
```

## Projects CPT (5 entries) — NO SFA project exists

| slug | title |
|---|---|
| coop-sharon | קואופרטיב חממות קטנות · השרון |
| hagina-shel-nimrod | הגינה של נמרוד |
| restaurant-supply | מסירה למסעדות |
| farm-y-bcs | חווה Y · BCS |
| rest-x-greenhouse | חממת מסעדת X |

**No `sfa` or `smallfarmsagents` slug exists in projects.** team_00 said "באתר החדש למערכת יש רשומה רגילה כפרויקט" — ambiguous (descriptive vs aspirational).

**Action:** **DO NOT create** a project:sfa entry. team_110 is escalating to team_00 for clarification:
- (X1) The statement was descriptive but the project record is missing — team_110 will create after team_00 confirms slug + fields
- (X2) The statement was aspirational — team_00 will provide content + team_110/team_10 will create in a follow-up batch
- (X3) "smallfarmsagents" blog post (from your §5.5 batch 001) is sufficient representation — no project CPT needed

This clarification is **OUT OF SCOPE for Batch 001**. Do not block on it.

## Updated §5.5 sequence

1. ✅ `seed_wp006_p006_wp001_placeholders.py` — 11 placeholder posts
2. ✅ DELETE service slugs `seed-t7-sfa`, `sfa` (script above)
3. ❌ ~~CREATE project:sfa~~ — SKIP (team_00 clarification pending; not blocking)

## Audit additions for §5.6 ATs

Add to your AT sweep:
- **AT-S1** — `GET /wp-json/wp/v2/services?slug=seed-t7-sfa` returns 404 (deleted)
- **AT-S2** — `GET /wp-json/wp/v2/services?slug=sfa` returns 404 (deleted)
- **AT-S3** — `GET /wp-json/wp/v2/services?per_page=20` returns 8 entries (was 10, minus 2 SFA)

## Theme references to SFA

There may be theme PHP that references SFA service (e.g., footer service grid, T2 template). After deletion, ensure no PHP rendering depends on SFA service existence (look for hardcoded queries on `slug=sfa` or `slug=seed-t7-sfa`).

Quick grep:
```bash
cd /data/projects/nimrod-bio/nimrod.bio
grep -rnE "['\"](sfa|seed-t7-sfa|Small.?Farms.?Agents)['\"]" wp-content/themes/nimrod-bio-2026/ 2>/dev/null | head -20
```

If found — list in COMPLETION (§5.8) as `theme_sfa_references_remaining` — team_110 will address in batch 002 or follow-up.

— team_110 — 2026-05-26
