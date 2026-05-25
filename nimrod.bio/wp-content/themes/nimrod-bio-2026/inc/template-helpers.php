<?php
defined( 'ABSPATH' ) || exit;

/**
 * Map world slug to UI label (note: 'know' shows as "ייעוץ והוראה" not "ידע").
 * Locked by team_35 - do NOT change without GCR.
 */
function nb_world_label( string $slug ): string {
	$labels = array(
		'soil' => 'אדמה',
		'know' => 'ייעוץ והוראה',
		'code' => 'דיגיטל',
	);
	return $labels[ $slug ] ?? $slug;
}

/**
 * Detect active world for nav highlighting.
 * v0.1: parse URL path. v1.0 (WP002-2): use queried_object's world taxonomy term.
 * Returns one of 'soil'|'know'|'code'|null.
 */
function nb_active_world(): ?string {
	$path = trim( wp_parse_url( home_url( add_query_arg( null, null ) ), PHP_URL_PATH ) ?? '', '/' );
	if ( preg_match( '#^world/(soil|know|code)\b#', $path, $m ) ) {
		return $m[1];
	}
	return null;
}

/**
 * Render the home icon SVG inline (per design - no icon font).
 */
function nb_home_icon(): string {
	return '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'
		. '<path d="M3 11.2 12 4l9 7.2"/>'
		. '<path d="M5.5 9.5V19a1 1 0 0 0 1 1H10v-5.5h4V20h3.5a1 1 0 0 0 1-1V9.5"/>'
		. '</svg>';
}
