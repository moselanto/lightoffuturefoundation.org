<?php
/**
 * Starter content from the foundation's previous website (lightoffuture.org),
 * confirmed by the foundation in October 2026. Called by the theme's setup
 * screen. Safe to run again: items are matched by slug and never changed or
 * duplicated once they exist.
 *
 * @package LightOfFutureCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Starter items: post type => list of [slug, title, excerpt, content, meta, order].
 *
 * @return array
 */
function lof_core_seed_data() {
	$source = __( 'Foundation records, confirmed October 2026', 'light-of-future-core' );
	return array(
		'lof_programme' => array(
			array( 'education-support', __( 'Education Support', 'light-of-future-core' ), __( 'Helping vulnerable children and orphans access education and school resources.', 'light-of-future-core' ), __( 'We help vulnerable children and orphans stay in school by providing school fees, uniforms, shoes and school resources, because children are the future leaders of tomorrow.', 'light-of-future-core' ), array( 'lof_beneficiary' => __( 'Vulnerable children and orphans', 'light-of-future-core' ), 'lof_status' => __( 'Ongoing', 'light-of-future-core' ) ), 1 ),
			array( 'community-empowerment', __( 'Community Empowerment', 'light-of-future-core' ), __( 'Supporting widows and struggling families with practical assistance.', 'light-of-future-core' ), __( 'We stand with widows, widowers and struggling families through practical assistance, from building safe homes to covering urgent medical care.', 'light-of-future-core' ), array( 'lof_beneficiary' => __( 'Widows, widowers and struggling families', 'light-of-future-core' ), 'lof_status' => __( 'Ongoing', 'light-of-future-core' ) ), 2 ),
			array( 'clean-water', __( 'Clean Water Projects', 'light-of-future-core' ), __( 'Improving access to safe drinking water for local communities.', 'light-of-future-core' ), __( 'We work to improve access to safe drinking water for local communities, so families spend less time fetching water and stay healthier.', 'light-of-future-core' ), array( 'lof_beneficiary' => __( 'Local communities', 'light-of-future-core' ), 'lof_status' => __( 'Ongoing', 'light-of-future-core' ) ), 3 ),
			array( 'talent-development', __( 'Talent Development', 'light-of-future-core' ), __( 'Encouraging youth talents through sports, music and creative arts.', 'light-of-future-core' ), __( 'We encourage young people to discover and grow their talents through sports, music and creative arts.', 'light-of-future-core' ), array( 'lof_beneficiary' => __( 'Young people', 'light-of-future-core' ), 'lof_status' => __( 'Ongoing', 'light-of-future-core' ) ), 4 ),
			array( 'prison-outreach', __( 'Prison Outreach', 'light-of-future-core' ), __( 'Providing encouragement and guidance through prison visits.', 'light-of-future-core' ), __( 'Through prison visits we offer encouragement, guidance and hope.', 'light-of-future-core' ), array( 'lof_status' => __( 'Ongoing', 'light-of-future-core' ) ), 5 ),
		),
		'lof_story'     => array(
			array( 'philister', __( 'Philister: a safe home at 85', 'light-of-future-core' ), __( 'After more than 45 years in a grass-thatched house, Philister now lives in a safe and secure home.', 'light-of-future-core' ), '<p>' . __( 'Philister is a widow from Inungo village whose husband passed away in 1979. Since then, she has faced many years of hardship, struggling to survive on her own.', 'light-of-future-core' ) . '</p><p>' . __( 'When we met her in 2023, she had been living in a grass-thatched house for over 45 years, often suffering from illness due to the poor conditions.', 'light-of-future-core' ) . '</p><p>' . __( 'With the support of generous donors, we were able to build her a safe and secure home at the age of 85, greatly improving her quality of life.', 'light-of-future-core' ) . '</p>', array( 'lof_display_name' => 'Philister', 'lof_location' => __( 'Inungo village', 'light-of-future-core' ), 'lof_consent' => '1' ), 1 ),
			array( 'elijah', __( 'Elijah: healing and a new home', 'light-of-future-core' ), __( 'Found in a critical condition in 2023, Elijah has recovered and lives in a safe, stable home.', 'light-of-future-core' ), '<p>' . __( 'Elijah is a widower whose wife passed away in 2016. After her death, he faced many challenges living alone and struggled to cope with daily life.', 'light-of-future-core' ) . '</p><p>' . __( 'We met Elijah in December 2023 in a critical condition. He had fallen into a coma, and we immediately arranged for him to be taken to hospital for urgent treatment.', 'light-of-future-core' ) . '</p><p>' . __( 'With the support of our online donors, we raised funds to cover his medical bills and build him a safe and stable home. Doctors later confirmed that Elijah was suffering from epilepsy and other health conditions, which had previously caused serious injuries, including burns to his leg.', 'light-of-future-core' ) . '</p><p>' . __( 'We thank God that Elijah has recovered and returned to his normal daily activities. We are especially grateful to all who supported this effort, including our main supporter, Kristen.', 'light-of-future-core' ) . '</p>', array( 'lof_display_name' => 'Elijah', 'lof_consent' => '1' ), 2 ),
			array( 'precious', __( 'Precious: staying in school', 'light-of-future-core' ), __( 'School fees, uniforms and shoes helped Precious stay in school with confidence.', 'light-of-future-core' ), '<p>' . __( 'Precious is a young girl living in Nairobi with her guardian. Life has been challenging, especially during periods of severe flooding, which made daily survival even more difficult.', 'light-of-future-core' ) . '</p><p>' . __( 'During this time, her guardian struggled to provide basic needs, including education. We stepped in to help Precious continue her schooling by providing school fees, uniforms and shoes.', 'light-of-future-core' ) . '</p><p>' . __( 'With this support, Precious has been able to stay in school and continue her education with confidence. We believe in supporting children\'s education, as they are the future leaders of tomorrow.', 'light-of-future-core' ) . '</p>', array( 'lof_display_name' => 'Precious', 'lof_location' => __( 'Nairobi', 'light-of-future-core' ), 'lof_consent' => '1' ), 3 ),
		),
		'lof_leader'    => array(
			array( 'francis-ouma-sikuku', 'Francis Ouma Sikuku', '', __( 'Musician, plumber, choir teacher and counsellor from Inungo village, Francis has served widows and families in his community since childhood and founded Light of Future Foundation.', 'light-of-future-core' ), array( 'lof_role' => __( 'Founder', 'light-of-future-core' ), 'lof_approved' => '1' ), 1 ),
			array( 'nixon-omondo-owino', 'Nixon Omondo Owino', '', '', array( 'lof_role' => __( 'Vice Chairman', 'light-of-future-core' ), 'lof_approved' => '1' ), 2 ),
			array( 'vincent-okoth-okallo', 'Vincent Okoth Okallo', '', '', array( 'lof_role' => __( 'Secretary', 'light-of-future-core' ), 'lof_approved' => '1' ), 3 ),
			array( 'kennedy-oyamo-mumbo', 'Kennedy Oyamo Mumbo', '', '', array( 'lof_role' => __( 'Treasurer', 'light-of-future-core' ), 'lof_approved' => '1' ), 4 ),
		),
		'lof_metric'    => array(
			array( 'children-supported', __( 'Children supported', 'light-of-future-core' ), '', '', array( 'lof_value' => '200', 'lof_suffix' => '+', 'lof_source' => $source, 'lof_verified' => '2026-10-30', 'lof_visible' => '1' ), 1 ),
			array( 'widows-assisted', __( 'Widows assisted', 'light-of-future-core' ), '', '', array( 'lof_value' => '30', 'lof_suffix' => '+', 'lof_source' => $source, 'lof_verified' => '2026-10-30', 'lof_visible' => '1' ), 2 ),
			array( 'community-programmes', __( 'Community programmes', 'light-of-future-core' ), '', '', array( 'lof_value' => '5', 'lof_suffix' => '', 'lof_source' => $source, 'lof_verified' => '2026-10-30', 'lof_visible' => '1' ), 3 ),
			array( 'officially-registered', __( 'Officially registered', 'light-of-future-core' ), '', '', array( 'lof_value' => '2025', 'lof_suffix' => '', 'lof_source' => __( 'NGO Board certificate, May 2025', 'light-of-future-core' ), 'lof_verified' => '2026-10-30', 'lof_visible' => '1' ), 4 ),
		),
	);
}

/**
 * Create starter items that do not exist yet.
 *
 * Items are first saved as drafts with their fields, then published, so the
 * story consent safeguard sees the recorded consent.
 *
 * @return string[] Report lines.
 */
function lof_core_seed_content() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return array();
	}
	lof_core_register_post_types();
	$created = 0;
	foreach ( lof_core_seed_data() as $type => $items ) {
		foreach ( $items as $item ) {
			list( $slug, $title, $excerpt, $content, $meta, $order ) = $item;
			$found = get_posts( array( 'post_type' => $type, 'name' => $slug, 'post_status' => 'any', 'posts_per_page' => 1, 'fields' => 'ids' ) );
			if ( $found || get_option( 'lof_seeded_' . $type . '_' . $slug ) ) {
				continue;
			}
			$id = wp_insert_post(
				array(
					'post_type'    => $type,
					'post_status'  => 'draft',
					'post_title'   => $title,
					'post_name'    => $slug,
					'post_excerpt' => $excerpt,
					'post_content' => $content,
					'menu_order'   => (int) $order,
				),
				true
			);
			if ( is_wp_error( $id ) ) {
				continue;
			}
			foreach ( $meta as $key => $value ) {
				update_post_meta( $id, $key, $value );
			}
			wp_update_post( array( 'ID' => $id, 'post_status' => 'publish', 'post_name' => $slug ) );
			update_option( 'lof_seeded_' . $type . '_' . $slug, 1, false );
			++$created;
		}
	}
	/* translators: %d: number of items */
	return array( sprintf( _n( '%d starter item added (programmes, stories, leaders, impact figures).', '%d starter items added (programmes, stories, leaders, impact figures).', $created, 'light-of-future-core' ), $created ) );
}
