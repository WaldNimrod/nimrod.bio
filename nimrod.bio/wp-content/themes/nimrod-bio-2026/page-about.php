<?php
defined( 'ABSPATH' ) || exit;

get_header();

$media_items = array(
	array(
		'kind'       => 'press',
		'kind_label' => 'כתבה',
		'outlet'     => 'מגזין',
		'date'       => '2025',
		'title'      => 'כתבה על המעבר מ-market garden לחממה הידרופונית',
		'href'       => '#',
		'tbc'        => true,
	),
	array(
		'kind'       => 'podcast',
		'kind_label' => 'פודקאסט',
		'outlet'     => 'פודקאסט',
		'date'       => '2024',
		'title'      => 'ראיון על תיעוד שטח ולמה זה חשוב',
		'href'       => '#',
		'tbc'        => true,
	),
	array(
		'kind'       => 'talk',
		'kind_label' => 'הרצאה',
		'outlet'     => 'אירוע',
		'date'       => '2024',
		'title'      => 'הרצאה על אותם עקרונות יסוד בשלושה שדות שונים',
		'href'       => '#',
		'tbc'        => true,
	),
	array(
		'kind'       => 'write',
		'kind_label' => 'מאמר',
		'outlet'     => 'פרסום',
		'date'       => '2023',
		'title'      => 'טור על סגירת הגינה ולמה זה לא משבר',
		'href'       => '#',
		'tbc'        => true,
	),
);
?>
<article class="t8 t8-about">
	<?php get_template_part( 'template-parts/t8-about-hero' ); ?>

	<section class="t8-wrap t8-section">
		<?php echo nb_sec_head( 1, 'הסיפור', 'איך הגעתי לכאן.', 'לא קורות חיים. מה שמסביר את שלוש הזרועות.' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<div class="story-block">
			<div class="story-inner">
				<p>
					גדלתי בין שני עולמות שנראו מנוגדים — אבא שלי הנדסת מערכות, אמא שלי גידלה הכל. <em>זה היה ברור לי מההתחלה שזה אותו דבר.</em> הוא תכנן מכונות, היא תכננה גינה — שניהם פתרו את אותה השאלה: <em>מערכת שעובדת לאורך זמן</em>. רק במילים שונות.
				</p>
				<p>
					למדתי הנדסת תוכנה. כתבתי קוד למחיה במשך 7 שנים — ולא ביטלתי את הסקרנות לאדמה. ב-2014 התחלתי את <a href="<?php echo esc_url( home_url( '/about/heritage/' ) ); ?>" class="entity-link entity-link--soil">הגינה של נמרוד</a> בפרדס חנה — לא כסטארטאפ, לא כפרויקט-צד. כעבודה. תשע עונות אקולוגיות שלימדו אותי על קצב, רוטציה, ותיעוד.
				</p>
				<div class="pullquote">"שורש אחד" זה לא ססמה — זה זיהוי.</div>
				<p>
					ב-2023 סגרתי את הגינה. לא משבר — החלפת קנה מידה. הבנתי שהידע ששווה לעולם יותר מהיבול שיוצא ממנו. <em>החממה ההידרופונית</em> שעלתה ב-2024 היא תשתית מקצועית במקום שדה — קטנה יותר, אבל מתועדת בכל דקה.
				</p>
				<p>
					היום אני עובד בשלוש זרועות. <strong>אדמה</strong> — חממה הידרופונית פעילה, תוצרת קבועה למסעדה שמוכנה לעבוד בתנאים שלנו, ושירות BCS בתשלום לחלקות קטנות. <strong>ייעוץ והוראה</strong> — תכנון חממות הידרופוניות, ייעוץ אגרו שטחי, והוראה מקצועית. <strong>דיגיטל</strong> — <a href="<?php echo esc_url( home_url( '/project/sfa/' ) ); ?>" class="entity-link entity-link--code">SFA</a>, כלי קהילתי וחינמי לחקלאות הקטנה, שניזון מתשע שנות תיעוד שטח אמיתי.
				</p>
				<p>
					הקישוריות ביניהן היא הסיפור — לא הענפים בנפרד. <em>זה גם מה שאני מחפש בלקוחות ובשותפים.</em>
				</p>
			</div>
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
		<div class="values">
			<?php
			get_template_part(
				'template-parts/t8-value-tile',
				null,
				array(
					'num'   => '01',
					'title' => 'Evidence-based, לא הבטחה.',
					'body'  => 'מה גדל. מה נבנה. מה עבד. <em>אני מציג את מה שיש לי, לא את מה שאני מציע לך לדמיין.</em>',
				)
			);
			get_template_part(
				'template-parts/t8-value-tile',
				null,
				array(
					'num'   => '02',
					'title' => 'תיעוד שבועי, לא ראוותני.',
					'body'  => 'כל החלטה שמעניינת בחממה נכנסת לרישום. גם אם זה משעמם. <em>בלי תיעוד אין מסקנה.</em>',
				)
			);
			get_template_part(
				'template-parts/t8-value-tile',
				null,
				array(
					'num'   => '03',
					'title' => 'הקישוריות, לא הענפים.',
					'body'  => 'הייחוד שלי לא בענף בודד. הוא בגשרים בין הענפים. <em>זה גם מה שאני מחפש אצל לקוחות ושותפים.</em>',
				)
			);
			?>
		</div>
	</section>

	<section class="t8-wrap t8-section">
		<?php echo nb_sec_head( 5, 'במדיה', 'כתבות, פודקאסטים, הרצאות.', 'ראיונות וטקסטים שכתבו עליי או שכתבתי בכלי-מדיה אחרים. כולם דורשים אימות + לינקים אמיתיים.' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<div class="media-grid">
			<?php foreach ( $media_items as $item ) : ?>
				<?php get_template_part( 'template-parts/t8-media-item', null, $item ); ?>
			<?php endforeach; ?>
		</div>
		<p class="media-note">רשימה ראשונית — תתעדכן בלינקים אמיתיים בהמשך.</p>
	</section>

	<section class="t8-wrap t8-section contact-teaser">
		<div class="contact-teaser-inner">
			<div>
				<h3>דבר איתי.</h3>
				<p>שיחה ראשונה — ללא התחייבות. <em>30 דקות, אני מבין על מה אתה עובד, אתה רואה אם יש לי משהו לתרום.</em></p>
			</div>
			<a class="contact-teaser-btn" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">לעמוד צור קשר ←</a>
		</div>
	</section>
</article>
<?php
get_footer();
