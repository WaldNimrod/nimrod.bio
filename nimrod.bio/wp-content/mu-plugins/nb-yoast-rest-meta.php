<?php
/**
 * Plugin Name: NB — Yoast SEO REST Meta
 * Description: Registers _yoast_wpseo_title and _yoast_wpseo_metadesc as readable/writable
 *              REST API meta fields for posts, services, and projects CPTs.
 *              Required because Yoast does not auto-register these for custom CPTs, and the
 *              WP REST API blocks meta fields unless explicitly registered with show_in_rest.
 * Version: 1.0.0
 * Author: team_10 (NB Builder)
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'init', function () {
    $post_types = [ 'post', 'service', 'project' ];
    $fields = [
        '_yoast_wpseo_title'    => 'string',
        '_yoast_wpseo_metadesc' => 'string',
    ];

    foreach ( $post_types as $pt ) {
        foreach ( $fields as $key => $type ) {
            register_post_meta( $pt, $key, [
                'type'          => $type,
                'single'        => true,
                'default'       => '',
                'show_in_rest'  => true,
                'auth_callback' => function () {
                    return current_user_can( 'edit_posts' );
                },
            ] );
        }
    }
}, 20 );
