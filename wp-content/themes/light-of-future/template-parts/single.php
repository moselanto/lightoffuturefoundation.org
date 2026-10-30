<?php
/**
 * Single post template (posts, programmes, projects, stories, reports) used
 * when the item is not built with Elementor.
 *
 * @package LightOfFuture
 */

defined( 'ABSPATH' ) || exit;

while ( have_posts() ) :
	the_post();
	$lof_type     = get_post_type();
	$lof_location = (string) get_post_meta( get_the_ID(), 'lof_location', true );
	$lof_labels   = array(
		'lof_programme' => __( 'Programme', 'light-of-future' ),
		'lof_project'   => __( 'Project', 'light-of-future' ),
		'lof_story'     => __( 'Impact story', 'light-of-future' ),
		'lof_resource'  => __( 'Report', 'light-of-future' ),
	);
	$lof_back     = array(
		'lof_programme' => array( '/programmes/', __( 'All programmes', 'light-of-future' ) ),
		'lof_story'     => array( '/stories/', __( 'All stories', 'light-of-future' ) ),
	);
	?>
	<main id="content" <?php post_class( 'lof-single' ); ?>>
		<section class="lof-hero lof-hero--page">
			<div class="lof-hero__art" aria-hidden="true"><span class="lof-hero__sun"></span><span class="lof-hero__rays"></span><span class="lof-hero__band lof-hero__band--1"></span><span class="lof-hero__band lof-hero__band--2"></span><span class="lof-hero__band lof-hero__band--3"></span></div>
			<div class="lof-container lof-hero__inner"><div class="lof-hero__content">
				<p class="lof-eyebrow lof-hero__eyebrow"><?php echo esc_html( isset( $lof_labels[ $lof_type ] ) ? $lof_labels[ $lof_type ] : get_the_date() ); ?><?php echo $lof_location ? ' &middot; ' . esc_html( $lof_location ) : ''; ?></p>
				<h1 class="lof-hero__title"><?php the_title(); ?></h1>
				<?php if ( has_excerpt() ) : ?>
					<p class="lof-hero__text"><?php echo esc_html( get_the_excerpt() ); ?></p>
				<?php endif; ?>
			</div></div>
		</section>
		<div class="lof-section lof-tone-white">
			<div class="lof-container lof-single__grid">
				<article class="lof-single__body lof-prose">
					<?php if ( has_post_thumbnail() ) : ?>
						<figure class="lof-single__image"><?php the_post_thumbnail( 'large' ); ?></figure>
					<?php endif; ?>
					<?php
					$lof_quote = (string) get_post_meta( get_the_ID(), 'lof_quote', true );
					if ( $lof_quote ) {
						printf( '<blockquote class="lof-quote"><p>%s</p></blockquote>', esc_html( $lof_quote ) );
					}
					the_content();
					if ( 'lof_resource' === $lof_type ) {
						$lof_file = (string) get_post_meta( get_the_ID(), 'lof_file_url', true );
						if ( $lof_file ) {
							printf( '<p><a class="lof-btn lof-btn--earth" href="%1$s">%2$s</a></p>', esc_url( $lof_file ), esc_html__( 'Download', 'light-of-future' ) );
						}
					}
					?>
				</article>
				<aside class="lof-single__aside">
					<?php
					if ( 'lof_programme' === $lof_type || 'lof_project' === $lof_type ) {
						$lof_facts = array(
							'lof_beneficiary'  => __( 'Who it serves', 'light-of-future' ),
							'lof_location'     => __( 'Where', 'light-of-future' ),
							'lof_status'       => __( 'Status', 'light-of-future' ),
							'lof_dates'        => __( 'Timeline', 'light-of-future' ),
							'lof_funding_need' => __( 'Funding need', 'light-of-future' ),
						);
						echo '<dl class="lof-facts">';
						foreach ( $lof_facts as $lof_key => $lof_label ) {
							$lof_val = (string) get_post_meta( get_the_ID(), $lof_key, true );
							if ( '' !== $lof_val ) {
								printf( '<div><dt>%1$s</dt><dd>%2$s</dd></div>', esc_html( $lof_label ), esc_html( $lof_val ) );
							}
						}
						echo '</dl>';
					}
					?>
					<div class="lof-aside-cta">
						<h2><?php esc_html_e( 'Help us do more', 'light-of-future' ); ?></h2>
						<p><?php esc_html_e( 'Your support brings hope and opportunity to more families.', 'light-of-future' ); ?></p>
						<a class="lof-btn lof-btn--sun" href="<?php echo esc_url( lof_donate_url() ); ?>"><?php esc_html_e( 'Donate', 'light-of-future' ); ?></a>
					</div>
					<?php if ( isset( $lof_back[ $lof_type ] ) ) : ?>
						<p><a class="lof-card__link" href="<?php echo esc_url( home_url( $lof_back[ $lof_type ][0] ) ); ?>"><?php echo esc_html( $lof_back[ $lof_type ][1] ); ?></a></p>
					<?php endif; ?>
				</aside>
			</div>
		</div>
	</main>
	<?php
endwhile;
