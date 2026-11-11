<?php
/**
 * Partner pages (0.71.0, owner request 11 Nov 2026).
 *
 * Each approved partner gets its own page at /partners/<slug>/ (template-parts/partner.php),
 * modelled on the Cistern of Hope partner pages. The Core plugin registers lof_partner as
 * private; this makes it viewable with a /partners/ address. Unapproved partners return 404.
 * Partner text lives in the partner post (content, excerpt) and post meta:
 * lof_tagline, lof_status_label, lof_focus, lof_impacts (one per line), lof_programme (slug).
 *
 * @package LightOfFuture
 */

defined( 'ABSPATH' ) || exit;

/**
 * Make partners viewable at /partners/<slug>/.
 *
 * @param array  $args Post type args.
 * @param string $type Post type.
 * @return array
 */
function lof_0710_partner_args( $args, $type ) {
	if ( 'lof_partner' !== $type ) {
		return $args;
	}
	$supports                    = isset( $args['supports'] ) ? (array) $args['supports'] : array();
	$args['public']              = true;
	$args['publicly_queryable']  = true;
	$args['exclude_from_search'] = true;
	$args['rewrite']             = array( 'slug' => 'partners', 'with_front' => false );
	$args['supports']            = array_values( array_unique( array_merge( $supports, array( 'title', 'editor', 'excerpt', 'thumbnail' ) ) ) );
	return $args;
}
add_filter( 'register_post_type_args', 'lof_0710_partner_args', 10, 2 );

/**
 * Is this partner approved and does it have a page to show?
 *
 * @param WP_Post|int $p Partner.
 * @return string Page URL or ''.
 */
function lof_partner_page_url( $p ) {
	$p = get_post( $p );
	if ( ! $p || 'lof_partner' !== $p->post_type || 'publish' !== $p->post_status ) {
		return '';
	}
	if ( '1' !== (string) get_post_meta( $p->ID, 'lof_approved', true ) || '' === trim( (string) $p->post_content ) ) {
		return '';
	}
	$url = get_permalink( $p );
	return $url ? (string) $url : '';
}

/**
 * 404 for partners without approval or without a page.
 */
function lof_0710_partner_guard() {
	if ( is_singular( 'lof_partner' ) && '' === lof_partner_page_url( get_queried_object_id() ) ) {
		global $wp_query;
		$wp_query->set_404();
		status_header( 404 );
		nocache_headers();
	}
}
add_action( 'template_redirect', 'lof_0710_partner_guard', 1 );

/**
 * Use the theme's partner layout.
 *
 * @param string $template Template path.
 * @return string
 */
function lof_0710_partner_template( $template ) {
	if ( is_singular( 'lof_partner' ) && ! is_404() ) {
		$file = LOF_THEME_DIR . '/single-lof_partner.php';
		if ( file_exists( $file ) ) {
			return $file;
		}
	}
	return $template;
}
add_filter( 'template_include', 'lof_0710_partner_template', 99 );

/**
 * Body class for styling.
 *
 * @param array $classes Classes.
 * @return array
 */
function lof_0710_partner_body_class( $classes ) {
	if ( is_singular( 'lof_partner' ) ) {
		$classes[] = 'lof-page-partner';
	}
	return $classes;
}
add_filter( 'body_class', 'lof_0710_partner_body_class' );
