<?php
/**
 * Plugin Name:       Light of Future Core
 * Plugin URI:        https://lightoffuturefoundation.org/
 * Description:       Content models and organisation settings for Light of Future Foundation: programmes, projects, impact stories, leaders, partners, reports and verified impact figures. Keeps data safe if the theme changes.
 * Version:           0.11.1
 * Requires at least: 6.3
 * Requires PHP:      7.4
 * Author:            Pimofy Digital
 * License:           GPL-2.0-or-later
 * Text Domain:       light-of-future-core
 *
 * @package LightOfFutureCore
 */

defined( 'ABSPATH' ) || exit;

define( 'LOF_CORE_VERSION', '0.11.1' );
define( 'LOF_CORE_DIR', plugin_dir_path( __FILE__ ) );
define( 'LOF_CORE_URL', plugin_dir_url( __FILE__ ) );

require_once LOF_CORE_DIR . 'includes/settings.php';
require_once LOF_CORE_DIR . 'includes/post-types.php';
require_once LOF_CORE_DIR . 'includes/meta.php';
require_once LOF_CORE_DIR . 'includes/seed.php';
require_once LOF_CORE_DIR . 'includes/forms.php';

/**
 * Refresh permalinks once after each plugin update.
 */
function lof_core_maybe_flush() {
	if ( get_option( 'lof_core_rewrite_version' ) !== LOF_CORE_VERSION ) {
		flush_rewrite_rules();
		update_option( 'lof_core_rewrite_version', LOF_CORE_VERSION, false );
	}
}
add_action( 'init', 'lof_core_maybe_flush', 99 );

/**
 * Flush rewrite rules once after activation so new archives work.
 */
function lof_core_activate() {
	lof_core_register_post_types();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'lof_core_activate' );

register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
