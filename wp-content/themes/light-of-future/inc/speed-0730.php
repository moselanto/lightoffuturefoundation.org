<?php
/**
 * 0.73.0 Speed for every inner page (Home already has this since 0.62.0, see speed-062.php).
 *
 * The styles needed for the first screen are printed in the head and the three large
 * stylesheets (Elementor frontend, sections.min.css, shop.min.css) load without blocking
 * the first paint (media="print" swapped to "all" on load, <noscript> fallback).
 *
 * First-screen files in assets/css/critical/:
 *   inner-*.css : pages, programmes, stories, partners, legal pages, 404.
 *   shop-*.css  : Shop, product pages and product categories.
 * They were extracted on 14 Nov 2026 from the live site (17 inner pages and 5 shop pages,
 * widths 320 to 1920, first screen plus 15%). The full stylesheets still load, so a stale
 * copy only affects the first paint. Regenerate after large design changes.
 *
 * Not used on Cart, Checkout and My account (they keep normal blocking stylesheets),
 * in wp-admin, in the Elementor preview, with SCRIPT_DEBUG, or in the Customizer.
 * Off switch: add_filter( 'lof_inner_critical', '__return_false' );
 *
 * @package LightOfFuture
 */

defined( 'ABSPATH' ) || exit;

/**
 * Which first-screen set applies ('inner', 'shop' or '' for none).
 *
 * @return string
 */
function lof_inner_critical_group() {
	static $group = null;
	if ( null !== $group ) {
		return $group;
	}
	$group = '';
	if ( is_admin() || is_front_page() || is_feed() || isset( $_GET['elementor-preview'] ) || is_customize_preview() || ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return $group;
	}
	if ( function_exists( 'is_cart' ) && ( is_cart() || is_checkout() || is_account_page() ) ) {
		return $group;
	}
	if ( ! apply_filters( 'lof_inner_critical', true ) ) {
		return $group;
	}
	$group = ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) ? 'shop' : 'inner';
	return $group;
}

/**
 * Stylesheet handle => file suffix.
 *
 * @return array
 */
function lof_inner_critical_map() {
	return array(
		'elementor-frontend' => 'elementor',
		'lof-sections'       => 'sections',
		'lof-shop'           => 'shop',
	);
}

/**
 * Print the first-screen styles in place of each large stylesheet; load the full file without blocking.
 *
 * @param string $tag    Link tag.
 * @param string $handle Handle.
 * @param string $href   URL.
 * @return string
 */
function lof_inner_critical_tag( $tag, $handle, $href ) {
	$map = lof_inner_critical_map();
	if ( ! isset( $map[ $handle ] ) || false === strpos( $tag, '<link' ) ) {
		return $tag;
	}
	$group = lof_inner_critical_group();
	if ( '' === $group ) {
		return $tag;
	}
	$file = LOF_THEME_DIR . '/assets/css/critical/' . $group . '-' . $map[ $handle ] . '.css';
	if ( ! is_readable( $file ) ) {
		return $tag;
	}
	$css = (string) file_get_contents( $file ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	// Relative image paths in the theme stylesheets point to assets/images/.
	$css = str_replace( array( 'url("../images/', "url('../images/", 'url(../images/' ), array( 'url("' . LOF_THEME_URI . '/assets/images/', "url('" . LOF_THEME_URI . '/assets/images/', 'url(' . LOF_THEME_URI . '/assets/images/' ), $css );
	$css = str_ireplace( '</style', '<\/style', $css );
	$url = esc_url( $href );
	$out  = '<style id="' . esc_attr( $handle ) . '-critical">' . $css . "</style>\n";
	$out .= '<link rel="stylesheet" id="' . esc_attr( $handle ) . '-css" href="' . $url . '" media="print" onload="this.media=\'all\';this.onload=null">' . "\n";
	$out .= '<noscript><link rel="stylesheet" href="' . $url . '"></noscript>' . "\n";
	return $out;
}
add_filter( 'style_loader_tag', 'lof_inner_critical_tag', 20, 3 );
