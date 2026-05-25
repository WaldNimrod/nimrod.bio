<?php
defined( 'ABSPATH' ) || exit;

add_action( 'after_switch_theme', 'nb_bootstrap_world_pages' );
add_action( 'after_switch_theme', 'nb_bootstrap_static_pages' );

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

function nb_bootstrap_static_pages() {
	$about_id = nb_ensure_page( 'about', 'על נמרוד', 0, 'publish', 'about' );
	nb_ensure_page( 'heritage', 'הגינה של נמרוד · הסיפור', $about_id, 'publish', 'about/heritage' );
	nb_ensure_page( 'contact', 'צור קשר', 0, 'publish', 'contact' );
	flush_rewrite_rules( false );
}

function nb_ensure_page( string $slug, string $title, int $parent_id, string $status, string $path = '' ): int {
	if ( '' === $path ) {
		$path = $parent_id ? "world/$slug" : $slug;
	}

	$existing = get_page_by_path( $path, OBJECT, 'page' );
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
			'post_content' => '<!-- auto-created by nb_ensure_page -->',
		)
	);
}
