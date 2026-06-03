<?php
defined( 'ABSPATH' ) || exit;

add_action(
	'nb_enqueue_template_styles',
	function () {
		// WP007 AT-1: the services index (archive-service.php) is rebuilt to the v5
		// T2 composition (page-hero → svc-grid → bridges-band → final-cta) and needs
		// t2.css too (alongside t1.css, kept for shared chrome).
		if ( is_post_type_archive( 'service' ) ) {
			wp_enqueue_style(
				'nb-t2',
				NB_THEME_URI . '/assets/css/t2.css',
				array( 'nb-shell' ),
				NB_THEME_VERSION
			);
		}
		if ( is_singular( 'service' ) ) {
			wp_enqueue_style(
				'nb-t2',
				NB_THEME_URI . '/assets/css/t2.css',
				array( 'nb-shell' ),
				NB_THEME_VERSION
			);
			// Service pages may include t3-gallery.php (gallery section) which
			// uses t3-wrap / t3-section / .gallery CSS defined in t3.css.
			wp_enqueue_style(
				'nb-t3',
				NB_THEME_URI . '/assets/css/t3.css',
				array( 'nb-t2' ),
				NB_THEME_VERSION
			);
		}
		if ( is_singular( 'project' ) ) {
			wp_enqueue_style(
				'nb-t3',
				NB_THEME_URI . '/assets/css/t3.css',
				array( 'nb-shell' ),
				NB_THEME_VERSION
			);
		}
	}
);
