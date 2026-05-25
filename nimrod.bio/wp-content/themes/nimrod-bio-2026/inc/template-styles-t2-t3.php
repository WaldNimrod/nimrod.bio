<?php
defined( 'ABSPATH' ) || exit;

add_action(
	'nb_enqueue_template_styles',
	function () {
		if ( is_singular( 'service' ) ) {
			wp_enqueue_style(
				'nb-t2',
				NB_THEME_URI . '/assets/css/t2.css',
				array( 'nb-shell' ),
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
