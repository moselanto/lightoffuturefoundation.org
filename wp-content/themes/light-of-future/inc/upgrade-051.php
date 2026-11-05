<?php
/**
 * 0.51.0: add "Shop" to the main menu once, before Contact, without
 * rebuilding the menu (so hand-made changes are kept).
 *
 * @package LightOfFuture
 */

defined( 'ABSPATH' ) || exit;

/**
 * Add the Shop page to the menu in the Main menu location.
 */
function lof_upgrade_menu_shop() {
	if ( (int) get_option( 'lof_menu_shop_rev', 0 ) >= 1 ) {
		return;
	}
	$shop_id = function_exists( 'wc_get_page_id' ) ? (int) wc_get_page_id( 'shop' ) : 0;
	if ( $shop_id <= 0 ) {
		$page    = get_page_by_path( 'shop', OBJECT, 'page' );
		$shop_id = $page ? (int) $page->ID : 0;
	}
	if ( $shop_id <= 0 || 'publish' !== get_post_status( $shop_id ) ) {
		return;
	}
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	if ( empty( $locations['lof-primary'] ) ) {
		update_option( 'lof_menu_shop_rev', 1, false );
		return;
	}
	$menu_id = (int) $locations['lof-primary'];
	$items   = wp_get_nav_menu_items( $menu_id );
	if ( ! is_array( $items ) ) {
		return;
	}
	$pos = 0;
	$max = 0;
	foreach ( $items as $item ) {
		$max = max( $max, (int) $item->menu_order );
		if ( ( 'page' === $item->object && (int) $item->object_id === $shop_id ) || false !== strpos( (string) $item->url, '/shop' ) ) {
			update_option( 'lof_menu_shop_rev', 1, false );
			return;
		}
		if ( ! $pos && 0 === (int) $item->menu_item_parent && ( 'contact' === get_post_field( 'post_name', (int) $item->object_id ) || 'contact' === strtolower( trim( $item->title ) ) ) ) {
			$pos = (int) $item->menu_order;
		}
	}
	if ( $pos ) {
		foreach ( $items as $item ) {
			if ( (int) $item->menu_order >= $pos ) {
				wp_update_post( array( 'ID' => (int) $item->ID, 'menu_order' => (int) $item->menu_order + 1 ) );
			}
		}
	} else {
		$pos = $max + 1;
	}
	$id = wp_update_nav_menu_item(
		$menu_id,
		0,
		array(
			'menu-item-title'     => __( 'Shop', 'light-of-future' ),
			'menu-item-object'    => 'page',
			'menu-item-object-id' => $shop_id,
			'menu-item-type'      => 'post_type',
			'menu-item-status'    => 'publish',
			'menu-item-parent-id' => 0,
			'menu-item-position'  => $pos,
		)
	);
	if ( ! is_wp_error( $id ) ) {
		update_option( 'lof_menu_shop_rev', 1, false );
	}
}
add_action( 'admin_init', 'lof_upgrade_menu_shop', 33 );
