<?php
/**
 * Crafts shop front end (0.44.0), modelled on the Cistern of Hope Hope Market:
 *
 * - WhatsApp chat card on the right (replaces the plain lof-wa float).
 * - "Order on WhatsApp" on product pages and shop cards, with the product,
 *   chosen design, quantity, price and link ready to send.
 * - Home "Shop with purpose" section: shortcode [lof_home_market] showing
 *   featured products (random featured first, topped up with others).
 *
 * WhatsApp number: the Core plugin setting "WhatsApp number" when set, else
 * the foundation's number (+254 715 394 295, confirmed by the founder).
 * Online checkout stays off until Paystack is verified (filter
 * 'lof_shop_cart_enabled'); until then orders go through WhatsApp.
 *
 * @package LightOfFuture
 */

defined( 'ABSPATH' ) || exit;

/**
 * WhatsApp number as wa.me expects it (digits, country code included).
 *
 * @return string
 */
function lof_wa_number() {
	$num = preg_replace( '/[^0-9]/', '', (string) lof_setting( 'whatsapp' ) );
	if ( '' === $num ) {
		$num = '254715394295';
	}
	return (string) apply_filters( 'lof_wa_number', $num );
}

/**
 * Is online cart/checkout enabled? Off until Paystack is live.
 *
 * @return bool
 */
function lof_shop_cart_enabled() {
	return (bool) apply_filters( 'lof_shop_cart_enabled', false );
}

/**
 * wa.me link with a message.
 *
 * @param string $text Message.
 * @return string
 */
function lof_wa_link( $text ) {
	return 'https://wa.me/' . lof_wa_number() . '?text=' . rawurlencode( $text );
}

/**
 * Plain-text price for a product, e.g. "KSh 3,500" or "KSh 700 - KSh 3,000".
 *
 * @param WC_Product $product Product.
 * @return string
 */
function lof_shop_price_text( $product ) {
	if ( ! $product ) {
		return '';
	}
	if ( $product->is_type( 'variable' ) ) {
		$min = $product->get_variation_price( 'min' );
		$max = $product->get_variation_price( 'max' );
		if ( '' === $min ) {
			return '';
		}
		$fmt = 'KSh ' . number_format_i18n( (float) $min );
		if ( (float) $max > (float) $min ) {
			$fmt .= ' - KSh ' . number_format_i18n( (float) $max );
		}
		return $fmt;
	}
	$price = $product->get_price();
	return '' === $price ? '' : 'KSh ' . number_format_i18n( (float) $price );
}

/**
 * Default WhatsApp order message for a product.
 *
 * @param WC_Product $product Product.
 * @return string
 */
function lof_shop_order_message( $product ) {
	$name  = wp_strip_all_tags( $product->get_name() );
	$price = lof_shop_price_text( $product );
	/* translators: %s: product name. */
	$msg = sprintf( __( 'Hello Light of Future Foundation, I would like to order: %s', 'light-of-future' ), $name );
	if ( $price ) {
		$msg .= ' (' . $price . ')';
	}
	$msg .= "\n" . get_permalink( $product->get_id() );
	return $msg;
}

/**
 * Data attributes that open the WhatsApp order form for a product.
 *
 * @param WC_Product $product Product.
 * @return string Escaped attribute string.
 */
function lof_order_attrs( $product ) {
	$designs = array();
	if ( $product->is_type( 'variable' ) ) {
		foreach ( $product->get_variation_attributes() as $attr => $opts ) {
			foreach ( (array) $opts as $opt ) {
				$term      = taxonomy_exists( $attr ) ? get_term_by( 'slug', $opt, $attr ) : false;
				$designs[] = $term ? $term->name : $opt;
			}
			break;
		}
	}
	$img = wp_get_attachment_image_url( $product->get_image_id(), 'woocommerce_thumbnail' );
	return sprintf(
		'data-lof-order data-name="%1$s" data-price="%2$s" data-url="%3$s" data-designs="%4$s" data-image="%5$s"',
		esc_attr( wp_strip_all_tags( $product->get_name() ) ),
		esc_attr( lof_shop_price_text( $product ) ),
		esc_url( get_permalink( $product->get_id() ) ),
		esc_attr( wp_json_encode( array_values( array_unique( $designs ) ) ) ),
		esc_url( $img ? $img : '' )
	);
}

/**
 * WhatsApp order form (popup). Printed once in the footer.
 */
function lof_order_form() {
	if ( is_admin() || ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	?>
	<div class="lof-order" data-lof-order-modal data-number="<?php echo esc_attr( lof_wa_number() ); ?>" hidden>
		<div class="lof-order__backdrop" data-lof-order-close></div>
		<div class="lof-order__dialog" role="dialog" aria-modal="true" aria-labelledby="lof-order-title" tabindex="-1">
			<div class="lof-order__head">
				<span class="lof-order__thumb"><img src="" alt="" width="64" height="64" hidden></span>
				<div class="lof-order__heading">
					<p class="lof-order__eyebrow"><?php esc_html_e( 'Order on WhatsApp', 'light-of-future' ); ?></p>
					<h2 class="lof-order__title" id="lof-order-title"></h2>
					<p class="lof-order__price"></p>
				</div>
				<button type="button" class="lof-order__close" data-lof-order-close aria-label="<?php esc_attr_e( 'Close order form', 'light-of-future' ); ?>">&times;</button>
			</div>
			<form class="lof-order__form" novalidate>
				<div class="lof-order__row lof-order__row--design" hidden>
					<label for="lof-order-design"><?php esc_html_e( 'Design', 'light-of-future' ); ?></label>
					<select id="lof-order-design" name="design"></select>
				</div>
				<div class="lof-order__grid">
					<div class="lof-order__row">
						<label for="lof-order-qty"><?php esc_html_e( 'Quantity', 'light-of-future' ); ?></label>
						<div class="lof-order__qty">
							<button type="button" data-step="-1" aria-label="<?php esc_attr_e( 'Fewer', 'light-of-future' ); ?>">&minus;</button>
							<input id="lof-order-qty" name="qty" type="number" min="1" max="500" value="1" inputmode="numeric">
							<button type="button" data-step="1" aria-label="<?php esc_attr_e( 'More', 'light-of-future' ); ?>">+</button>
						</div>
					</div>
					<div class="lof-order__row">
						<label for="lof-order-name"><?php esc_html_e( 'Your name', 'light-of-future' ); ?> <span aria-hidden="true">*</span></label>
						<input id="lof-order-name" name="name" type="text" autocomplete="name" required maxlength="80">
					</div>
				</div>
				<div class="lof-order__row">
					<label for="lof-order-place"><?php esc_html_e( 'Delivery town or country', 'light-of-future' ); ?> <span aria-hidden="true">*</span></label>
					<input id="lof-order-place" name="place" type="text" autocomplete="address-level2" required maxlength="120" placeholder="<?php esc_attr_e( 'e.g. Nairobi, Kisumu, London', 'light-of-future' ); ?>">
				</div>
				<div class="lof-order__row">
					<label for="lof-order-note"><?php esc_html_e( 'Anything else? (optional)', 'light-of-future' ); ?></label>
					<textarea id="lof-order-note" name="note" rows="2" maxlength="500" placeholder="<?php esc_attr_e( 'Colours, sizes, a gift message...', 'light-of-future' ); ?>"></textarea>
				</div>
				<p class="lof-order__error" role="alert" hidden><?php esc_html_e( 'Please add your name and delivery town.', 'light-of-future' ); ?></p>
				<button type="submit" class="lof-order__submit"><?php echo lof_wa_icon( 22 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?><span><?php esc_html_e( 'Continue on WhatsApp', 'light-of-future' ); ?></span></button>
				<ul class="lof-order__assure">
					<li><?php esc_html_e( 'Handmade in Kenya', 'light-of-future' ); ?></li>
					<li><?php esc_html_e( 'Price and delivery confirmed by our team', 'light-of-future' ); ?></li>
					<li><?php esc_html_e( 'Nothing is charged here', 'light-of-future' ); ?></li>
				</ul>
			</form>
		</div>
	</div>
	<?php
}
add_action( 'wp_footer', 'lof_order_form', 7 );

/**
 * WhatsApp icon SVG.
 *
 * @param int $size Size in px.
 * @return string
 */
function lof_wa_icon( $size = 20 ) {
	return '<svg viewBox="0 0 32 32" width="' . (int) $size . '" height="' . (int) $size . '" aria-hidden="true" focusable="false"><path fill="currentColor" d="M16 3.2A12.8 12.8 0 0 0 5 22.5L3.2 28.8l6.5-1.7A12.8 12.8 0 1 0 16 3.2zm0 23.3c-2 0-3.9-.5-5.5-1.5l-.4-.2-3.9 1 1-3.8-.2-.4A10.5 10.5 0 1 1 16 26.5zm5.8-7.9c-.3-.2-1.9-.9-2.2-1-.3-.1-.5-.2-.7.2l-1 1.2c-.2.2-.4.2-.7.1-.3-.2-1.4-.5-2.6-1.6-1-.9-1.6-1.9-1.8-2.2-.2-.3 0-.5.1-.7l.5-.6.3-.5c.1-.2 0-.4 0-.6l-1-2.4c-.3-.6-.5-.5-.7-.5h-.6c-.2 0-.6.1-.9.4-.3.3-1.2 1.1-1.2 2.8s1.2 3.2 1.4 3.5c.2.2 2.4 3.6 5.7 5 .8.4 1.4.6 1.9.7.8.2 1.5.2 2.1.1.6-.1 1.9-.8 2.2-1.5.3-.7.3-1.4.2-1.5-.1-.2-.3-.3-.6-.4z"/></svg>';
}

/* ------------------------------------------------------------------ */
/* Assets                                                              */
/* ------------------------------------------------------------------ */

/**
 * Enqueue shop + chat CSS/JS on the front end.
 */
function lof_shop_assets() {
	if ( is_admin() ) {
		return;
	}
	wp_enqueue_style( 'lof-shop', LOF_THEME_URI . '/assets/css/shop.css', array( 'lof-sections' ), LOF_THEME_VERSION );
	wp_enqueue_script( 'lof-shop', LOF_THEME_URI . '/assets/js/shop.js', array(), LOF_THEME_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
}
add_action( 'wp_enqueue_scripts', 'lof_shop_assets', 30 );

/**
 * Body class while online checkout is off (hides cart buttons by CSS).
 *
 * @param string[] $classes Classes.
 * @return string[]
 */
function lof_shop_body_class( $classes ) {
	if ( ! lof_shop_cart_enabled() ) {
		$classes[] = 'lof-wa-orders';
	}
	return $classes;
}
add_filter( 'body_class', 'lof_shop_body_class' );

/* ------------------------------------------------------------------ */
/* WhatsApp chat card                                                  */
/* ------------------------------------------------------------------ */

// The chat card replaces the plain floating button in inc/floating.php.
add_filter( 'lof_show_whatsapp', '__return_false' );

/**
 * Print the chat card in the footer.
 */
function lof_whatsapp_chat() {
	if ( is_admin() || is_feed() || is_embed() || is_customize_preview() ) {
		return;
	}
	if ( class_exists( '\\Elementor\\Plugin' ) && isset( \Elementor\Plugin::$instance->preview ) && \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
		return;
	}
	$number  = lof_wa_number();
	$avatar  = LOF_THEME_URI . '/assets/images/brand/symbol-light.svg';
	$title   = '';
	$url     = '';
	$chip    = '';
	if ( is_singular() ) {
		$id    = (int) get_queried_object_id();
		$title = wp_strip_all_tags( get_the_title( $id ) );
		$url   = (string) get_permalink( $id );
		if ( 'product' === get_post_type( $id ) ) {
			/* translators: %s: product name. */
			$chip = sprintf( __( 'I would like to order: %s', 'light-of-future' ), $title );
		}
	}
	$topics = array(
		__( 'I would like to order from the shop', 'light-of-future' ),
		__( 'I would like to give a donation', 'light-of-future' ),
		__( 'I would like to volunteer or partner with you', 'light-of-future' ),
		__( 'I have a general question', 'light-of-future' ),
	);
	if ( $chip ) {
		array_unshift( $topics, $chip );
	}
	$quiet    = ( function_exists( 'is_cart' ) && is_cart() ) || ( function_exists( 'is_checkout' ) && is_checkout() ) || is_page( array( 'donate', 'contact' ) );
	$fallback = lof_wa_link( __( 'Hello Light of Future Foundation, I would like to know more about your work.', 'light-of-future' ) );
	?>
	<div class="lof-chat" data-lof-chat data-number="<?php echo esc_attr( $number ); ?>" data-quiet="<?php echo $quiet ? '1' : '0'; ?>" data-page-title="<?php echo esc_attr( $title ); ?>" data-page-url="<?php echo esc_url( $url ); ?>">
		<div class="lof-chat__teaser" hidden>
			<button type="button" class="lof-chat__teaser-close" aria-label="<?php esc_attr_e( 'Dismiss message', 'light-of-future' ); ?>">&times;</button>
			<button type="button" class="lof-chat__teaser-open">
				<img src="<?php echo esc_url( $avatar ); ?>" alt="" width="36" height="36" loading="lazy" decoding="async">
				<span><strong><?php esc_html_e( 'Hello there', 'light-of-future' ); ?></strong> <?php esc_html_e( 'Questions about our work or a handmade product? Chat with us on WhatsApp.', 'light-of-future' ); ?></span>
			</button>
		</div>
		<div class="lof-chat__panel" id="lof-chat-panel" role="dialog" aria-modal="false" aria-labelledby="lof-chat-title" hidden>
			<div class="lof-chat__head">
				<span class="lof-chat__avatar"><img src="<?php echo esc_url( $avatar ); ?>" alt="" width="44" height="44" loading="lazy" decoding="async"><i aria-hidden="true"></i></span>
				<span class="lof-chat__who"><strong id="lof-chat-title"><?php esc_html_e( 'Light of Future Foundation', 'light-of-future' ); ?></strong><span><?php esc_html_e( 'We reply on WhatsApp as soon as we can', 'light-of-future' ); ?></span></span>
				<button type="button" class="lof-chat__close" aria-label="<?php esc_attr_e( 'Close chat', 'light-of-future' ); ?>">&times;</button>
			</div>
			<div class="lof-chat__body">
				<div class="lof-chat__typing" aria-hidden="true"><i></i><i></i><i></i></div>
				<div class="lof-chat__bubble" role="status">
					<p><?php esc_html_e( 'Hello and welcome. Thank you for standing with children, widows and families in Kenya.', 'light-of-future' ); ?></p>
					<p><?php esc_html_e( 'How can we help you today?', 'light-of-future' ); ?></p>
				</div>
				<p class="lof-chat__label" id="lof-chat-topics"><?php esc_html_e( 'Choose a topic or type your own message', 'light-of-future' ); ?></p>
				<div class="lof-chat__chips" role="group" aria-labelledby="lof-chat-topics">
					<?php foreach ( $topics as $i => $topic ) : ?>
						<button type="button" class="lof-chat__chip<?php echo ( 0 === $i && $chip ) ? ' is-page' : ''; ?>" aria-pressed="false"><?php echo esc_html( $topic ); ?></button>
					<?php endforeach; ?>
				</div>
			</div>
			<form class="lof-chat__form" action="<?php echo esc_url( 'https://wa.me/' . $number ); ?>" method="get" target="_blank" rel="noopener noreferrer">
				<?php if ( $url ) : ?>
					<label class="lof-chat__share"><input type="checkbox" checked> <span><?php esc_html_e( 'Include a link to this page', 'light-of-future' ); ?></span></label>
				<?php endif; ?>
				<div class="lof-chat__compose">
					<label class="screen-reader-text" for="lof-chat-text"><?php esc_html_e( 'Your message', 'light-of-future' ); ?></label>
					<textarea id="lof-chat-text" name="text" rows="1" maxlength="800" placeholder="<?php esc_attr_e( 'Type a message', 'light-of-future' ); ?>"></textarea>
					<button type="submit" class="lof-chat__send" aria-label="<?php esc_attr_e( 'Send on WhatsApp', 'light-of-future' ); ?>"><svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true" focusable="false"><path fill="currentColor" d="M3.4 20.4l17.5-7.5a1 1 0 000-1.8L3.4 3.6a.9.9 0 00-1.3 1l1.8 6.1 9 1.3-9 1.3-1.8 6.1a.9.9 0 001.3 1z"/></svg></button>
				</div>
			</form>
			<p class="lof-chat__note"><?php esc_html_e( 'Opens WhatsApp with your message ready to send. Please never share M-Pesa PINs or card details.', 'light-of-future' ); ?></p>
		</div>
		<a class="lof-chat__fab" href="<?php echo esc_url( $fallback ); ?>" target="_blank" rel="noopener noreferrer" aria-controls="lof-chat-panel" aria-expanded="false">
			<span class="lof-chat__fab-icon"><?php echo lof_wa_icon( 28 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></span>
			<span class="lof-chat__fab-label"><?php esc_html_e( 'Chat with us', 'light-of-future' ); ?></span>
			<span class="lof-chat__dot" aria-hidden="true" hidden></span>
		</a>
	</div>
	<?php
}
add_action( 'wp_footer', 'lof_whatsapp_chat', 6 );

/* ------------------------------------------------------------------ */
/* Order on WhatsApp                                                   */
/* ------------------------------------------------------------------ */

/**
 * Button on the single product page (after the add-to-cart area).
 */
function lof_shop_single_wa_button() {
	global $product;
	if ( ! $product instanceof WC_Product ) {
		return;
	}
	printf(
		'<div class="lof-wa-order"><a class="lof-wa-order__btn lof-btn-order" href="%1$s" target="_blank" rel="noopener noreferrer" %2$s data-single="1">%3$s<span>%4$s</span></a><p class="lof-wa-order__note">%5$s</p></div>',
		esc_url( lof_wa_link( lof_shop_order_message( $product ) ) ),
		lof_order_attrs( $product ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper.
		lof_wa_icon( 22 ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG.
		esc_html__( 'Order on WhatsApp', 'light-of-future' ),
		esc_html__( 'Choose your design and quantity, then send your order to our team on WhatsApp. Price and delivery are confirmed with you there.', 'light-of-future' )
	);
}
add_action( 'woocommerce_single_product_summary', 'lof_shop_single_wa_button', 32 );

/**
 * Small WhatsApp button on shop cards.
 */
function lof_shop_loop_wa_button() {
	global $product;
	if ( ! $product instanceof WC_Product ) {
		return;
	}
	printf(
		'<a class="lof-wa-order__mini lof-btn-order" href="%1$s" target="_blank" rel="noopener noreferrer" aria-label="%2$s" ' . lof_order_attrs( $product ) . '>%3$s<span class="lof-wa-long">%4$s</span><span class="lof-wa-short" aria-hidden="true">%5$s</span></a>',
		esc_url( lof_wa_link( lof_shop_order_message( $product ) ) ),
		/* translators: %s: product name. */
		esc_attr( sprintf( __( 'Order %s on WhatsApp', 'light-of-future' ), wp_strip_all_tags( $product->get_name() ) ) ),
		lof_wa_icon( 18 ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG.
		esc_html__( 'Order on WhatsApp', 'light-of-future' ),
		esc_html__( 'Order', 'light-of-future' )
	);
}
add_action( 'woocommerce_after_shop_loop_item', 'lof_shop_loop_wa_button', 15 );

/**
 * While checkout is off, the card button reads "View details" instead of
 * "Add to cart" / "Select options".
 *
 * @param string     $html    Button HTML.
 * @param WC_Product $product Product.
 * @return string
 */
function lof_shop_loop_button( $html, $product ) {
	if ( lof_shop_cart_enabled() ) {
		return $html;
	}
	return sprintf( '<a class="button lof-shop-view" href="%1$s">%2$s</a>', esc_url( get_permalink( $product->get_id() ) ), esc_html__( 'View details', 'light-of-future' ) );
}
add_filter( 'woocommerce_loop_add_to_cart_link', 'lof_shop_loop_button', 20, 2 );

/**
 * Random product order on the shop page (as on the Hope Market).
 *
 * @param array $args Ordering args.
 * @return array
 */
function lof_shop_random_order( $args ) {
	if ( function_exists( 'is_shop' ) && is_shop() && ! isset( $_GET['orderby'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$args['orderby'] = 'rand';
		$args['order']   = '';
	}
	return $args;
}
add_filter( 'woocommerce_get_catalog_ordering_args', 'lof_shop_random_order' );

/**
 * Shop page intro band.
 */
function lof_shop_intro() {
	if ( ! function_exists( 'is_shop' ) || ! is_shop() ) {
		return;
	}
	echo '<div class="lof-shop-intro"><p class="lof-shop-intro__eyebrow">' . esc_html__( 'Handmade in Kenya', 'light-of-future' ) . '</p><p class="lof-shop-intro__text">' . esc_html__( 'Every piece is made by hand. Every purchase supports community makers and the work of Light of Future Foundation. Pick a product and order on WhatsApp in one tap.', 'light-of-future' ) . '</p></div>';
}
add_action( 'woocommerce_archive_description', 'lof_shop_intro', 5 );

/* ------------------------------------------------------------------ */
/* Home "Shop with purpose"                                            */
/* ------------------------------------------------------------------ */

/**
 * Products for the home section: featured first (random), topped up.
 *
 * @param int $count How many.
 * @return WC_Product[]
 */
function lof_home_market_products( $count = 4 ) {
	if ( ! function_exists( 'wc_get_products' ) ) {
		return array();
	}
	$featured = wc_get_products( array( 'status' => 'publish', 'featured' => true, 'limit' => 12, 'orderby' => 'rand' ) );
	$list     = array_slice( $featured, 0, $count );
	if ( count( $list ) < $count ) {
		$ids  = array_map( function ( $p ) { return $p->get_id(); }, $list );
		$more = wc_get_products( array( 'status' => 'publish', 'limit' => $count - count( $list ), 'orderby' => 'rand', 'exclude' => $ids ) );
		$list = array_merge( $list, $more );
	}
	return $list;
}

/**
 * Render the home section.
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function lof_home_market( $atts = array() ) {
	$atts     = shortcode_atts( array( 'count' => 4 ), $atts, 'lof_home_market' );
	$products = lof_home_market_products( max( 1, min( 8, (int) $atts['count'] ) ) );
	if ( empty( $products ) ) {
		return '';
	}
	$shop = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
	ob_start();
	?>
	<section class="lof-market" aria-labelledby="lof-market-title">
		<div class="lof-market__inner">
			<div class="lof-market__head">
				<div>
					<p class="lof-market__eyebrow"><?php esc_html_e( 'Shop with purpose', 'light-of-future' ); ?></p>
					<h2 class="lof-market__title" id="lof-market-title"><?php esc_html_e( 'Handmade in Kenya, made to give back', 'light-of-future' ); ?></h2>
					<p class="lof-market__intro"><?php esc_html_e( 'Beaded jewellery, leather and sandals made by hand. Each purchase supports community makers and our work with families in need.', 'light-of-future' ); ?></p>
				</div>
				<a class="lof-market__all" href="<?php echo esc_url( $shop ); ?>"><?php esc_html_e( 'Visit the shop', 'light-of-future' ); ?> <span aria-hidden="true">&rarr;</span></a>
			</div>
			<ul class="lof-market__grid">
				<?php foreach ( $products as $i => $p ) : ?>
					<li class="lof-market__card<?php echo 0 === $i ? ' is-lead' : ''; ?>">
						<a class="lof-market__media" href="<?php echo esc_url( get_permalink( $p->get_id() ) ); ?>" tabindex="-1" aria-hidden="true">
							<?php echo $p->get_image( 'woocommerce_thumbnail', array( 'loading' => 'lazy', 'decoding' => 'async' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core image markup. ?>
							<?php if ( $p->is_featured() ) : ?><span class="lof-market__badge"><?php esc_html_e( 'Handmade', 'light-of-future' ); ?></span><?php endif; ?>
						</a>
						<div class="lof-market__body">
							<h3 class="lof-market__name"><a href="<?php echo esc_url( get_permalink( $p->get_id() ) ); ?>"><?php echo esc_html( $p->get_name() ); ?></a></h3>
							<p class="lof-market__price"><?php echo esc_html( lof_shop_price_text( $p ) ); ?></p>
							<div class="lof-market__actions">
								<a class="lof-market__wa lof-btn-order" href="<?php echo esc_url( lof_wa_link( lof_shop_order_message( $p ) ) ); ?>" target="_blank" rel="noopener noreferrer" <?php echo lof_order_attrs( $p ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?> aria-label="<?php echo esc_attr( sprintf( /* translators: %s: product name. */ __( 'Order %s on WhatsApp', 'light-of-future' ), wp_strip_all_tags( $p->get_name() ) ) ); ?>"><span class="lof-btn-order__ico"><?php echo lof_wa_icon( 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></span><span class="lof-wa-long"><?php esc_html_e( 'Order now', 'light-of-future' ); ?></span><span class="lof-wa-short" aria-hidden="true"><?php esc_html_e( 'Order', 'light-of-future' ); ?></span></a>
								<a class="lof-market__view" href="<?php echo esc_url( get_permalink( $p->get_id() ) ); ?>"><?php esc_html_e( 'Details', 'light-of-future' ); ?> <span aria-hidden="true">&rarr;</span></a>
							</div>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
			<ul class="lof-market__trust">
				<li><strong><?php esc_html_e( 'Handmade in Kenya', 'light-of-future' ); ?></strong><span><?php esc_html_e( 'Beadwork and leather made by hand by community makers.', 'light-of-future' ); ?></span></li>
				<li><strong><?php esc_html_e( 'Shopping that gives back', 'light-of-future' ); ?></strong><span><?php esc_html_e( 'Your order supports makers and our work with families.', 'light-of-future' ); ?></span></li>
				<li><strong><?php esc_html_e( 'Easy WhatsApp ordering', 'light-of-future' ); ?></strong><span><?php esc_html_e( 'Fill a short form; our team confirms price and delivery.', 'light-of-future' ); ?></span></li>
			</ul>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}
add_shortcode( 'lof_home_market', 'lof_home_market' );
