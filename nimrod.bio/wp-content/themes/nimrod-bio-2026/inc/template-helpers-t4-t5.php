<?php
defined( 'ABSPATH' ) || exit;

/**
 * WP004 — T4/T5 post helpers (loaded via template-styles-t4-t5.php).
 */

function nb_extract_toc( string $content ): array {
	preg_match_all( '#<h2[^>]*id="([^"]+)"[^>]*>(.+?)</h2>#', $content, $matches );
	$items = array();
	foreach ( $matches[1] as $i => $id ) {
		$items[] = array(
			'id'    => $id,
			'label' => wp_strip_all_tags( $matches[2][ $i ] ),
		);
	}
	return $items;
}

function nb_prepare_post_body_html( string $content ): string {
	$section = 0;
	return preg_replace_callback(
		'#<h2([^>]*)>(.*?)</h2>#s',
		function ( $m ) use ( &$section ) {
			++$section;
			$attrs = $m[1];
			$inner = $m[2];
			if ( ! preg_match( '#\bid="#', $attrs ) ) {
				$attrs .= ' id="section-' . $section . '"';
			}
			if ( ! preg_match( '#class="num"#', $inner ) && false === strpos( $inner, '<span class="num">' ) ) {
				$inner = '<span class="num">' . esc_html( str_pad( (string) $section, 2, '0', STR_PAD_LEFT ) ) . '</span>' . $inner;
			}
			return '<h2' . $attrs . '>' . $inner . '</h2>';
		},
		$content
	);
}

function nb_get_post_world_slugs( int $post_id ): array {
	$terms = get_the_terms( $post_id, 'world' );
	if ( ! $terms || is_wp_error( $terms ) ) {
		return array();
	}
	return wp_list_pluck( $terms, 'slug' );
}

function nb_get_post_flow_style( int $post_id ): string {
	$terms = get_the_terms( $post_id, 'flow_style' );
	if ( ! $terms || is_wp_error( $terms ) ) {
		return 'feature';
	}
	return $terms[0]->slug;
}

function nb_post_read_label( int $post_id ): string {
	$read = get_post_meta( $post_id, '_nb_read_time', true );
	return $read ? (string) $read : '8 דק׳';
}

function nb_post_featured_image( int $post_id, string $class = '', string $ratio = '16/10' ): string {
	if ( has_post_thumbnail( $post_id ) ) {
		return get_the_post_thumbnail(
			$post_id,
			'large',
			array(
				'class'   => trim( 'post-thumb ' . $class ),
				'loading' => 'lazy',
			)
		);
	}
	$subject = get_post_meta( $post_id, '_nb_image_subject', true ) ?: get_the_title( $post_id );
	$cap     = get_post_meta( $post_id, '_nb_image_cap', true ) ?: '';
	if ( function_exists( 'nb_img_ph' ) ) {
		return nb_img_ph( (string) $subject, (string) $cap, $class, $ratio );
	}
	return nb_img_placeholder( (string) $cap, (string) $subject, $ratio, $class );
}

function nb_get_related_posts( int $post_id, int $limit = 3 ): array {
	$worlds = nb_get_post_world_slugs( $post_id );
	if ( empty( $worlds ) ) {
		return array();
	}
	$q = new WP_Query(
		array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => $limit,
			'post__not_in'   => array( $post_id ),
			'tax_query'      => array(
				array(
					'taxonomy' => 'world',
					'field'    => 'slug',
					'terms'    => $worlds,
					'operator' => 'IN',
				),
			),
			'no_found_rows'  => true,
		)
	);
	return $q->posts;
}
