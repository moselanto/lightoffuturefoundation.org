<?php
/**
 * 0.78.0 Site-wide polish from the 15 Nov audit (Lighthouse accessibility + tap-target scan).
 * - Small amber labels (shop categories, shop eyebrows, product card category) used #DC8C13 on cream
 *   or white (contrast 2.5-2.7:1); now the darker brand amber #8A5208 (5:1+). Dark mode unchanged.
 * - Story pages: the "Updated ..." date in the dark hero was dark brown on dark brown; now cream.
 * - Tap targets: breadcrumb links, the cookie bar Privacy Policy link, inline text links in cards
 *   and form checkboxes get at least 24 px of tap height; slider controls sit above the tabs.
 * - Footer folding headings use a real button on phones (see main.js) styled to look the same.
 * 0.78.1: WhatsApp chat teaser link green #128C4B -> #0B6B37 (4.3:1 -> 6.5:1 on white).
 * Off switch: add_filter( 'lof_a11y_0780', '__return_false' ).
 *
 * @package LightOfFuture
 */

defined( 'ABSPATH' ) || exit;

/**
 * Print the fixes.
 */
function lof_a11y_0780_css() {
	if ( is_admin() || ! apply_filters( 'lof_a11y_0780', true ) ) {
		return;
	}
	$css = 'html:not([data-theme="dark"]) body .lof-cats__eyebrow,html:not([data-theme="dark"]) body .lof-card__cat,html:not([data-theme="dark"]) body .lof-howx__eyebrow{color:#8A5208!important}'
		. 'html body .lof-hero .lof-hero__meta{color:rgba(252,246,225,.82)!important}'
		. 'html body .lof-crumbs a{display:inline-flex;align-items:center;min-height:28px;padding-block:2px}'
		. 'html body #lof-consent a{display:inline-flex;align-items:center;min-height:28px}'
		. 'html body .lof-giftx__donate,html body .lof-text-link{display:inline-flex;align-items:center;min-height:32px}'
		. 'html body input[type="checkbox"],html body input[type="radio"]:not(.lofpay__seg input):not(.lofpay__tile input){width:20px;height:20px;min-width:20px}'
		. 'html body .lof-cine__ctrl{position:relative;z-index:4}'
		. 'html body .lof-footacc__btn{all:unset;box-sizing:border-box;display:flex;align-items:center;justify-content:space-between;gap:.75rem;width:100%;min-height:48px;cursor:pointer;font:inherit;letter-spacing:inherit;text-transform:inherit;color:inherit}'
		. 'html body .lof-footacc__btn:focus-visible{outline:3px solid var(--lof-gold);outline-offset:3px;border-radius:6px}'
		. 'html body .lof-footer__col.is-open .lof-footacc__chev{transform:rotate(180deg)}'
		. 'html:not([data-theme="dark"]) body .lof-chat__teaser-open strong{color:#0B6B37!important}';
	echo '<style id="lof-a11y-0780">' . $css . "</style>\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static CSS.
}
add_action( 'wp_head', 'lof_a11y_0780_css', 101 );
