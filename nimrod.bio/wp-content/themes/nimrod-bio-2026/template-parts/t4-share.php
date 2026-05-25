<?php
defined( 'ABSPATH' ) || exit;

$permalink = $args['permalink'] ?? get_permalink();
$share_url = rawurlencode( $permalink );
?>
<div class="share-row">
	<button type="button" class="share-btn" data-copy-url="<?php echo esc_url( $permalink ); ?>" aria-label="העתק קישור" title="העתק קישור">
		<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
	</button>
	<a href="<?php echo esc_url( 'https://wa.me/?text=' . $share_url ); ?>" class="share-btn" aria-label="WhatsApp" title="WhatsApp" target="_blank" rel="noopener noreferrer">
		<svg viewBox="0 0 24 24" width="14" height="14" fill="currentColor" aria-hidden="true"><path d="M17.5 14.4c-.3-.1-1.7-.8-1.9-.9-.3-.1-.5-.1-.7.1-.2.3-.8.9-1 1.1-.2.2-.4.2-.6.1-.3-.1-1.2-.5-2.2-1.4-.8-.7-1.4-1.6-1.5-1.9-.2-.3 0-.4.1-.6.1-.1.3-.4.4-.5.1-.2.2-.3.3-.5.1-.2 0-.4 0-.5-.1-.1-.7-1.6-.9-2.2-.2-.6-.5-.5-.7-.5h-.6c-.2 0-.5.1-.8.4-.3.3-1 1-1 2.5s1.1 2.9 1.2 3.1c.1.2 2.1 3.2 5 4.5.7.3 1.3.5 1.7.6.7.2 1.3.2 1.8.1.6-.1 1.7-.7 2-1.4.2-.7.2-1.3.2-1.4-.1-.2-.3-.3-.6-.4zM12 2.1C6.5 2.1 2.1 6.6 2.1 12c0 1.7.4 3.3 1.3 4.7L2.1 22l5.4-1.3c1.4.8 3 1.2 4.5 1.2 5.5 0 9.9-4.5 9.9-9.9S17.5 2.1 12 2.1z"/></svg>
	</a>
	<a href="<?php echo esc_url( 'mailto:?body=' . $share_url ); ?>" class="share-btn" aria-label="אימייל" title="שלח באימייל">
		<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
	</a>
</div>
<script>
document.querySelectorAll('.share-btn[data-copy-url]').forEach(function(btn) {
	btn.addEventListener('click', function() {
		var url = btn.getAttribute('data-copy-url');
		if (navigator.clipboard && navigator.clipboard.writeText) {
			navigator.clipboard.writeText(url);
		}
	});
});
</script>
