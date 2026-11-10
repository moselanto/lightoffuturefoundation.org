<?php
/**
 * 0.64.0 one-off upgrade (admin only, runs once on admin_init).
 *
 * Moves the home "Shop with purpose" section ([lof_home_market]) so it sits
 * directly after the home slider, as Cistern of Hope places its Hope Market
 * section straight after the hero. Only the existing Elementor element is
 * moved: nothing is deleted or rewritten. The previous layout is backed up in
 * the post meta _lof_layout_backup_0640.
 *
 * Guard option: lof_home_0640_order (delete it to run the move again).
 *
 * @package LightOfFuture
 */

defined( 'ABSPATH' ) || exit;

/**
 * Index of the first top-level element whose JSON contains a needle.
 *
 * @param array  $data   Elementor data.
 * @param string $needle Needle.
 * @return int -1 when not found.
 */
function lof_0640_find( $data, $needle ) {
	foreach ( $data as $i => $el ) {
		if ( false !== strpos( (string) wp_json_encode( $el ), $needle ) ) {
			return (int) $i;
		}
	}
	return -1;
}

/**
 * Move the home shop section after the slider, once.
 */
function lof_upgrade_0640_home_order() {
	if ( get_option( 'lof_home_0640_order' ) || ! current_user_can( 'edit_pages' ) ) {
		return;
	}
	$page = get_page_by_path( 'home', OBJECT, 'page' );
	if ( ! $page ) {
		$front = (int) get_option( 'page_on_front' );
		$page  = $front ? get_post( $front ) : null;
	}
	if ( ! $page ) {
		update_option( 'lof_home_0640_order', 'no-home', false );
		return;
	}
	$raw  = get_post_meta( $page->ID, '_elementor_data', true );
	$data = is_string( $raw ) ? json_decode( $raw, true ) : $raw;
	if ( ! is_array( $data ) || ! $data ) {
		update_option( 'lof_home_0640_order', 'no-data', false );
		return;
	}
	$data   = array_values( $data );
	$shop   = lof_0640_find( $data, 'lof_home_market' );
	$slider = lof_0640_find( $data, '"widgetType":"lof-slider"' );
	if ( $shop < 0 || $slider < 0 ) {
		update_option( 'lof_home_0640_order', 'not-found', false );
		return;
	}
	if ( $shop === $slider + 1 ) {
		update_option( 'lof_home_0640_order', 'already', false );
		return;
	}
	$count = count( $data );
	$el    = $data[ $shop ];
	array_splice( $data, $shop, 1 );
	$slider = lof_0640_find( $data, '"widgetType":"lof-slider"' );
	array_splice( $data, $slider + 1, 0, array( $el ) );
	if ( count( $data ) !== $count ) {
		update_option( 'lof_home_0640_order', 'count-mismatch', false );
		return;
	}
	$json = wp_json_encode( $data );
	if ( ! $json ) {
		update_option( 'lof_home_0640_order', 'encode-failed', false );
		return;
	}
	update_post_meta( $page->ID, '_lof_layout_backup_0640', wp_slash( is_string( $raw ) ? $raw : wp_json_encode( $raw ) ) );
	update_post_meta( $page->ID, '_elementor_data', wp_slash( $json ) );
	delete_post_meta( $page->ID, '_elementor_css' );
	delete_post_meta( $page->ID, '_elementor_element_cache' );
	if ( class_exists( '\Elementor\Plugin' ) && isset( \Elementor\Plugin::$instance->files_manager ) ) {
		\Elementor\Plugin::$instance->files_manager->clear_cache();
	}
	if ( has_action( 'litespeed_purge_all' ) ) {
		do_action( 'litespeed_purge_all' );
	}
	update_option( 'lof_home_0640_order', 'moved', false );
}
add_action( 'admin_init', 'lof_upgrade_0640_home_order', 40 );
