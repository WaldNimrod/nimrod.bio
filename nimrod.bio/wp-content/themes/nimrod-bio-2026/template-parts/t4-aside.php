<?php
defined( 'ABSPATH' ) || exit;

$args      = wp_parse_args(
	$args ?? array(),
	array(
		'toc'       => array(),
		'permalink' => '',
		'post_id'   => 0,
		'worlds'    => array(),
	)
);
$toc       = $args['toc'];
$permalink = $args['permalink'];
$post_id   = (int) $args['post_id'];
$worlds    = $args['worlds'];

$related_entities = array();
foreach ( $worlds as $world ) {
	$anchor = nb_get_anchor_service_for_world( $world );
	if ( $anchor ) {
		$related_entities[] = array(
			'kind'  => 'פעילות · anchor',
			'title' => get_the_title( $anchor ),
			'href'  => get_permalink( $anchor ),
		);
	}
}
?>
<!-- a11y P009-WP006: <aside>→<div> (nested complementary landmark, not top-level) -->
<div class="post-aside">
	<?php if ( $toc ) : ?>
		<div class="aside-block">
			<h3>תוכן עניינים</h3>
			<ul class="toc-list">
				<?php foreach ( $toc as $item ) : ?>
					<li><a href="#<?php echo esc_attr( $item['id'] ); ?>"><?php echo esc_html( $item['label'] ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>

	<div class="aside-block">
		<h3>שתף</h3>
		<?php get_template_part( 'template-parts/t4-share', null, array( 'permalink' => $permalink ) ); ?>
	</div>

	<?php if ( $related_entities ) : ?>
		<div class="aside-block">
			<h3>קשור · באתר</h3>
			<div class="related-entities">
				<?php foreach ( $related_entities as $entity ) : ?>
					<a href="<?php echo esc_url( $entity['href'] ); ?>" class="related-entity">
						<span class="re-kind"><?php echo esc_html( $entity['kind'] ); ?></span>
						<span class="re-title"><?php echo esc_html( $entity['title'] ); ?></span>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>
</div><!-- a11y P009-WP006: was </aside> -->
