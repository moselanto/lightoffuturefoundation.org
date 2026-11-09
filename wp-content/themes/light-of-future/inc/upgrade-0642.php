<?php
/**
 * 0.63.16 one-off (consent confirmed by the owner, 9 Nov 2026):
 * - Replaces programme gallery photos in the Light of Future Media plugin with
 *   cropped versions (school sign and phone number, school office sign and
 *   "TECNO" phone watermarks removed). Also refreshes the Media Library copies
 *   the plugin made of the same photos (page heroes).
 * - Adds Meshack and Silvester's school photo to Eunice's story.
 * Runs once in wp-admin.
 *
 * @package Light_Of_Future
 */

defined( 'ABSPATH' ) || exit;

/**
 * Run the 0.63.16 media update.
 */
function lof_upgrade_0642_media() {
	if ( get_option( 'lof_media_0642_rev' ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$src_dir = LOF_THEME_DIR . '/assets/lofm-fix/';
	$dst_dir = WP_PLUGIN_DIR . '/light-of-future-media/assets/photos/';
	if ( ! is_dir( $dst_dir ) ) {
		return;
	}
	$attach = get_option( 'lofm_attach_ids', array() );
	$attach = is_array( $attach ) ? $attach : array();
	require_once ABSPATH . 'wp-admin/includes/image.php';
	foreach ( (array) glob( $src_dir . '*.webp' ) as $file ) {
		$name = basename( $file );
		copy( $file, $dst_dir . $name );
		$slug = substr( $name, 0, -5 );
		if ( '-sm' !== substr( $slug, -3 ) && ! empty( $attach[ $slug ] ) ) {
			$path = get_attached_file( (int) $attach[ $slug ] );
			if ( $path && file_exists( $path ) && copy( $file, $path ) ) {
				wp_update_attachment_metadata( (int) $attach[ $slug ], wp_generate_attachment_metadata( (int) $attach[ $slug ], $path ) );
			}
		}
	}

	$eunice = get_page_by_path( 'eunice', OBJECT, 'lof_story' );
	if ( $eunice && false === strpos( $eunice->post_content, 'edu-boys' ) ) {
		$fig = '<figure class="wp-block-image size-large lof-fig-portrait"><img src="' . esc_url( plugins_url( 'light-of-future-media/assets/photos/edu-boys.webp' ) ) . '" alt="' . esc_attr__( 'Meshack and Silvester in their school uniforms', 'light-of-future' ) . '" loading="lazy" decoding="async" width="582" height="1204"><figcaption>' . esc_html__( 'Meshack and Silvester, Eunice\'s sons, ready for school. We also support their education.', 'light-of-future' ) . '</figcaption></figure>';
		$content = $eunice->post_content;
		$pos     = strpos( $content, '[lof_media' );
		$content = false === $pos ? $content . $fig : substr( $content, 0, $pos ) . $fig . substr( $content, $pos );
		wp_update_post( wp_slash( array( 'ID' => $eunice->ID, 'post_content' => $content ) ) );
	}

	if ( function_exists( 'litespeed_purge_all' ) ) {
		litespeed_purge_all();
	}
	do_action( 'litespeed_purge_all' );
	update_option( 'lof_media_0642_rev', 1, false );
}
add_action( 'admin_init', 'lof_upgrade_0642_media', 44 );
