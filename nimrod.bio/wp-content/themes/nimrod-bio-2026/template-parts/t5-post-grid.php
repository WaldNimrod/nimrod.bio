<?php
defined( 'ABSPATH' ) || exit;

$post    = $args['post'] ?? get_post();
$post_id = $post->ID;
$worlds  = nb_get_post_world_slugs( $post_id );
?>
<a href="<?php echo esc_url( get_permalink( $post ) ); ?>" class="post-tile">
	<?php echo nb_post_featured_image( $post_id, '', '16/10' ); ?>
	<div class="tile-meta">
		<time><?php echo esc_html( get_the_date( 'j.n.y', $post ) ); ?></time>
		<span class="dot">·</span>
		<span><?php echo esc_html( nb_post_read_label( $post_id ) ); ?></span>
	</div>
	<h3><?php echo esc_html( get_the_title( $post ) ); ?></h3>
	<p><?php echo esc_html( get_the_excerpt( $post ) ); ?></p>
	<div class="tags">
		<?php foreach ( $worlds as $world ) : ?>
			<?php echo nb_world_chip( $world, true ); ?>
		<?php endforeach; ?>
	</div>
</a>
