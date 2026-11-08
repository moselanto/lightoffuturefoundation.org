<?php
/**
 * 0.63.7 one-off upgrade: business empowerment stories (Rosemary and Hellen).
 * Stories are saved as drafts first, consent is recorded, then they are
 * published, so the consent safeguard in the core plugin is respected.
 * Content and photos supplied by Francis Ouma Sikuku (8 November 2026),
 * publication rights confirmed. Photos crop out children. Runs once in wp-admin.
 *
 * @package Light_Of_Future
 */

defined( 'ABSPATH' ) || exit;

/**
 * Create or refresh one story, then publish it.
 *
 * @param array $s Story fields.
 * @return int
 */
function lof_0637_story( $s ) {
	$data  = array(
		'post_type'    => 'lof_story',
		'post_status'  => 'draft',
		'post_name'    => $s['slug'],
		'post_title'   => $s['title'],
		'post_excerpt' => $s['excerpt'],
		'post_content' => $s['content'],
	);
	$found = get_page_by_path( $s['slug'], OBJECT, 'lof_story' );
	if ( $found ) {
		$data['ID'] = $found->ID;
		$id         = wp_update_post( wp_slash( $data ) );
	} else {
		$id = wp_insert_post( wp_slash( $data ) );
	}
	if ( ! $id || is_wp_error( $id ) ) {
		return 0;
	}
	if ( ! empty( $s['thumb'] ) ) {
		set_post_thumbnail( $id, $s['thumb'] );
	}
	update_post_meta( $id, 'lof_display_name', $s['name'] );
	update_post_meta( $id, 'lof_location', $s['location'] );
	update_post_meta( $id, 'lof_consent', '1' );
	update_post_meta( $id, 'lof_consent_date', '2026-11-08' );
	if ( taxonomy_exists( 'lof_story_topic' ) ) {
		wp_set_object_terms( $id, __( 'Business empowerment', 'light-of-future' ), 'lof_story_topic', true );
	}
	wp_update_post(
		array(
			'ID'          => $id,
			'post_status' => 'publish',
		)
	);
	return (int) $id;
}

/**
 * Publish Rosemary's and Hellen's stories.
 */
function lof_upgrade_0637_stories() {
	if ( (int) get_option( 'lof_stories_0637_rev', 0 ) >= 1 || ! current_user_can( 'manage_options' ) || ! function_exists( 'lof_0636_photo' ) ) {
		return;
	}
	$p   = static function ( $t ) {
		return '<p>' . esc_html( $t ) . '</p>';
	};
	$btn = static function ( $url, $label ) {
		return '<p><a class="lof-btn lof-btn--sun" href="' . esc_url( $url ) . '" target="_blank" rel="noopener">' . esc_html( $label ) . '</a></p>';
	};

	// Rosemary.
	$stall    = lof_0636_photo( 'story-rosemary-fruit-stall', __( 'Rosemary at her fruit and vegetable stall in Dandora', 'light-of-future' ), __( 'Rosemary at her new fruit and vegetable stall.', 'light-of-future' ) );
	$portrait = lof_0636_photo( 'story-rosemary-portrait', __( 'Portrait of Rosemary Atieno Otieno', 'light-of-future' ), __( 'Rosemary Atieno Otieno, Dandora.', 'light-of-future' ) );
	$c        = $p( __( 'Rosemary Atieno Otieno is a widow living in Dandora, Nairobi. We met her during one of our food donations in Dandora, through our community coordinator Edith Atieno.', 'light-of-future' ) );
	$c       .= $p( __( 'Rosemary shared with us the pain she has been going through. A road accident left her shoulder badly injured, and before that she had an operation on her stomach that did not go well. Although she healed, she has continued to live with a lot of pain.', 'light-of-future' ) );
	if ( $portrait ) {
		$c .= '<figure class="wp-block-image size-large">' . wp_get_attachment_image( $portrait, 'large', false, array( 'loading' => 'lazy' ) ) . '<figcaption>' . esc_html__( 'Rosemary Atieno Otieno, Dandora.', 'light-of-future' ) . '</figcaption></figure>';
	}
	$c .= $p( __( 'We decided to step in. Through our business empowerment programme, we supported Rosemary with money to start a business selling fruits, onions and vegetables, so she can earn an income of her own. Rosemary is the first beneficiary of the programme.', 'light-of-future' ) );
	$c .= $btn( 'https://drive.google.com/drive/folders/1YrRZ7bPE0EpoMOfXDBu_vgSCDNiqbdWH?usp=sharing', __( 'Watch Rosemary\'s video', 'light-of-future' ) );
	lof_0637_story(
		array(
			'slug'     => 'rosemary',
			'title'    => __( 'Rosemary: a new start with her own fruit and vegetable stall', 'light-of-future' ),
			'excerpt'  => __( 'Rosemary, a widow in Dandora, is the first beneficiary of our business empowerment programme.', 'light-of-future' ),
			'content'  => $c,
			'thumb'    => $stall,
			'name'     => 'Rosemary',
			'location' => __( 'Dandora, Nairobi', 'light-of-future' ),
		)
	);

	// Hellen.
	$fund = lof_0636_photo( 'story-hellen-startup-fund', __( 'Hellen Adhiambo receiving her business start-up fund from the Light of Future team', 'light-of-future' ), __( 'Hellen receives her start-up fund for a fish business.', 'light-of-future' ) );
	$h    = $p( __( 'Hellen Adhiambo is a single mother of five children living in Dandora, Nairobi. We met Hellen during one of our food donations in the area.', 'light-of-future' ) );
	$h   .= $p( __( 'We found out that Hellen suffers from arthritis. She cannot walk long distances, even to get to a job that could support her family, and they have been going through many challenges.', 'light-of-future' ) );
	$h   .= $p( __( 'Together with our team, we decided to step in and support Hellen with a business start-up fund to begin selling fish, so that she can stand on her own, feed her family and pay her own bills.', 'light-of-future' ) );
	$h   .= $btn( 'https://drive.google.com/file/d/1De9emUGCE3xLgy90wXR3-1GtJkgfqAZJ/view?usp=drive_link', __( 'Watch Hellen\'s video', 'light-of-future' ) );
	lof_0637_story(
		array(
			'slug'     => 'hellen',
			'title'    => __( 'Hellen: a fish business to support her family', 'light-of-future' ),
			'excerpt'  => __( 'Hellen, a single mother of five in Dandora living with arthritis, received a start-up fund to begin selling fish.', 'light-of-future' ),
			'content'  => $h,
			'thumb'    => $fund,
			'name'     => 'Hellen',
			'location' => __( 'Dandora, Nairobi', 'light-of-future' ),
		)
	);

	if ( function_exists( 'litespeed_purge_all' ) ) {
		litespeed_purge_all();
	}
	do_action( 'litespeed_purge_all' );
	update_option( 'lof_stories_0637_rev', 1, false );
}
add_action( 'admin_init', 'lof_upgrade_0637_stories', 41 );
