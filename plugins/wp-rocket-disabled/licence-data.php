<?php
/**
 * WP Rocket License Data
 * 
 * This file contains license data for WP Rocket plugin.
 * Created during site restoration to prevent fatal errors.
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define license constants if not already defined
if ( ! defined( 'WP_ROCKET_KEY' ) ) {
    define( 'WP_ROCKET_KEY', '' );
}

if ( ! defined( 'WP_ROCKET_EMAIL' ) ) {
    define( 'WP_ROCKET_EMAIL', '' );
}

if ( ! defined( 'WP_ROCKET_LICENSE' ) ) {
    define( 'WP_ROCKET_LICENSE', '' );
}

// License status
if ( ! defined( 'WP_ROCKET_LICENSE_STATUS' ) ) {
    define( 'WP_ROCKET_LICENSE_STATUS', 'invalid' );
}

// License data array
$wp_rocket_license_data = array(
    'key' => '',
    'email' => '',
    'status' => 'invalid',
    'expires' => '',
    'type' => '',
    'version' => ''
);

// Make license data available globally
if ( ! isset( $GLOBALS['wp_rocket_license_data'] ) ) {
    $GLOBALS['wp_rocket_license_data'] = $wp_rocket_license_data;
}
