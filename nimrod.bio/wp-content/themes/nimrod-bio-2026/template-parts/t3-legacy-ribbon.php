<?php
defined( 'ABSPATH' ) || exit;

$post_id   = (int) ( $args['post_id'] ?? get_the_ID() );
$legacy_of = nb_meta( $post_id, 'legacy_of' );
$year      = nb_meta( $post_id, 'year' );
?>
<section class="t3-wrap t3-legacy-ribbon" style="padding-top:24px">
	<div class="legacy-ribbon">
		<span class="badge">מורשת</span>
		<span><?php echo esc_html( $legacy_of ); ?></span>
		<?php if ( $year ) : ?>
			<span class="span">פעיל: <?php echo esc_html( $year ); ?></span>
		<?php endif; ?>
	</div>
</section>
