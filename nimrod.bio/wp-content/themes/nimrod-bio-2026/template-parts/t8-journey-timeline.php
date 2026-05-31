<?php
defined( 'ABSPATH' ) || exit;

$events = array(
	array(
		'year'  => '1999 →',
		'title' => 'מיזו',
		'body'  => 'מולטימדיה, סטודיו, עיצוב אקולוגי. עשרים-ומשהו שנות אפיון מערכות (היום: AOS).',
		'cls'   => '',
	),
	array(
		'year'  => '2014',
		'title' => 'תחילת \'הגינה של נמרוד\'',
		'body'  => 'פרדס חנה. market garden אקולוגי — לא סטארטאפ, לא פרויקט-צד. בית.',
		'cls'   => '',
	),
	array(
		'year'  => '2014–2023',
		'title' => 'תשע עונות בשטח',
		'body'  => 'בשיא כ-4 דונם. החנות בגינה, השווקים, הסלים לקהילה. תיעוד שבועי שהפך לבסיס-ידע.',
		'cls'   => '',
	),
	array(
		'year'  => '2023',
		'title' => 'סגירה מתוכננת',
		'body'  => 'לא משבר — החלפת קנה מידה. הבנתי שהידע שווה יותר מהיבול.',
		'cls'   => 'spark',
	),
	array(
		'year'  => '2024',
		'title' => 'החממה ההידרופונית עלתה',
		'body'  => 'תשתית מקצועית במקום שדה.',
		'cls'   => '',
	),
	array(
		'year'  => '2025',
		'title' => 'תוצרת, ייעוץ, שטח',
		'body'  => 'תוצרת למסעדת המחתרת התאילנדית (עירית שומית, פאטבונג). ייעוץ לחממות. BCS שירותי שטח.',
		'cls'   => 'know',
	),
	array(
		'year'  => '2026',
		'title' => 'SFA חי',
		'body'  => 'sfa.nimrod.bio — מדד מחירים + ספר גידולים, עולה במודולים. TikTrack בפיילוט.',
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
