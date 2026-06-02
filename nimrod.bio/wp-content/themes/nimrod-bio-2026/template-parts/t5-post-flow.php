<?php
defined( 'ABSPATH' ) || exit;

$post    = $args['post'] ?? get_post();
$post_id = $post->ID;
$style   = nb_get_post_flow_style( $post_id );
$worlds  = nb_get_post_world_slugs( $post_id );
$tags    = get_the_tags( $post_id ) ?: array();
$url     = get_permalink( $post );
$read    = nb_post_read_label( $post_id );
$date    = get_the_date( 'j.n.y', $post );
$title   = get_the_title( $post );
$excerpt = get_the_excerpt( $post );

switch ( $style ) {
	case 'lead':
		?>
<a href="<?php echo esc_url( $url ); ?>" class="flow-item flow-lead post-flow-lead">
	<div class="flow-img"><?php echo nb_post_featured_image( $post_id, '', 'auto' ); ?></div>
	<div class="flow-body">
		<div class="flow-eyebrow">
			<span class="lead-badge">★ נבחר העונה</span>
			<span>·</span>
			<time><?php echo esc_html( $date ); ?></time>
			<span>·</span>
			<span><?php echo esc_html( $read ); ?></span>
		</div>
		<h2 class="flow-title-lead"><?php echo esc_html( $title ); ?></h2>
		<p class="flow-excerpt-lead"><?php echo esc_html( $excerpt ); ?></p>
		<div class="flow-tags">
			<?php foreach ( $worlds as $world ) : ?>
				<?php echo nb_world_chip( $world, true ); ?>
			<?php endforeach; ?>
		</div>
		<span class="flow-read-more">לקריאה ←</span>
	</div>
</a>
		<?php
		break;

	case 'wide':
		?>
<a href="<?php echo esc_url( $url ); ?>" class="flow-item flow-wide post-flow-wide">
	<div class="flow-img"><?php echo nb_post_featured_image( $post_id, '', 'auto' ); ?></div>
	<div class="flow-body">
		<div class="flow-eyebrow">
			<time><?php echo esc_html( $date ); ?></time>
			<span>·</span>
			<span><?php echo esc_html( $read ); ?></span>
		</div>
		<h3 class="flow-title-feature"><?php echo esc_html( $title ); ?></h3>
		<p class="flow-excerpt"><?php echo esc_html( $excerpt ); ?></p>
		<div class="flow-tags">
			<?php foreach ( $worlds as $world ) : ?>
				<?php echo nb_world_chip( $world, true ); ?>
			<?php endforeach; ?>
		</div>
	</div>
</a>
		<?php
		break;

	case 'tall':
		?>
<a href="<?php echo esc_url( $url ); ?>" class="flow-item flow-tall post-flow-tall">
	<?php echo nb_post_featured_image( $post_id, '', '3/4' ); ?>
	<div class="flow-body">
		<div class="flow-eyebrow">
			<time><?php echo esc_html( $date ); ?></time>
			<span>·</span>
			<span><?php echo esc_html( $read ); ?></span>
		</div>
		<h3 class="flow-title-feature"><?php echo esc_html( $title ); ?></h3>
		<p class="flow-excerpt"><?php echo esc_html( $excerpt ); ?></p>
		<div class="flow-tags">
			<?php foreach ( $worlds as $world ) : ?>
				<?php echo nb_world_chip( $world, true ); ?>
			<?php endforeach; ?>
		</div>
	</div>
</a>
		<?php
		break;

	case 'typo':
		?>
<a href="<?php echo esc_url( $url ); ?>" class="flow-item flow-typo post-flow-typo">
	<div class="flow-eyebrow">
		<time><?php echo esc_html( $date ); ?></time>
		<span>·</span>
		<span><?php echo esc_html( $read ); ?></span>
	</div>
	<h3 class="flow-title-typo"><?php echo esc_html( $title ); ?></h3>
	<p class="flow-excerpt-typo"><?php echo esc_html( $excerpt ); ?></p>
	<div class="flow-tags">
		<?php foreach ( $worlds as $world ) : ?>
			<?php echo nb_world_chip( $world, true ); ?>
		<?php endforeach; ?>
	</div>
	<span class="flow-read-more typo">להמשך ←</span>
</a>
		<?php
		break;

	case 'quote':
		?>
<a href="<?php echo esc_url( $url ); ?>" class="flow-item flow-quote post-flow-quote">
	<div class="flow-quote-mark">「</div>
	<p class="flow-quote-text"><?php echo esc_html( $excerpt ); ?></p>
	<div class="flow-quote-meta">
		<span class="flow-title-quote"><?php echo esc_html( $title ); ?></span>
		<span class="flow-eyebrow">
			<time><?php echo esc_html( $date ); ?></time>
			<span>·</span>
			<span><?php echo esc_html( $read ); ?></span>
		</span>
	</div>
</a>
		<?php
		break;

	case 'brief':
		?>
<a href="<?php echo esc_url( $url ); ?>" class="flow-item flow-brief post-flow-brief">
	<div class="flow-eyebrow">
		<time><?php echo esc_html( $date ); ?></time>
		<span>·</span>
		<span><?php echo esc_html( $read ); ?></span>
	</div>
	<h3 class="flow-title-brief"><?php echo esc_html( $title ); ?></h3><!-- a11y P009-WP006: h4→h3 (class-styled, visual unchanged) -->
	<p class="flow-excerpt-brief"><?php echo esc_html( $excerpt ); ?></p>
	<div class="flow-tags">
		<?php foreach ( $worlds as $world ) : ?>
			<?php echo nb_world_chip( $world, true ); ?>
		<?php endforeach; ?>
	</div>
</a>
		<?php
		break;

	default:
		?>
<a href="<?php echo esc_url( $url ); ?>" class="flow-item flow-feature post-flow-feature">
	<?php echo nb_post_featured_image( $post_id, '', '16/10' ); ?>
	<div class="flow-body">
		<div class="flow-eyebrow">
			<time><?php echo esc_html( $date ); ?></time>
			<span>·</span>
			<span><?php echo esc_html( $read ); ?></span>
		</div>
		<h3 class="flow-title-feature"><?php echo esc_html( $title ); ?></h3>
		<p class="flow-excerpt"><?php echo esc_html( $excerpt ); ?></p>
		<div class="flow-tags">
			<?php foreach ( $worlds as $world ) : ?>
				<?php echo nb_world_chip( $world, true ); ?>
			<?php endforeach; ?>
			<?php foreach ( $tags as $tag ) : ?>
				<span class="ftag">#<?php echo esc_html( $tag->name ); ?></span>
			<?php endforeach; ?>
		</div>
	</div>
</a>
		<?php
		break;
}
