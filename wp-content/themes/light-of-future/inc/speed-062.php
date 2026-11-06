<?php
/**
 * 0.62.0 Home speed: the styles needed for the first screen are printed in the head,
 * the full Elementor, sections and shop stylesheets load without blocking the first paint,
 * and jQuery with every script after it is deferred.
 * Turn off with add_filter( 'lof_home_critical', '__return_false' ).
 *
 * The first-screen styles in assets/css/critical/ were taken from the live Home page at
 * widths 360 to 2560. Regenerate them after large Home design changes (the full stylesheets
 * still load, so stale copies only affect the first paint).
 *
 * @package LightOfFuture
 */

defined( 'ABSPATH' ) || exit;

/**
 * Whether the Home speed mode applies to this request.
 *
 * @return bool
 */
function lof_home_critical_on() {
	if ( is_admin() || ! is_front_page() || isset( $_GET['elementor-preview'] ) || is_customize_preview() || ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return false;
	}
	return (bool) apply_filters( 'lof_home_critical', true );
}

/**
 * Stylesheet handle => first-screen file.
 *
 * @return array
 */
function lof_home_critical_map() {
	return array(
		'elementor-frontend' => 'home-elementor',
		'lof-sections'       => 'home-sections',
		'lof-shop'           => 'home-shop',
	);
}

/**
 * Print first-screen styles in place of each large stylesheet and load the full file without blocking.
 *
 * @param string $tag    Link tag.
 * @param string $handle Handle.
 * @param string $href   URL.
 * @return string
 */
function lof_home_critical_tag( $tag, $handle, $href ) {
	$map = lof_home_critical_map();
	if ( ! isset( $map[ $handle ] ) || false === strpos( $tag, '<link' ) || ! lof_home_critical_on() ) {
		return $tag;
	}
	$file = LOF_THEME_DIR . '/assets/css/critical/' . $map[ $handle ] . '.css';
	if ( ! is_readable( $file ) ) {
		return $tag;
	}
	$css = (string) file_get_contents( $file ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	$id  = esc_attr( $handle ) . '-css';
	$url = esc_url( $href );
	$out  = '<style id="' . esc_attr( $handle ) . '-critical">' . $css . "</style>\n";
	$out .= '<link rel="preload" as="style" href="' . $url . '">' . "\n";
	$out .= '<link rel="stylesheet" id="' . $id . '" href="' . $url . '" media="print" onload="this.media=\'all\';this.onload=null">' . "\n";
	$out .= '<noscript><link rel="stylesheet" href="' . $url . '"></noscript>' . "\n";
	return $out;
}
add_filter( 'style_loader_tag', 'lof_home_critical_tag', 20, 3 );

/**
 * Defer jQuery and every classic script on Home (document order is kept, so dependencies still run in order).
 * Inline scripts that use jQuery are turned into deferred scripts so they run after it.
 *
 * @param string $html Page.
 * @return string
 */
function lof_home_defer_scripts( $html ) {
	if ( ! is_string( $html ) || false === stripos( $html, '</html>' ) || ! lof_home_critical_on() ) {
		return $html;
	}
	return preg_replace_callback(
		'#<script\b([^>]*)>(.*?)</script>#is',
		function ( $m ) {
			$attrs = $m[1];
			if ( preg_match( '/\stype=["\'](?!text\/javascript|application\/javascript)[^"\']+["\']/i', $attrs ) ) {
				return $m[0];
			}
			if ( preg_match( '/\ssrc=/i', $attrs ) ) {
				if ( preg_match( '/\s(defer|async)(\s|=|$)/i', $attrs ) ) {
					return $m[0];
				}
				return '<script defer' . $attrs . '>' . $m[2] . '</script>';
			}
			if ( preg_match( '/\bjQuery\b|\$\(/', $m[2] ) ) {
				return '<script defer' . $attrs . ' src="data:text/javascript;base64,' . base64_encode( $m[2] ) . '"></script>'; // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
			}
			return $m[0];
		},
		$html
	);
}

/**
 * Start the buffer after the 0.59 image buffer so both rewrites run.
 */
function lof_home_defer_buffer() {
	if ( lof_home_critical_on() && ! wp_doing_ajax() && ! is_feed() ) {
		ob_start( 'lof_home_defer_scripts' );
	}
}
add_action( 'template_redirect', 'lof_home_defer_buffer', 2 );
