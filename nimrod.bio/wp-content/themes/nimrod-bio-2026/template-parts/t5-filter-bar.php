<?php
defined( 'ABSPATH' ) || exit;

$world_filter  = $args['world_filter'] ?? array();
$view          = $args['view'] ?? 'flow';
$visible_count = (int) ( $args['visible_count'] ?? 0 );
$total_posts   = (int) ( $args['total_posts'] ?? 0 );
$blog_url      = home_url( '/blog/' );
?>
<div class="filter-bar">
	<div class="filter-bar-inner">
		<span class="filter-label">סנן · עולם</span>
		<?php foreach ( array_keys( nb_get_worlds() ) as $slug ) : ?>
			<button type="button" class="filter-chip <?php echo esc_attr( $slug ); ?><?php echo in_array( $slug, $world_filter, true ) ? ' is-on' : ''; ?>" data-world="<?php echo esc_attr( $slug ); ?>">
				<?php echo esc_html( nb_world_label( $slug ) ); ?>
			</button>
		<?php endforeach; ?>

		<?php if ( $world_filter ) : ?>
			<span class="filter-sep" aria-hidden="true"></span>
			<a class="filter-reset" href="<?php echo esc_url( $blog_url ); ?>">נקה הכל</a>
		<?php endif; ?>

		<span class="filter-sep" aria-hidden="true"></span>
		<span class="filter-label">תצוגה</span>
		<div class="view-toggle">
			<button type="button" class="view-toggle-btn<?php echo 'flow' === $view ? ' is-on' : ''; ?>" data-view="flow" title="זרימה עריכותית">זרימה</button>
			<button type="button" class="view-toggle-btn<?php echo 'grid' === $view ? ' is-on' : ''; ?>" data-view="grid" title="גריד אחיד">גריד</button>
		</div>

		<span class="filter-count"><b><?php echo esc_html( (string) $visible_count ); ?></b> / <?php echo esc_html( (string) $total_posts ); ?></span>
	</div>
</div>
