<?php
defined( 'ABSPATH' ) || exit;

$topics = array(
	'soil' => 'אדמה',
	'know' => 'ייעוץ והוראה',
	'code' => 'דיגיטל',
);
?>
<form id="nb-contact" class="contact-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
	<?php wp_nonce_field( 'nb_contact_submit', 'nb_contact_nonce' ); ?>
	<input type="hidden" name="action" value="nb_contact_submit">
	<input type="text" name="website" value="" tabindex="-1" autocomplete="off" class="hp-field" aria-hidden="true">

	<div id="nb-contact-status" class="contact-status" hidden></div>

	<h3>טופס פנייה</h3>
	<p class="form-intro">אני קורא הכל. בדרך כלל חוזר תוך 48 שעות. <em>אם זה דחוף — WhatsApp בצד.</em></p>

	<div class="field">
		<label for="nb-contact-name">שם <span class="opt">· איך לפנות אליך</span></label>
		<input id="nb-contact-name" type="text" name="name" required>
	</div>

	<div class="field">
		<label for="nb-contact-email">אימייל</label>
		<input id="nb-contact-email" type="email" name="email" required>
	</div>

	<div class="field">
		<label for="nb-contact-phone">טלפון <span class="opt">· אופציונלי</span></label>
		<input id="nb-contact-phone" type="tel" name="phone">
	</div>

	<fieldset class="topic-chips">
		<legend>נושא (אפשר לבחור כמה)</legend>
		<?php foreach ( $topics as $slug => $label ) : ?>
			<label class="topic-chip <?php echo esc_attr( $slug ); ?>">
				<input type="checkbox" name="topics[]" value="<?php echo esc_attr( $slug ); ?>">
				<?php echo esc_html( $label ); ?>
			</label>
		<?php endforeach; ?>
	</fieldset>

	<div class="field">
		<label for="nb-contact-message">הודעה</label>
		<textarea id="nb-contact-message" name="message" required minlength="20" placeholder="מה המצב, מה רצית להבין, מה אתה מנסה לעשות..."></textarea>
	</div>

	<button type="submit" class="form-submit btn btn-spark">שלח</button>
	<div class="form-foot">בלי ספאם · בלי רשימות תפוצה · רק תגובה לפנייה הספציפית</div>
</form>
