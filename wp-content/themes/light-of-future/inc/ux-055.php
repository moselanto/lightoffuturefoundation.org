<?php
/**
 * 0.55.0 UX: quick contact choices at the top of Contact, and product-page
 * delivery and care details.
 *
 * @package LightOfFuture
 */

defined( 'ABSPATH' ) || exit;

/**
 * Quick contact strip (Call, WhatsApp, Email) right under the Contact hero.
 *
 * @param string $content Page content.
 * @return string
 */
function lof_contact_quick( $content ) {
	if ( is_admin() || ! is_page( 'contact' ) || ! in_the_loop() || false !== strpos( $content, 'lof-quick' ) ) {
		return $content;
	}
	$phone = lof_setting( 'phone' );
	$email = lof_setting( 'email' );
	$items = '';
	if ( $phone ) {
		$items .= sprintf( '<li><a class="lof-quick__item" href="%1$s"><span class="lof-quick__k">%2$s</span><span class="lof-quick__v">%3$s</span></a></li>', esc_url( 'tel:' . preg_replace( '/[^0-9+]/', '', $phone ) ), esc_html__( 'Call us', 'light-of-future' ), esc_html( $phone ) );
	}
	if ( function_exists( 'lof_wa_link' ) ) {
		$items .= sprintf( '<li><a class="lof-quick__item lof-quick__item--wa" href="%1$s" target="_blank" rel="noopener noreferrer"><span class="lof-quick__k">%2$s</span><span class="lof-quick__v">%3$s</span></a></li>', esc_url( lof_wa_link( __( 'Hello Light of Future Foundation, ', 'light-of-future' ) ) ), esc_html__( 'WhatsApp', 'light-of-future' ), esc_html__( 'Chat with our team', 'light-of-future' ) );
	}
	if ( $email ) {
		$items .= sprintf( '<li><a class="lof-quick__item" href="%1$s"><span class="lof-quick__k">%2$s</span><span class="lof-quick__v">%3$s</span></a></li>', esc_url( 'mailto:' . antispambot( $email ) ), esc_html__( 'Email', 'light-of-future' ), esc_html( antispambot( $email ) ) );
	}
	if ( '' === $items ) {
		return $content;
	}
	$strip = '<section class="lof-quick" aria-label="' . esc_attr__( 'Ways to reach us', 'light-of-future' ) . '"><div class="lof-container"><ul class="lof-quick__list">' . $items . '</ul></div></section>';
	$pos   = strpos( $content, '</section>' );
	if ( false === $pos ) {
		return $strip . $content;
	}
	// After the hero's closing tags (its section and Elementor wrappers).
	$end = strpos( $content, 'elementor-top-section', $pos );
	if ( false !== $end ) {
		$start = strrpos( substr( $content, 0, $end ), '<section' );
		if ( false !== $start ) {
			return substr( $content, 0, $start ) . $strip . substr( $content, $start );
		}
	}
	return substr( $content, 0, $pos + 10 ) . $strip . substr( $content, $pos + 10 );
}
add_filter( 'the_content', 'lof_contact_quick', 99 );

/**
 * Contact page: the reply promise is "within 2 working days" (also where the
 * text was saved in the page editor).
 *
 * @param string $content Page content.
 * @return string
 */
function lof_contact_reply_time( $content ) {
	if ( is_admin() || ! is_page( 'contact' ) ) {
		return $content;
	}
	return str_replace( array( 'reply as soon as we can', 'get back to you as soon as we can', 'as soon as we can' ), array( 'reply within 2 working days', 'get back to you within 2 working days', 'within 2 working days' ), $content );
}
add_filter( 'the_content', 'lof_contact_reply_time', 100 );

/**
 * Delivery and care details on product pages.
 */
function lof_product_details_more() {
	global $product;
	if ( ! $product instanceof WC_Product ) {
		return;
	}
	$sku  = (string) $product->get_sku();
	$care = __( 'Beadwork: keep dry and store flat or hanging, away from strong sun. Wipe gently with a soft dry cloth.', 'light-of-future' );
	if ( in_array( $sku, array( 'LOF-BAG', 'LOF-BELT' ), true ) ) {
		$care = __( 'Leather: wipe with a soft dry cloth, keep away from long periods of rain and direct heat, and condition now and then with a leather balm. Beadwork: wipe gently, do not soak.', 'light-of-future' );
	} elseif ( 'LOF-SANDALS' === $sku ) {
		$care = __( 'Let the sandals dry naturally if they get wet, away from direct heat. Wipe the beadwork gently with a soft dry cloth. Tell us your shoe size when you order.', 'light-of-future' );
	}
	?>
	<section class="lof-pdinfo" aria-label="<?php esc_attr_e( 'Delivery, care and returns', 'light-of-future' ); ?>">
		<details open>
			<summary><?php esc_html_e( 'Delivery', 'light-of-future' ); ?></summary>
			<p><?php esc_html_e( 'We deliver in Nairobi, to other towns in Kenya and abroad. Delivery is charged separately: our team confirms the cost and timing with you on WhatsApp before you pay, so there are no surprises.', 'light-of-future' ); ?></p>
		</details>
		<details>
			<summary><?php esc_html_e( 'Care', 'light-of-future' ); ?></summary>
			<p><?php echo esc_html( $care ); ?></p>
		</details>
		<details>
			<summary><?php esc_html_e( 'Handmade, so each piece is unique', 'light-of-future' ); ?></summary>
			<p><?php esc_html_e( 'Every piece is made by hand, so colours and sizes can vary slightly from the photos. If you need exact measurements or a specific colour, ask us on WhatsApp before you order and we will check with the maker.', 'light-of-future' ); ?></p>
		</details>
	</section>
	<?php
}
add_action( 'woocommerce_single_product_summary', 'lof_product_details_more', 45 );
