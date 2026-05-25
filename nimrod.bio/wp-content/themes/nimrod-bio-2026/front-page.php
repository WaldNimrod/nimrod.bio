<?php
/**
 * front-page.php — T7 Home (statement / ribbon)
 */
defined( 'ABSPATH' ) || exit;
get_header();
?>

<section class="t7 t7-hero hero-statement">
	<div class="t7-wrap">
		<div class="hero-statement">
			<div class="grid">
				<div>
					<h1>
						פיזיקה, אקולוגיה, קוד וחקלאות —
						<em>אותה מערכת.</em>
						שלוש זרועות, <span class="spark">3×</span> חיבורים.
					</h1>
					<p class="lede">
						החממה ההידרופונית מזינה את הייעוץ. הייעוץ מקודד ל-SFA. SFA חוזרת לשטח של חוות אחרות. CDIP — Cross-Domain Isomorphism Perception — לא מטאפורה, עיקרון עבודה.
					</p>
					<div class="meta-row">
						<span><b>4</b> חממות · ייעוץ</span>
						<span><b>1</b> חקלאות · קומון</span>
						<span><b>1</b> SFA · קוד</span>
					</div>
				</div>
				<div class="diagram-wrap">
					<svg viewBox="0 0 320 280" aria-hidden="true">
						<circle cx="100" cy="100" r="60" fill="none" stroke="var(--w-soil-deep)" stroke-width="2"/>
						<circle cx="220" cy="100" r="60" fill="none" stroke="var(--w-know-deep)" stroke-width="2"/>
						<circle cx="160" cy="190" r="60" fill="none" stroke="var(--w-code-deep)" stroke-width="2"/>
						<text x="64" y="60" font-family="Frank Ruhl Libre" font-size="16" font-weight="700" fill="var(--w-soil-deep)">אדמה</text>
						<text x="216" y="60" font-family="Frank Ruhl Libre" font-size="16" font-weight="700" fill="var(--w-know-deep)">ייעוץ</text>
						<text x="138" y="250" font-family="Frank Ruhl Libre" font-size="16" font-weight="700" fill="var(--w-code-deep)">דיגיטל</text>
						<circle cx="160" cy="100" r="5" fill="var(--ink)"/>
						<circle cx="130" cy="150" r="5" fill="var(--ink)"/>
						<circle cx="190" cy="150" r="5" fill="var(--ink)"/>
						<circle cx="160" cy="135" r="8" fill="var(--spark)"/>
						<text x="155" y="139" font-family="JetBrains Mono" font-size="9" font-weight="700" fill="#fff">3×</text>
					</svg>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="t7-section t7-worlds" aria-labelledby="t7-worlds-title">
	<div class="t7-wrap">
		<?php echo nb_sec_head( 1, 'העולמות', 'שלוש זרועות · שורש אחד', 'כל זרוע פעילה בפועל. הקישוריות ביניהן היא הייחוד.' ); ?>
		<div class="worlds-grid">
			<?php foreach ( array( 'soil', 'know', 'code' ) as $w ) : ?>
				<a href="<?php echo esc_url( home_url( "/world/$w/" ) ); ?>" class="world-card world-<?php echo esc_attr( $w ); ?>">
					<span class="world-name"><?php echo esc_html( nb_world_label( $w ) ); ?></span>
					<?php
					$count = nb_query_by_world( 'service', $w, -1 )->found_posts;
					?>
					<span class="world-count"><?php echo (int) $count; ?> פעילויות</span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="t7-section t7-projects">
	<div class="t7-wrap">
		<?php echo nb_sec_head( 2, 'פרויקטים', 'בנו · מבנים · מחפשים', 'פרויקטים שמראים את הקישוריות בפעולה.' ); ?>
		<div class="projects-grid">
			<?php
			$featured = new WP_Query(
				array(
					'post_type'      => 'project',
					'posts_per_page' => 3,
					'post_status'    => 'publish',
					'meta_query'     => array(
						'relation' => 'AND',
						array(
							'key'     => '_nb_stage',
							'value'   => array( 'live', 'seeking-partners' ),
							'compare' => 'IN',
						),
						array(
							'key'   => '_nb_scope',
							'value' => 'own-venture',
						),
					),
					'orderby'        => 'date',
					'order'          => 'DESC',
				)
			);
			while ( $featured->have_posts() ) :
				$featured->the_post();
				$stage = nb_meta( get_the_ID(), 'stage' );
				?>
				<a class="proj-card" href="<?php the_permalink(); ?>">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'large' ); ?>
					<?php endif; ?>
					<?php echo nb_stage_stamp( $stage ); ?>
					<h3><?php the_title(); ?></h3>
					<p><?php echo esc_html( nb_meta( get_the_ID(), 'summary' ) ); ?></p>
				</a>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		</div>
	</div>
</section>

<aside class="unless-ribbon" role="complementary">
	<div class="t7-wrap">
		<p>העולם הוא כזה — <em>אלא אם כן</em>.</p>
	</div>
</aside>

<section class="t7-section t7-posts">
	<div class="t7-wrap">
		<?php echo nb_sec_head( 3, 'מהבלוג', 'מחשבות שיורדות לאדמה', '' ); ?>
		<div class="posts-grid posts-grid-4">
			<?php
			$recent = new WP_Query(
				array(
					'post_type'      => 'post',
					'posts_per_page' => 4,
					'post_status'    => 'publish',
					'meta_query'     => array(
						array(
							'key'   => '_nb_seed',
							'value' => 'v200-migrated',
						),
					),
					'orderby'        => 'date',
					'order'          => 'DESC',
				)
			);
			while ( $recent->have_posts() ) :
				$recent->the_post();
				?>
				<a class="post-card post-square" href="<?php the_permalink(); ?>">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'medium' ); ?>
					<?php endif; ?>
					<h4><?php the_title(); ?></h4>
				</a>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		</div>
	</div>
</section>

<section class="t7-section final-cta">
	<div class="t7-wrap">
		<h2>איך אפשר להתחיל?</h2>
		<div class="cta-paths">
			<a class="btn btn-primary" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">צור קשר</a>
			<a class="btn btn-spark" href="<?php echo esc_url( home_url( '/services/sfa/' ) ); ?>">הצטרף ל-SFA</a>
		</div>
	</div>
</section>

<?php
get_footer();
