<?php
defined( 'ABSPATH' ) || exit;

require_once NB_THEME_DIR . '/inc/template-helpers-t4-t5.php';

add_action(
	'init',
	function () {
		register_post_meta(
			'post',
			'_nb_seed',
			array(
				'auth_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
				'show_in_rest'  => true,
				'single'        => true,
				'type'          => 'string',
			)
		);
		foreach ( array( '_nb_read_time', '_nb_image_subject', '_nb_image_cap' ) as $key ) {
			register_post_meta(
				'post',
				$key,
				array(
					'auth_callback' => function () {
						return current_user_can( 'edit_posts' );
					},
					'show_in_rest'  => true,
					'single'        => true,
					'type'          => 'string',
				)
			);
		}
	}
);

add_action(
	'template_redirect',
	function () {
		if ( is_home() && isset( $_GET['world'] ) && '' !== trim( (string) $_GET['world'] ) ) {
			global $wp_query;
			$wp_query->is_404 = false;
			status_header( 200 );
		}
	},
	1
);

add_action(
	'pre_get_posts',
	function ( $query ) {
		if ( is_admin() || ! $query->is_main_query() || ! $query->is_home() ) {
			return;
		}
		$world_filter = isset( $_GET['world'] ) ? explode( ',', sanitize_text_field( wp_unslash( $_GET['world'] ) ) ) : array();
		if ( empty( $world_filter ) ) {
			return;
		}
		$world_filter = array_values( array_intersect( $world_filter, array_keys( nb_get_worlds() ) ) );
		if ( empty( $world_filter ) ) {
			return;
		}
		$query->set(
			'tax_query',
			array(
				array(
					'taxonomy' => 'world',
					'field'    => 'slug',
					'terms'    => $world_filter,
					'operator' => 'IN',
				),
			)
		);
	}
);

add_action(
	'nb_enqueue_template_styles',
	function () {
		if ( is_singular( 'post' ) ) {
			wp_enqueue_style( 'nb-t4', NB_THEME_URI . '/assets/css/t4.css', array( 'nb-shell' ), NB_THEME_VERSION );
		}
		if ( is_home() ) {
			wp_enqueue_style( 'nb-t5', NB_THEME_URI . '/assets/css/t5.css', array( 'nb-shell' ), NB_THEME_VERSION );
			wp_enqueue_script(
				'nb-t5-filter',
				NB_THEME_URI . '/assets/js/t5-filter.js',
				array(),
				NB_THEME_VERSION,
				true
			);
		}
	}
);
