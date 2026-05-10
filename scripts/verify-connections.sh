#!/usr/bin/env bash
# nimrod.bio — verify local Docker stack (smoke test per LOD400 §3.5).
# Does not print secrets.
set -u
ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT"

LOCAL_WP_PORT="${LOCAL_WP_PORT:-8085}"
LOCAL_WP_URL="${LOCAL_WP_URL:-http://localhost:8085}"
LOCAL_MYSQL_HOST_PORT="${LOCAL_MYSQL_HOST_PORT:-3309}"

FAIL=0
ok() { echo "[PASS] $*"; }
fail() { echo "[FAIL] $*" >&2; FAIL=1; }

echo "=== nimrod.bio — verify-connections ($(date -u +%Y-%m-%dT%H:%MZ)) ==="

# 1. Docker containers running
if ! docker compose ps --status running 2>/dev/null | grep -q nimrod-bio-wp; then
  fail "WordPress container not running — start with: docker compose up -d"
else
  ok "Docker: nimrod-bio-wp is running"
fi

if ! docker compose ps --status running 2>/dev/null | grep -q nimrod-bio-db; then
  fail "MySQL container not running"
else
  ok "Docker: nimrod-bio-db is running"
fi

# 2. MySQL connectivity from inside container
MU="${LOCAL_MYSQL_USER:-wordpress}"
MP="${LOCAL_MYSQL_PASSWORD:-wordpress}"
if docker compose exec -T db mysql -u"$MU" -p"$MP" -e "SELECT 1" --silent 2>/dev/null | grep -q 1; then
  ok "MySQL (container): SELECT 1 as $MU"
else
  fail "MySQL (container): query failed — check LOCAL_MYSQL_* in .env"
fi

# 3. HTTP 200 on localhost:8085
HTTP_CODE=$(curl -sS -o /dev/null -w "%{http_code}" --connect-timeout 5 --max-redirs 0 "${LOCAL_WP_URL}/" 2>/dev/null || echo "000")
if [[ "$HTTP_CODE" =~ ^(200|301|302)$ ]]; then
  ok "HTTP local ${LOCAL_WP_URL}/ → ${HTTP_CODE}"
else
  fail "HTTP local ${LOCAL_WP_URL}/ → ${HTTP_CODE} (expected 200/301/302)"
fi

# 4. wp-json valid JSON
REST_TMP=$(mktemp)
REST_CODE=$(curl -sS -o "$REST_TMP" -w "%{http_code}" --connect-timeout 5 --max-redirs 0 "${LOCAL_WP_URL}/wp-json/" 2>/dev/null || echo "000")
if [[ "$REST_CODE" != "200" ]]; then
  fail "REST ${LOCAL_WP_URL}/wp-json/ → ${REST_CODE} (expected 200; fix siteurl/home in wp_options if you see 301)"
elif ! head -c 1 "$REST_TMP" | grep -q '{'; then
  fail "REST ${LOCAL_WP_URL}/wp-json/ body is not JSON"
else
  ok "REST ${LOCAL_WP_URL}/wp-json/ → 200 (JSON body)"
fi
rm -f "$REST_TMP"

# 5. validate_aos.sh → 0 FAIL
VAL_SCRIPT="$ROOT/_aos/lean-kit/modules/validation-quality/scripts/validate_aos.sh"
if [ ! -f "$VAL_SCRIPT" ]; then
  fail "validate_aos.sh not found at expected path"
else
  VAL_LOG=$(mktemp)
  if bash "$VAL_SCRIPT" "$ROOT" >"$VAL_LOG" 2>&1; then
    if grep -q "0 FAIL" "$VAL_LOG"; then
      ok "validate_aos.sh → 0 FAIL"
    else
      fail "validate_aos.sh unexpected output (no '0 FAIL' line)"
      tail -20 "$VAL_LOG" >&2
    fi
  else
    fail "validate_aos.sh exited non-zero"
    tail -20 "$VAL_LOG" >&2
  fi
  rm -f "$VAL_LOG"
fi

echo "=== Result: $([ "$FAIL" -eq 0 ] && echo ALL_CHECKS_PASSED || echo SOME_CHECKS_FAILED) ==="
exit "$FAIL"
