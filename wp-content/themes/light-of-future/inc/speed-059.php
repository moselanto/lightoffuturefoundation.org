<?php
/**
 * 0.59.0 speed and indexing.
 *
 * @package LightOfFuture
 */

defined( 'ABSPATH' ) || exit;

/**
 * Search engines: the live site must never be hidden by the "Discourage search engines" setting.
 * Turn off with add_filter( 'lof_force_indexable', '__return_false' ).
 */
function lof_force_indexable( $value ) {
	$host = wp_parse_url( home_url(), PHP_URL_HOST );
	if ( apply_filters( 'lof_force_indexable', 'lightoffuturefoundation.org' === $host || 'www.lightoffuturefoundation.org' === $host ) ) {
		return '1';
	}
	return $value;
}
add_filter( 'option_blog_public', 'lof_force_indexable' );

/**
 * WooCommerce styles only where WooCommerce output appears.
 *
 * @param array $styles Styles.
 * @return array
 */
function lof_woo_styles_where_needed( $styles ) {
	if ( function_exists( 'is_woocommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
		return $styles;
	}
	return array();
}
add_filter( 'woocommerce_enqueue_styles', 'lof_woo_styles_where_needed' );

/**
 * Block styles are only needed on blog posts.
 */
function lof_drop_block_styles() {
	if ( is_admin() || is_singular( 'post' ) || isset( $_GET['elementor-preview'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return;
	}
	foreach ( array( 'wp-block-library', 'wp-block-library-theme', 'wc-blocks-style', 'classic-theme-styles', 'global-styles' ) as $h ) {
		wp_dequeue_style( $h );
	}
}
add_action( 'wp_enqueue_scripts', 'lof_drop_block_styles', 200 );

/**
 * Minified copies of the two large theme stylesheets.
 *
 * @param string $src    Source.
 * @param string $handle Handle.
 * @return string
 */
function lof_min_css_src( $src, $handle ) {
	if ( ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) || ! in_array( $handle, array( 'lof-sections', 'lof-shop', 'lof-main' ), true ) ) {
		return $src;
	}
	$min = preg_replace( '/\.css(\?|$)/', '.min.css$1', $src );
	$rel = preg_replace( '/\?.*$/', '', str_replace( LOF_THEME_URI, '', $min ) );
	return file_exists( LOF_THEME_DIR . $rel ) ? $min : $src;
}
add_filter( 'style_loader_src', 'lof_min_css_src', 10, 2 );

/**
 * Small stylesheets are printed inline in the head (fewer blocking requests before the first paint).
 *
 * @param string $tag    Link tag.
 * @param string $handle Handle.
 * @param string $href   URL.
 * @return string
 */
function lof_inline_small_css( $tag, $handle, $href ) {
	if ( is_admin() || isset( $_GET['elementor-preview'] ) || ! apply_filters( 'lof_inline_small_css', true ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return $tag;
	}
	$ok = in_array( $handle, array( 'lofm', 'hello-elementor', 'hello-elementor-theme-style', 'hello-elementor-header-footer', 'lof-main', 'base-desktop', 'base-mobile' ), true ) || 0 === strpos( $handle, 'elementor-post-' );
	if ( ! $ok || false !== strpos( $tag, 'media=\'print\'' ) ) {
		return $tag;
	}
	$url  = preg_replace( '/\?.*$/', '', $href );
	$base = content_url();
	if ( 0 === strpos( $url, '//' ) ) {
		$url = ( is_ssl() ? 'https:' : 'http:' ) . $url;
	}
	if ( 0 !== strpos( $url, $base ) ) {
		return $tag;
	}
	$file = WP_CONTENT_DIR . substr( $url, strlen( $base ) );
	if ( ! is_readable( $file ) || filesize( $file ) > 24000 ) {
		return $tag;
	}
	$key = 'lof_ic_' . md5( $file . filemtime( $file ) );
	$css = get_transient( $key );
	if ( false === $css ) {
		$css = (string) file_get_contents( $file ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		$dir = trailingslashit( dirname( $url ) );
		$css = preg_replace_callback(
			'/url\(\s*([\'"]?)([^\'")]+)\1\s*\)/i',
			function ( $m ) use ( $dir ) {
				$u = trim( $m[2] );
				if ( preg_match( '#^(data:|https?:|//|/|\#)#i', $u ) ) {
					return $m[0];
				}
				return 'url("' . $dir . $u . '")';
			},
			$css
		);
		$css = preg_replace( '#/\*.*?\*/#s', '', $css );
		$css = preg_replace( '/\s+/', ' ', $css );
		$css = str_replace( '</style', '<\/style', $css );
		set_transient( $key, $css, WEEK_IN_SECONDS );
	}
	$media = preg_match( "/media='([^']+)'/", $tag, $mm ) ? $mm[1] : 'all';
	return '<style id="' . esc_attr( $handle ) . '-css"' . ( 'all' !== $media ? ' media="' . esc_attr( $media ) . '"' : '' ) . '>' . $css . "</style>\n";
}
add_filter( 'style_loader_tag', 'lof_inline_small_css', 10, 3 );

/**
 * Start the page-hero photo download before the stylesheets.
 */
function lof_preload_page_hero() {
	if ( is_front_page() || ! is_page() || ! function_exists( 'lof_page_hero_side_photo' ) ) {
		return;
	}
	$id = lof_page_hero_side_photo();
	if ( ! $id ) {
		return;
	}
	$src    = wp_get_attachment_image_url( $id, 'full' );
	$srcset = wp_get_attachment_image_srcset( $id, 'full' );
	if ( ! $src ) {
		return;
	}
	printf( '<link rel="preload" as="image" href="%1$s"%2$s imagesizes="100vw" fetchpriority="high">' . "\n", esc_url( $src ), $srcset ? ' imagesrcset="' . esc_attr( $srcset ) . '"' : '' );
}
add_action( 'wp_head', 'lof_preload_page_hero', 1 );

/**
 * Smaller images on the Gallery, Shop and Home pages (rewritten once, then cached by LiteSpeed).
 */
function lof_speed_buffer() {
	if ( is_admin() || wp_doing_ajax() || is_feed() || isset( $_GET['elementor-preview'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return;
	}
	ob_start( 'lof_speed_rewrite' );
}
add_action( 'template_redirect', 'lof_speed_buffer', 1 );

/**
 * Rewrite image markup.
 *
 * @param string $html Page.
 * @return string
 */
function lof_speed_rewrite( $html ) {
	if ( ! is_string( $html ) || false === stripos( $html, '</html>' ) ) {
		return $html;
	}
	$dir = LOF_THEME_DIR . '/assets/images/';
	$uri = LOF_THEME_URI . '/assets/images/';
	// Gallery videos: small poster, loaded only when the video comes near the screen.
	$html = preg_replace_callback(
		'#<div class="lofm__frame" style="aspect-ratio:(\d+)/(\d+);background-image:url\(([^)]+?/video/([a-z0-9-]+)\.jpg)\)"><video ([^>]*?)poster="[^"]+"#',
		function ( $m ) use ( $dir, $uri ) {
			$p = file_exists( $dir . 'gallery/p/' . $m[4] . '.webp' ) ? $uri . 'gallery/p/' . $m[4] . '.webp' : $m[3];
			return '<div class="lofm__frame" style="aspect-ratio:' . $m[1] . '/' . $m[2] . '" data-lof-poster="' . esc_url( $p ) . '"><video ' . $m[5] . 'data-poster="' . esc_url( $p ) . '"';
		},
		$html
	);
	// Gallery photos: 400px thumbnails with the original as the larger option.
	$html = preg_replace_callback(
		'#<img ([^>]*?)src="([^"]+/light-of-future-media/assets/photos/([a-z0-9-]+)-sm\.webp)"#',
		function ( $m ) use ( $dir, $uri ) {
			if ( false !== strpos( $m[1], 'srcset' ) || ! file_exists( $dir . 'gallery/t/' . $m[3] . '.webp' ) ) {
				return $m[0];
			}
			$t = $uri . 'gallery/t/' . $m[3] . '.webp';
			return '<img ' . $m[1] . 'src="' . esc_url( $t ) . '" srcset="' . esc_url( $t ) . ' 400w, ' . esc_url( $m[2] ) . ' 560w" sizes="(max-width: 700px) 46vw, 300px"';
		},
		$html
	);
	// Shop artwork (hero mosaic and other theme shop photos): 320px copies with the original as the larger option.
	$html = preg_replace_callback(
		'#<img ([^>]*?)src="(' . preg_quote( $uri, '#' ) . 'shop/([a-z0-9-]+)\.webp)"#',
		function ( $m ) use ( $dir, $uri ) {
			if ( false !== strpos( $m[1], 'srcset' ) || ! file_exists( $dir . 'shop/sm/' . $m[3] . '.webp' ) ) {
				return $m[0];
			}
			$s = $uri . 'shop/sm/' . $m[3] . '.webp';
			return '<img ' . $m[1] . 'src="' . esc_url( $s ) . '" srcset="' . esc_url( $s ) . ' 320w, ' . esc_url( $m[2] ) . ' 900w" sizes="(max-width: 700px) 30vw, 260px"';
		},
		$html
	);
	// Founder photo.
	$html = str_replace(
		'src="' . $uri . 'photos/founder-francis-ouma-sikuku.webp"',
		'src="' . $uri . 'photos/founder-francis-ouma-sikuku-420.webp" srcset="' . $uri . 'photos/founder-francis-ouma-sikuku-420.webp 420w, ' . $uri . 'photos/founder-francis-ouma-sikuku.webp 720w" sizes="(max-width: 700px) 80vw, 420px"',
		$html
	);
	return $html;
}

/**
 * Shop product photos: ask for the size actually shown.
 *
 * @param array $attr Attributes.
 * @return array
 */
function lof_product_thumb_sizes( $attr ) {
	if ( isset( $attr['class'] ) && false !== strpos( $attr['class'], 'size-woocommerce_thumbnail' ) ) {
		$attr['sizes'] = '(max-width: 700px) 42vw, 260px';
	}
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'lof_product_thumb_sizes', 20 );
