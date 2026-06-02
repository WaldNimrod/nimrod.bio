<?php
defined( 'ABSPATH' ) || exit;

get_header();
?>
<article class="t8 t8-about">
	<?php get_template_part( 'template-parts/t8-about-hero' ); ?>

	<section class="t8-wrap t8-section">
		<?php echo nb_sec_head( 1, 'הסיפור', 'איך הגעתי לכאן.', 'לא קורות חיים. מה שמסביר את שלוש הזרועות.' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<div class="about-prose">
			<p>
				גדלתי בין שני עולמות שנראו מנוגדים. אבא שלי — הנדסת מערכות. אמא שלי — גידלה הכל. <em>לי זה אף פעם לא נראה מנוגד.</em> הוא תכנן מכונות, היא תכננה גינה. אותו דבר, חומר אחר.
			</p>
			<p>
				למדתי הנדסה, כתבתי קוד שנים — ולא ויתרתי על הסקרנות לאדמה. ב-2014 התחלתי את <a href="<?php echo esc_url( home_url( '/about/heritage/' ) ); ?>" class="entity-link entity-link--soil">הגינה של נמרוד</a> בפרדס חנה. לא סטארטאפ, לא פרויקט-צד — בית. תשע עונות. בשיא כ-4 דונם. חנות בגינה, שווקים, סלים לקהילה.
			</p>
			<blockquote>"שורש אחד" זה לא ססמה — זה זיהוי.</blockquote>
			<p>
				ב-2023 סגרתי אותה. לא משבר — <em>גלגול</em>. הבנתי שהידע שווה יותר מהיבול. כל מה שיש היום צמח מהשורש הזה: חממה הידרופונית שמספקת אוכל לשולחן ולמסעדה, ייעוץ שמבוסס על מה שעבר דרך הידיים שלי, ומערכת שאני בונה כדי שהידע לא ייעלם.
			</p>
			<p>
				אני נוטה להמציא את עצמי כל עשור או שניים. בכל פעם זה נראה כמו עזיבה — ובעצם זה אותו אדם, ממשיך לחבר את אותם דברים. <a href="<?php echo esc_url( home_url( '/project/sfa/' ) ); ?>" class="entity-link entity-link--code">SFA</a> — סוכן AI לחקלאות קטנה — הוא החוליה האחרונה לעת עתה.
			</p>
		</div>
	</section>

	<section class="t8-wrap t8-section">
		<?php echo nb_sec_head( 2, 'מסע', 'אבני דרך.', 'מה קרה ומתי — לא בלוג, רק נקודות שמסבירות איך הגענו לכאן.' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php get_template_part( 'template-parts/t8-journey-timeline' ); ?>
	</section>

	<section class="t8-wrap t8-section">
		<?php get_template_part( 'template-parts/t8-cdip-thesis' ); ?>
	</section>

	<section class="t8-wrap t8-section">
		<?php echo nb_sec_head( 4, 'עקרונות', 'איך אני עובד.', 'לא מנפסט שיווקי — שלושה דברים שאני באמת לא מתפשר עליהם.' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<div class="principle-grid">
			<?php
			get_template_part(
				'template-parts/t8-value-tile',
				null,
				array(
					'num'   => '01',
					'title' => 'Evidence-based, לא הבטחה.',
					'body'  => 'מה גדל, מה נבנה, מה עבד. <em>מה שיש לי — לא מה שתדמיין.</em>',
				)
			);
			get_template_part(
				'template-parts/t8-value-tile',
				null,
				array(
					'num'   => '02',
					'title' => 'תיעוד, לא ראוותנות.',
					'body'  => 'כל החלטה שמעניינת נכנסת לרישום. <em>בלי תיעוד אין מסקנה.</em>',
				)
			);
			get_template_part(
				'template-parts/t8-value-tile',
				null,
				array(
					'num'   => '03',
					'title' => 'הקישוריות, לא הענפים.',
					'body'  => 'הייחוד הוא בגשרים. <em>זה גם מה שאני מחפש בלקוחות ובשותפים.</em>',
				)
			);
			?>
		</div>
	</section>

	<?php /* §05 · בתקשורת — hidden: no verified press links yet (per SITE_COPY_ABOUT_v1). Section intentionally not rendered. */ ?>

	<section class="t8-section sea">
		<div class="t8-wrap sea-inner">
			<div class="eyebrow"><span class="num">קצת ים</span><span>· אישי</span></div>
			<p class="sea-quote">
				לפני כמה חודשים הוצאתי רישיון סקיפר. בהפלגה הרצינית הראשונה התקלקל המנוע, התוכנית השתבשה, והים לימד אותי את השיעור שאני כל הזמן לומד מחדש: לא איך להפליג. <b>איך לשחרר.</b> אותו דבר בגינה, בקוד, ובחממה — מערכת לא חייבת לך את התוכנית שבנית. הדבר הכי חשוב הוא לדעת לזרום איתה.
			</p>
		</div>
	</section>

	<section class="t8-section t8-final-cta">
		<div class="t8-wrap">
			<div class="head">
				<div class="eyebrow"><span class="num">צעד ראשון</span></div>
				<h2>דבר <em>איתי</em>.</h2>
				<p class="intro">שיחה ראשונה, ללא התחייבות. 30 דקות: אני מבין על מה אתה עובד, אתה רואה אם יש לי מה לתרום.</p>
				<div class="hero-act"><a class="btn btn-primary" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">לעמוד צור קשר</a></div>
			</div>
		</div>
	</section>
</article>
<?php
get_footer();
