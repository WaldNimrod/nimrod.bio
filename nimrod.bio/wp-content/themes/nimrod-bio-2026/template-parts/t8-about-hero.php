<?php
defined( 'ABSPATH' ) || exit;

$portrait_alt = 'נמרוד בשדה כרוב שטוף שמש מחזיק אגודת צנון';
?>
<section class="page-hero t8-about-hero">
	<div class="t8-wrap">
		<div class="about-hero">
			<div class="ah-text">
				<div class="eyebrow"><span class="num">אודות</span><span>· אישי</span></div>
				<h1>נימרוד ולד — חקלאי, <em>יועץ</em>, מקודד.</h1>
				<p class="ah-lede">
					שורש אחד, שלוש זרועות. חממה הידרופונית פעילה, ייעוץ לחוות קטנות ולפרויקטים קהילתיים, וסוכן AI לחקלאות קטנה שאני בונה.
				</p>
			</div>
			<div class="ah-portrait">
				<picture>
					<source type="image/webp" srcset="<?php echo esc_url( NB_THEME_URI . '/assets/img/about-portrait.webp' ); ?>">
					<img src="<?php echo esc_url( NB_THEME_URI . '/assets/img/about-portrait.jpg' ); ?>" alt="<?php echo esc_attr( $portrait_alt ); ?>" loading="eager" decoding="async" />
				</picture>
				<span class="ah-emblem"><img src="<?php echo esc_url( NB_THEME_URI . '/assets/img/basket-soil.png' ); ?>" alt="" width="36" height="36" /></span>
			</div>
		</div>
	</div>
</section>
