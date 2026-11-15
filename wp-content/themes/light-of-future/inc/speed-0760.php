<?php
/**
 * 0.76.0 Layout shift from web fonts: Nunito and Figtree load with font-display:swap, so the text
 * first draws in a fallback font and moves when the brand fonts arrive (Home desktop: the slider
 * text block, up to 0.13 CLS). This prints metric-matched fallback faces (Arial/Liberation/Helvetica,
 * Roboto for Android, DejaVu Sans for Linux) sized to the brand fonts, and puts them straight after
 * the brand fonts in --lof-font-display and --lof-font-body, so the swap barely moves anything.
 * Values: assets/css/font-fallback.css (size-adjust from average letter width, ascent/descent from
 * the font files). Off switch: add_filter( 'lof_font_fallback', '__return_false' ).
 *
 * @package LightOfFuture
 */

defined( 'ABSPATH' ) || exit;

/**
 * Print the fallback faces in the head, after the theme stylesheets.
 */
function lof_font_fallback_print() {
	if ( is_admin() || ! apply_filters( 'lof_font_fallback', true ) ) {
		return;
	}
	$file = LOF_THEME_DIR . '/assets/css/font-fallback.css';
	if ( ! is_readable( $file ) ) {
		return;
	}
	echo '<style id="lof-font-fallback">' . trim( (string) file_get_contents( $file ) ) . "</style>\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped, WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- static theme CSS.
}
add_action( 'wp_head', 'lof_font_fallback_print', 99 );
