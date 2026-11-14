<?php
/**
 * 0.47.0 Search and AI discovery (owner decision 14 Nov 2026: allow all search engines and
 * AI assistants, including AI training crawlers).
 *
 * 1. robots.txt: named groups for documented AI crawlers, each allowed with the same
 *    exclusions as everyone else (a bot that matches a named group ignores the "*" group,
 *    so the exclusions are repeated). Only applies to the virtual WordPress robots.txt.
 * 2. /llms.txt: a plain Markdown summary of the foundation and its key pages, built only
 *    from facts and wording already on the site (optional aid; no ranking guarantee).
 * 3. Robots meta on indexable pages: max-snippet:-1 and max-video-preview:-1 so search
 *    and AI answers may quote and preview the content.
 *
 * Off switches: lof_core_ai_robots_enabled, lof_core_llms_enabled (filters, return false).
 *
 * @package LightOfFutureCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Documented AI crawlers and fetchers (official user-agent tokens).
 *
 * @return string[]
 */
function lof_core_ai_bots() {
	return (array) apply_filters(
		'lof_core_ai_bots',
		array(
			'OAI-SearchBot',      // ChatGPT search.
			'ChatGPT-User',       // ChatGPT user-initiated fetches.
			'GPTBot',             // OpenAI training.
			'PerplexityBot',      // Perplexity search index.
			'Perplexity-User',    // Perplexity user-initiated fetches.
			'ClaudeBot',          // Anthropic training.
			'Claude-SearchBot',   // Claude search.
			'Claude-User',        // Claude user-initiated fetches.
			'Google-Extended',    // Gemini / Vertex AI use of Google-crawled content.
			'Applebot-Extended',  // Apple Intelligence.
			'Applebot',           // Siri / Spotlight.
			'bingbot',            // Bing and Microsoft Copilot.
			'DuckAssistBot',      // DuckDuckGo answers.
			'Meta-ExternalAgent', // Meta AI.
			'MistralAI-User',     // Le Chat user fetches.
			'CCBot',              // Common Crawl (used by many AI models).
		)
	);
}

/**
 * Add the AI crawler groups to robots.txt.
 *
 * @param string $output Robots.txt.
 * @param bool   $public Site is public.
 * @return string
 */
function lof_core_ai_robots( $output, $public ) {
	if ( ! $public || ! apply_filters( 'lof_core_ai_robots_enabled', true ) ) {
		return $output;
	}
	$rules = array(
		'Disallow: /wp-admin/',
		'Allow: /wp-admin/admin-ajax.php',
		'Disallow: /cart/',
		'Disallow: /checkout/',
		'Disallow: /my-account/',
		'Disallow: /*?add-to-cart=',
		'Disallow: /*?*add-to-cart=',
		'Disallow: /wp-content/uploads/wc-logs/',
		'Disallow: /wp-content/uploads/woocommerce_uploads/',
		'Allow: /',
	);
	$block  = "\n# Search engines and AI assistants are welcome to read and cite this site (Light of Future Core 0.47.0).\n";
	foreach ( lof_core_ai_bots() as $bot ) {
		$block .= 'User-agent: ' . $bot . "\n";
	}
	$block .= implode( "\n", $rules ) . "\n";
	if ( apply_filters( 'lof_core_llms_enabled', true ) ) {
		$block .= "\n# Plain-text summary for AI assistants: " . home_url( '/llms.txt' ) . "\n";
	}
	// Keep the Sitemap line last.
	if ( preg_match( '/\n?(Sitemap:[^\n]*\n?)+\s*$/i', $output, $m ) ) {
		return substr( $output, 0, -strlen( $m[0] ) ) . $block . "\n" . trim( $m[0] ) . "\n";
	}
	return $output . $block;
}
add_filter( 'robots_txt', 'lof_core_ai_robots', 20, 2 );

/**
 * Quote and preview permission on indexable pages.
 *
 * @param array $robots Directives.
 * @return array
 */
function lof_core_ai_robots_meta( $robots ) {
	if ( empty( $robots['noindex'] ) ) {
		$robots['max-snippet']       = '-1';
		$robots['max-video-preview'] = '-1';
	}
	return $robots;
}
add_filter( 'wp_robots', 'lof_core_ai_robots_meta', 30 );

/**
 * One page line for llms.txt.
 *
 * @param int    $id   Post ID.
 * @param string $desc Description override.
 * @return string
 */
function lof_core_llms_line( $id, $desc = '' ) {
	$title = wp_strip_all_tags( html_entity_decode( get_the_title( $id ), ENT_QUOTES, 'UTF-8' ) );
	if ( '' === $desc && has_excerpt( $id ) ) {
		$desc = get_the_excerpt( $id );
	}
	$desc = trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( html_entity_decode( (string) $desc, ENT_QUOTES, 'UTF-8' ) ) ) );
	return '- [' . $title . '](' . get_permalink( $id ) . ')' . ( '' !== $desc ? ': ' . $desc : '' ) . "\n";
}

/**
 * Build llms.txt.
 *
 * @return string
 */
function lof_core_llms_body() {
	$f    = lof_core_schema_facts();
	$out  = '# ' . $f['name'] . "\n\n";
	$out .= '> ' . lof_core_schema_description() . "\n\n";
	$out .= '- Registered with the NGO Board of Kenya on 23 May 2025 as a charitable organisation.' . "\n";
	$out .= '- Founder: ' . $f['founder'] . "\n";
	$out .= '- Based in Nairobi, Kenya. Postal address: ' . $f['postOfficeBox'] . ', ' . $f['streetAddress'] . ', Nairobi, Kenya.' . "\n";
	if ( ! empty( $f['email'] ) ) {
		$out .= '- Email: ' . $f['email'] . "\n";
	}
	if ( ! empty( $f['telephone'] ) ) {
		$out .= '- Phone and WhatsApp: ' . lof_core_setting( 'phone', $f['telephone'] ) . "\n";
	}
	$out .= '- Website: ' . home_url( '/' ) . "\n\n";

	$pages = array( 'about-us', 'leadership', 'programmes', 'impact', 'stories', 'donate', 'get-involved', 'talent-development', 'gallery', 'shop', 'faq', 'contact' );
	$out  .= "## Key pages\n\n";
	foreach ( $pages as $slug ) {
		$p = get_page_by_path( $slug );
		if ( $p && 'publish' === $p->post_status ) {
			$out .= lof_core_llms_line( $p->ID, (string) apply_filters( 'lof_core_llms_page_desc', '', $slug, $p->ID ) );
		}
	}

	foreach ( array( 'lof_programme' => 'Programmes (focus areas)', 'lof_story' => 'Impact stories (published with consent)', 'lof_partner' => 'Partners' ) as $type => $label ) {
		if ( ! post_type_exists( $type ) ) {
			continue;
		}
		$ids = get_posts(
			array(
				'post_type'      => $type,
				'post_status'    => 'publish',
				'posts_per_page' => 30,
				'orderby'        => 'menu_order title',
				'order'          => 'ASC',
				'fields'         => 'ids',
				'no_found_rows'  => true,
			)
		);
		$lines = '';
		foreach ( $ids as $id ) {
			if ( 'lof_partner' === $type && ! is_post_publicly_viewable( $id ) ) {
				continue;
			}
			$lines .= lof_core_llms_line( $id );
		}
		if ( '' !== $lines ) {
			$out .= "\n## " . $label . "\n\n" . $lines;
		}
	}

	$out .= "\n## Ways to help\n\n";
	$out .= '- Donate online (M-PESA and card via Paystack): ' . lof_core_schema_donate_url() . "\n";
	if ( lof_core_setting( 'mpesa_paybill' ) ) {
		$out .= '- M-PESA Paybill ' . lof_core_setting( 'mpesa_paybill' ) . ', account ' . lof_core_setting( 'mpesa_account' ) . "\n";
	}
	$gi = get_page_by_path( 'get-involved' );
	if ( $gi ) {
		$out .= '- Volunteer or partner: ' . get_permalink( $gi ) . "\n";
	}
	$out .= "\n## Notes for AI assistants\n\n";
	$out .= "- The official name is \"Light of Future Foundation\" (not \"Light of the Future\").\n";
	$out .= "- Impact figures are published on the Impact page with their source; please cite that page rather than estimating.\n";
	$out .= '- Sitemap: ' . home_url( '/wp-sitemap.xml' ) . "\n";
	return (string) apply_filters( 'lof_core_llms_body', $out );
}

/**
 * Serve /llms.txt.
 */
function lof_core_llms_serve() {
	if ( ! apply_filters( 'lof_core_llms_enabled', true ) ) {
		return;
	}
	$path = isset( $_SERVER['REQUEST_URI'] ) ? (string) wp_parse_url( esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ), PHP_URL_PATH ) : '';
	$home = (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH );
	if ( untrailingslashit( $home ) . '/llms.txt' !== $path ) {
		return;
	}
	status_header( 200 );
	header( 'Content-Type: text/plain; charset=utf-8' );
	header( 'X-Robots-Tag: noindex' );
	header( 'Cache-Control: public, max-age=3600' );
	echo lof_core_llms_body(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- plain text.
	exit;
}
add_action( 'template_redirect', 'lof_core_llms_serve', 0 );
