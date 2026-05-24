<?php
/**
 * Plugin Name: NB Dev — App Passwords over HTTP
 * Description: Allows WP Application Passwords on the uPress dev URL (no valid SSL).
 *              Active only when WP_ENVIRONMENT_TYPE is 'local' or 'development'.
 *              DO NOT ship to production.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_filter( 'wp_is_application_passwords_available', function( $available ) {
    $env = function_exists( 'wp_get_environment_type' ) ? wp_get_environment_type() : 'production';
    return in_array( $env, [ 'local', 'development' ], true ) ? true : $available;
} );

// Visual marker in admin bar so we never confuse dev with prod
add_action( 'admin_bar_menu', function( $bar ) {
    $env = function_exists( 'wp_get_environment_type' ) ? wp_get_environment_type() : 'production';
    if ( in_array( $env, [ 'local', 'development' ], true ) ) {
        $bar->add_node( [
            'id'    => 'nb-dev-marker',
            'title' => '⚠ DEV — ' . strtoupper( $env ),
            'meta'  => [ 'class' => 'nb-dev-marker' ],
        ] );
    }
}, 999 );

add_action( 'admin_head', function() {
    echo '<style>#wpadminbar #wp-admin-bar-nb-dev-marker > .ab-item { background:#d23a2e !important; color:#fff !important; font-weight:700; }</style>';
} );
