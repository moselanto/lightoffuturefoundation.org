<?php
/**
 * Structured data (JSON-LD) from verified facts only (0.46.5).
 *
 * Outputs one schema.org graph in wp_head:
 * - every page: the foundation as NGO + Organization, and a WebSite node;
 * - /donate/: a DonateAction (potentialAction on the NGO) targeting the Donate page;
 * - single stories (lof_story): an Article published by the NGO.
 *
 * Every fact comes from Light of Future > Organisation details or from wording the
 * foundation already approved for the site (registration with the NGO Board on
 * 23 May 2025, postal address, founder). Nothing is invented; facts can be changed
 * with the filter lof_core_schema_facts.
 *
 * Skipped completely when Rank Math or Yoast SEO is active (they output their own
 * graph). Shop products are WooCommerce products, and WooCommerce already prints
 * Product schema on product pages, so no Product node is added here.
 * Turn everything off with: add_filter( 'lof_core_schema_enabled', '__return_false' );
 *
 * @package LightOfFutureCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Whether an SEO plugin that prints its own schema is active.
 *
 * @return bool
 */
function lof_core_schema_seo_plugin() {
	return defined( 'RANK_MATH_VERSION' ) || defined( 'WPSEO_VERSION' );
}

/**
 * Verified organisation facts used in the graph.
 *
 * @return array<string,string>
 */
function lof_core_schema_facts() {
	$phone = preg_replace( '/[^0-9+]/', '', lof_core_setting( 'phone', '+254 716 385 485' ) );
	$facts = array(
		'name'            => 'Light of Future Foundation',
		'email'           => lof_core_setting( 'email', 'info@lightoffuturefoundation.org' ),
		'telephone'       => $phone ? $phone : '+254716385485',
		// Postal address as published on the About and Contact pages.
		'postOfficeBox'   => 'P.O. Box 53-00520',
		'streetAddress'   => 'Ruai, along Kangundo Road',
		'postalCode'      => '00520',
		'addressLocality' => 'Nairobi',
		'addressCountry'  => 'KE',
		// Registered with the NGO Board of Kenya on 23 May 2025 (site copy, About/FAQ).
		'foundingDate'    => '2025-05-23',
		'founder'         => 'Francis Ouma Sikuku',
		'facebook'        => lof_core_setting( 'social_facebook' ),
	);
	return (array) apply_filters( 'lof_core_schema_facts', $facts );
}

/**
 * Logo URL: the Custom Logo if one is uploaded, else the theme's brand logo.
 *
 * @return string
 */
function lof_core_schema_logo() {
	$logo_id = (int) get_theme_mod( 'custom_logo' );
	if ( $logo_id ) {
		$url = wp_get_attachment_image_url( $logo_id, 'full' );
		if ( $url ) {
			return $url;
		}
	}
	if ( function_exists( 'lof_brand_url' ) ) {
		return lof_brand_url( 'logo-horizontal-light.svg' );
	}
	return '';
}

/**
 * Donate page URL (setting donate_url, else /donate/).
 *
 * @return string
 */
function lof_core_schema_donate_url() {
	$url = lof_core_setting( 'donate_url' );
	return $url ? $url : home_url( '/donate/' );
}

/**
 * Is the current request the Donate page?
 *
 * @return bool
 */
function lof_core_schema_is_donate() {
	if ( is_page( 'donate' ) ) {
		return true;
	}
	if ( ! is_singular() ) {
		return false;
	}
	$here = untrailingslashit( (string) get_permalink() );
	return '' !== $here && untrailingslashit( lof_core_schema_donate_url() ) === $here;
}

/**
 * The NGO node.
 *
 * @return array
 */
function lof_core_schema_org() {
	$f   = lof_core_schema_facts();
	$org = array(
		'@type' => array( 'NGO', 'Organization' ),
		'@id'   => home_url( '/#organization' ),
		'name'  => $f['name'],
		'url'   => home_url( '/' ),
	);
	$logo = lof_core_schema_logo();
	if ( $logo ) {
		$org['logo'] = array(
			'@type' => 'ImageObject',
			'url'   => $logo,
		);
	}
	if ( ! empty( $f['email'] ) && is_email( $f['email'] ) ) {
		$org['email'] = $f['email'];
	}
	if ( ! empty( $f['telephone'] ) ) {
		$org['telephone'] = $f['telephone'];
	}
	if ( ! empty( $f['addressLocality'] ) ) {
		$address = array( '@type' => 'PostalAddress' );
		foreach ( array( 'postOfficeBoxNumber' => 'postOfficeBox', 'streetAddress' => 'streetAddress', 'postalCode' => 'postalCode', 'addressLocality' => 'addressLocality', 'addressCountry' => 'addressCountry' ) as $prop => $key ) {
			if ( ! empty( $f[ $key ] ) ) {
				$address[ $prop ] = $f[ $key ];
			}
		}
		$org['address'] = $address;
	}
	if ( ! empty( $f['foundingDate'] ) ) {
		$org['foundingDate'] = $f['foundingDate'];
	}
	if ( ! empty( $f['founder'] ) ) {
		$org['founder'] = array(
			'@type' => 'Person',
			'name'  => $f['founder'],
		);
	}
	if ( ! empty( $f['facebook'] ) ) {
		$org['sameAs'] = array( esc_url_raw( $f['facebook'] ) );
	}
	return $org;
}

/**
 * Article node for a single story.
 *
 * @param int $id Post ID.
 * @return array
 */
function lof_core_schema_article( $id ) {
	$article = array(
		'@type'            => 'Article',
		'@id'              => get_permalink( $id ) . '#article',
		'headline'         => wp_strip_all_tags( get_the_title( $id ) ),
		'datePublished'    => get_the_date( 'c', $id ),
		'dateModified'     => get_the_modified_date( 'c', $id ),
		'mainEntityOfPage' => get_permalink( $id ),
		'author'           => array( '@id' => home_url( '/#organization' ) ),
		'publisher'        => array( '@id' => home_url( '/#organization' ) ),
		'isPartOf'         => array( '@id' => home_url( '/#website' ) ),
	);
	$img = get_the_post_thumbnail_url( $id, 'full' );
	if ( $img ) {
		$article['image'] = $img;
	}
	return $article;
}

/**
 * Approved description of the foundation (home page intro, already on the site).
 *
 * @return string
 */
function lof_core_schema_description() {
	return (string) apply_filters( 'lof_core_schema_description', __( 'We support widows, widowers, orphans, vulnerable children and people living with disabilities through education, clean water and community programmes.', 'light-of-future-core' ) );
}

/**
 * Published programme titles (the focus areas shown on the site), for knowsAbout.
 *
 * @return string[]
 */
function lof_core_schema_programme_titles() {
	$ids = get_posts(
		array(
			'post_type'      => 'lof_programme',
			'post_status'    => 'publish',
			'posts_per_page' => 20,
			'orderby'        => 'menu_order title',
			'order'          => 'ASC',
			'fields'         => 'ids',
			'no_found_rows'  => true,
		)
	);
	$out = array();
	foreach ( $ids as $id ) {
		$out[] = wp_strip_all_tags( get_the_title( $id ) );
	}
	return array_values( array_filter( array_unique( $out ) ) );
}

/**
 * Every public profile URL saved in settings (sameAs).
 *
 * @return string[]
 */
function lof_core_schema_same_as() {
	$urls = array();
	foreach ( array( 'social_facebook', 'social_instagram', 'social_youtube', 'social_tiktok', 'social_x', 'social_linkedin', 'business_profile' ) as $key ) {
		$u = lof_core_setting( $key );
		if ( $u ) {
			$urls[] = esc_url_raw( $u );
		}
	}
	return array_values( array_unique( array_filter( $urls ) ) );
}

/**
 * URL of the current request (canonical where WordPress knows it).
 *
 * @return string
 */
function lof_core_schema_current_url() {
	if ( is_front_page() ) {
		return home_url( '/' );
	}
	if ( is_singular() ) {
		$c = wp_get_canonical_url( get_queried_object_id() );
		return $c ? $c : (string) get_permalink( get_queried_object_id() );
	}
	if ( is_post_type_archive() ) {
		$l = get_post_type_archive_link( (string) get_query_var( 'post_type' ) );
		return $l ? $l : '';
	}
	if ( is_tax() || is_category() || is_tag() ) {
		$l = get_term_link( get_queried_object() );
		return is_wp_error( $l ) ? '' : $l;
	}
	return '';
}

/**
 * WebPage type that matches the visible page.
 *
 * @return string
 */
function lof_core_schema_page_type() {
	if ( is_post_type_archive() || is_tax() || is_category() || is_tag() || is_page( array( 'programmes', 'stories', 'gallery', 'shop' ) ) ) {
		return 'CollectionPage';
	}
	if ( is_page( 'about-us' ) ) {
		return 'AboutPage';
	}
	if ( is_page( 'contact' ) ) {
		return 'ContactPage';
	}
	if ( is_page( 'faq' ) ) {
		return 'FAQPage';
	}
	return 'WebPage';
}

/**
 * Should our breadcrumb be printed? WooCommerce prints its own on shop pages.
 *
 * @return bool
 */
function lof_core_schema_wants_breadcrumb() {
	if ( is_front_page() ) {
		return false;
	}
	if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
		return false;
	}
	return is_singular() || is_post_type_archive();
}

/**
 * BreadcrumbList for the current page: Home > parent pages / section > page.
 *
 * @param string $url Current URL.
 * @return array|null
 */
function lof_core_schema_breadcrumb( $url ) {
	if ( '' === $url || ! lof_core_schema_wants_breadcrumb() ) {
		return null;
	}
	$crumbs = array( array( __( 'Home', 'light-of-future-core' ), home_url( '/' ) ) );
	if ( is_singular() ) {
		$post = get_queried_object();
		if ( ! ( $post instanceof WP_Post ) ) {
			return null;
		}
		if ( 'page' === $post->post_type ) {
			foreach ( array_reverse( get_post_ancestors( $post ) ) as $anc ) {
				$crumbs[] = array( wp_strip_all_tags( get_the_title( $anc ) ), get_permalink( $anc ) );
			}
		} elseif ( 'post' !== $post->post_type ) {
			$archive = get_post_type_archive_link( $post->post_type );
			$obj     = get_post_type_object( $post->post_type );
			if ( $archive && $obj ) {
				$crumbs[] = array( wp_strip_all_tags( $obj->labels->name ), $archive );
			}
		}
		$crumbs[] = array( wp_strip_all_tags( get_the_title( $post ) ), $url );
	} else {
		$obj      = get_post_type_object( (string) get_query_var( 'post_type' ) );
		$crumbs[] = array( $obj ? wp_strip_all_tags( $obj->labels->name ) : wp_strip_all_tags( wp_get_document_title() ), $url );
	}
	$items = array();
	foreach ( $crumbs as $i => $c ) {
		if ( '' === (string) $c[0] || '' === (string) $c[1] ) {
			continue;
		}
		$items[] = array(
			'@type'    => 'ListItem',
			'position' => count( $items ) + 1,
			'name'     => $c[0],
			'item'     => $c[1],
		);
	}
	if ( count( $items ) < 2 ) {
		return null;
	}
	return array(
		'@type'           => 'BreadcrumbList',
		'@id'             => $url . '#breadcrumb',
		'itemListElement' => $items,
	);
}

/**
 * Person nodes for approved leaders (Leadership page only).
 *
 * @return array[]
 */
function lof_core_schema_leaders() {
	$posts = get_posts(
		array(
			'post_type'      => 'lof_leader',
			'post_status'    => 'publish',
			'posts_per_page' => 30,
			'orderby'        => 'menu_order title',
			'order'          => 'ASC',
			'no_found_rows'  => true,
		)
	);
	$f     = lof_core_schema_facts();
	$nodes = array();
	foreach ( $posts as $p ) {
		if ( ! get_post_meta( $p->ID, 'lof_approved', true ) ) {
			continue;
		}
		$name = wp_strip_all_tags( get_the_title( $p ) );
		if ( '' === $name ) {
			continue;
		}
		$is_founder = ! empty( $f['founder'] ) && 0 === strcasecmp( $name, $f['founder'] );
		$node       = array(
			'@type'    => 'Person',
			'@id'      => $is_founder ? home_url( '/#founder' ) : home_url( '/leadership/#person-' . $p->post_name ),
			'name'     => $name,
			'worksFor' => array( '@id' => home_url( '/#organization' ) ),
		);
		$role = trim( (string) get_post_meta( $p->ID, 'lof_role', true ) );
		if ( '' !== $role ) {
			$node['jobTitle'] = $role;
		}
		$img = get_the_post_thumbnail_url( $p->ID, 'large' );
		if ( $img ) {
			$node['image'] = $img;
		}
		$nodes[] = $node;
	}
	return $nodes;
}

/**
 * FAQ items collected while the visible FAQ widget renders (theme calls this).
 *
 * @param string|null $question Question as shown.
 * @param string|null $answer   Answer as shown.
 * @return array
 */
function lof_core_schema_faq_add( $question = null, $answer = null ) {
	static $items = array();
	if ( null !== $question && null !== $answer ) {
		$q = trim( wp_strip_all_tags( (string) $question ) );
		$a = trim( wp_strip_all_tags( (string) $answer ) );
		if ( '' !== $q && '' !== $a && count( $items ) < 50 ) {
			$items[ md5( $q ) ] = array( $q, $a );
		}
	}
	return $items;
}

/**
 * Print a JSON-LD script.
 *
 * @param array  $data Data.
 * @param string $id   Element id.
 */
function lof_core_schema_print( $data, $id ) {
	$json = wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
	if ( ! $json ) {
		return;
	}
	$json = str_ireplace( '</', '<\/', $json );
	echo '<script type="application/ld+json" id="' . esc_attr( $id ) . '">' . $json . '</script>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON encoded above.
}

/**
 * FAQPage questions, printed in the footer after the visible FAQ has rendered.
 * Only the questions and answers actually shown on the page are used.
 */
function lof_core_schema_faq_output() {
	if ( is_admin() || lof_core_schema_seo_plugin() || ! apply_filters( 'lof_core_schema_enabled', true ) ) {
		return;
	}
	$items = lof_core_schema_faq_add();
	$url   = lof_core_schema_current_url();
	if ( ! $items || '' === $url ) {
		return;
	}
	$main = array();
	foreach ( $items as $qa ) {
		$main[] = array(
			'@type'          => 'Question',
			'name'           => $qa[0],
			'acceptedAnswer' => array(
				'@type' => 'Answer',
				'text'  => $qa[1],
			),
		);
	}
	lof_core_schema_print(
		array(
			'@context'   => 'https://schema.org',
			'@type'      => 'FAQPage',
			'@id'        => $url . '#faq',
			'url'        => $url,
			'isPartOf'   => array( '@id' => home_url( '/#website' ) ),
			'about'      => array( '@id' => home_url( '/#organization' ) ),
			'mainEntity' => $main,
		),
		'lof-schema-faq'
	);
}
add_action( 'wp_footer', 'lof_core_schema_faq_output', 40 );

/**
 * Print the JSON-LD graph.
 */
function lof_core_schema_output() {
	if ( is_admin() || is_feed() || is_404() || ! apply_filters( 'lof_core_schema_enabled', true ) ) {
		return;
	}
	$graph = array();
	if ( ! lof_core_schema_seo_plugin() ) {
		$org = lof_core_schema_org();
		if ( lof_core_schema_is_donate() ) {
			$org['potentialAction'] = array(
				'@type'     => 'DonateAction',
				'name'      => __( 'Donate to Light of Future Foundation', 'light-of-future-core' ),
				'target'    => lof_core_schema_donate_url(),
				'recipient' => array( '@id' => home_url( '/#organization' ) ),
			);
		}
		// 0.47.0: richer, connected organisation entity (only facts already on the site).
		$org_id = home_url( '/#organization' );
		if ( isset( $org['logo'] ) ) {
			$org['logo']['@id']     = home_url( '/#logo' );
			$org['logo']['caption'] = $org['name'];
			$org['image']           = array( '@id' => home_url( '/#logo' ) );
		}
		$org['legalName']   = $org['name'];
		$org['description'] = lof_core_schema_description();
		$org['areaServed']  = array(
			'@type'            => 'City',
			'name'             => 'Nairobi',
			'containedInPlace' => array(
				'@type' => 'Country',
				'name'  => 'Kenya',
			),
		);
		$knows = lof_core_schema_programme_titles();
		if ( $knows ) {
			$org['knowsAbout'] = $knows;
		}
		$contact = array(
			'@type'             => 'ContactPoint',
			'contactType'       => 'general enquiries',
			'areaServed'        => 'KE',
			'availableLanguage' => array( 'English', 'Swahili' ),
		);
		if ( ! empty( $org['telephone'] ) ) {
			$contact['telephone'] = $org['telephone'];
		}
		if ( ! empty( $org['email'] ) ) {
			$contact['email'] = $org['email'];
		}
		$org['contactPoint'] = $contact;
		$same                = lof_core_schema_same_as();
		if ( $same ) {
			$org['sameAs'] = $same;
		}
		$f = lof_core_schema_facts();
		if ( ! empty( $f['founder'] ) ) {
			$org['founder'] = array( '@id' => home_url( '/#founder' ) );
		}
		$graph[] = $org;
		$graph[] = array(
			'@type'      => 'WebSite',
			'@id'        => home_url( '/#website' ),
			'url'        => home_url( '/' ),
			'name'       => $org['name'],
			'description' => lof_core_schema_description(),
			'publisher'  => array( '@id' => $org_id ),
			'inLanguage' => get_bloginfo( 'language' ),
		);
		$leaders      = is_page( 'leadership' ) ? lof_core_schema_leaders() : array();
		$founder_seen = false;
		foreach ( $leaders as $l ) {
			if ( home_url( '/#founder' ) === $l['@id'] ) {
				$founder_seen = true;
			}
		}
		if ( ! empty( $f['founder'] ) && ! $founder_seen ) {
			$graph[] = array(
				'@type'    => 'Person',
				'@id'      => home_url( '/#founder' ),
				'name'     => $f['founder'],
				'jobTitle' => __( 'Founder', 'light-of-future-core' ),
				'worksFor' => array( '@id' => $org_id ),
			);
		}
		foreach ( $leaders as $l ) {
			$graph[] = $l;
		}

		// The page itself.
		$url = lof_core_schema_current_url();
		if ( '' !== $url ) {
			$page = array(
				'@type'      => lof_core_schema_page_type(),
				'@id'        => $url . '#webpage',
				'url'        => $url,
				'name'       => wp_strip_all_tags( html_entity_decode( wp_get_document_title(), ENT_QUOTES, 'UTF-8' ) ),
				'isPartOf'   => array( '@id' => home_url( '/#website' ) ),
				'inLanguage' => get_bloginfo( 'language' ),
			);
			if ( 'FAQPage' === $page['@type'] ) {
				$page['@type'] = 'WebPage'; // The FAQPage node with the visible questions is printed in the footer.
			}
			if ( function_exists( 'lof_seo_description' ) ) {
				$desc = (string) lof_seo_description();
				if ( '' !== $desc ) {
					$page['description'] = $desc;
				}
			}
			if ( is_front_page() || is_page( array( 'about-us', 'contact', 'leadership', 'donate' ) ) ) {
				$page['about'] = array( '@id' => $org_id );
			}
			if ( is_singular() ) {
				$qid                   = (int) get_queried_object_id();
				$page['datePublished'] = get_the_date( 'c', $qid );
				$page['dateModified']  = get_the_modified_date( 'c', $qid );
				$thumb                 = get_the_post_thumbnail_url( $qid, 'full' );
				if ( $thumb ) {
					$page['primaryImageOfPage'] = array(
						'@type' => 'ImageObject',
						'url'   => $thumb,
					);
				}
				if ( is_singular( 'lof_programme' ) ) {
					$about = array(
						'@type' => 'Thing',
						'name'  => wp_strip_all_tags( get_the_title( $qid ) ),
					);
					if ( has_excerpt( $qid ) ) {
						$about['description'] = wp_strip_all_tags( get_the_excerpt( $qid ) );
					}
					$page['about']    = $about;
					$page['mentions'] = array( '@id' => $org_id );
				}
			}
			$crumb = lof_core_schema_breadcrumb( $url );
			if ( $crumb ) {
				$page['breadcrumb'] = array( '@id' => $crumb['@id'] );
			}
			$graph[] = $page;
			if ( $crumb ) {
				$graph[] = $crumb;
			}
		}
		if ( is_singular( 'lof_story' ) ) {
			$article = lof_core_schema_article( (int) get_queried_object_id() );
			if ( '' !== $url ) {
				$article['mainEntityOfPage'] = array( '@id' => $url . '#webpage' );
			}
			$article['inLanguage'] = get_bloginfo( 'language' );
			$graph[]               = $article;
		}
	}
	$graph = (array) apply_filters( 'lof_core_schema_graph', $graph );
	if ( ! $graph ) {
		return;
	}
	$json = wp_json_encode(
		array(
			'@context' => 'https://schema.org',
			'@graph'   => array_values( $graph ),
		),
		JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
	);
	if ( ! $json ) {
		return;
	}
	// With unescaped slashes a "</script" inside a title could close the tag early.
	$json = str_ireplace( '</', '<\/', $json );
	echo '<script type="application/ld+json" id="lof-schema">' . $json . '</script>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON encoded above.
}
add_action( 'wp_head', 'lof_core_schema_output', 30 );
