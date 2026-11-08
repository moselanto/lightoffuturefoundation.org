<?php
/**
 * 0.63.6 one-off upgrade: Rosemary, the first beneficiary of the business
 * empowerment programme. Content and photos supplied by Francis Ouma Sikuku
 * (November 2026), with publication rights confirmed. Runs once in wp-admin.
 *
 * @package Light_Of_Future
 */

defined( 'ABSPATH' ) || exit;

/**
 * Import one bundled photo (webp) and return its attachment ID, reusing it if already imported.
 *
 * @param string $slug File slug in assets/images/photos.
 * @param string $alt  Alt text.
 * @param string $cap  Caption.
 * @return int
 */
function lof_0636_photo( $slug, $alt, $cap ) {
	$ids = get_option( 'lof_photo_ids', array() );
	$ids = is_array( $ids ) ? $ids : array();
	if ( ! empty( $ids[ $slug ] ) && 'attachment' === get_post_type( (int) $ids[ $slug ] ) ) {
		return (int) $ids[ $slug ];
	}
	$path = LOF_THEME_DIR . '/assets/images/photos/' . $slug . '.webp';
	if ( ! file_exists( $path ) ) {
		return 0;
	}
	require_once ABSPATH . 'wp-admin/includes/image.php';
	$upload = wp_upload_bits( $slug . '.webp', null, file_get_contents( $path ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- local theme file.
	if ( ! empty( $upload['error'] ) ) {
		return 0;
	}
	$att = wp_insert_attachment(
		array(
			'post_mime_type' => 'image/webp',
			'post_title'     => $alt,
			'post_excerpt'   => $cap,
			'post_status'    => 'inherit',
		),
		$upload['file']
	);
	if ( is_wp_error( $att ) || ! $att ) {
		return 0;
	}
	wp_update_attachment_metadata( $att, wp_generate_attachment_metadata( $att, $upload['file'] ) );
	update_post_meta( $att, '_wp_attachment_image_alt', $alt );
	$ids[ $slug ] = (int) $att;
	update_option( 'lof_photo_ids', $ids, false );
	return (int) $att;
}

/**
 * Create or refresh Rosemary's impact story.
 */
function lof_upgrade_0636_rosemary() {
	if ( (int) get_option( 'lof_story_rosemary_rev', 0 ) >= 1 || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$stall    = lof_0636_photo( 'story-rosemary-fruit-stall', __( 'Rosemary at her fruit and vegetable stall in Dandora', 'light-of-future' ), __( 'Rosemary at her new fruit and vegetable stall.', 'light-of-future' ) );
	$portrait = lof_0636_photo( 'story-rosemary-portrait', __( 'Portrait of Rosemary Atieno Otieno', 'light-of-future' ), __( 'Rosemary Atieno Otieno, Dandora.', 'light-of-future' ) );
	$video    = 'https://drive.google.com/drive/folders/1YrRZ7bPE0EpoMOfXDBu_vgSCDNiqbdWH?usp=sharing';

	$p       = static function ( $t ) {
		return '<p>' . esc_html( $t ) . '</p>';
	};
	$content = $p( __( 'Rosemary Atieno Otieno is a widow living in Dandora, Nairobi. We met her during one of our food donations in Dandora, through our community coordinator Edith Atieno.', 'light-of-future' ) );
	$content .= $p( __( 'Rosemary shared with us the pain she has been going through. A road accident left her shoulder badly injured, and before that she had an operation on her stomach that did not go well. Although she healed, she has continued to live with a lot of pain.', 'light-of-future' ) );
	if ( $portrait ) {
		$content .= '<figure class="wp-block-image size-large">' . wp_get_attachment_image( $portrait, 'large', false, array( 'loading' => 'lazy' ) ) . '<figcaption>' . esc_html__( 'Rosemary Atieno Otieno, Dandora.', 'light-of-future' ) . '</figcaption></figure>';
	}
	$content .= $p( __( 'We decided to step in. Through our business empowerment programme, we supported Rosemary with money to start a business selling fruits, onions and vegetables, so she can earn an income of her own. Rosemary is the first beneficiary of the programme.', 'light-of-future' ) );
	$content .= '<p><a class="lof-btn lof-btn--sun" href="' . esc_url( $video ) . '" target="_blank" rel="noopener">' . esc_html__( 'Watch Rosemary\'s video', 'light-of-future' ) . '</a></p>';

	$data  = array(
		'post_type'    => 'lof_story',
		'post_status'  => 'publish',
		'post_name'    => 'rosemary',
		'post_title'   => __( 'Rosemary: a new start with her own fruit and vegetable stall', 'light-of-future' ),
		'post_excerpt' => __( 'Rosemary, a widow in Dandora, is the first beneficiary of our business empowerment programme.', 'light-of-future' ),
		'post_content' => $content,
	);
	$found = get_page_by_path( 'rosemary', OBJECT, 'lof_story' );
	if ( $found ) {
		$data['ID'] = $found->ID;
		$id         = wp_update_post( wp_slash( $data ) );
	} else {
		$id = wp_insert_post( wp_slash( $data ) );
	}
	if ( ! $id || is_wp_error( $id ) ) {
		return;
	}
	if ( $stall ) {
		set_post_thumbnail( $id, $stall );
	}
	update_post_meta( $id, 'lof_display_name', 'Rosemary' );
	update_post_meta( $id, 'lof_location', __( 'Dandora, Nairobi', 'light-of-future' ) );
	update_post_meta( $id, 'lof_consent', '1' );
	update_post_meta( $id, 'lof_consent_date', '2026-11-08' );
	if ( taxonomy_exists( 'lof_story_topic' ) ) {
		wp_set_object_terms( $id, __( 'Business empowerment', 'light-of-future' ), 'lof_story_topic', true );
	}
	if ( function_exists( 'litespeed_purge_all' ) ) {
		litespeed_purge_all();
	}
	do_action( 'litespeed_purge_all' );
	update_option( 'lof_story_rosemary_rev', 1, false );
}
add_action( 'admin_init', 'lof_upgrade_0636_rosemary', 40 );
