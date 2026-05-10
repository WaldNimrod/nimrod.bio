#!/usr/bin/env bash
# Restore nimrod.bio local Docker from uPress-style full-site zip (files + databases/*.sql).
# - Overlays wp-content and core under nimrod.bio/
# - Imports MySQL into the Compose `db` service database
# - Rewrites production URLs → LOCAL_WP_URL (default http://localhost:8085)
# - Removes production wp-config.php so Docker env defines DB access (no secrets in tree)
#
# Usage: from repo root, with backup at sources/*.zip (override with BACKUP_ZIP=path).
set -euo pipefail
ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT"

if [ -f "$ROOT/.env" ]; then
  set -a
  # shellcheck disable=SC1091
  source "$ROOT/.env"
  set +a
fi

BACKUP_ZIP="${BACKUP_ZIP:-}"
if [ -z "$BACKUP_ZIP" ]; then
  BACKUP_ZIP=$(ls -1 "$ROOT"/sources/*.zip 2>/dev/null | head -1)
fi
if [ -z "$BACKUP_ZIP" ] || [ ! -f "$BACKUP_ZIP" ]; then
  echo "No BACKUP_ZIP found. Place a full-site .zip under sources/ or set BACKUP_ZIP=<path>" >&2
  exit 1
fi

LOCAL_WP_URL="${LOCAL_WP_URL:-http://localhost:8085}"
LOCAL_WP_URL="${LOCAL_WP_URL%/}"
PROD_URL_HTTPS="${PRODUCTION_URL:-https://nimrod.bio}"
PROD_URL_HTTPS="${PROD_URL_HTTPS%/}"
PROD_URL_HTTP="${UPRESS_PUBLIC_BASE:-http://nimrod.bio}"
PROD_URL_HTTP="${PROD_URL_HTTP%/}"

MU="${LOCAL_MYSQL_USER:-wordpress}"
MP="${LOCAL_MYSQL_PASSWORD:-wordpress}"
MD="${LOCAL_MYSQL_DATABASE:-wordpress}"

WORK="$ROOT/.restore-work/extract-$$"
mkdir -p "$WORK"
cleanup() { rm -rf "$WORK"; }
trap cleanup EXIT

echo "=== Extracting $(basename "$BACKUP_ZIP") (this may take a few minutes) ==="
unzip -q -o "$BACKUP_ZIP" -d "$WORK"

# Look for SQL inside the ZIP first, then fall back to sources/*.sql
SQL_FILE=$(find "$WORK" -maxdepth 4 -type f -name '*.sql' ! -path '*/wp-content/*' | head -1)
if [ -z "$SQL_FILE" ] || [ ! -f "$SQL_FILE" ]; then
  echo "No .sql found inside ZIP — checking sources/*.sql directly..."
  SQL_FILE=$(ls -1 "$ROOT"/sources/*.sql 2>/dev/null | head -1)
fi
if [ -z "$SQL_FILE" ] || [ ! -f "$SQL_FILE" ]; then
  echo "No SQL dump found (neither inside ZIP nor in sources/).  Place *.sql under sources/ or inside the ZIP." >&2
  exit 1
fi
echo "=== SQL: $SQL_FILE ==="

echo "=== Stopping WordPress container (file sync) ==="
docker compose -f "$ROOT/docker-compose.yml" stop wordpress 2>/dev/null || true

echo "=== Syncing files → nimrod.bio/ (excluding databases/; keep repo README) ==="
rsync -a --delete --exclude='databases/' --exclude='README.md' "$WORK/" "$ROOT/nimrod.bio/"
# Never serve SQL or backup metadata from the web root
rm -rf "$ROOT/nimrod.bio/databases" 2>/dev/null || true

echo "=== Removing production wp-config.php (Docker + .env supply DB settings) ==="
rm -f "$ROOT/nimrod.bio/wp-config.php" "$ROOT/nimrod.bio/wp-config-local.php" 2>/dev/null || true

echo "=== Recreating database and importing dump ==="
docker compose -f "$ROOT/docker-compose.yml" exec -T db mysql -h127.0.0.1 -uroot -p"${LOCAL_MYSQL_ROOT_PASSWORD:-local_root_only}" -e \
  "DROP DATABASE IF EXISTS \`$MD\`; CREATE DATABASE \`$MD\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci; GRANT ALL PRIVILEGES ON \`$MD\`.* TO '$MU'@'%'; FLUSH PRIVILEGES;" 2>/dev/null

docker compose -f "$ROOT/docker-compose.yml" exec -T db mysql -u"$MU" -p"$MP" "$MD" < "$SQL_FILE"

echo "=== Starting WordPress (bind mount ./nimrod.bio — recreate container if an old volume exists) ==="
docker compose -f "$ROOT/docker-compose.yml" up -d --force-recreate wordpress
sleep 4

SKIP_TS="--skip-plugins --skip-themes"

echo "=== WP-CLI: URL search-replace (serialized-safe) ==="
docker compose -f "$ROOT/docker-compose.yml" exec -T \
  -e "FROM1=$PROD_URL_HTTPS" -e "FROM2=$PROD_URL_HTTP" -e "TO=$LOCAL_WP_URL" \
  wordpress bash -c '
  set -e
  cd /var/www/html
  if [ ! -f wp-cli.phar ]; then
    curl -fsSL -o wp-cli.phar https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
    chmod +x wp-cli.phar
  fi
  php wp-cli.phar search-replace "$FROM1" "$TO" --all-tables --precise --allow-root '"$SKIP_TS"'
  php wp-cli.phar search-replace "${FROM1}/" "${TO}/" --all-tables --precise --allow-root '"$SKIP_TS"'
  php wp-cli.phar search-replace "$FROM2" "$TO" --all-tables --precise --allow-root '"$SKIP_TS"'
  php wp-cli.phar search-replace "${FROM2}/" "${TO}/" --all-tables --precise --allow-root '"$SKIP_TS"'
  php wp-cli.phar option update siteurl "$TO" --allow-root '"$SKIP_TS"'
  php wp-cli.phar option update home "$TO" --allow-root '"$SKIP_TS"'
  php wp-cli.phar rewrite flush --allow-root '"$SKIP_TS"'
'

echo "=== Refresh WordPress core from wordpress.org (fixes incomplete/mixed core files) ==="
docker compose -f "$ROOT/docker-compose.yml" exec -T wordpress bash -c '
  set -e
  cd /var/www/html
  if [ ! -f wp-cli.phar ]; then
    curl -fsSL -o wp-cli.phar https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
    chmod +x wp-cli.phar
  fi
  php wp-cli.phar core download --force --skip-content --allow-root
  rm -f wp-cli.phar
'

if [ ! -f "$ROOT/nimrod.bio/.htaccess" ]; then
  echo "=== Writing default WordPress .htaccess (pretty permalinks / REST) ==="
  cat >"$ROOT/nimrod.bio/.htaccess" <<'HTACCESS'
# BEGIN WordPress
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
</IfModule>
# END WordPress
HTACCESS
fi

echo "=== Done. Open $LOCAL_WP_URL ==="
