<?php
defined( 'ABSPATH' ) || exit;

$world_filter = isset( $_GET['world'] ) ? explode( ',', sanitize_text_field( wp_unslash( $_GET['world'] ) ) ) : array();
$world_filter = array_values( array_intersect( $world_filter, array_keys( nb_get_worlds() ) ) );
$view         = ( isset( $_GET['view'] ) && 'grid' === $_GET['view'] ) ? 'grid' : 'flow';

get_header();

global $wp_query;
$visible_count = (int) $wp_query->post_count;
$total_posts   = (int) wp_count_posts( 'post' )->publish;
$latest        = get_posts(
	array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => 1,
		'orderby'        => 'date',
		'order'          => 'DESC',
	)
);
$latest_date = $latest ? get_the_date( 'j.n.y', $latest[0] ) : '—';
?>
<section class="t5-section first">
	<div class="t5-wrap blog-header">
		<div class="blog-header-grid">
			<div>
				<h1>בלוג</h1>
				<p class="lede">
					יומן עונה, מקרים מהשטח, רעיונות שעוד לא הבשילו. כתוב לעצמי קודם — <em>שיתפתי כי אולי גם לך זה שייך.</em>
				</p>
			</div>
			<div class="stats">
				<div><span class="v"><?php echo esc_html( (string) $total_posts ); ?></span> פוסטים</div>
				<div>עדכון אחרון: <?php echo esc_html( $latest_date ); ?></div>
			</div>
		</div>
	</div>
</section>

<?php
get_template_part(
	'template-parts/t5-filter-bar',
	null,
	array(
		'world_filter'  => $world_filter,
		'view'          => $view,
		'visible_count' => $visible_count,
		'total_posts'   => $total_posts,
	)
);
?>

<?php if ( 'grid' === $view ) : ?>
	<?php if ( have_posts() ) : ?>
		<?php
		the_post();
		$featured_id = get_the_ID();
		?>
		<div class="blog-featured">
			<div class="t5-wrap">
				<div class="blog-featured-grid">
					<div><?php echo nb_post_featured_image( $featured_id, 'feat-img', '16/10' ); ?></div>
					<div>
						<div class="feat-eyebrow">
							<span>★ נבחר העונה</span>
							<span>·</span>
							<span><?php echo esc_html( nb_post_read_label( $featured_id ) ); ?></span>
						</div>
						<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<p><?php echo esc_html( get_the_excerpt() ); ?></p>
						<div class="meta">
							<time><?php echo esc_html( get_the_date( 'j.n.y' ) ); ?></time>
							<?php foreach ( nb_get_post_world_slugs( $featured_id ) as $world ) : ?>
								<span class="dot">·</span>
								<?php echo nb_world_chip( $world, true ); ?>
							<?php endforeach; ?>
						</div>
						<a class="read-more" href="<?php the_permalink(); ?>">לקריאה</a>
					</div>
				</div>
			</div>
		</div>
		<?php rewind_posts(); ?>
	<?php endif; ?>

	<section class="t5-section">
		<div class="t5-wrap">
			<?php if ( have_posts() ) : ?>
				<div class="t5-grid posts-grid">
					<?php
					$first = true;
					while ( have_posts() ) :
						the_post();
						if ( $first ) {
							$first = false;
							continue;
						}
						get_template_part( 'template-parts/t5-post-grid' );
					endwhile;
					?>
				</div>
			<?php else : ?>
				<div class="blog-empty">
					אין פוסטים תחת הסינון הנוכחי.
					<a class="filter-reset" href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">נקה סינון</a>
				</div>
			<?php endif; ?>
		</div>
	</section>
<?php else : ?>
	<section class="t5-section">
		<div class="t5-wrap">
			<?php if ( have_posts() ) : ?>
				<div class="t5-flow post-flow">
					<?php
					while ( have_posts() ) :
						the_post();
						get_template_part( 'template-parts/t5-post-flow' );
					endwhile;
					?>
				</div>
			<?php else : ?>
				<div class="blog-empty">
					אין פוסטים תחת הסינון הנוכחי.
					<a class="filter-reset" href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">נקה סינון</a>
				</div>
			<?php endif; ?>
		</div>
	</section>
<?php endif; ?>

<div class="blog-end">
	<div>זה הכל לעכשיו — <?php echo esc_html( (string) $total_posts ); ?> פוסטים, עוד גדלים</div>
	<span class="rule"></span>
</div>
<?php
get_footer();
