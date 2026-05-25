<?php
defined( 'ABSPATH' ) || exit;
get_header();
?>
<section class="nb-404">
	<div class="nb-container">
		<h1>404 - לא נמצא</h1>
		<p>הדף שחיפשת לא קיים. אולי <a href="<?php echo esc_url( home_url( '/' ) ); ?>">לחזור לדף הבית</a>?</p>
	</div>
</section>
<?php
get_footer();
