<?php
/**
 * Community Development agriculture videos on the public site (0.70.2, loaded front end since 0.70.3).
 * Puts the two agriculture videos first in the goat video row (#lofm-goats .lofm__videos)
 * on Community Development and, since 0.70.5, in the same section on the Gallery page.
 * URLs come from option lof_agri_video_urls (set by inc/upgrade-0700.php).
 *
 * @package LightOfFuture
 */

defined( 'ABSPATH' ) || exit;

/**
 * Agriculture video figures, in the media plugin's video markup.
 *
 * @return string
 */
function lof_0702_agri_figures() {
	$urls = get_option( 'lof_agri_video_urls' );
	if ( ! is_array( $urls ) || count( $urls ) < 2 ) {
		return '';
	}
	$img   = get_stylesheet_directory_uri() . '/assets/img/agri/';
	$items = array(
		array( $urls[0], $img . 'programme-agriculture-1-poster.jpg', __( 'Our banana project.', 'light-of-future' ), '360/640' ),
		array( $urls[1], $img . 'programme-agriculture-2-poster.jpg', __( 'In the field with our agriculture projects.', 'light-of-future' ), '360/648' ),
	);
	$html = '';
	foreach ( $items as $it ) {
		$html .= sprintf(
			'<figure class="lofm__video is-portrait lof-agri-video"><div class="lofm__frame" style="aspect-ratio:%4$s"><video controls playsinline preload="none" poster="%2$s"><source src="%1$s" type="video/mp4"></video></div><figcaption>%3$s</figcaption></figure>',
			esc_url( $it[0] ),
			esc_url( $it[1] ),
			esc_html( $it[2] ),
			esc_attr( $it[3] )
		);
	}
	return $html;
}

/**
 * Put the agriculture videos first in the goat video row on Community Development.
 * If that row is not in the output, they go at the end of the content instead.
 *
 * @param string $content Rendered content.
 * @return string
 */
function lof_0702_agri_in_goat_row( $content ) {
	if ( is_admin() ) {
		return $content;
	}
	$is_prog    = is_singular( 'lof_programme' ) && 'community-development' === get_post_field( 'post_name', get_queried_object_id() );
	$is_gallery = is_page( 'gallery' );
	if ( ! $is_prog && ! $is_gallery ) {
		return $content;
	}
	if ( false !== strpos( $content, 'lof-agri-video' ) ) {
		return $content;
	}
	$figs = lof_0702_agri_figures();
	if ( '' === $figs ) {
		return $content;
	}
	$out = preg_replace( '#(id="lofm-goats"[^>]*>(?:\s*<h2 class="lofm__title">.*?</h2>)?\s*<div class="lofm__videos">)#s', '$1' . str_replace( '$', '\$', $figs ), $content, 1, $count );
	if ( $count && is_string( $out ) ) {
		return $out;
	}
	if ( $is_gallery ) {
		return $content; // Gallery: only inside the goat section, never appended.
	}
	return $content . '<section class="lofm"><div class="lofm__videos">' . $figs . '</div></section>';
}
add_filter( 'the_content', 'lof_0702_agri_in_goat_row', 999 );

/**
 * 0.70.6: Rosemary's stall photos after Margaret's media on Community Empowerment (business
 * empowerment start-ups), in the media plugin's photo grid style. IDs from option lof_empower_photo_ids.
 *
 * @param string $content Rendered content.
 * @return string
 */
function lof_0706_empower_photos( $content ) {
	if ( is_admin() || ! is_singular( 'lof_programme' ) || 'community-empowerment' !== get_post_field( 'post_name', get_queried_object_id() ) ) {
		return $content;
	}
	if ( false !== strpos( $content, 'lofm-rosemary-stall' ) ) {
		return $content;
	}
	$ids = get_option( 'lof_empower_photo_ids' );
	if ( ! is_array( $ids ) || ! $ids ) {
		return $content;
	}
	$caps  = array( __( 'Rosemary at her fruit and vegetable stall.', 'light-of-future' ), __( 'Rosemary serving at her stall.', 'light-of-future' ) );
	$items = '';
	foreach ( array_values( $ids ) as $i => $id ) {
		$full = wp_get_attachment_url( $id );
		$img  = wp_get_attachment_image( $id, 'medium_large', false, array( 'loading' => 'lazy', 'alt' => $caps[ $i ] ?? $caps[0], 'sizes' => '(max-width: 700px) 46vw, 300px' ) );
		if ( ! $full || ! $img ) {
			continue;
		}
		$items .= '<li class="lofm__item"><figure><a class="lofm__link" href="' . esc_url( $full ) . '" target="_blank" rel="noopener">' . $img . '</a><figcaption>' . esc_html( $caps[ $i ] ?? $caps[0] ) . '</figcaption></figure></li>';
	}
	if ( '' === $items ) {
		return $content;
	}
	$block = '<section class="lofm" id="lofm-rosemary-stall"><ul class="lofm__grid">' . $items . '</ul></section>';
	$out   = preg_replace( '#(<section class="lofm" id="lofm-margaret">.*?</section>)#s', '$1' . str_replace( '$', '\$', $block ), $content, 1, $count );
	return ( $count && is_string( $out ) ) ? $out : $content . $block;
}
add_filter( 'the_content', 'lof_0706_empower_photos', 999 );
