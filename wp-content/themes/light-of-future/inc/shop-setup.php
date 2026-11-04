<?php
/**
 * Crafts shop setup (0.44.0). Admin only (loaded with inc/importer.php).
 *
 * One-off upgrades on init, guarded by options, as in docs/HANDOVER.md section 5:
 * - lof_upgrade_shop_products() (option lof_shop_rev = 1): creates the eight
 *   handmade products from the foundation's catalogue (prices confirmed by the
 *   founder on 4 Nov 2026) with their cleaned photos from assets/images/shop/.
 *   Skips any product whose SKU already exists, so it never duplicates or
 *   overwrites edits.
 * - lof_upgrade_home_market() (option lof_market_rev = 1): adds the
 *   "Shop with purpose" section ([lof_home_market]) to the home page, before
 *   the closing call to action. Backs up the layout in _lof_layout_backup.
 *
 * @package LightOfFuture
 */

defined( 'ABSPATH' ) || exit;

/**
 * Catalogue. Ranges become variable products with a "Design" option.
 *
 * @return array[]
 */
function lof_shop_catalogue() {
	$tail = __( 'Every purchase supports community makers and helps fund Light of Future Foundation\'s work with vulnerable children, widows and families in Kenya. Order on WhatsApp and we agree delivery with you.', 'light-of-future' );
	return array(
		array(
			'sku'      => 'LOF-BELT',
			'name'     => __( 'Handmade Beaded Leather Belt', 'light-of-future' ),
			'cat'      => __( 'Fashion & Accessories', 'light-of-future' ),
			'price'    => 3500,
			'featured' => false,
			'short'    => __( 'Hand-beaded leather belt with a sturdy metal buckle, made in Kenya by community members in our business empowerment project.', 'light-of-future' ),
			'desc'     => __( 'Each belt is made by hand, bead by bead, on leather and finished with a solid metal buckle. The bright traditional colours make it a striking piece with jeans, chinos or a dress, and a meaningful gift. Designs include Kenya Flag (black, red and green blocks) and Red Wave (red with a black and white wave).', 'light-of-future' ) . "\n\n" . $tail,
			'images'   => array( 'belt-beaded-leather' ),
		),
		array(
			'sku'      => 'LOF-BAG',
			'name'     => __( 'Handmade Leather Bag', 'light-of-future' ),
			'cat'      => __( 'Bags', 'light-of-future' ),
			'price'    => 7500,
			'featured' => true,
			'short'    => __( 'Handcrafted leather bag with hand-laced edges and a long shoulder strap, made in Kenya. Shipping is extra.', 'light-of-future' ),
			'desc'     => __( 'Each bag is cut, stitched and laced by hand, with decorative whipstitched edges and a long shoulder strap. Styles include a classic tan satchel, a red bag with a hand-tooled flap and a red barrel bag. A durable everyday bag and a meaningful gift.', 'light-of-future' ) . "\n\n" . $tail,
			'images'   => array( 'bag-tan-satchel', 'bag-red-tooled-flap', 'bag-red-barrel', 'bags-collection' ),
		),
		array(
			'sku'      => 'LOF-NECKLACE',
			'name'     => __( 'Handmade Maasai Beaded Necklace', 'light-of-future' ),
			'cat'      => __( 'Jewellery', 'light-of-future' ),
			'range'    => array( __( 'Simple design', 'light-of-future' ) => 700, __( 'Statement design', 'light-of-future' ) => 3000 ),
			'featured' => true,
			'short'    => __( 'Hand-beaded necklaces in Kenyan colours, from simple everyday designs (KSh 700) to bold statement pieces (KSh 3,000). Shipping is extra.', 'light-of-future' ),
			'desc'     => __( 'Each necklace is beaded by hand in the bright colours of Kenyan beadwork. Choose a simple design for everyday wear or a detailed statement necklace with a brass collar and long beaded fringe for weddings, celebrations and cultural events. No two pieces are exactly alike.', 'light-of-future' ) . "\n\n" . $tail,
			'images'   => array( 'necklace-bib-collection', 'necklace-bib-red', 'necklace-bib-cream', 'necklace-bib-turquoise' ),
		),
		array(
			'sku'      => 'LOF-EARRINGS',
			'name'     => __( 'Handmade Maasai Beaded Earrings', 'light-of-future' ),
			'cat'      => __( 'Jewellery', 'light-of-future' ),
			'range'    => array( __( 'Simple design', 'light-of-future' ) => 300, __( 'Statement design', 'light-of-future' ) => 2500 ),
			'featured' => false,
			'short'    => __( 'Hand-beaded earrings in bright Kenyan colours, from small everyday pairs (KSh 300) to bold statement hoops (KSh 2,500).', 'light-of-future' ),
			'desc'     => __( 'Each pair is beaded by hand in the vibrant colours of Kenyan beadwork. Lightweight and easy to wear: choose small hoops or discs for every day, or a statement fringe or large hoop for celebrations.', 'light-of-future' ) . "\n\n" . $tail,
			'images'   => array( 'earrings-display', 'earrings-hoop-multicolour', 'earrings-hoop-pair' ),
		),
		array(
			'sku'      => 'LOF-BASKET',
			'name'     => __( 'Handmade Beaded Basket Bag', 'light-of-future' ),
			'cat'      => __( 'Bags', 'light-of-future' ),
			'price'    => 6500,
			'featured' => true,
			'short'    => __( 'A round, hand-beaded basket bag in turquoise and white, with a zip-top lining and sturdy handles.', 'light-of-future' ),
			'desc'     => __( 'Built bead by bead into a bold turquoise and white diamond pattern with bands of white tube beads. A zip-top lining keeps your things secure and two padded handles make it easy to carry. A statement handbag, market bag or gift.', 'light-of-future' ) . "\n\n" . $tail,
			'images'   => array( 'basket-bag-turquoise' ),
		),
		array(
			'sku'      => 'LOF-SANDALS',
			'name'     => __( 'Handmade Maasai Beaded Sandals', 'light-of-future' ),
			'cat'      => __( 'Fashion & Accessories', 'light-of-future' ),
			'price'    => 1500,
			'featured' => true,
			'short'    => __( 'Handmade sandals finished with colourful Kenyan beadwork, in leather and cushioned cork-style soles. All styles KSh 1,500.', 'light-of-future' ),
			'desc'     => __( 'Each pair is finished by hand with bright Maasai-style beadwork. Choose leather thong sandals with an ankle strap, easy slides, or cushioned cork-style footbeds. Light, durable and perfect for holidays, weddings and everyday wear. Tell us your style and size on WhatsApp.', 'light-of-future' ) . "\n\n" . $tail,
			'images'   => array( 'sandals-teardrop', 'sandals-black-flower', 'sandals-cork-flower', 'sandals-cork-butterfly', 'sandals-leather-slide' ),
		),
		array(
			'sku'      => 'LOF-BANGLE',
			'name'     => __( 'Handmade Beaded Bangle', 'light-of-future' ),
			'cat'      => __( 'Jewellery', 'light-of-future' ),
			'range'    => array( __( 'Simple design', 'light-of-future' ) => 150, __( 'Detailed design', 'light-of-future' ) => 500 ),
			'featured' => false,
			'short'    => __( 'Colourful hand-beaded bangles, easy to stack and mix, from KSh 150 to KSh 500.', 'light-of-future' ),
			'desc'     => __( 'Each bangle is woven by hand from beads into a flexible band. Wear one on its own or stack several colours, including turquoise, gold, red and pink, and the black, red, green and white of the Kenyan flag.', 'light-of-future' ) . "\n\n" . $tail,
			'images'   => array( 'bangles-turquoise', 'bangles-gold', 'bangles-kenya', 'bangles-red-pink' ),
		),
		array(
			'sku'      => 'LOF-KEYHOLDER',
			'name'     => __( 'Handmade Beaded Key Holder', 'light-of-future' ),
			'cat'      => __( 'Gifts & Souvenirs', 'light-of-future' ),
			'range'    => array( __( 'Simple design', 'light-of-future' ) => 100, __( 'Detailed design', 'light-of-future' ) => 400 ),
			'featured' => false,
			'short'    => __( 'Colourful hand-beaded key holders, a small and meaningful gift from Kenya, from KSh 100 to KSh 400.', 'light-of-future' ),
			'desc'     => __( 'Each key holder is finished by hand with bright Kenyan beadwork on a sturdy metal ring: beaded loops, flag designs, a Maasai figure or a carved giraffe. A perfect stocking filler, party favour or keepsake.', 'light-of-future' ) . "\n\n" . $tail,
			'images'   => array( 'keyholders', 'keyholders-accessories' ),
		),
	);
}

/**
 * Import one theme image into the media library (once; cached by name).
 *
 * @param string $slug  File name without extension in assets/images/shop/.
 * @param string $title Alt text / title.
 * @return int Attachment ID or 0.
 */
function lof_shop_import_image( $slug, $title ) {
	$ids = get_option( 'lof_shop_image_ids', array() );
	$ids = is_array( $ids ) ? $ids : array();
	if ( ! empty( $ids[ $slug ] ) && get_post( $ids[ $slug ] ) ) {
		return (int) $ids[ $slug ];
	}
	$file = LOF_THEME_DIR . '/assets/images/shop/' . $slug . '.webp';
	if ( ! file_exists( $file ) ) {
		return 0;
	}
	$upload = wp_upload_bits( 'lof-shop-' . $slug . '.webp', null, file_get_contents( $file ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- local theme file.
	if ( ! empty( $upload['error'] ) ) {
		return 0;
	}
	$att_id = wp_insert_attachment(
		array(
			'post_mime_type' => 'image/webp',
			'post_title'     => $title,
			'post_status'    => 'inherit',
		),
		$upload['file']
	);
	if ( is_wp_error( $att_id ) || ! $att_id ) {
		return 0;
	}
	require_once ABSPATH . 'wp-admin/includes/image.php';
	wp_update_attachment_metadata( $att_id, wp_generate_attachment_metadata( $att_id, $upload['file'] ) );
	update_post_meta( $att_id, '_wp_attachment_image_alt', $title );
	$ids[ $slug ] = (int) $att_id;
	update_option( 'lof_shop_image_ids', $ids, false );
	return (int) $att_id;
}

/**
 * Product category ID by name (created if missing).
 *
 * @param string $name Category name.
 * @return int
 */
function lof_shop_cat_id( $name ) {
	$term = get_term_by( 'name', $name, 'product_cat' );
	if ( $term ) {
		return (int) $term->term_id;
	}
	$new = wp_insert_term( $name, 'product_cat' );
	return is_wp_error( $new ) ? 0 : (int) $new['term_id'];
}

/**
 * 0.44.0: create the eight catalogue products once.
 */
function lof_upgrade_shop_products() {
	if ( ! class_exists( 'WooCommerce' ) || ! function_exists( 'wc_get_product_id_by_sku' ) ) {
		return; // Try again once WooCommerce is active.
	}
	if ( (int) get_option( 'lof_shop_rev', 0 ) >= 1 ) {
		return;
	}
	update_option( 'lof_shop_rev', 1, false );
	if ( function_exists( 'set_time_limit' ) ) {
		@set_time_limit( 120 ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
	}
	if ( 'KES' !== get_option( 'woocommerce_currency' ) ) {
		update_option( 'woocommerce_currency', 'KES' );
	}
	foreach ( lof_shop_catalogue() as $item ) {
		if ( wc_get_product_id_by_sku( $item['sku'] ) ) {
			continue;
		}
		$is_var  = ! empty( $item['range'] );
		$product = $is_var ? new WC_Product_Variable() : new WC_Product_Simple();
		$product->set_name( $item['name'] );
		$product->set_sku( $item['sku'] );
		$product->set_status( 'publish' );
		$product->set_catalog_visibility( 'visible' );
		$product->set_short_description( $item['short'] );
		$product->set_description( wpautop( $item['desc'] ) );
		$product->set_featured( ! empty( $item['featured'] ) );
		$cat = lof_shop_cat_id( $item['cat'] );
		if ( $cat ) {
			$product->set_category_ids( array( $cat ) );
		}
		$images = array();
		foreach ( $item['images'] as $slug ) {
			$att = lof_shop_import_image( $slug, $item['name'] );
			if ( $att ) {
				$images[] = $att;
			}
		}
		if ( $images ) {
			$product->set_image_id( array_shift( $images ) );
			$product->set_gallery_image_ids( $images );
		}
		if ( $is_var ) {
			$attr = new WC_Product_Attribute();
			$attr->set_name( __( 'Design', 'light-of-future' ) );
			$attr->set_options( array_keys( $item['range'] ) );
			$attr->set_visible( true );
			$attr->set_variation( true );
			$product->set_attributes( array( $attr ) );
		} else {
			$product->set_regular_price( (string) $item['price'] );
		}
		$pid = $product->save();
		if ( $is_var && $pid ) {
			$key = sanitize_title( __( 'Design', 'light-of-future' ) );
			foreach ( $item['range'] as $label => $price ) {
				$var = new WC_Product_Variation();
				$var->set_parent_id( $pid );
				$var->set_attributes( array( $key => $label ) );
				$var->set_regular_price( (string) $price );
				$var->set_status( 'publish' );
				$var->save();
			}
			WC_Product_Variable::sync( $pid );
		}
	}
	if ( function_exists( 'wc_delete_product_transients' ) ) {
		wc_delete_product_transients();
	}
}
add_action( 'init', 'lof_upgrade_shop_products', 30 );

/**
 * 0.44.0: add "Shop with purpose" to the home page once, before the closing CTA.
 */
function lof_upgrade_home_market() {
	if ( (int) get_option( 'lof_market_rev', 0 ) >= 1 ) {
		return;
	}
	update_option( 'lof_market_rev', 1, false );
	$page = get_page_by_path( 'home', OBJECT, 'page' );
	if ( ! $page ) {
		$front = (int) get_option( 'page_on_front' );
		$page  = $front ? get_post( $front ) : null;
	}
	if ( ! $page ) {
		return;
	}
	$raw  = get_post_meta( $page->ID, '_elementor_data', true );
	$data = is_string( $raw ) ? json_decode( $raw, true ) : $raw;
	if ( ! is_array( $data ) || false !== strpos( (string) wp_json_encode( $data ), 'lof_home_market' ) ) {
		return;
	}
	$pos = count( $data );
	foreach ( $data as $i => $sec ) {
		if ( false !== strpos( (string) wp_json_encode( $sec ), '"widgetType":"lof-cta"' ) ) {
			$pos = $i;
		}
	}
	array_splice( $data, $pos, 0, array( lof_el( 'shortcode', array( 'shortcode' => '[lof_home_market count="4"]' ) ) ) );
	update_post_meta( $page->ID, '_lof_layout_backup', wp_slash( is_string( $raw ) ? $raw : wp_json_encode( $raw ) ) );
	update_post_meta( $page->ID, '_lof_layout_backup_date', current_time( 'mysql' ) );
	update_post_meta( $page->ID, '_elementor_data', wp_slash( wp_json_encode( $data ) ) );
	delete_post_meta( $page->ID, '_elementor_css' );
	delete_post_meta( $page->ID, '_elementor_element_cache' );
	if ( class_exists( '\\Elementor\\Plugin' ) && isset( \Elementor\Plugin::$instance->files_manager ) ) {
		\Elementor\Plugin::$instance->files_manager->clear_cache();
	}
	if ( has_action( 'litespeed_purge_all' ) ) {
		do_action( 'litespeed_purge_all' );
	}
}
add_action( 'init', 'lof_upgrade_home_market', 31 );
