<?php
defined( 'ABSPATH' ) || exit;

$post_id = (int) ( $args['post_id'] ?? get_the_ID() );
$gallery = nb_meta_array( $post_id, 'gallery' );
$sizes   = array( 'g-tall', 'g-square', 'g-square', 'g-wide', 'g-square', 'g-square' );
?>
<section class="t3-wrap t3-section">
	<?php echo nb_sec_head( 4, 'גלריה', 'תמונות מהשטח.', 'תמונות יוחלפו בתמונות אמיתיות של נמרוד.' ); ?>
	<div class="gallery">
		<?php if ( $gallery ) : ?>
			<?php foreach ( $gallery as $i => $attachment_id ) : ?>
				<?php
				$size_cls = $sizes[ $i % count( $sizes ) ];
				echo wp_get_attachment_image( (int) $attachment_id, 'large', false, array( 'class' => 'img-ph ' . $size_cls ) );
				?>
			<?php endforeach; ?>
		<?php else : ?>
			<?php
			$placeholders = array(
				array( 'subject' => 'תמונה מהשטח', 'cap' => 'TBD · גaleria', 'class' => 'g-tall' ),
				array( 'subject' => 'פרט', 'cap' => 'TBD · detail', 'class' => 'g-square' ),
				array( 'subject' => 'עבודה', 'cap' => 'TBD · work', 'class' => 'g-square' ),
			);
			foreach ( $placeholders as $ph ) {
				echo nb_img_placeholder( $ph['cap'], $ph['subject'], 'auto', trim( 'img-ph ' . $ph['class'] ) );
			}
			?>
		<?php endif; ?>
	</div>
</section>
