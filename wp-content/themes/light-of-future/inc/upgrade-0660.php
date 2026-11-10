<?php
/**
 * 0.66.0 one-off upgrade (admin only, runs on admin_init until it succeeds).
 *
 * Rosemary's original story video (uploads/lof-videos/story-rosemary.mp4) is about 62 MB.
 * A web-sized copy named "story-rosemary-web.mp4" is uploaded through the Media Library
 * (it lands in uploads/YYYY/MM/). Once that attachment exists, this sets post meta
 * lof_story_video on Rosemary's story to the attachment URL and keeps her existing poster.
 * If the attachment is not there yet, nothing changes and the option is NOT set, so it
 * tries again on the next wp-admin page load.
 *
 * Guard option: lof_rosemary_web_0660 (stores the attachment ID; delete it to run again).
 *
 * @package LightOfFuture
 */

defined( 'ABSPATH' ) || exit;

/**
 * Find the uploaded web-sized Rosemary video.
 *
 * @return int Attachment ID or 0.
 */
function lof_0660_rosemary_attachment() {
	global $wpdb;
	// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- one-off lookup in wp-admin.
	$rows = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT p.ID, m.meta_value AS file FROM {$wpdb->posts} p INNER JOIN {$wpdb->postmeta} m ON m.post_id = p.ID AND m.meta_key = '_wp_attached_file' WHERE p.post_type = 'attachment' AND p.post_mime_type = %s AND m.meta_value LIKE %s ORDER BY p.ID DESC LIMIT 10",
			'video/mp4',
			'%' . $wpdb->esc_like( 'story-rosemary-web' ) . '%'
		)
	);
	foreach ( (array) $rows as $row ) {
		if ( 0 === strpos( basename( (string) $row->file ), 'story-rosemary-web' ) ) {
			return (int) $row->ID;
		}
	}
	return 0;
}

/**
 * Rosemary's story post.
 *
 * @return WP_Post|null
 */
function lof_0660_rosemary_story() {
	$post = get_page_by_path( 'rosemary', OBJECT, 'lof_story' );
	if ( $post ) {
		return $post;
	}
	$post = get_post( 564 ); // Rosemary's story per docs/HANDOVER.md.
	if ( $post && 'lof_story' === $post->post_type && false !== strpos( $post->post_name, 'rosemary' ) ) {
		return $post;
	}
	$found = get_posts(
		array(
			'post_type'      => 'lof_story',
			'post_status'    => 'any',
			'posts_per_page' => 20,
			'no_found_rows'  => true,
		)
	);
	foreach ( $found as $p ) {
		if ( false !== strpos( $p->post_name, 'rosemary' ) ) {
			return $p;
		}
	}
	return null;
}

/**
 * Point Rosemary's story player at the web-sized video, once it is uploaded.
 */
function lof_upgrade_0660_rosemary_web() {
	if ( get_option( 'lof_rosemary_web_0660' ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$att = lof_0660_rosemary_attachment();
	if ( ! $att ) {
		return; // Not uploaded yet: try again on the next admin load.
	}
	$story = lof_0660_rosemary_story();
	$url   = wp_get_attachment_url( $att );
	if ( ! $story || ! $url ) {
		return;
	}
	// Keep the existing poster: if none is stored in meta, store the one the player used.
	if ( ! get_post_meta( $story->ID, 'lof_story_video_poster', true ) ) {
		$up     = wp_upload_dir();
		$poster = 'lof-videos/story-' . $story->post_name . '-poster.jpg';
		if ( file_exists( trailingslashit( $up['basedir'] ) . $poster ) ) {
			update_post_meta( $story->ID, 'lof_story_video_poster', esc_url_raw( trailingslashit( $up['baseurl'] ) . $poster ) );
		}
	}
	update_post_meta( $story->ID, 'lof_story_video', esc_url_raw( $url ) );
	if ( function_exists( 'litespeed_purge_all' ) ) {
		litespeed_purge_all();
	}
	do_action( 'litespeed_purge_all' );
	update_option( 'lof_rosemary_web_0660', $att, false );
}
add_action( 'admin_init', 'lof_upgrade_0660_rosemary_web', 44 );
