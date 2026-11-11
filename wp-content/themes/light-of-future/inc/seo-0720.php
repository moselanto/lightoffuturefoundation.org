<?php
/**
 * 0.72.0 site-wide clean-up (audit, 11 Nov 2026). Always loaded.
 *
 * 1. Unique meta descriptions: pages without an excerpt used one shared sentence on 23 pages.
 *    Each page now uses its own existing intro line (no new wording).
 * 2. Open Graph and Twitter card tags so links shared on WhatsApp, Facebook and X show a title,
 *    description and picture.
 * 3. Canonical links on the shop and product category pages (WordPress only adds them to single pages).
 * 4. Story topic pages (/stories/topic/...) returned 404 but were listed in the sitemap: removed from
 *    the sitemap and sent to /stories/ with a 301.
 * 5. Privacy Policy and Terms still hold placeholder text: kept out of search results until the
 *    foundation approves the wording.
 * 6. Old phone number (+254 715 394295) still shown in the FAQ answer: replaced on output with the
 *    official number (the stored copy is fixed by lof_upgrade_0720_phone_text in upgrade-0700.php).
 *
 * @package LightOfFuture
 */

defined( 'ABSPATH' ) || exit;

/**
 * True when an SEO plugin already prints these tags.
 *
 * @return bool
 */
function lof_seo_plugin_active() {
	return defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'AIOSEO_VERSION' ) || defined( 'SEOPRESS_VERSION' );
}

/**
 * Site-wide fallback description.
 *
 * @return string
 */
function lof_seo_default_desc() {
	return __( 'Light of Future Foundation is a registered NGO in Nairobi, Kenya, supporting widows, widowers, orphans, vulnerable children and people living with disabilities through education, clean water and community programmes.', 'light-of-future' );
}

/**
 * Description for the current request. Uses wording already on each page.
 *
 * @return string
 */
function lof_seo_description() {
	$map = array(
		'about-us'           => __( 'A registered charitable organisation restoring hope, dignity and opportunity in communities across Kenya.', 'light-of-future' ),
		'donate'             => __( 'Your gift builds safe homes, keeps children in school and brings clean water and care to vulnerable families.', 'light-of-future' ),
		'faq'                => __( 'Clear answers about who we are, where we work and how you can help.', 'light-of-future' ),
		'get-involved'       => __( 'There are many ways to stand with vulnerable families in Kenya.', 'light-of-future' ),
		'impact'             => __( 'The numbers behind our work, and the people behind the numbers.', 'light-of-future' ),
		'leadership'         => __( "The people accountable for the foundation's work, and the structure they are accountable through.", 'light-of-future' ),
		'programmes'         => __( 'Eleven focus areas that restore hope, dignity and self-reliance.', 'light-of-future' ),
		'stories'            => __( 'These are just a few of the lives changed through your support.', 'light-of-future' ),
		'talent-development' => __( 'Encouraging youth talents through sports, music and creative arts.', 'light-of-future' ),
		'contact'            => __( 'Give, volunteer or partner with us to bring hope and opportunity to families in Kenya.', 'light-of-future' ),
	);
	$desc = '';
	if ( is_front_page() ) {
		$desc = __( 'We support widows, widowers, orphans, vulnerable children and people living with disabilities through education, clean water and community programmes.', 'light-of-future' );
	} elseif ( is_singular() ) {
		$post = get_queried_object();
		if ( $post instanceof WP_Post ) {
			if ( has_excerpt( $post ) ) {
				$desc = get_the_excerpt( $post );
			} elseif ( isset( $map[ $post->post_name ] ) ) {
				$desc = $map[ $post->post_name ];
			} elseif ( 'product' === $post->post_type ) {
				$desc = wp_trim_words( wp_strip_all_tags( strip_shortcodes( $post->post_content ) ), 30, '' );
			}
			if ( '' === trim( (string) $desc ) ) {
				/* translators: 1: page title, 2: site description. */
				$desc = sprintf( __( '%1$s: %2$s', 'light-of-future' ), get_the_title( $post ), lof_seo_default_desc() );
			}
		}
	} elseif ( is_tax() || is_category() ) {
		$term = get_queried_object();
		$desc = ( $term && ! empty( $term->description ) ) ? $term->description : '';
		if ( '' === trim( (string) $desc ) && $term && isset( $term->name ) ) {
			/* translators: %s: category name. */
			$desc = sprintf( __( '%s handmade in Kenya, sold to support the work of Light of Future Foundation.', 'light-of-future' ), $term->name );
		}
	} elseif ( function_exists( 'is_shop' ) && is_shop() ) {
		$shop = (int) wc_get_page_id( 'shop' );
		$desc = $shop > 0 && has_excerpt( $shop ) ? get_the_excerpt( $shop ) : '';
	}
	if ( '' === trim( (string) $desc ) ) {
		$desc = lof_seo_default_desc();
	}
	return wp_trim_words( wp_strip_all_tags( html_entity_decode( (string) $desc, ENT_QUOTES, 'UTF-8' ) ), 32, '' );
}

/**
 * Image for social cards.
 *
 * @return string
 */
function lof_seo_image() {
	$id = 0;
	if ( is_singular() && has_post_thumbnail( get_queried_object_id() ) ) {
		$id = (int) get_post_thumbnail_id( get_queried_object_id() );
	}
	if ( ! $id ) {
		$front = (int) get_option( 'page_on_front' );
		$id    = $front && has_post_thumbnail( $front ) ? (int) get_post_thumbnail_id( $front ) : 0;
	}
	if ( ! $id ) {
		$id = (int) get_theme_mod( 'custom_logo' );
	}
	$url = $id ? wp_get_attachment_image_url( $id, 'large' ) : '';
	return $url ? $url : (string) get_site_icon_url( 512 );
}

/**
 * Replace the 0.6x meta description with the improved one and add social card tags.
 */
function lof_seo_head() {
	if ( lof_seo_plugin_active() || is_404() || is_search() ) {
		return;
	}
	$desc  = lof_seo_description();
	$title = wp_get_document_title();
	$url   = '';
	if ( is_singular() ) {
		$url = (string) wp_get_canonical_url( get_queried_object_id() );
	} elseif ( is_front_page() || is_home() ) {
		$url = home_url( '/' );
	} elseif ( is_tax() || is_category() ) {
		$link = get_term_link( get_queried_object() );
		$url  = is_wp_error( $link ) ? '' : $link;
	} elseif ( function_exists( 'is_shop' ) && is_shop() ) {
		$url = (string) get_permalink( wc_get_page_id( 'shop' ) );
	}
	$img = lof_seo_image();
	printf( '<meta name="description" content="%s">' . "\n", esc_attr( $desc ) );
	echo '<meta name="theme-color" content="#5C3D1E">' . "\n";
	if ( ! is_singular() && '' !== $url ) {
		printf( '<link rel="canonical" href="%s">' . "\n", esc_url( $url ) );
	}
	printf( '<meta property="og:site_name" content="%s">' . "\n", esc_attr( get_bloginfo( 'name' ) ) );
	echo '<meta property="og:locale" content="en_GB">' . "\n";
	printf( '<meta property="og:type" content="%s">' . "\n", ( is_singular( array( 'lof_story', 'post' ) ) ? 'article' : 'website' ) );
	printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( $title ) );
	printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( $desc ) );
	if ( '' !== $url ) {
		printf( '<meta property="og:url" content="%s">' . "\n", esc_url( $url ) );
	}
	if ( '' !== $img ) {
		printf( '<meta property="og:image" content="%s">' . "\n", esc_url( $img ) );
	}
	printf( '<meta name="twitter:card" content="%s">' . "\n", '' !== $img ? 'summary_large_image' : 'summary' );
	printf( '<meta name="twitter:title" content="%s">' . "\n", esc_attr( $title ) );
	printf( '<meta name="twitter:description" content="%s">' . "\n", esc_attr( $desc ) );
	if ( '' !== $img ) {
		printf( '<meta name="twitter:image" content="%s">' . "\n", esc_url( $img ) );
	}
}
remove_action( 'wp_head', 'lof_meta_description', 2 );
add_action(
	'wp',
	function () {
		remove_action( 'wp_head', 'lof_meta_description', 2 );
	}
);
add_action( 'wp_head', 'lof_seo_head', 2 );

/**
 * Story topic pages: out of the sitemap, and sent to the Stories page.
 */
add_filter(
	'wp_sitemaps_taxonomies',
	function ( $tax ) {
		unset( $tax['lof_story_topic'] );
		return $tax;
	},
	20
);
add_action(
	'template_redirect',
	function () {
		$path = isset( $_SERVER['REQUEST_URI'] ) ? (string) wp_parse_url( esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ), PHP_URL_PATH ) : '';
		if ( '' !== $path && preg_match( '#^/stories/topic(/|$)#', $path ) ) {
			wp_safe_redirect( home_url( '/stories/' ), 301 );
			exit;
		}
	},
	1
);

/**
 * Placeholder legal pages stay out of search results until approved wording is published.
 *
 * @param array $robots Robots directives.
 * @return array
 */
function lof_seo_noindex_placeholder_legal( $robots ) {
	if ( is_page( array( 'privacy-policy', 'terms' ) ) ) {
		$post = get_queried_object();
		$raw  = $post instanceof WP_Post ? $post->post_content . (string) get_post_meta( $post->ID, '_elementor_data', true ) : '';
		if ( false !== stripos( $raw, 'awaiting wording approved' ) || false !== stripos( $raw, 'Placeholder:' ) ) {
			$robots['noindex'] = true;
			$robots['follow']  = true;
			unset( $robots['max-image-preview'] );
		}
	}
	return $robots;
}
add_filter( 'wp_robots', 'lof_seo_noindex_placeholder_legal', 25 );
add_filter(
	'wp_sitemaps_posts_query_args',
	function ( $args, $post_type ) {
		if ( 'page' !== $post_type ) {
			return $args;
		}
		$ids = array();
		foreach ( array( 'privacy-policy', 'terms' ) as $slug ) {
			$p = get_page_by_path( $slug );
			if ( $p && ( false !== stripos( $p->post_content . (string) get_post_meta( $p->ID, '_elementor_data', true ), 'awaiting wording approved' ) ) ) {
				$ids[] = (int) $p->ID;
			}
		}
		if ( $ids ) {
			$args['post__not_in'] = array_merge( isset( $args['post__not_in'] ) ? (array) $args['post__not_in'] : array(), $ids );
		}
		return $args;
	},
	20,
	2
);

/**
 * Old phone number anywhere in page content becomes the official number.
 *
 * @param string $html Content.
 * @return string
 */
function lof_seo_fix_old_phone( $html ) {
	if ( ! is_string( $html ) || false === strpos( $html, '394295' ) ) {
		return $html;
	}
	return str_replace(
		array( '+254 715 394295', '+254715394295', '0715 394295', '0715394295', '254715394295' ),
		array( '+254 716 385 485', '+254716385485', '0716 385 485', '0716385485', '254716385485' ),
		$html
	);
}
add_filter( 'the_content', 'lof_seo_fix_old_phone', 999 );
add_filter( 'elementor/frontend/the_content', 'lof_seo_fix_old_phone', 999 );
add_filter( 'elementor/widget/render_content', 'lof_seo_fix_old_phone', 999 );
