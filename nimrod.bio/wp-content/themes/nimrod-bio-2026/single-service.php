<?php
/**
 * T2s · Single service — v5 precision composition (WP007 Phase 2).
 * Order (v5 t2s): breadcrumb → svc-single-hero (chip, h1, lede, ssh-meta, CTA, portrait)
 *   → §01 feat-grid → svc-pull → §02 svc-steps → §03 field-proof projects
 *   → §04 cross-world bridge → [BCS gallery preserved] → final-cta.
 * Every v5 section renders only when its meta is present (graceful fallback);
 * the anchor service (consulting-hydro) carries the full v5-approved copy.
 * Shared chrome reuses live helpers (nb_breadcrumb / nb_sec_head / nb_world_chip).
 * team_00-authorized scope expansion 2026-06-03 (beyond gate-approved LOD400 §3).
 */
defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	$post_id = get_the_ID();
	$slug    = get_post()->post_name;
	$worlds  = wp_get_post_terms( $post_id, 'world', array( 'fields' => 'slugs' ) );
	if ( is_wp_error( $worlds ) ) {
		$worlds = array();
	}
	$primary   = $worlds[0] ?? 'soil';
	$type      = nb_meta( $post_id, 'service_type', 'service' );
	$ssh_lede  = nb_meta( $post_id, 'lede' );
	if ( '' === $ssh_lede ) {
		$ssh_lede = nb_meta( $post_id, 'tagline' );
	}
	$cta_label  = nb_meta( $post_id, 'cta_label', 'קבע שיחת היכרות' );
	$meta_strip = nb_json_meta( $post_id, 'meta_strip' );
	$feat_tiles = nb_json_meta( $post_id, 'feat_tiles' );
	$svc_pull   = nb_meta( $post_id, 'svc_pull' );
	$svc_steps  = nb_json_meta( $post_id, 'svc_steps' );
	$bridge     = nb_json_meta( $post_id, 'bridge' );
	$linked     = nb_meta_array( $post_id, 'linked_projects' );
	$sec        = 0;
	?>
	<article <?php post_class( 't2-instance t2s ' . esc_attr( $slug ) ); ?>>
		<div class="t2-wrap">
			<?php echo nb_breadcrumb( nb_service_breadcrumb_crumbs( $post_id ) ); ?>
		</div>

		<section class="t2-wrap svc-single-hero">
			<div>
				<div class="ssh-tag">
					<?php echo nb_world_chip( $primary ); ?>
					<span class="hero-type"><?php echo esc_html( $type ); ?></span>
				</div>
				<h1><?php the_title(); ?></h1>
				<?php if ( $ssh_lede ) : ?>
					<p class="ssh-lede"><?php echo esc_html( $ssh_lede ); ?></p>
				<?php endif; ?>
				<?php if ( $meta_strip ) : ?>
					<div class="ssh-meta">
						<?php foreach ( $meta_strip as $m ) : ?>
							<div class="m">
								<span class="mv"><?php echo esc_html( $m['v'] ?? '' ); ?></span>
								<span class="mk"><?php echo esc_html( $m['k'] ?? '' ); ?></span>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
				<div class="ssh-act">
					<a class="hero-cta primary" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php echo esc_html( $cta_label ); ?></a>
				</div>
			</div>
			<div class="ssh-portrait">
				<?php echo nb_world_chip( $primary ); ?>
				<?php if ( has_post_thumbnail( $post_id ) ) : ?>
					<div class="img-ph clean">
						<?php echo get_the_post_thumbnail( $post_id, 'large', array( 'loading' => 'eager', 'fetchpriority' => 'high', 'alt' => esc_attr( get_the_title( $post_id ) ) ) ); ?>
					</div>
				<?php else : ?>
					<?php echo nb_img_placeholder( 'TBD · ' . get_the_title( $post_id ), get_the_title( $post_id ), '4/5' ); ?>
				<?php endif; ?>
			</div>
		</section>

		<?php if ( 'produce' === $slug ) : ?>
			<section class="t2-wrap t2-section">
				<?php get_template_part( 'template-parts/t2-heritage-strip' ); ?>
			</section>
		<?php endif; ?>

		<?php if ( $feat_tiles ) : ?>
			<section class="t2-wrap t2-section">
				<?php echo nb_sec_head( ++$sec, 'מה כלול', 'מה אתה מקבל.' ); ?>
				<div class="feat-grid">
					<?php foreach ( $feat_tiles as $tile ) : ?>
						<div class="feat-tile">
							<?php if ( ! empty( $tile['k'] ) ) : ?>
								<span class="ft-k"><?php echo esc_html( $tile['k'] ); ?></span>
							<?php endif; ?>
							<h3><?php echo esc_html( $tile['title'] ?? '' ); ?></h3>
							<?php if ( ! empty( $tile['body'] ) ) : ?>
								<p><?php echo esc_html( $tile['body'] ); ?></p>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>
			</section>
		<?php endif; ?>

		<?php if ( $svc_pull ) : ?>
			<section class="t2-wrap t2-section">
				<p class="svc-pull"><?php echo wp_kses( $svc_pull, array( 'em' => array() ) ); ?></p>
			</section>
		<?php endif; ?>

		<?php if ( $svc_steps ) : ?>
			<section class="t2-wrap t2-section">
				<?php echo nb_sec_head( ++$sec, 'איך זה עובד', 'ארבעה צעדים, בלי הפתעות.' ); ?>
				<div class="svc-steps">
					<?php foreach ( $svc_steps as $step ) : ?>
						<div class="svc-step">
							<div>
								<h3><?php echo esc_html( $step['title'] ?? '' ); ?></h3>
								<?php if ( ! empty( $step['body'] ) ) : ?>
									<p><?php echo esc_html( $step['body'] ); ?></p>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</section>
		<?php endif; ?>

		<?php if ( $linked ) : ?>
			<section class="t2-wrap t2-section">
				<?php echo nb_sec_head( ++$sec, 'מהשטח · פרויקטים', 'איפה זה כבר עבד.' ); ?>
				<div class="linked-projects">
					<?php
					foreach ( $linked as $project_slug ) :
						$project = nb_get_project_by_slug( $project_slug );
						if ( ! $project ) {
							continue;
						}
						$p_stage   = nb_meta( $project->ID, 'stage', 'live' );
						$p_year    = nb_meta( $project->ID, 'year' );
						$p_summary = nb_meta( $project->ID, 'summary' );
						?>
						<a href="<?php echo esc_url( get_permalink( $project ) ); ?>" class="lp-card">
							<?php
							if ( has_post_thumbnail( $project->ID ) ) {
								echo '<div class="img-ph clean">' . get_the_post_thumbnail( $project->ID, 'medium_large', array( 'alt' => esc_attr( get_the_title( $project ) ) ) ) . '</div>';
							} else {
								echo nb_img_placeholder( 'TBD · ' . get_the_title( $project ), get_the_title( $project ), '16/10' );
							}
							?>
							<div class="body">
								<div class="meta">
									<?php echo nb_stage_stamp( $p_stage ); ?>
									<?php if ( $p_year ) : ?>
										<span>· <?php echo esc_html( $p_year ); ?></span>
									<?php endif; ?>
								</div>
								<h3><?php echo esc_html( get_the_title( $project ) ); ?></h3>
								<?php if ( $p_summary ) : ?>
									<p class="lp-summary"><?php echo esc_html( $p_summary ); ?></p>
								<?php endif; ?>
							</div>
						</a>
					<?php endforeach; ?>
				</div>
			</section>
		<?php endif; ?>

		<?php
		if ( ! empty( $bridge['title'] ) ) :
			$b_a    = $bridge['a'] ?? 'know';
			$b_b    = $bridge['b'] ?? 'code';
			$b_pair = 'b-' . preg_replace( '/[^a-z]/', '', $b_a ) . '-' . preg_replace( '/[^a-z]/', '', $b_b );
			$b_proj = ! empty( $bridge['slug'] ) ? nb_get_project_by_slug( $bridge['slug'] ) : null;
			$b_href = $b_proj ? get_permalink( $b_proj ) : ( ! empty( $bridge['href'] ) ? $bridge['href'] : home_url( '/world/' . $b_b . '/' ) );
			?>
			<section class="t2-wrap t2-section">
				<?php echo nb_sec_head( ++$sec, 'קשור · גשר', 'איפה זה מתחבר.' ); ?>
				<a class="svc-bridge <?php echo esc_attr( $b_pair ); ?>" href="<?php echo esc_url( $b_href ); ?>">
					<span class="b-conn">
						<?php echo nb_world_chip( $b_a ); ?>
						<?php echo nb_world_chip( $b_b ); ?>
					</span>
					<h3><?php echo esc_html( $bridge['title'] ); ?></h3>
					<?php if ( ! empty( $bridge['body'] ) ) : ?>
						<p><?php echo esc_html( $bridge['body'] ); ?></p>
					<?php endif; ?>
					<?php if ( ! empty( $bridge['more'] ) ) : ?>
						<span class="more"><?php echo esc_html( $bridge['more'] ); ?></span>
					<?php endif; ?>
				</a>
			</section>
		<?php endif; ?>

		<?php
		// Non-regression: preserve the existing BCS field gallery (AT-2).
		$_nb_gallery_items = nb_meta_array( $post_id, 'gallery' );
		if ( ! empty( $_nb_gallery_items ) ) :
			get_template_part( 'template-parts/t3-gallery', null, array( 'post_id' => $post_id ) );
		endif;
		?>

		<?php
		get_template_part(
			'template-parts/t2-final-cta',
			null,
			array(
				'post_id' => $post_id,
				'slug'    => $slug,
				'is_free' => (bool) nb_meta( $post_id, 'is_free' ),
			)
		);
		?>
	</article>
	<?php
endwhile;

get_footer();
