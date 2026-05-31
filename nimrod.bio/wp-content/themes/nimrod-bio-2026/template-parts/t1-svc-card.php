<?php
defined( 'ABSPATH' ) || exit;
$post = $args['post'] ?? null;
if ( ! $post instanceof WP_Post ) {
	return;
}
$lede    = nb_meta( $post->ID, 'lede' );
$tagline = nb_meta( $post->ID, 'tagline' );
$stage   = nb_meta( $post->ID, 'stage', 'live' );
$worlds  = wp_get_post_terms( $post->ID, 'world', array( 'fields' => 'slugs' ) );
$world   = $worlds[0] ?? ( $args['world'] ?? 'soil' );
$layout  = $args['layout'] ?? 'card';
if ( 'lattice' === $layout ) {
	?>
	<div class="lat-side svc-card">
		<h4><a href="<?php echo esc_url( get_permalink( $post ) ); ?>"><?php echo esc_html( get_the_title( $post ) ); ?></a></h4>
		<?php if ( $lede ) : ?>
			<p><?php echo esc_html( $lede ); ?></p>
		<?php endif; ?>
		<div class="tag-row">
			<?php echo nb_world_chip( $world, true ); ?>
			<?php if ( $tagline ) : ?>
				<span class="meta" style="font-family:JetBrains Mono;font-size:10px;color:var(--ink-soft)">· <?php echo esc_html( $tagline ); ?></span>
			<?php endif; ?>
		</div>
	</div>
	<?php
	return;
}
?>
<a href="<?php echo esc_url( get_permalink( $post ) ); ?>" class="svc-card has-img" style="text-decoration:none;color:inherit">
	<div class="strip"></div>
	<?php if ( has_post_thumbnail( $post->ID ) ) : ?>
		<div class="img-ph clean" style="aspect-ratio:16/10;">
			<?php echo get_the_post_thumbnail( $post->ID, 'medium_large', array( 'loading' => 'lazy', 'alt' => esc_attr( get_the_title( $post ) ) ) ); ?>
		</div>
	<?php else : ?>
		<?php echo nb_img_placeholder( get_the_title( $post ), get_the_title( $post ), '16/10' ); ?>
	<?php endif; ?>
	<div class="body">
		<h3><?php echo esc_html( get_the_title( $post ) ); ?></h3>
		<?php if ( $lede ) : ?>
			<p class="lede"><?php echo esc_html( $lede ); ?></p>
		<?php endif; ?>
		<div class="meta">
			<?php echo nb_world_chip( $world, true ); ?>
			<span>· <?php echo esc_html( $stage ); ?></span>
		</div>
	</div>
</a>
