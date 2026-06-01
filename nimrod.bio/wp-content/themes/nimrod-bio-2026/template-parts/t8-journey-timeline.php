<?php
defined( 'ABSPATH' ) || exit;

// §02 journey rail — approved content from SITE_COPY_ABOUT_v1 §02.
// 'key' marks milestone rows (filled dot). Body may contain a single <b> emphasis.
$events = array(
	array(
		'year' => '1999 →',
		'body' => 'מיזו — מולטימדיה, סטודיו, עיצוב אקולוגי. עשרים-ומשהו שנות אפיון מערכות.',
		'key'  => false,
	),
	array(
		'year' => '2014',
		'body' => 'תחילת <b>"הגינה של נמרוד"</b> — פרדס חנה, market garden אקולוגי.',
		'key'  => true,
	),
	array(
		'year' => '2014–2023',
		'body' => 'תשע עונות בשטח. בשיא ~4 דונם. החנות, השווקים, הקהילה. תיעוד שבועי שהפך לבסיס-ידע.',
		'key'  => false,
	),
	array(
		'year' => '2023',
		'body' => 'סגירה מתוכננת — לא משבר, החלפת קנה מידה.',
		'key'  => false,
	),
	array(
		'year' => '2024',
		'body' => 'החממה ההידרופונית עלתה — <b>420 מ״ר</b> — תשתית מקצועית במקום שדה.',
		'key'  => true,
	),
	array(
		'year' => '2025',
		'body' => 'תוצרת למסעדת המחתרת התאילנדית. ייעוץ לחוות קטנות ולפרויקטים קהילתיים. BCS שירותי שטח.',
		'key'  => false,
	),
	array(
		'year' => '2026',
		'body' => 'SFA חי (sfa.nimrod.bio) — מדד מחירים + ספר גידולים, עולה במודולים. TikTrack בפיילוט.',
		'key'  => true,
	),
);
?>
<ol class="timeline">
	<?php foreach ( $events as $event ) : ?>
		<li class="tl-row<?php echo $event['key'] ? ' key' : ''; ?>">
			<span class="tl-dot"></span>
			<div>
				<p class="tl-year"><?php echo esc_html( $event['year'] ); ?></p>
				<p class="tl-body"><?php echo wp_kses( $event['body'], array( 'b' => array() ) ); ?></p>
			</div>
		</li>
	<?php endforeach; ?>
</ol>
