<?php
defined( 'ABSPATH' ) || exit;

$post_id = (int) ( $args['post_id'] ?? get_the_ID() );
$note    = nb_meta( $post_id, 'seeking_note' );
$year    = nb_meta( $post_id, 'year' );
?>
<section class="t3-wrap t3-seeking-ribbon" style="padding-top:24px">
	<div class="seeking-ribbon">
		<span class="badge">מחפש שותפים</span>
		<span><?php echo esc_html( $note ); ?></span>
		<?php if ( $year ) : ?>
			<span class="span"><?php echo esc_html( $year ); ?></span>
		<?php endif; ?>
	</div>
</section>
