<?php
/**
 * Organisation settings (WordPress: Light of Future > Organisation details).
 *
 * Only details confirmed by the organisation belong here. Empty fields are
 * hidden on the site, never replaced with invented values.
 *
 * @package LightOfFutureCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Field definitions: key => [label, type, help].
 *
 * @return array<string,array{0:string,1:string,2:string}>
 */
function lof_core_setting_fields() {
	return array(
		'phone'             => array( __( 'Phone', 'light-of-future-core' ), 'text', __( 'Shown in the header and footer.', 'light-of-future-core' ) ),
		'whatsapp'          => array( __( 'WhatsApp number', 'light-of-future-core' ), 'text', __( 'International format, e.g. 2547XXXXXXXX. The WhatsApp button only appears when this is set.', 'light-of-future-core' ) ),
		'email'             => array( __( 'Email', 'light-of-future-core' ), 'email', '' ),
		'address'           => array( __( 'Address / location', 'light-of-future-core' ), 'text', '' ),
		'office_hours'      => array( __( 'Office hours', 'light-of-future-core' ), 'text', '' ),
		'registration_line' => array( __( 'Registration line (footer)', 'light-of-future-core' ), 'text', __( 'Publish only wording the board has approved, e.g. "Registered with the NGO Board of Kenya".', 'light-of-future-core' ) ),
		'topbar_text'       => array( __( 'Top bar message', 'light-of-future-core' ), 'text', '' ),
		'footer_intro'      => array( __( 'Footer introduction', 'light-of-future-core' ), 'textarea', '' ),
		'donate_label'      => array( __( 'Donate button label', 'light-of-future-core' ), 'text', '' ),
		'donate_url'        => array( __( 'Donate button link', 'light-of-future-core' ), 'url', __( 'Leave empty to use the Donate page.', 'light-of-future-core' ) ),
		'social_facebook'   => array( 'Facebook', 'url', '' ),
		'social_instagram'  => array( 'Instagram', 'url', '' ),
		'social_youtube'    => array( 'YouTube', 'url', '' ),
		'social_tiktok'     => array( 'TikTok', 'url', '' ),
		'social_x'          => array( 'X', 'url', '' ),
		'social_linkedin'   => array( 'LinkedIn', 'url', '' ),
		'payment_mode'      => array( __( 'Payment mode', 'light-of-future-core' ), 'select', __( 'Gateway: Paystack (M-PESA and card). Stays on "Not set up" until the foundation\'s Paystack account is verified and tested. API keys go in wp-config.php (LOF_PAYSTACK_PUBLIC_KEY, LOF_PAYSTACK_SECRET_KEY), never here.', 'light-of-future-core' ) ),
	);
}

/**
 * Defaults: only details published on the organisation's previous website
 * (lightoffuture.org, read 30 Oct 2026). Confirm before launch.
 *
 * @return array<string,string>
 */
function lof_core_setting_defaults() {
	return array(
		'phone'        => '+254 715 394295',
		'email'        => 'lightoffuturefoundation@gmail.com',
		'address'      => 'Nairobi, Kenya',
		'payment_mode' => 'off',
	);
}

/**
 * Read one setting.
 *
 * @param string $key     Key.
 * @param string $default Fallback.
 * @return string
 */
function lof_core_setting( $key, $default = '' ) {
	$saved    = get_option( 'lof_core_settings', array() );
	$saved    = is_array( $saved ) ? $saved : array();
	$defaults = lof_core_setting_defaults();
	if ( isset( $saved[ $key ] ) && '' !== $saved[ $key ] ) {
		return (string) $saved[ $key ];
	}
	if ( ! isset( $saved[ $key ] ) && isset( $defaults[ $key ] ) ) {
		return $defaults[ $key ];
	}
	return (string) $default;
}

/**
 * Admin menu.
 */
function lof_core_admin_menu() {
	add_menu_page(
		__( 'Light of Future', 'light-of-future-core' ),
		__( 'Light of Future', 'light-of-future-core' ),
		'manage_options',
		'lof-core',
		'lof_core_settings_page',
		'dashicons-lightbulb',
		3
	);
	add_submenu_page( 'lof-core', __( 'Organisation details', 'light-of-future-core' ), __( 'Organisation details', 'light-of-future-core' ), 'manage_options', 'lof-core', 'lof_core_settings_page' );
}
add_action( 'admin_menu', 'lof_core_admin_menu' );

/**
 * Register the option with a sanitiser.
 */
function lof_core_register_setting() {
	register_setting(
		'lof_core_settings',
		'lof_core_settings',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'lof_core_sanitize_settings',
			'show_in_rest'      => false,
		)
	);
}
add_action( 'admin_init', 'lof_core_register_setting' );

/**
 * Sanitise by field type.
 *
 * @param mixed $input Raw input.
 * @return array<string,string>
 */
function lof_core_sanitize_settings( $input ) {
	$input = is_array( $input ) ? $input : array();
	$clean = array();
	foreach ( lof_core_setting_fields() as $key => $field ) {
		$value = isset( $input[ $key ] ) ? wp_unslash( $input[ $key ] ) : '';
		switch ( $field[1] ) {
			case 'email':
				$clean[ $key ] = sanitize_email( $value );
				break;
			case 'url':
				$clean[ $key ] = esc_url_raw( $value );
				break;
			case 'textarea':
				$clean[ $key ] = sanitize_textarea_field( $value );
				break;
			case 'select':
				$clean[ $key ] = in_array( $value, array( 'off', 'sandbox', 'live' ), true ) ? $value : 'off';
				break;
			default:
				$clean[ $key ] = sanitize_text_field( $value );
		}
	}
	return $clean;
}

/**
 * Settings screen.
 */
function lof_core_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Organisation details', 'light-of-future-core' ); ?></h1>
		<p><?php esc_html_e( 'These details appear across the website. Leave a field empty to hide it. Never enter passwords or payment API keys here; those go in wp-config.php.', 'light-of-future-core' ); ?></p>
		<form method="post" action="options.php">
			<?php settings_fields( 'lof_core_settings' ); ?>
			<table class="form-table" role="presentation">
				<?php foreach ( lof_core_setting_fields() as $key => $field ) : ?>
					<?php
					$id    = 'lof_core_' . $key;
					$name  = 'lof_core_settings[' . $key . ']';
					$value = lof_core_setting( $key );
					?>
					<tr>
						<th scope="row"><label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $field[0] ); ?></label></th>
						<td>
							<?php if ( 'textarea' === $field[1] ) : ?>
								<textarea class="large-text" rows="3" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>"><?php echo esc_textarea( $value ); ?></textarea>
							<?php elseif ( 'select' === $field[1] ) : ?>
								<select id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>">
									<option value="off" <?php selected( $value, 'off' ); ?>><?php esc_html_e( 'Not set up', 'light-of-future-core' ); ?></option>
									<option value="sandbox" <?php selected( $value, 'sandbox' ); ?>><?php esc_html_e( 'Test (sandbox)', 'light-of-future-core' ); ?></option>
									<option value="live" <?php selected( $value, 'live' ); ?>><?php esc_html_e( 'Live', 'light-of-future-core' ); ?></option>
								</select>
							<?php else : ?>
								<input class="regular-text" type="<?php echo esc_attr( 'url' === $field[1] ? 'url' : ( 'email' === $field[1] ? 'email' : 'text' ) ); ?>" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>">
							<?php endif; ?>
							<?php if ( $field[2] ) : ?>
								<p class="description"><?php echo esc_html( $field[2] ); ?></p>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}
