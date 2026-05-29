<?php
/**
 * front-page.php — T7 Home (statement / ribbon)
 */
defined( 'ABSPATH' ) || exit;
get_header();

// Inline the IconPark <symbol> sprite once so <use href="#ip-*"> resolves
// across the page (hero + world cards). (P009-WP003 B1.)
require NB_THEME_DIR . '/assets/icons/icon-sprite.php';
?>

<?php
/* ── HERO POSTER (README §2) — full-bleed photo + dual scrim, content at start. ── */
?>
<section class="hero-poster">
	<div class="hp-bg">
		<img src="<?php echo esc_url( NB_THEME_URI . '/assets/img/hero-poster.jpeg' ); ?>" alt="" fetchpriority="high" decoding="async">
	</div>
	<div class="hp-scrim" aria-hidden="true"></div>
	<div class="hp-scrim hp-scrim-bottom" aria-hidden="true"></div>
	<div class="hp-content">
		<div class="t7-wrap">
			<div class="hp-inner">
				<p class="hp-eyebrow">
					<span class="hp-eyebrow-name">נמרוד ולד</span>
					<span>· אותה מערכת</span>
					<span class="hp-spark" aria-hidden="true">
						<?php require NB_THEME_DIR . '/assets/icons/spark.svg'; ?>
					</span>
				</p>
				<h1 class="poster-h1">
					<span class="pw pw-soil"><i class="pw-dot" aria-hidden="true"></i>אדמה</span>
					<span class="pw pw-know"><i class="pw-dot" aria-hidden="true"></i>ידע</span>
					<span class="pw pw-code"><i class="pw-dot" aria-hidden="true"></i>דיגיטל</span>
				</h1>
				<p class="hp-tagline">שלוש זרועות, שורש אחד. הייחוד הוא בחיבורים — שלושה גשרים שמנצחים את האנטרופיה.</p>
				<div class="hero-foot">
					<div class="hp-kicker">
						<span class="kc"><b>4</b> חממות · ייעוץ</span>
						<span class="kc"><b>1</b> חקלאות · קומון</span>
						<span class="kc"><b>3×</b> גשרים</span>
					</div>
					<div class="hp-cta">
						<a class="btn btn-primary hp-btn-primary" href="#worlds">העולמות</a>
						<a class="btn btn-ghost hp-btn-ghost" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">צור קשר</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<?php
/* ── WORLDS (README §3) — negentropy backdrop + intro, then 3 world cards. ── */
$nb_worlds = array(
	'soil' => array(
		'idx'     => '01',
		'img'     => 'world-soil.jpg',
		'basket'  => 'basket-soil.png',
		'glyph'   => 'ip-carrot',
		'title'   => 'אדמה',
		'tagline' => 'איפה שהאדמה פוגשת ידיים.',
		'items'   => array(
			array( 'ip-carrot', 'תוצרת מקצועית · מסעדות' ),
			array( 'ip-tree', 'BCS · שירותי שטח' ),
			array( 'ip-greenhouse', 'חממה הידרופונית' ),
		),
	),
	'know' => array(
		'idx'     => '02',
		'img'     => 'world-know.jpg',
		'basket'  => 'basket-know.png',
		'glyph'   => 'ip-chef',
		'title'   => 'ייעוץ והוראה',
		'tagline' => 'איפה שהניסיון הופך לכלי.',
		'items'   => array(
			array( 'ip-seedling', 'ייעוץ · הידרופוניקה' ),
			array( 'ip-leaf', 'ייעוץ · אגרו ו-market garden' ),
			array( 'ip-chef', 'הוראה מקצועית' ),
		),
	),
	'code' => array(
		'idx'     => '03',
		'img'     => 'world-code.jpg',
		'basket'  => 'basket-code.png',
		'glyph'   => 'ip-leaf',
		'title'   => 'דיגיטל',
		'tagline' => 'איפה שהידע הופך למערכת חיה.',
		'items'   => array(
			array( 'ip-shop', 'AOS · SFA · קהילתי' ),
			array( 'ip-measure', 'tiktrack' ),
			array( 'ip-greenhouse', 'קואופרטיב חממות' ),
		),
	),
);
?>
<section id="worlds" class="t7-section t7-worlds" aria-labelledby="t7-worlds-title">
	<div class="t7-wrap">
		<div class="worlds-intro">
			<div class="wi-graphic" aria-hidden="true">
				<?php require NB_THEME_DIR . '/assets/icons/negentropy.svg'; ?>
			</div>
			<div class="wi-col">
				<p class="wi-eyebrow"><span class="num">01</span><span>העולמות</span></p>
				<h2 id="t7-worlds-title" class="wi-title">שלוש זרועות · <span class="under">שורש אחד</span></h2>
				<p class="wi-lede">כל זרוע פעילה בפועל. הקישוריות ביניהן היא הייחוד.</p>
				<p class="neg-cap"><b>נֶגֶנְטְרוֹפְּיָה</b> · פיזור שהופך לקישוריות — שלושה עולמות, סל אחד</p>
			</div>
		</div>
		<div class="worlds-grid">
			<?php foreach ( $nb_worlds as $w => $wd ) : ?>
				<a href="<?php echo esc_url( home_url( "/world/$w/" ) ); ?>" class="world-card <?php echo esc_attr( $w ); ?>">
					<div class="wcard-media">
						<img src="<?php echo esc_url( NB_THEME_URI . '/assets/img/' . $wd['img'] ); ?>" alt="" loading="lazy" decoding="async">
						<span class="nb-emblem <?php echo esc_attr( $w ); ?>">
							<img src="<?php echo esc_url( NB_THEME_URI . '/assets/img/' . $wd['basket'] ); ?>" alt="" loading="lazy" decoding="async">
							<svg class="em-ic ip" aria-hidden="true"><use href="#<?php echo esc_attr( $wd['glyph'] ); ?>"/></svg>
						</span>
					</div>
					<div class="wcard-body">
						<span class="num"><?php echo esc_html( $wd['idx'] . ' · ' . $w ); ?></span>
						<h3 class="wcard-title"><?php echo esc_html( $wd['title'] ); ?></h3>
						<p class="wcard-tagline"><?php echo esc_html( $wd['tagline'] ); ?></p>
						<ul class="wcard-list">
							<?php foreach ( $wd['items'] as $it ) : ?>
								<li><svg class="ip li-ic" aria-hidden="true"><use href="#<?php echo esc_attr( $it[0] ); ?>"/></svg><?php echo esc_html( $it[1] ); ?></li>
							<?php endforeach; ?>
						</ul>
						<?php $count = nb_query_by_world( 'service', $w, -1 )->found_posts; ?>
						<span class="wcard-more"><?php echo (int) $count; ?> פעילויות</span>
					</div>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php
/* ── SYSTEMS (README §4) — SFA + tiktrack as full-width product rows. ── */
?>
<section class="t7-section t7-systems">
	<div class="t7-wrap">
		<header class="t7-sec-head">
			<p class="t7-eyebrow"><span class="num">02</span><span>· מערכות · תוכנה</span></p>
			<h2 class="t7-sec-title">שתי מערכות — <span class="under">כל אחת מוצר</span></h2>
			<p class="t7-sec-lede">לא תחת ״פרויקטים״: כל מערכת עומדת בפני עצמה — נבנית מהשטח וחוזרת אליו.</p>
		</header>
		<div class="systems-row">
			<a class="sys-card code" href="<?php echo esc_url( 'https://sfa.nimrod.bio/' ); ?>" target="_blank" rel="noopener">
				<span class="spine" aria-hidden="true"></span>
				<div class="media img-ph clean sys-shot">
					<img src="<?php echo esc_url( NB_THEME_URI . '/assets/img/cand-e.jpeg' ); ?>" alt="" loading="lazy" decoding="async">
					<span class="shot-tag">SFA · ממשק · screenshot pending</span>
				</div>
				<div class="body">
					<span class="syslabel"><svg class="ip" aria-hidden="true"><use href="#ip-shop"/></svg>מערכת · SFA · AOS</span>
					<h3>SFA — סוכן שטח חקלאי</h3>
					<p>מערכת חינמית, קהילתית, לסוכני שטח. נבנתה מהשטח — חוזרת לשטח. ליבת עולם הדיגיטל.</p>
					<div class="row"><?php echo nb_stage_stamp( 'seeking-partners' ); ?><span class="action">כנס למערכת</span></div>
				</div>
			</a>
			<a class="sys-card know" href="<?php echo esc_url( home_url( '/project/tiktrack/' ) ); ?>">
				<span class="spine" aria-hidden="true"></span>
				<div class="media sys-shot tik">
					<svg class="spark-chart" viewBox="0 0 300 120" preserveAspectRatio="none" aria-hidden="true"><polyline points="0,92 30,70 60,82 90,52 120,64 150,38 180,48 210,26 240,44 270,16 300,30" fill="none" stroke="rgba(245,243,236,.9)" stroke-width="3" stroke-linejoin="round" stroke-linecap="round"/></svg>
					<span class="shot-tag">tiktrack · יומן מסחר · screenshot pending</span>
				</div>
				<div class="body">
					<span class="syslabel"><svg class="ip" aria-hidden="true"><use href="#ip-measure"/></svg>מערכת · tiktrack</span>
					<h3>tiktrack — יומן מסחר חכם</h3>
					<p>יומן מסחר שהופך לכלי חינוכי לסוחר הריטייל. אותם עקרונות־מערכת, תחום אחר.</p>
					<div class="row"><?php echo nb_stage_stamp( 'seed' ); ?><span class="action">לפרויקט</span></div>
				</div>
			</a>
		</div>
	</div>
</section>

<?php
/* ── SERVICES (README §5) — horizontal scroll-snap carousel of entry points. ── */
$nb_services = array(
	array( 'ip-carrot', 'תוצרת ושירותי שטח', 'תוצרת מקצועית למסעדות · BCS · משתלה', 'אדמה', 'soil' ),
	array( 'ip-seedling', 'ייעוץ הידרופוני ואגרו', 'תכנון, הקמה וליווי · market garden', 'ייעוץ', 'know' ),
	array( 'ip-chef', 'הוראה וקורסים', 'מורה מקצועי · ליווי וסדנאות', 'הוראה', 'know' ),
	array( 'ip-shop', 'דיגיטל · SFA ופיתוח', 'מערכות, ממשקים וייעוץ דיגיטלי', 'דיגיטל', 'code' ),
);
$nb_service_imgs = array( 'greenhouse-1.jpg', 'farm-b.jpg', 'greenhouse-2.jpg', 'cand-d.jpeg' );
?>
<section class="t7-section t7-services services-end">
	<div class="t7-wrap">
		<header class="t7-sec-head">
			<p class="t7-eyebrow"><span class="num">03</span><span>· מה אני עושה</span></p>
			<h2 class="t7-sec-title">כל מה שאני עושה — <span class="under">נקודת כניסה אחת</span></h2>
			<p class="t7-sec-lede">ארבע זרועות פעילות. בחרו מאיפה להתחיל.</p>
		</header>
		<div class="products-grid carousel">
			<?php foreach ( $nb_services as $i => $svc ) : ?>
				<a class="product-card" href="<?php echo esc_url( home_url( '/world/' . $svc[4] . '/' ) ); ?>">
					<div class="pimg img-ph clean">
						<img src="<?php echo esc_url( NB_THEME_URI . '/assets/img/' . $nb_service_imgs[ $i ] ); ?>" alt="" loading="lazy" decoding="async">
					</div>
					<div class="pbody">
						<span class="svc-ic"><svg class="ip" aria-hidden="true"><use href="#<?php echo esc_attr( $svc[0] ); ?>"/></svg></span>
						<h3><?php echo esc_html( $svc[1] ); ?></h3>
						<p class="pmeta"><?php echo esc_html( $svc[2] ); ?></p>
						<span class="add"><?php echo esc_html( $svc[3] ); ?> ←</span>
					</div>
				</a>
			<?php endforeach; ?>
		</div>
		<div class="proj-browse">
			<button class="pb-arrow" data-dir="1" aria-label="הקודם" type="button">→</button>
			<button class="pb-arrow" data-dir="-1" aria-label="הבא" type="button">←</button>
			<a class="proj-more" href="<?php echo esc_url( home_url( '/services/' ) ); ?>">כל השירותים ←</a>
		</div>
	</div>
</section>

<?php
/* ── BRIDGES (README §6) — full-bleed wash band, 3 dual-world cards. ── */
$nb_bridges = array(
	array(
		'a'     => 'soil',
		'b'     => 'know',
		'svg'   => 'bridge-soil-know.svg',
		'seal'  => 'w-soil-deep',
		'img'   => 'greenhouse-2.jpg',
		'label' => 'אדמה × ידע',
		'title' => 'אין ייעוץ שלא נוסה בבוץ',
		'lede'  => 'מה שאני מגדל בפועל הוא הבסיס לייעוץ ולהוראה. הדוגמאות מקושרות ישירות לשירותי הייעוץ.',
		'more'  => 'מקור הידע',
	),
	array(
		'a'     => 'know',
		'b'     => 'code',
		'svg'   => 'bridge-know-code.svg',
		'seal'  => 'w-know-deep',
		'img'   => 'wide-field-1.jpeg',
		'label' => 'ידע × דיגיטל',
		'title' => 'הידע הופך למערכת',
		'lede'  => 'מה שצברתי הופך ל־SFA ולכלים דיגיטליים — ולא נשאר רק אצלי. הייעוץ מקודד לסוכן בשטח.',
		'more'  => 'SFA · ייעוץ דיגיטלי',
	),
	array(
		'a'     => 'soil',
		'b'     => 'code',
		'svg'   => 'bridge-soil-code.svg',
		'seal'  => 'w-code-deep',
		'img'   => 'landscape.jpg',
		'label' => 'אדמה × דיגיטל',
		'title' => 'החווה היא מקור־דאטה',
		'lede'  => 'החווה מזינה את tiktrack ואת SFA בנתונים אמיתיים — לא הנחת־יסוד מופשטת. השטח מאמת את הקוד.',
		'more'  => 'SFA · קואופרטיב',
	),
);
?>
<section class="t7-section t7-bridges bridges-band">
	<div class="t7-wrap">
		<header class="t7-sec-head">
			<p class="t7-eyebrow"><span class="num">04</span><span>גשרים · seams</span></p>
			<h2 class="t7-sec-title">הייחוד הוא <em>בקישוריות</em>.</h2>
			<p class="t7-sec-lede">לא ענף נוסף — תפר. שני צבעי־עולם פוגשים בכרטיס אחד. הגשרים הם התוכן המרכזי, לא תת־טקסט.</p>
		</header>
		<div class="bridges-grid">
			<?php foreach ( $nb_bridges as $br ) : ?>
				<a class="bridge-card" href="#" style="--bridge-a:var(--w-<?php echo esc_attr( $br['a'] ); ?>);--bridge-b:var(--w-<?php echo esc_attr( $br['b'] ); ?>)">
					<span class="spine" aria-hidden="true"></span>
					<div class="media img-ph clean">
						<img src="<?php echo esc_url( NB_THEME_URI . '/assets/img/' . $br['img'] ); ?>" alt="" loading="lazy" decoding="async">
						<span class="seal" style="color:var(--<?php echo esc_attr( $br['seal'] ); ?>)" aria-hidden="true">
							<?php require NB_THEME_DIR . '/assets/icons/' . $br['svg']; ?>
						</span>
					</div>
					<div class="body">
						<span class="conn"><span class="d"><i style="background:var(--w-<?php echo esc_attr( $br['a'] ); ?>)"></i><i style="background:var(--w-<?php echo esc_attr( $br['b'] ); ?>)"></i></span><b><?php echo esc_html( $br['label'] ); ?></b></span>
						<h3><?php echo esc_html( $br['title'] ); ?></h3>
						<p class="lede"><?php echo esc_html( $br['lede'] ); ?></p>
						<span class="more"><?php echo esc_html( $br['more'] ); ?></span>
					</div>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php
/* ── UNLESS LOCKUP (README §7) — dark band, huge "Unless." + Hebrew gloss. ── */
?>
<aside class="unless-lockup" role="complementary">
	<div class="t7-wrap">
		<div class="inner">
			<p class="word">Unless<span class="pd">.</span></p>
			<div class="gloss">
				<p class="he"><span class="ul-spark" aria-hidden="true"><?php require NB_THEME_DIR . '/assets/icons/spark.svg'; ?></span>העולם נוטה לאי־סדר — <em>אלא אם כן</em> מישהו אכפת לו ממש מאוד.</p>
				<p class="src">Dr. Seuss · The Lorax · התזה של האתר</p>
			</div>
		</div>
	</div>
</aside>

<section class="t7-section t7-projects">
	<div class="t7-wrap">
		<?php echo nb_sec_head( 2, 'פרויקטים', 'בנו · מבנים · מחפשים', 'פרויקטים שמראים את הקישוריות בפעולה.' ); ?>
		<div class="projects-grid">
			<?php
			$featured = new WP_Query(
				array(
					'post_type'      => 'project',
					'posts_per_page' => 3,
					'post_status'    => 'publish',
					'meta_query'     => array(
						'relation' => 'AND',
						array(
							'key'     => '_nb_stage',
							'value'   => array( 'live', 'seeking-partners' ),
							'compare' => 'IN',
						),
						array(
							'key'   => '_nb_scope',
							'value' => 'own-venture',
						),
					),
					'orderby'        => 'date',
					'order'          => 'DESC',
				)
			);
			while ( $featured->have_posts() ) :
				$featured->the_post();
				$stage = nb_meta( get_the_ID(), 'stage' );
				?>
				<a class="proj-card" href="<?php the_permalink(); ?>">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'large' ); ?>
					<?php endif; ?>
					<?php echo nb_stage_stamp( $stage ); ?>
					<h3><?php the_title(); ?></h3>
					<p><?php echo esc_html( nb_meta( get_the_ID(), 'summary' ) ); ?></p>
				</a>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		</div>
	</div>
</section>

<aside class="unless-ribbon" role="complementary">
	<div class="t7-wrap">
		<p>העולם הוא כזה — <em>אלא אם כן</em>.</p>
	</div>
</aside>

<section class="t7-section t7-posts">
	<div class="t7-wrap">
		<?php echo nb_sec_head( 3, 'מהבלוג', 'מחשבות שיורדות לאדמה', '' ); ?>
		<div class="posts-grid posts-grid-4">
			<?php
			$recent = new WP_Query(
				array(
					'post_type'      => 'post',
					'posts_per_page' => 4,
					'post_status'    => 'publish',
					'meta_query'     => array(
						array(
							'key'   => '_nb_seed',
							'value' => 'v200-migrated',
						),
					),
					'orderby'        => 'date',
					'order'          => 'DESC',
				)
			);
			while ( $recent->have_posts() ) :
				$recent->the_post();
				?>
				<a class="post-card post-square" href="<?php the_permalink(); ?>">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'medium' ); ?>
					<?php endif; ?>
					<h4><?php the_title(); ?></h4>
				</a>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		</div>
	</div>
</section>

<section class="t7-section final-cta">
	<div class="t7-wrap">
		<h2>איך אפשר להתחיל?</h2>
		<div class="cta-paths">
			<a class="btn btn-primary" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">צור קשר</a>
			<a class="btn btn-secondary" href="<?php echo esc_url( home_url( '/project/sfa/' ) ); ?>">ראה פרויקט SFA</a>
				<a class="btn btn-spark" href="https://sfa.nimrod.bio/" target="_blank" rel="noopener">כנס למערכת →</a>
		</div>
	</div>
</section>

<?php
get_footer();
