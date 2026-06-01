<?php
/**
 * nimrod.bio 2026 - theme bootstrap
 */
defined( 'ABSPATH' ) || exit;

// Constants
define( 'NB_THEME_VERSION', '0.7.14' );
define( 'NB_THEME_DIR', get_template_directory() );
define( 'NB_THEME_URI', get_template_directory_uri() );

// Theme support
add_action(
	'after_setup_theme',
	function () {
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'gallery', 'caption', 'style', 'script' ) );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'editor-styles' );

			// Responsive hero image sizes (P009-WP002 — mobile/tablet srcset).
			add_image_size( 'nb-hero-mobile', 800, 9999, false );
			add_image_size( 'nb-hero-tablet', 1280, 9999, false );

		// Register two nav locations even though WP002 only renders one (Shell uses hard-coded markup).
		register_nav_menus(
			array(
				'primary'   => __( 'Shell - Primary nav', 'nimrod-bio-2026' ),
				'secondary' => __( 'Shell - Secondary nav', 'nimrod-bio-2026' ),
			)
		);

		// Hebrew RTL.
		load_theme_textdomain( 'nimrod-bio-2026', NB_THEME_DIR . '/languages' );
	}
);

// Includes
require_once NB_THEME_DIR . '/inc/enqueue.php';
require_once NB_THEME_DIR . '/inc/template-helpers.php';
require_once NB_THEME_DIR . '/inc/nav-walker.php';
require_once NB_THEME_DIR . '/inc/helpers-cpt.php';
require_once NB_THEME_DIR . '/inc/taxonomies.php';
require_once NB_THEME_DIR . '/inc/cpt-service.php';
require_once NB_THEME_DIR . '/inc/cpt-project.php';
require_once NB_THEME_DIR . '/inc/meta-registration.php';
require_once NB_THEME_DIR . '/inc/meta-box-service.php';
require_once NB_THEME_DIR . '/inc/meta-box-project.php';
require_once NB_THEME_DIR . '/inc/world-pages-bootstrap.php';
require_once NB_THEME_DIR . '/inc/rewrites.php';
require_once NB_THEME_DIR . '/inc/contact-form-handler.php';

foreach ( glob( NB_THEME_DIR . '/inc/template-styles-*.php' ) as $f ) {
	require_once $f;
}

/**
 * Stage B — dev-only media captions.
 * The "TBD · …" placeholder captions are an authoring aid, never shipped to
 * visitors. They render only for logged-in users who can edit posts. Templates
 * + helpers gate on this; the body.nb-dev class is the CSS belt-and-suspenders.
 */
function nb_dev_captions_visible(): bool {
	return is_user_logged_in() && current_user_can( 'edit_posts' );
}

add_filter(
	'body_class',
	function ( $classes ) {
		if ( nb_dev_captions_visible() ) {
			$classes[] = 'nb-dev';
		}
		return $classes;
	}
);

// Disable comments globally for V200 (design has no comment UI).
add_filter( 'comments_open', '__return_false', 20, 2 );
add_filter( 'pings_open', '__return_false', 20, 2 );

// Strip emoji scripts (we use no emoji).
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

// Default Open Graph image for Yoast SEO (P009-WP004 brand asset wiring).
// When Yoast is active it owns OG output; this filter supplies the brand
// og-image.png as the default whenever a post/page has no explicit OG image.
// (header.php emits a non-Yoast fallback only when WPSEO_VERSION is undefined.)
add_filter(
	'wpseo_opengraph_image',
	function ( $image ) {
		return $image ? $image : NB_THEME_URI . '/assets/img/og-image.png';
	}
);
