<?php
/**
 * Brand tokens and logo output (Logo Kit v1.0, October 2026).
 *
 * @package LightOfFuture
 */

defined( 'ABSPATH' ) || exit;

/**
 * Brand colours. Mirrored in assets/css/main.css and pushed to Elementor's
 * global colours (inc/elementor.php) so editors pick the same swatches.
 *
 * @return array<string,array{0:string,1:string}> id => [label, hex]
 */
function lof_brand_colors() {
	return apply_filters(
		'lof_brand_colors',
		array(
			'earth'  => array( __( 'Earth Brown', 'light-of-future' ), '#5C3D1E' ),
			'cream'  => array( __( 'Cream', 'light-of-future' ), '#FCF6E1' ),
			'yellow' => array( __( 'Sun Yellow', 'light-of-future' ), '#FBD62B' ),
			'gold'   => array( __( 'Sun Gold', 'light-of-future' ), '#F4AF16' ),
			'amber'  => array( __( 'Amber', 'light-of-future' ), '#DC8C13' ),
			'ink'    => array( __( 'Ink', 'light-of-future' ), '#2B1D0E' ),
		)
	);
}

/**
 * URL of a bundled brand file.
 *
 * @param string $file File name in assets/images/brand/.
 * @return string
 */
function lof_brand_url( $file ) {
	return LOF_THEME_URI . '/assets/images/brand/' . ltrim( $file, '/' );
}

/**
 * Header logo that swaps with the colour theme (light artwork on cream,
 * dark artwork on the dark theme). A Custom Logo, if uploaded, is used for both.
 */
function lof_logo_themed() {
	if ( has_custom_logo() ) {
		the_custom_logo();
		return;
	}
	printf(
		'<a class="lof-logo lof-logo--horizontal lof-logo--themed" href="%1$s" rel="home"><img class="lof-logo__light" src="%2$s" alt="%4$s" width="260" height="70" decoding="async"><img class="lof-logo__dark" src="%3$s" alt="" aria-hidden="true" width="260" height="70" decoding="async"></a>',
		esc_url( home_url( '/' ) ),
		esc_url( lof_brand_url( 'logo-horizontal-light.svg' ) ),
		esc_url( lof_brand_url( 'logo-horizontal-dark.svg' ) ),
		esc_attr( get_bloginfo( 'name' ) )
	);
}

/**
 * Print the site logo. Uses the Custom Logo when an editor uploads one,
 * otherwise the bundled Logo Kit artwork.
 *
 * @param string $variant 'light' for cream/white backgrounds, 'dark' for Earth Brown.
 * @param string $layout  'horizontal', 'compact' or 'stacked'.
 */
function lof_logo( $variant = 'light', $layout = 'horizontal' ) {
	$name = get_bloginfo( 'name' );
	if ( 'light' === $variant && has_custom_logo() ) {
		the_custom_logo();
		return;
	}
	$variant = in_array( $variant, array( 'light', 'dark' ), true ) ? $variant : 'light';
	$layout  = in_array( $layout, array( 'horizontal', 'compact', 'stacked' ), true ) ? $layout : 'horizontal';
	printf(
		'<a class="lof-logo lof-logo--%1$s" href="%2$s" rel="home"><img src="%3$s" alt="%4$s" width="%5$d" height="%6$d" decoding="async"></a>',
		esc_attr( $layout ),
		esc_url( home_url( '/' ) ),
		esc_url( lof_brand_url( 'logo-' . $layout . '-' . $variant . '.svg' ) ),
		esc_attr( $name ),
		'stacked' === $layout ? 220 : 260,
		'stacked' === $layout ? 220 : 70
	);
}
