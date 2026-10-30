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
$lof_social  = lof_social_links();
?>
<footer id="site-footer" class="lof-footer">
	<div class="lof-footer__glow" aria-hidden="true"></div>
	<div class="lof-container lof-footer__grid">
		<div class="lof-footer__about">
			<?php lof_logo( 'dark', 'stacked' ); ?>
			<p><?php echo esc_html( lof_setting( 'footer_intro', __( 'Supporting widows, widowers, orphans and vulnerable children through education, clean water and community programmes.', 'light-of-future' ) ) ); ?></p>
			<a class="lof-btn lof-btn--sun" href="<?php echo esc_url( lof_donate_url() ); ?>"><?php echo esc_html( lof_setting( 'donate_label', __( 'Support the Future', 'light-of-future' ) ) ); ?></a>
		</div>
		<nav class="lof-footer__col" aria-label="<?php esc_attr_e( 'Quick links', 'light-of-future' ); ?>">
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
		<div class="lof-footer__col">
			<h2 class="lof-footer__title"><?php esc_html_e( 'Contact', 'light-of-future' ); ?></h2>
			<ul class="lof-footer__contact">
				<?php if ( $lof_address ) : ?>
					<li><?php echo esc_html( $lof_address ); ?></li>
				<?php endif; ?>
				<?php if ( $lof_phone ) : ?>
					<li><a href="<?php echo esc_url( 'tel:' . preg_replace( '/[^0-9+]/', '', $lof_phone ) ); ?>"><?php echo esc_html( $lof_phone ); ?></a></li>
				<?php endif; ?>
				<?php if ( $lof_email ) : ?>
					<li><a href="<?php echo esc_url( 'mailto:' . antispambot( $lof_email ) ); ?>"><?php echo esc_html( antispambot( $lof_email ) ); ?></a></li>
				<?php endif; ?>
			</ul>
			<?php if ( $lof_social ) : ?>
				<ul class="lof-social">
					<?php foreach ( $lof_social as $lof_network => $lof_url ) : ?>
						<li><a href="<?php echo esc_url( $lof_url ); ?>" rel="noopener" target="_blank"><?php echo esc_html( ucfirst( $lof_network ) ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
	</div>
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
	</div>
</footer>
