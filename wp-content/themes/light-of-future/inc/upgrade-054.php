<?php
/**
 * 0.54.0: the foundation's email becomes info@lightoffuturefoundation.org
 * everywhere the site shows or sends it (contact setting, enquiry forms,
 * shop emails). Runs once on the first wp-admin load.
 *
 * @package LightOfFuture
 */

defined( 'ABSPATH' ) || exit;

/**
 * Apply the new address.
 */
function lof_upgrade_email_054() {
	if ( (int) get_option( 'lof_email_rev', 0 ) >= 1 ) {
		return;
	}
	$new = 'info@lightoffuturefoundation.org';
	$s   = get_option( 'lof_core_settings', array() );
	$s   = is_array( $s ) ? $s : array();
	$s['email'] = $new;
	update_option( 'lof_core_settings', $s );
	if ( class_exists( 'WooCommerce' ) ) {
		update_option( 'woocommerce_email_from_address', $new );
		update_option( 'woocommerce_email_from_name', 'Light of Future Foundation' );
		foreach ( array( 'woocommerce_new_order_settings', 'woocommerce_cancelled_order_settings', 'woocommerce_failed_order_settings' ) as $opt ) {
			$v = get_option( $opt, array() );
			$v = is_array( $v ) ? $v : array();
			$v['recipient'] = $new;
			update_option( $opt, $v );
		}
	}
	update_option( 'lof_email_rev', 1, false );
}
add_action( 'admin_init', 'lof_upgrade_email_054', 34 );
