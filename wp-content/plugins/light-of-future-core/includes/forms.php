<?php
/**
 * Secure enquiry form (contact, volunteer, partner, sponsor, giving).
 *
 * Visitor -> nonce + signed time + honeypot -> rate limit -> sanitise and
 * validate -> private `lof_enquiry` record (never public, never in REST) ->
 * plain-text email to the foundation -> back to the form with a message.
 *
 * Use: [lof_enquiry_form interest="volunteer"] or the LOF Enquiry Form widget.
 *
 * @package LightOfFutureCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enquiry types.
 *
 * @return array<string,string>
 */
function lof_core_enquiry_types() {
	return array(
		'general'   => __( 'General enquiry', 'light-of-future-core' ),
		'volunteer' => __( 'Volunteering', 'light-of-future-core' ),
		'partner'   => __( 'Partnership', 'light-of-future-core' ),
		'sponsor'   => __( 'Sponsorship', 'light-of-future-core' ),
		'donate'    => __( 'Giving / donations', 'light-of-future-core' ),
		'shop'      => __( 'Shop / crafts', 'light-of-future-core' ),
	);
}

/**
 * Private storage for enquiries.
 */
function lof_core_register_enquiries() {
	register_post_type(
		'lof_enquiry',
		array(
			'labels'              => array(
				'name'          => __( 'Enquiries', 'light-of-future-core' ),
				'singular_name' => __( 'Enquiry', 'light-of-future-core' ),
				'menu_name'     => __( 'Enquiries', 'light-of-future-core' ),
			),
			'public'              => false,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'show_ui'             => true,
			'show_in_menu'        => 'lof-core',
			'show_in_rest'        => false,
			'show_in_nav_menus'   => false,
			'supports'            => array( 'title', 'editor' ),
			'capability_type'     => 'post',
			'capabilities'        => array( 'create_posts' => 'do_not_allow' ),
			'map_meta_cap'        => true,
			'rewrite'             => false,
			'query_var'           => false,
		)
	);
}
add_action( 'init', 'lof_core_register_enquiries' );

/**
 * Visitor IP, hashed (we never store the raw address).
 *
 * @return string
 */
function lof_core_ip_hash() {
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	return wp_hash( 'lof-ip|' . $ip );
}

/**
 * Render the form.
 *
 * @param array $args interest (default type), button (label), id.
 * @return string
 */
function lof_core_enquiry_form( $args = array() ) {
	$args     = wp_parse_args( $args, array( 'interest' => 'general', 'button' => __( 'Send message', 'light-of-future-core' ), 'id' => 'lof-form' ) );
	$types    = lof_core_enquiry_types();
	$interest = isset( $types[ $args['interest'] ] ) ? $args['interest'] : 'general';
	$id       = sanitize_html_class( $args['id'] );
	$time     = time();
	$status   = isset( $_GET['lof_form'] ) ? sanitize_key( wp_unslash( $_GET['lof_form'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- display only.
	$messages = array(
		'sent'    => array( 'success', __( 'Thank you. Your message has been received and we will get back to you soon.', 'light-of-future-core' ) ),
		'invalid' => array( 'error', __( 'Please fill in your name, a valid email address and a message, and tick the consent box.', 'light-of-future-core' ) ),
		'limit'   => array( 'error', __( 'Too many messages in a short time. Please try again later or call us.', 'light-of-future-core' ) ),
		'expired' => array( 'error', __( 'The form expired. Please send it again.', 'light-of-future-core' ) ),
	);
	ob_start();
	?>
	<div class="lof-form-wrap" id="<?php echo esc_attr( $id ); ?>">
		<?php if ( isset( $messages[ $status ] ) ) : ?>
			<div class="lof-form-msg lof-form-msg--<?php echo esc_attr( $messages[ $status ][0] ); ?>" role="<?php echo 'success' === $messages[ $status ][0] ? 'status' : 'alert'; ?>"><?php echo esc_html( $messages[ $status ][1] ); ?></div>
		<?php endif; ?>
		<form class="lof-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" novalidate>
			<input type="hidden" name="action" value="lof_enquiry">
			<input type="hidden" name="lof_ts" value="<?php echo esc_attr( $time . '.' . wp_hash( 'lof-ts|' . $time ) ); ?>">
			<input type="hidden" name="lof_return" value="<?php echo esc_attr( get_permalink() ? get_permalink() : home_url( '/' ) ); ?>">
			<input type="hidden" name="lof_anchor" value="<?php echo esc_attr( $id ); ?>">
			<?php wp_nonce_field( 'lof_enquiry', 'lof_enquiry_nonce' ); ?>
			<div class="lof-form__hp" aria-hidden="true"><label>Website <input type="text" name="lof_website" tabindex="-1" autocomplete="off"></label></div>
			<div class="lof-form__row">
				<p class="lof-field"><label for="<?php echo esc_attr( $id ); ?>-name"><?php esc_html_e( 'Your name', 'light-of-future-core' ); ?> <span aria-hidden="true">*</span></label><input id="<?php echo esc_attr( $id ); ?>-name" type="text" name="lof_name" required maxlength="120" autocomplete="name"></p>
				<p class="lof-field"><label for="<?php echo esc_attr( $id ); ?>-email"><?php esc_html_e( 'Email', 'light-of-future-core' ); ?> <span aria-hidden="true">*</span></label><input id="<?php echo esc_attr( $id ); ?>-email" type="email" name="lof_email" required maxlength="160" autocomplete="email"></p>
			</div>
			<div class="lof-form__row">
				<p class="lof-field"><label for="<?php echo esc_attr( $id ); ?>-phone"><?php esc_html_e( 'Phone (optional)', 'light-of-future-core' ); ?></label><input id="<?php echo esc_attr( $id ); ?>-phone" type="tel" name="lof_phone" maxlength="30" autocomplete="tel"></p>
				<p class="lof-field"><label for="<?php echo esc_attr( $id ); ?>-type"><?php esc_html_e( 'I am interested in', 'light-of-future-core' ); ?></label>
					<select id="<?php echo esc_attr( $id ); ?>-type" name="lof_type">
						<?php foreach ( $types as $key => $label ) : ?>
							<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $interest, $key ); ?>><?php echo esc_html( $label ); ?></option>
						<?php endforeach; ?>
					</select>
				</p>
			</div>
			<p class="lof-field"><label for="<?php echo esc_attr( $id ); ?>-message"><?php esc_html_e( 'Message', 'light-of-future-core' ); ?> <span aria-hidden="true">*</span></label><textarea id="<?php echo esc_attr( $id ); ?>-message" name="lof_message" rows="5" required maxlength="3000"></textarea></p>
			<p class="lof-field lof-field--check"><label><input type="checkbox" name="lof_consent" value="1" required> <?php esc_html_e( 'I agree that Light of Future Foundation may use these details to reply to me.', 'light-of-future-core' ); ?></label></p>
			<button class="lof-btn lof-btn--earth" type="submit"><?php echo esc_html( $args['button'] ); ?></button>
		</form>
	</div>
	<?php
	return (string) ob_get_clean();
}

/**
 * Shortcode.
 *
 * @param array $atts Attributes.
 * @return string
 */
function lof_core_enquiry_shortcode( $atts ) {
	return lof_core_enquiry_form( shortcode_atts( array( 'interest' => 'general', 'button' => __( 'Send message', 'light-of-future-core' ), 'id' => 'lof-form' ), $atts ) );
}
add_shortcode( 'lof_enquiry_form', 'lof_core_enquiry_shortcode' );

/**
 * Handle a submission.
 */
function lof_core_handle_enquiry() {
	$return = isset( $_POST['lof_return'] ) ? esc_url_raw( wp_unslash( $_POST['lof_return'] ) ) : home_url( '/' );
	$return = wp_validate_redirect( $return, home_url( '/' ) );
	$anchor = isset( $_POST['lof_anchor'] ) ? sanitize_html_class( wp_unslash( $_POST['lof_anchor'] ) ) : 'lof-form';
	$back   = function ( $code ) use ( $return, $anchor ) {
		wp_safe_redirect( add_query_arg( 'lof_form', $code, $return ) . '#' . $anchor );
		exit;
	};

	// Bots: honeypot filled -> pretend success, store nothing.
	if ( ! empty( $_POST['lof_website'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing -- checked below.
		$back( 'sent' );
	}
	if ( ! isset( $_POST['lof_enquiry_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['lof_enquiry_nonce'] ) ), 'lof_enquiry' ) ) {
		$back( 'expired' );
	}
	$ts    = isset( $_POST['lof_ts'] ) ? sanitize_text_field( wp_unslash( $_POST['lof_ts'] ) ) : '';
	$parts = explode( '.', $ts, 2 );
	$time  = (int) $parts[0];
	if ( 2 !== count( $parts ) || ! hash_equals( wp_hash( 'lof-ts|' . $time ), $parts[1] ) || ( time() - $time ) > DAY_IN_SECONDS ) {
		$back( 'expired' );
	}
	if ( ( time() - $time ) < 3 ) { // Faster than a human can type.
		$back( 'sent' );
	}

	$key   = 'lof_rl_' . substr( lof_core_ip_hash(), 0, 32 );
	$count = (int) get_transient( $key );
	if ( $count >= (int) apply_filters( 'lof_core_enquiry_limit', 5 ) ) {
		$back( 'limit' );
	}

	$types   = lof_core_enquiry_types();
	$name    = isset( $_POST['lof_name'] ) ? sanitize_text_field( wp_unslash( $_POST['lof_name'] ) ) : '';
	$email   = isset( $_POST['lof_email'] ) ? sanitize_email( wp_unslash( $_POST['lof_email'] ) ) : '';
	$phone   = isset( $_POST['lof_phone'] ) ? preg_replace( '/[^0-9+ ()-]/', '', sanitize_text_field( wp_unslash( $_POST['lof_phone'] ) ) ) : '';
	$type    = isset( $_POST['lof_type'] ) ? sanitize_key( wp_unslash( $_POST['lof_type'] ) ) : 'general';
	$type    = isset( $types[ $type ] ) ? $type : 'general';
	$message = isset( $_POST['lof_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['lof_message'] ) ) : '';
	$consent = ! empty( $_POST['lof_consent'] );

	if ( '' === $name || ! is_email( $email ) || '' === trim( $message ) || ! $consent || strlen( $name ) > 120 || strlen( $message ) > 3000 ) {
		$back( 'invalid' );
	}
	set_transient( $key, $count + 1, HOUR_IN_SECONDS );

	$id = wp_insert_post(
		array(
			'post_type'    => 'lof_enquiry',
			'post_status'  => 'private',
			/* translators: 1: enquiry type, 2: name */
			'post_title'   => sprintf( __( '%1$s from %2$s', 'light-of-future-core' ), $types[ $type ], $name ),
			'post_content' => $message,
		),
		true
	);
	if ( ! is_wp_error( $id ) ) {
		update_post_meta( $id, 'lof_email', $email );
		update_post_meta( $id, 'lof_phone', $phone );
		update_post_meta( $id, 'lof_type', $type );
		update_post_meta( $id, 'lof_consent_at', gmdate( 'c' ) );
	}

	$to = lof_core_setting( 'email' );
	$to = is_email( $to ) ? $to : get_option( 'admin_email' );
	/* translators: %s: enquiry type */
	$subject = sprintf( __( 'New website enquiry: %s', 'light-of-future-core' ), $types[ $type ] );
	$body    = sprintf( "%s: %s\n%s: %s\n%s: %s\n%s: %s\n\n%s\n\n%s", __( 'Name', 'light-of-future-core' ), $name, __( 'Email', 'light-of-future-core' ), $email, __( 'Phone', 'light-of-future-core' ), $phone ? $phone : '-', __( 'Interest', 'light-of-future-core' ), $types[ $type ], $message, admin_url( 'edit.php?post_type=lof_enquiry' ) );
	wp_mail( $to, $subject, $body, array( 'Reply-To: ' . $name . ' <' . $email . '>' ) );

	$back( 'sent' );
}
add_action( 'admin_post_nopriv_lof_enquiry', 'lof_core_handle_enquiry' );
add_action( 'admin_post_lof_enquiry', 'lof_core_handle_enquiry' );

/**
 * Show contact details on the enquiry edit screen.
 */
function lof_core_enquiry_meta_box() {
	add_meta_box(
		'lof_enquiry_details',
		__( 'Contact details', 'light-of-future-core' ),
		function ( $post ) {
			$types = lof_core_enquiry_types();
			$type  = (string) get_post_meta( $post->ID, 'lof_type', true );
			$email = (string) get_post_meta( $post->ID, 'lof_email', true );
			echo '<p><strong>' . esc_html__( 'Email', 'light-of-future-core' ) . ':</strong> <a href="' . esc_url( 'mailto:' . $email ) . '">' . esc_html( $email ) . '</a></p>';
			echo '<p><strong>' . esc_html__( 'Phone', 'light-of-future-core' ) . ':</strong> ' . esc_html( (string) get_post_meta( $post->ID, 'lof_phone', true ) ) . '</p>';
			echo '<p><strong>' . esc_html__( 'Interest', 'light-of-future-core' ) . ':</strong> ' . esc_html( isset( $types[ $type ] ) ? $types[ $type ] : $type ) . '</p>';
			echo '<p><strong>' . esc_html__( 'Consent given', 'light-of-future-core' ) . ':</strong> ' . esc_html( (string) get_post_meta( $post->ID, 'lof_consent_at', true ) ) . '</p>';
		},
		'lof_enquiry',
		'side'
	);
}
add_action( 'add_meta_boxes', 'lof_core_enquiry_meta_box' );
