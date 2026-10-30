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
	if ( apply_filters( 'lof_show_mobile_donate', true ) && ! is_page( 'donate' ) ) {
		printf( '<div class="lof-mbar"><a class="lof-btn lof-btn--sun" href="%1$s">%2$s</a></div>', esc_url( lof_donate_url() ), esc_html( lof_setting( 'donate_label', __( 'Support the Future', 'light-of-future' ) ) ) );
	}
}
add_action( 'wp_footer', 'lof_floating_actions', 5 );
