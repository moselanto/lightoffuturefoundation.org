<?php
/**
 * Floating WhatsApp button (only when a WhatsApp number is set) and a
 * mobile "Donate" bar.
 *
 * @package LightOfFuture
 */

defined( 'ABSPATH' ) || exit;

/**
 * Print floating actions in the footer.
 */
function lof_floating_actions() {
	if ( is_admin() || ( class_exists( '\\Elementor\\Plugin' ) && isset( \Elementor\Plugin::$instance->preview ) && \Elementor\Plugin::$instance->preview->is_preview_mode() ) ) {
		return;
	}
	$wa = preg_replace( '/[^0-9]/', '', lof_setting( 'whatsapp' ) );
	if ( $wa && apply_filters( 'lof_show_whatsapp', true ) ) {
		printf(
			'<a class="lof-wa" href="%1$s" target="_blank" rel="noopener" aria-label="%2$s"><svg viewBox="0 0 32 32" width="28" height="28" aria-hidden="true" focusable="false"><path fill="currentColor" d="M16 3a13 13 0 0 0-11.2 19.6L3 29l6.6-1.7A13 13 0 1 0 16 3zm0 23.6c-2 0-4-.6-5.7-1.6l-.4-.2-3.9 1 1-3.8-.3-.4A10.6 10.6 0 1 1 16 26.6zm5.8-7.9c-.3-.2-1.9-.9-2.2-1s-.5-.2-.7.2-.8 1-1 1.2-.4.2-.7.1a8.7 8.7 0 0 1-4.3-3.8c-.3-.6.3-.5.9-1.7.1-.2 0-.4 0-.5l-1-2.4c-.3-.6-.5-.5-.7-.5h-.6a1.2 1.2 0 0 0-.9.4 3.6 3.6 0 0 0-1.1 2.7 6.3 6.3 0 0 0 1.3 3.3 14.4 14.4 0 0 0 5.5 4.9c2 .9 2.8 1 3.9.8a3.3 3.3 0 0 0 2.1-1.5 2.7 2.7 0 0 0 .2-1.5c-.1-.1-.3-.2-.7-.4z"/></svg></a>',
			esc_url( 'https://wa.me/' . $wa ),
			esc_attr__( 'Chat with us on WhatsApp', 'light-of-future' )
		);
	}
	if ( apply_filters( 'lof_show_mobile_donate', true ) ) {
		lof_mobile_tabbar();
	}
}
add_action( 'wp_footer', 'lof_floating_actions', 5 );

/**
 * Fixed bottom navigation on phones: Home, Shop, Donate (raised, centre), Stories, Menu. Focus areas are reached through the Menu (Programmes).
 */
function lof_mobile_tabbar() {
	$path  = trim( (string) wp_parse_url( isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '/', PHP_URL_PATH ), '/' );
	$home  = trim( (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH ), '/' );
	$path  = trim( $home && 0 === strpos( $path, $home ) ? substr( $path, strlen( $home ) ) : $path, '/' );
	$first = strtok( $path, '/' );
	$icons = array(
		'home'   => '<path d="M12 3 2.5 11h2.6v9.5h5.4V15h3v5.5h5.4V11h2.6z"/>',
		'areas'  => '<path d="M4 4h7v7H4zm9 0h7v7h-7zM4 13h7v7H4zm9 0h7v7h-7z"/>',
		'heart'  => '<path d="M12 21s-7.5-4.6-9.5-9.3C1.1 8.3 3.2 5 6.6 5c2 0 3.4 1.1 4.4 2.5C12 6.1 13.4 5 15.4 5c3.4 0 5.5 3.3 4.1 6.7C19.5 16.4 12 21 12 21z"/>',
		'story'  => '<path d="M5 3h11l3 3v15H5zm2 5v2h10V8zm0 4v2h10v-2zm0 4v2h7v-2z"/>',
		'shop'   => '<path d="M7 7V6a5 5 0 0 1 10 0v1h3.2l-1 14H4.8l-1-14zm2 0h6V6a3 3 0 0 0-6 0zm-1.5 4a1.2 1.2 0 1 0 0-2.4 1.2 1.2 0 0 0 0 2.4zm9 0a1.2 1.2 0 1 0 0-2.4 1.2 1.2 0 0 0 0 2.4z"/>',
		'menu'   => '<path d="M3 6h18v2H3zm0 5h18v2H3zm0 5h18v2H3z"/>',
	);
	$shop_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
	$items    = array(
		array( 'home', __( 'Home', 'light-of-future' ), home_url( '/' ), '' === $path || false === $first ),
		array( 'shop', __( 'Shop', 'light-of-future' ), $shop_url, in_array( $first, array( 'shop', 'product', 'product-category', 'product-tag' ), true ) ),
		array( 'heart', __( 'Donate', 'light-of-future' ), lof_donate_url(), 'donate' === $first ),
		array( 'story', __( 'Stories', 'light-of-future' ), home_url( '/stories/' ), in_array( $first, array( 'stories', 'story', 'impact' ), true ) ),
	);
	echo '<nav class="lof-tabbar" aria-label="' . esc_attr__( 'Quick links', 'light-of-future' ) . '"><ul>';
	foreach ( $items as $it ) {
		printf(
			'<li class="lof-tabbar__item lof-tabbar__item--%1$s%2$s"><a href="%3$s"%4$s><span class="lof-tabbar__ico"><svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true" focusable="false" fill="currentColor">%5$s</svg></span><span class="lof-tabbar__label">%6$s</span></a></li>',
			esc_attr( $it[0] ),
			$it[3] ? ' is-current' : '',
			esc_url( $it[2] ),
			$it[3] ? ' aria-current="page"' : '',
			$icons[ $it[0] ], // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG.
			esc_html( $it[1] )
		);
	}
	printf(
		'<li class="lof-tabbar__item lof-tabbar__item--menu"><button type="button" data-lof-tabmenu aria-controls="lof-primary-nav" aria-expanded="false"><span class="lof-tabbar__ico"><svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true" focusable="false" fill="currentColor">%1$s</svg></span><span class="lof-tabbar__label">%2$s</span></button></li>',
		$icons['menu'], // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG.
		esc_html__( 'Menu', 'light-of-future' )
	);
	echo '</ul></nav>';
}
