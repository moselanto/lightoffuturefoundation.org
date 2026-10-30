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
 * Apply the saved or device colour theme before the page paints (no flash).
 */
function lof_theme_mode_script() {
	echo '<meta name="color-scheme" content="light dark">' . "\n";
	echo "<script>(function(){try{var q=(location.search.match(/[?&]lof_theme=(light|dark)/)||[])[1];var t=q||localStorage.getItem('lof-theme');if(t!=='light'&&t!=='dark'){t=window.matchMedia&&window.matchMedia('(prefers-color-scheme: dark)').matches?'dark':'light';}document.documentElement.setAttribute('data-theme',t);}catch(e){document.documentElement.setAttribute('data-theme','light');}})();</script>\n";
}
add_action( 'wp_head', 'lof_theme_mode_script', 0 );

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

/**
 * Phone-width preview: /?lof_mobile_preview=light (or dark) shows each main page
 * in a 390px frame, so layouts can be checked from a desktop browser.
 */
function lof_mobile_preview() {
	if ( ! isset( $_GET['lof_mobile_preview'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return;
	}
	$mode  = 'dark' === sanitize_key( wp_unslash( $_GET['lof_mobile_preview'] ) ) ? 'dark' : 'light'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$only  = isset( $_GET['page'] ) ? sanitize_title( wp_unslash( $_GET['page'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$pages = array( '' => 'Home', 'about-us' => 'About', 'programmes' => 'Programmes', 'impact' => 'Impact', 'stories' => 'Stories', 'donate' => 'Donate', 'get-involved' => 'Get Involved', 'contact' => 'Contact', 'leadership' => 'Leadership' );
	header( 'X-Robots-Tag: noindex, nofollow' );
	nocache_headers();
	echo '<!doctype html><html><head><meta charset="utf-8"><meta name="robots" content="noindex,nofollow"><title>Phone preview (' . esc_html( $mode ) . ')</title>';
	echo '<style>body{margin:0;padding:20px;background:#555;font:14px sans-serif;color:#fff}.w{display:flex;flex-wrap:wrap;gap:24px}.f{width:390px}.f h2{margin:0 0 6px;font-size:15px}.f h2 a{color:#fff}iframe{width:390px;height:844px;border:0;background:#fff;border-radius:18px}</style></head><body><div class="w">';
	foreach ( $pages as $slug => $name ) {
		if ( $only && $only !== ( $slug ? $slug : 'home' ) ) {
			continue;
		}
		$url = add_query_arg( 'lof_theme', $mode, home_url( $slug ? '/' . $slug . '/' : '/' ) );
		echo '<div class="f"><h2><a href="' . esc_url( add_query_arg( array( 'lof_mobile_preview' => $mode, 'page' => $slug ? $slug : 'home' ), home_url( '/' ) ) ) . '">' . esc_html( $name ) . '</a></h2><iframe loading="lazy" src="' . esc_url( $url ) . '" title="' . esc_attr( $name ) . '"></iframe></div>';
	}
	echo '</div></body></html>';
	exit;
}
add_action( 'template_redirect', 'lof_mobile_preview', 0 );
