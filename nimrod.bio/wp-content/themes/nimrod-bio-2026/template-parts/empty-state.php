<?php
/**
 * template-parts/empty-state.php — on-voice empty state (team_35 Precision Mockup v4).
 * Used wherever an archive/world/blog loop has no posts. NOT "no results found".
 *
 * @param array $args {
 *     @type string $title Heading (on-voice). Required.
 *     @type string $body  Lead paragraph. Required.
 *     @type array  $links List of [ href, label ] pill links. Optional.
 * }
 */
defined( 'ABSPATH' ) || exit;

$es_title = (string) ( $args['title'] ?? 'עדיין אין כאן כלום — זה בסדר.' );
$es_body  = (string) ( $args['body'] ?? '' );
$es_links = is_array( $args['links'] ?? null ) ? $args['links'] : array();
?>
<div class="empty-state">
	<div class="es-emblem">
		<img src="<?php echo esc_url( NB_THEME_URI . '/assets/img/basket-paper.png' ); ?>" alt="" width="60" height="60" loading="lazy" decoding="async">
	</div>
	<h3><?php echo esc_html( $es_title ); ?></h3>
	<?php if ( '' !== $es_body ) : ?>
		<p><?php echo esc_html( $es_body ); ?></p>
	<?php endif; ?>
	<?php if ( $es_links ) : ?>
		<div class="err-links">
			<?php foreach ( $es_links as $lnk ) : ?>
				<a href="<?php echo esc_url( $lnk[0] ); ?>"><?php echo esc_html( $lnk[1] ); ?></a>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>
