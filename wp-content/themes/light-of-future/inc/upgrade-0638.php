<?php
/**
 * 0.63.8 one-off upgrade (wp-admin only), from details sent by Francis Ouma
 * Sikuku on 8 November 2026:
 * - Impact figures: 55 people supported since 2024; vision of 500,000 people by 2040.
 *   The older "Children supported 200+" and "Widows assisted 30+" figures are hidden
 *   (not deleted) so the site does not show conflicting numbers.
 * - Partners: Suivera and Grace International Foundation, with their logos.
 * - Giving details: KCB account 1354817087 and M-Pesa Paybill 522522, account 1354817087.
 *
 * @package Light_Of_Future
 */

defined( 'ABSPATH' ) || exit;

/**
 * Create or update a post of a given type by slug, with meta.
 *
 * @param string $type  Post type.
 * @param string $slug  Slug.
 * @param string $title Title.
 * @param array  $meta  Meta.
 * @param int    $order Menu order.
 * @param int    $thumb Attachment ID.
 * @return int
 */
function lof_0638_upsert( $type, $slug, $title, $meta, $order, $thumb = 0 ) {
	$found = get_page_by_path( $slug, OBJECT, $type );
	$data  = array(
		'post_type'   => $type,
		'post_status' => 'publish',
		'post_name'   => $slug,
		'post_title'  => $title,
		'menu_order'  => $order,
	);
	if ( $found ) {
		$data['ID'] = $found->ID;
		$id         = wp_update_post( wp_slash( $data ) );
	} else {
		$id = wp_insert_post( wp_slash( $data ) );
	}
	if ( ! $id || is_wp_error( $id ) ) {
		return 0;
	}
	foreach ( $meta as $k => $v ) {
		update_post_meta( $id, $k, $v );
	}
	if ( $thumb ) {
		set_post_thumbnail( $id, $thumb );
	}
	return (int) $id;
}

/**
 * Run the upgrade once.
 */
function lof_upgrade_0638() {
	if ( (int) get_option( 'lof_upgrade_0638_rev', 0 ) >= 1 || ! current_user_can( 'manage_options' ) || ! function_exists( 'lof_0636_photo' ) ) {
		return;
	}
	$src = __( 'Figures supplied by the foundation (Francis Ouma Sikuku), 8 November 2026', 'light-of-future' );

	// Impact figures.
	lof_0638_upsert( 'lof_metric', 'people-supported', __( 'People supported since 2024', 'light-of-future' ), array( 'lof_value' => '55', 'lof_suffix' => '', 'lof_period' => '', 'lof_source' => $src, 'lof_verified' => '2026-11-08', 'lof_visible' => '1' ), 0 );
	lof_0638_upsert( 'lof_metric', 'vision-2040', __( 'People we aim to support by 2040', 'light-of-future' ), array( 'lof_value' => '500,000', 'lof_suffix' => '', 'lof_period' => '', 'lof_source' => $src, 'lof_verified' => '2026-11-08', 'lof_visible' => '1' ), 1 );
	foreach ( array( 'children-supported', 'widows-assisted' ) as $old ) {
		$p = get_page_by_path( $old, OBJECT, 'lof_metric' );
		if ( $p ) {
			update_post_meta( $p->ID, 'lof_visible', '0' );
		}
	}

	// Partners.
	$suivera = lof_0636_photo( 'partner-suivera', __( 'Suivera logo', 'light-of-future' ), __( 'Suivera', 'light-of-future' ) );
	$grace   = lof_0636_photo( 'partner-grace-international-foundation', __( 'Grace International Foundation logo', 'light-of-future' ), __( 'Grace International Foundation', 'light-of-future' ) );
	lof_0638_upsert( 'lof_partner', 'suivera', 'Suivera', array( 'lof_approved' => '1' ), 1, $suivera );
	lof_0638_upsert( 'lof_partner', 'grace-international-foundation', 'Grace International Foundation', array( 'lof_approved' => '1' ), 2, $grace );

	// Giving details.
	$set = get_option( 'lof_core_settings', array() );
	$set = is_array( $set ) ? $set : array();
	$set['mpesa_paybill'] = '522522';
	$set['mpesa_account'] = '1354817087';
	$set['bank_name']     = 'Kenya Commercial Bank (KCB)';
	$set['bank_account']  = '1354817087';
	update_option( 'lof_core_settings', $set );

	if ( class_exists( '\Elementor\Plugin' ) && isset( \Elementor\Plugin::$instance->files_manager ) ) {
		\Elementor\Plugin::$instance->files_manager->clear_cache();
	}
	do_action( 'litespeed_purge_all' );
	update_option( 'lof_upgrade_0638_rev', 1, false );
}
add_action( 'admin_init', 'lof_upgrade_0638', 42 );
