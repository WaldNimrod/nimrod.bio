<?php
defined( 'ABSPATH' ) || exit;

add_action(
	'nb_enqueue_template_styles',
	function () {
		if ( is_page( array( 'soil', 'know', 'code' ) ) ) {
			wp_enqueue_style(
				'nb-t1',
				NB_THEME_URI . '/assets/css/t1.css',
				array( 'nb-shell' ),
				NB_THEME_VERSION
			);
		}
	}
);
