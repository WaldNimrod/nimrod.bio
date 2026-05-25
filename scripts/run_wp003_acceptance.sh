#!/usr/bin/env bash
# NB-S002-P003-WP003 acceptance runner (S1-S15 + baseline subset)
set -euo pipefail
ROOT="$(cd "$(dirname "$0")/.." && pwd)"
set -a
# shellcheck disable=SC1091
source "$ROOT/.env.upress.dev"
set +a

BASE="${UPRESS_DEV_URL_HTTP}"
AUTH="${WP_REST_USER}:${WP_REST_APP_PASSWORD}"
PASS=0
FAIL=0
log() { echo "[$(date '+%H:%M:%S')] $*"; }
pass() { PASS=$((PASS+1)); log "PASS $1"; }
fail() { FAIL=$((FAIL+1)); log "FAIL $1 — $2"; }

html() { curl -sk --max-time 30 -u "$AUTH" "$1" 2>/dev/null || true; }

log "S1 services REST"
S1=$(curl -sk --max-time 30 -u "$AUTH" "${WP_REST_BASE_URL}/wp/v2/services?per_page=100&_fields=id,slug,meta")
python3 - <<PY
import json, os, sys
items=json.loads('''$S1''')
need={'produce','consulting-hydro','sfa'}
found={i['slug'] for i in items if (i.get('meta') or {}).get('_nb_seed')=='v200'}
missing=need-found
print('found',sorted(found))
sys.exit(0 if not missing else 1)
PY
if [ $? -eq 0 ]; then pass S1; else fail S1 "missing seed services"; fi

log "S2 projects REST"
S2=$(curl -sk --max-time 30 -u "$AUTH" "${WP_REST_BASE_URL}/wp/v2/projects?per_page=100&_fields=id,slug,meta")
python3 - <<PY
import json, sys
items=json.loads('''$S2''')
need={'rest-x-greenhouse','hagina-shel-nimrod','coop-sharon'}
found={i['slug'] for i in items if (i.get('meta') or {}).get('_nb_seed')=='v200'}
missing=need-found
print('found',sorted(found),'missing',sorted(missing))
sys.exit(0 if not missing else 1)
PY
if [ $? -eq 0 ]; then pass S2; else fail S2 "missing seed projects"; fi

P=$(html "$BASE/services/produce/")
echo "$P" | grep -q 'single-hero' && pass S3 || fail S3 "no single-hero"

C=$(html "$BASE/services/consulting-hydro/")
echo "$C" | grep -q 'bridge-hero' && echo "$C" | grep -q 'bridge-stripe seam' && pass S4 || fail S4 "bridge hero/stripe"

S=$(html "$BASE/services/sfa/")
echo "$S" | grep -Eq 'sfa-origin|Origin · 3 שלבים' && pass S5 || fail S5 "no SFA origin flow"

echo "$P" | grep -q 't2-heritage-strip' && echo "$P" | grep -q '/about/heritage/' && pass S6 || fail S6 "heritage strip"
echo "$C" | grep -q 't2-heritage-strip' && fail S7 "heritage on consulting-hydro" || pass S7

RX=$(html "$BASE/project/rest-x-greenhouse/")
echo "$RX" | grep -q 'class="outcomes"' && pass S8 || fail S8 "no outcomes grid"

COOP=$(html "$BASE/project/coop-sharon/")
echo "$COOP" | grep -q 't3-seeking-ribbon' && pass S9 || fail S9 "no seeking ribbon"
echo "$COOP" | grep -q 'התוכנית' && ! echo "$COOP" | grep -q '>תוצאות<' && pass S10 || fail S10 "plan section"

HV=$(html "$BASE/project/hagina-shel-nimrod/")
echo "$HV" | grep -q 'קשור · מיזמים אחרים' && pass S11 || fail S11 "own-venture section missing"

echo "$P" | grep -q '<nav class="breadcrumb"' && pass S12 || fail S12 "breadcrumb nav"
echo "$P" | grep -q 'nb-t2-css' && ! echo "$P" | grep -q 'nb-t3-css' && pass "S13-t2" || fail S13 "t2 css enqueue"
echo "$RX" | grep -q 'nb-t3-css' && ! echo "$RX" | grep -q 'nb-t2-css' && pass "S13-t3" || fail S13 "t3 css enqueue"

echo "$RX" | grep -q 'stage-stamp stage-live' && pass "S14-live" || fail S14 "live stamp"
echo "$COOP" | grep -q 'stage-stamp stage-seeking-partners' && pass "S14-seeking" || fail S14 "seeking stamp"

H=$(html "$BASE/")
echo "$H" | grep -q 'shell-nav' && echo "$H" | grep -q 'shell-foot' && pass "S15-shell" || fail S15 "shell regression"

log "validate_aos.sh"
if bash "$ROOT/_aos/lean-kit/modules/validation-quality/scripts/validate_aos.sh" "$ROOT" | tail -1 | grep -q '0 FAIL'; then
  pass S15-validate
else
  fail S15-validate "validate_aos non-zero FAIL"
fi

log "RESULT: PASS=$PASS FAIL=$FAIL"
[ "$FAIL" -eq 0 ]
