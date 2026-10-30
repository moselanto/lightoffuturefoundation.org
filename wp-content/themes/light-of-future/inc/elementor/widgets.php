<?php
/**
 * Register the Light of Future widget category and widgets.
 *
 * @package LightOfFuture
 */

defined( 'ABSPATH' ) || exit;

/**
 * Widget category.
 *
 * @param \Elementor\Elements_Manager $manager Manager.
 */
function lof_elementor_category( $manager ) {
	$manager->add_category( 'light-of-future', array( 'title' => __( 'Light of Future', 'light-of-future' ), 'icon' => 'eicon-star' ) );
}
add_action( 'elementor/elements/categories_registered', 'lof_elementor_category' );

/**
 * Register widgets.
 *
 * @param \Elementor\Widgets_Manager $manager Manager.
 */
function lof_elementor_widgets( $manager ) {
	require_once LOF_THEME_DIR . '/inc/elementor/widgets-base.php';
	require_once LOF_THEME_DIR . '/inc/elementor/widgets-content.php';
	require_once LOF_THEME_DIR . '/inc/elementor/widgets-dynamic.php';
	$classes = array(
		'LOF_Widget_Hero',
		'LOF_Widget_Heading',
		'LOF_Widget_Feature',
		'LOF_Widget_Cards',
		'LOF_Widget_Programmes',
		'LOF_Widget_Impact',
		'LOF_Widget_Stories',
		'LOF_Widget_Leaders',
		'LOF_Widget_Partners',
		'LOF_Widget_CTA',
		'LOF_Widget_Contact',
	);
	foreach ( $classes as $class ) {
		if ( class_exists( $class ) ) {
			$manager->register( new $class() );
		}
	}
}
add_action( 'elementor/widgets/register', 'lof_elementor_widgets' );
