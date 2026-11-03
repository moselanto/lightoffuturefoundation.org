<?php
/**
 * Performance: compression and browser caching rules for Apache, written
 * between "Light of Future performance" markers in .htaccess (the same way
 * WordPress writes its own rules). Run from the setup screen. Disable with
 * add_filter( 'lof_write_htaccess', '__return_false' ).
 *
 * @package LightOfFuture
 */

defined( 'ABSPATH' ) || exit;

/**
 * Rules: gzip for text formats, one-year caching for versioned static files.
 *
 * @return string[]
 */
function lof_htaccess_rules() {
	return array(
		'<IfModule mod_deflate.c>',
		'AddOutputFilterByType DEFLATE text/html text/plain text/css text/xml text/javascript application/javascript application/x-javascript application/json application/xml application/rss+xml image/svg+xml',
		'</IfModule>',
		'<IfModule mod_expires.c>',
		'ExpiresActive On',
		'ExpiresByType text/css "access plus 1 year"',
		'ExpiresByType application/javascript "access plus 1 year"',
		'ExpiresByType text/javascript "access plus 1 year"',
		'ExpiresByType image/webp "access plus 1 year"',
		'ExpiresByType image/jpeg "access plus 1 year"',
		'ExpiresByType image/png "access plus 1 year"',
		'ExpiresByType image/svg+xml "access plus 1 year"',
		'ExpiresByType image/x-icon "access plus 1 year"',
		'ExpiresByType font/woff2 "access plus 1 year"',
		'</IfModule>',
		'<IfModule mod_headers.c>',
		'<FilesMatch "\\.(css|js|woff2|webp|jpe?g|png|svg|ico)$">',
		'Header set Cache-Control "public, max-age=31536000"',
		'</FilesMatch>',
		'</IfModule>',
		'AddType font/woff2 .woff2',
	);
}

/**
 * Write the rules. Returns a report line.
 *
 * @return string[]
 */
function lof_write_performance_rules() {
	if ( ! apply_filters( 'lof_write_htaccess', true ) || ! current_user_can( 'manage_options' ) ) {
		return array();
	}
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/misc.php';
	$file = get_home_path() . '.htaccess';
	if ( ( file_exists( $file ) && ! wp_is_writable( $file ) ) || ( ! file_exists( $file ) && ! wp_is_writable( get_home_path() ) ) ) {
		return array( __( 'Could not add speed rules: .htaccess is not writable (ask the host to enable compression and caching).', 'light-of-future' ) );
	}
	$ok = insert_with_markers( $file, 'Light of Future performance', lof_htaccess_rules() );
	return array( $ok ? __( 'Speed rules added: compression and browser caching.', 'light-of-future' ) : __( 'Could not add speed rules to .htaccess.', 'light-of-future' ) );
}

/**
 * Lighter front end: no emoji script/styles (not used by the design).
 */
function lof_trim_head() {
	if ( ! apply_filters( 'lof_trim_head', true ) ) {
		return;
	}
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
}
add_action( 'init', 'lof_trim_head' );

/**
 * Meta description and theme colour (audit, 3 Nov 2026). Skipped when an SEO plugin handles the head.
 */
function lof_meta_description() {
	if ( defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'AIOSEO_VERSION' ) || defined( 'SEOPRESS_VERSION' ) ) {
		return;
	}
	$desc = '';
	if ( is_singular() && ! is_front_page() ) {
		$desc = has_excerpt() ? get_the_excerpt() : '';
	}
	if ( '' === $desc ) {
		$desc = __( 'Light of Future Foundation is a registered NGO in Nairobi, Kenya, supporting widows, widowers, orphans, vulnerable children and people living with disabilities through education, clean water and community programmes.', 'light-of-future' );
	}
	printf( '<meta name="description" content="%s">' . "\n", esc_attr( wp_trim_words( wp_strip_all_tags( $desc ), 32, '' ) ) );
	echo '<meta name="theme-color" content="#5C3D1E">' . "\n";
}
add_action( 'wp_head', 'lof_meta_description', 2 );

/**
 * jQuery Migrate is only for old plugins; nothing on this site needs it (audit, 3 Nov 2026).
 *
 * @param WP_Scripts $scripts Scripts.
 */
function lof_drop_jquery_migrate( $scripts ) {
	if ( is_admin() || ! isset( $scripts->registered['jquery'] ) || ( isset( $_GET['elementor-preview'] ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return;
	}
	$scripts->registered['jquery']->deps = array_diff( $scripts->registered['jquery']->deps, array( 'jquery-migrate' ) );
}
add_action( 'wp_default_scripts', 'lof_drop_jquery_migrate' );
