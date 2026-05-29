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

/**
 * Render a per-world line icon inline (per Precision Mockup §1 — sits next to
 * each .nav-world label). Inherits color via currentColor so it flips with the
 * .shell-nav / .shell-nav.atop state. Mirrors assets/icons/world-{slug}.svg.
 */
function nb_world_icon( string $slug ): string {
	$paths = array(
		'soil' => '<path d="M12 21V11"/><path d="M12 11C12 8 9.5 6 6.5 6C6 9 8.5 11 12 11Z"/><path d="M12 13C12 10.5 14.5 9 17.5 9C18 11.5 15.5 13 12 13Z"/>',
		'know' => '<path d="M12 8.5C12 7 10.5 6 8 6C6 6 4.5 6.5 4 7V17C4.5 16.5 6 16 8 16C10.5 16 12 17 12 18.5"/><path d="M12 8.5C12 7 13.5 6 16 6C18 6 19.5 6.5 20 7V17C19.5 16.5 18 16 16 16C13.5 16 12 17 12 18.5"/><path d="M12 8.5V18.5"/>',
		'code' => '<circle cx="6" cy="6" r="2.2"/><circle cx="6" cy="18" r="2.2"/><circle cx="18" cy="12" r="2.2"/><path d="M8 7L16 11"/><path d="M8 17L16 13"/>',
	);
	if ( ! isset( $paths[ $slug ] ) ) {
		return '';
	}
	return '<svg class="ico" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'
		. $paths[ $slug ]
		. '</svg>';
}

/**
 * P003 shared helpers (LOD300 §3). WP002 adds nb_get_bridges_for_world.
 */

function nb_world_chip( string $slug, bool $ghost = false ): string {
	$label = esc_html( nb_world_label( $slug ) );
	$cls   = 'wc ' . esc_attr( $slug ) . ( $ghost ? ' wc-ghost' : '' );
	return '<span class="' . $cls . '">' . $label . '</span>';
}

function nb_stage_stamp( string $stage ): string {
	$labels = array(
		'seed'             => 'seed',
		'seeking-partners' => 'seeking partners',
		'pilot'            => 'pilot',
		'live'             => 'live',
		'legacy'           => 'legacy',
	);
	$label  = $labels[ $stage ] ?? $stage;
	return '<span class="stage-stamp stage-' . esc_attr( $stage ) . '">' . esc_html( $label ) . '</span>';
}

function nb_sec_head( int $num, string $eyebrow, string $title, string $lede = '' ): string {
	$out  = '<header class="sec-head">';
	$out .= '<div class="s-eyebrow"><span class="num">§ ' . esc_html( str_pad( (string) $num, 2, '0', STR_PAD_LEFT ) ) . '</span>' . esc_html( $eyebrow ) . '</div>';
	$out .= '<h2 class="s-title">' . esc_html( $title ) . '</h2>';
	if ( $lede ) {
		$out .= '<p class="s-lede">' . esc_html( $lede ) . '</p>';
	}
	$out .= '</header>';
	return $out;
}

function nb_query_by_world( string $post_type, string $world_slug, int $limit = -1 ): WP_Query {
	return new WP_Query(
		array(
			'post_type'      => $post_type,
			'posts_per_page' => $limit,
			'post_status'    => 'publish',
			'tax_query'      => array(
				array(
					'taxonomy' => 'world',
					'field'    => 'slug',
					'terms'    => array( $world_slug ),
					'operator' => 'IN',
				),
			),
			'no_found_rows'  => true,
		)
	);
}

function nb_get_anchor_service_for_world( string $world_slug ): ?WP_Post {
	$q = new WP_Query(
		array(
			'post_type'      => 'service',
			'posts_per_page' => 1,
			'post_status'    => 'publish',
			'meta_query'     => array(
				array(
					'key'   => '_nb_is_anchor_for_world',
					'value' => $world_slug,
				),
			),
		)
	);
	return $q->have_posts() ? $q->posts[0] : null;
}

function nb_breadcrumb( array $crumbs ): string {
	$out = '<nav class="breadcrumb" aria-label="פירורי לחם"><ol>';
	foreach ( $crumbs as $c ) {
		if ( isset( $c['href'] ) ) {
			$out .= '<li><a href="' . esc_url( $c['href'] ) . '">' . esc_html( $c['label'] ) . '</a></li>';
		} else {
			$out .= '<li aria-current="page">' . esc_html( $c['label'] ) . '</li>';
		}
	}
	$out .= '</ol></nav>';
	return $out;
}

/**
 * Bridge pairs per world (2-world bridges only; signal seam locked in template).
 * WP002: added for T1 world pages.
 */
function nb_get_bridges_for_world( string $world ): array {
	$all = array(
		array(
			'a'     => 'soil',
			'b'     => 'know',
			'slug'  => 'consulting-hydro',
			'title' => 'ייעוץ · תכנון חממה',
		),
		array(
			'a'     => 'know',
			'b'     => 'code',
			'slug'  => 'tiktrack',
			'title' => 'tiktrack',
		),
	);
	return array_values(
		array_filter(
			$all,
			function ( $b ) use ( $world ) {
				return $b['a'] === $world || $b['b'] === $world;
			}
		)
	);
}

function nb_get_service_by_slug( string $slug ): ?WP_Post {
	$q = new WP_Query(
		array(
			'post_type'      => 'service',
			'name'           => $slug,
			'posts_per_page' => 1,
			'post_status'    => 'publish',
			'no_found_rows'  => true,
		)
	);
	return $q->have_posts() ? $q->posts[0] : null;
}

function nb_get_t1_hero_copy( string $world ): array {
	$copies = array(
		'soil' => array(
			'tagline'     => 'איפה שהאדמה פוגשת ידיים.',
			'intro_short' => 'החממה ההידרופונית פעילה. ה־BCS יוצא לעונה. ירקות נוסעים למסעדות בכל בוקר שני וחמישי.',
			'intro_long'  => 'זה הענף שמרוויח קודם — והענף שמודד את הזמן בעונות, לא ברבעונים. כל מה שאני יודע ללמד ולקוֹדֵד התחיל פה, באדמה.',
		),
		'know' => array(
			'tagline'     => 'איפה שהידע פוגש שטח וקוד.',
			'intro_short' => 'ייעוץ חממה, אגרו ו־market garden — מה שעבר בוץ, לא רק מצגות.',
			'intro_long'  => 'הענף שממיר ניסוי בשטח להחלטות. מה שנלמד באדמה ונבנה בדיגיטל עובר דרך כאן.',
		),
		'code' => array(
			'tagline'     => 'איפה שהקוד פוגש חווה קטנה.',
			'intro_short' => 'SFA, TikTrack, כלים קהילתיים — נבנה מהשטח, חוזר לשטח.',
			'intro_long'  => 'הענף שמקודד ידע לסוכנים, מערכות וממשקים. לא מיזם לשם מיזם — כלי שעובד.',
		),
	);
	$base = $copies[ $world ] ?? $copies['soil'];
	$base['marker'] = sprintf( 'עולם · %s · %s', nb_world_label( $world ), $world );
	return $base;
}

function nb_render_cdip_diagram(): string {
	return '<svg viewBox="0 0 320 220" aria-hidden="true">'
		. '<circle cx="100" cy="80" r="58" fill="none" stroke="var(--w-soil)" stroke-width="1.5" opacity=".8"/>'
		. '<circle cx="220" cy="80" r="58" fill="none" stroke="var(--w-know)" stroke-width="1.5" opacity=".8"/>'
		. '<circle cx="160" cy="160" r="58" fill="none" stroke="var(--w-code)" stroke-width="1.5" opacity=".8"/>'
		. '<text x="62" y="44" font-family="Frank Ruhl Libre" font-size="14" font-weight="700" fill="var(--w-soil-deep)">אדמה</text>'
		. '<text x="246" y="44" font-family="Frank Ruhl Libre" font-size="14" font-weight="700" fill="var(--w-know-deep)">ידע</text>'
		. '<text x="138" y="208" font-family="Frank Ruhl Libre" font-size="14" font-weight="700" fill="var(--w-code-deep)">דיגיטל</text>'
		. '<circle cx="160" cy="80" r="4" fill="var(--ink)"/>'
		. '<circle cx="130" cy="130" r="4" fill="var(--ink)"/>'
		. '<circle cx="190" cy="130" r="4" fill="var(--ink)"/>'
		. '<circle cx="160" cy="110" r="6" fill="var(--spark)"/>'
		. '<text x="148" y="76" font-family="JetBrains Mono" font-size="9" fill="var(--ink-soft)">×</text>'
		. '<text x="118" y="125" font-family="JetBrains Mono" font-size="9" fill="var(--ink-soft)">×</text>'
		. '<text x="180" y="125" font-family="JetBrains Mono" font-size="9" fill="var(--ink-soft)">×</text>'
		. '<text x="156" y="108" font-family="JetBrains Mono" font-size="9" fill="var(--spark)" font-weight="700">3×</text>'
		. '</svg>';
}

function nb_img_placeholder( string $cap, string $subject = '', string $ratio = '16/10', string $class = '' ): string {
	$cap_attr = esc_attr( $cap );
	$cls      = trim( 'img-ph fail ' . $class );
	$subj     = $subject ? '<span class="img-subj">' . esc_html( $subject ) . '</span>' : '';
	return '<div class="' . esc_attr( $cls ) . '" data-cap="' . $cap_attr . '" style="aspect-ratio:' . esc_attr( $ratio ) . '">' . $subj . '</div>';
}

/**
 * Image placeholder block used on T8 gallery/heritage hero.
 */
function nb_get_project_by_slug( string $slug ): ?WP_Post {
	$q = new WP_Query(
		array(
			'post_type'      => 'project',
			'name'           => $slug,
			'posts_per_page' => 1,
			'post_status'    => 'publish',
			'no_found_rows'  => true,
		)
	);
	return $q->have_posts() ? $q->posts[0] : null;
}

function nb_json_meta( int $post_id, string $key, $default = array() ) {
	$raw = get_post_meta( $post_id, '_nb_' . $key, true );
	if ( is_array( $raw ) ) {
		return $raw;
	}
	if ( is_string( $raw ) && '' !== $raw ) {
		$decoded = json_decode( $raw, true );
		if ( JSON_ERROR_NONE === json_last_error() && is_array( $decoded ) ) {
			return $decoded;
		}
	}
	return $default;
}

function nb_bridge_style_attr( array $worlds ): string {
	if ( 2 !== count( $worlds ) ) {
		return '';
	}
	$map = array(
		'soil' => 'var(--w-soil)',
		'know' => 'var(--w-know)',
		'code' => 'var(--w-code)',
	);
	$a   = $map[ $worlds[0] ] ?? 'var(--w-soil)';
	$b   = $map[ $worlds[1] ] ?? 'var(--w-know)';
	return ' style="--bridge-a:' . esc_attr( $a ) . ';--bridge-b:' . esc_attr( $b ) . ';"';
}

function nb_service_breadcrumb_crumbs( int $post_id ): array {
	$worlds = wp_get_post_terms( $post_id, 'world', array( 'fields' => 'slugs' ) );
	$world  = $worlds[0] ?? 'soil';
	return array(
		array(
			'label' => 'בית',
			'href'  => home_url( '/' ),
		),
		array(
			'label' => nb_world_label( $world ),
			'href'  => home_url( '/world/' . $world . '/' ),
		),
		array(
			'label' => get_the_title( $post_id ),
		),
	);
}

function nb_project_breadcrumb_crumbs( int $post_id ): array {
	$worlds = wp_get_post_terms( $post_id, 'world', array( 'fields' => 'slugs' ) );
	$world  = $worlds[0] ?? 'soil';
	return array(
		array(
			'label' => 'בית',
			'href'  => home_url( '/' ),
		),
		array(
			'label' => nb_world_label( $world ),
			'href'  => home_url( '/world/' . $world . '/' ),
		),
		array(
			'label' => get_the_title( $post_id ),
		),
	);
}

function nb_render_tbc( string $label = 'שם אמיתי' ): string {
	return '<span class="tbc">' . esc_html( $label ) . '</span>';
}

function nb_whatsapp_icon_svg(): string {
	return '<svg width="16" height="16" viewBox="0 0 24 24" aria-hidden="true" fill="currentColor">'
		. '<path d="M17.5 14.4c-.3-.1-1.7-.8-1.9-.9-.3-.1-.5-.1-.7.1-.2.3-.8.9-1 1.1-.2.2-.4.2-.6.1-.3-.1-1.2-.5-2.2-1.4-.8-.7-1.4-1.6-1.5-1.9-.2-.3 0-.4.1-.6.1-.1.3-.4.4-.5.1-.2.2-.3.3-.5.1-.2 0-.4 0-.5-.1-.1-.7-1.6-.9-2.2-.2-.6-.5-.5-.7-.5h-.6c-.2 0-.5.1-.8.4-.3.3-1 1-1 2.5s1.1 2.9 1.2 3.1c.1.2 2.1 3.2 5 4.5.7.3 1.3.5 1.7.6.7.2 1.3.2 1.8.1.6-.1 1.7-.7 2-1.4.2-.7.2-1.3.2-1.4-.1-.2-.3-.3-.6-.4zM12 2.1C6.5 2.1 2.1 6.6 2.1 12c0 1.7.4 3.3 1.3 4.7L2.1 22l5.4-1.3c1.4.8 3 1.2 4.5 1.2 5.5 0 9.9-4.5 9.9-9.9S17.5 2.1 12 2.1zm0 18.1c-1.4 0-2.7-.4-3.9-1.1l-.3-.2-2.9.8.8-2.8-.2-.3c-.8-1.2-1.2-2.6-1.2-4.1 0-4.3 3.5-7.7 7.7-7.7s7.7 3.5 7.7 7.7-3.4 7.7-7.7 7.7z"/>'
		. '</svg>';
}

function nb_img_ph( string $subject, string $cap = '', string $class = '', string $ratio = '4/5' ): string {
	$attrs = array(
		'class'     => trim( 'img-ph fail ' . $class ),
		'data-cap'  => esc_attr( $cap ),
		'style'     => 'aspect-ratio:' . esc_attr( $ratio ) . ';',
	);
	$html  = '<div';
	foreach ( $attrs as $key => $value ) {
		if ( '' !== $value ) {
			$html .= ' ' . $key . '="' . $value . '"';
		}
	}
	$html .= '>';
	$html .= '<img src="' . esc_url( NB_THEME_URI . '/assets/icons/home.svg' ) . '" alt="' . esc_attr( $subject ) . '" width="48" height="48" loading="lazy" />';
	if ( $subject ) {
		$html .= '<span class="img-subj">' . esc_html( $subject ) . '</span>';
	}
	$html .= '</div>';
	return $html;
}

/**
 * P009-WP003 (G-05) — Featured-image media block with the container-aspect
 * pattern, styled by the shared components.css `.img-ph` rules.
 *
 * Renders ONE `.img-ph` container that carries `aspect-ratio` + `overflow:hidden`
 * (never the <img>). When the post has a thumbnail it is emitted as a covering
 * <img>; when it does NOT, a clean tinted `.img-ph.clean` placeholder is rendered
 * (world-tinted if a world slug is passed) — never a collapsed/empty box.
 *
 * Distinct from the legacy `nb_img_ph()` placeholder above (which always renders
 * the fail box). Use THIS for real content cards (world cards, project cards,
 * blog feature/grid) where a featured image may or may not exist.
 *
 * @param int   $post_id  Post whose featured image to use.
 * @param array $args {
 *     @type string $ratio    CSS aspect-ratio for the container. Default '4/5'.
 *     @type string $world    World slug ('soil'|'know'|'code') for the clean
 *                            fallback tint + a tint class on the container. Default ''.
 *     @type string $size     WP image size for the thumbnail. Default 'large'.
 *     @type string $class    Extra classes appended to the container. Default ''.
 *     @type string $subject  Label shown inside the clean fallback (what the image
 *                            would show). Default '' (uses the post title).
 *     @type string $cap      Optional mono corner caption. Default ''.
 * }
 * @return string Container markup.
 */
function nb_featured_media( int $post_id, array $args = array() ): string {
	$defaults = array(
		'ratio'   => '4/5',
		'world'   => '',
		'size'    => 'large',
		'class'   => '',
		'subject' => '',
		'cap'     => '',
	);
	$args  = array_merge( $defaults, $args );
	$world = in_array( $args['world'], array( 'soil', 'know', 'code' ), true ) ? $args['world'] : '';

	$has_thumb = has_post_thumbnail( $post_id );

	$classes = array( 'img-ph' );
	if ( ! $has_thumb ) {
		$classes[] = 'clean';
		if ( $world ) {
			$classes[] = $world;
		}
	}
	if ( '' !== $args['class'] ) {
		$classes[] = $args['class'];
	}

	$style = 'aspect-ratio:' . esc_attr( str_replace( '/', ' / ', $args['ratio'] ) ) . ';';
	$html  = '<div class="' . esc_attr( implode( ' ', $classes ) ) . '" style="' . $style . '">';

	if ( $has_thumb ) {
		$html .= get_the_post_thumbnail(
			$post_id,
			$args['size'],
			array(
				'loading' => 'lazy',
				'alt'     => esc_attr( get_the_title( $post_id ) ),
			)
		);
	} else {
		$subject = '' !== $args['subject'] ? $args['subject'] : get_the_title( $post_id );
		if ( '' !== $subject ) {
			$html .= '<span class="img-subj">' . esc_html( $subject ) . '</span>';
		}
	}

	if ( '' !== $args['cap'] ) {
		$html .= '<span class="cap">' . esc_html( $args['cap'] ) . '</span>';
	}

	$html .= '</div>';
	return $html;
}
