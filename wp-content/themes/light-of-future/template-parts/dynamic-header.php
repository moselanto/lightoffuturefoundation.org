<?php
/**
 * Hello Elementor 3.x loads template-parts/dynamic-header.php when Elementor is
 * active. Use the Light of Future header (Logo Kit logo, menus, contact) instead
 * of Hello's built-in site-title header. Elementor Pro Theme Builder headers
 * still take priority because they run before this file is reached.
 *
 * @package LightOfFuture
 */

defined( 'ABSPATH' ) || exit;

get_template_part( 'template-parts/header' );
