<?php
/**
 * Accessibility helpers.
 *
 * @package LightOfFuture
 */

defined( 'ABSPATH' ) || exit;

/**
 * Elementor's "Full width" (header-footer) page template prints content
 * without a <main> landmark, so the skip link had no target. Wrap it.
 */
function lof_main_open() {
	echo '<main id="content" class="lof-main" tabindex="-1">';
}
function lof_main_close() {
	echo '</main>';
}
add_action( 'elementor/page_templates/header-footer/before_content', 'lof_main_open' );
add_action( 'elementor/page_templates/header-footer/after_content', 'lof_main_close' );

/**
 * 0.46.0: one skip link only (the parent theme adds a second one), and a
 * real target on WooCommerce pages: replace WooCommerce's default
 * div#primary > main#main wrapper with main#content.
 */
add_filter( 'hello_elementor_enable_skip_link', '__return_false' );

function lof_wc_wrapper_start() {
	echo '<main id="content" class="site-main lof-main lof-shop-main" tabindex="-1">';
}
function lof_wc_wrapper_end() {
	echo '</main>';
}
function lof_wc_wrappers() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
	add_action( 'woocommerce_before_main_content', 'lof_wc_wrapper_start', 10 );
	add_action( 'woocommerce_after_main_content', 'lof_wc_wrapper_end', 10 );
}
add_action( 'after_setup_theme', 'lof_wc_wrappers', 20 );
