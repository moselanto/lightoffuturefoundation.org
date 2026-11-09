<?php
/**
 * 0.63.15 one-off: Rosemary's fruit-stall photo cuts off the top of her head in
 * every wide crop, so her well-framed portrait becomes the story's main photo
 * (story page top, Impact, Stories and Home cards) and the stall photo moves
 * into the story text. Runs once in wp-admin.
 *
 * @package Light_Of_Future
 */

defined( 'ABSPATH' ) || exit;

/**
 * Swap Rosemary's main and inline photos.
 */
function lof_upgrade_0641_rosemary() {
	if ( get_option( 'lof_rosemary_0641_rev' ) || ! current_user_can( 'manage_options' ) || ! function_exists( 'lof_0636_photo' ) ) {
		return;
	}
	$post = get_page_by_path( 'rosemary', OBJECT, 'lof_story' );
	if ( ! $post ) {
		return;
	}
	$portrait = lof_0636_photo( 'story-rosemary-portrait', __( 'Portrait of Rosemary Atieno Otieno', 'light-of-future' ), __( 'Rosemary Atieno Otieno, Dandora.', 'light-of-future' ) );
	$stall    = lof_0636_photo( 'story-rosemary-fruit-stall', __( 'Rosemary at her fruit and vegetable stall in Dandora', 'light-of-future' ), __( 'Rosemary at her new fruit and vegetable stall.', 'light-of-future' ) );
	if ( ! $portrait || ! $stall ) {
		return;
	}
	set_post_thumbnail( $post->ID, $portrait );
	$figure  = '<figure class="wp-block-image size-large">' . wp_get_attachment_image( $stall, 'large', false, array( 'loading' => 'lazy' ) ) . '<figcaption>' . esc_html__( 'Rosemary at her new fruit and vegetable stall in Dandora.', 'light-of-future' ) . '</figcaption></figure>';
	$content = preg_replace( '#<figure class="wp-block-image[^"]*">(?:(?!</figure>).)*story-rosemary-portrait(?:(?!</figure>).)*</figure>#s', $figure, $post->post_content, 1, $count );
	if ( $count ) {
		wp_update_post( wp_slash( array( 'ID' => $post->ID, 'post_content' => $content ) ) );
	}
	if ( function_exists( 'litespeed_purge_all' ) ) {
		litespeed_purge_all();
	}
	do_action( 'litespeed_purge_all' );
	update_option( 'lof_rosemary_0641_rev', 1, false );
}
add_action( 'admin_init', 'lof_upgrade_0641_rosemary', 43 );
