<?php
/**
 * IconPark <symbol> sprite sheet — garden-series + services glyphs.
 *
 * Source: curated IconPark outline set (team_35 design handoff,
 * images/ip/*.svg). Each glyph normalized to `currentColor` so CSS
 * controls color; original viewBox "0 0 48 48" / 2-stroke weight preserved.
 *
 * Echo this partial ONCE per page (e.g. near the top of front-page.php /
 * footer.php), then reference any glyph from a template:
 *
 *     <svg class="ip" aria-hidden="true"><use href="#ip-leaf"/></svg>
 *
 * Size/color via CSS, e.g. `.ip{width:1em;height:1em;color:var(--w-soil);}`.
 *
 * Available ids: ip-greenhouse, ip-seedling, ip-leaf, ip-tree, ip-carrot,
 * ip-chef, ip-shop, ip-scallion, ip-measure, ip-vbasket, ip-peas, ip-forkspoon,
 * ip-ext (external-link out-arrow · 24×24).
 *
 * @package nimrod-bio-2026
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<svg xmlns="http://www.w3.org/2000/svg" style="display:none" aria-hidden="true" focusable="false" data-sprite="iconpark">
	<symbol id="ip-greenhouse" viewBox="0 0 48 48">
		<g fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="4">
			<path d="M42 20v24H6V20L24 4z"></path>
			<path stroke-linecap="round" d="M6 24h36M13 14v30m22-30v30M20 32h8v12h-8z"></path>
		</g>
	</symbol>
	<symbol id="ip-seedling" viewBox="0 0 48 48">
		<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M24 42V26m17.942-15.993c-.776 13.024-9.13 17.236-15.946 17.896C24.896 28.009 24 27.104 24 26v-8.372c0-.233.04-.468.125-.684C27.117 9.199 34.283 8.155 40 8.02c1.105-.027 2.006.884 1.94 1.987M7.998 6.072c9.329.685 14.197 6.091 15.836 9.558c.115.242.166.508.166.776v7.504c0 1.14-.96 2.055-2.094 1.94C7.337 24.384 6.11 14.786 6.009 8C5.993 6.894 6.897 5.99 8 6.072"></path>
	</symbol>
	<symbol id="ip-leaf" viewBox="0 0 48 48">
		<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M37 24c0 14.094-13 20-13 20s-13-4.625-13-20S24 4 24 4s13 5.906 13 20M24 36l5-5m-5-2l-5-5m5-1l5-5m-5 26V14"></path>
	</symbol>
	<symbol id="ip-tree" viewBox="0 0 48 48">
		<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M13.045 14C13.55 8.393 18.262 4 24 4s10.45 4.393 10.955 10H35a9 9 0 1 1 0 18H13a9 9 0 1 1 0-18zM24 28l5-5m-5 2l-6-6m6 25V18"></path>
	</symbol>
	<symbol id="ip-carrot" viewBox="0 0 48 48">
		<g fill="none" stroke="currentColor" stroke-width="4">
			<path d="M15.624 20.682C14.29 15.248 18.404 10 24 10s9.71 5.248 8.376 10.682L27.279 41.43a3.376 3.376 0 0 1-6.557 0z"></path>
			<path stroke-linecap="round" d="M24 4v5.5m6.102-3.908l-2.728 3.25M18 5.592l2.727 3.25M16 19h6m3 7h6m-12 8h4"></path>
		</g>
	</symbol>
	<symbol id="ip-chef" viewBox="0 0 48 48">
		<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M13 24.125a8.64 8.64 0 1 1 3.857-16.837A8.63 8.63 0 0 1 23.64 4a8.63 8.63 0 0 1 6.919 3.464A8.64 8.64 0 1 1 35 24.124V40a2 2 0 0 1-2 2H15a2 2 0 0 1-2-2zM13 31h22m-15-6v6m15-3v6m-22-6v6"></path>
	</symbol>
	<symbol id="ip-shop" viewBox="0 0 48 48">
		<g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="4">
			<path d="M4 12h40v8l-1.398.84a7 7 0 0 1-7.203 0L34 20l-1.398.84a7 7 0 0 1-7.203 0L24 20l-1.398.84a7 7 0 0 1-7.203 0L14 20l-1.399.84a7 7 0 0 1-7.202 0L4 20z"></path>
			<path d="M8 22.489V44h32V22M8 11.822V4h32v8"></path>
			<path d="M19 32h10v12H19z"></path>
		</g>
	</symbol>
	<symbol id="ip-scallion" viewBox="0 0 48 48">
		<g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="4">
			<path d="M20 24s2.5-4.5 3-9s-1-8-1-8l5-3s1 6 1 9"></path>
			<path d="M6 43c-2-1.5-2-6.91 2-10s4.186-2.283 9-6S34 4 34 4l4.5 3.5l-12.19 16.24c-2.984 3.977-3.758 9.313-6.26 13.61C18.102 40.7 16 42 14 43s-6 1.5-8 0"></path>
			<path d="M23 30s3-2 7-4s13-2 13-2l-3-7s-8 0-11 3"></path>
		</g>
	</symbol>
	<symbol id="ip-measure" viewBox="0 0 48 48">
		<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M36 6h8l-1.936 14H36M9 6h27v34a2 2 0 0 1-2 2H11a2 2 0 0 1-2-2l-.001-23.5c0-.944-.444-1.828-1.16-2.443C5.148 11.75-.591 6 8.999 6M26 15h4m-4 8h4m-4 8h4"></path>
	</symbol>
	<symbol id="ip-vbasket" viewBox="0 0 48 48">
		<g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="4">
			<path d="M10 14H7.378a3 3 0 0 0-2.98 3.354L7.12 40.236A2 2 0 0 0 9.105 42h30.368a2 2 0 0 0 1.991-1.807l2.218-22.904A3 3 0 0 0 40.696 14H38M5 22h38m-28 7h18m-16 7h14"></path>
			<path d="M24 6c-4.418 0-8 6.925-8 15.467q0 .267.005.533h15.99q.005-.266.005-.533C32 12.925 28.418 6 24 6"></path>
		</g>
	</symbol>
	<symbol id="ip-peas" viewBox="0 0 48 48">
		<g fill="none">
			<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M27 12s7 7 7 16s-4.445 16.223-8 16c-3.556-.223-7-7-6-16s7-16 7-16m0 0s1-4.124 4-6.062S39.89 9 39 12s-4 3-5 0s4-6.5 7-6.062S44.257 11.18 44 14c-.501 5.5-2 10-2 10"></path>
			<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M27 12s0 8-7 16s-13.675 9.7-16 7s0-10 7-17s16-6 16-6"></path>
			<circle cx="27.243" cy="27.408" r="2.5" fill="currentColor"></circle>
			<circle cx="26.243" cy="34.408" r="2.5" fill="currentColor"></circle>
		</g>
	</symbol>
	<symbol id="ip-forkspoon" viewBox="0 0 48 48">
		<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M14 4v40M8 5v10c0 5 6 5 6 5s6 0 6-5V5m14 15v24m6-32c0 4.418-2.686 8-6 8s-6-3.582-6-8s2.686-8 6-8s6 3.582 6 8"></path>
	</symbol>
	<symbol id="ip-ext" viewBox="0 0 24 24">
		<g fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
			<path d="M14 5h5v5"></path>
			<path d="M19 5l-8 8"></path>
			<path d="M18 14v4a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h4"></path>
		</g>
	</symbol>
</svg>
