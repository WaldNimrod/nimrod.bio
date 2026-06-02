<?php
/**
 * search.php — System → search results (team_35 Precision Mockup v4, System screen).
 * .search-field (icon + input + button) · .search-meta · .results-list > .result-row
 * (.r-kind chip + h4 + excerpt). No-results branch renders .empty-state (on-voice).
 */
defined( 'ABSPATH' ) || exit;
get_header();

$nb_q      = get_search_query();
$nb_total  = (int) ( $GLOBALS['wp_query']->found_posts ?? 0 );

/**
 * Map a post type to its Hebrew result-kind chip label.
 */
$nb_kind = function ( $post_id ) {
	$pt = get_post_type( $post_id );
	switch ( $pt ) {
		case 'project':
			return 'פרויקט';
		case 'service':
			return 'שירות';
		case 'page':
			return 'עמוד';
		case 'post':
			return 'פוסט';
		default:
			$obj = get_post_type_object( $pt );
			return $obj ? $obj->labels->singular_name : $pt;
	}
};
?>
<section class="t8-section">
	<div class="t8-wrap">
		<h1 class="search-h1"><?php echo $nb_q ? esc_html( sprintf( 'חיפוש · "%s"', $nb_q ) ) : 'חיפוש'; ?></h1><!-- a11y P009-WP006: page-has-heading-one -->
		<form class="search-field" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
			<input type="search" name="s" value="<?php echo esc_attr( $nb_q ); ?>" aria-label="חיפוש" placeholder="חיפוש באתר…">
			<button type="submit">חפש</button>
		</form>

		<?php if ( have_posts() ) : ?>
			<p class="search-meta">
				<?php
				/* translators: %1$d = result count, %2$s = query. */
				echo esc_html( sprintf( '%d תוצאות ל־"%s"', $nb_total, $nb_q ) );
				?>
			</p>
			<div class="results-list">
				<?php
				while ( have_posts() ) :
					the_post();
					$nb_id = get_the_ID();
					?>
					<a class="result-row" href="<?php the_permalink(); ?>">
						<span class="r-kind"><?php echo esc_html( $nb_kind( $nb_id ) ); ?></span>
						<div>
							<h2><?php the_title(); ?></h2><!-- a11y P009-WP006: result h4→h2 under page h1 -->
							<?php $nb_ex = get_the_excerpt(); ?>
							<?php if ( $nb_ex ) : ?>
								<p><?php echo esc_html( wp_trim_words( $nb_ex, 24, '…' ) ); ?></p>
							<?php endif; ?>
						</div>
					</a>
				<?php endwhile; ?>
			</div>

			<?php
			the_posts_pagination(
				array(
					'mid_size'  => 1,
					'prev_text' => 'הקודם',
					'next_text' => 'הבא',
				)
			);
			?>
		<?php else : ?>
			<div class="empty-state">
				<div class="es-emblem">
					<img src="<?php echo esc_url( NB_THEME_URI . '/assets/img/basket-paper.png' ); ?>" alt="" width="60" height="60" loading="lazy" decoding="async">
				</div>
				<h2>לא מצאתי כלום על <?php echo esc_html( $nb_q ); ?>.</h2><!-- a11y P009-WP006: h3→h2 under page h1 -->
				<p>אולי נסה ניסוח אחר, או התחל מאחד העולמות.</p>
				<div class="err-links">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>">לדף הבית</a>
					<a href="<?php echo esc_url( home_url( '/world/soil/' ) ); ?>">אדמה</a>
					<a href="<?php echo esc_url( home_url( '/world/know/' ) ); ?>">ייעוץ והוראה</a>
					<a href="<?php echo esc_url( home_url( '/world/code/' ) ); ?>">דיגיטל</a>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
