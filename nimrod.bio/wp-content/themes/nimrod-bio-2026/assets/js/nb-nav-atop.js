/* nb-nav-atop.js — hero-aware nav transparency (nimrod.bio T7, P009-WP003)
 *
 * Behavior:
 *   - The shell nav (.shell-nav) is transparent-dark while it sits OVER the hero
 *     poster, and solid once the hero has scrolled away.
 *   - The transparent-dark state is the CSS class `.atop` on the nav.
 *       hero.getBoundingClientRect().bottom > 120  ->  add .atop  (over hero)
 *       otherwise                                  ->  remove .atop (solid)
 *   - Pages with NO .hero-poster (inner pages) are forced to the solid state
 *     (no .atop), matching the mockup's T1 world-page screen.
 *
 * DOM contract:
 *   - nav:  <nav class="shell-nav"> ... </nav>     (the toggled element)
 *   - hero: <section class="hero-poster"> ... </section>  (optional)
 *
 * Progressive enhancement: every selector is guarded; missing elements no-op.
 * Runs on initial load, on scroll, on resize, and continuously via rAF so the
 * state is correct even when the scroll position changes without a scroll event
 * (anchor jumps, layout shifts, programmatic scroll).
 *
 * Vanilla JS, no dependencies, defer-safe.
 */
(function () {
	'use strict';

	function init() {
		var nav = document.querySelector('.shell-nav');
		if (!nav) { return; }

		var hero = document.querySelector('.hero-poster');

		// No hero on this page -> force solid state and stop. Nothing to watch.
		if (!hero) {
			nav.classList.remove('atop');
			return;
		}

		var THRESHOLD = 120; // px from viewport top

		// Track last applied state to avoid redundant classList writes in the
		// rAF loop (cheap, but keeps layout/style work to a minimum).
		var lastAtop = null;

		function evaluate() {
			var atop = hero.getBoundingClientRect().bottom > THRESHOLD;
			if (atop !== lastAtop) {
				lastAtop = atop;
				nav.classList.toggle('atop', atop);
			}
		}

		// Continuous rAF loop — robust to scroll position changes that do not
		// emit a scroll event.
		function tick() {
			evaluate();
			window.requestAnimationFrame(tick);
		}

		// Initial + event-driven passes (rAF covers the rest).
		evaluate();
		window.addEventListener('scroll', evaluate, { passive: true });
		window.addEventListener('resize', evaluate, { passive: true });

		if (window.requestAnimationFrame) {
			window.requestAnimationFrame(tick);
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
