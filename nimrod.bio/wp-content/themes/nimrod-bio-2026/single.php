<?php
defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	$post_id   = get_the_ID();
	$raw       = get_post_field( 'post_content', $post_id );
	$prepared  = nb_prepare_post_body_html( apply_filters( 'the_content', $raw ) );
	$toc       = nb_extract_toc( $prepared );
	$worlds    = nb_get_post_world_slugs( $post_id );
	$tags      = get_the_tags( $post_id ) ?: array();
	$related   = nb_get_related_posts( $post_id, 3 );
	$permalink = get_permalink();
	?>
<article class="t4-post">
	<div class="t4-wrap">
		<?php
		echo nb_breadcrumb(
			array(
				array(
					'label' => 'בית',
					'href'  => home_url( '/' ),
				),
				array(
					'label' => 'בלוג',
					'href'  => home_url( '/blog/' ),
				),
				array(
					'label' => get_the_title(),
				),
			)
		);
		?>
	</div>

	<section class="post-hero">
		<div class="t4-wrap">
			<div class="post-hero-meta-top">
				<span>בלוג</span>
				<span class="dot">·</span>
				<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'j.n.y' ) ); ?></time>
				<span class="dot">·</span>
				<span><b><?php echo esc_html( nb_post_read_label( $post_id ) ); ?></b> קריאה</span>
				<?php if ( $worlds ) : ?>
					<span class="dot">·</span>
					<?php foreach ( $worlds as $world ) : ?>
						<?php echo nb_world_chip( $world, true ); ?>
					<?php endforeach; ?>
				<?php endif; ?>
				<?php if ( $tags ) : ?>
					<span class="dot">·</span>
					<?php foreach ( $tags as $tag ) : ?>
						<span>#<?php echo esc_html( $tag->name ); ?></span>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>

			<h1 class="post-title"><?php the_title(); ?></h1>
			<?php if ( has_excerpt() ) : ?>
				<p class="post-subtitle"><?php echo esc_html( get_the_excerpt() ); ?></p>
			<?php endif; ?>

			<div class="post-meta-row">
				<span class="author">
					<span class="av">נ</span>
					נמרוד ולד
				</span>
				<span>· מחבר ועורך</span>
			</div>
		</div>

		<div class="t4-wrap">
			<div class="post-hero-image">
				<?php echo nb_post_featured_image( $post_id, 'post-hero-image', '21/9' ); ?>
			</div>
		</div>
	</section>

	<div class="t4-wrap post-layout">
		<div class="t4-body post-body">
			<?php echo $prepared; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- filtered content. ?>
		</div>
		<?php
		get_template_part(
			'template-parts/t4-aside',
			null,
			array(
				'toc'       => $toc,
				'permalink' => $permalink,
				'post_id'   => $post_id,
				'worlds'    => $worlds,
			)
		);
		?>
	</div>

	<?php if ( $related ) : ?>
		<div class="related-posts-block">
			<section class="t4-wrap">
				<?php echo nb_sec_head( 0, 'המשך לקרוא', 'פוסטים קשורים.', 'פוסטים שתויגו בעולמות חופפים.' ); ?>
				<div class="posts-grid">
					<?php foreach ( $related as $rel ) : ?>
						<?php
						get_template_part(
							'template-parts/t5-post-grid',
							null,
							array(
								'post' => $rel,
							)
						);
						?>
					<?php endforeach; ?>
				</div>
			</section>
		</div>
	<?php endif; ?>
</article>
	<?php
endwhile;

get_footer();
