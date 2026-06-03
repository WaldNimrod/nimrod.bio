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

		foreach ( array( 'tagline', 'lede', 'service_type', 'stage', 'cta_label', 'cta_whatsapp_href', 'is_anchor_for_world', 'cta_hint', 'cta_final_h', 'cta_final_p', 'hero_facts', 'external_url', 'external_label' ) as $key ) {
			register_post_meta( 'service', '_nb_' . $key, $string_meta );
		}
		register_post_meta( 'service', '_nb_is_free', $bool_meta );
		foreach ( array( 'linked_projects', 'related_posts', 'gallery' ) as $key ) {
			register_post_meta( 'service', '_nb_' . $key, $array_meta );
		}
		register_post_meta( 'service', '_nb_sections', $string_meta );
		register_post_meta( 'service', '_nb_meta_strip', $string_meta );
		// WP007 Phase 2 — v5 t2s structured content (JSON strings parsed via nb_json_meta;
		// svc_pull is a plain string). team_00-authorized scope expansion 2026-06-03.
		foreach ( array( 'feat_tiles', 'svc_steps', 'svc_pull', 'bridge' ) as $key ) {
			register_post_meta( 'service', '_nb_' . $key, $string_meta );
		}

		foreach ( array( 'scope', 'stage', 'year', 'location', 'duration', 'summary', 'seeking_note', 'legacy_of', 'seeking_cta_h', 'seeking_cta_p', 'seeking_cta_label', 'seeking_cta_hint', 'outcomes_note', 'external_url', 'external_label' ) as $key ) {
			register_post_meta( 'project', '_nb_' . $key, $string_meta );
		}
		register_post_meta( 'project', '_nb_name_tbc', $bool_meta );
		foreach ( array( 'linked_services', 'gallery', 'more_projects_ids' ) as $key ) {
			register_post_meta( 'project', '_nb_' . $key, $array_meta );
		}
		register_post_meta( 'project', '_nb_outcomes', $string_meta );

		foreach ( array( 'service', 'project', 'post' ) as $post_type ) {
			register_post_meta( $post_type, '_nb_seed', $string_meta );
		}

		// Yoast SEO — register title + metadesc for REST so agents can PATCH them
		// Yoast v27+ does not auto-register these for custom CPTs.
		foreach ( array( 'post', 'service', 'project' ) as $pt ) {
			register_post_meta( $pt, '_yoast_wpseo_title', $string_meta );
			register_post_meta( $pt, '_yoast_wpseo_metadesc', $string_meta );
		}
	}
);
