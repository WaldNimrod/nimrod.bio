<?php defined( 'ABSPATH' ) || exit; ?>
<footer class="shell-foot">
	<div class="shell-foot-inner">
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
