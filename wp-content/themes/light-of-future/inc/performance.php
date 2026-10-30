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
