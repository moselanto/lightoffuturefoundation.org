<?php
/**
 * Starter content supplied by Light of Future Foundation (content document,
 * October 2026, plus the previous website). Called by the theme's setup and
 * "Apply latest design" buttons.
 *
 * Safe to run again: new items are created once (matched by slug). When an
 * item's revision number goes up, it is refreshed only if nobody has edited
 * it since setup created it; edited items are never overwritten.
 *
 * @package LightOfFutureCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Starter items: post type => list of [slug, title, excerpt, content, meta, order, revision].
 *
 * @return array
 */
function lof_core_seed_data() {
	$source = __( 'Foundation records, confirmed October 2026', 'light-of-future-core' );
	$p      = function ( $text ) {
		return '<p>' . $text . '</p>';
	};
	return array(
		'lof_programme' => array(
			array( 'decent-houses', __( 'Decent Houses for Widows, Widowers and Orphans', 'light-of-future-core' ), __( 'Building decent, safe houses for widows, widowers and total orphans.', 'light-of-future-core' ), $p( __( 'We improve the living standards of widows, widowers and orphans by building them decent houses. Philister, who lived in a grass-thatched house for over 45 years, and Elijah both now live in safe homes built with the help of our donors.', 'light-of-future-core' ) ), array( 'lof_beneficiary' => __( 'Widows, widowers and total orphans', 'light-of-future-core' ), 'lof_status' => __( 'Ongoing', 'light-of-future-core' ) ), 1, 2 ),
			array( 'hygiene-kits', __( 'Hygiene Kits for Adolescent Girls', 'light-of-future-core' ), __( 'Supporting adolescent girls from vulnerable families with hygiene kits such as sanitary pads.', 'light-of-future-core' ), $p( __( 'We support adolescent girls from vulnerable families with hygiene kits, such as sanitary pads, so that they can learn and live with dignity.', 'light-of-future-core' ) ), array( 'lof_beneficiary' => __( 'Adolescent girls from vulnerable families', 'light-of-future-core' ), 'lof_status' => __( 'Ongoing', 'light-of-future-core' ) ), 2, 2 ),
			array( 'education-support', __( 'Education Support', 'light-of-future-core' ), __( 'Mobilising resources and creating educational opportunities for vulnerable children.', 'light-of-future-core' ), $p( __( 'We mobilise resources and establish educational opportunities for vulnerable children, from school fees to uniforms and shoes, because children are the future leaders of tomorrow.', 'light-of-future-core' ) ), array( 'lof_beneficiary' => __( 'Vulnerable children and orphans', 'light-of-future-core' ), 'lof_status' => __( 'Ongoing', 'light-of-future-core' ) ), 3, 2 ),
			array( 'clean-water', __( 'Clean Water and Sanitation', 'light-of-future-core' ), __( 'Supporting water and sanitation projects in the community.', 'light-of-future-core' ), $p( __( 'We support water and sanitation projects that improve access to safe drinking water for local communities.', 'light-of-future-core' ) ), array( 'lof_beneficiary' => __( 'Local communities', 'light-of-future-core' ), 'lof_status' => __( 'Ongoing', 'light-of-future-core' ) ), 4, 2 ),
			array( 'talent-development', __( 'Talent Development', 'light-of-future-core' ), __( 'Talent search through music, dance, drama, art and comedy, with platforms to shine.', 'light-of-future-core' ), $p( __( 'We conduct talent searches through music, dance, drama, art work and comedy, and create platforms where young people can grow and showcase their gifts.', 'light-of-future-core' ) ), array( 'lof_beneficiary' => __( 'Young people', 'light-of-future-core' ), 'lof_status' => __( 'Ongoing', 'light-of-future-core' ) ), 5, 2 ),
			array( 'hand-skills', __( 'Hand Skills Development', 'light-of-future-core' ), __( 'Training in practical hand skills that build self-reliance.', 'light-of-future-core' ), $p( __( 'Through hand skills training we equip individuals and families with practical skills, tools and confidence to shape their own futures.', 'light-of-future-core' ) ), array( 'lof_status' => __( 'Ongoing', 'light-of-future-core' ) ), 6, 2 ),
			array( 'community-empowerment', __( 'Community Empowerment through Business Start-ups', 'light-of-future-core' ), __( 'Trainings and business start-ups that help families earn a living and move out of poverty.', 'light-of-future-core' ), $p( __( 'We empower communities economically by conducting trainings and giving business start-ups to widows, widowers and vulnerable families, to eradicate poverty and bring long-lasting change.', 'light-of-future-core' ) ), array( 'lof_beneficiary' => __( 'Widows, widowers and vulnerable families', 'light-of-future-core' ), 'lof_status' => __( 'Ongoing', 'light-of-future-core' ) ), 7, 2 ),
			array( 'health-medical-support', __( 'Health Search and Medical Support', 'light-of-future-core' ), __( 'Health checks and medical support for vulnerable people in our communities.', 'light-of-future-core' ), $p( __( 'We help vulnerable people get the medical care they need. When we found Elijah in a coma in December 2023, we arranged urgent hospital treatment and, with our online donors, covered his medical bills.', 'light-of-future-core' ) ), array( 'lof_status' => __( 'Ongoing', 'light-of-future-core' ) ), 8, 2 ),
			array( 'gbv-awareness', __( 'Domestic and Gender-Based Violence Awareness', 'light-of-future-core' ), __( 'Awareness on domestic and gender-based violence, and helping people know their rights.', 'light-of-future-core' ), $p( __( 'We raise awareness on domestic violence and gender-based violence, and help vulnerable people in the community know their rights and when to apply them.', 'light-of-future-core' ) ), array( 'lof_status' => __( 'Ongoing', 'light-of-future-core' ) ), 9, 2 ),
			array( 'prison-outreach', __( 'Prison Outreach', 'light-of-future-core' ), __( 'Providing encouragement and guidance through prison visits.', 'light-of-future-core' ), $p( __( 'Through prison outreach visits we offer encouragement, guidance and hope.', 'light-of-future-core' ) ), array( 'lof_status' => __( 'Ongoing', 'light-of-future-core' ) ), 10, 2 ),
			array( 'food-donations', __( 'Food Donations', 'light-of-future-core' ), __( 'Food for vulnerable children and the elderly in our communities.', 'light-of-future-core' ), $p( __( 'We donate food to vulnerable children and elderly people in the communities we serve.', 'light-of-future-core' ) ), array( 'lof_beneficiary' => __( 'Vulnerable children and the elderly', 'light-of-future-core' ), 'lof_status' => __( 'Ongoing', 'light-of-future-core' ) ), 11, 2 ),
		),
		'lof_story'     => array(
			array( 'philister', __( 'Philister: a safe home at 85', 'light-of-future-core' ), __( 'After more than 45 years in a grass-thatched house, Philister now lives in a safe and secure home.', 'light-of-future-core' ), $p( __( 'Philister is a widow from Inungo village whose husband passed away in 1979. Since then, she has faced many years of hardship, struggling to survive on her own.', 'light-of-future-core' ) ) . $p( __( 'When we met her in 2023, she had been living in a grass-thatched house for over 45 years, often suffering from illness due to the poor conditions.', 'light-of-future-core' ) ) . $p( __( 'With the support of generous donors, we were able to build her a safe and secure home at the age of 85, greatly improving her quality of life.', 'light-of-future-core' ) ), array( 'lof_display_name' => 'Philister', 'lof_location' => __( 'Inungo village', 'light-of-future-core' ), 'lof_consent' => '1' ), 1, 1 ),
			array( 'elijah', __( 'Elijah: healing and a new home', 'light-of-future-core' ), __( 'Found in a critical condition in 2023, Elijah has recovered and lives in a safe, stable home.', 'light-of-future-core' ), $p( __( 'Elijah is a widower whose wife passed away in 2016. After her death, he faced many challenges living alone and struggled to cope with daily life.', 'light-of-future-core' ) ) . $p( __( 'We met Elijah in December 2023 in a critical condition. He had fallen into a coma, and we immediately arranged for him to be taken to hospital for urgent treatment.', 'light-of-future-core' ) ) . $p( __( 'With the support of our online donors, we raised funds to cover his medical bills and build him a safe and stable home. Doctors later confirmed that Elijah was suffering from epilepsy and other health conditions, which had previously caused serious injuries, including burns to his leg.', 'light-of-future-core' ) ) . $p( __( 'We thank God that Elijah has recovered and returned to his normal daily activities. We are especially grateful to all who supported this effort, including our main supporter, Kristen.', 'light-of-future-core' ) ), array( 'lof_display_name' => 'Elijah', 'lof_consent' => '1' ), 2, 1 ),
			array( 'precious', __( 'Precious: staying in school', 'light-of-future-core' ), __( 'School fees, uniforms and shoes helped Precious stay in school with confidence.', 'light-of-future-core' ), $p( __( 'Precious is a young girl living in Nairobi with her guardian. Life has been challenging, especially during periods of severe flooding, which made daily survival even more difficult.', 'light-of-future-core' ) ) . $p( __( 'During this time, her guardian struggled to provide basic needs, including education. We stepped in to help Precious continue her schooling by providing school fees, uniforms and shoes.', 'light-of-future-core' ) ) . $p( __( 'With this support, Precious has been able to stay in school and continue her education with confidence. We believe in supporting children\'s education, as they are the future leaders of tomorrow.', 'light-of-future-core' ) ), array( 'lof_display_name' => 'Precious', 'lof_location' => __( 'Nairobi', 'light-of-future-core' ), 'lof_consent' => '1' ), 3, 1 ),
		),
		'lof_leader'    => array(
			array( 'francis-ouma-sikuku', 'Francis Ouma Sikuku', __( 'Board of Directors: Chairman', 'light-of-future-core' ), __( 'Provides overall leadership and strategic direction for the foundation, coordinates organisational development, oversees programme implementation, develops partnerships and ensures that the foundation remains focused on its mission and community impact.', 'light-of-future-core' ), array( 'lof_role' => __( 'Founder, Executive Director & Chairman', 'light-of-future-core' ), 'lof_group' => 'executive', 'lof_approved' => '1' ), 1, 4 ),
			array( 'nixon-omondo-owino', 'Nixon Omondo Owino', __( 'Board of Directors: Vice Chairperson', 'light-of-future-core' ), __( 'Oversees day-to-day operations, coordination, logistics and organisational support, helping to ensure that programmes and activities are implemented efficiently and effectively.', 'light-of-future-core' ), array( 'lof_role' => __( 'General Operations Manager & Chief Adviser', 'light-of-future-core' ), 'lof_group' => 'board', 'lof_approved' => '1' ), 2, 4 ),
			array( 'vincent-okoth-okallo', 'Vincent Okoth Okallo', __( 'Board of Directors: Secretary', 'light-of-future-core' ), __( 'Supports the foundation\'s administrative functions and direction, including budgeting, general record keeping and administrative coordination, and strengthens accountability for the organisation\'s resources.', 'light-of-future-core' ), array( 'lof_role' => __( 'Administration Manager', 'light-of-future-core' ), 'lof_group' => 'board', 'lof_approved' => '1' ), 3, 4 ),
			array( 'frederick-otieno', 'Frederick Otieno', __( 'Board of Directors: Treasurer', 'light-of-future-core' ), __( 'Ensures that the foundation\'s funds are managed transparently and used directly to support the mission, and helps with budgeting.', 'light-of-future-core' ), array( 'lof_role' => __( 'Finance Manager', 'light-of-future-core' ), 'lof_group' => 'board', 'lof_approved' => '1' ), 4, 3 ),
			array( 'edith-atieno', 'Edith Atieno', '', __( 'Coordinates school support programmes, educational materials, scholarships, child protection and safeguarding, and vocational training.', 'light-of-future-core' ), array( 'lof_role' => __( 'Education & Skills Development (children and youth)', 'light-of-future-core' ), 'lof_group' => 'team', 'lof_approved' => '1' ), 5, 2 ),
			array( 'marion-atieno', 'Marion Atieno', '', __( 'Leads communications, media engagement and digital visibility, shares the foundation\'s impact with communities, partners, donors and the wider public, and helps document the foundation\'s work.', 'light-of-future-core' ), array( 'lof_role' => __( 'Communication, Media & Digital Engagement', 'light-of-future-core' ), 'lof_group' => 'team', 'lof_approved' => '1' ), 6, 4 ),
			array( 'lucy-habiba', 'Lucy Habiba', '', __( 'Identifies vulnerable families, mobilises volunteers and coordinates community activities. She safeguards children\'s rights and helps coordinate hygiene kits for girls and boys, such as sanitary pads and bar soap.', 'light-of-future-core' ), array( 'lof_role' => __( 'Community Outreach & Social Development', 'light-of-future-core' ), 'lof_group' => 'team', 'lof_approved' => '1' ), 7, 4 ),
			array( 'stephen-oduor', 'Stephen Oduor', '', __( 'Assists with the foundation\'s operations and supports initiatives that improve access to healthcare, medical camps and the wellbeing of the communities we serve.', 'light-of-future-core' ), array( 'lof_role' => __( 'Assistant Operations Manager & Health Care', 'light-of-future-core' ), 'lof_group' => 'team', 'lof_approved' => '1' ), 8, 4 ),
			array( 'kennedy-oyamo-mumbo', 'Kennedy Mumbo', '', __( 'Supports responsible environmental and sustainability initiatives, and access to safe water, wells and sanitation for underserved communities.', 'light-of-future-core' ), array( 'lof_role' => __( 'Environment, Water & Sanitation Programmes', 'light-of-future-core' ), 'lof_group' => 'team', 'lof_approved' => '1' ), 9, 4 ),
			array( 'victor-onyango', 'Victor Onyango', '', __( 'Supports sustainable, community-focused initiatives that build resilience, and helps coordinate the building of decent houses for widows, widowers and vulnerable families.', 'light-of-future-core' ), array( 'lof_role' => __( 'Community Development', 'light-of-future-core' ), 'lof_group' => 'team', 'lof_approved' => '1' ), 10, 4 ),
			array( 'scholastica-baraza', 'Scholastica Baraza', '', __( 'Supports programmes that address hunger and food insecurity with dignity, and helps vulnerable children, orphans and families thrive by coordinating food donations and other support.', 'light-of-future-core' ), array( 'lof_role' => __( 'Food Security, Children & Families', 'light-of-future-core' ), 'lof_group' => 'team', 'lof_approved' => '1' ), 11, 4 ),
		),
		'lof_metric'    => array(
			array( 'children-supported', __( 'Children supported', 'light-of-future-core' ), '', '', array( 'lof_value' => '200', 'lof_suffix' => '+', 'lof_source' => $source, 'lof_verified' => '2026-10-30', 'lof_visible' => '1' ), 1, 1 ),
			array( 'widows-assisted', __( 'Widows assisted', 'light-of-future-core' ), '', '', array( 'lof_value' => '30', 'lof_suffix' => '+', 'lof_source' => $source, 'lof_verified' => '2026-10-30', 'lof_visible' => '1' ), 2, 1 ),
			array( 'community-programmes', __( 'Community programmes', 'light-of-future-core' ), '', '', array( 'lof_value' => '11', 'lof_suffix' => '', 'lof_source' => __( 'Programme list supplied by the foundation, October 2026', 'light-of-future-core' ), 'lof_verified' => '2026-10-30', 'lof_visible' => '1' ), 3, 2 ),
			array( 'officially-registered', __( 'Officially registered', 'light-of-future-core' ), '', '', array( 'lof_value' => '2025', 'lof_suffix' => '', 'lof_source' => __( 'NGO Board certificate, 23 May 2025', 'light-of-future-core' ), 'lof_verified' => '2026-10-30', 'lof_visible' => '1' ), 4, 2 ),
		),
	);
}

/**
 * Fingerprint of an item's editable fields, to tell whether someone edited it.
 *
 * @param int $id Post ID.
 * @return string
 */
function lof_core_seed_fingerprint( $id ) {
	$post = get_post( $id );
	return $post ? md5( $post->post_title . '|' . $post->post_excerpt . '|' . $post->post_content ) : '';
}

/**
 * Create new starter items and refresh untouched ones whose revision went up.
 *
 * @return string[] Report lines.
 */
function lof_core_seed_content() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return array();
	}
	lof_core_register_post_types();
	$created = 0;
	$updated = 0;
	foreach ( lof_core_seed_data() as $type => $items ) {
		foreach ( $items as $item ) {
			list( $slug, $title, $excerpt, $content, $meta, $order ) = $item;
			$rev   = isset( $item[6] ) ? (int) $item[6] : 1;
			$found = get_posts( array( 'post_type' => $type, 'name' => $slug, 'post_status' => 'any', 'posts_per_page' => 1, 'fields' => 'ids' ) );

			if ( $found ) {
				$id       = (int) $found[0];
				$have_rev = (int) get_post_meta( $id, '_lof_seed_rev', true );
				$have_rev = $have_rev ? $have_rev : 1;
				$print    = (string) get_post_meta( $id, '_lof_seed_print', true );
				$touched  = $print && lof_core_seed_fingerprint( $id ) !== $print;
				if ( $rev <= $have_rev || $touched || 'trash' === get_post_status( $id ) ) {
					continue;
				}
				wp_update_post( array( 'ID' => $id, 'post_title' => $title, 'post_excerpt' => $excerpt, 'post_content' => $content, 'menu_order' => (int) $order ) );
				foreach ( $meta as $key => $value ) {
					update_post_meta( $id, $key, $value );
				}
				update_post_meta( $id, '_lof_seed_rev', $rev );
				update_post_meta( $id, '_lof_seed_print', lof_core_seed_fingerprint( $id ) );
				++$updated;
				continue;
			}
			if ( get_option( 'lof_seeded_' . $type . '_' . $slug ) ) {
				continue; // Created before and deleted on purpose: respect that.
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
			update_post_meta( $id, '_lof_seed_rev', $rev );
			update_post_meta( $id, '_lof_seed_print', lof_core_seed_fingerprint( $id ) );
			update_option( 'lof_seeded_' . $type . '_' . $slug, 1, false );
			++$created;
		}
	}
	$report = array(
		/* translators: %d: number of items */
		sprintf( _n( '%d starter item added.', '%d starter items added.', $created, 'light-of-future-core' ), $created ),
		/* translators: %d: number of items */
		sprintf( _n( '%d starter item refreshed with the foundation\'s latest content (edited items were left alone).', '%d starter items refreshed with the foundation\'s latest content (edited items were left alone).', $updated, 'light-of-future-core' ), $updated ),
	);
	return array_merge( $report, lof_core_upgrade_settings() );
}

/**
 * Fill organisation details supplied by the foundation, without overwriting
 * anything an admin has typed in.
 *
 * @return string[] Report lines.
 */
function lof_core_upgrade_settings() {
	$saved   = get_option( 'lof_core_settings', array() );
	$saved   = is_array( $saved ) ? $saved : array();
	$changed = false;
	$fill    = array(
		'address'           => array( array( '', 'Nairobi, Kenya' ), 'P.O. Box 53-00520, Ruai, along Kangundo Road, Nairobi, Kenya' ),
		'registration_line' => array( array( '' ), __( 'Registered with the NGO Board of Kenya on 23 May 2025.', 'light-of-future-core' ) ),
	);
	foreach ( $fill as $key => $rule ) {
		$current = isset( $saved[ $key ] ) ? (string) $saved[ $key ] : '';
		if ( in_array( $current, $rule[0], true ) ) {
			$saved[ $key ] = $rule[1];
			$changed       = true;
		}
	}
	if ( $changed ) {
		foreach ( lof_core_setting_defaults() as $key => $value ) {
			if ( ! isset( $saved[ $key ] ) ) {
				$saved[ $key ] = $value;
			}
		}
		update_option( 'lof_core_settings', $saved );
		return array( __( 'Organisation details updated: address and registration line.', 'light-of-future-core' ) );
	}
	return array();
}
