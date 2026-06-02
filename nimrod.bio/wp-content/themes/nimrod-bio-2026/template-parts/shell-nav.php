<?php
defined( 'ABSPATH' ) || exit;
$active = nb_active_world();
?>
<nav class="shell-nav" aria-label="ראשי">
	<div class="shell-nav-inner">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="shell-mark">
			<img class="basket basket-ink" src="<?php echo esc_url( NB_THEME_URI . '/assets/img/basket-ink.png' ); ?>" alt="" width="36" height="36" decoding="async">
			<img class="basket basket-paper" src="<?php echo esc_url( NB_THEME_URI . '/assets/img/basket-paper.png' ); ?>" alt="" width="36" height="36" decoding="async">
			<span class="wm">נימרוד ולד<small>nimrod.bio</small></span>
		</a>
		<div class="shell-links">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nav-home" aria-label="בית" title="בית">
				<?php echo nb_home_icon(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</a>
			<div class="nav-worlds">
				<a href="<?php echo esc_url( home_url( '/world/soil/' ) ); ?>"
					class="nav-world soil<?php echo $active === 'soil' ? ' is-active' : ''; ?>"><?php echo nb_world_icon( 'soil' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php echo esc_html( nb_world_label( 'soil' ) ); ?></span></a>
				<a href="<?php echo esc_url( home_url( '/world/know/' ) ); ?>"
					class="nav-world know<?php echo $active === 'know' ? ' is-active' : ''; ?>"><?php echo nb_world_icon( 'know' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php echo esc_html( nb_world_label( 'know' ) ); ?></span></a>
				<a href="<?php echo esc_url( home_url( '/world/code/' ) ); ?>"
					class="nav-world code<?php echo $active === 'code' ? ' is-active' : ''; ?>"><?php echo nb_world_icon( 'code' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php echo esc_html( nb_world_label( 'code' ) ); ?></span></a>
			</div>
			<span class="nav-sep" aria-hidden="true"></span>
			<div class="nav-secondary">
				<a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">בלוג</a>
				<a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">על נמרוד</a>
			</div>
			<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="contact">צור קשר</a>
		</div>
		<button class="nav-toggle" type="button"
			aria-label="פתח תפריט" aria-expanded="false" aria-controls="nav-drawer">
			<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
				stroke-width="2" stroke-linecap="round" aria-hidden="true">
				<line x1="3" y1="6" x2="21" y2="6"></line>
				<line x1="3" y1="12" x2="21" y2="12"></line>
				<line x1="3" y1="18" x2="21" y2="18"></line>
			</svg>
		</button>
	</div>
</nav>

<!-- a11y P009-WP006: `inert` while closed removes the drawer links from focus
     order + the a11y tree (fixes aria-hidden-focus). nav-drawer.js toggles it. -->
<aside class="nav-drawer" id="nav-drawer" aria-hidden="true" inert>
	<div class="drawer-head">
		<span class="title">תפריט</span>
		<button class="drawer-close" type="button" aria-label="סגור">&times;</button>
	</div>
	<nav class="drawer-nav" aria-label="ראשי - מובייל">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="drawer-link home">בית</a>
		<hr>
		<div class="drawer-section-label">עולמות</div>
		<a href="<?php echo esc_url( home_url( '/world/soil/' ) ); ?>" class="drawer-link world soil">
			<span class="dot"></span><?php echo esc_html( nb_world_label( 'soil' ) ); ?>
		</a>
		<a href="<?php echo esc_url( home_url( '/world/know/' ) ); ?>" class="drawer-link world know">
			<span class="dot"></span><?php echo esc_html( nb_world_label( 'know' ) ); ?>
		</a>
		<a href="<?php echo esc_url( home_url( '/world/code/' ) ); ?>" class="drawer-link world code">
			<span class="dot"></span><?php echo esc_html( nb_world_label( 'code' ) ); ?>
		</a>
		<hr>
		<a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>" class="drawer-link">בלוג</a>
		<a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="drawer-link">על נמרוד</a>
		<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="drawer-link cta">צור קשר ←</a>
	</nav>
</aside>

<div class="nav-backdrop" aria-hidden="true"></div>
