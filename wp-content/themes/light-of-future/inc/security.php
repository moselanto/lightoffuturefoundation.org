<?php
/**
 * Baseline hardening. Each feature has an off-switch filter.
 *
 * @package LightOfFuture
 */

defined( 'ABSPATH' ) || exit;

if ( apply_filters( 'lof_disable_xmlrpc', true ) ) {
	add_filter( 'xmlrpc_enabled', '__return_false' );
}

remove_action( 'wp_head', 'wp_generator' );

/**
 * Security headers (payment-compatible: no strict CSP until gateways are chosen).
 */
function lof_security_headers() {
	if ( is_admin() || headers_sent() || ! apply_filters( 'lof_security_headers', true ) ) {
		return;
	}
	header( 'X-Content-Type-Options: nosniff' );
	header( 'X-Frame-Options: SAMEORIGIN' );
	header( 'Referrer-Policy: strict-origin-when-cross-origin' );
	header( 'Permissions-Policy: camera=(), microphone=(), geolocation=()' );
}
add_action( 'send_headers', 'lof_security_headers' );

/**
 * Block ?author=N user enumeration for visitors.
 */
function lof_block_author_enum() {
	if ( ! is_admin() && isset( $_GET['author'] ) && ! current_user_can( 'list_users' ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		wp_safe_redirect( home_url( '/' ), 301 );
		exit;
	}
}
add_action( 'template_redirect', 'lof_block_author_enum', 1 );

/**
 * Hide user endpoints from the public REST API.
 *
 * @param array $endpoints Endpoints.
 * @return array
 */
function lof_rest_users( $endpoints ) {
	if ( ! current_user_can( 'list_users' ) ) {
		unset( $endpoints['/wp/v2/users'], $endpoints['/wp/v2/users/(?P<id>[\\d]+)'] );
	}
	return $endpoints;
}
add_filter( 'rest_endpoints', 'lof_rest_users' );
