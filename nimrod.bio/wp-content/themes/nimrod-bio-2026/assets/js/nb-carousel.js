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
 *       Here `data-dir` is a LOGICAL direction (mockup contract):
 *         data-dir="1"  (→) -> "previous" / toward start
 *         data-dir="-1" (←) -> "next"     / toward content (later items)
 *       The PHYSICAL scrollLeft sign is DERIVED from the track's actual scroll
 *       scheme (RTL containers use a negative/inverted scrollLeft range in
 *       Chromium), so clicking ← reveals later items in RTL. It is NOT passed to
 *       scrollBy verbatim — that was a no-op in the RTL negative scheme.
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

	// Resolve the track's resolved inline (writing) direction.
	function trackIsRtl(track) {
		var dir = 'ltr';
		if (window.getComputedStyle) {
			dir = window.getComputedStyle(track).direction || 'ltr';
		} else if (document.dir) {
			dir = document.dir;
		}
		return dir === 'rtl';
	}

	// Physical sign (+1/-1) that, when applied to scrollLeft via scrollBy,
	// moves the track FORWARD into content (toward later items / "next").
	//
	// Browsers implement three scrollLeft schemes for RTL containers:
	//   - "negative"  : start=0, forward = scrollLeft goes negative  (Chrome/Edge/FF today)
	//   - "reverse"   : start=max, forward = scrollLeft goes toward 0 (legacy WebKit)
	//   - "default"   : same as LTR, forward = scrollLeft grows       (very old)
	// LTR is always "forward = scrollLeft grows".
	//
	// We feature-detect by probing scrollLeft instead of assuming a sign — the
	// previous code passed mockup data-dir verbatim (+320 for "1"), which is a
	// no-op in the negative scheme (start scrollLeft is already 0). Detection is
	// non-destructive: we restore scrollLeft after probing.
	function forwardPhysicalSign(track) {
		if (!trackIsRtl(track)) { return 1; } // LTR: forward grows scrollLeft

		var probe = track.scrollLeft;
		// If already negative, we're in the negative scheme -> forward is -1.
		if (probe < 0) { return -1; }

		// scrollLeft is 0 (typical at start). Try to move it positive by 1px.
		track.scrollLeft = 1;
		var moved = track.scrollLeft;
		track.scrollLeft = probe; // restore

		if (moved > 0) {
			// "default" scheme: positive scrollLeft is reachable -> forward = +1.
			return 1;
		}
		// Could not go positive from 0 -> negative scheme -> forward = -1.
		// (reverse scheme starts at max, so probe would have been > 0 already.)
		return -1;
	}

	// Resolve the physical scroll delta (px) for a click.
	// Numeric data-dir ("1"/"-1") = mockup contract: 1 = "previous/start" (→),
	//   -1 = "next/content" (←). Logical data-dir ("next"/"prev") = same intent.
	// Both are mapped to a LOGICAL forward/back, then to a physical sign derived
	// from the track's actual scrollLeft scheme — so arrows move the track
	// correctly in RTL (and LTR) regardless of the browser's scrollLeft sign.
	function physicalDelta(btn, track) {
		var raw = (btn.getAttribute('data-dir') || '').trim().toLowerCase();

		// logicalForward: +1 = advance into content ("next"), -1 = back ("previous").
		var logicalForward;
		var numeric = parseInt(raw, 10);
		if (!isNaN(numeric) && /^-?\d+$/.test(raw)) {
			// Mockup: data-dir="-1" is "next" (←), data-dir="1" is "previous" (→).
			logicalForward = numeric < 0 ? 1 : -1;
		} else {
			logicalForward = raw === 'prev' ? -1 : 1; // default/unknown -> next
		}

		return logicalForward * forwardPhysicalSign(track) * STEP;
	}

	// Whether the track can still scroll in the PHYSICAL direction of `delta`.
	// Works across LTR and RTL by treating the reachable scrollLeft range as a
	// continuous interval [min .. max]. The interval bounds depend on the RTL
	// scrollLeft scheme, which we derive from the forward physical sign rather
	// than the current scrollLeft value (which is 0 at the start position and so
	// cannot, on its own, distinguish the negative scheme).
	//   - forward sign +1 (LTR / "default"): scrollLeft in [0 .. span]
	//   - forward sign -1 (negative RTL):     scrollLeft in [-span .. 0]
	// `delta > 0` means scroll toward larger scrollLeft; `delta < 0` toward smaller.
	function canScroll(track, delta) {
		var span = track.scrollWidth - track.clientWidth;
		if (span <= 1) { return false; } // not scrollable at all

		var negativeScheme = forwardPhysicalSign(track) < 0;
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
