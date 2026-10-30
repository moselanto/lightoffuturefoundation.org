<?php
/**
 * Light of Future Elementor widgets: dynamic widgets that read from the
 * Light of Future Core plugin (programmes, impact figures, stories, leaders,
 * partners). Each hides itself for visitors when nothing approved exists.
 *
 * @package LightOfFuture
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

/**
 * Programmes grid (Light of Future > Programmes).
 */
class LOF_Widget_Programmes extends LOF_Widget_Base {
	public function get_name() {
		return 'lof-programmes';
	}
	public function get_title() {
		return __( 'LOF Programmes', 'light-of-future' );
	}
	public function get_icon() {
		return 'eicon-posts-grid';
	}
	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Content', 'light-of-future' ) ) );
		$this->add_heading_controls( __( 'What we do', 'light-of-future' ), __( 'Our programmes', 'light-of-future' ), '' );
		$this->add_control( 'count', array( 'label' => __( 'How many', 'light-of-future' ), 'type' => Controls_Manager::NUMBER, 'default' => 6, 'min' => 1, 'max' => 24 ) );
		$this->add_control( 'more_label', array( 'label' => __( 'Card link text', 'light-of-future' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Learn more', 'light-of-future' ) ) );
		$this->add_button_controls( 'btn1', '', '', __( 'Button below the grid', 'light-of-future' ) );
		$this->add_layout_controls();
		$this->end_controls_section();
		$this->add_style_controls();
	}
	protected function render() {
		$s     = $this->get_settings_for_display();
		$items = get_posts( array( 'post_type' => 'lof_programme', 'post_status' => 'publish', 'posts_per_page' => max( 1, (int) $s['count'] ), 'orderby' => 'menu_order', 'order' => 'ASC' ) );
		if ( ! $items ) {
			$this->editor_notice( __( 'No programmes yet. Add them under Light of Future > Programmes.', 'light-of-future' ) );
			return;
		}
		$this->open_section( $s );
		$this->render_heading( $s, __( 'Our programmes', 'light-of-future' ) );
		echo '<div class="lof-grid lof-grid--3">';
		foreach ( $items as $i => $p ) {
			$link = get_permalink( $p );
			echo '<article class="lof-card lof-card--programme lof-reveal">';
			printf( '<a class="lof-card__media" href="%s" tabindex="-1" aria-hidden="true">', esc_url( $link ) );
			if ( has_post_thumbnail( $p ) ) {
				echo get_the_post_thumbnail( $p, 'lof-card', array( 'loading' => 'lazy' ) );
			} else {
				printf( '<span class="lof-placeholder"><span class="lof-card__num">%02d</span></span>', (int) $i + 1 );
			}
			echo '</a><div class="lof-card__body">';
			printf( '<h3 class="lof-card__title"><a href="%1$s">%2$s</a></h3>', esc_url( $link ), esc_html( get_the_title( $p ) ) );
			printf( '<p>%s</p>', esc_html( get_the_excerpt( $p ) ) );
			if ( ! empty( $s['more_label'] ) ) {
				printf( '<a class="lof-card__link" href="%1$s">%2$s<span class="screen-reader-text"> %3$s</span></a>', esc_url( $link ), esc_html( $s['more_label'] ), esc_html( get_the_title( $p ) ) );
			}
			echo '</div></article>';
		}
		echo '</div><div class="lof-btn-row lof-btn-row--center">';
		$this->render_button( $s, 'btn1', isset( $s['tone'] ) && 'earth' === $s['tone'] ? 'sun' : 'earth' );
		echo '</div>';
		$this->close_section();
	}
}

/**
 * Verified impact figures (Light of Future > Impact figures).
 */
class LOF_Widget_Impact extends LOF_Widget_Base {
	public function get_name() {
		return 'lof-impact';
	}
	public function get_title() {
		return __( 'LOF Impact Figures', 'light-of-future' );
	}
	public function get_icon() {
		return 'eicon-counter';
	}
	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Content', 'light-of-future' ) ) );
		$this->add_heading_controls( __( 'Our impact', 'light-of-future' ), __( 'Lives changed, one family at a time', 'light-of-future' ), '' );
		$this->add_control( 'show_source', array( 'label' => __( 'Show source under figures', 'light-of-future' ), 'type' => Controls_Manager::SWITCHER, 'default' => '' ) );
		$this->add_control( 'strip', array( 'label' => __( 'Compact trust strip (no heading, divided figures)', 'light-of-future' ), 'type' => Controls_Manager::SWITCHER, 'default' => '' ) );
		$this->add_control( 'note', array( 'type' => Controls_Manager::RAW_HTML, 'raw' => esc_html__( 'Figures come from Light of Future > Impact figures. Only figures ticked "Verified - show on website" appear.', 'light-of-future' ), 'content_classes' => 'elementor-descriptor' ) );
		$this->add_layout_controls( 'earth' );
		$this->end_controls_section();
		$this->add_style_controls();
	}
	protected function render() {
		$s       = $this->get_settings_for_display();
		$metrics = function_exists( 'lof_core_get_metrics' ) ? lof_core_get_metrics() : array();
		if ( ! $metrics ) {
			$this->editor_notice( __( 'No verified impact figures yet. Add them under Light of Future > Impact figures.', 'light-of-future' ) );
			return;
		}
		$this->open_section( $s, 'lof-impact' . ( isset( $s['strip'] ) && 'yes' === $s['strip'] ? ' lof-impact--strip' : '' ) );
		$this->render_heading( $s, __( 'Our impact in numbers', 'light-of-future' ) );
		echo '<dl class="lof-impact__grid">';
		foreach ( $metrics as $m ) {
			echo '<div class="lof-impact__item lof-reveal">';
			printf( '<dt class="lof-impact__label">%s</dt>', esc_html( $m['label'] ) );
			$num = preg_match( '/^\d+$/', $m['value'] ) && (int) $m['value'] < 1900 ? (int) $m['value'] : 0;
			printf( '<dd class="lof-impact__value"><span class="lof-count"%1$s>%2$s</span><span>%3$s</span></dd>', $num ? ' data-count="' . esc_attr( $num ) . '"' : '', esc_html( $m['value'] ), esc_html( $m['suffix'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			if ( isset( $s['show_source'] ) && 'yes' === $s['show_source'] && ( $m['source'] || $m['period'] ) ) {
				printf( '<dd class="lof-impact__source">%s</dd>', esc_html( trim( $m['period'] . ' ' . $m['source'] ) ) );
			}
			echo '</div>';
		}
		echo '</dl>';
		$this->close_section();
	}
}

/**
 * Impact stories (Light of Future > Impact stories).
 */
class LOF_Widget_Stories extends LOF_Widget_Base {
	public function get_name() {
		return 'lof-stories';
	}
	public function get_title() {
		return __( 'LOF Impact Stories', 'light-of-future' );
	}
	public function get_icon() {
		return 'eicon-testimonial-carousel';
	}
	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Content', 'light-of-future' ) ) );
		$this->add_heading_controls( __( 'Stories of change', 'light-of-future' ), __( 'Real people, real change', 'light-of-future' ), '' );
		$this->add_control( 'count', array( 'label' => __( 'How many', 'light-of-future' ), 'type' => Controls_Manager::NUMBER, 'default' => 3, 'min' => 1, 'max' => 24 ) );
		$this->add_control(
			'layout',
			array(
				'label'   => __( 'Layout', 'light-of-future' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'grid',
				'options' => array( 'grid' => __( 'Cards', 'light-of-future' ), 'featured' => __( 'First story large, then cards', 'light-of-future' ) ),
			)
		);
		$this->add_control( 'more_label', array( 'label' => __( 'Card link text', 'light-of-future' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Read the story', 'light-of-future' ) ) );
		$this->add_button_controls( 'btn1', '', '', __( 'Button below the stories', 'light-of-future' ) );
		$this->add_layout_controls( 'sand' );
		$this->end_controls_section();
		$this->add_style_controls();
	}
	protected function render() {
		$s     = $this->get_settings_for_display();
		$items = get_posts( array( 'post_type' => 'lof_story', 'post_status' => 'publish', 'posts_per_page' => max( 1, (int) $s['count'] ) ) );
		if ( ! $items ) {
			$this->editor_notice( __( 'No published stories yet. A story publishes once consent is ticked.', 'light-of-future' ) );
			return;
		}
		$this->open_section( $s );
		$this->render_heading( $s, __( 'Impact stories', 'light-of-future' ) );
		if ( isset( $s['layout'] ) && 'featured' === $s['layout'] ) {
			$f     = array_shift( $items );
			$flink = get_permalink( $f );
			$fquote = (string) get_post_meta( $f->ID, 'lof_quote', true );
			$fplace = (string) get_post_meta( $f->ID, 'lof_location', true );
			echo '<article class="lof-spot lof-reveal">';
			if ( has_post_thumbnail( $f ) ) {
				printf( '<a class="lof-spot__media" href="%1$s" tabindex="-1" aria-hidden="true">%2$s</a>', esc_url( $flink ), get_the_post_thumbnail( $f, 'large', array( 'loading' => 'lazy' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			echo '<div class="lof-spot__body"><p class="lof-eyebrow">' . esc_html__( 'Featured story', 'light-of-future' ) . ( $fplace ? ' &middot; ' . esc_html( $fplace ) : '' ) . '</p>';
			printf( '<h3 class="lof-spot__title"><a href="%1$s">%2$s</a></h3>', esc_url( $flink ), esc_html( get_the_title( $f ) ) );
			printf( '<p class="lof-spot__text">%s</p>', esc_html( $fquote ? $fquote : get_the_excerpt( $f ) ) );
			printf( '<a class="lof-btn lof-btn--earth" href="%1$s">%2$s<span class="screen-reader-text"> %3$s</span></a>', esc_url( $flink ), esc_html( $s['more_label'] ? $s['more_label'] : __( 'Read the story', 'light-of-future' ) ), esc_html( get_the_title( $f ) ) );
			echo '</div></article>';
		}
		echo $items ? '<div class="lof-grid lof-grid--3">' : '<div>';
		foreach ( $items as $p ) {
			$link  = get_permalink( $p );
			$quote = (string) get_post_meta( $p->ID, 'lof_quote', true );
			$place = (string) get_post_meta( $p->ID, 'lof_location', true );
			echo '<article class="lof-card lof-card--story lof-reveal">';
			if ( has_post_thumbnail( $p ) ) {
				printf( '<a class="lof-card__media" href="%1$s" tabindex="-1" aria-hidden="true">%2$s</a>', esc_url( $link ), get_the_post_thumbnail( $p, 'lof-card', array( 'loading' => 'lazy' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			echo '<div class="lof-card__body">';
			if ( $place ) {
				printf( '<p class="lof-eyebrow">%s</p>', esc_html( $place ) );
			}
			printf( '<h3 class="lof-card__title"><a href="%1$s">%2$s</a></h3>', esc_url( $link ), esc_html( get_the_title( $p ) ) );
			printf( '<p>%s</p>', esc_html( $quote ? $quote : get_the_excerpt( $p ) ) );
			if ( ! empty( $s['more_label'] ) ) {
				printf( '<a class="lof-card__link" href="%1$s">%2$s<span class="screen-reader-text"> %3$s</span></a>', esc_url( $link ), esc_html( $s['more_label'] ), esc_html( get_the_title( $p ) ) );
			}
			echo '</div></article>';
		}
		echo '</div><div class="lof-btn-row lof-btn-row--center">';
		$this->render_button( $s, 'btn1', 'earth' );
		echo '</div>';
		$this->close_section();
	}
}

/**
 * Leadership (Light of Future > Leadership, approved only).
 */
class LOF_Widget_Leaders extends LOF_Widget_Base {
	public function get_name() {
		return 'lof-leaders';
	}
	public function get_title() {
		return __( 'LOF Leadership', 'light-of-future' );
	}
	public function get_icon() {
		return 'eicon-person';
	}
	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Content', 'light-of-future' ) ) );
		$this->add_heading_controls( __( 'Leadership', 'light-of-future' ), __( 'The people guiding our work', 'light-of-future' ), '' );
		$this->add_control( 'show_bio', array( 'label' => __( 'Show short bios', 'light-of-future' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' ) );
		$this->add_control(
			'group',
			array(
				'label'   => __( 'Show', 'light-of-future' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array( '' => __( 'Everyone', 'light-of-future' ), 'executive' => __( 'Executive leadership', 'light-of-future' ), 'board' => __( 'Board of Directors', 'light-of-future' ), 'team' => __( 'Management & programme team', 'light-of-future' ) ),
				'description' => __( 'Each person\'s group is set under Light of Future > Leadership.', 'light-of-future' ),
			)
		);
		$this->add_control(
			'layout',
			array(
				'label'   => __( 'Card style', 'light-of-future' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'grid',
				'options' => array( 'grid' => __( 'Portrait cards', 'light-of-future' ), 'row' => __( 'Profile cards (photo beside text)', 'light-of-future' ) ),
			)
		);
		$this->add_layout_controls( 'white' );
		$this->end_controls_section();
		$this->add_style_controls();
	}
	protected function render() {
		$s     = $this->get_settings_for_display();
		$query = array(
			'post_type'      => 'lof_leader',
			'post_status'    => 'publish',
			'posts_per_page' => 30,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'meta_query'     => array( array( 'key' => 'lof_approved', 'value' => '1' ) ), // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
		);
		if ( ! empty( $s['group'] ) ) {
			$query['meta_query'][] = array( 'key' => 'lof_group', 'value' => sanitize_key( $s['group'] ) );
		}
		$items = get_posts( $query );
		if ( ! $items ) {
			$this->editor_notice( __( 'No approved leaders yet. Add them under Light of Future > Leadership and tick "Approved for publication".', 'light-of-future' ) );
			return;
		}
		$this->open_section( $s );
		$this->render_heading( $s, __( 'Our leadership team', 'light-of-future' ) );
		$row  = isset( $s['layout'] ) && 'row' === $s['layout'];
		$cols = $row ? ( count( $items ) > 1 ? 2 : 1 ) : min( 4, max( 2, count( $items ) ) );
		printf( '<div class="lof-grid lof-grid--%1$d%2$s">', (int) $cols, $row ? ' lof-people--row' . ( 1 === $cols ? ' lof-people--single' : '' ) : '' );
		foreach ( $items as $p ) {
			echo '<article class="lof-person lof-reveal' . ( $row ? ' lof-person--row' : '' ) . '"><div class="lof-person__photo">';
			if ( has_post_thumbnail( $p ) ) {
				echo get_the_post_thumbnail( $p, 'lof-portrait', array( 'loading' => 'lazy' ) );
			} else {
				$initials = '';
				foreach ( array_slice( preg_split( '/\s+/', trim( get_the_title( $p ) ) ), 0, 2 ) as $part ) {
					$initials .= function_exists( 'mb_substr' ) ? mb_substr( $part, 0, 1 ) : substr( $part, 0, 1 );
				}
				printf( '<span class="lof-person__initials" aria-hidden="true">%s</span>', esc_html( $initials ) );
			}
			echo '</div><div class="lof-person__info">';
			printf( '<h3 class="lof-person__name">%s</h3>', esc_html( get_the_title( $p ) ) );
			if ( $p->post_excerpt ) {
				printf( '<p class="lof-person__board">%s</p>', esc_html( $p->post_excerpt ) );
			}
			printf( '<p class="lof-person__role">%s</p>', esc_html( (string) get_post_meta( $p->ID, 'lof_role', true ) ) );
			if ( isset( $s['show_bio'] ) && 'yes' === $s['show_bio'] && $p->post_content ) {
				printf( '<div class="lof-person__bio">%s</div>', wp_kses_post( wpautop( $p->post_content ) ) );
			}
			echo '</div></article>';
		}
		echo '</div>';
		$this->close_section();
	}
}

/**
 * Partners (approved logos only; hidden when none).
 */
class LOF_Widget_Partners extends LOF_Widget_Base {
	public function get_name() {
		return 'lof-partners';
	}
	public function get_title() {
		return __( 'LOF Partners', 'light-of-future' );
	}
	public function get_icon() {
		return 'eicon-logo';
	}
	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Content', 'light-of-future' ) ) );
		$this->add_heading_controls( __( 'With thanks', 'light-of-future' ), __( 'Our partners and supporters', 'light-of-future' ), '' );
		$this->add_layout_controls( 'white' );
		$this->end_controls_section();
		$this->add_style_controls();
	}
	protected function render() {
		$s     = $this->get_settings_for_display();
		$items = get_posts(
			array(
				'post_type'      => 'lof_partner',
				'post_status'    => 'publish',
				'posts_per_page' => 40,
				'orderby'        => 'menu_order',
				'order'          => 'ASC',
				'meta_key'       => 'lof_approved', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				'meta_value'     => '1', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			)
		);
		if ( ! $items ) {
			$this->editor_notice( __( 'No approved partners yet. This section stays hidden until one is added.', 'light-of-future' ) );
			return;
		}
		$this->open_section( $s, 'lof-section--compact' );
		$this->render_heading( $s, __( 'Our partners', 'light-of-future' ) );
		echo '<ul class="lof-partners">';
		foreach ( $items as $p ) {
			$url  = (string) get_post_meta( $p->ID, 'lof_url', true );
			$logo = has_post_thumbnail( $p ) ? get_the_post_thumbnail( $p, 'medium', array( 'loading' => 'lazy', 'alt' => get_the_title( $p ) ) ) : esc_html( get_the_title( $p ) );
			echo '<li>' . ( $url ? '<a href="' . esc_url( $url ) . '" rel="noopener" target="_blank">' . $logo . '</a>' : $logo ) . '</li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core image markup or escaped title.
		}
		echo '</ul>';
		$this->close_section();
	}
}
