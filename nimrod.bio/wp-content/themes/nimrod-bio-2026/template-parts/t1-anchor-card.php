<?php
defined( 'ABSPATH' ) || exit;
$post = $args['post'] ?? null;
if ( ! $post instanceof WP_Post ) {
	?>
	<div class="anchor-card lat-anchor" style="grid-column:2/3;grid-row:1/3">
		<span class="anchor-eye">▣ תשתית · עוגן</span>
		<h3>TBD · anchor service</h3>
		<p>עוגן לעולם זה טרם הוגדר ב־CPT. team_00 יקבע `_nb_is_anchor_for_world`.</p>
	</div>
	<?php
	return;
}

$lede   = nb_meta( $post->ID, 'lede' );
$strip  = json_decode( (string) nb_meta( $post->ID, 'meta_strip', '[]' ), true );
$facts  = is_array( $strip ) ? array_slice( $strip, 0, 3 ) : array();
$tagline = nb_meta( $post->ID, 'tagline' );
?>
<div class="lat-anchor anchor-card">
	<?php if ( has_post_thumbnail( $post->ID ) ) : ?>
		<div class="img-ph clean anchor-img">
			<?php echo get_the_post_thumbnail( $post->ID, 'medium_large', array( 'loading' => 'lazy', 'alt' => esc_attr( get_the_title( $post ) ) ) ); ?>
		</div>
	<?php else : ?>
		<?php echo nb_img_placeholder( 'TBD · ' . get_the_title( $post ), get_the_title( $post ), 'auto', 'anchor-img clean' ); ?>
	<?php endif; ?>
	<span class="anchor-eye">▣ תשתית · עוגן · <?php echo esc_html( nb_world_label( $args['world'] ?? 'soil' ) ); ?></span>
	<h3><?php echo esc_html( get_the_title( $post ) ); ?></h3>
	<?php if ( $lede ) : ?>
		<p><?php echo esc_html( $lede ); ?></p>
	<?php endif; ?>
	<?php if ( $facts ) : ?>
		<div class="facts">
			<?php foreach ( $facts as $fact ) : ?>
				<div>
					<div class="k"><?php echo esc_html( $fact['k'] ?? '' ); ?></div>
					<div class="v"><?php echo esc_html( $fact['v'] ?? '' ); ?></div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
	<?php if ( $tagline ) : ?>
		<div class="links"><a href="<?php echo esc_url( get_permalink( $post ) ); ?>"><?php echo esc_html( $tagline ); ?></a></div>
	<?php endif; ?>
</div>
