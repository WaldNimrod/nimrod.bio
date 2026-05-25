<?php
defined( 'ABSPATH' ) || exit;

$gallery = array(
	array( 'subject' => 'בחממה · 2025', 'cap' => 'TBD · נמרוד בחממה' ),
	array( 'subject' => 'סדנת market garden', 'cap' => 'TBD · נמרוד מלמד' ),
	array( 'subject' => 'BCS · עמק חפר', 'cap' => 'TBD · עם הטרקטור' ),
	array( 'subject' => 'במטבח · עם שף', 'cap' => 'TBD · במסעדה' ),
	array( 'subject' => 'כותב ביומן', 'cap' => 'TBD · כתיבה' ),
);
?>
<section class="t8-about-hero">
	<div class="t8-wrap">
		<div class="about-hero-top">
			<div class="avatar">
				<img src="<?php echo esc_url( NB_THEME_URI . '/assets/icons/home.svg' ); ?>" alt="נימרוד ולד" width="56" height="56" loading="eager" />
			</div>
			<div class="name-block">
				<span class="role">על</span>
				<span class="name">נימרוד ולד</span>
			</div>
		</div>

		<h1>חקלאי, יועץ, מקודד.</h1>
		<p class="lede">
			<em>שורש אחד, שלוש זרועות.</em> חממה הידרופונית פעילה, ייעוץ ל-4 חממות נוספות, וסוכן AI לחקלאות קטנה שאני בונה.
		</p>

		<div class="about-gallery">
			<?php foreach ( $gallery as $item ) : ?>
				<?php echo nb_img_ph( $item['subject'], $item['cap'], '', '4/5' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php endforeach; ?>
		</div>

		<div class="factrow">
			<div class="row"><span>בסיס</span><b>פרדס חנה</b></div>
			<div class="row"><span>פעיל מ</span><b>2014</b></div>
			<div class="row"><span>חממה פעילה</span><b>240 מ״ר · NFT</b></div>
			<div class="row"><span>SFA</span><b>בבנייה · v0.1</b></div>
		</div>
	</div>
</section>
