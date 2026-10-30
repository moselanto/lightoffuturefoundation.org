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
		<div class="lof-header__brand"><?php lof_logo_themed(); ?></div>
		<button class="lof-theme-toggle" type="button" aria-pressed="false" data-label-dark="<?php esc_attr_e( 'Switch to dark mode', 'light-of-future' ); ?>" data-label-light="<?php esc_attr_e( 'Switch to light mode', 'light-of-future' ); ?>" aria-label="<?php esc_attr_e( 'Switch to dark mode', 'light-of-future' ); ?>" title="<?php esc_attr_e( 'Light / dark mode', 'light-of-future' ); ?>">
			<svg class="lof-icon-moon" viewBox="0 0 24 24" width="20" height="20" aria-hidden="true" focusable="false"><path fill="currentColor" d="M21 14.5A9 9 0 0 1 9.5 3a.7.7 0 0 0-.9-.8A10 10 0 1 0 21.8 15.4a.7.7 0 0 0-.8-.9z"/></svg>
			<svg class="lof-icon-sun" viewBox="0 0 24 24" width="20" height="20" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="4.5" fill="currentColor"/><g stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 2v2M12 20v2M2 12h2M20 12h2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></g></svg>
		</button>
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
			<a class="lof-btn lof-btn--sun lof-nav__cta" href="<?php echo esc_url( lof_donate_url() ); ?>"><?php echo esc_html( lof_setting( 'donate_label', __( 'Donate', 'light-of-future' ) ) ); ?></a>
		</nav>
	</div>
</header>
