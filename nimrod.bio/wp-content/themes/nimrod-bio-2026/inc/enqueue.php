<?php
defined( 'ABSPATH' ) || exit;

add_action(
	'wp_enqueue_scripts',
	function () {
		// Google Fonts - preconnect + family link.
		wp_enqueue_style(
			'nb-fonts',
			'https://fonts.googleapis.com/css2?family=Assistant:wght@300;400;500;600;700;800&family=Frank+Ruhl+Libre:wght@500;700;900&family=JetBrains+Mono:wght@400;500&display=swap&subset=hebrew,latin',
			array(),
			null
		);

		// system.css - design tokens (always).
		wp_enqueue_style(
			'nb-system',
			NB_THEME_URI . '/assets/css/system.css',
			array( 'nb-fonts' ),
			NB_THEME_VERSION
		);

		// components.css - shared design-language components (type tokens, .wc,
		// .stage-stamp, .nb-card, .img-ph, .nb-emblem). Depends on tokens; loads
		// BEFORE template styles (nb-t7 etc.) so templates can override. Shared,
		// so enqueue globally. (P009-WP003 B1.)
		wp_enqueue_style(
			'nb-components',
			NB_THEME_URI . '/assets/css/components.css',
			array( 'nb-system' ),
			NB_THEME_VERSION
		);

		// shell.css - global nav + footer (always).
		wp_enqueue_style(
			'nb-shell',
			NB_THEME_URI . '/assets/css/shell.css',
			array( 'nb-system', 'nb-components' ),
			NB_THEME_VERSION
		);

		// shell.js - sets is-active on current world nav link.
		wp_enqueue_script(
			'nb-shell-js',
			NB_THEME_URI . '/assets/js/shell.js',
			array(),
			NB_THEME_VERSION,
			true
		);

		// nav-drawer.js - accessible mobile nav drawer (P009-WP002).
		wp_enqueue_script(
			'nb-nav-drawer',
			NB_THEME_URI . '/assets/js/nav-drawer.js',
			array(),
			NB_THEME_VERSION,
			array(
				'strategy'  => 'defer',
				'in_footer' => true,
			)
		);

		// nb-nav-atop.js - hero-aware nav transparency (toggles .atop on
		// .shell-nav while the .hero-poster is in view). Global: no-ops on
		// pages without a hero poster. (P009-WP003 B1.)
		wp_enqueue_script(
			'nb-nav-atop',
			NB_THEME_URI . '/assets/js/nb-nav-atop.js',
			array(),
			NB_THEME_VERSION,
			array(
				'strategy'  => 'defer',
				'in_footer' => true,
			)
		);

		// nb-carousel.js - horizontal scroll-snap carousels (Services/Projects;
		// consumed by a later increment). Defer/footer; harmless when no
		// carousel is present. (P009-WP003 B1.)
		wp_enqueue_script(
			'nb-carousel',
			NB_THEME_URI . '/assets/js/nb-carousel.js',
			array(),
			NB_THEME_VERSION,
			array(
				'strategy'  => 'defer',
				'in_footer' => true,
			)
		);

		// Hook for template-specific styles (WP003+ adds via this action).
		do_action( 'nb_enqueue_template_styles' );
	}
);

// Preconnect for Google Fonts - printed inside <head> before stylesheet links.
add_action(
	'wp_head',
	function () {
		echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
		echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
	},
	1
);
