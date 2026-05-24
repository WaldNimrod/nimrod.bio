#!/usr/bin/env bash
# wp_dev_baseline.sh — NB-S002-P001-WP001
# Idempotent baseline configuration for the nimrod-bio-2026 dev WordPress install.
# All operations use the WP REST API + admin session cookie, or lftp for file uploads.
#
# Prerequisites:
#   1. Valid admin session cookies in /tmp/wp_cookies.txt
#      (run: bash scripts/wp_dev_login.sh to refresh)
#   2. lftp installed (brew install lftp)
#   3. .env.upress.dev sourced (set -a; source .env.upress.dev; set +a)
#
# Usage: bash scripts/wp_dev_baseline.sh

set -euo pipefail

DEV_URL="http://nimrod-bio-2026.s887.upress.link"
COOKIES="/tmp/wp_cookies.txt"

log() { echo "[$(date '+%H:%M:%S')] $*"; }

# ─── Helper: get fresh REST nonce ────────────────────────────────────────────
get_nonce() {
  curl -sk -b "$COOKIES" "${DEV_URL}/wp-admin/admin-ajax.php?action=rest-nonce"
}

# ─── Helper: admin form nonce ─────────────────────────────────────────────────
get_form_nonce() {
  local page="$1"
  python3 - << PYEOF
import subprocess, re
r = subprocess.run(
    ['curl', '-sk', '-b', '${COOKIES}', '${DEV_URL}/wp-admin/${page}'],
    capture_output=True
)
m = re.search(r'name="_wpnonce"\s+value="([^"]+)"', r.stdout.decode('utf-8', errors='replace'))
print(m.group(1) if m else '')
PYEOF
}

# ─── Step 1: verify admin session ─────────────────────────────────────────────
log "Step 1: verifying admin session"
NONCE=$(get_nonce)
ME=$(curl -sk -b "$COOKIES" -H "X-WP-Nonce: $NONCE" \
  "${DEV_URL}/wp-json/wp/v2/users/me" | python3 -c "import sys,json; d=json.load(sys.stdin); print(d.get('name','NOT_LOGGED_IN'))")
if [ "$ME" = "NOT_LOGGED_IN" ]; then
  echo "ERROR: not authenticated — re-run wp_dev_login.sh first"; exit 1
fi
log "  logged in as: $ME"

# ─── Step 2: site settings ────────────────────────────────────────────────────
log "Step 2: setting site title and description"
curl -sk -b "$COOKIES" -H "X-WP-Nonce: $NONCE" \
  -H "Content-Type: application/json" -X POST \
  "${DEV_URL}/wp-json/wp/v2/settings" \
  -d '{"title":"nimrod.bio · V200 dev","description":"DEV — do not index","default_comment_status":"closed","default_ping_status":"closed"}' > /dev/null
log "  done"

# ─── Step 3: general settings (locale + noindex) ──────────────────────────────
log "Step 3: setting locale (he_IL) + search engine discouragement"
GEN_NONCE=$(get_form_nonce "options-general.php")
python3 - << PYEOF
import subprocess, urllib.parse
data = {
    '_wpnonce': '${GEN_NONCE}',
    '_wp_http_referer': '/wp-admin/options-general.php',
    'blogname': 'nimrod.bio · V200 dev',
    'blogdescription': 'DEV — do not index',
    'admin_email': 'admin@mezoo.co',
    'WPLANG': 'he_IL',
    'blog_public': '0',
    'submit': 'Save Changes',
}
result = subprocess.run(
    ['curl', '-sk', '-b', '${COOKIES}', '-c', '${COOKIES}',
     '-X', 'POST', '--data', urllib.parse.urlencode(data), '-w', '\n%{http_code}',
     '${DEV_URL}/wp-admin/options-general.php'],
    capture_output=True
)
lines = result.stdout.decode('utf-8', errors='replace').strip().split('\n')
code = lines[-1]
print(f"  general settings save: HTTP {code}")
PYEOF

# ─── Step 4: permalink structure ──────────────────────────────────────────────
log "Step 4: setting permalink structure to /blog/%postname%/"
PERM_NONCE=$(get_form_nonce "options-permalink.php")
python3 - << PYEOF
import subprocess, urllib.parse
data = {
    '_wpnonce': '${PERM_NONCE}',
    '_wp_http_referer': '/wp-admin/options-permalink.php',
    'selection': 'custom',
    'permalink_structure': '/blog/%postname%/',
    'category_base': '',
    'tag_base': '',
    'submit': 'Save Changes',
}
result = subprocess.run(
    ['curl', '-sk', '-b', '${COOKIES}', '-c', '${COOKIES}',
     '-X', 'POST', '--data', urllib.parse.urlencode(data), '-w', '\n%{http_code}',
     '${DEV_URL}/wp-admin/options-permalink.php'],
    capture_output=True
)
lines = result.stdout.decode('utf-8', errors='replace').strip().split('\n')
code = lines[-1]
print(f"  permalink save: HTTP {code}")
PYEOF

# ─── Step 5: deploy MU plugin via lftp ────────────────────────────────────────
log "Step 5: deploying MU plugin nb-dev-app-passwords.php via FTPS"
log "  NOTE: requires correct FTP credentials in .env.upress.dev"
if [ -n "${UPRESS_FTP_HOST:-}" ] && [ -n "${UPRESS_FTP_PASS:-}" ]; then
  lftp -e "
    set ftp:ssl-allow yes;
    set ftp:ssl-force yes;
    set ssl:verify-certificate no;
    put -O wp-content/mu-plugins/ nimrod.bio/wp-content/mu-plugins/nb-dev-app-passwords.php;
    quit
  " -u "${UPRESS_FTP_USER},${UPRESS_FTP_PASS}" "${UPRESS_FTP_HOST}"
  log "  MU plugin deployed"
else
  log "  SKIP: FTP env vars not set — deploy manually via uPress file manager"
fi

# ─── Step 6: add WP_ENVIRONMENT_TYPE to wp-config.php ────────────────────────
log "Step 6: WP_ENVIRONMENT_TYPE = 'local' in wp-config.php"
log "  MANUAL STEP: add the following line to wp-config.php (before 'That's all, stop editing!'):"
log "    define( 'WP_ENVIRONMENT_TYPE', 'local' );"
log "  Requires FTP access or uPress file manager."

# ─── Step 7: verify permalink with test post ──────────────────────────────────
log "Step 7: verifying permalink with hello-world post"
NONCE2=$(get_nonce)
POST_LINK=$(curl -sk -b "$COOKIES" -H "X-WP-Nonce: $NONCE2" \
  "${DEV_URL}/wp-json/wp/v2/posts?slug=hello-world" | \
  python3 -c "import sys,json; posts=json.load(sys.stdin); print(posts[0]['link'] if posts else 'NOT_FOUND')" 2>/dev/null)
log "  hello-world URL: $POST_LINK"
if echo "$POST_LINK" | grep -q "/blog/hello-world/"; then
  log "  permalink OK ✓"
else
  log "  WARNING: expected /blog/hello-world/, got: $POST_LINK"
fi

# ─── Step 8: validate_aos.sh ──────────────────────────────────────────────────
log "Step 8: validate_aos.sh"
REPO_ROOT="$(cd "$(dirname "$0")/.." && pwd)"
bash "$REPO_ROOT/_aos/lean-kit/modules/validation-quality/scripts/validate_aos.sh" "$REPO_ROOT"

log "Baseline complete."
