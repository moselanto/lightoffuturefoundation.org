<?php
/**
 * 0.63.0: shop browsing (search, category slider with photos, price bands, sorting,
 * uniform product grid), page loader, and a still first Home slide until the page has loaded.
 *
 * @package LightOfFuture
 */

defined( 'ABSPATH' ) || exit;

/**
 * Price bands (KSh).
 *
 * @return array slug => array( label, min, max|null )
 */
function lof_shop_price_bands() {
	return array(
		'under-500'  => array( __( 'Under KSh 500', 'light-of-future' ), 0, 499.99 ),
		'500-2000'   => array( __( 'KSh 500 - 2,000', 'light-of-future' ), 500, 2000 ),
		'2000-5000'  => array( __( 'KSh 2,000 - 5,000', 'light-of-future' ), 2000.01, 5000 ),
		'5000-plus'  => array( __( 'KSh 5,000 and over', 'light-of-future' ), 5000.01, null ),
	);
}

/**
 * Current search text and price band from the URL.
 *
 * @return array
 */
function lof_shop_state() {
	// phpcs:disable WordPress.Security.NonceVerification.Recommended
	$q     = isset( $_GET['q'] ) ? sanitize_text_field( wp_unslash( $_GET['q'] ) ) : '';
	$price = isset( $_GET['price'] ) ? sanitize_key( wp_unslash( $_GET['price'] ) ) : '';
	// phpcs:enable
	$bands = lof_shop_price_bands();
	return array(
		'q'     => mb_substr( $q, 0, 80 ),
		'price' => isset( $bands[ $price ] ) ? $price : '',
	);
}

/**
 * Apply search and price band to the shop query.
 *
 * @param WP_Query $query Query.
 */
function lof_shop_apply_filters( $query ) {
	$s = lof_shop_state();
	if ( '' !== $s['q'] ) {
		$query->set( 's', $s['q'] );
	}
	if ( '' !== $s['price'] ) {
		$b    = lof_shop_price_bands()[ $s['price'] ];
		$meta = (array) $query->get( 'meta_query' );
		if ( null === $b[2] ) {
			$meta[] = array( 'key' => '_price', 'value' => $b[1], 'compare' => '>=', 'type' => 'DECIMAL(10,2)' );
		} else {
			$meta[] = array( 'key' => '_price', 'value' => array( $b[1], $b[2] ), 'compare' => 'BETWEEN', 'type' => 'DECIMAL(10,2)' );
		}
		$query->set( 'meta_query', $meta );
	}
}
add_action( 'woocommerce_product_query', 'lof_shop_apply_filters' );

/**
 * Filtered shop pages are not separate pages for search engines.
 */
function lof_shop_filter_noindex() {
	$s = lof_shop_state();
	if ( function_exists( 'lof_is_shop_archive' ) && lof_is_shop_archive() && ( $s['q'] || $s['price'] ) ) {
		echo '<meta name="robots" content="noindex, follow">' . "\n";
	}
}
add_action( 'wp_head', 'lof_shop_filter_noindex', 1 );

/**
 * Body class for the new shop layout.
 *
 * @param array $c Classes.
 * @return array
 */
function lof_shop63_body_class( $c ) {
	if ( function_exists( 'lof_is_shop_archive' ) && lof_is_shop_archive() ) {
		$c[] = 'lof-shop63';
	}
	return $c;
}
add_filter( 'body_class', 'lof_shop63_body_class' );

/**
 * Swap the old chips and the stock sorting form for the new browsing bar.
 */
function lof_shop63_setup() {
	if ( ! function_exists( 'lof_is_shop_archive' ) || ! lof_is_shop_archive() ) {
		return;
	}
	remove_action( 'woocommerce_before_shop_loop', 'lof_shop_chips', 5 );
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
	add_action( 'woocommerce_before_shop_loop', 'lof_shop_browse', 5 );
	add_action( 'woocommerce_no_products_found', 'lof_shop_browse', 5 );
	add_action( 'woocommerce_shop_loop_item_title', 'lof_shop_card_category', 5 );
	if ( is_shop() ) {
		remove_action( 'woocommerce_archive_description', 'lof_shop_hero', 4 );
		remove_action( 'woocommerce_archive_description', 'lof_shop_intro', 5 );
		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
		add_action( 'woocommerce_archive_description', 'lof_shop_hero63', 4 );
	}
}
add_action( 'wp', 'lof_shop63_setup', 20 );

/**
 * Small category label above each product name.
 */
function lof_shop_card_category() {
	global $product;
	if ( ! $product ) {
		return;
	}
	$terms = get_the_terms( $product->get_id(), 'product_cat' );
	if ( $terms && ! is_wp_error( $terms ) ) {
		echo '<span class="lof-card__cat">' . esc_html( $terms[0]->name ) . '</span>';
	}
}

/**
 * Photo for a category: its own thumbnail, otherwise its first product photo.
 *
 * @param WP_Term $t Term.
 * @return string URL or ''.
 */
function lof_shop_cat_photo( $t ) {
	$id = (int) get_term_meta( $t->term_id, 'thumbnail_id', true );
	if ( ! $id ) {
		$p = get_posts( array( 'post_type' => 'product', 'posts_per_page' => 1, 'fields' => 'ids', 'tax_query' => array( array( 'taxonomy' => 'product_cat', 'terms' => $t->term_id ) ), 'meta_key' => '_thumbnail_id', 'orderby' => 'menu_order date', 'order' => 'ASC' ) ); // phpcs:ignore WordPress.DB.SlowDBQuery
		$id = $p ? (int) get_post_thumbnail_id( $p[0] ) : 0;
	}
	return $id ? (string) wp_get_attachment_image_url( $id, 'woocommerce_thumbnail' ) : '';
}

/**
 * URL keeping the current search, band and sort, with changes.
 *
 * @param array  $set  Args to set (null removes).
 * @param string $base Base URL.
 * @return string
 */
function lof_shop_url( $set, $base = '' ) {
	$s    = lof_shop_state();
	$args = array_filter(
		array(
			'q'       => $s['q'],
			'price'   => $s['price'],
			'orderby' => isset( $_GET['orderby'] ) ? sanitize_key( wp_unslash( $_GET['orderby'] ) ) : '', // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		)
	);
	foreach ( $set as $k => $v ) {
		if ( null === $v || '' === $v ) {
			unset( $args[ $k ] );
		} else {
			$args[ $k ] = $v;
		}
	}
	if ( '' === $base ) {
		$base = is_product_taxonomy() ? get_term_link( get_queried_object() ) : wc_get_page_permalink( 'shop' );
	}
	return add_query_arg( $args, $base ) . '#lof-shop-collection';
}

/**
 * Search, category slider, price bands, count and sorting.
 */
function lof_shop_browse() {
	static $done = false;
	if ( $done ) {
		return;
	}
	$done    = true;
	$s       = lof_shop_state();
	$shop    = wc_get_page_permalink( 'shop' );
	$current = is_product_category() ? get_queried_object_id() : 0;
	$action  = is_product_taxonomy() ? get_term_link( get_queried_object() ) : $shop;
	$terms   = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => true, 'parent' => 0 ) );
	$terms   = is_wp_error( $terms ) ? array() : array_values( array_filter( $terms, function ( $t ) { return 'uncategorized' !== $t->slug; } ) );
	$total   = (int) wc_get_loop_prop( 'total' );
	$all_n   = wp_count_posts( 'product' );
	$all_n   = isset( $all_n->publish ) ? (int) $all_n->publish : 0;
	?>
	<div class="lof-browse" id="lof-shop-collection">
		<form class="lof-browse__search" role="search" method="get" action="<?php echo esc_url( $action ); ?>">
			<label class="screen-reader-text" for="lof-shop-q"><?php esc_html_e( 'Search the shop', 'light-of-future' ); ?></label>
			<svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true" focusable="false"><path fill="currentColor" d="M10.5 3a7.5 7.5 0 0 1 5.9 12.1l4.8 4.8-1.4 1.4-4.8-4.8A7.5 7.5 0 1 1 10.5 3zm0 2a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11z"/></svg>
			<input type="search" id="lof-shop-q" name="q" value="<?php echo esc_attr( $s['q'] ); ?>" placeholder="<?php esc_attr_e( 'Search bags, jewellery, sandals, gifts...', 'light-of-future' ); ?>" maxlength="80" autocomplete="off">
			<?php if ( $s['price'] ) : ?><input type="hidden" name="price" value="<?php echo esc_attr( $s['price'] ); ?>"><?php endif; ?>
			<button type="submit"><?php esc_html_e( 'Search', 'light-of-future' ); ?></button>
		</form>

		<?php if ( $terms ) : ?>
		<section class="lof-cats" aria-labelledby="lof-cats-title">
			<div class="lof-cats__head">
				<div>
					<p class="lof-cats__eyebrow"><?php esc_html_e( 'Handmade in Kenya', 'light-of-future' ); ?></p>
					<h2 class="lof-cats__title" id="lof-cats-title"><?php esc_html_e( 'Shop by category', 'light-of-future' ); ?></h2>
				</div>
				<div class="lof-cats__nav">
					<button type="button" class="lof-cats__arrow" data-cats-prev aria-label="<?php esc_attr_e( 'Previous categories', 'light-of-future' ); ?>"><svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true"><path fill="currentColor" d="M15.4 7.4 10.8 12l4.6 4.6L14 18l-6-6 6-6z"/></svg></button>
					<button type="button" class="lof-cats__arrow" data-cats-next aria-label="<?php esc_attr_e( 'More categories', 'light-of-future' ); ?>"><svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true"><path fill="currentColor" d="M8.6 16.6 13.2 12 8.6 7.4 10 6l6 6-6 6z"/></svg></button>
				</div>
			</div>
			<ul class="lof-cats__track" data-cats-track>
				<li><a class="lof-cat lof-cat--all" href="<?php echo esc_url( lof_shop_url( array(), $shop ) ); ?>"<?php echo $current ? '' : ' aria-current="page"'; ?>>
					<span class="lof-cat__media lof-cat__media--all" aria-hidden="true"><svg viewBox="0 0 24 24" width="34" height="34"><path fill="currentColor" d="M4 4h7v7H4zm9 0h7v7h-7zM4 13h7v7H4zm9 0h7v7h-7z"/></svg></span>
					<span class="lof-cat__label"><strong><?php esc_html_e( 'All products', 'light-of-future' ); ?></strong><small><?php /* translators: %d items */ echo esc_html( sprintf( _n( '%d item', '%d items', $all_n, 'light-of-future' ), $all_n ) ); ?></small></span>
				</a></li>
				<?php foreach ( $terms as $t ) : $ph = lof_shop_cat_photo( $t ); ?>
				<li><a class="lof-cat" href="<?php echo esc_url( lof_shop_url( array(), get_term_link( $t ) ) ); ?>"<?php echo $current === (int) $t->term_id ? ' aria-current="page"' : ''; ?>>
					<span class="lof-cat__media"><?php if ( $ph ) : ?><img src="<?php echo esc_url( $ph ); ?>" alt="" width="300" height="300" loading="lazy" decoding="async"><?php endif; ?></span>
					<span class="lof-cat__label"><strong><?php echo esc_html( $t->name ); ?></strong><small><?php /* translators: %d items */ echo esc_html( sprintf( _n( '%d item', '%d items', (int) $t->count, 'light-of-future' ), (int) $t->count ) ); ?></small></span>
				</a></li>
				<?php endforeach; ?>
			</ul>
		</section>
		<?php endif; ?>

		<div class="lof-filterbar">
			<div class="lof-filterbar__prices" role="group" aria-label="<?php esc_attr_e( 'Filter by price', 'light-of-future' ); ?>">
				<span class="lof-filterbar__label"><svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true"><path fill="currentColor" d="M3 5h18v2l-7 7v5l-4 2v-7L3 7z"/></svg><?php esc_html_e( 'Price', 'light-of-future' ); ?></span>
				<a class="lof-pill" href="<?php echo esc_url( lof_shop_url( array( 'price' => null ) ) ); ?>"<?php echo $s['price'] ? '' : ' aria-current="true"'; ?>><?php esc_html_e( 'Any', 'light-of-future' ); ?></a>
				<?php foreach ( lof_shop_price_bands() as $slug => $b ) : ?>
					<a class="lof-pill" href="<?php echo esc_url( lof_shop_url( array( 'price' => $slug ) ) ); ?>"<?php echo $slug === $s['price'] ? ' aria-current="true"' : ''; ?>><?php echo esc_html( $b[0] ); ?></a>
				<?php endforeach; ?>
			</div>
			<div class="lof-filterbar__right">
				<p class="lof-filterbar__count"><?php /* translators: %d products */ echo esc_html( sprintf( _n( '%d product', '%d products', $total, 'light-of-future' ), $total ) ); ?></p>
				<?php if ( function_exists( 'woocommerce_catalog_ordering' ) && $total ) { woocommerce_catalog_ordering(); } ?>
			</div>
		</div>
		<?php if ( $s['q'] || $s['price'] ) : ?>
			<p class="lof-filterbar__active">
				<?php if ( $s['q'] ) : ?>
					<?php /* translators: %s search text */ echo esc_html( sprintf( __( 'Results for "%s"', 'light-of-future' ), $s['q'] ) ); ?>
				<?php endif; ?>
				<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) . '#lof-shop-collection' ); ?>"><?php esc_html_e( 'Clear filters', 'light-of-future' ); ?></a>
			</p>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Keep the search text and price band when the sort order changes.
 */
function lof_shop_ordering_hidden() {
	$s = lof_shop_state();
	foreach ( array( 'q', 'price' ) as $k ) {
		if ( '' !== $s[ $k ] ) {
			echo '<input type="hidden" name="' . esc_attr( $k ) . '" value="' . esc_attr( $s[ $k ] ) . '">';
		}
	}
}
// WooCommerce's sort form already keeps other URL values (wc_query_string_form_fields).

/**
 * Shorter, clearer sort labels; no rating sort (the shop has no reviews).
 *
 * @param array $o Options.
 * @return array
 */
function lof_shop_sort_labels( $o ) {
	unset( $o['rating'] );
	$map = array(
		'menu_order' => __( 'Featured', 'light-of-future' ),
		'popularity' => __( 'Most popular', 'light-of-future' ),
		'date'       => __( 'Newest', 'light-of-future' ),
		'price'      => __( 'Price: low to high', 'light-of-future' ),
		'price-desc' => __( 'Price: high to low', 'light-of-future' ),
	);
	foreach ( $map as $k => $v ) {
		if ( isset( $o[ $k ] ) ) {
			$o[ $k ] = $v;
		}
	}
	return $o;
}
add_filter( 'woocommerce_catalog_orderby', 'lof_shop_sort_labels', 20 );

/**
 * Page loader: light rays around the foundation's symbol. Same safety rules as the Cistern of Hope
 * loader: it removes itself with CSS alone, the script only makes it leave sooner, and it shows
 * once per browsing session. Turn off with add_filter( 'lof_page_loader', '__return_false' ).
 */
function lof_page_loader() {
	if ( is_admin() || isset( $_GET['elementor-preview'] ) || isset( $_GET['lof_mobile_preview'] ) || is_customize_preview() || ! apply_filters( 'lof_page_loader', true ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return;
	}
	?>
<div class="lof-loader" id="lof-loader" role="status" aria-label="<?php esc_attr_e( 'Loading', 'light-of-future' ); ?>">
	<div class="lof-loader__stage" aria-hidden="true">
		<span class="lof-loader__ring"></span><span class="lof-loader__ring"></span><span class="lof-loader__ring"></span>
		<span class="lof-loader__rays"></span>
		<img class="lof-loader__mark" src="<?php echo esc_url( LOF_THEME_URI . '/assets/images/brand/symbol-light.svg' ); ?>" alt="" width="56" height="56">
	</div>
	<p class="lof-loader__name"><?php esc_html_e( 'Light of Future Foundation', 'light-of-future' ); ?></p>
	<p class="lof-loader__strap"><?php esc_html_e( 'Bringing hope to communities in Kenya', 'light-of-future' ); ?></p>
</div>
<script>(function(){var el=document.getElementById('lof-loader');if(!el){return;}var done=false;function dismiss(){if(done){return;}done=true;el.className+=' is-done';}try{if(sessionStorage.getItem('lofLoaderSeen')==='1'){el.className+=' is-instant';dismiss();return;}sessionStorage.setItem('lofLoaderSeen','1');}catch(e){}if(document.readyState!=='loading'){dismiss();}else{document.addEventListener('DOMContentLoaded',dismiss);}setTimeout(dismiss,900);}());</script>
	<?php
}
add_action( 'wp_body_open', 'lof_page_loader', 1 );

/**
 * Loader styles in the head (they must apply before any stylesheet arrives).
 */
function lof_page_loader_css() {
	if ( is_admin() || isset( $_GET['elementor-preview'] ) || isset( $_GET['lof_mobile_preview'] ) || ! apply_filters( 'lof_page_loader', true ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return;
	}
	echo '<style id="lof-loader-css">.lof-loader{position:fixed;inset:0;z-index:100000;display:flex;flex-direction:column;align-items:center;justify-content:center;background:radial-gradient(circle at 50% 42%,#5C3D1E 0,#3F2913 46%,#2B1D0E 100%);color:#FCF6E1;animation:lof-loader-out .4s ease .9s forwards}.lof-loader.is-done{opacity:0;visibility:hidden;pointer-events:none;animation:none;transition:opacity .42s ease,visibility 0s linear .42s}.lof-loader.is-instant{transition:none}@keyframes lof-loader-out{to{opacity:0;visibility:hidden;pointer-events:none}}.lof-loader__stage{position:relative;display:grid;place-items:center;width:140px;height:140px;margin-bottom:28px}.lof-loader__ring{position:absolute;inset:0;border:2px solid #F4AF16;border-radius:50%;opacity:0;transform:scale(.3);animation:lof-ring 2.4s cubic-bezier(.22,.61,.36,1) infinite}.lof-loader__ring:nth-child(2){animation-delay:.6s}.lof-loader__ring:nth-child(3){animation-delay:1.2s}@keyframes lof-ring{0%{transform:scale(.3);opacity:0}15%{opacity:.85}100%{transform:scale(1);opacity:0}}.lof-loader__rays{position:absolute;width:96px;height:96px;border-radius:50%;background:repeating-conic-gradient(from 0deg,rgba(251,214,43,.55) 0 6deg,transparent 6deg 30deg);-webkit-mask:radial-gradient(circle,transparent 34px,#000 35px,#000 47px,transparent 48px);mask:radial-gradient(circle,transparent 34px,#000 35px,#000 47px,transparent 48px);animation:lof-rays 6s linear infinite}@keyframes lof-rays{to{transform:rotate(360deg)}}.lof-loader__mark{position:relative;width:56px;height:56px;filter:drop-shadow(0 0 14px rgba(244,175,22,.55));animation:lof-glow 2.4s ease-in-out infinite}@keyframes lof-glow{0%,100%{transform:scale(1)}50%{transform:scale(1.08)}}.lof-loader__name{margin:0;font:800 1.15rem/1.2 Nunito,Figtree,system-ui,sans-serif;letter-spacing:.02em}.lof-loader__strap{margin:.4rem 0 0;font:600 .72rem/1.4 Figtree,system-ui,sans-serif;letter-spacing:.18em;text-transform:uppercase;color:#F4AF16}@media (prefers-reduced-motion:reduce){.lof-loader__ring,.lof-loader__rays,.lof-loader__mark{animation:none}.lof-loader__ring{opacity:.35;transform:scale(.8)}}</style>' . "\n";
}
add_action( 'wp_head', 'lof_page_loader_css', 3 );

/**
 * Home slider: the first slide stays still (no text entrance, no progress bar) until the page has loaded.
 */
function lof_cine_still_css() {
	if ( ! is_front_page() ) {
		return;
	}
	echo '<style id="lof-cine-still">[data-lof-cine]:not(.has-moved) .lof-cine__slide.is-active .lof-cine__eyebrow,[data-lof-cine]:not(.has-moved) .lof-cine__slide.is-active .lof-cine__title,[data-lof-cine]:not(.has-moved) .lof-cine__slide.is-active .lof-cine__text,[data-lof-cine]:not(.has-moved) .lof-cine__slide.is-active .lof-btn-row{animation:none!important;opacity:1!important;transform:none!important}[data-lof-cine]:not(.is-ready) .lof-cine__bar span{animation:none!important;width:0!important}</style>' . "\n";
}
add_action( 'wp_head', 'lof_cine_still_css', 4 );

/**
 * 0.63.1: full-width shop hero with a photo mosaic of the makers' work.
 */
function lof_shop_hero63() {
	$img   = LOF_THEME_URI . '/assets/images/shop/';
	$tiles = array( 'bags-collection', 'earrings-hoop-multicolour', 'basket-bag-turquoise', 'bangles-gold', 'sandals-teardrop', 'necklace-bib-collection', 'earrings-display', 'bangles-turquoise' );
	$count = wp_count_posts( 'product' );
	$count = isset( $count->publish ) ? (int) $count->publish : 0;
	$terms = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => true, 'parent' => 0, 'fields' => 'ids', 'exclude' => array( (int) get_option( 'default_product_cat' ) ) ) );
	$cats  = is_wp_error( $terms ) ? 0 : count( $terms );
	$wa    = lof_wa_link( __( 'Hello Light of Future Foundation, I would like to ask about a custom or bulk order from your shop.', 'light-of-future' ) );
	?>
	<section class="lof-shopx" aria-labelledby="lof-shopx-title">
		<div class="lof-shopx__mosaic" aria-hidden="true">
			<?php foreach ( $tiles as $i => $t ) : ?>
				<figure class="lof-shopx__tile lof-shopx__tile--<?php echo (int) $i + 1; ?>"><img src="<?php echo esc_url( $img . 'sm/' . $t . '.webp' ); ?>" srcset="<?php echo esc_url( $img . 'sm/' . $t . '.webp' ); ?> 320w, <?php echo esc_url( $img . $t . '.webp' ); ?> 900w" sizes="(max-width: 767px) 34vw, 22vw" alt="" width="900" height="900" <?php echo $i < 3 ? 'fetchpriority="high"' : 'loading="lazy"'; ?> decoding="async"></figure>
			<?php endforeach; ?>
		</div>
		<div class="lof-shopx__shade" aria-hidden="true"></div>
		<div class="lof-shopx__inner">
			<p class="lof-shopx__eyebrow"><span aria-hidden="true"></span><?php esc_html_e( 'The Light of Future Shop', 'light-of-future' ); ?></p>
			<h1 class="lof-shopx__title" id="lof-shopx-title"><?php esc_html_e( 'Handmade in Kenya,', 'light-of-future' ); ?> <em><?php esc_html_e( 'made to give back', 'light-of-future' ); ?></em></h1>
			<p class="lof-shopx__lead"><?php esc_html_e( 'Beaded jewellery, leather bags and sandals, each made by hand by community makers. Every purchase supports the makers and our work with vulnerable children, widows and families.', 'light-of-future' ); ?></p>
			<div class="lof-shopx__actions">
				<a class="lof-shopx__btn lof-shopx__btn--primary" href="#lof-shop-collection"><?php esc_html_e( 'Shop the collection', 'light-of-future' ); ?> <span aria-hidden="true">&rarr;</span></a>
				<a class="lof-shopx__btn lof-shopx__btn--ghost" href="<?php echo esc_url( $wa ); ?>" target="_blank" rel="noopener noreferrer"><?php echo lof_wa_icon( 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?><span><?php esc_html_e( 'Custom and bulk orders', 'light-of-future' ); ?></span></a>
			</div>
			<ul class="lof-shopx__facts">
				<?php if ( $count ) : ?><li><strong><?php echo esc_html( number_format_i18n( $count ) ); ?></strong><span><?php esc_html_e( 'handmade products', 'light-of-future' ); ?></span></li><?php endif; ?>
				<?php if ( $cats ) : ?><li><strong><?php echo esc_html( number_format_i18n( $cats ) ); ?></strong><span><?php esc_html_e( 'collections', 'light-of-future' ); ?></span></li><?php endif; ?>
				<li><strong>100%</strong><span><?php esc_html_e( 'made by hand', 'light-of-future' ); ?></span></li>
			</ul>
		</div>
	</section>
	<ul class="lof-shoptrust lof-shoptrust--x" aria-label="<?php esc_attr_e( 'Why shop with us', 'light-of-future' ); ?>">
		<li><span class="lof-shoptrust__ico" aria-hidden="true"><svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M12 2l2.4 5 5.6.8-4 3.9.9 5.5L12 14.6 7.1 17.2 8 11.7 4 7.8l5.6-.8z"/></svg></span><strong><?php esc_html_e( 'Handmade quality', 'light-of-future' ); ?></strong><span><?php esc_html_e( 'Finished by hand. No two pieces are exactly alike.', 'light-of-future' ); ?></span></li>
		<li><span class="lof-shoptrust__ico" aria-hidden="true"><svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M12 21s-7.5-4.6-9.5-9.3C1.1 8.3 3.2 5 6.6 5c2 0 3.4 1.1 4.4 2.5C12 6.1 13.4 5 15.4 5c3.4 0 5.5 3.3 4.1 6.7C19.5 16.4 12 21 12 21z"/></svg></span><strong><?php esc_html_e( 'Shopping that gives back', 'light-of-future' ); ?></strong><span><?php esc_html_e( 'Every order supports makers and our programmes.', 'light-of-future' ); ?></span></li>
		<li><span class="lof-shoptrust__ico" aria-hidden="true"><?php echo lof_wa_icon( 22 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></span><strong><?php esc_html_e( 'Easy WhatsApp ordering', 'light-of-future' ); ?></strong><span><?php esc_html_e( 'Pick a design and our team confirms with you.', 'light-of-future' ); ?></span></li>
		<li><span class="lof-shoptrust__ico" aria-hidden="true"><svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M3 6h11v9H3zm11 3h4l3 3v3h-7zM6.5 19a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm11 0a2 2 0 1 1 0-4 2 2 0 0 1 0 4z"/></svg></span><strong><?php esc_html_e( 'Delivery arranged with you', 'light-of-future' ); ?></strong><span><?php esc_html_e( 'Nairobi, across Kenya and abroad.', 'light-of-future' ); ?></span></li>
	</ul>
	<?php
}
