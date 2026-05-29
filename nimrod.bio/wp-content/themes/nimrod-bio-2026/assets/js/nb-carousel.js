/* nb-carousel.js — horizontal scroll-snap carousels (nimrod.bio T7, P009-WP003)
 *
 * Wires the round arrow buttons of the Services and Projects carousels to
 * native smooth horizontal scrolling. The scroll track itself uses CSS
 * scroll-snap (handled elsewhere); this file only handles the arrows and the
 * end-state disabling.
 *
 * SUPPORTED CARROUSEL TRACKS (the scrollable element):
 *   - Services: .products-grid.carousel   (inside .services-end)
 *   - Projects: .projects-row
 *
 * DOM CONTRACT — markup author, please follow ONE of these (both supported):
 *
 *   (A) Mockup convention (already in Precision Mockup.html) — PRIMARY:
 *       A sibling controls block `.proj-browse` placed in the SAME <section>
 *       as the track, containing the arrow buttons:
 *
 *         <section class="section wrap services-end">
 *           <div class="products-grid carousel"> ...cards... </div>
 *           <div class="proj-browse">
 *             <button class="pb-arrow" data-dir="1"  aria-label="הקודם">→</button>
 *             <button class="pb-arrow" data-dir="-1" aria-label="הבא">←</button>
 *             <a class="proj-more" href="#">כל השירותים ←</a>
 *           </div>
 *         </section>
 *
 *       Here `data-dir` is the PHYSICAL scroll sign passed straight to scrollBy:
 *         data-dir="1"  -> scrollBy(+320)  (RTL: toward start / "previous")
 *         data-dir="-1" -> scrollBy(-320)  (RTL: toward content / "next")
 *       These values are already RTL-correct for the Hebrew layout, so they are
 *       used verbatim — no extra flipping is applied to numeric data-dir.
 *
 *   (B) Explicit wrapper convention (cleaner, recommended for new markup):
 *       Wrap the track + arrows in an element carrying [data-carousel]; give
 *       each arrow a LOGICAL direction:
 *
 *         <div data-carousel>
 *           <div class="projects-row"> ...cards... </div>
 *           <button data-dir="prev" aria-label="הקודם">→</button>
 *           <button data-dir="next" aria-label="הבא">←</button>
 *         </div>
 *
 *       Logical "next" = forward into content. The physical sign is derived
 *       from the track's resolved direction (RTL flips it), so this works in
 *       both LTR and RTL automatically.
 *
 * STEP: 320px per click (per design spec).
 * REDUCED MOTION: honors `@media (prefers-reduced-motion: reduce)` ->
 *   behavior:'auto' (instant) instead of 'smooth'.
 * END STATE: arrows are disabled (and aria-disabled) when the track cannot
 *   scroll further in that direction.
 *
 * Vanilla JS, no dependencies, defer-safe, supports multiple carousels/page.
 */
(function () {
	'use strict';

	var STEP = 320;
	var TRACK_SELECTOR = '.products-grid.carousel, .projects-row';

	function prefersReducedMotion() {
		return !!(window.matchMedia &&
			window.matchMedia('(prefers-reduced-motion: reduce)').matches);
	}

	function scrollBehavior() {
		return prefersReducedMotion() ? 'auto' : 'smooth';
	}

	// Resolve a track for a given arrow button.
	// Tries: explicit [data-carousel] wrapper first, then nearest <section>.
	function findTrack(btn) {
		var scope = btn.closest('[data-carousel]') || btn.closest('section');
		if (!scope) { return null; }
		return scope.querySelector(TRACK_SELECTOR);
	}

	// Resolve the physical scroll delta (px) for a click.
	// Numeric data-dir ("1"/"-1") -> physical sign, used verbatim.
	// Logical data-dir ("next"/"prev") -> derived from track direction (RTL-aware).
	function physicalDelta(btn, track) {
		var raw = (btn.getAttribute('data-dir') || '').trim().toLowerCase();

		var numeric = parseInt(raw, 10);
		if (!isNaN(numeric) && /^-?\d+$/.test(raw)) {
			return numeric * STEP;
		}

		// Logical direction. Determine track's resolved writing direction.
		var dir = 'ltr';
		if (window.getComputedStyle) {
			dir = window.getComputedStyle(track).direction || 'ltr';
		} else if (document.dir) {
			dir = document.dir;
		}
		var isRtl = dir === 'rtl';

		// Logical "next" = forward into content.
		// LTR: forward = +scrollLeft. RTL: forward = -scrollLeft (toward start).
		var forward = raw === 'prev' ? -1 : 1; // default/unknown -> next
		var sign = isRtl ? -forward : forward;
		return sign * STEP;
	}

	// Whether the track can still scroll in the PHYSICAL direction of `delta`.
	// Works across LTR and RTL by treating the reachable scrollLeft range as a
	// continuous interval [min .. max]:
	//   - LTR / modern RTL:  scrollLeft in [0 .. (scrollWidth-clientWidth)]
	//   - legacy negative RTL: scrollLeft in [-(scrollWidth-clientWidth) .. 0]
	// `delta > 0` means scroll toward larger scrollLeft; `delta < 0` toward smaller.
	function canScroll(track, delta) {
		var span = track.scrollWidth - track.clientWidth;
		if (span <= 1) { return false; } // not scrollable at all

		// Detect which RTL scheme is in use from the current sign.
		var negativeScheme = track.scrollLeft < 0;
		var min = negativeScheme ? -span : 0;
		var max = negativeScheme ? 0 : span;

		var pos = track.scrollLeft;
		if (delta > 0) { return pos < max - 1; } // room to grow
		if (delta < 0) { return pos > min + 1; } // room to shrink
		return false;
	}

	function updateArrowStates(track, arrows) {
		for (var i = 0; i < arrows.length; i++) {
			var btn = arrows[i];
			var delta = physicalDelta(btn, track);
			var enabled = canScroll(track, delta);
			btn.disabled = !enabled;
			btn.setAttribute('aria-disabled', enabled ? 'false' : 'true');
		}
	}

	function wireCarousel(track, arrows) {
		if (!track || !arrows.length) { return; }

		function onClick(e) {
			var btn = e.currentTarget;
			var delta = physicalDelta(btn, track);
			track.scrollBy({ left: delta, behavior: scrollBehavior() });
		}

		for (var i = 0; i < arrows.length; i++) {
			arrows[i].addEventListener('click', onClick);
		}

		var refresh = function () { updateArrowStates(track, arrows); };
		track.addEventListener('scroll', refresh, { passive: true });
		window.addEventListener('resize', refresh, { passive: true });
		refresh(); // initial end-state
	}

	function init() {
		var tracks = document.querySelectorAll(TRACK_SELECTOR);
		if (!tracks.length) { return; }

		for (var i = 0; i < tracks.length; i++) {
			var track = tracks[i];
			var scope = track.closest('[data-carousel]') || track.closest('section');
			if (!scope) { continue; }

			// Arrows: explicit wrapper buttons, or the mockup's .proj-browse .pb-arrow.
			var arrows = scope.querySelectorAll(
				'[data-dir], .proj-browse .pb-arrow'
			);
			wireCarousel(track, arrows);
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
