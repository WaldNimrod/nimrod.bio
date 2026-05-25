<?php
defined( 'ABSPATH' ) || exit;
$active = nb_active_world();
?>
<nav class="shell-nav" aria-label="ראשי">
	<div class="shell-nav-inner">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="shell-mark">
			נימרוד ולד<small>nimrod.bio</small>
		</a>
		<div class="shell-links">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nav-home" aria-label="בית" title="בית">
				<?php echo nb_home_icon(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</a>
			<div class="nav-worlds">
				<a href="<?php echo esc_url( home_url( '/world/soil/' ) ); ?>"
					class="nav-world soil<?php echo $active === 'soil' ? ' is-active' : ''; ?>"><?php echo esc_html( nb_world_label( 'soil' ) ); ?></a>
				<a href="<?php echo esc_url( home_url( '/world/know/' ) ); ?>"
					class="nav-world know<?php echo $active === 'know' ? ' is-active' : ''; ?>"><?php echo esc_html( nb_world_label( 'know' ) ); ?></a>
				<a href="<?php echo esc_url( home_url( '/world/code/' ) ); ?>"
					class="nav-world code<?php echo $active === 'code' ? ' is-active' : ''; ?>"><?php echo esc_html( nb_world_label( 'code' ) ); ?></a>
			</div>
			<span class="nav-sep" aria-hidden="true"></span>
			<div class="nav-secondary">
				<a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">בלוג</a>
				<a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">על נמרוד</a>
			</div>
			<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="contact">צור קשר</a>
		</div>
	</div>
</nav>
