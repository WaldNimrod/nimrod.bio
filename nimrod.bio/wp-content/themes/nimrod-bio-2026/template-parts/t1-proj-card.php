<?php
defined( 'ABSPATH' ) || exit;
$post = $args['post'] ?? null;
if ( ! $post instanceof WP_Post ) {
	return;
}
$year    = nb_meta( $post->ID, 'year' );
$stage   = nb_meta( $post->ID, 'stage', 'live' );
$summary = nb_meta( $post->ID, 'summary' );
$worlds  = wp_get_post_terms( $post->ID, 'world', array( 'fields' => 'slugs' ) );
?>
<a href="<?php echo esc_url( get_permalink( $post ) ); ?>" class="proj-card">
	<?php echo nb_img_placeholder( 'TBD · ' . get_the_title( $post ), get_the_title( $post ), '16/10' ); ?>
	<div class="body">
		<div class="meta">
			<?php echo nb_stage_stamp( $stage ); ?>
			<?php if ( $year ) : ?>
				<span>· <?php echo esc_html( $year ); ?></span>
			<?php endif; ?>
		</div>
		<h4><?php echo esc_html( get_the_title( $post ) ); ?></h4>
		<?php if ( $summary ) : ?>
			<p style="font-size:14px;color:var(--ink-soft);margin:8px 0 0;line-height:1.55"><?php echo esc_html( $summary ); ?></p>
		<?php endif; ?>
		<div class="meta">
			<?php foreach ( $worlds as $w ) : ?>
				<?php echo nb_world_chip( $w, true ); ?>
			<?php endforeach; ?>
		</div>
	</div>
</a>
