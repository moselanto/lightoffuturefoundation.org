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


/* ------------------------------------------------------------------ */
/* 0.54.0 hardening                                                    */
/* ------------------------------------------------------------------ */

/**
 * Do not advertise the PHP version; force HTTPS for a year once a visitor has used it.
 */
function lof_security_headers_more() {
	if ( headers_sent() ) {
		return;
	}
	if ( function_exists( 'header_remove' ) ) {
		header_remove( 'X-Powered-By' );
		header_remove( 'X-Pingback' );
	}
	if ( is_ssl() && apply_filters( 'lof_hsts', true ) ) {
		header( 'Strict-Transport-Security: max-age=31536000' );
	}
}
add_action( 'send_headers', 'lof_security_headers_more', 20 );

/* No pingbacks or trackbacks (a common spam and DDoS route). */
add_filter( 'pings_open', '__return_false', 20 );
add_filter(
	'wp_headers',
	function ( $headers ) {
		unset( $headers['X-Pingback'] );
		return $headers;
	}
);

/**
 * No theme or plugin file editor in wp-admin: a stolen login cannot be
 * turned into code on the server.
 *
 * @param string[] $caps Primitive caps.
 * @param string   $cap  Requested cap.
 * @return string[]
 */
function lof_no_file_editor( $caps, $cap ) {
	if ( in_array( $cap, array( 'edit_themes', 'edit_plugins', 'edit_files' ), true ) && apply_filters( 'lof_block_file_editor', true ) ) {
		return array( 'do_not_allow' );
	}
	return $caps;
}
add_filter( 'map_meta_cap', 'lof_no_file_editor', 10, 2 );

/**
 * Same message for a wrong username or a wrong password (no account fishing).
 *
 * @return string
 */
function lof_login_error() {
	return __( 'The login details are not correct. Please try again.', 'light-of-future' );
}
add_filter( 'login_errors', 'lof_login_error' );

/**
 * Visitor IP for throttling (REMOTE_ADDR only: forwarded headers can be faked).
 *
 * @return string
 */
function lof_client_ip() {
	return isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '0';
}

/**
 * Count failed logins per IP.
 */
function lof_login_failed() {
	$key = 'lof_lf_' . md5( lof_client_ip() );
	$n   = (int) get_transient( $key );
	set_transient( $key, $n + 1, 15 * MINUTE_IN_SECONDS );
}
add_action( 'wp_login_failed', 'lof_login_failed' );

/**
 * After 5 failed logins from one IP, refuse logins from it for 15 minutes.
 *
 * @param WP_User|WP_Error|null $user User.
 * @return WP_User|WP_Error|null
 */
function lof_login_throttle( $user ) {
	$limit = (int) apply_filters( 'lof_login_attempts', 5 );
	if ( (int) get_transient( 'lof_lf_' . md5( lof_client_ip() ) ) >= $limit ) {
		return new WP_Error( 'lof_locked', __( 'Too many failed login attempts. Please wait 15 minutes and try again.', 'light-of-future' ) );
	}
	return $user;
}
add_filter( 'authenticate', 'lof_login_throttle', 1 );

/**
 * Clear the counter after a good login.
 */
function lof_login_ok() {
	delete_transient( 'lof_lf_' . md5( lof_client_ip() ) );
}
add_action( 'wp_login', 'lof_login_ok' );

/**
 * Reviews and comments: hidden honeypot field and a signed time stamp.
 */
function lof_comment_trap() {
	$t = time();
	echo '<p class="lof-hp" aria-hidden="true" style="position:absolute;left:-9999px;width:1px;height:1px;overflow:hidden"><label>' . esc_html__( 'Leave this field empty', 'light-of-future' ) . '<input type="text" name="lof_website" value="" tabindex="-1" autocomplete="off"></label></p>';
	echo '<input type="hidden" name="lof_ts" value="' . esc_attr( $t . '.' . wp_hash( 'lof_ts' . $t ) ) . '">';
}
add_action( 'comment_form', 'lof_comment_trap' );

/**
 * Reject comments/reviews from bots: honeypot filled, missing/forged stamp,
 * sent in under 4 seconds, or more than 2 links.
 *
 * @param array $data Comment data.
 * @return array
 */
function lof_comment_check( $data ) {
	if ( is_user_logged_in() && current_user_can( 'moderate_comments' ) ) {
		return $data;
	}
	$hp = isset( $_POST['lof_website'] ) ? trim( (string) wp_unslash( $_POST['lof_website'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	$ts = isset( $_POST['lof_ts'] ) ? sanitize_text_field( wp_unslash( $_POST['lof_ts'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
	$ok = false;
	if ( '' === $hp && preg_match( '/^(\d+)\.([a-f0-9]+)$/', $ts, $m ) ) {
		$age = time() - (int) $m[1];
		$ok  = hash_equals( wp_hash( 'lof_ts' . $m[1] ), $m[2] ) && $age >= 4 && $age < DAY_IN_SECONDS * 2;
	}
	if ( $ok && substr_count( strtolower( (string) $data['comment_content'] ), 'http' ) > 2 ) {
		$ok = false;
	}
	if ( ! $ok ) {
		wp_die( esc_html__( 'Sorry, your message could not be posted. Please go back and try again.', 'light-of-future' ), '', array( 'response' => 403, 'back_link' => true ) );
	}
	return $data;
}
add_filter( 'preprocess_comment', 'lof_comment_check', 1 );
