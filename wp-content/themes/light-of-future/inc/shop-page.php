<?php
/**
 * Shop page presentation (0.51.0): hero with product mosaic, category chips,
 * trust strip, "How ordering works" and a closing call to action.
 *
 * @package LightOfFuture
 */

defined( 'ABSPATH' ) || exit;

/**
 * Is this the shop or a product category/tag archive?
 *
 * @return bool
 */
function lof_is_shop_archive() {
	return function_exists( 'is_shop' ) && ( is_shop() || is_product_taxonomy() );
}

/**
 * Hide WooCommerce's plain "Shop" title: the hero carries the page heading.
 *
 * @param bool $show Show title.
 * @return bool
 */
function lof_shop_hide_title( $show ) {
	return ( function_exists( 'is_shop' ) && is_shop() ) ? false : $show;
}
add_filter( 'woocommerce_show_page_title', 'lof_shop_hide_title' );

/**
 * Tidy the toolbar: no "Showing all 8 results" line on the shop.
 */
function lof_shop_toolbar() {
	if ( lof_is_shop_archive() ) {
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
	}
}
add_action( 'wp', 'lof_shop_toolbar' );

/**
 * Shop hero.
 */
function lof_shop_hero() {
	if ( ! function_exists( 'is_shop' ) || ! is_shop() ) {
		return;
	}
	remove_action( 'woocommerce_archive_description', 'lof_shop_intro', 5 );
	$img   = LOF_THEME_URI . '/assets/images/shop/';
	$tiles = array(
		array( 'bag-tan-satchel', __( 'Handmade tan leather satchel', 'light-of-future' ) ),
		array( 'necklace-bib-red', __( 'Red beaded statement necklace', 'light-of-future' ) ),
		array( 'basket-bag-turquoise', __( 'Turquoise and white beaded basket bag', 'light-of-future' ) ),
		array( 'sandals-cork-flower', __( 'Beaded sandals with flower beadwork', 'light-of-future' ) ),
	);
	$count = wp_count_posts( 'product' );
	$count = isset( $count->publish ) ? (int) $count->publish : 0;
	$wa    = lof_wa_link( __( 'Hello Light of Future Foundation, I would like to ask about a custom or bulk order from your shop.', 'light-of-future' ) );
	?>
	<section class="lof-shophero" aria-labelledby="lof-shophero-title">
		<div class="lof-shophero__text">
			<p class="lof-shophero__eyebrow"><?php esc_html_e( 'The Light of Future Shop', 'light-of-future' ); ?></p>
			<h1 class="lof-shophero__title" id="lof-shophero-title"><?php esc_html_e( 'Handmade in Kenya, made to give back', 'light-of-future' ); ?></h1>
			<p class="lof-shophero__lead"><?php esc_html_e( 'Beaded jewellery, leather bags and sandals, each made by hand by community makers. Every purchase supports the makers and our work with vulnerable children, widows and families.', 'light-of-future' ); ?></p>
			<div class="lof-shophero__actions">
				<a class="lof-shophero__btn lof-shophero__btn--primary" href="#lof-shop-collection"><?php esc_html_e( 'Browse the collection', 'light-of-future' ); ?></a>
				<a class="lof-shophero__btn lof-shophero__btn--ghost" href="<?php echo esc_url( $wa ); ?>" target="_blank" rel="noopener noreferrer"><?php echo lof_wa_icon( 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?><span><?php esc_html_e( 'Custom and bulk orders', 'light-of-future' ); ?></span></a>
			</div>
			<ul class="lof-shophero__facts">
				<?php if ( $count ) : ?>
					<li><strong><?php echo esc_html( number_format_i18n( $count ) ); ?></strong><span><?php esc_html_e( 'handmade products', 'light-of-future' ); ?></span></li>
				<?php endif; ?>
				<li><strong><?php esc_html_e( '100%', 'light-of-future' ); ?></strong><span><?php esc_html_e( 'made by hand', 'light-of-future' ); ?></span></li>
				<li><strong><?php esc_html_e( 'KSh', 'light-of-future' ); ?></strong><span><?php esc_html_e( 'prices in Kenyan shillings', 'light-of-future' ); ?></span></li>
			</ul>
		</div>
		<div class="lof-shophero__mosaic" aria-hidden="true">
			<?php foreach ( $tiles as $i => $t ) : ?>
				<figure class="lof-shophero__tile lof-shophero__tile--<?php echo (int) $i + 1; ?>"><img src="<?php echo esc_url( $img . $t[0] . '.webp' ); ?>" alt="" width="600" height="600" <?php echo 0 === $i ? 'fetchpriority="high"' : 'loading="lazy"'; ?> decoding="async"></figure>
			<?php endforeach; ?>
		</div>
	</section>
	<ul class="lof-shoptrust" aria-label="<?php esc_attr_e( 'Why shop with us', 'light-of-future' ); ?>">
		<li><span class="lof-shoptrust__ico" aria-hidden="true"><svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M12 2l2.4 5 5.6.8-4 3.9.9 5.5L12 14.6 7.1 17.2 8 11.7 4 7.8l5.6-.8z"/></svg></span><strong><?php esc_html_e( 'Handmade quality', 'light-of-future' ); ?></strong><span><?php esc_html_e( 'Beadwork and leather finished by hand. No two pieces are exactly alike.', 'light-of-future' ); ?></span></li>
		<li><span class="lof-shoptrust__ico" aria-hidden="true"><svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M12 21s-7.5-4.6-9.5-9.3C1.1 8.3 3.2 5 6.6 5c2 0 3.4 1.1 4.4 2.5C12 6.1 13.4 5 15.4 5c3.4 0 5.5 3.3 4.1 6.7C19.5 16.4 12 21 12 21z"/></svg></span><strong><?php esc_html_e( 'Shopping that gives back', 'light-of-future' ); ?></strong><span><?php esc_html_e( 'Your order supports community makers and our programmes.', 'light-of-future' ); ?></span></li>
		<li><span class="lof-shoptrust__ico" aria-hidden="true"><?php echo lof_wa_icon( 22 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></span><strong><?php esc_html_e( 'Easy WhatsApp ordering', 'light-of-future' ); ?></strong><span><?php esc_html_e( 'Pick a design, send a short form and our team confirms with you.', 'light-of-future' ); ?></span></li>
		<li><span class="lof-shoptrust__ico" aria-hidden="true"><svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M3 6h11v9H3zm11 3h4l3 3v3h-7zM6.5 19a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm11 0a2 2 0 1 1 0-4 2 2 0 0 1 0 4z"/></svg></span><strong><?php esc_html_e( 'Delivery arranged with you', 'light-of-future' ); ?></strong><span><?php esc_html_e( 'In Nairobi, across Kenya and abroad. We confirm the cost and timing before you pay.', 'light-of-future' ); ?></span></li>
	</ul>
	<?php
}
add_action( 'woocommerce_archive_description', 'lof_shop_hero', 4 );

/**
 * Category chips above the grid (shop and category pages).
 */
function lof_shop_chips() {
	if ( ! lof_is_shop_archive() ) {
		return;
	}
	$terms = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => true, 'parent' => 0 ) );
	if ( is_wp_error( $terms ) ) {
		$terms = array();
	}
	$terms   = array_filter( $terms, function ( $t ) { return 'uncategorized' !== $t->slug; } );
	$current = is_product_category() ? get_queried_object_id() : 0;
	echo '<div class="lof-shopbar" id="lof-shop-collection"><div class="lof-shopbar__head"><h2 class="lof-shopbar__title">' . esc_html( is_shop() ? __( 'The collection', 'light-of-future' ) : __( 'Shop by category', 'light-of-future' ) ) . '</h2>';
	if ( $terms ) {
		echo '<nav class="lof-shopchips" aria-label="' . esc_attr__( 'Shop by category', 'light-of-future' ) . '"><ul>';
		printf( '<li><a href="%1$s"%2$s>%3$s</a></li>', esc_url( wc_get_page_permalink( 'shop' ) ), $current ? '' : ' aria-current="page"', esc_html__( 'All', 'light-of-future' ) );
		foreach ( $terms as $t ) {
			printf( '<li><a href="%1$s"%2$s>%3$s <span class="lof-shopchips__n">%4$d</span></a></li>', esc_url( get_term_link( $t ) ), $current === (int) $t->term_id ? ' aria-current="page"' : '', esc_html( $t->name ), (int) $t->count );
		}
		echo '</ul></nav>';
	}
	echo '</div></div>';
}
add_action( 'woocommerce_before_shop_loop', 'lof_shop_chips', 5 );

/**
 * How ordering works + closing call to action.
 */
function lof_shop_after() {
	if ( ! lof_is_shop_archive() ) {
		return;
	}
	$wa = lof_wa_link( __( 'Hello Light of Future Foundation, I have a question about your shop.', 'light-of-future' ) );
	?>
	<section class="lof-shopsteps" aria-labelledby="lof-shopsteps-title">
		<p class="lof-shopsteps__eyebrow"><?php esc_html_e( 'Simple and personal', 'light-of-future' ); ?></p>
		<h2 class="lof-shopsteps__title" id="lof-shopsteps-title"><?php esc_html_e( 'How ordering works', 'light-of-future' ); ?></h2>
		<ol class="lof-shopsteps__list">
			<li><span class="lof-shopsteps__n" aria-hidden="true">1</span><strong><?php esc_html_e( 'Choose your piece', 'light-of-future' ); ?></strong><span><?php esc_html_e( 'Open a product to see its designs, photos and price.', 'light-of-future' ); ?></span></li>
			<li><span class="lof-shopsteps__n" aria-hidden="true">2</span><strong><?php esc_html_e( 'Order on WhatsApp', 'light-of-future' ); ?></strong><span><?php esc_html_e( 'Pick your design and quantity in the short form and send it to our team.', 'light-of-future' ); ?></span></li>
			<li><span class="lof-shopsteps__n" aria-hidden="true">3</span><strong><?php esc_html_e( 'We confirm and deliver', 'light-of-future' ); ?></strong><span><?php esc_html_e( 'We confirm the price, payment and delivery with you, then send your order.', 'light-of-future' ); ?></span></li>
		</ol>
	</section>
	<section class="lof-shopcta" aria-labelledby="lof-shopcta-title">
		<div>
			<h2 class="lof-shopcta__title" id="lof-shopcta-title"><?php esc_html_e( 'Looking for a gift or a larger order?', 'light-of-future' ); ?></h2>
			<p class="lof-shopcta__text"><?php esc_html_e( 'We can help with gifts, events and bulk orders. Message our team and we will reply on WhatsApp.', 'light-of-future' ); ?></p>
		</div>
		<div class="lof-shopcta__actions">
			<a class="lof-btn-order" href="<?php echo esc_url( $wa ); ?>" target="_blank" rel="noopener noreferrer"><?php echo lof_wa_icon( 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?><span><?php esc_html_e( 'Message us on WhatsApp', 'light-of-future' ); ?></span></a>
			<a class="lof-shopcta__link" href="<?php echo esc_url( lof_donate_url() ); ?>"><?php esc_html_e( 'Or make a donation', 'light-of-future' ); ?> <span aria-hidden="true">&rarr;</span></a>
		</div>
	</section>
	<?php
}
add_action( 'woocommerce_after_shop_loop', 'lof_shop_after', 30 );
