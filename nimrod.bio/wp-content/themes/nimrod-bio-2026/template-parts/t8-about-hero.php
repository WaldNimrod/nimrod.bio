<?php
defined( 'ABSPATH' ) || exit;

// First tile is the confirmed portrait (real image, theme asset, ~4/5 crop).
// Remaining tiles stay as clean degraded placeholders until owner supplies media.
$gallery = array(
	array( 'subject' => 'סדנת market garden', 'cap' => 'TBD · נמרוד מלמד' ),
	array( 'subject' => 'BCS · עמק חפר', 'cap' => 'TBD · עם הטרקטור' ),
	array( 'subject' => 'בחממה ההידרופונית', 'cap' => 'TBD · נמרוד בחממה' ),
	array( 'subject' => 'כותב ביומן', 'cap' => 'TBD · כתיבה' ),
);
$portrait_alt = 'נמרוד בשדה כרוב שטוף שמש מחזיק אגודת צנון';
?>
<section class="t8-about-hero">
	<div class="t8-wrap">
		<div class="about-hero-top">
			<div class="avatar">
				<img src="<?php echo esc_url( NB_THEME_URI . '/assets/img/about-portrait.jpg' ); ?>" alt="<?php echo esc_attr( $portrait_alt ); ?>" width="56" height="56" loading="eager" decoding="async" />
			</div>
			<div class="name-block">
				<span class="role">על</span>
				<span class="name">נימרוד ולד</span>
			</div>
		</div>

		<h1>נימרוד ולד — חקלאי, יועץ, מקודד.</h1>
		<p class="lede">
			<em>שורש אחד, שלוש זרועות.</em> חממה הידרופונית פעילה, ייעוץ לחממות, וסוכן AI לחקלאות קטנה שאני בונה.
		</p>

		<div class="about-gallery">
			<div class="img-ph clean" style="aspect-ratio:4/5;border-radius:var(--radius-m);">
				<picture>
					<source type="image/webp" srcset="<?php echo esc_url( NB_THEME_URI . '/assets/img/about-portrait.webp' ); ?>">
					<img src="<?php echo esc_url( NB_THEME_URI . '/assets/img/about-portrait.jpg' ); ?>" alt="<?php echo esc_attr( $portrait_alt ); ?>" loading="lazy" decoding="async" />
				</picture>
			</div>
			<?php foreach ( $gallery as $item ) : ?>
				<?php echo nb_img_ph( $item['subject'], $item['cap'], '', '4/5' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php endforeach; ?>
		</div>

		<div class="factrow">
			<div class="row"><span>בסיס</span><b>פרדס חנה</b></div>
			<div class="row"><span>פעיל מ</span><b>2014</b></div>
			<div class="row"><span>חממה פעילה</span><b>הידרופונית</b></div>
			<div class="row"><span>SFA</span><b>חי · sfa.nimrod.bio</b></div>
		</div>
	</div>
</section>
