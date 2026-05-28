<?php
defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	$post_id = get_the_ID();
	$slug    = get_post()->post_name;
	$scope   = nb_meta( $post_id, 'scope', 'client-case' );
	$stage   = nb_meta( $post_id, 'stage', 'live' );
	$worlds  = wp_get_post_terms( $post_id, 'world', array( 'fields' => 'slugs' ) );
	if ( is_wp_error( $worlds ) ) {
		$worlds = array();
	}
	$is_bridge = 2 === count( $worlds );
	$hero_cls  = 't3-hero';
	if ( $is_bridge ) {
		$hero_cls .= ' bridge';
	}
	if ( 'legacy' === $stage ) {
		$hero_cls .= ' legacy';
	}
	if ( 'seeking-partners' === $stage ) {
		$hero_cls .= ' seeking-partners';
	}
	?>
	<article <?php post_class( 't3-project ' . esc_attr( $slug ) ); ?>>
		<div class="t3-wrap">
			<?php echo nb_breadcrumb( nb_project_breadcrumb_crumbs( $post_id ) ); ?>
		</div>

		<?php if ( 'seeking-partners' === $stage ) : ?>
			<?php get_template_part( 'template-parts/t3-seeking-ribbon', null, array( 'post_id' => $post_id ) ); ?>
		<?php elseif ( 'legacy' === $stage ) : ?>
			<?php get_template_part( 'template-parts/t3-legacy-ribbon', null, array( 'post_id' => $post_id ) ); ?>
		<?php endif; ?>

		<section class="<?php echo esc_attr( $hero_cls ); ?>"<?php echo $is_bridge ? nb_bridge_style_attr( $worlds ) : ''; ?>>
			<div class="t3-wrap">
				<div class="t3-hero-grid">
					<div>
						<div class="t3-hero-tags">
							<?php foreach ( $worlds as $i => $world ) : ?>
								<?php if ( $i > 0 ) : ?>
									<span class="conn">×</span>
								<?php endif; ?>
								<?php echo nb_world_chip( $world ); ?>
							<?php endforeach; ?>
							<span class="hero-type"><?php echo esc_html( 'own-venture' === $scope ? 'project · own-venture' : 'project' ); ?></span>
						</div>
						<h1 class="t3-hero-title">
							<?php the_title(); ?>
							<?php if ( nb_meta( $post_id, 'name_tbc' ) ) : ?>
								<?php echo nb_render_tbc( 'seeking-partners' === $stage ? 'שם רשמי' : 'שם אמיתי' ); ?>
							<?php endif; ?>
						</h1>
						<?php if ( $summary = nb_meta( $post_id, 'summary' ) ) : ?>
							<p class="t3-hero-summary"><?php echo esc_html( $summary ); ?></p>
						<?php endif; ?>
						<div class="t3-hero-meta">
							<?php if ( $year = nb_meta( $post_id, 'year' ) ) : ?>
								<span class="row">שנה: <b><?php echo esc_html( $year ); ?></b></span>
							<?php endif; ?>
							<?php if ( $location = nb_meta( $post_id, 'location' ) ) : ?>
								<span class="row">מיקום: <b><?php echo esc_html( $location ); ?></b></span>
							<?php endif; ?>
							<?php if ( $duration = nb_meta( $post_id, 'duration' ) ) : ?>
								<span class="row">משך: <b><?php echo esc_html( $duration ); ?></b></span>
							<?php endif; ?>
						</div>
					</div>
					<div style="position:relative">
						<?php echo nb_stage_stamp( $stage ); ?>
						<?php if ( has_post_thumbnail( $post_id ) ) : ?>
							<div class="img-ph clean t3-hero-image" style="aspect-ratio:4/5;">
								<?php echo get_the_post_thumbnail( $post_id, 'large', array( 'loading' => 'eager', 'alt' => esc_attr( get_the_title() ) ) ); ?>
							</div>
						<?php else : ?>
							<?php echo nb_img_ph( get_the_title(), 'TBD · ' . get_the_title(), 't3-hero-image', '4/5' ); ?>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</section>

		<?php get_template_part( 'template-parts/t3-story' ); ?>

		<?php if ( 'client-case' === $scope ) : ?>
			<section class="t3-wrap t3-section">
				<?php echo nb_sec_head( 2, 'פעילויות שהפעילו', 'מה שהפעלנו כאן.', 'הפרויקט הזה השתמש בפעילויות הבאות. לחץ לקרוא עוד על כל אחת.' ); ?>
				<div class="linked-services">
					<?php foreach ( nb_meta_array( $post_id, 'linked_services' ) as $service_slug ) : ?>
						<?php
						$service = nb_get_service_by_slug( $service_slug );
						if ( ! $service ) {
							continue;
						}
						$s_worlds = wp_get_post_terms( $service->ID, 'world', array( 'fields' => 'slugs' ) );
						$s_bridge = 2 === count( $s_worlds );
						$card_cls = 'ls-card' . ( $s_bridge ? ' bridge-card' : '' );
						?>
						<a href="<?php echo esc_url( get_permalink( $service ) ); ?>" class="<?php echo esc_attr( $card_cls ); ?>"<?php echo $s_bridge ? nb_bridge_style_attr( $s_worlds ) : ''; ?>>
							<div class="tags">
								<?php foreach ( $s_worlds as $i => $w ) : ?>
									<?php if ( $i > 0 ) : ?>
										<span class="conn">×</span>
									<?php endif; ?>
									<?php echo nb_world_chip( $w ); ?>
								<?php endforeach; ?>
							</div>
							<h4><?php echo esc_html( get_the_title( $service ) ); ?></h4>
							<p><?php echo esc_html( nb_meta( $service->ID, 'lede' ) ); ?></p>
							<span class="more">לעמוד הפעילות</span>
						</a>
					<?php endforeach; ?>
				</div>
			</section>
		<?php elseif ( 'own-venture' === $scope ) : ?>
			<section class="t3-wrap t3-section">
				<?php echo nb_sec_head( 2, 'קשור · מיזמים אחרים', 'מה זה ממשיך.', 'המיזם הזה נשען על מה שכבר נבנה.' ); ?>
				<div class="linked-services">
					<?php
					$venture_slugs = nb_meta_array( $post_id, 'more_projects_ids' );
					if ( 'coop-sharon' === $slug ) {
						$venture_slugs = array( 'sfa' );
					}
					foreach ( $venture_slugs as $service_slug ) :
						$service = nb_get_service_by_slug( $service_slug );
						if ( ! $service ) {
							continue;
						}
						$s_worlds = wp_get_post_terms( $service->ID, 'world', array( 'fields' => 'slugs' ) );
						?>
						<a href="<?php echo esc_url( get_permalink( $service ) ); ?>" class="ls-card bridge-card"<?php echo nb_bridge_style_attr( $s_worlds ); ?>>
							<div class="tags">
								<?php foreach ( $s_worlds as $i => $w ) : ?>
									<?php if ( $i > 0 ) : ?>
										<span class="conn">×</span>
									<?php endif; ?>
									<?php echo nb_world_chip( $w ); ?>
								<?php endforeach; ?>
							</div>
							<h4><?php echo esc_html( get_the_title( $service ) ); ?></h4>
							<p><?php echo esc_html( nb_meta( $service->ID, 'lede' ) ); ?></p>
							<span class="more">לעמוד הפעילות</span>
						</a>
					<?php endforeach; ?>
				</div>
			</section>
		<?php endif; ?>

		<?php if ( in_array( $stage, array( 'live', 'legacy' ), true ) ) : ?>
			<?php get_template_part( 'template-parts/t3-outcomes', null, array( 'post_id' => $post_id, 'mode' => 'outcomes' ) ); ?>
		<?php elseif ( 'seeking-partners' === $stage ) : ?>
			<section class="t3-wrap t3-section">
				<?php echo nb_sec_head( 3, 'התוכנית', 'מה צריך לקרות.', nb_meta( $post_id, 'seeking_note' ) ); ?>
				<?php get_template_part( 'template-parts/t3-outcomes', null, array( 'post_id' => $post_id, 'mode' => 'plan' ) ); ?>
			</section>
		<?php endif; ?>

		<?php get_template_part( 'template-parts/t3-gallery', null, array( 'post_id' => $post_id ) ); ?>

		<section class="t3-wrap t3-section">
			<?php echo nb_sec_head( 5, 'פרויקטים נוספים', 'מאותו עולם.', 'פרויקטים שתויגו באותם עולמות. לחץ לקרוא על כל אחד.' ); ?>
			<div class="more-projects">
				<?php
				$related = new WP_Query(
					array(
						'post_type'      => 'project',
						'posts_per_page' => 3,
						'post_status'    => 'publish',
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
				while ( $related->have_posts() ) :
					$related->the_post();
					get_template_part( 'template-parts/t1-proj-card', null, array( 'post' => get_post() ) );
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		</section>

		<?php if ( 'seeking-partners' === $stage ) : ?>
			<section class="t3-wrap t3-section">
				<div class="seeking-cta">
					<div>
						<h3><?php echo esc_html( nb_meta( $post_id, 'seeking_cta_h', 'אם זה נראה לך — דבר איתי.' ) ); ?></h3>
						<p><?php echo esc_html( nb_meta( $post_id, 'seeking_cta_p', 'פגישה ראשונה לא מחייבת כלום.' ) ); ?></p>
					</div>
					<div class="cta-side">
						<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="cta-btn"><?php echo esc_html( nb_meta( $post_id, 'seeking_cta_label', 'התעניינות · שיחה ראשונה' ) ); ?></a>
						<span class="meta"><?php echo esc_html( nb_meta( $post_id, 'seeking_cta_hint', 'ללא התחייבות · 30 דק׳' ) ); ?></span>
					</div>
				</div>
			</section>
		<?php endif; ?>
	</article>
	<?php
endwhile;

get_footer();
