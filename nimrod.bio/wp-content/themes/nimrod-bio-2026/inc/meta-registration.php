<?php
defined( 'ABSPATH' ) || exit;

add_action(
	'init',
	function () {
		$can_edit = function () {
			return current_user_can( 'edit_posts' );
		};

		$string_meta = array(
			'auth_callback' => $can_edit,
			'show_in_rest'  => true,
			'single'        => true,
			'type'          => 'string',
		);
		$bool_meta   = array(
			'auth_callback' => $can_edit,
			'show_in_rest'  => true,
			'single'        => true,
			'type'          => 'boolean',
		);
		$array_meta  = array(
			'auth_callback' => $can_edit,
			'show_in_rest'  => array(
				'schema' => array(
					'type'  => 'array',
					'items' => array(
						'type' => 'string',
					),
				),
			),
			'single'        => true,
			'type'          => 'array',
		);

		foreach ( array( 'tagline', 'lede', 'service_type', 'stage', 'cta_label', 'cta_whatsapp_href', 'is_anchor_for_world' ) as $key ) {
			register_post_meta( 'service', '_nb_' . $key, $string_meta );
		}
		register_post_meta( 'service', '_nb_is_free', $bool_meta );
		foreach ( array( 'linked_projects', 'related_posts' ) as $key ) {
			register_post_meta( 'service', '_nb_' . $key, $array_meta );
		}
		register_post_meta( 'service', '_nb_sections', $string_meta );
		register_post_meta( 'service', '_nb_meta_strip', $string_meta );

		foreach ( array( 'scope', 'stage', 'year', 'location', 'duration', 'summary', 'seeking_note', 'legacy_of' ) as $key ) {
			register_post_meta( 'project', '_nb_' . $key, $string_meta );
		}
		register_post_meta( 'project', '_nb_name_tbc', $bool_meta );
		foreach ( array( 'linked_services', 'gallery', 'more_projects_ids' ) as $key ) {
			register_post_meta( 'project', '_nb_' . $key, $array_meta );
		}
		register_post_meta( 'project', '_nb_outcomes', $string_meta );
	}
);
