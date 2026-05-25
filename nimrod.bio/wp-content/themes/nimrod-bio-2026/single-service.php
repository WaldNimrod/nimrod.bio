<?php
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
	$is_bridge = 2 === count( $worlds );
	$is_free   = (bool) nb_meta( $post_id, 'is_free' );
	?>
	<article <?php post_class( 't2-instance ' . esc_attr( $slug ) ); ?>>
		<div class="t2-wrap">
			<?php echo nb_breadcrumb( nb_service_breadcrumb_crumbs( $post_id ) ); ?>
		</div>

		<?php
		get_template_part(
			'template-parts/t2-hero',
			null,
			array(
				'post_id'   => $post_id,
				'slug'      => $slug,
				'worlds'    => $worlds,
				'is_bridge' => $is_bridge,
				'is_free'   => $is_free,
			)
		);
		?>

		<?php if ( 'produce' === $slug ) : ?>
			<?php get_template_part( 'template-parts/t2-heritage-strip' ); ?>
		<?php endif; ?>

		<?php get_template_part( 'template-parts/t2-meta-strip', null, array( 'post_id' => $post_id ) ); ?>

		<?php if ( 'sfa' === $slug ) : ?>
			<section class="t2-wrap t2-section sfa-origin-flow" aria-label="Origin · 3 שלבים">
				<?php
				echo nb_sec_head( 1, 'מקור · CDIP בפעולה', 'החממה מזינה את הידע, הידע מקודד את הסוכן.', 'המקרה שבסיס ה-SFA. ידע מהשטח, לא מהנייר.' );
				?>
				<div class="sfa-origin">
					<div>
						<div class="label">מהיכן SFA מגיע</div>
						<p>ה-SFA הוא הקצה של שורה — שמתחילה בחממה ההידרופונית שלי, עוברת דרך הייעוץ של חממות אחרות, ומקודדת לבסוף לבסיס ידע. זה לא מוצר טכנולוגי שמנסה למצוא שוק; זה מוצר שטח שהשתמש בטכנולוגיה.</p>
					</div>
					<div class="flow">
						<div class="step"><span class="n">1</span><span class="t">החממה ההידרופונית · 240 מ״ר · עונה 5</span></div>
						<div class="step"><span class="n">2</span><span class="t">ייעוץ ל-4 חממות נוספות · תיעוד מקרים</span></div>
						<div class="step"><span class="n">3</span><span class="t">SFA · קידוד הידע לסוכן AI · v0.1</span></div>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<?php get_template_part( 'template-parts/t2-three-col', null, array( 'post_id' => $post_id ) ); ?>

		<section class="t2-wrap t2-section">
			<?php
			$sec_num = 'sfa' === $slug ? 5 : 4;
			echo nb_sec_head(
				$sec_num,
				'sfa' === $slug ? 'פרויקטים · שתורמים מקרים ל-SFA' : ( 'consulting-hydro' === $slug ? 'פרויקטים · השתמשו בייעוץ הזה' : 'פרויקטים · השתמשו ב-service הזה' ),
				'sfa' === $slug ? 'מי שכבר תורם ידע.' : ( 'consulting-hydro' === $slug ? 'חממות שעבדנו עליהן.' : 'מי שכבר עובד איתי.' ),
				'sfa' === $slug ? 'חוות שכבר תורמות מקרים לבסיס הידע.' : ( 'consulting-hydro' === $slug ? 'פרויקטים שעברו דרך אבחון, תכנון, או ליווי שנתי.' : 'לא רשימה — דוגמאות פעילות.' )
			);
			$linked = nb_meta_array( $post_id, 'linked_projects' );
			?>
			<div class="linked-projects">
				<?php foreach ( $linked as $project_slug ) : ?>
					<?php
					$project = nb_get_project_by_slug( $project_slug );
					if ( ! $project ) {
						continue;
					}
					$p_stage   = nb_meta( $project->ID, 'stage', 'live' );
					$p_year    = nb_meta( $project->ID, 'year' );
					$p_summary = nb_meta( $project->ID, 'summary' );
					$name_tbc  = (bool) nb_meta( $project->ID, 'name_tbc' );
					?>
					<a href="<?php echo esc_url( get_permalink( $project ) ); ?>" class="lp-card">
						<?php echo nb_img_placeholder( 'TBD · ' . get_the_title( $project ), get_the_title( $project ), '16/10' ); ?>
						<div class="body">
							<div class="meta">
								<?php echo nb_stage_stamp( $p_stage ); ?>
								<?php if ( $p_year ) : ?>
									<span>· <?php echo esc_html( $p_year ); ?></span>
								<?php endif; ?>
							</div>
							<h4>
								<?php echo esc_html( get_the_title( $project ) ); ?>
								<?php if ( $name_tbc ) : ?>
									<?php echo nb_render_tbc(); ?>
								<?php endif; ?>
							</h4>
							<?php if ( $p_summary ) : ?>
								<p style="font-size:14px;color:var(--ink-soft);margin:0;line-height:1.55"><?php echo esc_html( $p_summary ); ?></p>
							<?php endif; ?>
						</div>
					</a>
				<?php endforeach; ?>
			</div>
		</section>

		<section class="t2-wrap t2-section">
			<?php
			$post_sec = 'sfa' === $slug ? 6 : 5;
			echo nb_sec_head(
				$post_sec,
				'פוסטים קשורים',
				'ביומן.',
				'sfa' === $slug ? 'פוסטים על המעבר מהאדמה לדיגיטל, ומה שמחבר ביניהם.' : ( 'consulting-hydro' === $slug ? 'פוסטים על תכנון חממה, מערכות, ומה שהשטח לימד.' : 'פוסטים שעוסקים בנושאי תוצרת / חממה / שדה.' )
			);
			?>
			<div class="related-posts">
				<p class="t2-empty-note">פוסטים יופיעו כאן לאחר WP004.</p>
			</div>
		</section>

		<?php get_template_part( 'template-parts/t2-final-cta', null, array( 'post_id' => $post_id, 'slug' => $slug, 'is_free' => $is_free ) ); ?>
	</article>
	<?php
endwhile;

get_footer();
