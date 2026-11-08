<?php
/**
 * 0.63.10: play Rosemary's and Hellen's story videos on the website instead of
 * linking to Google Drive. Videos (supplied by Francis Ouma Sikuku) are downloaded once from their shared
 * Google Drive files into uploads/lof-videos, so they play from this site and survive theme updates.
 *
 * @package Light_Of_Future
 */

defined( 'ABSPATH' ) || exit;

/**
 * Copy the story videos into uploads and link them to the stories. Runs once in wp-admin.
 */
function lof_upgrade_0640_videos() {
	if ( get_option( 'lof_videos_0640_rev' ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$up = wp_upload_dir();
	if ( ! empty( $up['error'] ) ) {
		return;
	}
	$dir = trailingslashit( $up['basedir'] ) . 'lof-videos';
	$url = trailingslashit( $up['baseurl'] ) . 'lof-videos';
	wp_mkdir_p( $dir );
	$ok    = true;
	$drive = array(
		'rosemary' => '1_u8_w18ynvouS_7ucG1bExd6i8j1zl10',
		'hellen'   => '1De9emUGCE3xLgy90wXR3-1GtJkgfqAZJ',
	);
	if ( function_exists( 'set_time_limit' ) ) {
		@set_time_limit( 600 ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
	}
	require_once ABSPATH . 'wp-admin/includes/file.php';
	foreach ( $drive as $slug => $drive_id ) {
		$poster_src = LOF_THEME_DIR . '/assets/video/story-' . $slug . '-poster.jpg';
		$poster_dst = $dir . '/story-' . $slug . '-poster.jpg';
		if ( ! file_exists( $poster_dst ) && file_exists( $poster_src ) ) {
			copy( $poster_src, $poster_dst );
		}
		$mp4 = $dir . '/story-' . $slug . '.mp4';
		if ( ! file_exists( $mp4 ) || filesize( $mp4 ) < 1000000 ) {
			$tmp = download_url( 'https://drive.usercontent.google.com/download?id=' . $drive_id . '&export=download&confirm=t', 300 );
			if ( is_wp_error( $tmp ) || filesize( $tmp ) < 1000000 ) {
				if ( ! is_wp_error( $tmp ) ) {
					wp_delete_file( $tmp );
				}
				$ok = false;
				continue;
			}
			if ( ! @rename( $tmp, $mp4 ) ) { // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
				copy( $tmp, $mp4 );
				wp_delete_file( $tmp );
			}
		}
		$post = get_page_by_path( $slug, OBJECT, 'lof_story' );
		if ( $post && file_exists( $mp4 ) ) {
			update_post_meta( $post->ID, 'lof_story_video', $url . '/story-' . $slug . '.mp4' );
			update_post_meta( $post->ID, 'lof_story_video_poster', $url . '/story-' . $slug . '-poster.jpg' );
		}
	}
	if ( ! $ok ) {
		return;
	}
	if ( function_exists( 'litespeed_purge_all' ) ) {
		litespeed_purge_all();
	}
	do_action( 'litespeed_purge_all' );
	update_option( 'lof_videos_0640_rev', 1, false );
}
add_action( 'admin_init', 'lof_upgrade_0640_videos', 42 );
