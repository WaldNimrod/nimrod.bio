<?php
defined( 'ABSPATH' ) || exit;
?>
<section class="t3-wrap t3-section">
	<?php echo nb_sec_head( 1, 'הסיפור', get_the_title(), '' ); ?>
	<div class="story">
		<div class="story-inner">
			<?php the_content(); ?>
		</div>
	</div>
</section>
