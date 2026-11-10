<?php
/**
 * 0.63.13: front-end story video player (moved out of the admin-only upgrade file).
 *
 * @package Light_Of_Future
 */

defined( 'ABSPATH' ) || exit;

/**
 * On a story with a hosted video, replace the Google Drive "Watch" button with an
 * on-page player (or add the player at the end if there is no button).
 *
 * @param string $content Post content.
 * @return string
 */
function lof_story_video_player( $content ) {
	$id = get_the_ID();
	if ( ! $id || 'lof_story' !== get_post_type( $id ) || false !== strpos( $content, 'lof-story-video' ) ) {
		return $content;
	}
	$video  = get_post_meta( $id, 'lof_story_video', true );
	$poster = get_post_meta( $id, 'lof_story_video_poster', true );
	$up   = wp_upload_dir();
	$slug = get_post_field( 'post_name', $id );
	$dir  = trailingslashit( $up['basedir'] ) . 'lof-videos/';
	$url  = trailingslashit( $up['baseurl'] ) . 'lof-videos/';
	if ( ! $video ) {
		if ( ! $slug ) {
			return $content;
		}
		// 0.66.0: prefer a web-sized sibling (story-{slug}-web.mp4) over the original.
		if ( file_exists( $dir . 'story-' . $slug . '-web.mp4' ) ) {
			$video = $url . 'story-' . $slug . '-web.mp4';
		} elseif ( file_exists( $dir . 'story-' . $slug . '.mp4' ) ) {
			$video = $url . 'story-' . $slug . '.mp4';
		} else {
			return $content;
		}
	}
	if ( ! $poster && $slug && file_exists( $dir . 'story-' . $slug . '-poster.jpg' ) ) {
		$poster = $url . 'story-' . $slug . '-poster.jpg';
	}
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
add_filter( 'the_content', 'lof_story_video_player', 99 );
