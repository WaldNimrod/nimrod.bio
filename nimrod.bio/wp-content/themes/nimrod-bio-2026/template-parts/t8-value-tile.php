<?php
defined( 'ABSPATH' ) || exit;

$num   = $args['num'] ?? '';
$title = $args['title'] ?? '';
$body  = $args['body'] ?? '';
?>
<div class="principle-tile">
	<div class="pt-k"><?php echo esc_html( $num ); ?></div>
	<h4><?php echo esc_html( $title ); ?></h4>
	<p><?php echo wp_kses_post( $body ); ?></p>
</div>
