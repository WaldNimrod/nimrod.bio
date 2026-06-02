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

/**
 * Clean brand URLs → canonical world pages.
 *
 * The world pages render at /world/<slug>/ (children of the `private` "world"
 * parent), and every in-theme link already points there. But the bare brand
 * URLs /soil/ /know/ /code/ — which users type or link externally — have no
 * top-level page and would 404. 301-redirect them to the canonical world page.
 * Additive: no link changes, /world/<slug>/ stays canonical. (P009 follow-on.)
 */
add_action( 'template_redirect', 'nb_redirect_bare_world_slugs' );
function nb_redirect_bare_world_slugs() {
	if ( is_admin() || wp_doing_ajax() || wp_doing_cron() ) {
		return;
	}
	$path = trim( (string) wp_parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ), '/' );
	if ( '' === $path ) {
		return;
	}
	if ( array_key_exists( $path, nb_get_worlds() ) ) {
		wp_safe_redirect( home_url( "/world/$path/" ), 301 );
		exit;
	}
}
