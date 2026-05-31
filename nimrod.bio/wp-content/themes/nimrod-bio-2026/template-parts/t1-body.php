<?php
defined( 'ABSPATH' ) || exit;
$world  = $args['world'] ?? 'soil';
$anchor = nb_get_anchor_service_for_world( $world );

// SITE_COPY_WORLDS_v1: the דיגיטל (code) anchor is SFA — the live community
// core. SFA is a project (not a service CPT), so resolve it directly when no
// service anchor is flagged for this world. This resolves the prior unset anchor.
if ( ! $anchor && 'code' === $world ) {
	$anchor = nb_get_project_by_slug( 'sfa' );
}
$anchor_id = $anchor ? (int) $anchor->ID : 0;

$lat_services = array();
$svcs_q       = nb_query_by_world( 'service', $world, 8 );
if ( $svcs_q->have_posts() ) {
	while ( $svcs_q->have_posts() ) {
		$svcs_q->the_post();
		if ( get_the_ID() === $anchor_id ) {
			continue;
		}
		$lat_services[] = get_post();
		if ( count( $lat_services ) >= 4 ) {
			break;
		}
	}
	wp_reset_postdata();
}

$bridges = nb_get_bridges_for_world( $world );
foreach ( $bridges as $i => $bridge ) {
	// Only backfill lede from the linked service when the bridge did not ship
	// its own copy (SITE_COPY_WORLDS_v1 bridge ledes are authoritative).
	if ( empty( $bridge['lede'] ) ) {
		$svc = ! empty( $bridge['slug'] ) ? nb_get_service_by_slug( $bridge['slug'] ) : null;
		if ( $svc ) {
			$bridges[ $i ]['lede'] = nb_meta( $svc->ID, 'lede' );
		}
	}
}

$projs_q = nb_query_by_world( 'project', $world, 3 );
$posts_q = nb_query_by_world( 'post', $world, 4 );
?>
<article class="variant c vc-shell t1-world t1-world-<?php echo esc_attr( $world ); ?>" data-bridge="seam">
	<?php get_template_part( 'template-parts/t1-hero', null, array( 'world' => $world ) ); ?>

	<section class="t1-wrap t1-section" style="border-top:none">
		<?php echo nb_sec_head( 1, 'ליבה · lattice', 'עוגן במרכז. השאר במסלול סביבו.', 'חממה במרכז. שאר הפעילויות תלויות בה ישירות. הקריאה מבפנים החוצה.' ); ?>

		<div class="vc-lattice">
			<?php
			$slots = array( 0, 1, 2, 3 );
			foreach ( $slots as $idx => $slot ) {
				if ( 1 === $idx ) {
					get_template_part(
						'template-parts/t1-anchor-card',
						null,
						array(
							'post'  => $anchor,
							'world' => $world,
						)
					);
					continue;
				}
				$svc_idx = $idx > 1 ? $idx - 1 : $idx;
				if ( isset( $lat_services[ $svc_idx ] ) ) {
					get_template_part(
						'template-parts/t1-svc-card',
						null,
						array(
							'post'   => $lat_services[ $svc_idx ],
							'world'  => $world,
							'layout' => 'lattice',
						)
					);
				} elseif ( 3 === $idx && 'code' === $world ) {
					// דיגיטל activities = the two live community tools, each linking
					// to its live home (SITE_COPY_WORLDS_v1). SFA is the anchor;
					// TikTrack is a separately-marketed pilot.
					?>
					<div class="lat-side" style="grid-column:3/4;background:var(--paper-2);border-color:var(--line)">
						<h4 style="font-style:italic;color:var(--ink-soft)">כלים חיים</h4>
						<p><strong>SFA</strong> — מדד מחירים שקוף וספר גידולים. חי ב־<a href="https://sfa.nimrod.bio" rel="noopener">sfa.nimrod.bio</a>.</p>
						<p><strong>TikTrack</strong> — מערכת QA למסחר, בפיילוט סגור, משווק בנפרד. חי ב־<a href="https://tt.nimrod.bio" rel="noopener">tt.nimrod.bio</a>.</p>
						<div class="tag-row" style="font-family:JetBrains Mono;font-size:10px;color:var(--ink-soft)">מהשטח · חוזר לשטח</div>
					</div>
					<?php
				} elseif ( 3 === $idx ) {
					?>
					<div class="lat-side" style="grid-column:3/4;background:var(--paper-2);border-color:var(--line)">
						<h4 style="font-style:italic;color:var(--ink-soft)">קריאה מבפנים</h4>
						<p>החממה מזינה את התוצרת. התוצרת מאמתת את הייעוץ. הייעוץ מקודד ל־SFA. SFA חוזרת לקהילה — לחממה הבאה.</p>
						<div class="tag-row" style="font-family:JetBrains Mono;font-size:10px;color:var(--ink-soft)">evidence-based · מבוסס־שדה</div>
					</div>
					<?php
				}
			}
			?>
		</div>

		<div class="vc-principle">
			<div class="ph img-ph clean" style="aspect-ratio:4/3"></div>
			<div>
				<div class="label">אותו עיקרון · חומר אחר</div>
				<h4>כשאני מתכנן חממה, אני חושב בדיוק כמו כשאני מתכנן מערכת תוכנה.</h4>
				<p>זו לא מטאפורה. <em>זו אותה מערכת, בחומר אחר</em> — לחממה, לקוד, ולתכנון. החיבור ביניהן הוא הסיפור.</p>
			</div>
		</div>
	</section>

	<section class="t1-wrap t1-section" style="border-top:none">
		<?php echo nb_sec_head( 2, 'גשרים · seams', 'הייחוד הוא בקישוריות.', 'לא ענף נוסף — תפר. שני צבעי־עולם פוגשים בכרטיס אחד.' ); ?>
		<div class="vc-bridges">
			<?php foreach ( $bridges as $bridge ) : ?>
				<?php get_template_part( 'template-parts/t1-bridge-card', null, array( 'bridge' => $bridge ) ); ?>
			<?php endforeach; ?>
		</div>
	</section>

	<section class="t1-wrap t1-section" style="border-top:none">
		<?php echo nb_sec_head( 3, 'פרויקטים · מהשטח', 'המקרים מספרים את התזה — לא להפך.', 'פרויקטים נבחרים. כל אחד מקושר לפעילויות שהפעילו אותו.' ); ?>
		<div class="vc-projects">
			<?php if ( $projs_q->have_posts() ) : ?>
				<?php while ( $projs_q->have_posts() ) : ?>
					<?php $projs_q->the_post(); ?>
					<?php get_template_part( 'template-parts/t1-proj-card', null, array( 'post' => get_post() ) ); ?>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			<?php endif; ?>
		</div>
	</section>

	<section class="t1-wrap t1-section" style="border-top:none">
		<?php echo nb_sec_head( 4, 'יומן', 'מה נכתב ב' . nb_world_label( $world ) . '.', 'לא חדשות. תצפיות, מקרים מהשטח, רעיונות שעוד לא הבשילו.' ); ?>
		<div class="vc-posts">
			<?php if ( $posts_q->have_posts() ) : ?>
				<?php while ( $posts_q->have_posts() ) : ?>
					<?php
					$posts_q->the_post();
					$p_worlds = wp_get_post_terms( get_the_ID(), 'world', array( 'fields' => 'slugs' ) );
					$ts       = get_the_date( 'j|n|y' );
					$parts    = explode( '|', $ts );
					?>
					<a href="<?php the_permalink(); ?>" class="post-card">
						<div class="date"><?php echo esc_html( get_the_date( 'M' ) ); ?><b><?php echo esc_html( $parts[0] ?? '' ); ?></b><?php echo esc_html( $parts[2] ?? '' ); ?></div>
						<div>
							<h5><?php the_title(); ?></h5>
							<div class="meta">
								<?php foreach ( $p_worlds as $w ) : ?>
									<?php echo nb_world_chip( $w, true ); ?>
								<?php endforeach; ?>
							</div>
							<?php if ( has_excerpt() ) : ?>
								<p style="font-size:14px;color:var(--ink-soft);margin:8px 0 0;line-height:1.55"><?php echo esc_html( get_the_excerpt() ); ?></p>
							<?php endif; ?>
						</div>
					</a>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			<?php endif; ?>
		</div>
	</section>
</article>
