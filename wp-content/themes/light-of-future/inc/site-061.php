<?php
/**
 * 0.61.0: site tidy-up (sitemap and default WordPress pages).
 *
 * @package LightOfFuture
 */

defined( 'ABSPATH' ) || exit;

/**
 * Default WordPress placeholder content that should never be found by search engines.
 *
 * @return string[]
 */
function lof_placeholder_slugs() {
	return apply_filters( 'lof_placeholder_slugs', array( 'hello-world', 'sample-page' ) );
}

/**
 * Sitemap: no author pages, no blog categories, no placeholder posts.
 */
add_filter(
	'wp_sitemaps_add_provider',
	function ( $provider, $name ) {
		return 'users' === $name ? false : $provider;
	},
	10,
	2
);
add_filter(
	'wp_sitemaps_taxonomies',
	function ( $tax ) {
		unset( $tax['category'], $tax['post_tag'], $tax['post_format'] );
		return $tax;
	}
);
add_filter(
	'wp_sitemaps_posts_query_args',
	function ( $args ) {
		$ids = array();
		foreach ( lof_placeholder_slugs() as $slug ) {
			foreach ( array( 'post', 'page' ) as $type ) {
				$p = get_page_by_path( $slug, OBJECT, $type );
				if ( $p ) {
					$ids[] = (int) $p->ID;
				}
			}
		}
		if ( $ids ) {
			$args['post__not_in'] = array_merge( isset( $args['post__not_in'] ) ? (array) $args['post__not_in'] : array(), $ids );
		}
		return $args;
	}
);

/**
 * Placeholder posts, author archives and the empty blog category stay out of search results.
 *
 * @param array $robots Robots directives.
 * @return array
 */
function lof_noindex_placeholders( $robots ) {
	$obj = get_queried_object();
	if ( is_author() || is_category() || is_tag() || ( $obj && isset( $obj->post_name ) && in_array( $obj->post_name, lof_placeholder_slugs(), true ) ) ) {
		$robots['noindex'] = true;
		$robots['follow']  = true;
		unset( $robots['max-image-preview'] );
	}
	return $robots;
}
add_filter( 'wp_robots', 'lof_noindex_placeholders', 20 );
