<?php
defined( 'ABSPATH' ) || exit;

$post_id = (int) ( $args['post_id'] ?? get_the_ID() );
$slug    = (string) ( $args['slug'] ?? get_post_field( 'post_name', $post_id ) );
$is_free = (bool) ( $args['is_free'] ?? false );

$final_h    = nb_meta( $post_id, 'cta_final_h' );
$final_p    = nb_meta( $post_id, 'cta_final_p' );
$cta_label  = nb_meta( $post_id, 'cta_label', 'הצעת מחיר' );
$cta_href   = nb_meta( $post_id, 'cta_whatsapp_href' );
$cta_hint   = nb_meta( $post_id, 'cta_hint' );
$ext_url    = nb_meta( $post_id, 'external_url' );
$ext_label  = nb_meta( $post_id, 'external_label', 'כנס למערכת →' );
$band_cls  = 'final-cta' . ( $is_free ? ' free' : '' );
?>
<section class="<?php echo esc_attr( $band_cls ); ?>">
	<div class="final-cta-inner">
		<div>
			<?php if ( $final_h ) : ?>
				<h2><?php echo esc_html( $final_h ); ?></h2>
			<?php endif; ?>
			<?php if ( $final_p ) : ?>
				<p><?php echo esc_html( $final_p ); ?></p>
			<?php endif; ?>
		</div>
		<div class="cta-side">
			<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="hero-cta primary<?php echo $is_free ? ' free' : ''; ?>"><?php echo esc_html( $cta_label ); ?></a>
			<?php if ( $cta_href && ! $is_free && 'produce' === $slug ) : ?>
				<a href="<?php echo esc_url( $cta_href ); ?>" class="hero-cta whatsapp on-dark" target="_blank" rel="noopener">
					<?php echo nb_whatsapp_icon_svg(); ?>
					WhatsApp · הודעה
				</a>
			<?php endif; ?>
			<?php if ( $ext_url ) : ?>
				<a href="<?php echo esc_url( $ext_url ); ?>" class="hero-cta secondary on-dark" target="_blank" rel="noopener"><?php echo esc_html( $ext_label ); ?></a>
			<?php endif; ?>
			<?php if ( $cta_hint ) : ?>
				<span class="meta"><?php echo esc_html( $cta_hint ); ?></span>
			<?php endif; ?>
		</div>
	</div>
</section>
