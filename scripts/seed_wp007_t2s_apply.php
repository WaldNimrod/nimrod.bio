<?php
/**
 * WP007 Phase 2 — apply v5 t2s anchor content to the local WP via the WP meta API.
 * Usage (in container): php /tmp/apply.php /tmp/data.json
 * Idempotent: re-running overwrites the same keys. Sets _nb_seed=wp007-t2s.
 */
$wp_load = getenv( 'NB_WP_LOAD' ) ?: '/var/www/html/wp-load.php';
require $wp_load;

$path = $argv[1] ?? '';
if ( ! $path || ! file_exists( $path ) ) {
	fwrite( STDERR, "data json not found: $path\n" );
	exit( 1 );
}
$data = json_decode( file_get_contents( $path ), true );
if ( ! is_array( $data ) ) {
	fwrite( STDERR, "bad json\n" );
	exit( 1 );
}

foreach ( $data as $slug => $spec ) {
	if ( '_note' === $slug || ! is_array( $spec ) ) {
		continue;
	}
	$post = get_page_by_path( $slug, OBJECT, 'service' );
	if ( ! $post ) {
		fwrite( STDERR, "service not found: $slug\n" );
		continue;
	}
	$id = (int) $post->ID;
	foreach ( ( $spec['string'] ?? array() ) as $k => $v ) {
		update_post_meta( $id, '_nb_' . $k, $v );
	}
	foreach ( ( $spec['json'] ?? array() ) as $k => $v ) {
		update_post_meta( $id, '_nb_' . $k, wp_json_encode( $v, JSON_UNESCAPED_UNICODE ) );
	}
	foreach ( ( $spec['array'] ?? array() ) as $k => $v ) {
		update_post_meta( $id, '_nb_' . $k, $v );
	}
	update_post_meta( $id, '_nb_seed', 'wp007-t2s' );
	echo "seeded $slug (ID $id): " .
		count( $spec['string'] ?? array() ) . " str / " .
		count( $spec['json'] ?? array() ) . " json / " .
		count( $spec['array'] ?? array() ) . " arr\n";
}
echo "done\n";
