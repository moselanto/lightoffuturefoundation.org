<?php
/**
 * Website setup: one click creates the pages, Elementor layouts, menus and
 * home page, and asks the Core plugin to add the starter content.
 *
 * Safe to run again: existing pages, menus and content are never changed or
 * duplicated. To rebuild one page's layout, delete (or rename the slug of)
 * that page and run setup again.
 *
 * @package LightOfFuture
 */

defined( 'ABSPATH' ) || exit;

/**
 * Admin screen under Appearance.
 */
function lof_setup_menu() {
	add_theme_page( __( 'Light of Future setup', 'light-of-future' ), __( 'Light of Future setup', 'light-of-future' ), 'manage_options', 'lof-setup', 'lof_setup_page' );
}
add_action( 'admin_menu', 'lof_setup_menu' );

/**
 * Remind admins to run setup once.
 */
function lof_setup_notice() {
	if ( ! current_user_can( 'manage_options' ) || get_option( 'lof_setup_done' ) ) {
		return;
	}
	$screen = get_current_screen();
	if ( $screen && 'appearance_page_lof-setup' === $screen->id ) {
		return;
	}
	printf(
		'<div class="notice notice-info"><p>%1$s <a class="button button-primary" href="%2$s">%3$s</a></p></div>',
		esc_html__( 'Light of Future Foundation theme is active. Build the pages and menus in one click:', 'light-of-future' ),
		esc_url( admin_url( 'themes.php?page=lof-setup' ) ),
		esc_html__( 'Open setup', 'light-of-future' )
	);
}
add_action( 'admin_notices', 'lof_setup_notice' );

/**
 * Setup screen.
 */
function lof_setup_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$report = get_transient( 'lof_setup_report' );
	delete_transient( 'lof_setup_report' );
	$checks = array(
		__( 'Elementor', 'light-of-future' )            => did_action( 'elementor/loaded' ) > 0,
		__( 'Light of Future Core plugin', 'light-of-future' ) => function_exists( 'lof_core_seed_content' ),
	);
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Light of Future setup', 'light-of-future' ); ?></h1>
		<p><?php esc_html_e( 'Creates the website pages with their Elementor layouts, the main and footer menus, sets the home page, and adds the starter programmes, stories, leaders and impact figures. Anything that already exists is left exactly as it is.', 'light-of-future' ); ?></p>
		<h2><?php esc_html_e( 'Requirements', 'light-of-future' ); ?></h2>
		<ul>
			<?php foreach ( $checks as $label => $ok ) : ?>
				<li><?php echo $ok ? '&#10003; ' : '&#10007; '; ?><?php echo esc_html( $label ); ?><?php echo $ok ? '' : ' - ' . esc_html__( 'please install and activate it first', 'light-of-future' ); ?></li>
			<?php endforeach; ?>
		</ul>
		<?php if ( is_array( $report ) && $report ) : ?>
			<div class="notice notice-success inline"><p><strong><?php esc_html_e( 'Setup finished:', 'light-of-future' ); ?></strong></p><ul style="list-style:disc;margin-left:2em">
				<?php foreach ( $report as $line ) : ?>
					<li><?php echo esc_html( $line ); ?></li>
				<?php endforeach; ?>
			</ul></div>
		<?php endif; ?>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php wp_nonce_field( 'lof_run_setup' ); ?>
			<input type="hidden" name="action" value="lof_run_setup">
			<?php submit_button( __( 'Run setup', 'light-of-future' ), 'primary', 'submit', false, in_array( false, $checks, true ) ? array( 'disabled' => 'disabled' ) : array() ); ?>
		</form>
		<hr>
		<h2><?php esc_html_e( 'Apply latest design', 'light-of-future' ); ?></h2>
		<p><?php esc_html_e( 'Adds the bundled photos to the Media Library, gives starter stories and the founder their photos (only where none is set), and replaces the layouts of the pages that setup created with the newest design. The previous layout of each page is kept as a backup. Pages you created yourself are never touched.', 'light-of-future' ); ?></p>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" onsubmit="return confirm('<?php echo esc_js( __( 'Replace the layouts of the setup pages with the latest design? A backup of each layout is kept.', 'light-of-future' ) ); ?>');">
			<?php wp_nonce_field( 'lof_apply_design' ); ?>
			<input type="hidden" name="action" value="lof_apply_design">
			<?php submit_button( __( 'Apply latest design', 'light-of-future' ), 'secondary', 'submit', false, in_array( false, $checks, true ) ? array( 'disabled' => 'disabled' ) : array() ); ?>
		</form>
	</div>
	<?php
}

/**
 * Handle the setup form.
 */
function lof_run_setup() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Not allowed.', 'light-of-future' ), 403 );
	}
	check_admin_referer( 'lof_run_setup' );
	$report = array();
	if ( function_exists( 'lof_core_seed_content' ) ) {
		$report = array_merge( $report, lof_core_seed_content() );
	}
	$report = array_merge( $report, lof_import_photos() );
	$report = array_merge( $report, lof_setup_pages() );
	$report = array_merge( $report, lof_setup_menus() );
	$locs   = get_theme_mod( 'nav_menu_locations', array() );
	if ( empty( $locs['lof-primary'] ) ) {
		$report = array_merge( $report, lof_rebuild_primary_menu() );
	}
	update_option( 'lof_setup_done', LOF_THEME_VERSION, false );
	flush_rewrite_rules();
	set_transient( 'lof_setup_report', $report, 300 );
	wp_safe_redirect( admin_url( 'themes.php?page=lof-setup' ) );
	exit;
}
add_action( 'admin_post_lof_run_setup', 'lof_run_setup' );

/**
 * Build one Elementor section containing one widget.
 *
 * @param string $widget   Widget name.
 * @param array  $settings Widget settings.
 * @return array
 */
function lof_el( $widget, $settings ) {
	$id = function () {
		return substr( md5( wp_generate_password( 12, false ) ), 0, 7 );
	};
	return array(
		'id'       => $id(),
		'elType'   => 'section',
		'isInner'  => false,
		'settings' => array( 'layout' => 'full_width', 'gap' => 'no', 'padding' => array( 'unit' => 'px', 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'isLinked' => true ) ),
		'elements' => array(
			array(
				'id'       => $id(),
				'elType'   => 'column',
				'isInner'  => false,
				'settings' => array( '_column_size' => 100, 'padding' => array( 'unit' => 'px', 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'isLinked' => true ) ),
				'elements' => array(
					array(
						'id'         => $id(),
						'elType'     => 'widget',
						'widgetType' => $widget,
						'settings'   => $settings,
						'elements'   => array(),
					),
				),
			),
		),
	);
}

/**
 * Elementor URL control value for a site path.
 *
 * @param string $path Path such as /donate/.
 * @return array
 */
function lof_link( $path ) {
	return array( 'url' => home_url( $path ), 'is_external' => '', 'nofollow' => '' );
}

/**
 * Inner-page banner.
 *
 * @param string $eyebrow Label.
 * @param string $title   Heading.
 * @param string $text    Text.
 * @return array
 */
function lof_page_hero( $eyebrow, $title, $text = '' ) {
	return lof_el(
		'lof-hero',
		array(
			'variant'   => 'page',
			'eyebrow'   => $eyebrow,
			'title'     => $title,
			'highlight' => '',
			'text'      => $text,
			'btn1_label' => '',
			'btn2_label' => '',
		)
	);
}

/**
 * Standard closing call to action.
 *
 * @return array
 */
function lof_closing_cta() {
	return lof_el(
		'lof-cta',
		array(
			'eyebrow'    => __( 'Support our work', 'light-of-future' ),
			'title'      => __( 'Be the light in someone\'s future', 'light-of-future' ),
			'intro'      => __( 'Your support helps provide education, clean water, safe homes and care to vulnerable families in Kenya.', 'light-of-future' ),
			'btn1_label' => __( 'Donate', 'light-of-future' ),
			'btn1_url'   => lof_link( '/donate/' ),
			'btn2_label' => __( 'Get involved', 'light-of-future' ),
			'btn2_url'   => lof_link( '/get-involved/' ),
			'tone'       => 'sun',
		)
	);
}

/**
 * Text shared by several pages (from the foundation's previous website).
 *
 * @param string $key Key.
 * @return string
 */
function lof_copy( $key ) {
	$copy = array(
		'about'   => '<p>' . __( 'Light of Future Foundation is a charitable organisation dedicated to supporting vulnerable communities in Kenya. We focus on assisting widows, widowers, orphans and people living with disabilities through programmes that create opportunity and restore dignity.', 'light-of-future' ) . '</p><p>' . __( 'Our work includes education support, community empowerment, clean water access and youth development. By working directly with communities, we aim to create lasting and sustainable impact.', 'light-of-future' ) . '</p>',
		'founder' => '<p>' . __( 'Francis Ouma Sikuku, from Inungo village, is a musician, plumber, choir teacher and counsellor with a deep passion for serving his community. From the age of 20 he engaged in charity work, visiting widows\' homes, helping with cleaning, fetching water and collecting firewood, which planted the seed for his lifelong commitment to helping others.', 'light-of-future' ) . '</p><p>' . __( 'In 2016 he began formal studies in Social Work and Community Development at Kisumu Institute of Management College. Financial challenges prevented him from completing his diploma, so he dedicated 20% of his earnings from music and plumbing to charitable work.', 'light-of-future' ) . '</p><p>' . __( 'In 2021 he began registering that work as a charitable organisation and, knowing he could not do it alone, brought together a team of 10 members. After four years of dedication and paperwork by the team, Light of Future Foundation was formally registered with the NGO Board on 23 May 2025.', 'light-of-future' ) . '</p>',
		'quote'   => __( 'Serving humanity is our passion and it brings us great joy. It has been a long journey, but with God\'s grace we are now able to make a long-lasting impact in different communities in Kenya.', 'light-of-future' ),
	);
	return isset( $copy[ $key ] ) ? $copy[ $key ] : '';
}

/**
 * Page definitions: slug => [title, status, sections].
 *
 * @return array
 */
function lof_setup_page_defs() {
	$founder = array(
		'eyebrow'    => __( 'Our founder', 'light-of-future' ),
		'title'      => __( 'A lifelong calling to serve', 'light-of-future' ),
		'body'       => lof_copy( 'founder' ),
		'quote'      => lof_copy( 'quote' ),
		'quote_by'   => __( 'Francis Ouma Sikuku, Founder & Executive Director', 'light-of-future' ),
		'image_side' => 'right',
		'tone'       => 'sand',
		'image'      => lof_photo( 'founder-francis-ouma-sikuku' ),
		'milestones' => array(
			array( '_id' => 'ms1', 'value' => '20', 'label' => __( 'Age he began serving widows and families', 'light-of-future' ) ),
			array( '_id' => 'ms2', 'value' => '2021', 'label' => __( 'Registration begun with a team of 10', 'light-of-future' ) ),
			array( '_id' => 'ms3', 'value' => '2025', 'label' => __( 'Registered with the NGO Board, 23 May', 'light-of-future' ) ),
		),
	);

	return array(
		'home'               => array(
			__( 'Home', 'light-of-future' ),
			'publish',
			array(
				lof_el(
					'lof-hero',
					array(
						'variant'    => 'home',
						'eyebrow'    => __( 'Light of Future Foundation · Nairobi, Kenya', 'light-of-future' ),
						'title'      => __( 'Bringing hope and opportunity to communities in Kenya', 'light-of-future' ),
						'highlight'  => __( 'hope', 'light-of-future' ),
						'text'       => __( 'We support widows, widowers, orphans, vulnerable children and people living with disabilities through education, clean water and community programmes.', 'light-of-future' ),
						'btn1_label' => __( 'Explore Our Work', 'light-of-future' ),
						'btn1_url'   => lof_link( '/programmes/' ),
						'btn2_label' => __( 'Support the Future', 'light-of-future' ),
						'btn2_url'   => lof_link( '/donate/' ),
						'collage1'   => lof_photo( 'story-elijah-new-home' ),
						'collage2'   => lof_photo( 'story-philister-home' ),
						'stamp'      => __( 'Hope in action', 'light-of-future' ),
						'card_title' => __( 'Since 2021', 'light-of-future' ),
						'card_text'  => __( 'A community journey, registered with the NGO Board on 23 May 2025.', 'light-of-future' ),
						'badge1'     => __( 'Registered with the NGO Board, 2025', 'light-of-future' ),
						'badge2'     => __( '200+ children supported', 'light-of-future' ),
						'badge3'     => __( 'Nairobi & Inungo village', 'light-of-future' ),
					)
				),
				lof_el( 'lof-ribbon', array( 'words' => "Education\nClean water\nSafe homes\nHealthcare\nYouth talent\nDignity\nHope", 'tone' => 'sun' ) ),
				lof_el( 'lof-impact', array( 'eyebrow' => __( 'Our impact', 'light-of-future' ), 'title' => __( 'Hope you can count', 'light-of-future' ), 'tone' => 'earth' ) ),
				lof_el(
					'lof-feature',
					array(
						'eyebrow'    => __( 'Who we are', 'light-of-future' ),
						'title'      => __( 'Restoring dignity, creating opportunity', 'light-of-future' ),
						'body'       => lof_copy( 'about' ),
						'btn1_label' => __( 'Our story', 'light-of-future' ),
						'btn1_url'   => lof_link( '/about-us/' ),
						'image'      => lof_photo( 'story-philister-home' ),
						'image_caption' => __( 'Philister outside the safe home donors helped build for her.', 'light-of-future' ),
						'image_side' => 'left',
						'tone'       => 'cream',
					)
				),
				lof_el(
					'lof-intro',
					array(
						'eyebrow'    => __( 'Why we exist', 'light-of-future' ),
						'title'      => __( 'Hope should open doors.', 'light-of-future' ),
						'statement'  => __( 'We believe every widow, orphan and vulnerable family deserves the dignity, skills and opportunity to shape a better future.', 'light-of-future' ),
						'text'       => __( 'Light of Future Foundation brings communities, local groups and partners together through training, seminars and practical support, from education and clean water to safe homes, healthcare and youth talent.', 'light-of-future' ),
						'btn1_label' => __( 'Discover our story', 'light-of-future' ),
						'btn1_url'   => lof_link( '/about-us/' ),
						'tone'       => 'white',
						'panels'     => array(
							array( '_id' => 'in1', 'kicker' => __( 'Our vision', 'light-of-future' ), 'title' => __( 'Hope, dignity and opportunity for every community.', 'light-of-future' ), 'text' => __( 'To bring hope, dignity and opportunity by empowering vulnerable communities through sustainable support and long-lasting impact, in collaboration with the people of those communities.', 'light-of-future' ) ),
							array( '_id' => 'in2', 'kicker' => __( 'Our mission', 'light-of-future' ), 'title' => __( 'Partnership that restores hope.', 'light-of-future' ), 'text' => __( 'To harness partnership and collaboration to restore hope and self-realisation among orphans, widows, widowers and vulnerable families, by organising training and seminars and empowering them in different areas of life.', 'light-of-future' ) ),
						),
					)
				),
				lof_el(
					'lof-programmes',
					array(
						'eyebrow'    => __( 'What we do', 'light-of-future' ),
						'title'      => __( 'Eleven ways we bring light', 'light-of-future' ),
						'intro'      => __( 'From decent houses and clean water to education, skills and business start-ups, we work hand in hand with communities to restore hope and self-reliance.', 'light-of-future' ),
						'count'      => 6,
						'btn1_label' => __( 'See all 11 programmes', 'light-of-future' ),
						'btn1_url'   => lof_link( '/programmes/' ),
						'tone'       => 'white',
					)
				),
				lof_el( 'lof-feature', array_merge( $founder, array( 'btn1_label' => __( 'Meet our leadership', 'light-of-future' ), 'btn1_url' => lof_link( '/leadership/' ) ) ) ),
				lof_el( 'lof-leaders', array( 'eyebrow' => __( 'Our leadership', 'light-of-future' ), 'title' => __( 'Meet the people behind the work', 'light-of-future' ), 'show_bio' => '', 'layout' => 'grid', 'tone' => 'white' ) ),
				lof_el(
					'lof-donate',
					array(
						'eyebrow' => __( 'Give today', 'light-of-future' ),
						'title'   => __( 'Your gift keeps the light on', 'light-of-future' ),
						'intro'   => __( 'Every shilling goes toward school fees, safe homes, medical care and clean water for families who need it most.', 'light-of-future' ),
						'target'  => lof_link( '/donate/' ),
						'image'   => lof_photo( 'story-precious-school-shoes' ),
						'tone'    => 'earth',
					)
				),
				lof_el(
					'lof-stories',
					array(
						'eyebrow'    => __( 'Stories of change', 'light-of-future' ),
						'title'      => __( 'Real people, real change', 'light-of-future' ),
						'btn1_label' => __( 'More stories', 'light-of-future' ),
						'btn1_url'   => lof_link( '/stories/' ),
						'tone'       => 'cream',
					)
				),
				lof_el( 'lof-partners', array( 'tone' => 'white' ) ),
				lof_closing_cta(),
			),
		),
		'about-us'           => array(
			__( 'About Us', 'light-of-future' ),
			'publish',
			array(
				lof_page_hero( __( 'About us', 'light-of-future' ), __( 'Who we are', 'light-of-future' ), __( 'A registered charitable organisation restoring hope, dignity and opportunity in communities across Kenya.', 'light-of-future' ) ),
				lof_el( 'lof-impact', array( 'eyebrow' => '', 'title' => '', 'strip' => 'yes', 'tone' => 'earth' ) ),
				lof_el( 'lof-feature', array( 'eyebrow' => __( 'Our story', 'light-of-future' ), 'title' => __( 'Restoring dignity, creating opportunity', 'light-of-future' ), 'body' => lof_copy( 'about' ), 'image' => lof_photo( 'story-elijah-new-home' ), 'image_caption' => __( 'Elijah at his new home.', 'light-of-future' ), 'image_side' => 'left', 'tone' => 'cream' ) ),
				lof_el(
					'lof-intro',
					array(
						'eyebrow'    => __( 'What guides us', 'light-of-future' ),
						'title'      => __( 'Holistic transformation, built together.', 'light-of-future' ),
						'statement'  => __( 'Our main objective is to provide holistic transformation to young, middle-aged and older people and disadvantaged communities through training, empowerment and exposure to life-changing innovations.', 'light-of-future' ),
						'btn1_label' => '',
						'tone'       => 'white',
						'panels'     => array(
							array( '_id' => 'm1', 'kicker' => __( 'Our vision', 'light-of-future' ), 'title' => __( 'Hope, dignity and opportunity for every community.', 'light-of-future' ), 'text' => __( 'To bring hope, dignity and opportunity by empowering vulnerable communities through sustainable support and long-lasting impact, in collaboration with the people of those communities.', 'light-of-future' ) ),
							array( '_id' => 'm2', 'kicker' => __( 'Our mission', 'light-of-future' ), 'title' => __( 'Partnership that restores hope.', 'light-of-future' ), 'text' => __( 'To harness partnership and collaboration to restore hope and self-realisation among orphans, widows, widowers and vulnerable families, by organising training and seminars and empowering them in different areas of life.', 'light-of-future' ) ),
						),
					)
				),
				lof_el(
					'lof-cards',
					array(
						'eyebrow' => __( 'Our values', 'light-of-future' ),
						'title'   => __( 'How we work with people', 'light-of-future' ),
						'columns' => '4',
						'tone'    => 'sand',
						'items'   => array(
							array( '_id' => 'v1', 'title' => __( 'Dignity and respect', 'light-of-future' ), 'text' => __( 'We acknowledge the inherent worth of every person, offer help without judgement, stigma or pity, and honour each person\'s right to self-determination.', 'light-of-future' ) ),
							array( '_id' => 'v2', 'title' => __( 'Compassion and empathy', 'light-of-future' ), 'text' => __( 'We act from genuine care and a deep understanding of the struggles and trauma faced by marginalised communities.', 'light-of-future' ) ),
							array( '_id' => 'v3', 'title' => __( 'Social justice and equity', 'light-of-future' ), 'text' => __( 'We stand against the inequalities, discrimination and barriers that keep people vulnerable, and work for fair access to resources and rights.', 'light-of-future' ) ),
							array( '_id' => 'v4', 'title' => __( 'Empowerment', 'light-of-future' ), 'text' => __( 'We move beyond temporary relief to build self-reliance, equipping people with the skills, tools and confidence to shape their own futures.', 'light-of-future' ) ),
							array( '_id' => 'v5', 'title' => __( 'Integrity, transparency and accountability', 'light-of-future' ), 'text' => __( 'We keep high ethical standards, care responsibly for donor funds and community trust, and report to donors within agreed timeframes.', 'light-of-future' ) ),
							array( '_id' => 'v6', 'title' => __( 'Inclusiveness and diversity', 'light-of-future' ), 'text' => __( 'We welcome and serve people regardless of background, ethnicity, gender, religion or economic status, and make sure diverse voices are heard.', 'light-of-future' ) ),
							array( '_id' => 'v7', 'title' => __( 'Collaboration and partnership', 'light-of-future' ), 'text' => __( 'We work hand in hand with community members, grassroots groups, churches and other partners, never in isolation.', 'light-of-future' ) ),
						),
					)
				),
				lof_el(
					'lof-cards',
					array(
						'eyebrow' => __( 'Our objectives', 'light-of-future' ),
						'title'   => __( 'What we set out to do', 'light-of-future' ),
						'columns' => '3',
						'tone'    => 'white',
						'items'   => array(
							array( '_id' => 'o1', 'kicker' => '01', 'title' => __( 'Education', 'light-of-future' ), 'text' => __( 'Mobilise resources and establish educational opportunities for vulnerable children.', 'light-of-future' ) ),
							array( '_id' => 'o2', 'kicker' => '02', 'title' => __( 'Organic farming', 'light-of-future' ), 'text' => __( 'Support improved agriculture through organic farming.', 'light-of-future' ) ),
							array( '_id' => 'o3', 'kicker' => '03', 'title' => __( 'Know your rights', 'light-of-future' ), 'text' => __( 'Help vulnerable people know their rights and when to apply them.', 'light-of-future' ) ),
							array( '_id' => 'o4', 'kicker' => '04', 'title' => __( 'Talent', 'light-of-future' ), 'text' => __( 'Run talent searches in music, dance, drama, art and comedy, and create platforms for young people.', 'light-of-future' ) ),
							array( '_id' => 'o5', 'kicker' => '05', 'title' => __( 'Decent homes and start-ups', 'light-of-future' ), 'text' => __( 'Improve the living standards of widows, widowers and orphans with decent houses and business start-ups.', 'light-of-future' ) ),
							array( '_id' => 'o6', 'kicker' => '06', 'title' => __( 'Water and sanitation', 'light-of-future' ), 'text' => __( 'Support water and sanitation projects in the community.', 'light-of-future' ) ),
							array( '_id' => 'o7', 'kicker' => '07', 'title' => __( 'Economic empowerment', 'light-of-future' ), 'text' => __( 'Empower communities economically through training and start-ups to eradicate poverty.', 'light-of-future' ) ),
						),
					)
				),
				lof_el( 'lof-feature', $founder ),
				lof_el(
					'lof-cards',
					array(
						'eyebrow' => __( 'At a glance', 'light-of-future' ),
						'title'   => __( 'Registration and contacts', 'light-of-future' ),
						'columns' => '4',
						'tone'    => 'white',
						'items'   => array(
							array( '_id' => 'r1', 'title' => __( 'Registered', 'light-of-future' ), 'text' => __( 'With the NGO Board of Kenya on 23 May 2025, as a charitable organisation.', 'light-of-future' ) ),
							array( '_id' => 'r2', 'title' => __( 'Charitable purpose', 'light-of-future' ), 'text' => __( 'Social, educational, health, environmental and poverty-relief goals.', 'light-of-future' ) ),
							array( '_id' => 'r3', 'title' => __( 'Leadership', 'light-of-future' ), 'text' => __( 'Founder and Executive Director: Francis Ouma Sikuku.', 'light-of-future' ), 'link_label' => __( 'Meet the team', 'light-of-future' ), 'link' => lof_link( '/leadership/' ) ),
							array( '_id' => 'r4', 'title' => __( 'Postal address', 'light-of-future' ), 'text' => __( 'P.O. Box 53-00520, Ruai, along Kangundo Road, Nairobi, Kenya.', 'light-of-future' ) ),
						),
					)
				),
				lof_closing_cta(),
			),
		),
		'leadership'         => array(
			__( 'Leadership & Governance', 'light-of-future' ),
			'publish',
			array(
				lof_page_hero( __( 'People and accountability', 'light-of-future' ), __( 'Leadership & Governance', 'light-of-future' ), __( 'The people accountable for the foundation\'s work, and the structure they are accountable through.', 'light-of-future' ) ),
				lof_el( 'lof-leaders', array( 'number' => '01', 'eyebrow' => __( 'Executive leadership', 'light-of-future' ), 'title' => __( 'Vision and stewardship.', 'light-of-future' ), 'intro' => __( 'Overall direction, strategy and day-to-day leadership of the foundation.', 'light-of-future' ), 'group' => 'executive', 'layout' => 'row', 'tone' => 'cream' ) ),
				lof_el( 'lof-leaders', array( 'number' => '02', 'eyebrow' => __( 'Board of Directors', 'light-of-future' ), 'title' => __( 'Governance and oversight.', 'light-of-future' ), 'intro' => __( 'A four-member board, chaired by our founder, oversees direction, finances and accountability. Each director also serves in the foundation\'s management.', 'light-of-future' ), 'group' => 'board', 'layout' => 'row', 'tone' => 'white' ) ),
				lof_el( 'lof-leaders', array( 'number' => '03', 'eyebrow' => __( 'Programmes team', 'light-of-future' ), 'title' => __( 'The people moving the work forward.', 'light-of-future' ), 'intro' => __( 'The team delivering programmes for children, young people and families.', 'light-of-future' ), 'group' => 'team', 'layout' => 'row', 'tone' => 'cream' ) ),
				lof_el(
					'lof-cards',
					array(
						'eyebrow' => __( 'Governance', 'light-of-future' ),
						'title'   => __( 'How the foundation is governed.', 'light-of-future' ),
						'intro'   => __( 'Registered with the NGO Board of Kenya on 23 May 2025 as a charitable organisation.', 'light-of-future' ),
						'style'   => 'steps',
						'columns' => '4',
						'tone'    => 'sand',
						'items'   => array(
							array( '_id' => 'gv1', 'kicker' => '01', 'title' => __( 'Members', 'light-of-future' ), 'text' => __( 'A team of 10 members who came together to register and grow the foundation.', 'light-of-future' ) ),
							array( '_id' => 'gv2', 'kicker' => '02', 'title' => __( 'Board of Directors', 'light-of-future' ), 'text' => __( 'Chairman, Vice Chairperson, Secretary and Treasurer provide oversight and direction.', 'light-of-future' ) ),
							array( '_id' => 'gv3', 'kicker' => '03', 'title' => __( 'Executive Director', 'light-of-future' ), 'text' => __( 'Leads strategy, partnerships and programme implementation.', 'light-of-future' ) ),
							array( '_id' => 'gv4', 'kicker' => '04', 'title' => __( 'Management & programmes', 'light-of-future' ), 'text' => __( 'Run operations, finance, administration and programmes day to day.', 'light-of-future' ) ),
						),
					)
				),
				lof_el(
					'lof-cards',
					array(
						'eyebrow' => __( 'Accountability', 'light-of-future' ),
						'title'   => __( 'How we keep faith with communities and donors.', 'light-of-future' ),
						'columns' => '3',
						'tone'    => 'white',
						'items'   => array(
							array( '_id' => 'ac1', 'title' => __( 'Safeguarding', 'light-of-future' ), 'text' => __( 'Child protection and safeguarding are coordinated within our education and skills programmes, so children are protected in everything we do.', 'light-of-future' ) ),
							array( '_id' => 'ac2', 'title' => __( 'Financial accountability', 'light-of-future' ), 'text' => __( 'Funds are managed transparently, used directly for the mission, and reported to donors within agreed timeframes.', 'light-of-future' ) ),
							array( '_id' => 'ac3', 'title' => __( 'Responsible communication', 'light-of-future' ), 'text' => __( 'Stories and photos are shared only with consent, protecting the dignity and privacy of the people we serve.', 'light-of-future' ) ),
						),
					)
				),
				lof_closing_cta(),
			),
		),
		'programmes'         => array(
			__( 'Programmes', 'light-of-future' ),
			'publish',
			array(
				lof_page_hero( __( 'Our work', 'light-of-future' ), __( 'Programmes', 'light-of-future' ), __( 'Eleven programmes that restore hope, dignity and self-reliance.', 'light-of-future' ) ),
				lof_el( 'lof-impact', array( 'eyebrow' => '', 'title' => '', 'strip' => 'yes', 'tone' => 'earth' ) ),
				lof_el(
					'lof-intro',
					array(
						'eyebrow'    => __( 'Our approach', 'light-of-future' ),
						'title'      => __( 'From relief to self-reliance.', 'light-of-future' ),
						'statement'  => __( 'We meet urgent needs first, then walk with families until they can stand on their own.', 'light-of-future' ),
						'btn1_label' => '',
						'tone'       => 'white',
						'panels'     => array(
							array( '_id' => 'p1', 'kicker' => '01', 'title' => __( 'Meet urgent needs', 'light-of-future' ), 'text' => __( 'Safe houses, food, medical care and hygiene kits for those who need them most.', 'light-of-future' ) ),
							array( '_id' => 'p2', 'kicker' => '02', 'title' => __( 'Open doors', 'light-of-future' ), 'text' => __( 'Education, talent platforms and awareness of rights, so people can see and seize opportunity.', 'light-of-future' ) ),
							array( '_id' => 'p3', 'kicker' => '03', 'title' => __( 'Build self-reliance', 'light-of-future' ), 'text' => __( 'Hand skills training, organic farming and business start-ups for a lasting way out of poverty.', 'light-of-future' ) ),
						),
					)
				),
				lof_el( 'lof-programmes', array( 'eyebrow' => __( '11 programmes', 'light-of-future' ), 'title' => __( 'Explore our programmes', 'light-of-future' ), 'count' => 24, 'tone' => 'cream' ) ),
				lof_el(
					'lof-donate',
					array(
						'eyebrow' => __( 'Support a programme', 'light-of-future' ),
						'title'   => __( 'Choose an amount', 'light-of-future' ),
						'intro'   => __( 'Tell us which programme you would like to support, or let us use your gift where it is needed most.', 'light-of-future' ),
						'target'  => lof_link( '/donate/' ),
						'tone'    => 'earth',
					)
				),
				lof_closing_cta(),
			),
		),
		'talent-development' => array(
			__( 'Talent Development', 'light-of-future' ),
			'publish',
			array(
				lof_page_hero( __( 'Talent development', 'light-of-future' ), __( 'Growing young talent', 'light-of-future' ), __( 'Encouraging youth talents through sports, music and creative arts.', 'light-of-future' ) ),
				lof_el(
					'lof-cards',
					array(
						'eyebrow' => __( 'Talent areas', 'light-of-future' ),
						'title'   => __( 'Where young people shine', 'light-of-future' ),
						'columns' => '3',
						'tone'    => 'white',
						'items'   => array(
							array( '_id' => 't1', 'title' => __( 'Sports', 'light-of-future' ), 'text' => __( 'Teamwork, discipline and confidence through sport.', 'light-of-future' ) ),
							array( '_id' => 't2', 'title' => __( 'Music', 'light-of-future' ), 'text' => __( 'Singing, choir and music, a passion close to our founder\'s heart.', 'light-of-future' ) ),
							array( '_id' => 't3', 'title' => __( 'Creative arts', 'light-of-future' ), 'text' => __( 'Space for young people to create, perform and express themselves.', 'light-of-future' ) ),
						),
					)
				),
				lof_el(
					'lof-cta',
					array(
						'eyebrow'    => __( 'Sponsor a young talent', 'light-of-future' ),
						'title'      => __( 'Help a young person discover what they can do', 'light-of-future' ),
						'btn1_label' => __( 'Talk to us', 'light-of-future' ),
						'btn1_url'   => lof_link( '/contact/' ),
						'btn2_label' => __( 'Donate', 'light-of-future' ),
						'btn2_url'   => lof_link( '/donate/' ),
						'tone'       => 'sun',
					)
				),
			),
		),
		'impact'             => array(
			__( 'Impact', 'light-of-future' ),
			'publish',
			array(
				lof_page_hero( __( 'Our impact', 'light-of-future' ), __( 'Hope you can count', 'light-of-future' ) ),
				lof_el( 'lof-impact', array( 'eyebrow' => '', 'title' => __( 'What your support has made possible', 'light-of-future' ), 'show_source' => 'yes', 'tone' => 'earth' ) ),
				lof_el( 'lof-stories', array( 'eyebrow' => __( 'Behind the numbers', 'light-of-future' ), 'title' => __( 'Real people, real change', 'light-of-future' ), 'tone' => 'cream' ) ),
				lof_closing_cta(),
			),
		),
		'stories'            => array(
			__( 'Stories', 'light-of-future' ),
			'publish',
			array(
				lof_page_hero( __( 'Impact stories', 'light-of-future' ), __( 'Real people, real change', 'light-of-future' ), __( 'These are just a few of the lives changed through your support.', 'light-of-future' ) ),
				lof_el( 'lof-impact', array( 'eyebrow' => '', 'title' => '', 'strip' => 'yes', 'tone' => 'earth' ) ),
				lof_el( 'lof-stories', array( 'eyebrow' => '', 'title' => '', 'count' => 24, 'layout' => 'featured', 'tone' => 'cream' ) ),
				lof_el(
					'lof-cards',
					array(
						'eyebrow' => __( 'How we tell stories', 'light-of-future' ),
						'title'   => __( 'With dignity and consent', 'light-of-future' ),
						'columns' => '3',
						'tone'    => 'white',
						'items'   => array(
							array( '_id' => 's1', 'title' => __( 'Consent first', 'light-of-future' ), 'text' => __( 'Every story and photo is shared with the person\'s consent, and a guardian\'s consent for children.', 'light-of-future' ) ),
							array( '_id' => 's2', 'title' => __( 'Dignity, not pity', 'light-of-future' ), 'text' => __( 'We show people as capable and strong, and never share details that could put them at risk.', 'light-of-future' ) ),
							array( '_id' => 's3', 'title' => __( 'Honest updates', 'light-of-future' ), 'text' => __( 'We report back to donors within agreed timeframes on how their support was used.', 'light-of-future' ) ),
						),
					)
				),
				lof_closing_cta(),
			),
		),
		'donate'             => array(
			__( 'Donate', 'light-of-future' ),
			'publish',
			array(
				lof_page_hero( __( 'Donate', 'light-of-future' ), __( 'Support the future', 'light-of-future' ), __( 'Your gift builds safe homes, keeps children in school and brings clean water and care to vulnerable families.', 'light-of-future' ) ),
				lof_el( 'lof-donate', array( 'eyebrow' => __( 'Choose an amount', 'light-of-future' ), 'title' => __( 'Give in Kenya Shillings', 'light-of-future' ), 'target' => lof_link( '/contact/' ), 'note' => __( 'Secure online giving through Paystack (M-PESA and card) is being set up. For now, choosing an amount takes you to our contact form and we will guide you.', 'light-of-future' ), 'image' => lof_photo( 'story-precious-school-shoes' ), 'tone' => 'earth' ) ),
				lof_el(
					'lof-cards',
					array(
						'eyebrow' => __( 'Where your gift goes', 'light-of-future' ),
						'title'   => __( 'Real needs, real change', 'light-of-future' ),
						'columns' => '3',
						'tone'    => 'white',
						'items'   => array(
							array( '_id' => 'd1', 'title' => __( 'Decent houses', 'light-of-future' ), 'text' => __( 'Safe homes for widows, widowers and total orphans, like Philister and Elijah.', 'light-of-future' ), 'link_label' => __( 'Read Philister\'s story', 'light-of-future' ), 'link' => lof_link( '/stories/philister/' ) ),
							array( '_id' => 'd2', 'title' => __( 'School for every child', 'light-of-future' ), 'text' => __( 'School fees, uniforms and shoes so vulnerable children stay in class.', 'light-of-future' ), 'link_label' => __( 'Read Precious\'s story', 'light-of-future' ), 'link' => lof_link( '/stories/precious/' ) ),
							array( '_id' => 'd3', 'title' => __( 'Health and dignity', 'light-of-future' ), 'text' => __( 'Medical support for those in crisis and hygiene kits for adolescent girls.', 'light-of-future' ) ),
							array( '_id' => 'd4', 'title' => __( 'Clean water', 'light-of-future' ), 'text' => __( 'Water and sanitation projects that keep families healthy.', 'light-of-future' ) ),
							array( '_id' => 'd5', 'title' => __( 'Food for the vulnerable', 'light-of-future' ), 'text' => __( 'Food donations for vulnerable children and the elderly.', 'light-of-future' ) ),
							array( '_id' => 'd6', 'title' => __( 'A way out of poverty', 'light-of-future' ), 'text' => __( 'Hand skills training and business start-ups that build self-reliance.', 'light-of-future' ) ),
						),
					)
				),
				lof_el(
					'lof-cards',
					array(
						'eyebrow' => __( 'Ways to give', 'light-of-future' ),
						'title'   => __( 'Give in the way that suits you', 'light-of-future' ),
						'columns' => '4',
						'tone'    => 'sand',
						'items'   => array(
							array( '_id' => 'w1', 'kicker' => '01', 'title' => __( 'Online', 'light-of-future' ), 'text' => __( 'M-PESA and card through Paystack - launching soon.', 'light-of-future' ) ),
							array( '_id' => 'w2', 'kicker' => '02', 'title' => __( 'Talk to us', 'light-of-future' ), 'text' => __( 'Call or write and we will guide you through giving today.', 'light-of-future' ), 'link_label' => __( 'Contact us', 'light-of-future' ), 'link' => lof_link( '/contact/' ) ),
							array( '_id' => 'w3', 'kicker' => '03', 'title' => __( 'Give in kind', 'light-of-future' ), 'text' => __( 'Food, school supplies, sanitary pads or building materials.', 'light-of-future' ), 'link_label' => __( 'Arrange a donation', 'light-of-future' ), 'link' => lof_link( '/contact/' ) ),
							array( '_id' => 'w4', 'kicker' => '04', 'title' => __( 'Partner or sponsor', 'light-of-future' ), 'text' => __( 'Sponsor a child, a programme or a project as a church, business or group.', 'light-of-future' ), 'link_label' => __( 'Get involved', 'light-of-future' ), 'link' => lof_link( '/get-involved/' ) ),
						),
					)
				),
				lof_el(
					'lof-cards',
					array(
						'eyebrow' => __( 'Why give with confidence', 'light-of-future' ),
						'title'   => __( 'Transparent and accountable', 'light-of-future' ),
						'columns' => '3',
						'tone'    => 'white',
						'items'   => array(
							array( '_id' => 't1', 'title' => __( 'Registered', 'light-of-future' ), 'text' => __( 'Registered with the NGO Board of Kenya on 23 May 2025 as a charitable organisation.', 'light-of-future' ) ),
							array( '_id' => 't2', 'title' => __( 'Accountable', 'light-of-future' ), 'text' => __( 'Responsible stewardship of donor funds, with reports to donors within agreed timeframes.', 'light-of-future' ) ),
							array( '_id' => 't3', 'title' => __( 'Community-led', 'light-of-future' ), 'text' => __( 'We work hand in hand with communities, churches and local groups, never in isolation.', 'light-of-future' ) ),
						),
					)
				),
				lof_el(
					'lof-cards',
					array(
						'eyebrow' => __( 'Questions about giving', 'light-of-future' ),
						'title'   => __( 'Giving FAQ', 'light-of-future' ),
						'style'   => 'faq',
						'tone'    => 'cream',
						'items'   => array(
							array( '_id' => 'q1', 'title' => __( 'Can I give by M-PESA?', 'light-of-future' ), 'text' => __( 'Yes. M-PESA and card giving through Paystack is being set up. Until it goes live, contact us and we will guide you.', 'light-of-future' ) ),
							array( '_id' => 'q2', 'title' => __( 'Can I choose which programme my gift supports?', 'light-of-future' ), 'text' => __( 'Yes. Tell us the programme you would like to support, or let us use your gift where it is needed most.', 'light-of-future' ) ),
							array( '_id' => 'q3', 'title' => __( 'Can I give food or items instead of money?', 'light-of-future' ), 'text' => __( 'Yes. Contact us to arrange donations of food, school supplies, sanitary pads or other items.', 'light-of-future' ) ),
							array( '_id' => 'q4', 'title' => __( 'Will I hear how my gift was used?', 'light-of-future' ), 'text' => __( 'Yes. Accountability is one of our values: we report back to donors within agreed timeframes.', 'light-of-future' ) ),
						),
					)
				),
				lof_el( 'lof-impact', array( 'eyebrow' => '', 'title' => '', 'strip' => 'yes', 'tone' => 'earth' ) ),
				lof_el( 'lof-form', array( 'eyebrow' => __( 'Ready to give?', 'light-of-future' ), 'title' => __( 'Talk to us about your gift', 'light-of-future' ), 'interest' => 'donate', 'button' => __( 'Send', 'light-of-future' ), 'tone' => 'white' ) ),
			),
		),
		'get-involved'       => array(
			__( 'Get Involved', 'light-of-future' ),
			'publish',
			array(
				lof_page_hero( __( 'Get involved', 'light-of-future' ), __( 'Shine a light with us', 'light-of-future' ), __( 'There are many ways to stand with vulnerable families in Kenya.', 'light-of-future' ) ),
				lof_el( 'lof-impact', array( 'eyebrow' => '', 'title' => '', 'strip' => 'yes', 'tone' => 'earth' ) ),
				lof_el(
					'lof-cards',
					array(
						'eyebrow' => '',
						'title'   => __( 'Ways to help', 'light-of-future' ),
						'columns' => '4',
						'tone'    => 'white',
						'items'   => array(
							array( '_id' => 'g1', 'title' => __( 'Volunteer', 'light-of-future' ), 'text' => __( 'Give your time and skills alongside our team in the community.', 'light-of-future' ), 'link_label' => __( 'Ask about volunteering', 'light-of-future' ), 'link' => lof_link( '/contact/' ) ),
							array( '_id' => 'g2', 'title' => __( 'Partner', 'light-of-future' ), 'text' => __( 'Organisations, churches and businesses can partner with us on programmes.', 'light-of-future' ), 'link_label' => __( 'Talk about a partnership', 'light-of-future' ), 'link' => lof_link( '/contact/' ) ),
							array( '_id' => 'g3', 'title' => __( 'Sponsor', 'light-of-future' ), 'text' => __( 'Sponsor a child\'s schooling or a young person\'s talent.', 'light-of-future' ), 'link_label' => __( 'Ask about sponsorship', 'light-of-future' ), 'link' => lof_link( '/contact/' ) ),
							array( '_id' => 'g4', 'title' => __( 'Fundraise', 'light-of-future' ), 'text' => __( 'Rally your friends, church or workplace behind a cause.', 'light-of-future' ), 'link_label' => __( 'Start a fundraiser', 'light-of-future' ), 'link' => lof_link( '/contact/' ) ),
						),
					)
				),
				lof_el( 'lof-form', array( 'eyebrow' => __( 'Take the first step', 'light-of-future' ), 'title' => __( 'Tell us how you would like to help', 'light-of-future' ), 'interest' => 'volunteer', 'button' => __( 'Send', 'light-of-future' ), 'tone' => 'cream' ) ),
				lof_closing_cta(),
			),
		),
		'contact'            => array(
			__( 'Contact', 'light-of-future' ),
			'publish',
			array(
				lof_page_hero( __( 'Contact', 'light-of-future' ), __( 'We would love to hear from you', 'light-of-future' ) ),
				lof_el( 'lof-form', array( 'eyebrow' => __( 'Write to us', 'light-of-future' ), 'title' => __( 'Send us a message', 'light-of-future' ), 'tone' => 'white' ) ),
				lof_el( 'lof-contact', array( 'eyebrow' => '', 'title' => __( 'Other ways to reach us', 'light-of-future' ), 'align' => 'left', 'tone' => 'cream' ) ),
			),
		),
		'faq'                => array(
			__( 'FAQ', 'light-of-future' ),
			'publish',
			array(
				lof_page_hero( __( 'FAQ', 'light-of-future' ), __( 'Questions and answers', 'light-of-future' ) ),
				lof_el(
					'lof-cards',
					array(
						'eyebrow' => '',
						'title'   => '',
						'style'   => 'faq',
						'tone'    => 'white',
						'items'   => array(
							array( '_id' => 'f1', 'title' => __( 'Is Light of Future Foundation registered?', 'light-of-future' ), 'text' => __( 'Yes. Light of Future Foundation was formally registered with the NGO Board of Kenya on 23 May 2025 as a charitable organisation.', 'light-of-future' ) ),
							array( '_id' => 'f2', 'title' => __( 'Who do you support?', 'light-of-future' ), 'text' => __( 'Orphans, widows, widowers, vulnerable children and families, adolescent girls, the elderly and people living with disabilities.', 'light-of-future' ) ),
							array( '_id' => 'f3', 'title' => __( 'Where do you work?', 'light-of-future' ), 'text' => __( 'Our office is in Ruai, along Kangundo Road, Nairobi. We work with communities in Nairobi and in Inungo village.', 'light-of-future' ) ),
							array( '_id' => 'f4', 'title' => __( 'How can I donate?', 'light-of-future' ), 'text' => __( 'Secure online giving by M-PESA and card through Paystack is being set up. Until then, please contact us and we will guide you.', 'light-of-future' ) ),
							array( '_id' => 'f5', 'title' => __( 'Can I choose which programme my gift supports?', 'light-of-future' ), 'text' => __( 'Yes. Tell us the programme you would like to support, or let us use your gift where it is needed most.', 'light-of-future' ) ),
						),
					)
				),
				lof_closing_cta(),
			),
		),
		// Drafts: pages planned in the brief that wait for the foundation's content.
		'projects'           => array( __( 'Projects', 'light-of-future' ), 'draft', array( lof_page_hero( __( 'Projects', 'light-of-future' ), __( 'Our projects', 'light-of-future' ) ) ) ),
		'gallery'            => array( __( 'Gallery', 'light-of-future' ), 'draft', array( lof_page_hero( __( 'Gallery', 'light-of-future' ), __( 'A glimpse into our work', 'light-of-future' ) ), lof_el( 'lof-gallery', array( 'eyebrow' => '', 'title' => '', 'gallery' => array_values( array_filter( array( lof_photo( 'story-philister-home' ), lof_photo( 'story-elijah-new-home' ), lof_photo( 'story-precious-school-shoes' ), lof_photo( 'founder-francis-ouma-sikuku' ) ) ) ), 'tone' => 'white' ) ) ) ),
		'resources'          => array( __( 'Resources & Reports', 'light-of-future' ), 'draft', array( lof_page_hero( __( 'Resources', 'light-of-future' ), __( 'Reports and documents', 'light-of-future' ) ) ) ),
	);
}

/**
 * Plain HTML fallback (shown if Elementor is ever deactivated).
 *
 * @param array $sections Sections.
 * @return string
 */
function lof_setup_fallback_html( $sections ) {
	$html = '';
	foreach ( $sections as $section ) {
		$s = $section['elements'][0]['elements'][0]['settings'];
		if ( ! empty( $s['title'] ) ) {
			$html .= '<h2>' . esc_html( $s['title'] ) . '</h2>';
		}
		foreach ( array( 'intro', 'text' ) as $k ) {
			if ( ! empty( $s[ $k ] ) ) {
				$html .= '<p>' . esc_html( $s[ $k ] ) . '</p>';
			}
		}
		if ( ! empty( $s['body'] ) ) {
			$html .= wp_kses_post( $s['body'] );
		}
		if ( ! empty( $s['items'] ) ) {
			foreach ( $s['items'] as $item ) {
				$html .= '<h3>' . esc_html( $item['title'] ) . '</h3>' . ( ! empty( $item['text'] ) ? '<p>' . esc_html( $item['text'] ) . '</p>' : '' );
			}
		}
	}
	return $html;
}

/**
 * Create missing pages with their Elementor layouts and set the home page.
 *
 * @return string[] Report lines.
 */
function lof_setup_pages() {
	$report  = array();
	$created = 0;
	$version = defined( 'ELEMENTOR_VERSION' ) ? ELEMENTOR_VERSION : '3.0.0';
	foreach ( lof_setup_page_defs() as $slug => $def ) {
		list( $title, $status, $sections ) = $def;
		$existing = get_page_by_path( $slug, OBJECT, 'page' );
		if ( $existing ) {
			continue;
		}
		$id = wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_status'  => $status,
				'post_title'   => $title,
				'post_name'    => $slug,
				'post_content' => lof_setup_fallback_html( $sections ),
			),
			true
		);
		if ( is_wp_error( $id ) ) {
			/* translators: %s: page title */
			$report[] = sprintf( __( 'Could not create %s.', 'light-of-future' ), $title );
			continue;
		}
		update_post_meta( $id, '_wp_page_template', 'elementor_header_footer' );
		update_post_meta( $id, '_elementor_edit_mode', 'builder' );
		update_post_meta( $id, '_elementor_template_type', 'wp-page' );
		update_post_meta( $id, '_elementor_version', $version );
		update_post_meta( $id, '_elementor_data', wp_slash( wp_json_encode( $sections ) ) );
		update_post_meta( $id, '_lof_setup_page', $slug );
		++$created;
	}
	/* translators: %d: number of pages */
	$report[] = sprintf( _n( '%d page created (existing pages were left unchanged).', '%d pages created (existing pages were left unchanged).', $created, 'light-of-future' ), $created );

	$home = get_page_by_path( 'home', OBJECT, 'page' );
	if ( $home && 'page' !== get_option( 'show_on_front' ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $home->ID );
		$report[] = __( 'Home page set.', 'light-of-future' );
	}
	if ( class_exists( '\Elementor\Plugin' ) ) {
		\Elementor\Plugin::$instance->files_manager->clear_cache();
	}
	return $report;
}

/**
 * Create the three menus if their locations are empty.
 *
 * @return string[] Report lines.
 */
function lof_setup_menus() {
	$report    = array();
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	$menus     = array(
		'lof-footer'  => array( __( 'Footer quick links', 'light-of-future' ), array( 'about-us', 'leadership', 'programmes', 'talent-development', 'impact', 'stories', 'donate', 'get-involved', 'faq', 'contact' ) ),
	);
	foreach ( $menus as $location => $menu ) {
		if ( ! empty( $locations[ $location ] ) && wp_get_nav_menu_object( $locations[ $location ] ) ) {
			continue;
		}
		$existing = wp_get_nav_menu_object( $menu[0] );
		$menu_id  = $existing ? (int) $existing->term_id : wp_create_nav_menu( $menu[0] );
		if ( is_wp_error( $menu_id ) ) {
			continue;
		}
		if ( ! $existing ) {
			foreach ( $menu[1] as $slug ) {
				$page = get_page_by_path( $slug, OBJECT, 'page' );
				if ( $page && 'publish' === $page->post_status ) {
					wp_update_nav_menu_item(
						$menu_id,
						0,
						array(
							'menu-item-title'     => get_the_title( $page ),
							'menu-item-object'    => 'page',
							'menu-item-object-id' => $page->ID,
							'menu-item-type'      => 'post_type',
							'menu-item-status'    => 'publish',
						)
					);
				}
			}
		}
		$locations[ $location ] = $menu_id;
		/* translators: %s: menu name */
		$report[] = sprintf( __( '%s ready.', 'light-of-future' ), $menu[0] );
	}
	set_theme_mod( 'nav_menu_locations', $locations );
	return $report;
}

/**
 * Bundled photos (theme assets/images/photos/): slug => [alt text, caption].
 *
 * @return array
 */
function lof_bundled_photos() {
	return array(
		'story-elijah-new-home'       => array( __( 'Elijah walking outside his newly built home', 'light-of-future' ), __( 'Elijah at his new home.', 'light-of-future' ) ),
		'story-philister-home'        => array( __( 'Philister standing at the door of her new house', 'light-of-future' ), __( 'Philister outside her new home.', 'light-of-future' ) ),
		'story-precious-school-shoes' => array( __( 'Precious holding up her new school shoes and socks', 'light-of-future' ), __( 'New school shoes for Precious (shared with her guardian\'s consent).', 'light-of-future' ) ),
		'founder-francis-ouma-sikuku' => array( __( 'Francis Ouma Sikuku, founder of Light of Future Foundation', 'light-of-future' ), __( 'Francis Ouma Sikuku, Founder.', 'light-of-future' ) ),
		'leader-francis-ouma-sikuku'  => array( __( 'Portrait of Francis Ouma Sikuku', 'light-of-future' ), __( 'Francis Ouma Sikuku, Founder & Executive Director.', 'light-of-future' ) ),
		'leader-edith-atieno'         => array( __( 'Portrait of Edith Atieno', 'light-of-future' ), __( 'Edith Atieno, Education & Skills Development Coordinator.', 'light-of-future' ) ),
		'leader-nixon-omondo-owino'   => array( __( 'Portrait of Nixon Omondo Owino', 'light-of-future' ), __( 'Nixon Omondo Owino, General Operations Manager & Chief Adviser.', 'light-of-future' ) ),
		'leader-frederick-otieno'     => array( __( 'Portrait of Frederick Otieno', 'light-of-future' ), __( 'Frederick Otieno, Finance Manager.', 'light-of-future' ) ),
		'leader-vincent-okoth-okallo' => array( __( 'Portrait of Vincent Okoth Okallo', 'light-of-future' ), __( 'Vincent Okoth Okallo, Administration Manager & General Secretary.', 'light-of-future' ) ),
	);
}

/**
 * Elementor Media value for a bundled photo that has been imported.
 *
 * @param string $slug Photo slug.
 * @return array
 */
function lof_photo( $slug ) {
	$ids = get_option( 'lof_photo_ids', array() );
	$id  = is_array( $ids ) && isset( $ids[ $slug ] ) ? (int) $ids[ $slug ] : 0;
	if ( ! $id || 'attachment' !== get_post_type( $id ) ) {
		return array();
	}
	return array( 'id' => $id, 'url' => (string) wp_get_attachment_url( $id ) );
}

/**
 * Copy bundled photos into the Media Library once.
 *
 * @return string[] Report.
 */
function lof_import_photos() {
	require_once ABSPATH . 'wp-admin/includes/image.php';
	$ids   = get_option( 'lof_photo_ids', array() );
	$ids   = is_array( $ids ) ? $ids : array();
	$added = 0;
	foreach ( lof_bundled_photos() as $slug => $text ) {
		if ( ! empty( $ids[ $slug ] ) && 'attachment' === get_post_type( (int) $ids[ $slug ] ) ) {
			continue;
		}
		$path = LOF_THEME_DIR . '/assets/images/photos/' . $slug . '.webp';
		if ( ! file_exists( $path ) ) {
			continue;
		}
		$upload = wp_upload_bits( $slug . '.webp', null, file_get_contents( $path ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- local theme file.
		if ( ! empty( $upload['error'] ) ) {
			continue;
		}
		$att = wp_insert_attachment(
			array(
				'post_mime_type' => 'image/webp',
				'post_title'     => $text[0],
				'post_excerpt'   => $text[1],
				'post_status'    => 'inherit',
			),
			$upload['file']
		);
		if ( is_wp_error( $att ) || ! $att ) {
			continue;
		}
		wp_update_attachment_metadata( $att, wp_generate_attachment_metadata( $att, $upload['file'] ) );
		update_post_meta( $att, '_wp_attachment_image_alt', $text[0] );
		$ids[ $slug ] = (int) $att;
		++$added;
	}
	update_option( 'lof_photo_ids', $ids, false );

	// Featured images for starter items, only where none is set.
	$map = array(
		array( 'lof_story', 'philister', 'story-philister-home' ),
		array( 'lof_story', 'elijah', 'story-elijah-new-home' ),
		array( 'lof_story', 'precious', 'story-precious-school-shoes' ),
		array( 'lof_leader', 'francis-ouma-sikuku', 'leader-francis-ouma-sikuku' ),
		array( 'lof_leader', 'vincent-okoth-okallo', 'leader-vincent-okoth-okallo' ),
		array( 'lof_leader', 'frederick-otieno', 'leader-frederick-otieno' ),
		array( 'lof_leader', 'nixon-omondo-owino', 'leader-nixon-omondo-owino' ),
		array( 'lof_leader', 'edith-atieno', 'leader-edith-atieno' ),
		array( 'lof_programme', 'decent-houses', 'story-elijah-new-home' ),
		array( 'lof_programme', 'health-medical-support', 'story-elijah-new-home' ),
		array( 'lof_programme', 'education-support', 'story-precious-school-shoes' ),

	);
	$fr = get_posts( array( 'post_type' => 'lof_leader', 'name' => 'francis-ouma-sikuku', 'post_status' => 'any', 'posts_per_page' => 1, 'fields' => 'ids' ) );
	if ( $fr && ! empty( $ids['founder-francis-ouma-sikuku'] ) && ! empty( $ids['leader-francis-ouma-sikuku'] ) && (int) get_post_thumbnail_id( $fr[0] ) === (int) $ids['founder-francis-ouma-sikuku'] ) {
		set_post_thumbnail( $fr[0], (int) $ids['leader-francis-ouma-sikuku'] );
	}
	foreach ( $map as $row ) {
		$found = get_posts( array( 'post_type' => $row[0], 'name' => $row[1], 'post_status' => 'any', 'posts_per_page' => 1, 'fields' => 'ids' ) );
		if ( $found && ! has_post_thumbnail( $found[0] ) && ! empty( $ids[ $row[2] ] ) ) {
			set_post_thumbnail( $found[0], (int) $ids[ $row[2] ] );
		}
	}
	/* translators: %d: number of photos */
	return array( sprintf( _n( '%d photo added to the Media Library.', '%d photos added to the Media Library.', $added, 'light-of-future' ), $added ) );
}

/**
 * Apply the latest layouts to pages created by setup (with backup).
 */
function lof_apply_design() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Not allowed.', 'light-of-future' ), 403 );
	}
	check_admin_referer( 'lof_apply_design' );
	$report = lof_import_photos();
	if ( function_exists( 'lof_core_seed_content' ) ) {
		$report = array_merge( $report, lof_core_seed_content() );
		$report = array_merge( $report, array_slice( lof_import_photos(), 1 ) ); // Thumbnails for any newly seeded items.
	}
	$updated = 0;
	foreach ( lof_setup_page_defs() as $slug => $def ) {
		$page = get_page_by_path( $slug, OBJECT, 'page' );
		if ( ! $page || ! get_post_meta( $page->ID, '_lof_setup_page', true ) ) {
			continue;
		}
		$old = get_post_meta( $page->ID, '_elementor_data', true );
		if ( $old ) {
			update_post_meta( $page->ID, '_lof_layout_backup', wp_slash( $old ) );
			update_post_meta( $page->ID, '_lof_layout_backup_date', current_time( 'mysql' ) );
		}
		update_post_meta( $page->ID, '_elementor_data', wp_slash( wp_json_encode( $def[2] ) ) );
		update_post_meta( $page->ID, '_elementor_edit_mode', 'builder' );
		update_post_meta( $page->ID, '_wp_page_template', 'elementor_header_footer' );
		delete_post_meta( $page->ID, '_elementor_css' );
		wp_update_post( array( 'ID' => $page->ID, 'post_content' => lof_setup_fallback_html( $def[2] ) ) );
		++$updated;
	}
	/* translators: %d: number of pages */
	$report[] = sprintf( _n( '%d page updated to the latest design (previous layout backed up).', '%d pages updated to the latest design (previous layouts backed up).', $updated, 'light-of-future' ), $updated );
	$report   = array_merge( $report, lof_setup_pages(), lof_setup_menus(), lof_rebuild_primary_menu(), lof_write_performance_rules() );
	update_option( 'lof_design_version', LOF_THEME_VERSION, false );
	if ( class_exists( '\Elementor\Plugin' ) ) {
		\Elementor\Plugin::$instance->files_manager->clear_cache();
	}
	set_transient( 'lof_setup_report', $report, 300 );
	wp_safe_redirect( admin_url( 'themes.php?page=lof-setup' ) );
	exit;
}
add_action( 'admin_post_lof_apply_design', 'lof_apply_design' );

/**
 * Main menu structure with dropdowns: [label, page slug, children slugs].
 *
 * @return array
 */
function lof_primary_menu_tree() {
	return array(
		array( __( 'About', 'light-of-future' ), 'about-us', array( 'about-us', 'leadership', 'faq' ) ),
		array( __( 'Our Work', 'light-of-future' ), 'programmes', array( 'programmes', 'talent-development', 'impact', 'stories' ) ),
		array( __( 'Get Involved', 'light-of-future' ), 'get-involved', array() ),
		array( __( 'Contact', 'light-of-future' ), 'contact', array() ),
	);
}

/**
 * Add one page to a menu.
 *
 * @param int    $menu_id Menu.
 * @param string $slug    Page slug.
 * @param int    $parent  Parent item ID.
 * @param string $label   Label (defaults to page title).
 * @param int    $order   Position.
 * @return int Item ID or 0.
 */
function lof_menu_add_page( $menu_id, $slug, $parent = 0, $label = '', $order = 0 ) {
	$page = get_page_by_path( $slug, OBJECT, 'page' );
	if ( ! $page || 'publish' !== $page->post_status ) {
		return 0;
	}
	$id = wp_update_nav_menu_item(
		$menu_id,
		0,
		array(
			'menu-item-title'     => $label ? $label : get_the_title( $page ),
			'menu-item-object'    => 'page',
			'menu-item-object-id' => $page->ID,
			'menu-item-type'      => 'post_type',
			'menu-item-status'    => 'publish',
			'menu-item-parent-id' => (int) $parent,
			'menu-item-position'  => (int) $order,
		)
	);
	return is_wp_error( $id ) ? 0 : (int) $id;
}

/**
 * Rebuild the main menu created by setup ("Main menu") with dropdowns.
 * A menu with any other name is treated as hand-made and left alone.
 *
 * @return string[] Report.
 */
function lof_rebuild_primary_menu() {
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	$menu      = ! empty( $locations['lof-primary'] ) ? wp_get_nav_menu_object( $locations['lof-primary'] ) : false;
	$setup     = __( 'Main menu', 'light-of-future' );
	if ( $menu && $menu->name !== $setup && 'Main menu' !== $menu->name ) {
		return array( __( 'Main menu left unchanged (it was edited by hand).', 'light-of-future' ) );
	}
	if ( ! $menu ) {
		$menu_id = wp_create_nav_menu( $setup );
		if ( is_wp_error( $menu_id ) ) {
			return array();
		}
		$menu = wp_get_nav_menu_object( $menu_id );
	}
	foreach ( (array) wp_get_nav_menu_items( $menu->term_id ) as $item ) {
		wp_delete_post( $item->ID, true );
	}
	$pos = 1;
	foreach ( lof_primary_menu_tree() as $node ) {
		$parent = lof_menu_add_page( $menu->term_id, $node[1], 0, $node[0], $pos++ );
		if ( $parent ) {
			foreach ( $node[2] as $child ) {
				lof_menu_add_page( $menu->term_id, $child, $parent, '', $pos++ );
			}
		}
	}
	$locations['lof-primary'] = (int) $menu->term_id;
	set_theme_mod( 'nav_menu_locations', $locations );
	return array( __( 'Main menu rebuilt with About and Our Work dropdowns.', 'light-of-future' ) );
}
