<?php
defined( 'ABSPATH' ) || exit;

add_action(
	'init',
	function () {
		$stored = get_option( 'nb_theme_rewrite_version' );
		if ( $stored !== NB_THEME_VERSION ) {
			flush_rewrite_rules( false );
			update_option( 'nb_theme_rewrite_version', NB_THEME_VERSION );
		}
	},
	99
);
