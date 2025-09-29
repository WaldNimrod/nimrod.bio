<?php
/**
 * WordPress Environment Configuration
 * ===================================
 * 
 * This file manages environment-specific settings
 * Allows seamless deployment from development to production
 * without code changes.
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Environment Detection
 * =====================
 * Automatically detects environment based on server settings
 */
function wp_get_environment() {
    // Check for environment variable first
    if ( defined( 'WP_ENV' ) ) {
        return WP_ENV;
    }
    
    // Check for Docker environment
    if ( getenv( 'WORDPRESS_ENV' ) ) {
        return getenv( 'WORDPRESS_ENV' );
    }
    
    // Check for local development indicators
    if ( 
        strpos( $_SERVER['HTTP_HOST'] ?? '', 'localhost' ) !== false ||
        strpos( $_SERVER['HTTP_HOST'] ?? '', '127.0.0.1' ) !== false ||
        strpos( $_SERVER['HTTP_HOST'] ?? '', '.local' ) !== false ||
        strpos( $_SERVER['HTTP_HOST'] ?? '', '.dev' ) !== false
    ) {
        return 'development';
    }
    
    // Check for staging indicators
    if ( 
        strpos( $_SERVER['HTTP_HOST'] ?? '', 'staging' ) !== false ||
        strpos( $_SERVER['HTTP_HOST'] ?? '', 'test' ) !== false ||
        strpos( $_SERVER['HTTP_HOST'] ?? '', 'preview' ) !== false
    ) {
        return 'staging';
    }
    
    // Default to production
    return 'production';
}

// Set environment
$wp_environment = wp_get_environment();

/**
 * Environment-Specific Configuration
 * ==================================
 */
switch ( $wp_environment ) {
    case 'development':
        // Development settings
        define( 'WP_DEBUG', true );
        define( 'WP_DEBUG_LOG', true );
        define( 'WP_DEBUG_DISPLAY', false );
        define( 'SCRIPT_DEBUG', true );
        define( 'SAVEQUERIES', true );
        
        // Database settings for Docker
        define( 'DB_NAME', 'wordpress' );
        define( 'DB_USER', 'wordpress' );
        define( 'DB_PASSWORD', 'wordpress123' );
        define( 'DB_HOST', 'mysql:3306' );
        
        // Cache settings - minimal for development
        define( 'WP_CACHE', false );
        define( 'COMPRESS_CSS', false );
        define( 'COMPRESS_SCRIPTS', false );
        define( 'ENFORCE_GZIP', false );
        
        // File editing allowed in development
        define( 'DISALLOW_FILE_EDIT', false );
        define( 'DISALLOW_FILE_MODS', false );
        
        // Memory and limits
        define( 'WP_MEMORY_LIMIT', '512M' );
        define( 'WP_MAX_MEMORY_LIMIT', '1024M' );
        
        break;
        
    case 'staging':
        // Staging settings
        define( 'WP_DEBUG', true );
        define( 'WP_DEBUG_LOG', true );
        define( 'WP_DEBUG_DISPLAY', false );
        define( 'SCRIPT_DEBUG', false );
        
        // Database settings - will be overridden by production config
        define( 'DB_NAME', 'staging_db' );
        define( 'DB_USER', 'staging_user' );
        define( 'DB_PASSWORD', 'staging_password' );
        define( 'DB_HOST', 'localhost' );
        
        // Cache settings - moderate for staging
        define( 'WP_CACHE', true );
        define( 'COMPRESS_CSS', true );
        define( 'COMPRESS_SCRIPTS', true );
        
        // File editing restricted
        define( 'DISALLOW_FILE_EDIT', true );
        define( 'DISALLOW_FILE_MODS', false );
        
        break;
        
    case 'production':
    default:
        // Production settings
        define( 'WP_DEBUG', false );
        define( 'WP_DEBUG_LOG', false );
        define( 'WP_DEBUG_DISPLAY', false );
        define( 'SCRIPT_DEBUG', false );
        
        // Database settings - will be overridden by production config
        define( 'DB_NAME', 'production_db' );
        define( 'DB_USER', 'production_user' );
        define( 'DB_PASSWORD', 'production_password' );
        define( 'DB_HOST', 'localhost' );
        
        // Cache settings - full optimization for production
        define( 'WP_CACHE', true );
        define( 'COMPRESS_CSS', true );
        define( 'COMPRESS_SCRIPTS', true );
        define( 'ENFORCE_GZIP', true );
        
        // Security settings
        define( 'DISALLOW_FILE_EDIT', true );
        define( 'DISALLOW_FILE_MODS', true );
        
        // Performance settings
        define( 'WP_MEMORY_LIMIT', '256M' );
        define( 'WP_MAX_MEMORY_LIMIT', '512M' );
        
        break;
}

/**
 * Common Settings (All Environments)
 * ==================================
 */

// Database charset
define( 'DB_CHARSET', 'utf8mb4' );
define( 'DB_COLLATE', 'utf8mb4_unicode_ci' );

// Security keys (same for all environments)
define( 'AUTH_KEY',         '_l@|o,+FiT=g})Hxt#LY8L3Fv9(v(DGVj(P@sgwBZki/_6FzG7js]BxDQox?Nqxf' );
define( 'SECURE_AUTH_KEY',  '#!xGEiG`?|uPGh|}mf[R$6mH!q|&`WLE|Ytn8g95!7fMdp+CeI+iVp|s-t~RmJ<&' );
define( 'LOGGED_IN_KEY',    'v<3~980Ur-31q9 ;9beG5O%Gawp9a+DC%zK^ir6or|4)tb%cwW,@_P6#n&fVer++' );
define( 'NONCE_KEY',        '4d`b w1DG(/W>DPu]bGE9!N)=O#(|yu($#<xiptlx] 6cXUWZWvDcFg 7>|NS5Q/' );
define( 'AUTH_SALT',        '2I3#&g9E-E%Kaj_q;g<.,L z4d-Cs!c6kMZ#|ld*Y~f(/`AVTi~ MX|wbuLpXOAp' );
define( 'SECURE_AUTH_SALT', 'C&fts]mY-/!&pKcCor|EZEgk9s:b8f,;~K[nrx+ft-T*SWXEng-0L|AQkZF-O->C' );
define( 'LOGGED_IN_SALT',   '&q1sOYI-ac?)TKl7X$DcbsBwm-8u<K7B<fz_!u3sas|RcM:hX>so^t|*N!F8oTy8' );
define( 'NONCE_SALT',       'tTG[S+m]$&oCOfT=@8#X;p^_@C0-OAb8 `3ib:e+8oKY~Kz-M3&K6Sx%oQUAbv}B' );

// Table prefix
$table_prefix = 'qvj_';

// WordPress URLs (will be auto-detected)
if ( ! defined( 'WP_HOME' ) ) {
    define( 'WP_HOME', 'http://' . ( $_SERVER['HTTP_HOST'] ?? 'localhost' ) );
}
if ( ! defined( 'WP_SITEURL' ) ) {
    define( 'WP_SITEURL', WP_HOME );
}

// File permissions
define( 'FS_METHOD', 'direct' );
define( 'FS_CHMOD_DIR', ( 0755 & ~ umask() ) );
define( 'FS_CHMOD_FILE', ( 0644 & ~ umask() ) );

// Multisite settings
define( 'WPMS_ON', true );
define( 'WPMS_SMTP_PASS', 'ajw53gtubf04n' );

// Log environment for debugging
if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
    error_log( "WordPress Environment: {$wp_environment}" );
}

// Set environment constant for use in themes/plugins
define( 'WP_ENVIRONMENT', $wp_environment );





