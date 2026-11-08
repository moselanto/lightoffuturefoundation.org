<?php
/**
 * 0.63.10: play Rosemary's and Hellen's story videos on the website instead of
 * linking to Google Drive. Videos (supplied by Francis Ouma Sikuku, compressed for
 * the web) are copied once into uploads/lof-videos so they survive theme updates.
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
	$ok = true;
	foreach ( array( 'rosemary', 'hellen' ) as $slug ) {
		foreach ( array( 'story-' . $slug . '.mp4', 'story-' . $slug . '-poster.jpg' ) as $file ) {
			$src = LOF_THEME_DIR . '/assets/video/' . $file;
			$dst = $dir . '/' . $file;
			if ( ! file_exists( $dst ) || filesize( $dst ) !== filesize( $src ) ) {
				if ( ! file_exists( $src ) || ! copy( $src, $dst ) ) {
					$ok = false;
				}
			}
		}
		$post = get_page_by_path( $slug, OBJECT, 'lof_story' );
		if ( $post && file_exists( $dir . '/story-' . $slug . '.mp4' ) ) {
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

/**
 * On a story with a hosted video, replace the Google Drive "Watch" button with an
 * on-page player (or add the player at the end if there is no button).
 *
 * @param string $content Post content.
 * @return string
 */
function lof_story_video_player( $content ) {
	if ( ! is_singular( 'lof_story' ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}
	$id    = get_the_ID();
	$video = get_post_meta( $id, 'lof_story_video', true );
	if ( ! $video ) {
		return $content;
	}
	$poster = get_post_meta( $id, 'lof_story_video_poster', true );
	$name   = get_post_meta( $id, 'lof_display_name', true );
	$label  = $name ? sprintf( /* translators: %s: name */ __( 'Watch %s\'s story', 'light-of-future' ), $name ) : __( 'Watch the story', 'light-of-future' );
	$player = '<figure class="lof-story-video"><video controls playsinline preload="none"'
		. ( $poster ? ' poster="' . esc_url( $poster ) . '"' : '' )
		. ' aria-label="' . esc_attr( $label ) . '"><source src="' . esc_url( $video ) . '" type="video/mp4"></video>'
		. '<figcaption>' . esc_html( $label ) . '</figcaption></figure>';
	$count   = 0;
	$content = preg_replace( '#<p>\s*<a[^>]+drive\.google\.com[^>]*>.*?</a>\s*</p>#is', $player, $content, 1, $count );
	if ( ! $count ) {
		$content .= $player;
	}
	return preg_replace( '#<p>\s*<a[^>]+drive\.google\.com[^>]*>.*?</a>\s*</p>#is', '', $content );
}
add_filter( 'the_content', 'lof_story_video_player', 20 );
