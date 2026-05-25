<?php
defined( 'ABSPATH' ) || exit;

$post_id = (int) ( $args['post_id'] ?? get_the_ID() );
$items   = nb_json_meta( $post_id, 'meta_strip' );
if ( ! $items ) {
	return;
}
?>
<section class="t2-wrap" style="padding-block:28px">
	<div class="svc-meta-strip">
		<?php foreach ( $items as $item ) : ?>
			<div>
				<div class="k"><?php echo esc_html( $item['k'] ?? '' ); ?></div>
				<div class="v<?php echo ! empty( $item['spark'] ) ? ' spark' : ''; ?>"><?php echo esc_html( $item['v'] ?? '' ); ?></div>
			</div>
		<?php endforeach; ?>
	</div>
</section>
