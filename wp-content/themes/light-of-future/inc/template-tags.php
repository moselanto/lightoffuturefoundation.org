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
