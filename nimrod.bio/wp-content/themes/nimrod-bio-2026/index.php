<?php
defined( 'ABSPATH' ) || exit;
get_header();
?>
<section class="nb-coming-soon">
	<div class="nb-container">
		<h1>nimrod.bio · V200</h1>
		<p>האתר בבנייה. תבניות התוכן יתווספו ב-WPs הבאים.</p>
		<p>סביבה: <code><?php echo esc_html( wp_get_environment_type() ); ?></code></p>
	</div>
</section>
<?php
get_footer();
