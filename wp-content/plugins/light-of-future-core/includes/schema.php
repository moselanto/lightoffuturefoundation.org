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
		$graph[] = $org;
		$graph[] = array(
			'@type'     => 'WebSite',
			'@id'       => home_url( '/#website' ),
			'url'       => home_url( '/' ),
			'name'      => $org['name'],
			'publisher' => array( '@id' => home_url( '/#organization' ) ),
			'inLanguage' => get_bloginfo( 'language' ),
		);
		if ( is_singular( 'lof_story' ) ) {
			$graph[] = lof_core_schema_article( (int) get_queried_object_id() );
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
