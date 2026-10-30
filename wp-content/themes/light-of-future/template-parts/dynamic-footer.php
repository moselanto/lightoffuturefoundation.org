<?php
/**
 * Hello Elementor 3.x loads template-parts/dynamic-footer.php when Elementor is
 * active. Use the Light of Future footer (Logo Kit logo, menus, contact) instead
 * of Hello's built-in site-title footer. Elementor Pro Theme Builder footers
 * still take priority because they run before this file is reached.
 *
 * @package LightOfFuture
 */

defined( 'ABSPATH' ) || exit;

get_template_part( 'template-parts/footer' );
