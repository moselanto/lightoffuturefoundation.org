<?php
/**
 * Light of Future Foundation child theme.
 *
 * Presentation only. Content models, settings and forms live in the
 * Light of Future Core plugin so they survive a theme change.
 *
 * @package LightOfFuture
 */

defined( 'ABSPATH' ) || exit;

define( 'LOF_THEME_VERSION', '0.5.0' );
define( 'LOF_THEME_DIR', get_stylesheet_directory() );
define( 'LOF_THEME_URI', get_stylesheet_directory_uri() );

require_once LOF_THEME_DIR . '/inc/setup.php';
require_once LOF_THEME_DIR . '/inc/brand.php';
require_once LOF_THEME_DIR . '/inc/template-tags.php';
require_once LOF_THEME_DIR . '/inc/elementor.php';
require_once LOF_THEME_DIR . '/inc/security.php';
require_once LOF_THEME_DIR . '/inc/elementor/widgets.php';
require_once LOF_THEME_DIR . '/inc/floating.php';

if ( is_admin() ) {
	require_once LOF_THEME_DIR . '/inc/importer.php';
}
