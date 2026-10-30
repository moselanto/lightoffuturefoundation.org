<?php
/**
 * Elementor integration: brand colours and fonts in Site Settings.
 *
 * Runs once per theme version and only fills in values; an editor's later
 * changes in Elementor > Site Settings are never overwritten.
 *
 * @package LightOfFuture
 */

defined( 'ABSPATH' ) || exit;

/**
 * Add brand colours and typography to the active Elementor kit.
 */
function lof_sync_elementor_kit() {
	if ( ! did_action( 'elementor/loaded' ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	if ( get_option( 'lof_kit_synced' ) === LOF_THEME_VERSION ) {
		return;
	}
	$kit_id = (int) get_option( 'elementor_active_kit' );
	if ( ! $kit_id ) {
		return;
	}
	$settings = get_post_meta( $kit_id, '_elementor_page_settings', true );
	$settings = is_array( $settings ) ? $settings : array();

	$custom   = isset( $settings['custom_colors'] ) && is_array( $settings['custom_colors'] ) ? $settings['custom_colors'] : array();
	$existing = wp_list_pluck( $custom, '_id' );
	foreach ( lof_brand_colors() as $id => $color ) {
		$key = 'lof_' . $id;
		if ( ! in_array( $key, $existing, true ) ) {
			$custom[] = array( '_id' => $key, 'title' => $color[0], 'color' => $color[1] );
		}
	}
	$settings['custom_colors'] = $custom;

	if ( empty( $settings['system_colors'] ) ) {
		$settings['system_colors'] = array(
			array( '_id' => 'primary', 'title' => 'Primary', 'color' => '#5C3D1E' ),
			array( '_id' => 'secondary', 'title' => 'Secondary', 'color' => '#F4AF16' ),
			array( '_id' => 'text', 'title' => 'Text', 'color' => '#2B1D0E' ),
			array( '_id' => 'accent', 'title' => 'Accent', 'color' => '#DC8C13' ),
		);
	}
	if ( empty( $settings['system_typography'] ) ) {
		$settings['system_typography'] = array(
			array( '_id' => 'primary', 'title' => 'Primary', 'typography_typography' => 'custom', 'typography_font_family' => 'Cinzel', 'typography_font_weight' => '700' ),
			array( '_id' => 'secondary', 'title' => 'Secondary', 'typography_typography' => 'custom', 'typography_font_family' => 'Montserrat', 'typography_font_weight' => '700' ),
			array( '_id' => 'text', 'title' => 'Text', 'typography_typography' => 'custom', 'typography_font_family' => 'Montserrat', 'typography_font_weight' => '400' ),
			array( '_id' => 'accent', 'title' => 'Accent', 'typography_typography' => 'custom', 'typography_font_family' => 'Montserrat', 'typography_font_weight' => '600' ),
		);
	}
	update_post_meta( $kit_id, '_elementor_page_settings', $settings );
	update_option( 'lof_kit_synced', LOF_THEME_VERSION, false );
	if ( class_exists( '\\Elementor\\Plugin' ) ) {
		\Elementor\Plugin::$instance->files_manager->clear_cache();
	}
}
add_action( 'admin_init', 'lof_sync_elementor_kit' );

/**
 * Fonts are self-hosted by the theme, so stop Elementor loading Google copies.
 */
add_filter( 'elementor/frontend/print_google_fonts', '__return_false' );

/**
 * Register Cinzel and Montserrat as custom fonts in Elementor's font list.
 *
 * @param array $fonts Fonts.
 * @return array
 */
function lof_elementor_fonts( $fonts ) {
	$fonts['Cinzel']     = 'system';
	$fonts['Montserrat'] = 'system';
	return $fonts;
}
add_filter( 'elementor/fonts/additional_fonts', 'lof_elementor_fonts' );
