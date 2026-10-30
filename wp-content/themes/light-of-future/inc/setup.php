<?php
/**
 * Theme setup: supports, menus, assets, fonts and icons.
 *
 * @package LightOfFuture
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register theme supports and menu locations.
 */
function lof_theme_setup() {
	load_child_theme_textdomain( 'light-of-future', LOF_THEME_DIR . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo', array( 'height' => 120, 'width' => 480, 'flex-height' => true, 'flex-width' => true ) );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	register_nav_menus(
		array(
			'lof-primary' => __( 'Main menu', 'light-of-future' ),
			'lof-footer'  => __( 'Footer quick links', 'light-of-future' ),
			'lof-legal'   => __( 'Footer legal links', 'light-of-future' ),
		)
	);

	add_image_size( 'lof-card', 720, 540, true );
	add_image_size( 'lof-portrait', 600, 750, true );
	add_image_size( 'lof-hero', 1920, 1080, true );
}
add_action( 'after_setup_theme', 'lof_theme_setup', 20 );

/**
 * Enqueue styles and scripts. The parent stylesheet is light; ours is versioned.
 */
function lof_enqueue_assets() {
	wp_enqueue_style( 'lof-main', LOF_THEME_URI . '/assets/css/main.css', array(), LOF_THEME_VERSION );
	wp_enqueue_style( 'lof-sections', LOF_THEME_URI . '/assets/css/sections.css', array( 'lof-main' ), LOF_THEME_VERSION );
	wp_enqueue_script( 'lof-main', LOF_THEME_URI . '/assets/js/main.js', array(), LOF_THEME_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
}
add_action( 'wp_enqueue_scripts', 'lof_enqueue_assets', 20 );

/**
 * Preload the two self-hosted brand fonts (Cinzel and Montserrat, SIL OFL).
 */
function lof_preload_fonts() {
	foreach ( array( 'montserrat-variable', 'cinzel-variable' ) as $font ) {
		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
			esc_url( LOF_THEME_URI . '/assets/fonts/' . $font . '.woff2' )
		);
	}
}
add_action( 'wp_head', 'lof_preload_fonts', 1 );

/**
 * Favicons and app icons from the Logo Kit, used only when no Site Icon is set.
 */
function lof_icons() {
	if ( has_site_icon() ) {
		return;
	}
	$base = LOF_THEME_URI . '/assets/images/icons/';
	printf( '<link rel="icon" href="%s" sizes="any">' . "\n", esc_url( $base . 'favicon.ico' ) );
	printf( '<link rel="icon" href="%s" type="image/svg+xml">' . "\n", esc_url( $base . 'favicon.svg' ) );
	printf( '<link rel="apple-touch-icon" href="%s">' . "\n", esc_url( $base . 'apple-touch-icon.png' ) );
	echo '<meta name="theme-color" content="#5C3D1E">' . "\n";
}
add_action( 'wp_head', 'lof_icons', 2 );
