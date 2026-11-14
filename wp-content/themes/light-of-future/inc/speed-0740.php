<?php
/**
 * 0.74.0 Gallery: less main-thread work on phones.
 *
 * The Gallery holds several long photo and video sections. Sections far below the screen are
 * now skipped by the browser's style and layout work until they come near the screen
 * (content-visibility: auto), with a placeholder height so the scroll bar stays stable.
 * The filter pills, Show all, lightbox and video buttons are unchanged.
 * Off switch: add_filter( 'lof_gallery_cv', '__return_false' );
 *
 * @package LightOfFuture
 */

defined( 'ABSPATH' ) || exit;

/**
 * Print the Gallery rule in the head (Gallery page only).
 */
function lof_gallery_cv_css() {
	if ( is_admin() || ! is_page( 'gallery' ) || isset( $_GET['elementor-preview'] ) || ! apply_filters( 'lof_gallery_cv', true ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return;
	}
	echo '<style id="lof-gallery-cv">.lof-page-gallery .lofm-all section.lofm{content-visibility:auto;contain-intrinsic-size:auto 1100px}@media (max-width:640px){.lof-page-gallery .lofm-all section.lofm{contain-intrinsic-size:auto 1600px}}</style>' . "\n";
}
add_action( 'wp_head', 'lof_gallery_cv_css', 40 );
