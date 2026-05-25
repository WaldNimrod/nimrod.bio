<?php
defined( 'ABSPATH' ) || exit;

add_action(
	'init',
	function () {
		register_taxonomy(
			'world',
			array( 'service', 'project', 'post' ),
			array(
				'labels'            => array(
					'name'          => 'עולמות',
					'singular_name' => 'עולם',
					'menu_name'     => 'עולמות',
				),
				'public'            => true,
				'hierarchical'      => false,
				'show_ui'           => true,
				'show_admin_column' => true,
				'show_in_rest'      => true,
				'rest_base'         => 'world',
				'rewrite'           => false,
				'query_var'         => 'world',
			)
		);

		register_taxonomy(
			'flow_style',
			array( 'post' ),
			array(
				'labels'            => array(
					'name'          => 'סגנון זרימה (T5)',
					'singular_name' => 'סגנון',
				),
				'public'            => false,
				'hierarchical'      => false,
				'show_ui'           => true,
				'show_admin_column' => true,
				'show_in_rest'      => true,
				'rest_base'         => 'flow_style',
				'rewrite'           => false,
				'query_var'         => 'flow_style',
			)
		);

		foreach ( array_keys( nb_get_worlds() ) as $slug ) {
			if ( ! term_exists( $slug, 'world' ) ) {
				wp_insert_term(
					nb_get_worlds()[ $slug ],
					'world',
					array(
						'slug' => $slug,
					)
				);
			}
		}

		foreach ( array_keys( nb_get_flow_styles() ) as $slug ) {
			if ( ! term_exists( $slug, 'flow_style' ) ) {
				wp_insert_term(
					$slug,
					'flow_style',
					array(
						'slug' => $slug,
					)
				);
			}
		}
	}
);
