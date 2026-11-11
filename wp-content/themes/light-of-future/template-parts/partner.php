<?php
/**
 * Partner page layout (0.71.0): hero, logo + story, what the partnership achieves,
 * related programme, other partners, become a partner.
 *
 * @package LightOfFuture
 */

defined( 'ABSPATH' ) || exit;

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
	?>
	<main id="content" <?php post_class( 'lof-single lof-partner' ); ?>>
		<section class="lof-hero lof-hero--page lof-partner__hero">
			<div class="lof-hero__art" aria-hidden="true"><span class="lof-hero__sun"></span><span class="lof-hero__rays"></span><span class="lof-hero__band lof-hero__band--1"></span><span class="lof-hero__band lof-hero__band--2"></span><span class="lof-hero__band lof-hero__band--3"></span></div>
			<div class="lof-container lof-hero__inner"><div class="lof-hero__content">
				<nav class="lof-crumbs lof-crumbs--single" aria-label="<?php esc_attr_e( 'Breadcrumb', 'light-of-future' ); ?>"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'light-of-future' ); ?></a><span aria-hidden="true">/</span><a href="<?php echo esc_url( $lof_all ); ?>"><?php esc_html_e( 'Our partners', 'light-of-future' ); ?></a></nav>
				<p class="lof-eyebrow lof-hero__eyebrow"><?php echo esc_html( '' !== $lof_status ? $lof_status : __( 'Our partner', 'light-of-future' ) ); ?><?php echo '' !== $lof_focus ? ' &middot; ' . esc_html( $lof_focus ) : ''; ?></p>
				<h1 class="lof-hero__title"><?php the_title(); ?></h1>
				<?php if ( '' !== $lof_tagline ) : ?>
					<p class="lof-hero__text"><?php echo esc_html( $lof_tagline ); ?></p>
				<?php endif; ?>
			</div></div>
		</section>

		<div class="lof-section lof-tone-white lof-partner__story">
			<div class="lof-container lof-partner__grid">
				<div class="lof-partner__side">
					<figure class="lof-partner__logo">
						<?php
						if ( has_post_thumbnail() ) {
							the_post_thumbnail( 'medium', array( 'alt' => sprintf( /* translators: %s: partner name. */ __( '%s logo', 'light-of-future' ), get_the_title() ), 'loading' => 'eager' ) );
						} else {
							echo '<span class="lof-partner__mono" aria-hidden="true">' . esc_html( mb_substr( get_the_title(), 0, 1 ) ) . '</span>';
						}
						?>
						<figcaption><?php the_title(); ?></figcaption>
					</figure>
					<?php if ( '' !== $lof_status || '' !== $lof_focus ) : ?>
						<dl class="lof-partner__facts">
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
					<?php endif; ?>
				</div>
				<article class="lof-partner__copy lof-prose">
					<?php the_content(); ?>
					<?php if ( '' !== $lof_site ) : ?>
						<p><a class="lof-btn lof-btn--earth" href="<?php echo esc_url( $lof_site ); ?>" rel="noopener" target="_blank"><?php esc_html_e( 'Visit their website', 'light-of-future' ); ?></a></p>
					<?php endif; ?>
				</article>
			</div>
		</div>

		<?php if ( $lof_impacts ) : ?>
			<div class="lof-section lof-partner__impact">
				<div class="lof-container">
					<div class="lof-section__head lof-partner__head">
						<p class="lof-eyebrow"><?php esc_html_e( 'Together', 'light-of-future' ); ?></p>
						<h2 class="lof-section__title"><?php esc_html_e( 'The difference this partnership makes', 'light-of-future' ); ?></h2>
					</div>
					<ol class="lof-partner__impacts">
						<?php foreach ( $lof_impacts as $lof_i => $lof_line ) : ?>
							<li class="lof-reveal"><span class="lof-partner__n" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $lof_i + 1 ) ); ?></span><p><?php echo esc_html( $lof_line ); ?></p></li>
						<?php endforeach; ?>
					</ol>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( $lof_prog ) : ?>
			<div class="lof-section lof-tone-white lof-partner__prog">
				<div class="lof-container">
					<a class="lof-partner__progcard" href="<?php echo esc_url( get_permalink( $lof_prog ) ); ?>">
						<?php if ( has_post_thumbnail( $lof_prog ) ) : ?>
							<span class="lof-partner__progimg"><?php echo get_the_post_thumbnail( $lof_prog, 'medium_large', array( 'loading' => 'lazy', 'alt' => '' ) ); ?></span>
						<?php endif; ?>
						<span class="lof-partner__progtext">
							<span class="lof-eyebrow"><?php esc_html_e( 'The programme they support', 'light-of-future' ); ?></span>
							<strong><?php echo esc_html( get_the_title( $lof_prog ) ); ?></strong>
							<?php $lof_lead = function_exists( 'lof_programme_lead' ) ? (string) lof_programme_lead( $lof_prog ) : (string) get_the_excerpt( $lof_prog ); ?>
							<?php if ( '' !== $lof_lead ) : ?>
								<span class="lof-partner__proglead"><?php echo esc_html( wp_strip_all_tags( $lof_lead ) ); ?></span>
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
							$lof_ourl = lof_partner_page_url( $lof_o );
							$lof_ologo = has_post_thumbnail( $lof_o ) ? get_the_post_thumbnail( $lof_o, 'medium', array( 'loading' => 'lazy', 'alt' => '' ) ) : '';
							$lof_inner = $lof_ologo . '<span>' . esc_html( get_the_title( $lof_o ) ) . '</span>';
							echo '<li>' . ( '' !== $lof_ourl ? '<a href="' . esc_url( $lof_ourl ) . '">' . $lof_inner . '</a>' : '<div>' . $lof_inner . '</div>' ) . '</li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core image markup and escaped title.
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
						<a class="lof-btn lof-btn--earth" href="<?php echo esc_url( $lof_all ); ?>"><?php esc_html_e( 'All our partners', 'light-of-future' ); ?></a>
					</div>
				</div>
			</div>
		</div>
	</main>
	<?php
endwhile;
