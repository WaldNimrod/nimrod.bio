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
							<svg class="ip" aria-hidden="true"><use href="#<?php echo esc_attr( $wd['glyph'] ); ?>"/></svg>
							<span class="em-ic"><svg class="ip" aria-hidden="true"><use href="#<?php echo esc_attr( $wd['glyph'] ); ?>"/></svg></span>
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
