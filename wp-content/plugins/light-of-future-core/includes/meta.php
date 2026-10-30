<?php
/**
 * Structured fields for each content type, with consent and verification
 * safeguards. Fields are registered as post meta (REST-visible only where
 * safe) and edited in a simple meta box.
 *
 * @package LightOfFutureCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Field map: post type => [ key => [label, type, public] ].
 * Non-public fields (consent records) are never exposed through the REST API.
 *
 * @return array
 */
function lof_core_meta_fields() {
	return array(
		'lof_programme' => array(
			'lof_need'        => array( __( 'The need', 'light-of-future-core' ), 'textarea', true ),
			'lof_activities'  => array( __( 'Activities', 'light-of-future-core' ), 'textarea', true ),
			'lof_beneficiary' => array( __( 'Who it serves', 'light-of-future-core' ), 'text', true ),
			'lof_location'    => array( __( 'Area', 'light-of-future-core' ), 'text', true ),
			'lof_status'      => array( __( 'Status (ongoing / completed / planned)', 'light-of-future-core' ), 'text', true ),
		),
		'lof_project'   => array(
			'lof_status'       => array( __( 'Status (ongoing / completed / planned)', 'light-of-future-core' ), 'text', true ),
			'lof_programme'    => array( __( 'Related programme (title)', 'light-of-future-core' ), 'text', true ),
			'lof_location'     => array( __( 'Location', 'light-of-future-core' ), 'text', true ),
			'lof_dates'        => array( __( 'Timeline', 'light-of-future-core' ), 'text', true ),
			'lof_funding_need' => array( __( 'Funding need (only if verified)', 'light-of-future-core' ), 'text', true ),
		),
		'lof_story'     => array(
			'lof_quote'         => array( __( 'Approved quote', 'light-of-future-core' ), 'textarea', true ),
			'lof_display_name'  => array( __( 'Name to show (use a pseudonym for children and vulnerable people)', 'light-of-future-core' ), 'text', true ),
			'lof_location'      => array( __( 'General location (village or area only)', 'light-of-future-core' ), 'text', true ),
			'lof_consent'       => array( __( 'Written consent received (guardian consent for minors)', 'light-of-future-core' ), 'checkbox', false ),
			'lof_consent_date'  => array( __( 'Consent date (private)', 'light-of-future-core' ), 'text', false ),
		),
		'lof_leader'    => array(
			'lof_role'     => array( __( 'Role / title', 'light-of-future-core' ), 'text', true ),
			'lof_group'    => array( __( 'Group: executive, board or team', 'light-of-future-core' ), 'text', true ),
			'lof_approved' => array( __( 'Approved for publication', 'light-of-future-core' ), 'checkbox', true ),
		),
		'lof_partner'   => array(
			'lof_url'      => array( __( 'Website', 'light-of-future-core' ), 'url', true ),
			'lof_approved' => array( __( 'Logo use approved', 'light-of-future-core' ), 'checkbox', true ),
		),
		'lof_resource'  => array(
			'lof_file_url' => array( __( 'File link (upload to Media, paste URL)', 'light-of-future-core' ), 'url', true ),
			'lof_year'     => array( __( 'Year', 'light-of-future-core' ), 'text', true ),
		),
		'lof_metric'    => array(
			'lof_value'    => array( __( 'Value (number)', 'light-of-future-core' ), 'text', true ),
			'lof_suffix'   => array( __( 'Suffix, e.g. + or %', 'light-of-future-core' ), 'text', true ),
			'lof_period'   => array( __( 'Period, e.g. 2023-2025', 'light-of-future-core' ), 'text', true ),
			'lof_source'   => array( __( 'Source / evidence', 'light-of-future-core' ), 'text', true ),
			'lof_verified' => array( __( 'Last verified date', 'light-of-future-core' ), 'text', true ),
			'lof_visible'  => array( __( 'Verified - show on website', 'light-of-future-core' ), 'checkbox', true ),
		),
	);
}

/**
 * Register meta so Elementor dynamic tags and the REST API can read it.
 */
function lof_core_register_meta() {
	foreach ( lof_core_meta_fields() as $type => $fields ) {
		foreach ( $fields as $key => $field ) {
			register_post_meta(
				$type,
				$key,
				array(
					'type'          => 'checkbox' === $field[1] ? 'boolean' : 'string',
					'single'        => true,
					'show_in_rest'  => (bool) $field[2],
					'auth_callback' => function () {
						return current_user_can( 'edit_posts' );
					},
				)
			);
		}
	}
}
add_action( 'init', 'lof_core_register_meta', 20 );

/**
 * Add the details meta box.
 */
function lof_core_add_meta_boxes() {
	foreach ( array_keys( lof_core_meta_fields() ) as $type ) {
		add_meta_box( 'lof_core_details', __( 'Details', 'light-of-future-core' ), 'lof_core_render_meta_box', $type, 'normal', 'high' );
	}
}
add_action( 'add_meta_boxes', 'lof_core_add_meta_boxes' );

/**
 * Render the meta box.
 *
 * @param WP_Post $post Post.
 */
function lof_core_render_meta_box( $post ) {
	$fields = lof_core_meta_fields();
	if ( empty( $fields[ $post->post_type ] ) ) {
		return;
	}
	wp_nonce_field( 'lof_core_meta', 'lof_core_meta_nonce' );
	echo '<table class="form-table" role="presentation">';
	foreach ( $fields[ $post->post_type ] as $key => $field ) {
		$value = get_post_meta( $post->ID, $key, true );
		echo '<tr><th scope="row"><label for="' . esc_attr( $key ) . '">' . esc_html( $field[0] ) . '</label></th><td>';
		if ( 'textarea' === $field[1] ) {
			echo '<textarea class="large-text" rows="3" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '">' . esc_textarea( (string) $value ) . '</textarea>';
		} elseif ( 'checkbox' === $field[1] ) {
			echo '<input type="checkbox" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="1" ' . checked( (bool) $value, true, false ) . '>';
		} else {
			echo '<input class="regular-text" type="' . esc_attr( 'url' === $field[1] ? 'url' : 'text' ) . '" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( (string) $value ) . '">';
		}
		echo '</td></tr>';
	}
	echo '</table>';
}

/**
 * Save meta with nonce and capability checks.
 *
 * @param int $post_id Post ID.
 */
function lof_core_save_meta( $post_id ) {
	if ( ! isset( $_POST['lof_core_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['lof_core_meta_nonce'] ) ), 'lof_core_meta' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$type   = get_post_type( $post_id );
	$fields = lof_core_meta_fields();
	if ( empty( $fields[ $type ] ) ) {
		return;
	}
	foreach ( $fields[ $type ] as $key => $field ) {
		$raw = isset( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitised below by type.
		switch ( $field[1] ) {
			case 'checkbox':
				update_post_meta( $post_id, $key, '1' === $raw );
				break;
			case 'url':
				update_post_meta( $post_id, $key, esc_url_raw( $raw ) );
				break;
			case 'textarea':
				update_post_meta( $post_id, $key, sanitize_textarea_field( $raw ) );
				break;
			default:
				update_post_meta( $post_id, $key, sanitize_text_field( $raw ) );
		}
	}
}
add_action( 'save_post', 'lof_core_save_meta' );

/**
 * Safeguard: an impact story cannot be published until consent is recorded.
 *
 * @param array $data    Post data.
 * @param array $postarr Raw post array.
 * @return array
 */
function lof_core_require_story_consent( $data, $postarr ) {
	if ( 'lof_story' !== $data['post_type'] || 'publish' !== $data['post_status'] ) {
		return $data;
	}
	$id      = isset( $postarr['ID'] ) ? (int) $postarr['ID'] : 0;
	$posted  = isset( $_POST['lof_core_meta_nonce'] ) ? ! empty( $_POST['lof_consent'] ) : null; // phpcs:ignore WordPress.Security.NonceVerification.Missing -- verified in lof_core_save_meta.
	$consent = null === $posted ? (bool) get_post_meta( $id, 'lof_consent', true ) : $posted;
	if ( ! $consent && apply_filters( 'lof_core_require_story_consent', true ) ) {
		$data['post_status'] = 'draft';
	}
	return $data;
}
add_filter( 'wp_insert_post_data', 'lof_core_require_story_consent', 10, 2 );

/**
 * Verified, visible impact figures for widgets. Unverified ones never render.
 *
 * @return array<int,array{label:string,value:string,suffix:string,period:string,source:string}>
 */
function lof_core_get_metrics() {
	$posts = get_posts(
		array(
			'post_type'      => 'lof_metric',
			'post_status'    => 'publish',
			'posts_per_page' => 12,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'meta_key'       => 'lof_visible', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'meta_value'     => '1', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
		)
	);
	$out = array();
	foreach ( $posts as $p ) {
		$value = (string) get_post_meta( $p->ID, 'lof_value', true );
		if ( '' === $value ) {
			continue;
		}
		$out[] = array(
			'label'  => get_the_title( $p ),
			'value'  => $value,
			'suffix' => (string) get_post_meta( $p->ID, 'lof_suffix', true ),
			'period' => (string) get_post_meta( $p->ID, 'lof_period', true ),
			'source' => (string) get_post_meta( $p->ID, 'lof_source', true ),
		);
	}
	return $out;
}
