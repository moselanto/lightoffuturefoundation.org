<?php
/**
 * 0.46.7 server hardening: deny public access to readme.html and license.txt.
 *
 * WordPress puts these files back after core updates and they reveal the WordPress version.
 * A rule in our own marked block of the site root .htaccess blocks them even when they exist.
 * WordPress core updates do not touch .htaccess; LiteSpeed Cache keeps blocks it does not own.
 *
 * - Written on admin_init (wp-admin only) when the block is missing; re-added if removed.
 * - Removed again when the plugin is deactivated.
 * - Off switch: add_filter( 'lof_core_harden_htaccess', '__return_false' ); (the block is then removed).
 *
 * @package LightOfFutureCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Marker name used for the .htaccess block.
 *
 * @return string
 */
function lof_core_harden_marker() {
	return 'Light of Future Core hardening';
}

/**
 * Rules inside the marked block.
 *
 * @return string[]
 */
function lof_core_harden_rules() {
	return array(
		'# Deny files that reveal the WordPress version (they return after core updates).',
		'<IfModule mod_rewrite.c>',
		'RewriteEngine On',
		'RewriteRule ^(readme\.html|license\.txt)$ - [F,L,NC]',
		'</IfModule>',
		'<FilesMatch "^(readme\.html|license\.txt)$">',
		'<IfModule mod_authz_core.c>',
		'Require all denied',
		'</IfModule>',
		'<IfModule !mod_authz_core.c>',
		'Order allow,deny',
		'Deny from all',
		'</IfModule>',
		'</FilesMatch>',
	);
}

/**
 * Path of the site root .htaccess.
 *
 * @return string
 */
function lof_core_harden_htaccess_path() {
	if ( ! function_exists( 'get_home_path' ) ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
	}
	return trailingslashit( get_home_path() ) . '.htaccess';
}

/**
 * Write (or remove) the marked block.
 *
 * @param bool $enable True to write the rules, false to empty the block.
 * @return bool True when the file was written.
 */
function lof_core_harden_write( $enable ) {
	if ( ! function_exists( 'insert_with_markers' ) ) {
		require_once ABSPATH . 'wp-admin/includes/misc.php';
	}
	$path = lof_core_harden_htaccess_path();
	if ( file_exists( $path ) ? ! is_writable( $path ) : ! is_writable( dirname( $path ) ) ) { // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_is_writable
		return false;
	}
	return (bool) insert_with_markers( $path, lof_core_harden_marker(), $enable ? lof_core_harden_rules() : array() );
}

/**
 * Add the block when it is missing (cheap check on every wp-admin load).
 */
function lof_core_harden_maybe_write() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$enable = (bool) apply_filters( 'lof_core_harden_htaccess', true );
	$path   = lof_core_harden_htaccess_path();
	$raw    = file_exists( $path ) ? (string) file_get_contents( $path ) : ''; // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	$has    = false !== strpos( $raw, 'RewriteRule ^(readme\.html|license\.txt)$' );
	if ( $enable === $has ) {
		return;
	}
	$ok = lof_core_harden_write( $enable );
	update_option( 'lof_core_harden_0467', $ok ? ( $enable ? 'written' : 'removed' ) : 'not-writable', false );
}
add_action( 'admin_init', 'lof_core_harden_maybe_write' );

/**
 * Remove the block when the plugin is deactivated.
 */
function lof_core_harden_deactivate() {
	lof_core_harden_write( false );
	delete_option( 'lof_core_harden_0467' );
}
