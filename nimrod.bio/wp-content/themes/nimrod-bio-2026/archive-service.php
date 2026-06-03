<?php
/**
 * archive-service.php — Services index (/services/) — v5 T2 composition (WP007 AT-1).
 *
 * page-hero (eyebrow · h1 · lede · stats) → svc-grid 2-col service cards
 * (world-icon + points + CTA) → bridges-band (2 cross-arm bridges) → final-cta.
 * Existing (locked) eyebrow/h1/lede copy preserved; the new hero stats, bridge
 * cards and final-CTA use v5-approved register (AC-C). Cards driven by the
 * service CPT; svc-points derived from each service's sections when present.
 *
 * @package nimrod-bio-2026
 */
defined( 'ABSPATH' ) || exit;

get_header();

$services_query = new WP_Query(
	array(
		'post_type'      => 'service',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'orderby'        => 'menu_order title',
		'order'          => 'ASC',
	)
);
?>
<section class="t2-wrap" aria-label="ארכיון שירותים">

	<header class="page-hero">
		<div class="eyebrow"><span class="num">שירותים</span><span>· נקודות כניסה</span></div>
		<h1>שירותים</h1>
		<p class="lede">יעוץ, הוראה, ייצור וגידול — מגוון הפעילויות של נמרוד ולד.</p>
		<div class="ssh-meta">
			<div class="m"><span class="mv">4 זרועות</span><span class="mk">פעילות בפועל</span></div>
			<div class="m"><span class="mv">9 עונות</span><span class="mk">ניסיון בשטח</span></div>
			<div class="m"><span class="mv">ללא התחייבות</span><span class="mk">שיחה ראשונה · 30 דק׳</span></div>
		</div>
	</header>

	<?php if ( $services_query->have_posts() ) : ?>
		<h2 class="nb-sr-only">כל השירותים</h2><!-- a11y: sr-only section heading so h1→h2→h3(cards) stays sequential -->
		<div class="svc-grid">
			<?php
			while ( $services_query->have_posts() ) :
				$services_query->the_post();
				$sid     = get_the_ID();
				$worlds  = wp_get_post_terms( $sid, 'world', array( 'fields' => 'slugs' ) );
				$worlds  = is_wp_error( $worlds ) ? array() : $worlds;
				$primary = $worlds[0] ?? 'soil';
				$tagline = nb_meta( $sid, 'tagline' );
				$secs    = nb_json_meta( $sid, 'sections' );
				?>
				<a class="product-card" href="<?php echo esc_url( get_permalink() ); ?>">
					<div class="pimg">
						<?php
						if ( has_post_thumbnail( $sid ) ) {
							echo get_the_post_thumbnail( $sid, 'medium_large', array( 'alt' => esc_attr( get_the_title() ) ) );
						} else {
							echo nb_img_placeholder( 'TBD · ' . get_the_title(), get_the_title(), '16/10' );
						}
						?>
					</div>
					<div class="pbody">
						<span class="svc-ic <?php echo esc_attr( $primary ); ?>" aria-hidden="true"></span>
						<h3><?php the_title(); ?></h3>
						<?php if ( $tagline ) : ?>
							<p class="pmeta"><?php echo esc_html( $tagline ); ?></p>
						<?php endif; ?>
						<?php
						$points = array();
						foreach ( array( 'who', 'how', 'what' ) as $sk ) {
							if ( ! empty( $secs[ $sk ]['title'] ) ) {
								$points[] = $secs[ $sk ]['title'];
							}
						}
						if ( $points ) :
							?>
							<ul class="svc-points">
								<?php foreach ( array_slice( $points, 0, 3 ) as $pt ) : ?>
									<li><?php echo esc_html( $pt ); ?></li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
						<div class="prow">
							<span class="frm"><?php echo esc_html( nb_world_label( $primary ) ); ?></span>
							<span class="add">פרטים ←</span>
						</div>
					</div>
				</a>
				<?php
			endwhile;
			wp_reset_postdata();
			?>
		</div>

		<section class="t2-section band bridges-band">
			<?php echo nb_sec_head( 0, 'למה אצלי · הקישוריות', 'כל שירות יושב על זרוע פעילה אחרת.', 'מה שאני מגדל הוא הבסיס למה שאני מייעץ ומלמד — וגם למה שאני מקודד.' ); ?>
			<div class="svc-bridges-grid">
				<a class="svc-bridge b-soil-know" href="<?php echo esc_url( home_url( '/world/know/' ) ); ?>">
					<span class="b-conn"><?php echo nb_world_chip( 'soil' ); ?><?php echo nb_world_chip( 'know' ); ?></span>
					<h3>הניסיון בשטח הוא תנאי לייעוץ</h3>
					<p>מה שעובר דרך הידיים שלי הוא הבסיס לכל המלצה — לא מצגת, עונה שלמה.</p>
					<span class="more">מקור הידע</span>
				</a>
				<a class="svc-bridge b-know-code" href="<?php echo esc_url( home_url( '/world/code/' ) ); ?>">
					<span class="b-conn"><?php echo nb_world_chip( 'know' ); ?><?php echo nb_world_chip( 'code' ); ?></span>
					<h3>הידע הופך למערכת</h3>
					<p>מה שצברתי בייעוץ נכנס ל־SFA ולכלים — ולא נשאר רק אצלי.</p>
					<span class="more">SFA · ייעוץ דיגיטלי</span>
				</a>
			</div>
		</section>

	<?php else : ?>
		<div class="empty-state">
			<h2>אין שירותים זמינים כרגע.</h2>
			<p>פרטים יופיעו כאן בקרוב.</p>
		</div>
	<?php endif; ?>

</section>

<section class="final-cta">
	<div class="final-cta-inner">
		<div>
			<h2>לא בטוח מאיפה <em>להתחיל</em>?</h2>
			<p>שיחה קצרה תעשה סדר. נבין יחד מה אתה צריך ומאיזו זרוע נכון לפתוח.</p>
		</div>
		<div class="cta-side">
			<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="hero-cta primary">בואו נדבר</a>
		</div>
	</div>
</section>

<?php get_footer(); ?>
