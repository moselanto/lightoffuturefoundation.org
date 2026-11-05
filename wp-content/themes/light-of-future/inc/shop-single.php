<?php
/**
 * Single product page presentation (0.52.0), matching the shop page:
 * category eyebrow, clean price, design chips, Order on WhatsApp card,
 * trust points, "How ordering works" and a tidy details section.
 *
 * @package LightOfFuture
 */

defined( 'ABSPATH' ) || exit;

/**
 * KSh prices without decimals ("KSh 7,500", not "KSh 7,500.00").
 *
 * @return int
 */
function lof_price_decimals() {
	return 0;
}
add_filter( 'wc_get_price_decimals', 'lof_price_decimals' );

/**
 * Design names offered for a product (same source as the order form).
 *
 * @param WC_Product $product Product.
 * @return string[]
 */
function lof_product_designs( $product ) {
	if ( ! preg_match( '/data-designs="([^"]*)"/', lof_order_attrs( $product ), $m ) ) {
		return array();
	}
	$list = json_decode( html_entity_decode( $m[1], ENT_QUOTES ), true );
	return is_array( $list ) ? array_filter( array_map( 'strval', $list ) ) : array();
}

/**
 * Rearrange the summary while ordering goes through WhatsApp.
 */
function lof_single_layout() {
	if ( ! function_exists( 'is_product' ) || ! is_product() ) {
		return;
	}
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
	if ( ! lof_shop_cart_enabled() ) {
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
	}
	remove_action( 'woocommerce_single_product_summary', 'lof_shop_single_wa_button', 32 );
}
add_action( 'wp', 'lof_single_layout', 20 );

/**
 * Category eyebrow above the title.
 */
function lof_single_eyebrow() {
	global $product;
	if ( ! $product instanceof WC_Product ) {
		return;
	}
	$terms = get_the_terms( $product->get_id(), 'product_cat' );
	$name  = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : __( 'Handmade in Kenya', 'light-of-future' );
	$link  = ( $terms && ! is_wp_error( $terms ) ) ? get_term_link( $terms[0] ) : wc_get_page_permalink( 'shop' );
	printf( '<p class="lof-pd__eyebrow"><a href="%1$s">%2$s</a><span aria-hidden="true">&middot;</span><span>%3$s</span></p>', esc_url( $link ), esc_html( $name ), esc_html__( 'Handmade in Kenya', 'light-of-future' ) );
}
add_action( 'woocommerce_single_product_summary', 'lof_single_eyebrow', 3 );

/**
 * Designs, Order on WhatsApp card and trust points.
 */
function lof_single_order_card() {
	global $product;
	if ( ! $product instanceof WC_Product ) {
		return;
	}
	$designs = lof_product_designs( $product );
	?>
	<div class="lof-pd__order">
		<?php if ( $designs ) : ?>
			<div class="lof-pd__designs">
				<p class="lof-pd__label"><?php echo esc_html( sprintf( /* translators: %d: number of designs. */ _n( '%d design available', '%d designs available', count( $designs ), 'light-of-future' ), count( $designs ) ) ); ?></p>
				<ul class="lof-pd__chips">
					<?php $lof_map = in_array( (string) $product->get_sku(), array( 'LOF-BAG', 'LOF-SANDALS' ), true ); ?>
					<?php foreach ( array_values( $designs ) as $i => $d ) : ?>
						<li><button type="button" class="lof-pd__chip" aria-pressed="false" data-design="<?php echo esc_attr( $d ); ?>"<?php echo $lof_map ? ' data-index="' . (int) $i . '"' : ''; ?>><?php echo esc_html( $d ); ?></button></li>
					<?php endforeach; ?>
				</ul>
				<p class="lof-pd__hint"><?php esc_html_e( 'Tap a design to select it. It is filled in for you in the order form.', 'light-of-future' ); ?></p>
			</div>
		<?php endif; ?>
		<div class="lof-wa-order">
			<a class="lof-wa-order__btn lof-btn-order" href="<?php echo esc_url( lof_wa_link( lof_shop_order_message( $product ) ) ); ?>" target="_blank" rel="noopener noreferrer" <?php echo lof_order_attrs( $product ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?> data-single="1"><?php echo lof_wa_icon( 22 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?><span><?php esc_html_e( 'Order on WhatsApp', 'light-of-future' ); ?></span></a>
			<p class="lof-wa-order__note"><?php esc_html_e( 'Choose your design and quantity, then send your order to our team on WhatsApp. Price and delivery are confirmed with you there.', 'light-of-future' ); ?></p>
		</div>
		<ul class="lof-pd__trust">
			<li><?php esc_html_e( 'Made by hand by community makers in Kenya', 'light-of-future' ); ?></li>
			<li><?php esc_html_e( 'Every purchase supports our work with families', 'light-of-future' ); ?></li>
			<li><?php esc_html_e( 'Delivery in Nairobi, across Kenya and abroad, agreed with you', 'light-of-future' ); ?></li>
		</ul>
	</div>
	<?php
}
add_action( 'woocommerce_single_product_summary', 'lof_single_order_card', 31 );

/**
 * "How ordering works" under the product.
 */
function lof_single_steps() {
	?>
	<section class="lof-shopsteps lof-shopsteps--single" aria-labelledby="lof-pd-steps-title">
		<p class="lof-shopsteps__eyebrow"><?php esc_html_e( 'Simple and personal', 'light-of-future' ); ?></p>
		<h2 class="lof-shopsteps__title" id="lof-pd-steps-title"><?php esc_html_e( 'How ordering works', 'light-of-future' ); ?></h2>
		<ol class="lof-shopsteps__list">
			<li><span class="lof-shopsteps__n" aria-hidden="true">1</span><strong><?php esc_html_e( 'Choose your design', 'light-of-future' ); ?></strong><span><?php esc_html_e( 'Look through the photos and pick the design and quantity you want.', 'light-of-future' ); ?></span></li>
			<li><span class="lof-shopsteps__n" aria-hidden="true">2</span><strong><?php esc_html_e( 'Order on WhatsApp', 'light-of-future' ); ?></strong><span><?php esc_html_e( 'Fill in the short order form and send it to our team.', 'light-of-future' ); ?></span></li>
			<li><span class="lof-shopsteps__n" aria-hidden="true">3</span><strong><?php esc_html_e( 'We confirm and deliver', 'light-of-future' ); ?></strong><span><?php esc_html_e( 'We confirm the price, payment and delivery with you, then send your order.', 'light-of-future' ); ?></span></li>
		</ol>
	</section>
	<?php
}
add_action( 'woocommerce_after_single_product_summary', 'lof_single_steps', 12 );

/**
 * Tab and related headings.
 *
 * @param array $tabs Tabs.
 * @return array
 */
function lof_single_tabs( $tabs ) {
	unset( $tabs['additional_information'] );
	if ( isset( $tabs['description'] ) ) {
		$tabs['description']['title'] = __( 'About this piece', 'light-of-future' );
	}
	return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'lof_single_tabs', 20 );
add_filter( 'woocommerce_product_description_heading', '__return_empty_string' );

/**
 * Related products heading.
 *
 * @return string
 */
function lof_related_heading() {
	return __( 'You may also like', 'light-of-future' );
}
add_filter( 'woocommerce_product_related_products_heading', 'lof_related_heading' );
