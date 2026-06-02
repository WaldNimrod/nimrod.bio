<?php
defined( 'ABSPATH' ) || exit;

$post_id  = (int) ( $args['post_id'] ?? get_the_ID() );
$sections = nb_json_meta( $post_id, 'sections' );
$cols     = array();
foreach ( array( 'who', 'how', 'what' ) as $key ) {
	if ( empty( $sections[ $key ] ) ) {
		continue;
	}
	$col = $sections[ $key ];
	if ( is_string( $col ) ) {
		continue;
	}
	$cols[] = $col;
}
if ( ! $cols ) {
	return;
}
?>
<section class="t2-wrap t2-section" aria-labelledby="nb-svc-overview">
	<h2 id="nb-svc-overview" class="nb-sr-only">סקירת השירות</h2><!-- a11y P009-WP006: sr-only section heading restores h1→h2→h3 order -->
	<div class="three-col">
		<?php foreach ( $cols as $col ) : ?>
			<div class="col">
				<?php if ( ! empty( $col['num'] ) ) : ?>
					<div class="col-num"><?php echo esc_html( $col['num'] ); ?></div>
				<?php endif; ?>
				<?php if ( ! empty( $col['title'] ) ) : ?>
					<h3><?php echo esc_html( $col['title'] ); ?></h3>
				<?php endif; ?>
				<?php if ( ! empty( $col['bullets'] ) && is_array( $col['bullets'] ) ) : ?>
					<ul>
						<?php foreach ( $col['bullets'] as $bullet ) : ?>
							<li><?php echo esc_html( $bullet ); ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
				<?php if ( ! empty( $col['meta'] ) ) : ?>
					<div class="meta"><?php echo esc_html( $col['meta'] ); ?></div>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
</section>
