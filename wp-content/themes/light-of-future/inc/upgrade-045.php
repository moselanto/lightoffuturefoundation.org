<?php
/**
 * 0.45.0 one-off upgrade: focus-area photos from the November 2026 content
 * intake. Only photos marked safe in the intake list (no children's faces in
 * close-up, no private records). Runs once in wp-admin, guarded by an option.
 *
 * @package Light_Of_Future
 */

defined( 'ABSPATH' ) || exit;

/**
 * Programme slug => bundled photo slug.
 *
 * @return array
 */
function lof_programme_photo_map() {
	return array(
		'food-donations'         => 'prog-food-packs',
		'clean-water'            => 'prog-clean-water',
		'hygiene-kits'           => 'prog-sanitary-pads',
		'decent-houses'          => 'prog-decent-house',
		'community-empowerment'  => 'prog-empowerment-margaret',
		'community-development'  => 'prog-community-goats',
		'children-and-families'  => 'prog-mentorship-hall',
		'gbv-awareness'          => 'prog-awareness-session',
	);
}

/**
 * Import the new photos and set them as focus-area images.
 */
function lof_upgrade_programme_photos() {
	if ( (int) get_option( 'lof_prog_photos_rev', 0 ) >= 1 || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	if ( ! function_exists( 'lof_import_photos' ) ) {
		return;
	}
	lof_import_photos();
	$ids  = get_option( 'lof_photo_ids', array() );
	$done = 0;
	foreach ( lof_programme_photo_map() as $prog => $photo ) {
		$found = get_posts( array( 'post_type' => 'lof_programme', 'name' => $prog, 'post_status' => 'any', 'posts_per_page' => 1, 'fields' => 'ids' ) );
		if ( $found && ! empty( $ids[ $photo ] ) ) {
			set_post_thumbnail( $found[0], (int) $ids[ $photo ] );
			++$done;
		}
	}
	if ( class_exists( '\Elementor\Plugin' ) && isset( \Elementor\Plugin::$instance->files_manager ) ) {
		\Elementor\Plugin::$instance->files_manager->clear_cache();
	}
	if ( $done > 0 ) {
		update_option( 'lof_prog_photos_rev', 1, false );
	}
}
add_action( 'admin_init', 'lof_upgrade_programme_photos', 30 );

/**
 * 0.46.0: shorter founder story on the homepage and a tighter page flow
 * (the second "Who we are" block repeats the About text, so it is removed).
 * Backs up the layout first. Runs once in wp-admin.
 */
function lof_upgrade_home_flow() {
	if ( (int) get_option( 'lof_home_flow_rev', 0 ) >= 1 || ! current_user_can( 'manage_options' ) || ! function_exists( 'lof_copy' ) ) {
		return;
	}
	$page = get_page_by_path( 'home', OBJECT, 'page' );
	if ( ! $page ) {
		$front = (int) get_option( 'page_on_front' );
		$page  = $front ? get_post( $front ) : null;
	}
	if ( ! $page ) {
		return;
	}
	$raw  = get_post_meta( $page->ID, '_elementor_data', true );
	$data = is_string( $raw ) ? json_decode( $raw, true ) : $raw;
	if ( ! is_array( $data ) ) {
		return;
	}
	$out     = array();
	$changed = false;
	foreach ( $data as $sec ) {
		$w = isset( $sec['elements'][0]['elements'][0] ) ? $sec['elements'][0]['elements'][0] : null;
		if ( $w && isset( $w['widgetType'] ) && 'lof-feature' === $w['widgetType'] ) {
			$title = isset( $w['settings']['title'] ) ? (string) $w['settings']['title'] : '';
			if ( 0 === strpos( $title, 'Every person deserves dignity' ) ) {
				$changed = true;
				continue;
			}
			if ( 0 === strpos( $title, 'From a local calling' ) ) {
				$sec['elements'][0]['elements'][0]['settings']['body'] = lof_copy( 'founder_short' );
				unset( $sec['elements'][0]['elements'][0]['settings']['quote'], $sec['elements'][0]['elements'][0]['settings']['quote_by'] );
				$changed = true;
			}
		}
		$out[] = $sec;
	}
	update_option( 'lof_home_flow_rev', 1, false );
	if ( ! $changed ) {
		return;
	}
	update_post_meta( $page->ID, '_lof_layout_backup', wp_slash( is_string( $raw ) ? $raw : wp_json_encode( $raw ) ) );
	update_post_meta( $page->ID, '_lof_layout_backup_date', current_time( 'mysql' ) );
	update_post_meta( $page->ID, '_elementor_data', wp_slash( wp_json_encode( $out ) ) );
	delete_post_meta( $page->ID, '_elementor_css' );
	delete_post_meta( $page->ID, '_elementor_element_cache' );
	if ( class_exists( '\Elementor\Plugin' ) && isset( \Elementor\Plugin::$instance->files_manager ) ) {
		\Elementor\Plugin::$instance->files_manager->clear_cache();
	}
	if ( has_action( 'litespeed_purge_all' ) ) {
		do_action( 'litespeed_purge_all' );
	}
}
add_action( 'admin_init', 'lof_upgrade_home_flow', 31 );

/**
 * 0.47.0: four-slide home slider (adds "Shop with purpose"). Replaces only the
 * slides of the slider widget Light of Future created; backs up the layout.
 */
function lof_upgrade_home_slider_four() {
	if ( (int) get_option( 'lof_hero_rev', 0 ) >= 4 || ! current_user_can( 'manage_options' ) || ! function_exists( 'lof_home_slider_settings' ) ) {
		return;
	}
	$ids = get_option( 'lof_shop_image_ids', array() );
	if ( ! is_array( $ids ) || ( empty( $ids['bags-collection'] ) && empty( $ids['bag-tan-satchel'] ) ) ) {
		return;
	}
	$page = get_page_by_path( 'home', OBJECT, 'page' );
	if ( ! $page ) {
		$front = (int) get_option( 'page_on_front' );
		$page  = $front ? get_post( $front ) : null;
	}
	if ( ! $page ) {
		return;
	}
	$raw  = get_post_meta( $page->ID, '_elementor_data', true );
	$data = is_string( $raw ) ? json_decode( $raw, true ) : $raw;
	if ( ! is_array( $data ) ) {
		return;
	}
	$new     = lof_home_slider_settings();
	$changed = false;
	foreach ( $data as &$sec ) {
		if ( isset( $sec['elements'][0]['elements'][0]['widgetType'] ) && 'lof-slider' === $sec['elements'][0]['elements'][0]['widgetType'] ) {
			$sec['elements'][0]['elements'][0]['settings']['slides'] = $new['slides'];
			$changed = true;
			break;
		}
	}
	unset( $sec );
	update_option( 'lof_hero_rev', 4, false );
	if ( ! $changed ) {
		return;
	}
	update_post_meta( $page->ID, '_lof_layout_backup', wp_slash( is_string( $raw ) ? $raw : wp_json_encode( $raw ) ) );
	update_post_meta( $page->ID, '_lof_layout_backup_date', current_time( 'mysql' ) );
	update_post_meta( $page->ID, '_elementor_data', wp_slash( wp_json_encode( $data ) ) );
	delete_post_meta( $page->ID, '_elementor_css' );
	delete_post_meta( $page->ID, '_elementor_element_cache' );
	if ( class_exists( '\Elementor\Plugin' ) && isset( \Elementor\Plugin::$instance->files_manager ) ) {
		\Elementor\Plugin::$instance->files_manager->clear_cache();
	}
	if ( has_action( 'litespeed_purge_all' ) ) {
		do_action( 'litespeed_purge_all' );
	}
}
add_action( 'admin_init', 'lof_upgrade_home_slider_four', 32 );
