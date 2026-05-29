<?php
defined( 'ABSPATH' ) || exit;

$post_id   = (int) ( $args['post_id'] ?? get_the_ID() );
$slug      = (string) ( $args['slug'] ?? get_post_field( 'post_name', $post_id ) );
$worlds    = (array) ( $args['worlds'] ?? array() );
$is_bridge = (bool) ( $args['is_bridge'] ?? false );
$is_free   = (bool) ( $args['is_free'] ?? false );

$tagline   = nb_meta( $post_id, 'tagline' );
$lede      = nb_meta( $post_id, 'lede' );
$cta_label = nb_meta( $post_id, 'cta_label', 'הצעת מחיר' );
$cta_href  = nb_meta( $post_id, 'cta_whatsapp_href' );
$cta_hint  = nb_meta( $post_id, 'cta_hint' );
$facts     = nb_json_meta( $post_id, 'hero_facts' );
$type      = nb_meta( $post_id, 'service_type', 'service' );

$hero_cls = $is_bridge ? 't2-hero bridge-hero' : 't2-hero single-hero world-' . esc_attr( $worlds[0] ?? 'soil' );
$title_cls = 'hero-title';
if ( $is_bridge && isset( $worlds[0], $worlds[1] ) ) {
	$title_cls .= ' bridge-' . esc_attr( $worlds[0] ) . '-' . esc_attr( $worlds[1] );
} elseif ( ! $is_bridge ) {
	$title_cls .= ' ' . esc_attr( $worlds[0] ?? 'soil' );
}
$cta_primary_cls = 'hero-cta primary' . ( $is_free ? ' free' : '' );
?>
<section class="<?php echo esc_attr( $hero_cls ); ?>"<?php echo $is_bridge ? nb_bridge_style_attr( $worlds ) : ''; ?>>
	<?php if ( $is_bridge ) : ?>
		<div class="bridge-stripe seam"></div>
	<?php endif; ?>
	<div class="t2-wrap">
		<div class="hero-grid">
			<div>
				<div class="hero-tags">
					<?php if ( $is_bridge ) : ?>
						<?php echo nb_world_chip( $worlds[0] ?? 'soil' ); ?>
						<span class="conn">×</span>
						<?php echo nb_world_chip( $worlds[1] ?? 'know' ); ?>
					<?php else : ?>
						<?php echo nb_world_chip( $worlds[0] ?? 'soil' ); ?>
					<?php endif; ?>
					<span class="hero-type"><?php echo esc_html( $type ); ?></span>
					<?php if ( $is_free ) : ?>
						<span class="free-badge">חינם · קהילתי</span>
					<?php endif; ?>
				</div>
				<h1 class="<?php echo esc_attr( $title_cls ); ?>"><?php the_title(); ?></h1>
				<?php if ( $tagline ) : ?>
					<p class="hero-tagline"><?php echo esc_html( $tagline ); ?></p>
				<?php endif; ?>
				<?php if ( $lede ) : ?>
					<p class="hero-lede"><?php echo esc_html( $lede ); ?></p>
				<?php endif; ?>
				<div class="hero-cta-row">
					<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="<?php echo esc_attr( $cta_primary_cls ); ?>"><?php echo esc_html( $cta_label ); ?></a>
					<?php if ( $cta_href && ! $is_free ) : ?>
						<a href="<?php echo esc_url( $cta_href ); ?>" class="hero-cta whatsapp" target="_blank" rel="noopener">
							<?php echo nb_whatsapp_icon_svg(); ?>
							WhatsApp · הודעה
						</a>
					<?php endif; ?>
					<?php if ( $cta_hint ) : ?>
						<span class="hero-cta-hint"><?php echo esc_html( $cta_hint ); ?></span>
					<?php endif; ?>
				</div>
			</div>
			<?php if ( has_post_thumbnail( $post_id ) ) : ?>
				<div class="img-ph clean hero-image">
					<?php echo get_the_post_thumbnail( $post_id, 'large', array( 'loading' => 'eager', 'fetchpriority' => 'high', 'sizes' => '(max-width: 640px) 800px, (max-width: 900px) 1280px, 1920px', 'alt' => esc_attr( get_the_title( $post_id ) ) ) ); ?>
				</div>
			<?php else : ?>
				<?php
				$img_cap = 'produce' === $slug ? 'TBD · ירקות בחממה' : 'TBD · החממה בעבודה';
				echo nb_img_placeholder( $img_cap, get_the_title( $post_id ), '4/5', 'hero-image' );
				?>
			<?php endif; ?>
		</div>

		<?php if ( $facts ) : ?>
			<div class="hero-facts">
				<?php foreach ( $facts as $fact ) : ?>
					<div>
						<div class="k"><?php echo esc_html( $fact['k'] ?? '' ); ?></div>
						<div class="v"><?php echo esc_html( $fact['v'] ?? '' ); ?></div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
