<?php
defined( 'ABSPATH' ) || exit;

$post_id = (int) ( $args['post_id'] ?? get_the_ID() );
$mode    = (string) ( $args['mode'] ?? 'outcomes' );
$data    = nb_json_meta( $post_id, 'outcomes' );
$tiles   = $data['tiles'] ?? $data;
if ( ! is_array( $tiles ) || ! $tiles ) {
	return;
}

if ( 'outcomes' === $mode ) :
	?>
	<section class="t3-wrap t3-section">
		<?php
		echo nb_sec_head(
			3,
			'legacy' === nb_meta( $post_id, 'stage' ) ? 'במספרים' : 'תוצאות',
			$data['title'] ?? ( 'legacy' === nb_meta( $post_id, 'stage' ) ? '9 עונות במספרים.' : 'מספרים אחרי שנה.' ),
			''
		);
		?>
		<div class="outcomes">
			<?php foreach ( $tiles as $tile ) : ?>
				<div class="oc-tile">
					<span class="n"><?php echo esc_html( $tile['n'] ?? $tile['number'] ?? '' ); ?></span>
					<div class="v <?php echo esc_attr( $tile['color'] ?? '' ); ?>"><?php echo esc_html( $tile['v'] ?? $tile['number'] ?? '' ); ?></div>
					<div class="l"><?php echo esc_html( $tile['l'] ?? $tile['label'] ?? '' ); ?></div>
					<?php if ( ! empty( $tile['desc'] ) ) : ?>
						<p class="desc"><?php echo esc_html( $tile['desc'] ); ?></p>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
		$note = nb_meta( $post_id, 'outcomes_note' );
		if ( ! $note && ! empty( $data['note'] ) ) {
			$note = $data['note'];
		}
		if ( $note ) :
			?>
			<div class="outcomes-note"><?php echo esc_html( $note ); ?></div>
		<?php endif; ?>
	</section>
	<?php
else :
	?>
	<div class="outcomes">
		<?php foreach ( $tiles as $tile ) : ?>
			<div class="oc-tile">
				<span class="n"><?php echo esc_html( $tile['n'] ?? '' ); ?></span>
				<div class="v <?php echo esc_attr( $tile['color'] ?? 'code' ); ?>"><?php echo esc_html( $tile['v'] ?? '' ); ?></div>
				<div class="l"><?php echo esc_html( $tile['l'] ?? '' ); ?></div>
				<?php if ( ! empty( $tile['desc'] ) ) : ?>
					<p class="desc"><?php echo esc_html( $tile['desc'] ); ?></p>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
	<?php
	$note = nb_meta( $post_id, 'outcomes_note' );
	if ( ! $note && ! empty( $data['note'] ) ) {
		$note = $data['note'];
	}
	if ( $note ) :
		?>
		<div class="outcomes-note"><?php echo esc_html( $note ); ?></div>
	<?php endif; ?>
<?php endif; ?>
