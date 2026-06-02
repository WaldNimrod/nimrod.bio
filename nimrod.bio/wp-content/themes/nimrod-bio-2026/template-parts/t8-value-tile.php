<?php
defined( 'ABSPATH' ) || exit;

$num   = $args['num'] ?? '';
$title = $args['title'] ?? '';
$body  = $args['body'] ?? '';
?>
<div class="principle-tile">
	<div class="pt-k"><?php echo esc_html( $num ); ?></div>
	<h3><?php echo esc_html( $title ); ?></h3><!-- a11y P009-WP006: h4→h3 under principle-section h2 -->
	<p><?php echo wp_kses_post( $body ); ?></p>
</div>
