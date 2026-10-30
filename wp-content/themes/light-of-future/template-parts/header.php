<?php
/**
 * Site header (used when no Elementor Pro header template is assigned).
 *
 * @package LightOfFuture
 */

defined( 'ABSPATH' ) || exit;

$lof_phone = lof_setting( 'phone' );
$lof_email = lof_setting( 'email' );
?>
<a class="lof-skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'light-of-future' ); ?></a>
<?php if ( $lof_phone || $lof_email ) : ?>
<div class="lof-topbar">
	<div class="lof-container lof-topbar__inner">
		<p class="lof-topbar__tag"><?php echo esc_html( lof_setting( 'topbar_text', __( 'Bringing hope and opportunity to communities in Kenya', 'light-of-future' ) ) ); ?></p>
		<ul class="lof-topbar__contacts">
			<?php if ( $lof_phone ) : ?>
				<li><a href="<?php echo esc_url( 'tel:' . preg_replace( '/[^0-9+]/', '', $lof_phone ) ); ?>"><?php echo esc_html( $lof_phone ); ?></a></li>
			<?php endif; ?>
			<?php if ( $lof_email ) : ?>
				<li><a href="<?php echo esc_url( 'mailto:' . antispambot( $lof_email ) ); ?>"><?php echo esc_html( antispambot( $lof_email ) ); ?></a></li>
			<?php endif; ?>
		</ul>
	</div>
</div>
<?php endif; ?>
<header id="site-header" class="lof-header" data-lof-header>
	<div class="lof-container lof-header__inner">
		<div class="lof-header__brand"><?php lof_logo( 'light', 'horizontal' ); ?></div>
		<button class="lof-nav-toggle" type="button" aria-controls="lof-primary-nav" aria-expanded="false">
			<span class="lof-nav-toggle__bars" aria-hidden="true"></span>
			<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'light-of-future' ); ?></span>
		</button>
		<nav id="lof-primary-nav" class="lof-nav" aria-label="<?php esc_attr_e( 'Main menu', 'light-of-future' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'lof-primary',
					'container'      => false,
					'menu_class'     => 'lof-menu',
					'depth'          => 2,
					'fallback_cb'    => 'lof_fallback_menu',
				)
			);
			?>
			<a class="lof-btn lof-btn--sun lof-nav__cta" href="<?php echo esc_url( lof_donate_url() ); ?>"><?php echo esc_html( lof_setting( 'donate_label', __( 'Support the Future', 'light-of-future' ) ) ); ?></a>
		</nav>
	</div>
</header>
