<?php
/**
 * archive-project.php — Projects archive landing (/projects/)
 *
 * Lists all `project` CPT posts in the proj-card design-system style.
 * Resolves the home §05 + T1 "לכל הפרויקטים" links, which previously had no
 * template (project CPT was registered has_archive=false). (G1; team_00 2026-06-03.)
 *
 * Markup mirrors front-page.php §05 / T1 §03 proj-cards: scope + stage stamps,
 * world label, summary — pulling REAL project posts (newest first).
 *
 * @package nimrod-bio-2026
 * @version 0.7.16
 */
defined( 'ABSPATH' ) || exit;

get_header();

$nb_proj_archive = new WP_Query(
	array(
		'post_type'      => 'project',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
	)
);
?>
<!-- a11y P009-WP006: <main>→<section> (header.php already provides the page <main id="main">; this was a duplicate/nested main) -->
<section class="t1-wrap projects-archive" aria-label="ארכיון פרויקטים">

	<header class="archive-head">
		<p class="s-eyebrow"><span class="num">פרויקטים ·</span> מהשטח</p>
		<h1 class="s-title">פרויקטים · <span class="under">מהשטח</span></h1>
		<p class="s-lede">מקרים שמספרים את התזה — לקוחות ומיזמים שהקישוריות הפעילה אותם.</p>
	</header>

	<?php if ( $nb_proj_archive->have_posts() ) : ?>
		<div class="projects-grid">
			<?php
			while ( $nb_proj_archive->have_posts() ) :
				$nb_proj_archive->the_post();
				$nb_pid   = get_the_ID();
				$nb_scope = nb_meta( $nb_pid, 'scope' ) === 'client-case' ? 'client' : 'venture';
				$nb_stage = nb_meta( $nb_pid, 'stage' );
				$nb_world = nb_meta( $nb_pid, 'world_label' );
				?>
				<a class="proj-card" href="<?php the_permalink(); ?>">
					<?php echo nb_featured_media( $nb_pid, array( 'ratio' => '16/10', 'class' => 'ph', 'world' => 'soil' ) ); ?>
					<div class="body">
						<div class="scope-row">
							<span class="scope <?php echo esc_attr( $nb_scope ); ?>"><?php echo 'client' === $nb_scope ? 'client-case' : 'own-venture'; ?></span>
							<?php echo nb_stage_stamp( $nb_stage ); ?>
						</div>
						<h2><?php the_title(); ?></h2><!-- a11y P009-WP006: card title h3→h2 (primary content under archive h1) -->
						<?php $nb_summary = nb_meta( $nb_pid, 'summary' ); ?>
						<?php if ( $nb_summary ) : ?><p><?php echo esc_html( $nb_summary ); ?></p><?php endif; ?>
						<?php if ( $nb_world ) : ?><div class="meta"><span><?php echo esc_html( $nb_world ); ?></span></div><?php endif; ?>
					</div>
				</a>
				<?php
			endwhile;
			wp_reset_postdata();
			?>
		</div>
	<?php else : ?>
		<div class="empty-state">
			<h2>עדיין אין פרויקטים שפורסמו.</h2><!-- a11y P009-WP006: h3→h2 under archive h1 -->
			<p>הפרויקטים מהשטח יופיעו כאן כשיהיו מוכנים — מקרים שמספרים את התזה, לא לפי לוח.</p>
			<div class="err-links"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">לדף הבית</a></div>
		</div>
	<?php endif; ?>

</section><!-- a11y P009-WP006: was </main> -->

<?php get_footer(); ?>
