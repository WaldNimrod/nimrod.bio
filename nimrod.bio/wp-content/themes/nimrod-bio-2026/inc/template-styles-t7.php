<?php
defined( 'ABSPATH' ) || exit;

add_action(
	'nb_enqueue_template_styles',
	function () {
		if ( is_front_page() ) {
			wp_enqueue_style( 'nb-t7', NB_THEME_URI . '/assets/css/t7.css', array( 'nb-shell' ), NB_THEME_VERSION );
		}
	}
);
