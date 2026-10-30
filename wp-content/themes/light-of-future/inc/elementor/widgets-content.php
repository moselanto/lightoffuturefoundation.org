<?php
/**
 * Light of Future Elementor widgets: content widgets (hero, heading,
 * feature, cards/FAQ, call to action, contact details).
 *
 * @package LightOfFuture
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;

/**
 * Hero.
 */
class LOF_Widget_Hero extends LOF_Widget_Base {
	public function get_name() {
		return 'lof-hero';
	}
	public function get_title() {
		return __( 'LOF Hero', 'light-of-future' );
	}
	public function get_icon() {
		return 'eicon-header';
	}
	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Content', 'light-of-future' ) ) );
		$this->add_control( 'eyebrow', array( 'label' => __( 'Small label', 'light-of-future' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Light of Future Foundation', 'light-of-future' ), 'label_block' => true ) );
		$this->add_control( 'title', array( 'label' => __( 'Heading', 'light-of-future' ), 'type' => Controls_Manager::TEXTAREA, 'rows' => 2, 'default' => __( 'Bringing hope and opportunity', 'light-of-future' ) ) );
		$this->add_control( 'highlight', array( 'label' => __( 'Highlighted words (gold)', 'light-of-future' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'hope', 'light-of-future' ), 'description' => __( 'Words from the heading to colour gold.', 'light-of-future' ) ) );
		$this->add_control( 'text', array( 'label' => __( 'Supporting text', 'light-of-future' ), 'type' => Controls_Manager::TEXTAREA, 'rows' => 3, 'default' => '' ) );
		$this->add_control(
			'heading_tag',
			array(
				'label'   => __( 'Heading level', 'light-of-future' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h1',
				'options' => array( 'h1' => 'H1', 'h2' => 'H2' ),
			)
		);
		$this->add_button_controls( 'btn1', __( 'Explore Our Work', 'light-of-future' ), '/programmes/', __( 'Main button', 'light-of-future' ) );
		$this->add_button_controls( 'btn2', __( 'Support the Future', 'light-of-future' ), '/donate/', __( 'Second button', 'light-of-future' ) );
		$this->add_control( 'image', array( 'label' => __( 'Photo (optional)', 'light-of-future' ), 'type' => Controls_Manager::MEDIA, 'separator' => 'before', 'description' => __( 'Without a photo the hero shows the brand sunrise artwork.', 'light-of-future' ) ) );
		$this->add_control(
			'variant',
			array(
				'label'   => __( 'Layout', 'light-of-future' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'home',
				'options' => array(
					'home' => __( 'Home (tall)', 'light-of-future' ),
					'page' => __( 'Inner page banner', 'light-of-future' ),
				),
			)
		);
		$this->add_control( 'collage_heading', array( 'label' => __( 'Photo collage (home layout)', 'light-of-future' ), 'type' => Controls_Manager::HEADING, 'separator' => 'before' ) );
		$this->add_control( 'collage1', array( 'label' => __( 'Collage photo 1 (large)', 'light-of-future' ), 'type' => Controls_Manager::MEDIA ) );
		$this->add_control( 'collage2', array( 'label' => __( 'Collage photo 2', 'light-of-future' ), 'type' => Controls_Manager::MEDIA ) );
		$this->add_control( 'collage3', array( 'label' => __( 'Collage photo 3', 'light-of-future' ), 'type' => Controls_Manager::MEDIA ) );
		$this->add_control( 'badge_heading', array( 'label' => __( 'Trust badges', 'light-of-future' ), 'type' => Controls_Manager::HEADING, 'separator' => 'before' ) );
		$this->add_control( 'badge1', array( 'label' => __( 'Badge 1', 'light-of-future' ), 'type' => Controls_Manager::TEXT, 'label_block' => true ) );
		$this->add_control( 'badge2', array( 'label' => __( 'Badge 2', 'light-of-future' ), 'type' => Controls_Manager::TEXT, 'label_block' => true ) );
		$this->add_control( 'badge3', array( 'label' => __( 'Badge 3', 'light-of-future' ), 'type' => Controls_Manager::TEXT, 'label_block' => true ) );
		$this->end_controls_section();

		$this->start_controls_section( 'style_section', array( 'label' => __( 'Style', 'light-of-future' ), 'tab' => Controls_Manager::TAB_STYLE ) );
		$this->add_responsive_control(
			'min_height',
			array(
				'label'      => __( 'Minimum height', 'light-of-future' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh' ),
				'range'      => array( 'px' => array( 'min' => 240, 'max' => 1000 ), 'vh' => array( 'min' => 20, 'max' => 100 ) ),
				'selectors'  => array( '{{WRAPPER}} .lof-hero' => 'min-height: {{SIZE}}{{UNIT}};' ),
			)
		);
		$this->add_control(
			'overlay',
			array(
				'label'     => __( 'Photo darkening', 'light-of-future' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 0.9, 'step' => 0.05 ) ),
				'default'   => array( 'size' => 0.55 ),
				'selectors' => array( '{{WRAPPER}} .lof-hero__shade' => 'opacity: {{SIZE}};' ),
			)
		);
		$this->add_control( 'title_color', array( 'label' => __( 'Heading colour', 'light-of-future' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .lof-hero__title' => 'color: {{VALUE}};' ) ) );
		$this->add_group_control( Group_Control_Typography::get_type(), array( 'name' => 'title_typo', 'selector' => '{{WRAPPER}} .lof-hero__title' ) );
		$this->end_controls_section();
	}
	protected function render() {
		$s       = $this->get_settings_for_display();
		$variant = isset( $s['variant'] ) && 'page' === $s['variant'] ? 'page' : 'home';
		$tag     = isset( $s['heading_tag'] ) && 'h2' === $s['heading_tag'] ? 'h2' : 'h1';
		$title   = esc_html( (string) $s['title'] );
		if ( ! empty( $s['highlight'] ) ) {
			$hl  = esc_html( $s['highlight'] );
			$pos = stripos( $title, $hl );
			if ( false !== $pos ) {
				$title = substr( $title, 0, $pos ) . '<span class="lof-hl">' . substr( $title, $pos, strlen( $hl ) ) . '</span>' . substr( $title, $pos + strlen( $hl ) );
			}
		}
		$has_img = $this->has_media( isset( $s['image'] ) ? $s['image'] : array() );
		$collage = array();
		foreach ( array( 'collage1', 'collage2', 'collage3' ) as $ck ) {
			if ( ! empty( $s[ $ck ] ) && $this->has_media( $s[ $ck ] ) ) {
				$collage[] = $s[ $ck ];
			}
		}
		$collage = 'home' === $variant && ! $has_img ? $collage : array();
		printf( '<section class="lof-hero lof-hero--%1$s%2$s%3$s">', esc_attr( $variant ), $has_img ? ' has-photo' : '', $collage ? ' has-collage' : '' );
		if ( $has_img ) {
			echo '<div class="lof-hero__media">';
			$this->render_media( $s['image'], 'lof-hero', 'lof-hero__img', 'home' === $variant );
			echo '<span class="lof-hero__shade"></span></div>';
		} else {
			echo '<div class="lof-hero__art" aria-hidden="true"><span class="lof-hero__sun"></span><span class="lof-hero__rays"></span><span class="lof-hero__band lof-hero__band--1"></span><span class="lof-hero__band lof-hero__band--2"></span><span class="lof-hero__band lof-hero__band--3"></span></div>';
		}
		echo '<div class="lof-container lof-hero__inner"><div class="lof-hero__content">';
		if ( ! empty( $s['eyebrow'] ) ) {
			printf( '<p class="lof-eyebrow lof-hero__eyebrow">%s</p>', esc_html( $s['eyebrow'] ) );
		}
		printf( '<%1$s class="lof-hero__title">%2$s</%1$s>', tag_escape( $tag ), nl2br( $title ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above.
		if ( ! empty( $s['text'] ) ) {
			printf( '<p class="lof-hero__text">%s</p>', nl2br( esc_html( $s['text'] ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		echo '<div class="lof-btn-row">';
		$this->render_button( $s, 'btn1', 'sun' );
		$this->render_button( $s, 'btn2', 'ghost' );
		echo '</div>';
		$badges = array_filter( array( isset( $s['badge1'] ) ? $s['badge1'] : '', isset( $s['badge2'] ) ? $s['badge2'] : '', isset( $s['badge3'] ) ? $s['badge3'] : '' ) );
		if ( $badges ) {
			echo '<ul class="lof-hero__badges">';
			foreach ( $badges as $b ) {
				printf( '<li>%s</li>', esc_html( $b ) );
			}
			echo '</ul>';
		}
		echo '</div>';
		if ( $collage ) {
			echo '<div class="lof-hero__collage">';
			foreach ( $collage as $i => $m ) {
				printf( '<figure class="lof-hero__shot lof-hero__shot--%d">', (int) $i + 1 );
				$this->render_media( $m, 0 === $i ? 'large' : 'lof-card', 'lof-hero__shot-img', 0 === $i );
				echo '</figure>';
			}
			echo '<span class="lof-hero__seal" aria-hidden="true"><img src="' . esc_url( lof_brand_url( 'symbol-dark.svg' ) ) . '" alt="" width="80" height="80" loading="lazy" decoding="async"></span>';
			echo '</div>';
		}
		echo '</div></section>';
	}
}

/**
 * Section heading.
 */
class LOF_Widget_Heading extends LOF_Widget_Base {
	public function get_name() {
		return 'lof-heading';
	}
	public function get_title() {
		return __( 'LOF Section Heading', 'light-of-future' );
	}
	public function get_icon() {
		return 'eicon-heading';
	}
	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Content', 'light-of-future' ) ) );
		$this->add_heading_controls( __( 'Small label', 'light-of-future' ), __( 'Section heading', 'light-of-future' ), '' );
		$this->add_layout_controls();
		$this->end_controls_section();
		$this->add_style_controls();
	}
	protected function render() {
		$s = $this->get_settings_for_display();
		$this->open_section( $s, 'lof-section--compact' );
		$this->render_heading( $s );
		$this->close_section();
	}
}

/**
 * Feature: image + rich text + optional quote and button.
 */
class LOF_Widget_Feature extends LOF_Widget_Base {
	public function get_name() {
		return 'lof-feature';
	}
	public function get_title() {
		return __( 'LOF Story Feature (image + text)', 'light-of-future' );
	}
	public function get_icon() {
		return 'eicon-image-box';
	}
	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Content', 'light-of-future' ) ) );
		$this->add_control( 'eyebrow', array( 'label' => __( 'Small label', 'light-of-future' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Who we are', 'light-of-future' ) ) );
		$this->add_control( 'title', array( 'label' => __( 'Heading', 'light-of-future' ), 'type' => Controls_Manager::TEXTAREA, 'rows' => 2, 'default' => __( 'Heading', 'light-of-future' ) ) );
		$this->add_control( 'body', array( 'label' => __( 'Text', 'light-of-future' ), 'type' => Controls_Manager::WYSIWYG, 'default' => '<p>' . __( 'Add your text here.', 'light-of-future' ) . '</p>' ) );
		$this->add_control( 'quote', array( 'label' => __( 'Pull quote (optional)', 'light-of-future' ), 'type' => Controls_Manager::TEXTAREA, 'rows' => 3 ) );
		$this->add_control( 'quote_by', array( 'label' => __( 'Quote by', 'light-of-future' ), 'type' => Controls_Manager::TEXT ) );
		$this->add_button_controls( 'btn1', '', '', __( 'Button', 'light-of-future' ) );
		$this->add_control( 'image', array( 'label' => __( 'Image', 'light-of-future' ), 'type' => Controls_Manager::MEDIA, 'separator' => 'before' ) );
		$this->add_control( 'image_caption', array( 'label' => __( 'Image caption', 'light-of-future' ), 'type' => Controls_Manager::TEXT ) );
		$this->add_control(
			'image_side',
			array(
				'label'   => __( 'Image side', 'light-of-future' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => array( 'left' => __( 'Left', 'light-of-future' ), 'right' => __( 'Right', 'light-of-future' ) ),
			)
		);
		$this->add_control(
			'tone',
			array(
				'label'   => __( 'Background', 'light-of-future' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'cream',
				'options' => array( 'cream' => __( 'Cream', 'light-of-future' ), 'white' => __( 'White', 'light-of-future' ), 'sand' => __( 'Sand', 'light-of-future' ), 'earth' => __( 'Earth Brown', 'light-of-future' ) ),
			)
		);
		$this->end_controls_section();
		$this->add_style_controls();
	}
	protected function render() {
		$s    = $this->get_settings_for_display();
		$side = isset( $s['image_side'] ) && 'right' === $s['image_side'] ? 'right' : 'left';
		$this->open_section( $s, 'lof-feature lof-feature--img-' . $side );
		echo '<div class="lof-feature__grid">';
		echo '<figure class="lof-feature__media lof-reveal">';
		$this->render_media( isset( $s['image'] ) ? $s['image'] : array(), 'large', 'lof-feature__img' );
		if ( ! empty( $s['image_caption'] ) ) {
			printf( '<figcaption>%s</figcaption>', esc_html( $s['image_caption'] ) );
		}
		echo '</figure><div class="lof-feature__body lof-reveal">';
		if ( ! empty( $s['eyebrow'] ) ) {
			printf( '<p class="lof-eyebrow">%s</p>', esc_html( $s['eyebrow'] ) );
		}
		if ( ! empty( $s['title'] ) ) {
			printf( '<h2 class="lof-section__title">%s</h2>', nl2br( esc_html( $s['title'] ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		echo '<div class="lof-prose">' . wp_kses_post( (string) $s['body'] ) . '</div>';
		if ( ! empty( $s['quote'] ) ) {
			printf( '<blockquote class="lof-quote"><p>%1$s</p>%2$s</blockquote>', esc_html( $s['quote'] ), ! empty( $s['quote_by'] ) ? '<cite>' . esc_html( $s['quote_by'] ) . '</cite>' : '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		echo '<div class="lof-btn-row">';
		$this->render_button( $s, 'btn1', isset( $s['tone'] ) && 'earth' === $s['tone'] ? 'sun' : 'earth' );
		echo '</div></div></div>';
		$this->close_section();
	}
}

/**
 * Cards from a repeater (values, ways to help, get-involved paths) or FAQ.
 */
class LOF_Widget_Cards extends LOF_Widget_Base {
	public function get_name() {
		return 'lof-cards';
	}
	public function get_title() {
		return __( 'LOF Cards / FAQ', 'light-of-future' );
	}
	public function get_icon() {
		return 'eicon-gallery-grid';
	}
	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Content', 'light-of-future' ) ) );
		$this->add_heading_controls( '', __( 'Heading', 'light-of-future' ), '' );
		$r = new Repeater();
		$r->add_control( 'kicker', array( 'label' => __( 'Number or label', 'light-of-future' ), 'type' => Controls_Manager::TEXT ) );
		$r->add_control( 'title', array( 'label' => __( 'Title / question', 'light-of-future' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Card title', 'light-of-future' ), 'label_block' => true ) );
		$r->add_control( 'text', array( 'label' => __( 'Text / answer', 'light-of-future' ), 'type' => Controls_Manager::TEXTAREA, 'rows' => 3 ) );
		$r->add_control( 'link_label', array( 'label' => __( 'Link text', 'light-of-future' ), 'type' => Controls_Manager::TEXT ) );
		$r->add_control( 'link', array( 'label' => __( 'Link', 'light-of-future' ), 'type' => Controls_Manager::URL ) );
		$this->add_control(
			'items',
			array(
				'label'       => __( 'Cards', 'light-of-future' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $r->get_controls(),
				'title_field' => '{{{ title }}}',
				'default'     => array( array( 'title' => __( 'Card title', 'light-of-future' ) ) ),
			)
		);
		$this->add_control(
			'columns',
			array(
				'label'   => __( 'Columns', 'light-of-future' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '3',
				'options' => array( '2' => '2', '3' => '3', '4' => '4' ),
			)
		);
		$this->add_control(
			'style',
			array(
				'label'   => __( 'Display as', 'light-of-future' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'card',
				'options' => array( 'card' => __( 'Cards', 'light-of-future' ), 'faq' => __( 'FAQ (open / close)', 'light-of-future' ) ),
			)
		);
		$this->add_layout_controls();
		$this->end_controls_section();
		$this->add_style_controls();
	}
	protected function render() {
		$s = $this->get_settings_for_display();
		if ( empty( $s['items'] ) || ! is_array( $s['items'] ) ) {
			return;
		}
		$this->open_section( $s );
		$this->render_heading( $s, isset( $s['style'] ) && 'faq' === $s['style'] ? __( 'Frequently asked questions', 'light-of-future' ) : __( 'More information', 'light-of-future' ) );
		if ( isset( $s['style'] ) && 'faq' === $s['style'] ) {
			echo '<div class="lof-faq">';
			foreach ( $s['items'] as $item ) {
				printf( '<details class="lof-faq__item"><summary>%1$s</summary><div class="lof-faq__answer"><p>%2$s</p></div></details>', esc_html( $item['title'] ), nl2br( esc_html( $item['text'] ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			echo '</div>';
		} else {
			$cols = in_array( (string) $s['columns'], array( '2', '3', '4' ), true ) ? (int) $s['columns'] : 3;
			printf( '<div class="lof-grid lof-grid--%d">', (int) $cols );
			foreach ( $s['items'] as $i => $item ) {
				echo '<article class="lof-card lof-card--pillar lof-reveal">';
				if ( ! empty( $item['kicker'] ) ) {
					printf( '<span class="lof-card__kicker">%s</span>', esc_html( $item['kicker'] ) );
				}
				printf( '<h3 class="lof-card__title">%s</h3>', esc_html( $item['title'] ) );
				if ( ! empty( $item['text'] ) ) {
					printf( '<p>%s</p>', nl2br( esc_html( $item['text'] ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
				if ( ! empty( $item['link_label'] ) && ! empty( $item['link']['url'] ) ) {
					$key = 'card_link_' . $i;
					$this->add_link_attributes( $key, $item['link'] );
					$this->add_render_attribute( $key, 'class', 'lof-card__link' );
					printf( '<a %1$s>%2$s</a>', $this->get_render_attribute_string( $key ), esc_html( $item['link_label'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
				echo '</article>';
			}
			echo '</div>';
		}
		$this->close_section();
	}
}

/**
 * Call-to-action band.
 */
class LOF_Widget_CTA extends LOF_Widget_Base {
	public function get_name() {
		return 'lof-cta';
	}
	public function get_title() {
		return __( 'LOF Call to Action', 'light-of-future' );
	}
	public function get_icon() {
		return 'eicon-call-to-action';
	}
	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Content', 'light-of-future' ) ) );
		$this->add_heading_controls( __( 'Support our work', 'light-of-future' ), __( 'Be the light in someone\'s future', 'light-of-future' ), '' );
		$this->add_button_controls( 'btn1', __( 'Donate', 'light-of-future' ), '/donate/', __( 'Main button', 'light-of-future' ) );
		$this->add_button_controls( 'btn2', __( 'Get involved', 'light-of-future' ), '/get-involved/', __( 'Second button', 'light-of-future' ) );
		$this->add_layout_controls( 'sun' );
		$this->end_controls_section();
		$this->add_style_controls();
	}
	protected function render() {
		$s     = $this->get_settings_for_display();
		$align = isset( $s['align'] ) && 'left' === $s['align'] ? 'left' : 'center';
		$this->open_section( $s, 'lof-cta' );
		echo '<div class="lof-cta__box lof-reveal">';
		$this->render_heading( $s );
		printf( '<div class="lof-btn-row lof-btn-row--%s">', esc_attr( $align ) );
		$this->render_button( $s, 'btn1', isset( $s['tone'] ) && 'earth' === $s['tone'] ? 'sun' : 'earth' );
		$this->render_button( $s, 'btn2', 'outline' );
		echo '</div></div>';
		$this->close_section();
	}
}

/**
 * Contact details (from Organisation details) with an optional map.
 */
class LOF_Widget_Contact extends LOF_Widget_Base {
	public function get_name() {
		return 'lof-contact';
	}
	public function get_title() {
		return __( 'LOF Contact Details', 'light-of-future' );
	}
	public function get_icon() {
		return 'eicon-envelope';
	}
	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Content', 'light-of-future' ) ) );
		$this->add_heading_controls( __( 'Contact', 'light-of-future' ), __( 'We would love to hear from you', 'light-of-future' ), '' );
		$this->add_control( 'details_note', array( 'type' => Controls_Manager::RAW_HTML, 'raw' => esc_html__( 'Phone, WhatsApp, email, location and office hours come from Light of Future > Organisation details. Empty fields are hidden.', 'light-of-future' ), 'content_classes' => 'elementor-descriptor' ) );
		$this->add_control( 'map_embed', array( 'label' => __( 'Google Maps embed link (optional)', 'light-of-future' ), 'type' => Controls_Manager::URL, 'description' => __( 'Paste the "Embed a map" src link from Google Maps.', 'light-of-future' ) ) );
		$this->add_layout_controls( 'white' );
		$this->end_controls_section();
		$this->add_style_controls();
	}
	protected function render() {
		$s    = $this->get_settings_for_display();
		$rows = array(
			'phone'        => __( 'Phone', 'light-of-future' ),
			'whatsapp'     => __( 'WhatsApp', 'light-of-future' ),
			'email'        => __( 'Email', 'light-of-future' ),
			'address'      => __( 'Location', 'light-of-future' ),
			'office_hours' => __( 'Office hours', 'light-of-future' ),
		);
		$this->open_section( $s, 'lof-contact' );
		$this->render_heading( $s );
		echo '<div class="lof-contact__grid"><dl class="lof-contact__list lof-reveal">';
		foreach ( $rows as $key => $label ) {
			$value = lof_setting( $key );
			if ( '' === $value ) {
				continue;
			}
			if ( 'phone' === $key ) {
				$html = '<a href="' . esc_url( 'tel:' . preg_replace( '/[^0-9+]/', '', $value ) ) . '">' . esc_html( $value ) . '</a>';
			} elseif ( 'whatsapp' === $key ) {
				$html = '<a href="' . esc_url( 'https://wa.me/' . preg_replace( '/[^0-9]/', '', $value ) ) . '" rel="noopener" target="_blank">' . esc_html( $value ) . '</a>';
			} elseif ( 'email' === $key ) {
				$html = '<a href="' . esc_url( 'mailto:' . antispambot( $value ) ) . '">' . esc_html( antispambot( $value ) ) . '</a>';
			} else {
				$html = esc_html( $value );
			}
			printf( '<div><dt>%1$s</dt><dd>%2$s</dd></div>', esc_html( $label ), $html ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above.
		}
		echo '</dl>';
		$map = isset( $s['map_embed']['url'] ) ? (string) $s['map_embed']['url'] : '';
		if ( $map && 0 === strpos( $map, 'https://www.google.com/maps/embed' ) ) {
			printf( '<iframe class="lof-contact__map" src="%1$s" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="%2$s"></iframe>', esc_url( $map ), esc_attr__( 'Map', 'light-of-future' ) );
		}
		echo '</div>';
		$this->close_section();
	}
}
