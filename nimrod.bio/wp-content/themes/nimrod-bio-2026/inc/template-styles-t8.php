<?php
defined( 'ABSPATH' ) || exit;

add_action(
	'nb_enqueue_template_styles',
	function () {
		if ( is_page( array( 'about', 'heritage', 'contact' ) ) ) {
			wp_enqueue_style( 'nb-t8', NB_THEME_URI . '/assets/css/t8.css', array( 'nb-shell' ), NB_THEME_VERSION );
		}
		if ( is_page( 'contact' ) ) {
			wp_enqueue_script( 'nb-t8-contact', NB_THEME_URI . '/assets/js/t8-contact.js', array(), NB_THEME_VERSION, true );
		}
	}
);
