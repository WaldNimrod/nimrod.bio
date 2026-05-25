<?php
defined( 'ABSPATH' ) || exit;

$kind       = $args['kind'] ?? 'press';
$kind_label = $args['kind_label'] ?? '';
$outlet     = $args['outlet'] ?? '';
$date       = $args['date'] ?? '';
$title      = $args['title'] ?? '';
$href       = $args['href'] ?? '#';
$tbc        = ! empty( $args['tbc'] );

$icons = array(
	'press'   => '<path d="M4 4h13a2 2 0 0 1 2 2v14H6a2 2 0 0 1-2-2V4z"/><path d="M19 8h3v10a2 2 0 0 1-2 2"/><path d="M8 8h7M8 12h7M8 16h7"/>',
	'podcast' => '<rect x="9" y="2" width="6" height="12" rx="3"/><path d="M5 10v2a7 7 0 0 0 14 0v-2M12 19v3"/>',
	'talk'    => '<rect x="3" y="3" width="18" height="14" rx="2"/><path d="m10 8 5 3-5 3V8z" fill="currentColor"/><path d="M8 21h8"/>',
	'write'   => '<path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25z"/><path d="m14.06 6.19 3.75 3.75"/>',
);
$icon_path = $icons[ $kind ] ?? $icons['press'];
?>
<a href="<?php echo esc_url( $href ); ?>" class="media-item<?php echo $tbc ? ' tbc' : ''; ?>">
	<div class="media-kind <?php echo esc_attr( $kind ); ?>">
		<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
			<?php echo $icon_path; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</svg>
	</div>
	<div class="media-body">
		<div class="media-meta">
			<span><?php echo esc_html( $kind_label ); ?></span>
			<span>·</span>
			<span class="outlet"><?php echo esc_html( $outlet ); ?></span>
			<span>·</span>
			<span><?php echo esc_html( $date ); ?></span>
		</div>
		<div class="media-title"><?php echo esc_html( $title ); ?></div>
	</div>
	<span class="media-arrow" aria-hidden="true">↗</span>
</a>
