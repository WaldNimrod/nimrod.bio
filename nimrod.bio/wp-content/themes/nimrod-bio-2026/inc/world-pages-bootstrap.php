<?php
defined( 'ABSPATH' ) || exit;

add_action( 'after_switch_theme', 'nb_bootstrap_world_pages' );

function nb_bootstrap_world_pages() {
	$parent_id = nb_ensure_page( 'world', 'עולמות', 0, 'private' );
	if ( ! $parent_id ) {
		return;
	}

	foreach ( nb_get_worlds() as $slug => $label ) {
		nb_ensure_page( $slug, $label, $parent_id, 'publish' );
	}

	flush_rewrite_rules( false );
}

function nb_ensure_page( string $slug, string $title, int $parent_id, string $status ): int {
	$existing = get_page_by_path( $parent_id ? "world/$slug" : $slug, OBJECT, 'page' );
	if ( $existing ) {
		return (int) $existing->ID;
	}

	return (int) wp_insert_post(
		array(
			'post_title'   => $title,
			'post_name'    => $slug,
			'post_status'  => $status,
			'post_type'    => 'page',
			'post_parent'  => $parent_id,
			'post_content' => '<!-- world page placeholder - T1 template (WP003-P003-WP002) will render dynamic content -->',
		)
	);
}
