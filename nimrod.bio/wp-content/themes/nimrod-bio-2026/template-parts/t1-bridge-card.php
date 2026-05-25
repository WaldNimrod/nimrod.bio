<?php
defined( 'ABSPATH' ) || exit;
$bridge = $args['bridge'] ?? array();
$a      = $bridge['a'] ?? 'soil';
$b      = $bridge['b'] ?? 'know';
$slug   = $bridge['slug'] ?? '';
$title  = $bridge['title'] ?? '';
$lede   = $bridge['lede'] ?? '';

$service = $slug ? nb_get_service_by_slug( $slug ) : null;
if ( $service ) {
	$title = get_the_title( $service );
	$lede  = nb_meta( $service->ID, 'lede' ) ?: $lede;
	$href  = get_permalink( $service );
} else {
	$href = home_url( '/services/' . $slug . '/' );
}
?>
<a class="bridge-card seam" href="<?php echo esc_url( $href ); ?>" data-w="<?php echo esc_attr( $a . '-' . $b ); ?>" style="--bridge-a:var(--w-<?php echo esc_attr( $a ); ?>);--bridge-b:var(--w-<?php echo esc_attr( $b ); ?>)">
	<span class="bridge-signal-seam" aria-hidden="true"></span>
	<div class="bridge-meta tag-row">
		<?php echo nb_world_chip( $a ); ?>
		<span class="wc-sep conn"><span class="x">×</span></span>
		<?php echo nb_world_chip( $b ); ?>
	</div>
	<h3 class="bridge-title"><?php echo esc_html( $title ); ?></h3>
	<?php if ( $lede ) : ?>
		<p class="bridge-lede lede"><?php echo esc_html( $lede ); ?></p>
	<?php endif; ?>
	<span class="more">לעמוד הפעילות</span>
</a>
