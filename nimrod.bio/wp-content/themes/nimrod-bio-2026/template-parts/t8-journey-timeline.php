<?php
defined( 'ABSPATH' ) || exit;

$events = array(
	array(
		'year'  => '2014',
		'title' => 'התחלת \'הגינה של נמרוד\'',
		'body'  => '1.2 דונם בפרדס חנה. market garden אקולוגי, סלים לבעלי-בית, ימי שוק. עונה ראשונה — בעיקר עגבניות, עלים, ועשבי תיבול.',
		'cls'   => '',
	),
	array(
		'year'  => '2014–2023',
		'title' => 'תשע עונות בשטח',
		'body'  => '1.2 דונם הפכו למעבדה. כל עונה מקרה — שנה ירודה אחת (בצורת + עכבר השדה), שנה שיא אחת. ~80 לקוחות קבועים. 5 מסעדות עוגן. תיעוד שבועי שיהיה הבסיס לכל מה שיבוא אחרי.',
		'cls'   => '',
	),
	array(
		'year'  => 'סתיו 2023',
		'title' => 'סגירה מתוכננת של הגינה',
		'body'  => 'לא משבר. החלפת קנה מידה. הבנתי שמה שצברתי בתשע השנים שווה יותר כתשתית-ידע מאשר כיבול. ספירת מלאי, סיום חוזים, פיזור תשתיות.',
		'cls'   => 'spark',
	),
	array(
		'year'  => 'ינואר 2024',
		'title' => 'החממה ההידרופונית עלתה',
		'body'  => '240 מ״ר. NFT. תשתית מקצועית במקום שדה. מספקת תוצרת מתועדת ל-5 מסעדות, ומשמשת מעבדה לכל הייעוץ.',
		'cls'   => '',
	),
	array(
		'year'  => '2024–2025',
		'title' => 'ייעוץ ל-4 חממות נוספות',
		'body'  => 'תכנון, אבחון, ליווי שנתי. הידע שצברתי בגינה ובחממה שלי, נכנס לחממות של אחרים — וחוזר אליי כתיעוד נוסף.',
		'cls'   => 'know',
	),
	array(
		'year'  => '2026 →',
		'title' => 'SFA · Small Farm Agents',
		'body'  => 'סוכן AI על בסיס מקרים מהשטח. חינמי. קהילתי. v0.1 בבנייה — לסוף 2026. במקביל: קואופרטיב חממות בשרון, מחפש שותפים.',
		'cls'   => 'code',
	),
);
?>
<div class="journey">
	<div class="journey-track" aria-hidden="true"></div>
	<div class="journey-list">
		<?php foreach ( $events as $event ) : ?>
			<div class="journey-event <?php echo esc_attr( $event['cls'] ); ?>">
				<div class="j-year"><?php echo esc_html( $event['year'] ); ?></div>
				<h3 class="j-title"><?php echo esc_html( $event['title'] ); ?></h3>
				<p class="j-body"><?php echo esc_html( $event['body'] ); ?></p>
			</div>
		<?php endforeach; ?>
	</div>
</div>
