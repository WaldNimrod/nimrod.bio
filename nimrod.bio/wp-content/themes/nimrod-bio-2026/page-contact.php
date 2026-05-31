<?php
defined( 'ABSPATH' ) || exit;

get_header();
?>
<article class="t8 t8-contact">
	<section class="contact-hero">
		<div class="t8-wrap">
			<h1>דבר איתי</h1>
			<p class="lede">
				הדרך הכי טובה להתחיל היא פשוט לכתוב. שיחה ראשונה — ללא התחייבות.
				<em>30 דקות: אני מבין על מה אתה עובד, אתה רואה אם יש לי מה לתרום.</em>
			</p>
			<div class="contact-hero-actions">
				<a href="https://wa.me/972547776770" class="hero-act hero-act--primary" target="_blank" rel="noopener noreferrer">
					<svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true" fill="currentColor">
						<path d="M17.5 14.4c-.3-.1-1.7-.8-1.9-.9-.3-.1-.5-.1-.7.1-.2.3-.8.9-1 1.1-.2.2-.4.2-.6.1-.3-.1-1.2-.5-2.2-1.4-.8-.7-1.4-1.6-1.5-1.9-.2-.3 0-.4.1-.6.1-.1.3-.4.4-.5.1-.2.2-.3.3-.5.1-.2 0-.4 0-.5-.1-.1-.7-1.6-.9-2.2-.2-.6-.5-.5-.7-.5h-.6c-.2 0-.5.1-.8.4-.3.3-1 1-1 2.5s1.1 2.9 1.2 3.1c.1.2 2.1 3.2 5 4.5.7.3 1.3.5 1.7.6.7.2 1.3.2 1.8.1.6-.1 1.7-.7 2-1.4.2-.7.2-1.3.2-1.4-.1-.2-.3-.3-.6-.4zM12 2.1C6.5 2.1 2.1 6.6 2.1 12c0 1.7.4 3.3 1.3 4.7L2.1 22l5.4-1.3c1.4.8 3 1.2 4.5 1.2 5.5 0 9.9-4.5 9.9-9.9S17.5 2.1 12 2.1z"/>
					</svg>
					WhatsApp · 054-7776770
				</a>
				<a href="#nb-contact" class="hero-act hero-act--ghost">טופס</a>
			</div>
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
