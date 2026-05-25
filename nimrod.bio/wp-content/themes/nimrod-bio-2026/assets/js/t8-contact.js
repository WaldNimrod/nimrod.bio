(function () {
	'use strict';

	var params = new URLSearchParams(window.location.search);
	var status = params.get('status');
	var banner = document.getElementById('nb-contact-status');

	if (banner && status) {
		var messages = {
			ok: 'הפנייה נשלחה. אחזור אליך תוך 48 שעות.',
			error: 'משהו לא עבד בשליחה. נסה שוב או שלח WhatsApp.',
			invalid: 'חסרים שדות חובה או שההודעה קצרה מדי (20 תווים).'
		};
		banner.textContent = messages[status] || '';
		banner.hidden = !messages[status];
		banner.className = 'contact-status contact-status--' + status;
	}

	document.querySelectorAll('.topic-chip input[type="checkbox"]').forEach(function (input) {
		var chip = input.closest('.topic-chip');
		if (!chip) {
			return;
		}
		var sync = function () {
			chip.classList.toggle('is-on', input.checked);
		};
		input.addEventListener('change', sync);
		sync();
	});

	var form = document.getElementById('nb-contact');
	if (form) {
		form.addEventListener('submit', function () {
			var btn = form.querySelector('.form-submit');
			if (btn) {
				btn.disabled = true;
				btn.textContent = 'שולח…';
			}
		});
	}
})();
