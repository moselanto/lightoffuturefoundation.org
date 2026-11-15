<?php
/**
 * 0.79.0 Shop: the first hero mosaic photo is the largest thing on a phone screen. It is now
 * preloaded from the head (phone size first) and is the only mosaic photo with high priority,
 * so it no longer waits behind the stylesheets and the other two first-row tiles.
 * Off switch: add_filter( 'lof_shop_hero_preload', '__return_false' ).
 *
 * @package LightOfFuture
 */

defined( 'ABSPATH' ) || exit;

/**
 * Preload the first Shop hero tile.
 */
function lof_shop_hero_preload() {
	if ( is_admin() || ! function_exists( 'is_shop' ) || ! is_shop() || ! apply_filters( 'lof_shop_hero_preload', true ) ) {
		return;
	}
	$img = LOF_THEME_URI . '/assets/images/shop/';
	printf(
		'<link rel="preload" as="image" href="%1$s" imagesrcset="%1$s 320w, %2$s 900w" imagesizes="(max-width: 767px) 34vw, 22vw" fetchpriority="high">' . "\n",
		esc_url( $img . 'sm/bags-collection.webp' ),
		esc_url( $img . 'bags-collection.webp' )
	);
}
add_action( 'wp_head', 'lof_shop_hero_preload', 1 );
