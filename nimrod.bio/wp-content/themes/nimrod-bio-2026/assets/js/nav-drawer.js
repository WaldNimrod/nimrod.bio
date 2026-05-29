// nav-drawer.js — accessible mobile nav drawer (P009-WP002)
// Vanilla, no deps. Progressive enhancement: if elements are missing, no-op.
(function () {
	'use strict';

	function init() {
		var toggle   = document.querySelector('.nav-toggle');
		var drawer   = document.querySelector('.nav-drawer');
		var backdrop = document.querySelector('.nav-backdrop');
		var closeBtn = drawer ? drawer.querySelector('.drawer-close') : null;

		// Progressive enhancement: bail silently if shell markup absent.
		if (!toggle || !drawer || !backdrop) {
			return;
		}

		var FOCUSABLE = 'a[href], button:not([disabled]), [tabindex]:not([tabindex="-1"])';

		function focusable() {
			return Array.prototype.slice.call(drawer.querySelectorAll(FOCUSABLE))
				.filter(function (el) {
					return el.offsetParent !== null || el === document.activeElement;
				});
		}

		function open() {
			drawer.classList.add('is-open');
			backdrop.classList.add('is-open');
			drawer.setAttribute('aria-hidden', 'false');
			backdrop.setAttribute('aria-hidden', 'false');
			toggle.setAttribute('aria-expanded', 'true');
			document.body.style.overflow = 'hidden';
			document.body.classList.add('nav-open');
			var items = focusable();
			if (items.length) {
				items[0].focus();
			}
			document.addEventListener('keydown', onKeydown);
		}

		function shut() {
			drawer.classList.remove('is-open');
			backdrop.classList.remove('is-open');
			drawer.setAttribute('aria-hidden', 'true');
			backdrop.setAttribute('aria-hidden', 'true');
			toggle.setAttribute('aria-expanded', 'false');
			document.body.style.overflow = '';
			document.body.classList.remove('nav-open');
			document.removeEventListener('keydown', onKeydown);
			toggle.focus();
		}

		function isOpen() {
			return drawer.classList.contains('is-open');
		}

		function onKeydown(e) {
			if (e.key === 'Escape' || e.key === 'Esc') {
				shut();
				return;
			}
			if (e.key !== 'Tab') {
				return;
			}
			// Focus trap.
			var items = focusable();
			if (!items.length) {
				e.preventDefault();
				return;
			}
			var first = items[0];
			var last  = items[items.length - 1];
			var active = document.activeElement;

			if (e.shiftKey) {
				if (active === first || !drawer.contains(active)) {
					e.preventDefault();
					last.focus();
				}
			} else {
				if (active === last || !drawer.contains(active)) {
					e.preventDefault();
					first.focus();
				}
			}
		}

		toggle.addEventListener('click', function () {
			if (isOpen()) { shut(); } else { open(); }
		});
		backdrop.addEventListener('click', shut);
		if (closeBtn) {
			closeBtn.addEventListener('click', shut);
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
