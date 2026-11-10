<?php
/**
 * 0.70.0 one-off upgrades (admin only, run on admin_init until they succeed).
 *
 * 1. Official phone number: Core setting "phone" becomes +254 716 385 485 (owner, 10 Nov 2026).
 *    Shown in the header, footer, Contact and FAQ. The WhatsApp number is a separate setting and
 *    is NOT changed. Guard option: lof_phone_0700.
 * 2. Community Development programme: the two agriculture project videos (bananas, maize and
 *    groundnuts) are added under the agriculture paragraph. The web-sized copies
 *    "programme-agriculture-1-web.mp4" and "programme-agriculture-2-web.mp4" are uploaded through
 *    the Media Library; posters ship with the theme (assets/img/agri/). If the videos are not
 *    uploaded yet nothing changes and it tries again. Guard option: lof_agri_videos_0700.
 *
 * @package LightOfFuture
 */

defined( 'ABSPATH' ) || exit;

/**
 * Set the official phone number.
 */
function lof_upgrade_0700_phone() {
	if ( get_option( 'lof_phone_0700' ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$set          = get_option( 'lof_core_settings', array() );
	$set          = is_array( $set ) ? $set : array();
	$set['phone'] = '+254 716 385 485';
	update_option( 'lof_core_settings', $set );
	if ( function_exists( 'litespeed_purge_all' ) ) {
		litespeed_purge_all();
	}
	do_action( 'litespeed_purge_all' );
	update_option( 'lof_phone_0700', 1, false );
}
add_action( 'admin_init', 'lof_upgrade_0700_phone', 45 );

/**
 * Find an uploaded video attachment by file name prefix.
 *
 * @param string $prefix File name prefix.
 * @return int Attachment ID or 0.
 */
function lof_0700_video( $prefix ) {
	global $wpdb;
	// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- one-off lookup in wp-admin.
	$rows = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT p.ID, m.meta_value AS file FROM {$wpdb->posts} p INNER JOIN {$wpdb->postmeta} m ON m.post_id = p.ID AND m.meta_key = '_wp_attached_file' WHERE p.post_type = 'attachment' AND p.post_mime_type = %s AND m.meta_value LIKE %s ORDER BY p.ID DESC LIMIT 10",
			'video/mp4',
			'%' . $wpdb->esc_like( $prefix ) . '%'
		)
	);
	foreach ( (array) $rows as $row ) {
		if ( 0 === strpos( basename( (string) $row->file ), $prefix ) ) {
			return (int) $row->ID;
		}
	}
	return 0;
}

/**
 * Add the agriculture videos to the Community Development programme.
 */
function lof_upgrade_0700_agri_videos() {
	if ( get_option( 'lof_agri_videos_0700' ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$a1 = lof_0700_video( 'programme-agriculture-1-web' );
	$a2 = lof_0700_video( 'programme-agriculture-2-web' );
	if ( ! $a1 || ! $a2 ) {
		return; // Not uploaded yet: try again on the next admin load.
	}
	$post = get_page_by_path( 'community-development', OBJECT, 'lof_programme' );
	if ( ! $post ) {
		return;
	}
	if ( false !== strpos( $post->post_content, 'lof-agri-videos' ) ) {
		update_option( 'lof_agri_videos_0700', 1, false );
		return;
	}
	$img   = get_stylesheet_directory_uri() . '/assets/img/agri/';
	$items = array(
		array( wp_get_attachment_url( $a1 ), $img . 'programme-agriculture-1-poster.jpg', __( 'Our banana project.', 'light-of-future' ) ),
		array( wp_get_attachment_url( $a2 ), $img . 'programme-agriculture-2-poster.jpg', __( 'In the field with our agriculture projects.', 'light-of-future' ) ),
	);
	$html = '<!-- wp:html --><div class="lof-agri-videos">';
	foreach ( $items as $it ) {
		$html .= sprintf(
			'<figure class="lof-agri-video"><video controls playsinline preload="none" poster="%2$s" src="%1$s"></video><figcaption>%3$s</figcaption></figure>',
			esc_url( $it[0] ),
			esc_url( $it[1] ),
			esc_html( $it[2] )
		);
	}
	$html   .= '</div><!-- /wp:html -->';
	$content = $post->post_content;
	$pos     = stripos( $content, 'groundnuts' );
	$end     = false !== $pos ? stripos( $content, '</p>', $pos ) : false;
	if ( false !== $end ) {
		$after = $end + 4;
		// Step past a closing paragraph block comment, if present.
		if ( preg_match( '#^\s*<!-- /wp:paragraph -->#', substr( $content, $after ), $m ) ) {
			$after += strlen( $m[0] );
		}
		$content = substr( $content, 0, $after ) . "\n\n" . $html . "\n\n" . substr( $content, $after );
	} else {
		$content .= "\n\n" . $html;
	}
	wp_update_post( array( 'ID' => $post->ID, 'post_content' => wp_slash( $content ) ) );
	if ( function_exists( 'litespeed_purge_all' ) ) {
		litespeed_purge_all();
	}
	do_action( 'litespeed_purge_all' );
	update_option( 'lof_agri_videos_0700', 1, false );
}
add_action( 'admin_init', 'lof_upgrade_0700_agri_videos', 46 );

/**
 * 0.70.1: the 0.70.0 run built the poster URLs from the parent theme (hello-elementor), so the
 * posters did not load. Point them at this theme. Guard option: lof_agri_posters_0701.
 */
function lof_upgrade_0701_agri_posters() {
	if ( get_option( 'lof_agri_posters_0701' ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$post = get_page_by_path( 'community-development', OBJECT, 'lof_programme' );
	if ( ! $post ) {
		return;
	}
	$bad = '/themes/' . get_template() . '/assets/img/agri/';
	$ok  = '/themes/' . get_stylesheet() . '/assets/img/agri/';
	if ( $bad !== $ok && false !== strpos( $post->post_content, $bad ) ) {
		wp_update_post( array( 'ID' => $post->ID, 'post_content' => wp_slash( str_replace( $bad, $ok, $post->post_content ) ) ) );
		if ( function_exists( 'litespeed_purge_all' ) ) {
			litespeed_purge_all();
		}
		do_action( 'litespeed_purge_all' );
	}
	update_option( 'lof_agri_posters_0701', 1, false );
}
add_action( 'admin_init', 'lof_upgrade_0701_agri_posters', 47 );

/**
 * 0.70.2 (owner): show the two agriculture videos in the same video row as the goat videos
 * (the media plugin's #lofm-goats .lofm__videos), styled like them, instead of a separate block.
 * One-off: removes the 0.70.0 block from the programme content and stores the video URLs in
 * option lof_agri_video_urls. Guard option: lof_agri_move_0702.
 */
function lof_upgrade_0702_agri_move() {
	if ( get_option( 'lof_agri_move_0702' ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$a1 = lof_0700_video( 'programme-agriculture-1-web' );
	$a2 = lof_0700_video( 'programme-agriculture-2-web' );
	$post = get_page_by_path( 'community-development', OBJECT, 'lof_programme' );
	if ( ! $a1 || ! $a2 || ! $post ) {
		return;
	}
	update_option( 'lof_agri_video_urls', array( wp_get_attachment_url( $a1 ), wp_get_attachment_url( $a2 ) ), true );
	$content = preg_replace( '#\s*<!-- wp:html --><div class="lof-agri-videos">.*?</div><!-- /wp:html -->#s', '', $post->post_content, 1, $count );
	if ( $count ) {
		wp_update_post( array( 'ID' => $post->ID, 'post_content' => wp_slash( $content ) ) );
	}
	if ( function_exists( 'litespeed_purge_all' ) ) {
		litespeed_purge_all();
	}
	do_action( 'litespeed_purge_all' );
	update_option( 'lof_agri_move_0702', 1, false );
}
add_action( 'admin_init', 'lof_upgrade_0702_agri_move', 48 );

