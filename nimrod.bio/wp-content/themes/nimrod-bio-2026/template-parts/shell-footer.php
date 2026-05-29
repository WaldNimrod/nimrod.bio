<?php
defined( 'ABSPATH' ) || exit;

/* Ensure the IconPark sprite is present for the footer garden-series strip
 * on every page (front-page.php already inlines it; guard prevents a
 * double-include there). (P009-WP003 B3.) */
if ( ! defined( 'NB_ICON_SPRITE_DONE' ) ) {
	define( 'NB_ICON_SPRITE_DONE', true );
	require NB_THEME_DIR . '/assets/icons/icon-sprite.php';
}
?>
<footer class="shell-foot">
	<div class="shell-foot-inner">
		<div class="series-strip" aria-hidden="true">
			<svg class="ip"><use href="#ip-seedling"/></svg>
			<svg class="ip"><use href="#ip-leaf"/></svg>
			<svg class="ip"><use href="#ip-greenhouse"/></svg>
			<svg class="ip"><use href="#ip-carrot"/></svg>
			<svg class="ip"><use href="#ip-scallion"/></svg>
			<svg class="ip"><use href="#ip-peas"/></svg>
			<svg class="ip"><use href="#ip-tree"/></svg>
		</div>
		<div class="cols">
			<div class="brand-block">
				<div class="name">נימרוד ולד</div>
				<div class="tag">שורש אחד, שלושה עולמות. <em class="unless-inline">Unless</em>.</div>
			</div>
			<div>
				<h6>עולמות</h6>
				<a href="<?php echo esc_url( home_url( '/world/soil/' ) ); ?>">אדמה</a>
				<a href="<?php echo esc_url( home_url( '/world/know/' ) ); ?>">ייעוץ והוראה <small>· ידע</small></a>
				<a href="<?php echo esc_url( home_url( '/world/code/' ) ); ?>">דיגיטל / מיזו</a>
			</div>
			<div>
				<h6>תוכן</h6>
				<a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">בלוג</a>
				<a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">על נמרוד</a>
			</div>
			<div>
				<h6>קשר</h6>
				<a href="mailto:nimrod@nimrod.bio">nimrod@nimrod.bio</a>
				<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">צור קשר</a>
			</div>
		</div>
		<div class="bottom">
			<span>© נימרוד ולד · <?php echo esc_html( date( 'Y' ) ); ?> · נבנה בשני עונות.</span>
			<span class="unless">העולם הוא כזה — <em>אלא אם כן</em>.</span>
		</div>
	</div>
</footer>

<a href="https://wa.me/972547776770"
	class="wa-fab"
	target="_blank" rel="noopener"
	aria-label="WhatsApp - שלח הודעה">
	<svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
		<path d="M17.5 14.4c-.3-.1-1.7-.8-1.9-.9-.3-.1-.5-.1-.7.1-.2.3-.8.9-1 1.1-.2.2-.4.2-.6.1-.3-.1-1.2-.5-2.2-1.4-.8-.7-1.4-1.6-1.5-1.9-.2-.3 0-.4.1-.6.1-.1.3-.4.4-.5.1-.2.2-.3.3-.5.1-.2 0-.4 0-.5-.1-.1-.7-1.6-.9-2.2-.2-.6-.5-.5-.7-.5h-.6c-.2 0-.5.1-.8.4-.3.3-1 1-1 2.5s1.1 2.9 1.2 3.1c.1.2 2.1 3.2 5 4.5.7.3 1.3.5 1.7.6.7.2 1.3.2 1.8.1.6-.1 1.7-.7 2-1.4.2-.7.2-1.3.2-1.4-.1-.2-.3-.3-.6-.4zM12 2.1C6.5 2.1 2.1 6.6 2.1 12c0 1.7.4 3.3 1.3 4.7L2.1 22l5.4-1.3c1.4.8 3 1.2 4.5 1.2 5.5 0 9.9-4.5 9.9-9.9S17.5 2.1 12 2.1z"/>
	</svg>
</a>
