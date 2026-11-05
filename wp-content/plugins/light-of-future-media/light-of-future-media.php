<?php
/**
 * Plugin Name: Light of Future Media
 * Description: Stories, photo galleries, video clips and impact figures from the November 2026 content intake. Adds [lof_media group="..."] and [lof_media_all], updates stories and focus areas once in wp-admin.
 * Version: 1.0.1
 * Author: Light of Future Foundation
 * Text Domain: light-of-future
 *
 * @package LightOfFutureMedia
 */

defined( 'ABSPATH' ) || exit;

define( 'LOFM_VERSION', '1.0.1' );

/**
 * Media groups: title, photos (slug => caption; prefix "!" marks a sensitive image), videos (slug => caption).
 *
 * @return array
 */
function lofm_groups() {
	return array(
		'philister' => array(
			'title'  => __( 'Philister: from a grass-thatched house to a safe home', 'light-of-future' ),
			'photos' => array(
				'philister-old'           => __( 'Philister\'s old grass-thatched house.', 'light-of-future' ),
				'philister-before-framed' => __( 'The house before.', 'light-of-future' ),
				'philister-team'          => __( 'Builders at work on the floor.', 'light-of-future' ),
				'philister-floor2'        => __( 'Levelling the floor.', 'light-of-future' ),
				'philister-floor'         => __( 'The concrete floor goes in.', 'light-of-future' ),
				'philister-side'          => __( 'Walls and roof complete.', 'light-of-future' ),
				'philister-fronta'        => __( 'Fitting the door and windows.', 'light-of-future' ),
				'philister-frontb'        => __( 'The new house takes shape.', 'light-of-future' ),
				'philister-portrait'      => __( 'Philister at the door of her new home.', 'light-of-future' ),
				'philister-finished'      => __( 'The finished, plastered house.', 'light-of-future' ),
			),
			'videos' => array(),
		),
		'elijah' => array(
			'title'  => __( 'Elijah: healing and a new home', 'light-of-future' ),
			'photos' => array(
				'elijah-portrait'   => __( 'Elijah when we met him in December 2023.', 'light-of-future' ),
				'elijah-seated'     => __( 'Elijah in his old house.', 'light-of-future' ),
				'!elijah-s-unwell'  => __( 'Elijah, very unwell, before treatment.', 'light-of-future' ),
				'!elijah-s-burn'    => __( 'The burn on Elijah\'s leg.', 'light-of-future' ),
				'elijah-hospital'   => __( 'Elijah at Ambira Level 3 Hospital.', 'light-of-future' ),
				'elijah-bench2'     => __( 'Waiting on the ward.', 'light-of-future' ),
				'elijah-nurse'      => __( 'A nurse checks Elijah\'s arm.', 'light-of-future' ),
				'elijah-bedside'    => __( 'Daily care in hospital.', 'light-of-future' ),
				'elijah-bandaged'   => __( 'Recovering at home.', 'light-of-future' ),
				'elijah-bedframe'   => __( 'A new bed delivered.', 'light-of-future' ),
				'elijah-bed'        => __( 'Elijah, recovered, on his new bed.', 'light-of-future' ),
				'elijah-cement'     => __( 'Building materials arrive.', 'light-of-future' ),
				'elijah-wall'       => __( 'Plastering the walls.', 'light-of-future' ),
				'elijah-corner'     => __( 'Finishing the base of the house.', 'light-of-future' ),
				'elijah-builders'   => __( 'Local builders at work.', 'light-of-future' ),
				'elijah-doorway'    => __( 'Levelling the doorway.', 'light-of-future' ),
				'elijah-front'      => __( 'New roof, walls and door.', 'light-of-future' ),
				'elijah-greendoor'  => __( 'The finished house.', 'light-of-future' ),
				'elijah-doorclosed' => __( 'A door that locks.', 'light-of-future' ),
				'elijah-athouse'    => __( 'Elijah outside his new home.', 'light-of-future' ),
				'elijah-selfie'     => __( 'The team on a visit.', 'light-of-future' ),
			),
			'videos' => array(
				'elijah-first'     => __( 'Our first visit: a bed, mattress and blanket for Elijah.', 'light-of-future' ),
				'elijah-blood'     => __( 'Giving blood for Elijah at the hospital.', 'light-of-future' ),
				'elijah-bed'       => __( 'Elijah\'s new bed and bedding.', 'light-of-future' ),
				'elijah-materials' => __( 'Materials arrive to start work on the house.', 'light-of-future' ),
				'elijah-thanks'    => __( 'Elijah, recovered, says thank you.', 'light-of-future' ),
			),
		),
		'eunice' => array(
			'title'  => __( 'Eunice and her sons: a decent house', 'light-of-future' ),
			'photos' => array(
				'eunice-before'   => __( 'Eunice and her sons at their old house.', 'light-of-future' ),
				'eunice-son-old'  => __( 'The old house in the dry season.', 'light-of-future' ),
				'eunice-oldhouse' => __( 'The grass-thatched house with a leaking roof.', 'light-of-future' ),
				'eunice-builder'  => __( 'Building the new house.', 'light-of-future' ),
				'eunice-wall'     => __( 'Plastered walls.', 'light-of-future' ),
				'eunice-floor'    => __( 'Finishing the cement floor.', 'light-of-future' ),
				'eunice-after'    => __( 'Eunice and her sons at their new house.', 'light-of-future' ),
				'eunice-room'     => __( 'New chairs and a table.', 'light-of-future' ),
				'eunice-roomb'    => __( 'The furnished living room.', 'light-of-future' ),
				'eunice-thumbs'   => __( 'New bedding and household items.', 'light-of-future' ),
				'eunice-quilt'    => __( 'The family with a quilt sent by a supporter in the USA.', 'light-of-future' ),
				'eunice-quiltb'   => __( 'A happy handover.', 'light-of-future' ),
			),
			'videos' => array(
				'eunice-house' => __( 'Francis, Eunice\'s family and the builders at the new house.', 'light-of-future' ),
				'eunice-items' => __( 'Handing over the household items.', 'light-of-future' ),
			),
		),
		'margaret' => array(
			'title'  => __( 'Margaret: food, light and a fresh start', 'light-of-future' ),
			'photos' => array(
				'margaret-door'   => __( 'Margaret at the door of her house.', 'light-of-future' ),
				'margaret-inside' => __( 'Margaret at home.', 'light-of-future' ),
				'margaret-food'   => __( 'Food and household items for Margaret.', 'light-of-future' ),
				'margaret-foodb'  => __( 'The handover.', 'light-of-future' ),
			),
			'videos' => array(
				'margaret-food'   => __( 'What donors bought for Margaret.', 'light-of-future' ),
				'margaret-thanks' => __( 'Margaret thanks her supporters.', 'light-of-future' ),
			),
		),
		'precious' => array(
			'title'  => __( 'Precious: staying in school', 'light-of-future' ),
			'photos' => array(
				'edu-precious-uniform'   => __( 'Precious in her new school uniform.', 'light-of-future' ),
				'edu-precious-shoes'     => __( 'New school shoes and socks.', 'light-of-future' ),
				'edu-precious-receiving' => __( 'Receiving her school shoes.', 'light-of-future' ),
				'edu-precious-team'      => __( 'Ready for school.', 'light-of-future' ),
				'edu-precious-guardian'  => __( 'Precious with her guardian.', 'light-of-future' ),
			),
			'videos' => array(),
		),
		'education' => array(
			'title'  => __( 'Education support and school materials', 'light-of-future' ),
			'photos' => array(
				'edu-boys'       => __( 'Meshack and Silvester in their school uniforms.', 'light-of-future' ),
				'edu-books'      => __( 'Children with their new exercise books.', 'light-of-future' ),
				'edu-books-team' => __( 'Handing out books and pens.', 'light-of-future' ),
			),
			'videos' => array(
				'books-thanks' => __( '"Thank you to our supporters!"', 'light-of-future' ),
				'books-cheer'  => __( 'New books, big smiles.', 'light-of-future' ),
				'books-kibera' => __( 'Thanks from our partners in Kibera.', 'light-of-future' ),
				'books-talk'   => __( 'A talk at the books distribution.', 'light-of-future' ),
			),
		),
		'pads' => array(
			'title'  => __( 'Sanitary pads for girls', 'light-of-future' ),
			'photos' => array(
				'pads-thumbs'    => __( 'Girls with their sanitary pads.', 'light-of-future' ),
				'pads-community' => __( 'A community distribution.', 'light-of-future' ),
				'pads-school'    => __( 'Handing over pads at a school.', 'light-of-future' ),
				'pads-kits'      => __( 'Schoolgirls with hygiene kits.', 'light-of-future' ),
			),
			'videos' => array(
				'pads-thanks'   => __( 'A class says thank you for books and sanitary pads.', 'light-of-future' ),
				'talent-spoken' => __( '"My body is changing": a spoken-word piece by young women.', 'light-of-future' ),
			),
		),
		'mentorship' => array(
			'title'  => __( 'Mentorship for boys and girls', 'light-of-future' ),
			'photos' => array(
				'ment-listening'   => __( 'Children listening at a mentorship session.', 'light-of-future' ),
				'ment-girls'       => __( 'A girls\' mentorship session.', 'light-of-future' ),
				'ment-boys-mentor' => __( 'A mentor speaks to boys and young men.', 'light-of-future' ),
			),
			'videos' => array(
				'mentor-mic'  => __( 'A mentor speaks to young people (in Swahili).', 'light-of-future' ),
				'mentor-hall' => __( 'A mentorship talk in a full hall.', 'light-of-future' ),
			),
		),
		'talent' => array(
			'title'  => __( 'Talent development', 'light-of-future' ),
			'photos' => array(),
			'videos' => array(
				'talent-valued' => __( '"You are valued": a song by young women.', 'light-of-future' ),
				'talent-spoken' => __( 'A spoken-word piece on menstruation and dignity.', 'light-of-future' ),
			),
		),
		'food' => array(
			'title'  => __( 'Food donations', 'light-of-future' ),
			'photos' => array(
				'food-children' => __( 'Children receive food packs.', 'light-of-future' ),
				'food-family'   => __( 'A family receives food at home.', 'light-of-future' ),
				'food-rice'     => __( 'Portioning rice for families.', 'light-of-future' ),
				'food-beans'    => __( 'Beans ready for packing.', 'light-of-future' ),
				'food-sack'     => __( 'Supplies for the next distribution.', 'light-of-future' ),
				'food-closeup'  => __( 'Packed food bags.', 'light-of-future' ),
			),
			'videos' => array(),
		),
		'water' => array(
			'title'  => __( 'Clean water in Ugenya', 'light-of-future' ),
			'photos' => array(
				'water-boy' => __( 'Fresh water at the Ugenya borehole.', 'light-of-future' ),
			),
			'videos' => array(),
		),
		'goats' => array(
			'title'  => __( 'Community enterprise: the goat feedlot vision', 'light-of-future' ),
			'photos' => array(
				'goats-wide' => __( 'A goat herd grazing.', 'light-of-future' ),
			),
			'videos' => array(
				'goats-vision' => __( '"This is the vision": building sustainability.', 'light-of-future' ),
				'goats-shed'   => __( 'Goats leaving a raised shed.', 'light-of-future' ),
			),
		),
	);
}

/**
 * URL of a bundled asset.
 *
 * @param string $path Relative path.
 * @return string
 */
function lofm_url( $path ) {
	if ( 0 === strpos( $path, 'video/' ) ) {
		for ( $i = 1; $i <= 9; $i++ ) {
			$dir = 'light-of-future-video-' . $i;
			if ( file_exists( WP_PLUGIN_DIR . '/' . $dir . '/' . $path ) ) {
				return plugins_url( $dir . '/' . $path );
			}
		}
	}
	return plugins_url( 'assets/' . $path, __FILE__ );
}

/**
 * Render a group.
 *
 * @param string $key   Group key.
 * @param bool   $title Show title.
 * @return string
 */
function lofm_render_group( $key, $title = false ) {
	$groups = lofm_groups();
	if ( empty( $groups[ $key ] ) ) {
		return '';
	}
	$g   = $groups[ $key ];
	$out = '<section class="lofm" id="lofm-' . esc_attr( $key ) . '">';
	if ( $title ) {
		$out .= '<h2 class="lofm__title">' . esc_html( $g['title'] ) . '</h2>';
	}
	if ( ! empty( $g['videos'] ) ) {
		$out .= '<div class="lofm__videos">';
		foreach ( $g['videos'] as $slug => $cap ) {
			$out .= sprintf(
				'<figure class="lofm__video"><video controls playsinline preload="none" poster="%1$s"><source src="%2$s" type="video/mp4"></video><figcaption>%3$s</figcaption></figure>',
				esc_url( lofm_url( 'video/' . $slug . '.jpg' ) ),
				esc_url( lofm_url( 'video/' . $slug . '.mp4' ) ),
				esc_html( $cap )
			);
		}
		$out .= '</div>';
	}
	if ( ! empty( $g['photos'] ) ) {
		$out .= '<ul class="lofm__grid">';
		foreach ( $g['photos'] as $slug => $cap ) {
			$sens = 0 === strpos( $slug, '!' );
			$slug = ltrim( $slug, '!' );
			$img  = sprintf(
				'<a class="lofm__link" href="%1$s" target="_blank" rel="noopener"><img src="%2$s" alt="%3$s" loading="lazy" decoding="async" width="560" height="420"></a>',
				esc_url( lofm_url( 'photos/' . $slug . '.webp' ) ),
				esc_url( lofm_url( 'photos/' . $slug . '-sm.webp' ) ),
				esc_attr( $cap )
			);
			if ( $sens ) {
				$img = '<div class="lofm__sens"><div class="lofm__sens-media" aria-hidden="true">' . $img . '</div><button type="button" class="lofm__reveal" data-lofm-reveal>' . esc_html__( 'Sensitive image: tap to view', 'light-of-future' ) . '</button></div>';
			}
			$out .= '<li class="lofm__item"><figure>' . $img . '<figcaption>' . esc_html( $cap ) . '</figcaption></figure></li>';
		}
		$out .= '</ul>';
	}
	return $out . '</section>';
}

add_shortcode(
	'lof_media',
	function ( $atts ) {
		$atts = shortcode_atts( array( 'group' => '', 'title' => '0' ), $atts, 'lof_media' );
		$out  = '';
		foreach ( array_filter( array_map( 'trim', explode( ',', $atts['group'] ) ) ) as $key ) {
			$out .= lofm_render_group( sanitize_key( $key ), '1' === $atts['title'] );
		}
		return $out;
	}
);

add_shortcode(
	'lof_media_all',
	function () {
		$groups = lofm_groups();
		$nav    = '<nav class="lofm-nav" aria-label="' . esc_attr__( 'Gallery sections', 'light-of-future' ) . '"><ul>';
		foreach ( $groups as $key => $g ) {
			$nav .= '<li><a href="#lofm-' . esc_attr( $key ) . '">' . esc_html( $g['title'] ) . '</a></li>';
		}
		$nav .= '</ul></nav>';
		$out = '';
		foreach ( array_keys( $groups ) as $key ) {
			$out .= lofm_render_group( $key, true );
		}
		return '<div class="lofm-all">' . $nav . $out . '</div>';
	}
);

add_action(
	'wp_enqueue_scripts',
	function () {
		wp_enqueue_style( 'lofm', plugins_url( 'assets/lofm.css', __FILE__ ), array(), LOFM_VERSION );
		wp_enqueue_script( 'lofm', plugins_url( 'assets/lofm.js', __FILE__ ), array(), LOFM_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
	}
);

require_once __DIR__ . '/includes/content.php';
