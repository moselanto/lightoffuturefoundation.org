<?php
/**
 * Small template helpers. Organisation details come from the Core plugin
 * (lof_core_setting()); everything degrades gracefully if it is inactive.
 *
 * @package LightOfFuture
 */

defined( 'ABSPATH' ) || exit;

/**
 * Read an organisation setting from the Core plugin.
 *
 * @param string $key     Setting key.
 * @param string $default Fallback.
 * @return string
 */
function lof_setting( $key, $default = '' ) {
	if ( function_exists( 'lof_core_setting' ) ) {
		return (string) lof_core_setting( $key, $default );
	}
	return $default;
}

/**
 * Donate button URL: the Donate page if it exists, else the setting.
 *
 * @return string
 */
function lof_donate_url() {
	$url = lof_setting( 'donate_url' );
	if ( $url ) {
		return $url;
	}
	$page = get_page_by_path( 'donate' );
	return $page ? get_permalink( $page ) : home_url( '/donate/' );
}

/**
 * Social links that have a URL set.
 *
 * @return array<string,string> network => url
 */
function lof_social_links() {
	$out = array();
	foreach ( array( 'facebook', 'instagram', 'youtube', 'tiktok', 'x', 'linkedin' ) as $network ) {
		$url = lof_setting( 'social_' . $network );
		if ( $url ) {
			$out[ $network ] = $url;
		}
	}
	return $out;
}

/**
 * Fallback menu when no menu is assigned yet.
 */
function lof_fallback_menu() {
	$items = array(
		'about-us'     => __( 'About', 'light-of-future' ),
		'programmes'   => __( 'Our Work', 'light-of-future' ),
		'impact'       => __( 'Impact', 'light-of-future' ),
		'stories'      => __( 'Stories', 'light-of-future' ),
		'get-involved' => __( 'Get Involved', 'light-of-future' ),
		'contact'      => __( 'Contact', 'light-of-future' ),
	);
	echo '<ul class="lof-menu">';
	foreach ( $items as $slug => $label ) {
		printf( '<li><a href="%s">%s</a></li>', esc_url( home_url( '/' . $slug . '/' ) ), esc_html( $label ) );
	}
	echo '</ul>';
}

/**
 * Programme lead, as named in the foundation's own document (leaves blank where it names nobody).
 * Override per programme with the custom field lof_lead.
 *
 * @param WP_Post|int $post Programme.
 * @return string
 */
function lof_programme_lead( $post ) {
	$post   = get_post( $post );
	$custom = $post ? (string) get_post_meta( $post->ID, 'lof_lead', true ) : '';
	if ( $custom ) {
		return $custom;
	}
	$map = array(
		'education-support'      => 'Edith Atieno',
		'food-donations'         => 'Scholastica Baraza',
		'health-medical-support' => 'Stephen Oduor',
		'clean-water'            => 'Kennedy Mumbo',
		'hygiene-kits'           => 'Lucy Habiba',
		'decent-houses'          => 'Victor Onyango',
		'community-development'  => 'Victor Onyango',
		'children-and-families'  => 'Scholastica Baraza',
	);
	return $post && isset( $map[ $post->post_name ] ) ? $map[ $post->post_name ] : '';
}

/**
 * 0.24.0: photo shown beside the title in inner-page heroes that have no photo of their own.
 * Uses only the foundation's own consented photos.
 *
 * @return int Attachment ID or 0.
 */
function lof_page_hero_side_photo() {
	$post = get_queried_object();
	if ( ! $post || empty( $post->post_name ) ) {
		return 0;
	}
	$map = array(
		'about-us'           => 'story-elijah-new-home',
		'faq'                => 'story-philister-home',
		'leadership'         => 'story-elijah-new-home',
		'programmes'         => 'story-precious-school-shoes',
		'talent-development' => 'story-precious-school-shoes',
		'stories'            => 'story-philister-home',
		'impact'             => 'story-elijah-new-home',
		'get-involved'       => 'story-precious-school-shoes',
		'contact'            => 'founder-francis-ouma-sikuku',
		'donate'             => 'story-elijah-new-home',
	);
	$map = apply_filters( 'lof_page_hero_side_photos', $map );
	if ( empty( $map[ $post->post_name ] ) ) {
		return 0;
	}
	$ids = get_option( 'lof_photo_ids', array() );
	$id  = is_array( $ids ) && isset( $ids[ $map[ $post->post_name ] ] ) ? (int) $ids[ $map[ $post->post_name ] ] : 0;
	return ( $id && 'attachment' === get_post_type( $id ) ) ? $id : 0;
}

/**
 * 0.25.0: "At a glance" figures beside the Leadership hero, counted from the
 * approved Leadership entries (nothing typed in by hand).
 *
 * @return array[] Each item: array( number, label ).
 */
function lof_page_hero_facts() {
	$post = get_queried_object();
	if ( ! $post || empty( $post->post_name ) || 'leadership' !== $post->post_name ) {
		return array();
	}
	$count = function ( $group ) {
		return count(
			get_posts(
				array(
					'post_type'      => 'lof_leader',
					'post_status'    => 'publish',
					'posts_per_page' => 50,
					'fields'         => 'ids',
					'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
						array( 'key' => 'lof_approved', 'value' => '1' ),
						array( 'key' => 'lof_group', 'value' => $group ),
					),
				)
			)
		);
	};
	$board = $count( 'executive' ) + $count( 'board' );
	$team  = $count( 'team' );
	if ( ! $board && ! $team ) {
		return array();
	}
	$out = array();
	if ( $board ) {
		$out[] = array( (string) $board, __( 'Board of Directors', 'light-of-future' ) );
	}
	if ( $team ) {
		$out[] = array( (string) $team, __( 'Programme leads', 'light-of-future' ) );
	}
	$out[] = array( '10', __( 'Founding members', 'light-of-future' ) );
	$out[] = array( '2025', __( 'Registered, NGO Board of Kenya', 'light-of-future' ) );
	return $out;
}

/**
 * 0.26.0: simple line icon for a programme (used when a programme has no photo yet).
 *
 * @param string $slug Programme slug.
 * @return string SVG markup (trusted, static).
 */
function lof_programme_icon( $slug ) {
	$paths = array(
		'education'  => 'M12 3 1 9l11 6 9-4.9V17h2V9L12 3zm-6.5 9.6V16c0 1.7 2.9 3.5 6.5 3.5s6.5-1.8 6.5-3.5v-3.4L12 16.2l-6.5-3.6z',
		'food'       => 'M3 11h18a9 9 0 0 1-18 0zm4-8c0 1.5-1 1.5-1 3s1 1.5 1 3H5.5c0-1.5-1-1.5-1-3s1-1.5 1-3H7zm5 0c0 1.5-1 1.5-1 3s1 1.5 1 3h-1.5c0-1.5-1-1.5-1-3s1-1.5 1-3H12zm5 0c0 1.5-1 1.5-1 3s1 1.5 1 3h-1.5c0-1.5-1-1.5-1-3s1-1.5 1-3H17z',
		'health'     => 'M10 3h4v7h7v4h-7v7h-4v-7H3v-4h7z',
		'water'      => 'M12 2.5S5 10.2 5 15a7 7 0 0 0 14 0c0-4.8-7-12.5-7-12.5zm-3 12.7h1.8a2.2 2.2 0 0 0 2.2 2.2V19a4 4 0 0 1-4-3.8z',
		'hygiene'    => 'M12 21s-7.5-4.6-9.4-9.3C1.2 8 3.4 4 7.1 4c2 0 3.4 1.1 4.9 3 1.5-1.9 2.9-3 4.9-3 3.7 0 5.9 4 4.5 7.7C19.5 16.4 12 21 12 21z',
		'house'      => 'M12 3 2 11.5h3V21h5.5v-5.5h3V21H19v-9.5h3L12 3z',
		'community'  => 'M8 11a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7zm8 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM8 13c-3.3 0-6 1.7-6 4v3h12v-3c0-2.3-2.7-4-6-4zm8 0c-.7 0-1.4.1-2 .3 1.3.9 2 2.2 2 3.7v3h6v-3c0-2.2-2.7-4-6-4z',
		'children'   => 'M12 7.5a2.75 2.75 0 1 0 0-5.5 2.75 2.75 0 0 0 0 5.5zM9 9h6l3 5-1.8 1-1.7-2.6V22h-2.2v-5h-.6v5H9.5v-9.6L7.8 15 6 14z',
		'shield'     => 'M12 2 4 5v6c0 5 3.4 9.4 8 11 4.6-1.6 8-6 8-11V5l-8-3zm-1.2 13.6-3.5-3.5 1.4-1.4 2.1 2.1 4.6-4.6 1.4 1.4-6 6z',
		'outreach'   => 'M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z',
		'sun'        => 'M12 7a5 5 0 1 0 0 10 5 5 0 0 0 0-10zm-1-6h2v3h-2zm0 19h2v3h-2zM1 11h3v2H1zm19 0h3v2h-3zM4.2 5.6l1.4-1.4 2.1 2.1-1.4 1.4zm12.1 12.1 1.4-1.4 2.1 2.1-1.4 1.4zM4.2 18.4l2.1-2.1 1.4 1.4-2.1 2.1zM16.3 6.3l2.1-2.1 1.4 1.4-2.1 2.1z',
	);
	$keys = array( 'education' => 'education', 'food' => 'food', 'health' => 'health', 'water' => 'water', 'hygiene' => 'hygiene', 'house' => 'house', 'empower' => 'community', 'community' => 'community', 'children' => 'children', 'gbv' => 'shield', 'violence' => 'shield', 'prison' => 'outreach', 'talent' => 'sun' );
	$key  = 'sun';
	foreach ( $keys as $needle => $k ) {
		if ( false !== strpos( (string) $slug, $needle ) ) {
			$key = $k;
			break;
		}
	}
	return '<svg viewBox="0 0 24 24" width="40" height="40" fill="currentColor" aria-hidden="true" focusable="false"><path d="' . esc_attr( $paths[ $key ] ) . '"/></svg>';
}
