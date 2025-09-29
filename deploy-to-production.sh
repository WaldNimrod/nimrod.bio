#!/bin/bash
# WordPress Production Deployment Script
# =====================================
# 
# This script prepares the WordPress site for production deployment
# without requiring code changes.

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Project directory
PROJECT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$PROJECT_DIR"

print_status "Starting WordPress production deployment preparation..."

# Create deployment directory
DEPLOY_DIR="./deployment-package"
if [ -d "$DEPLOY_DIR" ]; then
    print_warning "Removing existing deployment directory..."
    rm -rf "$DEPLOY_DIR"
fi

print_status "Creating deployment package..."

# Create deployment directory structure
mkdir -p "$DEPLOY_DIR"
mkdir -p "$DEPLOY_DIR/wp-content"
mkdir -p "$DEPLOY_DIR/wp-content/themes"
mkdir -p "$DEPLOY_DIR/wp-content/plugins"
mkdir -p "$DEPLOY_DIR/wp-content/uploads"

# Copy WordPress core files (excluding development-specific files)
print_status "Copying WordPress core files..."
cp -r wp-content/themes/* "$DEPLOY_DIR/wp-content/themes/"
cp -r wp-content/plugins/* "$DEPLOY_DIR/wp-content/plugins/"
cp -r wp-content/uploads/* "$DEPLOY_DIR/wp-content/uploads/"

# Copy configuration files
print_status "Copying configuration files..."
cp wp-config-environment.php "$DEPLOY_DIR/"
cp wp-config.php "$DEPLOY_DIR/"

# Create production-specific wp-config.php
print_status "Creating production wp-config.php..."
cat > "$DEPLOY_DIR/wp-config-production.php" << 'EOF'
<?php
/**
 * WordPress Production Configuration
 * =================================
 * 
 * This file should be renamed to wp-config.php on the production server
 * and the database credentials should be updated.
 */

// Set environment to production
define( 'WP_ENV', 'production' );

// Load environment-specific configuration
require_once( dirname( __FILE__ ) . '/wp-config-environment.php' );

/* That's all, stop editing! Happy blogging. */
/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
EOF

# Create deployment instructions
print_status "Creating deployment instructions..."
cat > "$DEPLOY_DIR/DEPLOYMENT_INSTRUCTIONS.md" << 'EOF'
# WordPress Production Deployment Instructions
=============================================

## Quick Deployment Steps

### 1. Upload Files
Upload all files from this package to your production server's WordPress directory.

### 2. Database Configuration
Rename `wp-config-production.php` to `wp-config.php` and update the database credentials:

```php
// Update these lines in wp-config.php
define('DB_NAME', 'your_production_database');
define('DB_USER', 'your_production_user');
define('DB_PASSWORD', 'your_production_password');
define('DB_HOST', 'localhost'); // or your database host
```

### 3. Import Database
Import your database backup to the production database.

### 4. Update URLs (if needed)
If your production URL is different, run these SQL commands:

```sql
UPDATE wp_options SET option_value = 'https://yourdomain.com' WHERE option_name = 'home';
UPDATE wp_options SET option_value = 'https://yourdomain.com' WHERE option_name = 'siteurl';
UPDATE wp_posts SET post_content = REPLACE(post_content, 'http://localhost:8081', 'https://yourdomain.com');
UPDATE wp_postmeta SET meta_value = REPLACE(meta_value, 'http://localhost:8081', 'https://yourdomain.com');
```

### 5. Set File Permissions
```bash
find /path/to/wordpress -type d -exec chmod 755 {} \;
find /path/to/wordpress -type f -exec chmod 644 {} \;
chmod 600 wp-config.php
```

### 6. Configure Web Server
Ensure your web server is configured to serve WordPress files.

## Environment Features

This deployment package includes automatic environment detection:

- **Development**: localhost, .local, .dev domains
- **Staging**: staging, test, preview subdomains  
- **Production**: All other domains

No code changes needed when moving between environments!

## Cache Configuration

- **Development**: Cache disabled for easy debugging
- **Staging**: Moderate caching enabled
- **Production**: Full caching and optimization enabled

## Security Features

- File editing disabled in production
- Debug mode disabled in production
- Optimized memory limits for production
- Secure file permissions

## Support

If you encounter issues:
1. Check file permissions
2. Verify database connection
3. Check web server error logs
4. Ensure all files were uploaded correctly
EOF

# Create database export script
print_status "Creating database export script..."
cat > "$DEPLOY_DIR/export-database.sh" << 'EOF'
#!/bin/bash
# Database Export Script for Production Deployment

# Configuration
DB_NAME="wordpress"
DB_USER="wordpress"
DB_PASSWORD="wordpress123"
DB_HOST="localhost:3307"

# Export database
echo "Exporting database..."
mysqldump -h localhost -P 3307 -u "$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" > database-backup.sql

echo "Database exported to database-backup.sql"
echo "Upload this file to your production server and import it."
EOF

chmod +x "$DEPLOY_DIR/export-database.sh"

# Create .htaccess for production
print_status "Creating production .htaccess..."
cat > "$DEPLOY_DIR/.htaccess" << 'EOF'
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

# Security Headers
<IfModule mod_headers.c>
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options DENY
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
</IfModule>

# Cache Control
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType text/css "access plus 1 year"
    ExpiresByType application/javascript "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/svg+xml "access plus 1 year"
</IfModule>

# Gzip Compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
</IfModule>
EOF

# Create package info
print_status "Creating package information..."
cat > "$DEPLOY_DIR/PACKAGE_INFO.txt" << EOF
WordPress Production Deployment Package
======================================

Created: $(date)
Source: Development Environment
Environment: Auto-detecting (Development/Staging/Production)

Contents:
- WordPress themes and plugins
- Configuration files
- Database export script
- Deployment instructions
- Production .htaccess

Total Size: $(du -sh "$DEPLOY_DIR" | cut -f1)

Next Steps:
1. Run ./export-database.sh to export database
2. Upload all files to production server
3. Follow DEPLOYMENT_INSTRUCTIONS.md
4. Import database on production server
5. Update database credentials in wp-config.php

No code changes required!
EOF

# Create final package
print_status "Creating final deployment package..."
cd "$PROJECT_DIR"
tar -czf "wordpress-production-$(date +%Y%m%d-%H%M%S).tar.gz" -C "$DEPLOY_DIR" .

print_success "Deployment package created successfully!"
echo ""
echo "📦 Package: wordpress-production-$(date +%Y%m%d-%H%M%S).tar.gz"
echo "📁 Contents: $DEPLOY_DIR/"
echo "📋 Instructions: $DEPLOY_DIR/DEPLOYMENT_INSTRUCTIONS.md"
echo ""
print_status "Next steps:"
echo "1. Run: cd $DEPLOY_DIR && ./export-database.sh"
echo "2. Upload the .tar.gz file to your production server"
echo "3. Follow the deployment instructions"
echo ""
print_success "Ready for production deployment! 🚀"





