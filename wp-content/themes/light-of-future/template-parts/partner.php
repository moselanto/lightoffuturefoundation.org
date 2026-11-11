<?php
/**
 * Partner page layout (0.71.0, redesigned 0.71.1): photo hero with logo card,
 * partnership story with key facts, impact cards with icons, supported programme,
 * other partners and a partner-with-us band.
 *
 * @package LightOfFuture
 */

defined( 'ABSPATH' ) || exit;

/**
 * Small line icons for the impact cards.
 *
 * @param int $i Index.
 * @return string
 */
function lof_partner_icon( $i ) {
	$paths = array(
		'<path d="M3 9l9-5 9 5-9 5-9-5z"/><path d="M7 11.5V16c0 1.4 2.2 3 5 3s5-1.6 5-3v-4.5"/><path d="M21 9v5"/>',
		'<path d="M12 21s-7-4.4-7-10.2A4.3 4.3 0 0 1 12 8a4.3 4.3 0 0 1 7 2.8C19 16.6 12 21 12 21z"/>',
		'<path d="M4 8l8-4 8 4v8l-8 4-8-4V8z"/><path d="M4 8l8 4 8-4"/><path d="M12 12v8"/>',
		'<path d="M5 12l4 4 10-10"/>',
	);
	$p = $paths[ $i % count( $paths ) ];
	return '<svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $p . '</svg>';
}

while ( have_posts() ) :
	the_post();
	$lof_pid     = get_the_ID();
	$lof_tagline = (string) get_post_meta( $lof_pid, 'lof_tagline', true );
	$lof_status  = (string) get_post_meta( $lof_pid, 'lof_status_label', true );
	$lof_focus   = (string) get_post_meta( $lof_pid, 'lof_focus', true );
	$lof_impacts = array_values( array_filter( array_map( 'trim', explode( "\n", (string) get_post_meta( $lof_pid, 'lof_impacts', true ) ) ) ) );
	$lof_slug    = (string) get_post_meta( $lof_pid, 'lof_programme', true );
	$lof_prog    = '' !== $lof_slug ? get_page_by_path( $lof_slug, OBJECT, 'lof_programme' ) : null;
	$lof_prog    = ( $lof_prog && 'publish' === $lof_prog->post_status ) ? $lof_prog : null;
	$lof_site    = (string) get_post_meta( $lof_pid, 'lof_url', true );
	$lof_all     = home_url( '/#our-partners' );
	$lof_bg      = ( $lof_prog && has_post_thumbnail( $lof_prog ) ) ? (int) get_post_thumbnail_id( $lof_prog ) : 0;
	$lof_others  = get_posts(
		array(
			'post_type'      => 'lof_partner',
			'post_status'    => 'publish',
			'posts_per_page' => 12,
			'post__not_in'   => array( $lof_pid ),
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'meta_key'       => 'lof_approved', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'meta_value'     => '1', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
		)
	);
	$lof_prog_text = $lof_prog && has_excerpt( $lof_prog ) ? (string) get_the_excerpt( $lof_prog ) : '';
	?>
	<main id="content" <?php post_class( 'lof-single lof-partner' ); ?>>
		<section class="lof-hero lof-hero--page lof-partner__hero<?php echo $lof_bg ? ' has-bg' : ''; ?>">
			<?php if ( $lof_bg ) : ?>
				<div class="lof-hero__bg" aria-hidden="true"><?php echo wp_get_attachment_image( $lof_bg, 'full', false, array( 'class' => 'lof-hero__bg-img', 'alt' => '', 'loading' => 'eager', 'decoding' => 'async', 'fetchpriority' => 'high', 'sizes' => '100vw' ) ); ?></div>
			<?php endif; ?>
			<div class="lof-container lof-hero__inner lof-partner__heroin">
				<div class="lof-hero__content">
					<nav class="lof-crumbs lof-crumbs--single" aria-label="<?php esc_attr_e( 'Breadcrumb', 'light-of-future' ); ?>"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'light-of-future' ); ?></a><span aria-hidden="true">/</span><a href="<?php echo esc_url( $lof_all ); ?>"><?php esc_html_e( 'Our partners', 'light-of-future' ); ?></a></nav>
					<p class="lof-partner__pill"><span class="lof-partner__dot" aria-hidden="true"></span><?php echo esc_html( '' !== $lof_status ? $lof_status : __( 'Our partner', 'light-of-future' ) ); ?><?php echo '' !== $lof_focus ? ' &middot; ' . esc_html( $lof_focus ) : ''; ?></p>
					<h1 class="lof-hero__title"><?php the_title(); ?></h1>
					<?php if ( '' !== $lof_tagline ) : ?>
						<p class="lof-hero__text"><?php echo esc_html( $lof_tagline ); ?></p>
					<?php endif; ?>
					<div class="lof-partner__herobtns">
						<?php if ( $lof_prog ) : ?>
							<a class="lof-btn lof-btn--sun" href="<?php echo esc_url( get_permalink( $lof_prog ) ); ?>"><?php esc_html_e( 'See the programme', 'light-of-future' ); ?></a>
						<?php endif; ?>
						<a class="lof-btn lof-partner__ghost" href="<?php echo esc_url( home_url( '/get-involved/' ) ); ?>"><?php esc_html_e( 'Become a partner', 'light-of-future' ); ?></a>
					</div>
				</div>
				<figure class="lof-partner__herologo">
					<?php
					if ( has_post_thumbnail() ) {
						the_post_thumbnail( 'medium', array( 'alt' => sprintf( /* translators: %s: partner name. */ __( '%s logo', 'light-of-future' ), get_the_title() ), 'loading' => 'eager' ) );
					} else {
						echo '<span class="lof-partner__mono" aria-hidden="true">' . esc_html( mb_substr( get_the_title(), 0, 1 ) ) . '</span>';
					}
					?>
				</figure>
			</div>
		</section>

		<div class="lof-section lof-tone-white lof-partner__story">
			<div class="lof-container lof-partner__grid">
				<article class="lof-partner__copy">
					<p class="lof-eyebrow"><?php esc_html_e( 'Our partnership', 'light-of-future' ); ?></p>
					<h2 class="lof-partner__h2">
						<?php
						/* translators: %s: partner name. */
						echo esc_html( sprintf( __( 'Working together with %s', 'light-of-future' ), get_the_title() ) );
						?>
					</h2>
					<div class="lof-prose"><?php the_content(); ?></div>
					<?php if ( '' !== $lof_site ) : ?>
						<p><a class="lof-btn lof-btn--earth" href="<?php echo esc_url( $lof_site ); ?>" rel="noopener" target="_blank"><?php esc_html_e( 'Visit their website', 'light-of-future' ); ?></a></p>
					<?php endif; ?>
				</article>
				<aside class="lof-partner__facts" aria-label="<?php esc_attr_e( 'Partnership at a glance', 'light-of-future' ); ?>">
					<p class="lof-partner__factstitle"><?php esc_html_e( 'At a glance', 'light-of-future' ); ?></p>
					<dl>
						<div><dt><?php esc_html_e( 'Partner', 'light-of-future' ); ?></dt><dd><?php the_title(); ?></dd></div>
						<?php if ( '' !== $lof_status ) : ?>
							<div><dt><?php esc_html_e( 'Partnership', 'light-of-future' ); ?></dt><dd><?php echo esc_html( $lof_status ); ?></dd></div>
						<?php endif; ?>
						<?php if ( '' !== $lof_focus ) : ?>
							<div><dt><?php esc_html_e( 'Supports', 'light-of-future' ); ?></dt><dd><?php echo esc_html( $lof_focus ); ?></dd></div>
						<?php endif; ?>
						<?php if ( $lof_prog ) : ?>
							<div><dt><?php esc_html_e( 'Programme', 'light-of-future' ); ?></dt><dd><a href="<?php echo esc_url( get_permalink( $lof_prog ) ); ?>"><?php echo esc_html( get_the_title( $lof_prog ) ); ?></a></dd></div>
						<?php endif; ?>
					</dl>
					<a class="lof-btn lof-btn--sun lof-partner__factsbtn" href="<?php echo esc_url( lof_donate_url() ); ?>"><?php esc_html_e( 'Donate', 'light-of-future' ); ?></a>
				</aside>
			</div>
		</div>

		<?php if ( $lof_impacts ) : ?>
			<div class="lof-section lof-partner__impact">
				<div class="lof-container">
					<div class="lof-partner__head">
						<p class="lof-eyebrow"><?php esc_html_e( 'Impact', 'light-of-future' ); ?></p>
						<h2 class="lof-partner__h2"><?php esc_html_e( 'The difference this partnership makes', 'light-of-future' ); ?></h2>
					</div>
					<ol class="lof-partner__impacts">
						<?php foreach ( $lof_impacts as $lof_i => $lof_line ) : ?>
							<li>
								<span class="lof-partner__ico"><?php echo lof_partner_icon( $lof_i ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></span>
								<span class="lof-partner__n" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $lof_i + 1 ) ); ?></span>
								<p><?php echo esc_html( $lof_line ); ?></p>
							</li>
						<?php endforeach; ?>
					</ol>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( $lof_prog ) : ?>
			<div class="lof-section lof-tone-white lof-partner__prog">
				<div class="lof-container">
					<a class="lof-partner__progcard<?php echo has_post_thumbnail( $lof_prog ) ? ' has-img' : ''; ?>" href="<?php echo esc_url( get_permalink( $lof_prog ) ); ?>">
						<?php if ( has_post_thumbnail( $lof_prog ) ) : ?>
							<span class="lof-partner__progimg"><?php echo get_the_post_thumbnail( $lof_prog, 'large', array( 'loading' => 'lazy', 'alt' => '' ) ); ?></span>
						<?php endif; ?>
						<span class="lof-partner__progtext">
							<span class="lof-eyebrow"><?php esc_html_e( 'The programme they support', 'light-of-future' ); ?></span>
							<strong><?php echo esc_html( get_the_title( $lof_prog ) ); ?></strong>
							<?php if ( '' !== $lof_prog_text ) : ?>
								<span class="lof-partner__proglead"><?php echo esc_html( wp_strip_all_tags( $lof_prog_text ) ); ?></span>
							<?php endif; ?>
							<span class="lof-partner__proglink"><?php esc_html_e( 'See the programme', 'light-of-future' ); ?> <span aria-hidden="true">&rarr;</span></span>
						</span>
					</a>
				</div>
			</div>
		<?php endif; ?>

		<div class="lof-section lof-partner__more">
			<div class="lof-container">
				<?php if ( $lof_others ) : ?>
					<h2 class="lof-partner__subtitle"><?php esc_html_e( 'Our other partners', 'light-of-future' ); ?></h2>
					<ul class="lof-partner__others">
						<?php
						foreach ( $lof_others as $lof_o ) {
							$lof_ourl  = lof_partner_page_url( $lof_o );
							$lof_ologo = has_post_thumbnail( $lof_o ) ? get_the_post_thumbnail( $lof_o, 'medium', array( 'loading' => 'lazy', 'alt' => '' ) ) : '';
							$lof_inner = '<span class="lof-partner__ologo">' . $lof_ologo . '</span><span class="lof-partner__oname">' . esc_html( get_the_title( $lof_o ) ) . '</span>';
							if ( '' !== $lof_ourl ) {
								$lof_inner .= '<span class="lof-partner__omore">' . esc_html__( 'Read about our partnership', 'light-of-future' ) . ' <span aria-hidden="true">&rarr;</span></span>';
							}
							echo '<li>' . ( '' !== $lof_ourl ? '<a href="' . esc_url( $lof_ourl ) . '">' . $lof_inner . '</a>' : '<div>' . $lof_inner . '</div>' ) . '</li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core image markup and escaped text.
						}
						?>
					</ul>
				<?php endif; ?>
				<div class="lof-partner__cta">
					<div>
						<h2><?php esc_html_e( 'Partner with us', 'light-of-future' ); ?></h2>
						<p><?php esc_html_e( 'Organisations, churches and businesses can partner with us on programmes.', 'light-of-future' ); ?></p>
					</div>
					<div class="lof-partner__ctabtns">
						<a class="lof-btn lof-btn--sun" href="<?php echo esc_url( home_url( '/get-involved/' ) ); ?>"><?php esc_html_e( 'Become a partner', 'light-of-future' ); ?></a>
						<a class="lof-btn lof-partner__ghost" href="<?php echo esc_url( $lof_all ); ?>"><?php esc_html_e( 'All our partners', 'light-of-future' ); ?></a>
					</div>
				</div>
			</div>
		</div>
	</main>
	<?php
endwhile;
