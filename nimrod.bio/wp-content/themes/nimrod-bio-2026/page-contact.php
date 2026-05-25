<?php
defined( 'ABSPATH' ) || exit;

get_header();
?>
<article class="t8 t8-contact">
	<section class="contact-hero">
		<div class="t8-wrap">
			<h1>דבר איתי.</h1>
			<p class="lede">
				שיחה ראשונה — ללא התחייבות. <em>30 דקות, אני מבין מה אתה צריך, אתה רואה אם יש לי משהו לתרום.</em>
			</p>
		</div>
	</section>

	<section class="t8-wrap">
		<div class="contact-body">
			<?php get_template_part( 'template-parts/t8-contact-form' ); ?>
			<?php get_template_part( 'template-parts/t8-contact-side' ); ?>
		</div>
	</section>
</article>
<?php
get_footer();
