<?php
/**
 * One-off content update (runs once in wp-admin, option lofm_content_rev):
 * stories, focus-area pages, Talent Development, a Gallery page and the
 * impact figures Francis confirmed on 4 November 2026.
 *
 * @package LightOfFutureMedia
 */

defined( 'ABSPATH' ) || exit;

/**
 * Paragraphs helper.
 *
 * @param string[] $ps Paragraphs.
 * @return string
 */
function lofm_p( $ps ) {
	return '<p>' . implode( '</p><p>', array_map( 'esc_html', $ps ) ) . '</p>';
}

/**
 * Figures box.
 *
 * @param array $rows [value, label].
 * @return string
 */
function lofm_goal( $rows ) {
	$h = '<div class="lofm-goal">';
	foreach ( $rows as $r ) {
		$h .= '<div><strong>' . esc_html( $r[0] ) . '</strong><span>' . esc_html( $r[1] ) . '</span></div>';
	}
	return $h . '</div>';
}

/**
 * Stories: slug => [title, excerpt, location, body, lead photo].
 *
 * @return array
 */
function lofm_stories() {
	return array(
		'philister' => array(
			__( 'Philister: a safe home at 85', 'light-of-future' ),
			__( 'After more than 45 years in a grass-thatched house, Philister now lives in a safe and secure home.', 'light-of-future' ),
			__( 'Inungo village', 'light-of-future' ),
			lofm_p( array(
				__( 'Philister is a widow from Inungo village whose husband passed away in 1979. Since then, she has faced many years of hardship, struggling to survive on her own.', 'light-of-future' ),
				__( 'When we met her in 2023, she had been living in a grass-thatched house for over 45 years, often falling ill because of the poor condition of the house. The roof leaked in the rainy season, and in the windy season strong winds carried away the grass from the roof.', 'light-of-future' ),
				__( 'With the support of generous donors, we were able to build her a safe and secure home at the age of 85, greatly improving her quality of life.', 'light-of-future' ),
			) ) . '[lof_media group="philister"]',
			'philister-portrait',
		),
		'elijah' => array(
			__( 'Elijah: healing and a new home', 'light-of-future' ),
			__( 'Found in a critical condition in 2023, Elijah has recovered and lives in a safe, stable home.', 'light-of-future' ),
			__( 'Udimbe village, East Ugenya', 'light-of-future' ),
			lofm_p( array(
				__( 'Elijah is a widower from Udimbe village in East Ugenya location. His wife passed away in 2016, and since then he has faced many of life\'s challenges alone.', 'light-of-future' ),
				__( 'We met Elijah in December 2023, when he was in a critical condition. Elijah has epilepsy. During a seizure he fell into the fire while he was alone in the house, and he was badly burned on his leg.', 'light-of-future' ),
				__( 'We immediately arranged for him to be taken to Ambira Level 3 Hospital for urgent treatment. When we found that he had no one to stay with him there, our founder Francis set aside all his programmes and stayed with Elijah at the hospital for over two weeks.', 'light-of-future' ),
				__( 'The doctors found that Elijah\'s blood level was very low. Francis donated blood, and two other Good Samaritans each donated one unit, so Elijah received three units of blood.', 'light-of-future' ),
				__( 'While he was in hospital, we started raising funds for his bills. With the help of our online donors and supporters, we raised enough to cover his hospital bill and to give him a decent, safe and stable home.', 'light-of-future' ),
				__( 'We thank God that Elijah has recovered well and returned to his normal daily activities. We are deeply grateful to every hand that offered support when Elijah needed it most.', 'light-of-future' ),
			) ) . '[lof_media group="elijah"]',
			'elijah-athouse',
		),
		'eunice' => array(
			__( 'Eunice: a decent house for a mother and her sons', 'light-of-future' ),
			__( 'A leaking grass-thatched house made her boys ill. Donors built Eunice a decent house and furnished it.', 'light-of-future' ),
			__( 'Sega Ywaya village', 'light-of-future' ),
			lofm_p( array(
				__( 'Eunice Adhiambo, 49, is from Sega Ywaya village, where she lives with her two sons, Meshack and Silvester. We first met Eunice in 2022, and in July and August 2025 we were able to support her with a decent house.', 'light-of-future' ),
				__( 'We found Eunice living in a very poor grass-thatched house with a leaking roof, which was causing her two young boys to suffer from pneumonia. It touched our hearts deeply, and we started online campaigns to raise funds to build Eunice a decent house.', 'light-of-future' ),
				__( 'We thank God that, through our online supporters and donors, we raised the funds and built Eunice a decent house. We also bought her household items, including bedding, chairs, a table, utensils and clothes, and one of our online supporters even sent her a quilt from the USA.', 'light-of-future' ),
				__( 'We truly appreciate everyone who gave wholeheartedly to Eunice\'s project. May God bless you all.', 'light-of-future' ),
			) ) . '[lof_media group="eunice"]',
			'eunice-after',
		),
		'margaret' => array(
			__( 'Margaret: food, light and a fresh start', 'light-of-future' ),
			__( 'A widow whose house was being eaten by termites now has food, solar light and a small business.', 'light-of-future' ),
			'',
			lofm_p( array(
				__( 'Margaret has been a widow since her husband passed away in 2018. When we met her in 2025, she was going through a great deal of hardship, and termites had badly damaged her house.', 'light-of-future' ),
				__( 'We acted immediately with an online friend of the foundation to support Margaret. We first bought pesticides to kill the termites destroying the house, along with food, a mobile phone, clothes and shoes, and gave her some money for her needs.', 'light-of-future' ),
				__( 'We later went back and bought her a solar panel, a radio and more food. We also left Margaret with some money to start a small business to support herself.', 'light-of-future' ),
			) ) . '[lof_media group="margaret"]',
			'margaret-door',
		),
		'precious' => array(
			__( 'Precious: staying in school', 'light-of-future' ),
			__( 'School fees, a uniform and shoes helped Precious stay in school with confidence.', 'light-of-future' ),
			__( 'Nairobi', 'light-of-future' ),
			lofm_p( array(
				__( 'Precious is a young girl who lives with her guardian in Nairobi. Life has been challenging, especially during periods of severe flooding in the area, which made daily survival even harder.', 'light-of-future' ),
				__( 'During this time her guardian struggled to provide basic needs, including support for her education. We stepped in to help Precious continue her schooling by providing school fees, a uniform and shoes.', 'light-of-future' ),
			) ) . '[lof_media group="precious"]',
			'edu-precious-uniform',
		),
	);
}

/**
 * Focus areas: slug => html appended (once) to the programme content.
 *
 * @return array
 */
function lofm_programmes() {
	return array(
		'decent-houses'          => lofm_goal( array( array( '30', __( 'homes built for widows, widowers and vulnerable families since 2023', 'light-of-future' ) ) ) ) . lofm_p( array( __( 'Philister, Elijah and Eunice are three of the families now living in safe, decent homes. Read their stories below.', 'light-of-future' ) ) ) . '[lof_media group="philister,eunice"]',
		'health-medical-support' => lofm_p( array( __( 'When we found Elijah in December 2023, we rushed him to Ambira Level 3 Hospital. Our founder and two Good Samaritans gave the three units of blood the doctors required, and Francis stayed with Elijah for over two weeks until he was discharged.', 'light-of-future' ) ) ) . '[lof_media group="elijah"]',
		'education-support'      => lofm_goal( array( array( '200', __( 'children\'s school fees paid since 2022 (goal: 5,000)', 'light-of-future' ) ), array( '2,500', __( 'children given school materials since 2022 (goal: 50,000)', 'light-of-future' ) ) ) ) . lofm_p( array( __( 'Children from vulnerable families face many challenges, and a lack of books, pens and other supplies can block their dreams. We pay school fees, buy uniforms and shoes, and give children the materials they need.', 'light-of-future' ), __( 'Meshack and Silvester, from Sega Ywaya village, are among the children we support. We believe in supporting children\'s education, because they are the leaders of tomorrow.', 'light-of-future' ) ) ) . '[lof_media group="precious,education"]',
		'hygiene-kits'           => lofm_goal( array( array( '4,500', __( 'adolescent girls given sanitary pads since 2023 (goal: 150,000)', 'light-of-future' ) ) ) ) . lofm_p( array( __( 'Many girls in the slums and from vulnerable families miss classes because they cannot afford sanitary pads. Some even enter early relationships in search of support for basic hygiene kits, which can lead to early pregnancy and school dropouts.', 'light-of-future' ), __( 'We run this programme in informal settlements across Nairobi, including Mathare, Dandora, Njiru and Kawangware, and support girls in remote areas where hygiene kits are hard to find. We thank our partner, Suivera Organization, for consistently supporting this programme.', 'light-of-future' ) ) ) . '[lof_media group="pads"]',
		'food-donations'         => lofm_goal( array( array( '3,500', __( 'families served with food since 2023', 'light-of-future' ) ) ) ) . lofm_p( array( __( 'Through our work in the community, we found that many families are struggling with hunger. So we began donating food in slum areas and rural communities. To date, we have served over 3,500 families with food.', 'light-of-future' ) ) ) . '[lof_media group="food"]',
		'clean-water'            => lofm_goal( array( array( '1', __( 'borehole drilled for a community in Ugenya', 'light-of-future' ) ) ) ) . lofm_p( array( __( 'Many communities in Kenya suffer from a lack of clean water. Without it, waterborne diseases spread, and some families walk long distances to fetch water. So far we have drilled one borehole for a community in Ugenya, and we hope to reach many more communities.', 'light-of-future' ) ) ) . '[lof_media group="water"]',
		'community-development'  => lofm_goal( array( array( '8', __( 'community agriculture projects since 2023', 'light-of-future' ) ) ) ) . lofm_p( array( __( 'We run agriculture projects in the community, including bananas, maize and groundnuts, to generate income for our programmes so that we do not depend only on donations.', 'light-of-future' ), __( 'Our vision is to buy 40 acres of land and set up a large goat feedlot, selling live goats and meat to keep our programmes running even when donations are low.', 'light-of-future' ) ) ) . '[lof_media group="goats"]',
		'community-empowerment'  => lofm_goal( array( array( '5', __( 'business empowerment start-ups supported since 2025', 'light-of-future' ) ) ) ) . lofm_p( array( __( 'We help widows, youths and vulnerable families earn a living. Margaret, a widow we support, received food, a solar light and money to start a small business.', 'light-of-future' ) ) ) . '[lof_media group="margaret"]',
		'children-and-families'  => lofm_goal( array( array( '6,000', __( 'young, middle-aged and elderly people mentored since 2021', 'light-of-future' ) ) ) ) . lofm_p( array( __( 'We mentor girls and boys on personal hygiene, the environment and life skills, and give young people a platform for their talents.', 'light-of-future' ) ) ) . '[lof_media group="mentorship,talent"]',
	);
}

/**
 * Attach a bundled photo to the media library (once) and return its ID.
 *
 * @param string $slug Photo slug.
 * @param string $alt  Alt text.
 * @return int
 */
function lofm_attach( $slug, $alt ) {
	$ids = get_option( 'lofm_attach_ids', array() );
	if ( ! empty( $ids[ $slug ] ) && get_post( $ids[ $slug ] ) ) {
		return (int) $ids[ $slug ];
	}
	$src = dirname( __DIR__ ) . '/assets/photos/' . $slug . '.webp';
	if ( ! file_exists( $src ) ) {
		return 0;
	}
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';
	$tmp = wp_tempnam( $slug . '.webp' );
	copy( $src, $tmp );
	$id = media_handle_sideload( array( 'name' => 'lof-' . $slug . '.webp', 'tmp_name' => $tmp ), 0, $alt );
	if ( is_wp_error( $id ) ) {
		@unlink( $tmp ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		return 0;
	}
	update_post_meta( $id, '_wp_attachment_image_alt', $alt );
	$ids[ $slug ] = (int) $id;
	update_option( 'lofm_attach_ids', $ids, false );
	return (int) $id;
}

/**
 * Run the content update once.
 */
function lofm_content_upgrade() {
	if ( (int) get_option( 'lofm_content_rev', 0 ) >= 1 || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	update_option( 'lofm_content_rev', 1, false );
	@set_time_limit( 300 ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
	$report = array();

	// Stories.
	foreach ( lofm_stories() as $slug => $s ) {
		$found = get_page_by_path( $slug, OBJECT, 'lof_story' );
		$data  = array( 'post_type' => 'lof_story', 'post_status' => 'publish', 'post_name' => $slug, 'post_title' => $s[0], 'post_excerpt' => $s[1], 'post_content' => $s[3] );
		if ( $found ) {
			$data['ID'] = $found->ID;
			$id         = wp_update_post( wp_slash( $data ) );
		} else {
			$id = wp_insert_post( wp_slash( $data ) );
		}
		if ( $id && ! is_wp_error( $id ) ) {
			if ( $s[2] ) {
				update_post_meta( $id, 'lof_location', $s[2] );
			}
			$att = lofm_attach( $s[4], $s[0] );
			if ( $att ) {
				set_post_thumbnail( $id, $att );
			}
			$report[] = 'story:' . $slug;
		}
	}

	// Focus areas.
	foreach ( lofm_programmes() as $slug => $html ) {
		$p = get_page_by_path( $slug, OBJECT, 'lof_programme' );
		if ( ! $p || false !== strpos( $p->post_content, '[lof_media' ) ) {
			continue;
		}
		wp_update_post( wp_slash( array( 'ID' => $p->ID, 'post_content' => $p->post_content . "\n" . $html ) ) );
		$report[] = 'programme:' . $slug;
	}

	// Gallery page.
	if ( ! get_page_by_path( 'gallery' ) ) {
		wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'publish', 'post_name' => 'gallery', 'post_title' => __( 'Gallery', 'light-of-future' ), 'post_content' => '<p>' . esc_html__( 'Photos and short videos from our work with families and communities in Kenya, shared with the consent of the people in them.', 'light-of-future' ) . '</p>[lof_media_all]' ) );
		$report[] = 'gallery';
	}

	// Talent Development page: add the videos.
	$talent = get_page_by_path( 'talent-development' );
	if ( $talent && function_exists( 'lof_el' ) ) {
		$raw  = get_post_meta( $talent->ID, '_elementor_data', true );
		$data = is_string( $raw ) ? json_decode( $raw, true ) : $raw;
		if ( is_array( $data ) && false === strpos( (string) wp_json_encode( $data ), 'lof_media' ) ) {
			array_splice( $data, max( 1, count( $data ) - 1 ), 0, array( lof_el( 'shortcode', array( 'shortcode' => '<div class="lof-container">[lof_media group="talent" title="1"]</div>' ) ) ) );
			update_post_meta( $talent->ID, '_lof_layout_backup', wp_slash( is_string( $raw ) ? $raw : wp_json_encode( $raw ) ) );
			update_post_meta( $talent->ID, '_elementor_data', wp_slash( wp_json_encode( $data ) ) );
			delete_post_meta( $talent->ID, '_elementor_css' );
			delete_post_meta( $talent->ID, '_elementor_element_cache' );
			$report[] = 'talent';
		}
	}

	// Impact figures (Francis, 4 November 2026).
	$src  = __( 'Key achievements supplied by Francis Ouma Sikuku, 4 November 2026', 'light-of-future' );
	$figs = array(
		array( 'people-mentored', __( 'People mentored', 'light-of-future' ), '6,000', '+' ),
		array( 'girls-sanitary-pads', __( 'Girls given sanitary pads', 'light-of-future' ), '4,500', '+' ),
		array( 'families-fed', __( 'Families served with food', 'light-of-future' ), '3,500', '+' ),
		array( 'children-school-materials', __( 'Children given school materials', 'light-of-future' ), '2,500', '+' ),
		array( 'children-school-fees', __( 'Children\'s school fees paid', 'light-of-future' ), '200', '' ),
		array( 'homes-built', __( 'Homes built for widows and widowers', 'light-of-future' ), '30', '' ),
	);
	foreach ( get_posts( array( 'post_type' => 'lof_metric', 'post_status' => 'any', 'numberposts' => -1 ) ) as $m ) {
		update_post_meta( $m->ID, 'lof_visible', '0' );
	}
	foreach ( $figs as $i => $f ) {
		$found = get_page_by_path( $f[0], OBJECT, 'lof_metric' );
		$id    = $found ? $found->ID : wp_insert_post( array( 'post_type' => 'lof_metric', 'post_status' => 'publish', 'post_name' => $f[0], 'post_title' => $f[1], 'menu_order' => $i + 1 ) );
		if ( $id && ! is_wp_error( $id ) ) {
			update_post_meta( $id, 'lof_value', $f[2] );
			update_post_meta( $id, 'lof_suffix', $f[3] );
			update_post_meta( $id, 'lof_source', $src );
			update_post_meta( $id, 'lof_verified', '2026-11-04' );
			update_post_meta( $id, 'lof_visible', '1' );
		}
	}
	$report[] = 'metrics';

	// Home slider figure bar.
	$home = get_page_by_path( 'home' );
	if ( ! $home ) {
		$front = (int) get_option( 'page_on_front' );
		$home  = $front ? get_post( $front ) : null;
	}
	if ( $home ) {
		$raw  = get_post_meta( $home->ID, '_elementor_data', true );
		$data = is_string( $raw ) ? json_decode( $raw, true ) : $raw;
		if ( is_array( $data ) ) {
			foreach ( $data as &$sec ) {
				if ( isset( $sec['elements'][0]['elements'][0]['widgetType'] ) && 'lof-slider' === $sec['elements'][0]['elements'][0]['widgetType'] ) {
					$st          = &$sec['elements'][0]['elements'][0]['settings'];
					$st['fig1']  = __( '6,000+|People mentored', 'light-of-future' );
					$st['fig2']  = __( '4,500+|Girls given sanitary pads', 'light-of-future' );
					$st['fig3']  = __( '3,500+|Families fed', 'light-of-future' );
					$st['fig4']  = __( '30|Homes built', 'light-of-future' );
					unset( $st );
					break;
				}
			}
			unset( $sec );
			update_post_meta( $home->ID, '_elementor_data', wp_slash( wp_json_encode( $data ) ) );
			delete_post_meta( $home->ID, '_elementor_css' );
			delete_post_meta( $home->ID, '_elementor_element_cache' );
			$report[] = 'home-figures';
		}
	}
	if ( class_exists( '\Elementor\Plugin' ) && isset( \Elementor\Plugin::$instance->files_manager ) ) {
		\Elementor\Plugin::$instance->files_manager->clear_cache();
	}
	if ( has_action( 'litespeed_purge_all' ) ) {
		do_action( 'litespeed_purge_all' );
	}
	update_option( 'lofm_content_report', $report, false );
}
add_action( 'admin_init', 'lofm_content_upgrade', 40 );

/**
 * Admin notice with what the update did.
 */
add_action(
	'admin_notices',
	function () {
		$r = get_option( 'lofm_content_report' );
		if ( $r && current_user_can( 'manage_options' ) ) {
			echo '<div class="notice notice-success"><p>' . esc_html( 'Light of Future Media: ' . implode( ', ', (array) $r ) ) . '</p></div>';
		}
	}
);

/**
 * Rev 2: record the consent Francis confirmed (relayed by the site team on
 * 5 November 2026) for the two new stories so they can publish, and put the
 * full media gallery on the existing Gallery page.
 */
function lofm_content_upgrade_2() {
	if ( (int) get_option( 'lofm_content_rev', 0 ) !== 1 || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	update_option( 'lofm_content_rev', 2, false );
	$report = (array) get_option( 'lofm_content_report', array() );
	foreach ( array( 'eunice', 'margaret' ) as $slug ) {
		$posts = get_posts( array( 'post_type' => 'lof_story', 'name' => $slug, 'post_status' => 'any', 'numberposts' => 1 ) );
		if ( ! $posts ) {
			continue;
		}
		$id = $posts[0]->ID;
		update_post_meta( $id, 'lof_consent', '1' );
		update_post_meta( $id, 'lof_consent_date', '2026-11-05 (confirmed by Francis Ouma Sikuku, relayed by the site team)' );
		wp_update_post( array( 'ID' => $id, 'post_status' => 'publish' ) );
		$report[] = 'published:' . $slug . '=' . get_post_status( $id );
	}
	$g = get_page_by_path( 'gallery' );
	if ( $g ) {
		$raw  = get_post_meta( $g->ID, '_elementor_data', true );
		$data = is_string( $raw ) ? json_decode( $raw, true ) : $raw;
		if ( is_array( $data ) && $data && function_exists( 'lof_el' ) ) {
			if ( false === strpos( (string) wp_json_encode( $data ), 'lof_media_all' ) ) {
				$data[] = lof_el( 'shortcode', array( 'shortcode' => '<div class="lof-container" style="padding-block:clamp(2rem,4vw,3.5rem)">[lof_media_all]</div>' ) );
				update_post_meta( $g->ID, '_lof_layout_backup', wp_slash( is_string( $raw ) ? $raw : wp_json_encode( $raw ) ) );
				update_post_meta( $g->ID, '_elementor_data', wp_slash( wp_json_encode( $data ) ) );
				delete_post_meta( $g->ID, '_elementor_css' );
				delete_post_meta( $g->ID, '_elementor_element_cache' );
			}
		} elseif ( false === strpos( $g->post_content, 'lof_media_all' ) ) {
			wp_update_post( array( 'ID' => $g->ID, 'post_content' => $g->post_content . "\n[lof_media_all]" ) );
		}
		if ( 'publish' !== $g->post_status ) {
			wp_update_post( array( 'ID' => $g->ID, 'post_status' => 'publish' ) );
		}
		$report[] = 'gallery-media';
	}
	if ( class_exists( '\Elementor\Plugin' ) && isset( \Elementor\Plugin::$instance->files_manager ) ) {
		\Elementor\Plugin::$instance->files_manager->clear_cache();
	}
	update_option( 'lofm_content_report', $report, false );
}
add_action( 'admin_init', 'lofm_content_upgrade_2', 41 );
