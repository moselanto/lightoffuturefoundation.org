<?php
/**
 * 0.45.0 one-off upgrade: focus-area photos from the November 2026 content
 * intake. Only photos marked safe in the intake list (no children's faces in
 * close-up, no private records). Runs once in wp-admin, guarded by an option.
 *
 * @package Light_Of_Future
 */

defined( 'ABSPATH' ) || exit;

/**
 * Programme slug => bundled photo slug.
 *
 * @return array
 */
function lof_programme_photo_map() {
	return array(
		'food-donations'         => 'prog-food-packs',
		'clean-water'            => 'prog-clean-water',
		'hygiene-kits'           => 'prog-sanitary-pads',
		'decent-houses'          => 'prog-decent-house',
		'community-empowerment'  => 'prog-empowerment-margaret',
		'community-development'  => 'prog-community-goats',
		'children-and-families'  => 'prog-mentorship-hall',
		'gbv-awareness'          => 'prog-awareness-session',
	);
}

/**
 * Import the new photos and set them as focus-area images.
 */
function lof_upgrade_programme_photos() {
	if ( (int) get_option( 'lof_prog_photos_rev', 0 ) >= 1 || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	if ( ! function_exists( 'lof_import_photos' ) ) {
		return;
	}
	lof_import_photos();
	$ids  = get_option( 'lof_photo_ids', array() );
	$done = 0;
	foreach ( lof_programme_photo_map() as $prog => $photo ) {
		$found = get_posts( array( 'post_type' => 'lof_programme', 'name' => $prog, 'post_status' => 'any', 'posts_per_page' => 1, 'fields' => 'ids' ) );
		if ( $found && ! empty( $ids[ $photo ] ) ) {
			set_post_thumbnail( $found[0], (int) $ids[ $photo ] );
			++$done;
		}
	}
	if ( class_exists( '\Elementor\Plugin' ) && isset( \Elementor\Plugin::$instance->files_manager ) ) {
		\Elementor\Plugin::$instance->files_manager->clear_cache();
	}
	if ( $done > 0 ) {
		update_option( 'lof_prog_photos_rev', 1, false );
	}
}
add_action( 'admin_init', 'lof_upgrade_programme_photos', 30 );
