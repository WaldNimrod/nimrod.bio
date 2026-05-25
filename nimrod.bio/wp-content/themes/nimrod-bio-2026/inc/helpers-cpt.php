<?php
defined( 'ABSPATH' ) || exit;

function nb_get_worlds(): array {
	return array(
		'soil' => 'אדמה',
		'know' => 'ייעוץ והוראה',
		'code' => 'דיגיטל',
	);
}

function nb_get_stages(): array {
	return array(
		'seed'             => 'seed · רעיון',
		'seeking-partners' => 'seeking-partners · מחפש שותפים',
		'pilot'            => 'pilot · פיילוט',
		'live'             => 'live · פעיל',
		'legacy'           => 'legacy · מורשת',
	);
}

function nb_get_service_types(): array {
	return array(
		'service'    => 'service · פעילות',
		'system'     => 'system · מערכת',
		'background' => 'background · רקע',
	);
}

function nb_get_scopes(): array {
	return array(
		'client-case' => 'client-case · עבור לקוח',
		'own-venture' => 'own-venture · מיזם עצמי',
	);
}

function nb_get_flow_styles(): array {
	return array(
		'lead'    => 'lead · גדול אופקי',
		'wide'    => 'wide · רחב עם תמונה',
		'tall'    => 'tall · אנכי',
		'typo'    => 'typo · טיפוגרפיה',
		'quote'   => 'quote · ציטוט',
		'feature' => 'feature · רגיל מודגש',
		'brief'   => 'brief · קצר',
	);
}

function nb_meta( int $post_id, string $key, $default = '' ) {
	$value = get_post_meta( $post_id, '_nb_' . $key, true );
	return '' === $value ? $default : $value;
}

function nb_meta_array( int $post_id, string $key ): array {
	$value = get_post_meta( $post_id, '_nb_' . $key, true );
	if ( is_array( $value ) ) {
		return $value;
	}
	if ( is_string( $value ) && '' !== $value ) {
		return array_filter( array_map( 'trim', explode( ',', $value ) ) );
	}
	return array();
}

function nb_sanitize_url_field( $url ): string {
	$url = trim( (string) $url );
	if ( '' === $url ) {
		return '';
	}
	if ( 0 === strpos( $url, 'wa.me/' ) || 0 === strpos( $url, 'mailto:' ) || 0 === strpos( $url, '/' ) ) {
		return $url;
	}
	return esc_url_raw( $url );
}
