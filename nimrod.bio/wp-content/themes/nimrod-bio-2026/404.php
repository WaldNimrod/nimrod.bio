<?php
/**
 * 404.php — System → "השביל הזה לא מוביל" (team_35 Precision Mockup v4, System screen).
 * Three-world dot lockup + on-voice lead + pill links (home + 3 worlds + contact).
 */
defined( 'ABSPATH' ) || exit;
get_header();
?>
<section class="t8-section">
	<div class="t8-wrap">
		<div class="err-404">
			<p class="code" aria-hidden="true"><span>4</span><i class="d1"></i><span>4</span></p>
			<h1>השביל הזה לא מוביל לשום מקום.</h1>
			<p>הדף שחיפשת לא קיים — אולי זז או שינה כתובת. אפשר לחזור להתחלה, או לבחור עולם.</p>
			<div class="err-links">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>">לדף הבית</a>
				<a href="<?php echo esc_url( home_url( '/world/soil/' ) ); ?>">אדמה</a>
				<a href="<?php echo esc_url( home_url( '/world/know/' ) ); ?>">ייעוץ והוראה</a>
				<a href="<?php echo esc_url( home_url( '/world/code/' ) ); ?>">דיגיטל</a>
				<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">צור קשר</a>
			</div>
		</div>
	</div>
</section>
<?php
get_footer();
