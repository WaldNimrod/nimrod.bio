<?php
/**
 * archive-service.php — Services archive landing (/services/)
 *
 * Lists all `service` CPT posts in the svc-card design-system style.
 * Built for F-002: home "כל השירותים" link resolves here.
 *
 * @package nimrod-bio-2026
 * @version 0.7.13
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
<!-- a11y P009-WP006: <main>→<section> (header.php already provides the page <main id="main">; this was a duplicate/nested main) -->
<section class="t1-wrap" style="padding-block: clamp(48px, 6vw, 80px);" aria-label="ארכיון שירותים">

	<header class="archive-head" style="margin-bottom: clamp(32px, 4vw, 52px);">
		<p class="s-eyebrow">
			<span class="num">01 ·</span> שירותים
		</p>
		<h1 class="s-title">שירותים</h1>
		<p class="s-lede">יעוץ, הוראה, ייצור וגידול — מגוון הפעילויות של נמרוד ולד.</p>
	</header>

	<?php if ( $services_query->have_posts() ) : ?>
		<div class="services-grid" style="display:grid; grid-template-columns:repeat(3,1fr); gap:22px;">
			<?php
			while ( $services_query->have_posts() ) :
				$services_query->the_post();
				get_template_part(
					'template-parts/t1-svc-card',
					null,
					array( 'post' => get_post() )
				);
			endwhile;
			wp_reset_postdata();
			?>
		</div>
	<?php else : ?>
		<div class="empty-state">
			<h2>אין שירותים זמינים כרגע.</h2><!-- a11y P009-WP006: h3→h2 under archive h1 -->
			<p>פרטים יופיעו כאן בקרוב.</p>
		</div>
	<?php endif; ?>

</section><!-- a11y P009-WP006: was </main> -->

<?php get_footer(); ?>
