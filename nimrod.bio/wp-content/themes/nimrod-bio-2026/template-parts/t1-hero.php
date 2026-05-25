<?php
defined( 'ABSPATH' ) || exit;
$world = $args['world'] ?? 'soil';
$copy  = nb_get_t1_hero_copy( $world );
$name  = nb_world_label( $world );
?>
<section class="t1-wrap t1-hero hero-variant-c vc-hero">
	<h1 class="vc-hero-stack">
		<span class="t1-hero-echo echo e3" aria-hidden="true"><?php echo esc_html( $name ); ?></span>
		<span class="t1-hero-echo echo e2" aria-hidden="true"><?php echo esc_html( $name ); ?></span>
		<span class="t1-hero-echo echo e1" aria-hidden="true"><?php echo esc_html( $name ); ?></span>
		<?php echo esc_html( $name ); ?>
	</h1>
	<div class="gloss">
		<span class="rule"></span>
		<span class="word"><?php echo esc_html( $copy['tagline'] ); ?></span>
		<span class="rule"></span>
	</div>
	<div class="intro">
		<div class="marker"><?php echo esc_html( $copy['marker'] ); ?></div>
		<p>
			<em><?php echo esc_html( $copy['intro_short'] ); ?></em>
			<?php echo esc_html( $copy['intro_long'] ); ?>
		</p>
	</div>
</section>
