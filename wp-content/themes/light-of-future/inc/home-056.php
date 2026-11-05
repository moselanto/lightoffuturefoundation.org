<?php
/**
 * 0.56.0 homepage: shorter and without repeats.
 * - "Why we exist" and "Our story" become one short "Who we are" block.
 * - The closing "Be the light" banner is removed (Give today stays).
 * - Phones: 2x2 approach steps, 6 focus areas, 2 stories, 2 products.
 * Applied at render time, so the Elementor page data is untouched.
 *
 * @package LightOfFuture
 */

defined( 'ABSPATH' ) || exit;

/**
 * Split HTML into top-level <section> blocks.
 *
 * @param string $html HTML.
 * @return array[] List of array( start, end ) byte offsets.
 */
function lof_top_sections( $html ) {
	$out   = array();
	$depth = 0;
	$start = 0;
	if ( ! preg_match_all( '#<section\b|</section>#i', $html, $m, PREG_OFFSET_CAPTURE ) ) {
		return $out;
	}
	foreach ( $m[0] as $tag ) {
		if ( '<' === $tag[0][0] && '/' !== $tag[0][1] ) {
			if ( 0 === $depth ) {
				$start = $tag[1];
			}
			$depth++;
		} else {
			$depth--;
			if ( 0 === $depth ) {
				$out[] = array( $start, $tag[1] + 10 );
			}
			if ( $depth < 0 ) {
				$depth = 0;
			}
		}
	}
	return $out;
}

/**
 * The "Who we are" block (facts from the site's own Our story section).
 *
 * @return string
 */
function lof_who_we_are() {
	$img = LOF_THEME_URI . '/assets/images/photos/founder-francis-ouma-sikuku.webp';
	ob_start();
	?>
	<section class="lof-section lof-tone-white lof-who" aria-labelledby="lof-who-title">
		<div class="lof-container lof-who__grid">
			<div class="lof-who__text lof-reveal">
				<p class="lof-eyebrow"><?php esc_html_e( 'Who we are', 'light-of-future' ); ?></p>
				<h2 class="lof-section__title" id="lof-who-title"><?php esc_html_e( 'Hope should open doors.', 'light-of-future' ); ?></h2>
				<p class="lof-who__lead"><?php esc_html_e( 'We believe every widow, orphan and vulnerable family deserves the dignity, skills and opportunity to shape a better future.', 'light-of-future' ); ?></p>
				<p><?php esc_html_e( 'Our founder, Francis Ouma Sikuku, began serving widows in his village at the age of 20. In 2021 he brought together a team of 10, and Light of Future Foundation was registered with the NGO Board on 23 May 2025.', 'light-of-future' ); ?></p>
				<ul class="lof-who__facts">
					<li><strong>20</strong><span><?php esc_html_e( 'Age he began serving', 'light-of-future' ); ?></span></li>
					<li><strong>2021</strong><span><?php esc_html_e( 'Team of 10 formed', 'light-of-future' ); ?></span></li>
					<li><strong>2025</strong><span><?php esc_html_e( 'Registered NGO', 'light-of-future' ); ?></span></li>
				</ul>
				<a class="lof-btn lof-btn--earth" href="<?php echo esc_url( home_url( '/about-us/' ) ); ?>"><?php esc_html_e( 'Read our story', 'light-of-future' ); ?></a>
			</div>
			<figure class="lof-who__photo lof-reveal"><img src="<?php echo esc_url( $img ); ?>" alt="<?php esc_attr_e( 'Francis Ouma Sikuku, founder of Light of Future Foundation', 'light-of-future' ); ?>" width="600" height="750" loading="lazy" decoding="async"></figure>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}

/**
 * Rebuild the homepage content.
 *
 * @param string $content Content.
 * @return string
 */
function lof_home_trim( $content ) {
	if ( is_admin() || ! is_front_page() || ! apply_filters( 'lof_home_trim', true ) || false !== strpos( $content, 'lof-who' ) ) {
		return $content;
	}
	$secs = lof_top_sections( $content );
	if ( ! $secs ) {
		return $content;
	}
	$out  = '';
	$prev = 0;
	foreach ( $secs as $s ) {
		$block = substr( $content, $s[0], $s[1] - $s[0] );
		$out  .= substr( $content, $prev, $s[0] - $prev );
		$prev  = $s[1];
		if ( false !== strpos( $block, 'lof-intro' ) ) {
			$out .= lof_who_we_are();
			continue;
		}
		if ( false !== strpos( $block, 'lof-feature--story' ) || false !== strpos( $block, 'lof-cta' ) ) {
			continue;
		}
		if ( false && false !== strpos( $block, 'lof-card--programme' ) ) {
			$link  = '<div class="lof-btn-row lof-btn-row--center lof-home-allprog"><a class="lof-btn lof-btn--earth" href="' . esc_url( home_url( '/programmes/' ) ) . '">' . esc_html__( 'See all focus areas', 'light-of-future' ) . '</a></div>';
			$pos   = strrpos( $block, '</div>' );
			$cut   = strrpos( substr( $block, 0, (int) strrpos( $block, '</section>' ) ), '</div>' );
			$block = false !== $cut ? substr( $block, 0, $cut ) . $link . substr( $block, $cut ) : $block;
		}
		$out .= $block;
	}
	$out .= substr( $content, $prev );
	return $out;
}
add_filter( 'the_content', 'lof_home_trim', 98 );
