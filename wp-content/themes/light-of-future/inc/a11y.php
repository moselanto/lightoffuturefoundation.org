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
