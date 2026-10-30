<?php
/**
 * Site footer (used when no Elementor Pro footer template is assigned).
 *
 * @package LightOfFuture
 */

defined( 'ABSPATH' ) || exit;

$lof_phone   = lof_setting( 'phone' );
$lof_email   = lof_setting( 'email' );
$lof_address = lof_setting( 'address' );
$lof_wa      = preg_replace( '/[^0-9]/', '', (string) lof_setting( 'whatsapp' ) );
$lof_social  = lof_social_links();
$lof_areas   = post_type_exists( 'lof_programme' ) ? get_posts(
	array(
		'post_type'      => 'lof_programme',
		'posts_per_page' => 6,
		'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
		'no_found_rows'  => true,
	)
) : array();
$lof_icons = array(
	'facebook'  => '<path d="M14 8h3V4h-3c-2.8 0-4.5 1.8-4.5 4.6V11H7v4h2.5v9h4v-9h3l.5-4h-3.5V8.8c0-.5.3-.8.5-.8z"/>',
	'instagram' => '<path d="M12 7.3A4.7 4.7 0 1 0 12 16.7 4.7 4.7 0 0 0 12 7.3zm0 7.7a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm6-7.9a1.1 1.1 0 1 1-2.2 0 1.1 1.1 0 0 1 2.2 0zM12 3.6c2.7 0 3 0 4.1.1 2.7.1 4 1.4 4.1 4.1.1 1.1.1 1.4.1 4.1s0 3-.1 4.1c-.1 2.7-1.4 4-4.1 4.1-1.1.1-1.4.1-4.1.1s-3 0-4.1-.1c-2.7-.1-4-1.4-4.1-4.1C3.7 15 3.6 14.7 3.6 12s0-3 .1-4.1c.1-2.7 1.4-4 4.1-4.1 1.1-.1 1.4-.1 4.2-.2z"/>',
	'youtube'   => '<path d="M22 8.2a3 3 0 0 0-2.1-2.1C18 5.6 12 5.6 12 5.6s-6 0-7.9.5A3 3 0 0 0 2 8.2 31 31 0 0 0 1.6 12 31 31 0 0 0 2 15.8a3 3 0 0 0 2.1 2.1c1.9.5 7.9.5 7.9.5s6 0 7.9-.5a3 3 0 0 0 2.1-2.1 31 31 0 0 0 .4-3.8 31 31 0 0 0-.4-3.8zM10 15.1V8.9l5.2 3.1z"/>',
	'tiktok'    => '<path d="M16.6 3h-3.3v12.4a2.8 2.8 0 1 1-2-2.7V9.3a6.1 6.1 0 1 0 5.3 6.1V9.1a7.6 7.6 0 0 0 4.4 1.4V7.2a4.4 4.4 0 0 1-4.4-4.2z"/>',
	'x'         => '<path d="M17.7 3h3.1l-6.8 7.8 8 10.2h-6.3l-4.9-6.4L5.2 21H2.1l7.3-8.3L1.8 3h6.4l4.4 5.9zm-1.1 16.2h1.7L7.5 4.7H5.6z"/>',
	'linkedin'  => '<path d="M4.9 3.3a2 2 0 1 1 0 4 2 2 0 0 1 0-4zM3.2 9h3.4v11.7H3.2zm5.6 0h3.3v1.6c.5-.9 1.6-1.9 3.4-1.9 3.6 0 4.3 2.4 4.3 5.5v6.5h-3.4v-5.8c0-1.4 0-3.1-1.9-3.1s-2.2 1.5-2.2 3v5.9H8.8z"/>',
	'whatsapp'  => '<path d="M12 2.2a9.8 9.8 0 0 0-8.4 14.9L2.2 21.8l4.8-1.3A9.8 9.8 0 1 0 12 2.2zm0 17.9a8.1 8.1 0 0 1-4.2-1.2l-.3-.2-2.8.8.8-2.8-.2-.3A8.1 8.1 0 1 1 12 20.1zm4.5-6c-.2-.1-1.5-.7-1.7-.8-.2-.1-.4-.1-.6.1l-.8 1c-.1.2-.3.2-.5.1a6.6 6.6 0 0 1-3.3-2.9c-.2-.4.2-.4.7-1.3.1-.2 0-.3 0-.4l-.8-1.8c-.2-.5-.4-.4-.6-.4h-.5a1 1 0 0 0-.7.3 3 3 0 0 0-.9 2.2c0 1.3.9 2.5 1.1 2.7.1.2 1.8 2.8 4.4 3.9 1.6.7 2.3.8 3.1.6.5-.1 1.5-.6 1.7-1.2.2-.6.2-1.1.2-1.2-.1-.1-.3-.2-.5-.3z"/>',
);
if ( $lof_wa ) {
	$lof_social['whatsapp'] = 'https://wa.me/' . $lof_wa;
}
$lof_show_cta = ! is_front_page() && ! is_page( 'donate' );
?>
<footer id="site-footer" class="lof-footer lof-footer--v2">
	<div class="lof-footer__rays" aria-hidden="true"></div>
	<?php if ( $lof_show_cta ) : ?>
	<div class="lof-container">
		<div class="lof-footer__cta">
			<div class="lof-footer__cta-copy">
				<p class="lof-footer__kicker"><?php esc_html_e( 'Be part of the story', 'light-of-future' ); ?></p>
				<h2 class="lof-footer__cta-title"><?php esc_html_e( 'Every gift lights a future.', 'light-of-future' ); ?></h2>
				<p><?php esc_html_e( 'Give, volunteer or partner with us to bring hope and opportunity to families in Kenya.', 'light-of-future' ); ?></p>
			</div>
			<div class="lof-footer__cta-actions">
				<a class="lof-btn lof-btn--earth" href="<?php echo esc_url( lof_donate_url() ); ?>"><?php esc_html_e( 'Donate now', 'light-of-future' ); ?></a>
				<a class="lof-btn lof-footer__cta-ghost" href="<?php echo esc_url( home_url( '/get-involved/' ) ); ?>"><?php esc_html_e( 'Get involved', 'light-of-future' ); ?></a>
			</div>
		</div>
	</div>
	<?php endif; ?>
	<div class="lof-container lof-footer__main">
		<div class="lof-footer__brand">
			<?php lof_logo( 'dark', 'stacked' ); ?>
			<p class="lof-footer__intro"><?php echo esc_html( lof_setting( 'footer_intro', __( 'Supporting widows, widowers, orphans and vulnerable children through education, clean water and community programmes.', 'light-of-future' ) ) ); ?></p>
			<?php if ( $lof_social ) : ?>
				<ul class="lof-footer__social">
					<?php foreach ( $lof_social as $lof_network => $lof_url ) : ?>
						<li><a href="<?php echo esc_url( $lof_url ); ?>" rel="noopener" target="_blank" aria-label="<?php echo esc_attr( 'x' === $lof_network ? 'X' : ( 'tiktok' === $lof_network ? 'TikTok' : ( 'youtube' === $lof_network ? 'YouTube' : ( 'linkedin' === $lof_network ? 'LinkedIn' : ( 'whatsapp' === $lof_network ? 'WhatsApp' : ucfirst( $lof_network ) ) ) ) ) ); ?>"><svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true" focusable="false" fill="currentColor"><?php echo isset( $lof_icons[ $lof_network ] ) ? $lof_icons[ $lof_network ] : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG paths. ?></svg></a></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
		<nav class="lof-footer__col" aria-label="<?php esc_attr_e( 'Explore', 'light-of-future' ); ?>">
			<h2 class="lof-footer__title"><?php esc_html_e( 'Explore', 'light-of-future' ); ?></h2>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'lof-footer',
					'container'      => false,
					'menu_class'     => 'lof-footer__links',
					'depth'          => 1,
					'fallback_cb'    => 'lof_fallback_menu',
				)
			);
			?>
		</nav>
		<?php if ( $lof_areas ) : ?>
		<nav class="lof-footer__col" aria-label="<?php esc_attr_e( 'Focus areas', 'light-of-future' ); ?>">
			<h2 class="lof-footer__title"><?php esc_html_e( 'Focus areas', 'light-of-future' ); ?></h2>
			<ul class="lof-footer__links">
				<?php foreach ( $lof_areas as $lof_area ) : ?>
					<li><a href="<?php echo esc_url( get_permalink( $lof_area ) ); ?>"><?php echo esc_html( get_the_title( $lof_area ) ); ?></a></li>
				<?php endforeach; ?>
				<li><a class="lof-footer__more" href="<?php echo esc_url( home_url( '/programmes/' ) ); ?>"><?php esc_html_e( 'All 11 focus areas', 'light-of-future' ); ?></a></li>
			</ul>
		</nav>
		<?php endif; ?>
		<div class="lof-footer__col">
			<h2 class="lof-footer__title"><?php esc_html_e( 'Get in touch', 'light-of-future' ); ?></h2>
			<ul class="lof-footer__contact">
				<?php if ( $lof_address ) : ?>
					<li><span class="lof-footer__ico" aria-hidden="true"><svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M12 2a7 7 0 0 0-7 7c0 5.2 7 13 7 13s7-7.8 7-13a7 7 0 0 0-7-7zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5z"/></svg></span><span><?php echo esc_html( $lof_address ); ?></span></li>
				<?php endif; ?>
				<?php if ( $lof_phone ) : ?>
					<li><span class="lof-footer__ico" aria-hidden="true"><svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M6.6 10.8a15.2 15.2 0 0 0 6.6 6.6l2.2-2.2a1 1 0 0 1 1-.2 11.4 11.4 0 0 0 3.6.6 1 1 0 0 1 1 1V20a1 1 0 0 1-1 1A17 17 0 0 1 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.2.2 2.4.6 3.6a1 1 0 0 1-.3 1z"/></svg></span><a href="<?php echo esc_url( 'tel:' . preg_replace( '/[^0-9+]/', '', $lof_phone ) ); ?>"><?php echo esc_html( $lof_phone ); ?></a></li>
				<?php endif; ?>
				<?php if ( $lof_email ) : ?>
					<li><span class="lof-footer__ico" aria-hidden="true"><svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm0 4.2-8 5-8-5V6l8 5 8-5z"/></svg></span><a href="<?php echo esc_url( 'mailto:' . antispambot( $lof_email ) ); ?>"><?php echo esc_html( antispambot( $lof_email ) ); ?></a></li>
				<?php endif; ?>
			</ul>
			<a class="lof-btn lof-btn--sun lof-footer__donate" href="<?php echo esc_url( lof_donate_url() ); ?>"><?php echo esc_html( lof_setting( 'donate_label', __( 'Support the Future', 'light-of-future' ) ) ); ?></a>
		</div>
	</div>
	<div class="lof-footer__mark" aria-hidden="true">Light of Future</div>
	<div class="lof-footer__base">
		<div class="lof-container lof-footer__bottom">
			<p>&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?>. <?php echo esc_html( lof_setting( 'registration_line' ) ); ?></p>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'lof-legal',
					'container'      => false,
					'menu_class'     => 'lof-footer__legal',
					'depth'          => 1,
					'fallback_cb'    => false,
				)
			);
			?>
			<a class="lof-footer__top" href="#"><?php esc_html_e( 'Back to top', 'light-of-future' ); ?> <svg viewBox="0 0 24 24" width="14" height="14" aria-hidden="true" focusable="false"><path fill="currentColor" d="M12 5l7 7-1.4 1.4L13 8.8V20h-2V8.8l-4.6 4.6L5 12z"/></svg></a>
		</div>
	</div>
</footer>
