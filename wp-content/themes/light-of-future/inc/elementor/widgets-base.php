<?php
/**
 * Light of Future Elementor widgets: shared base class.
 *
 * Every visible heading, paragraph, button, image, colour and spacing value is
 * a control in the Elementor panel. Loaded on `elementor/widgets/register`,
 * so Elementor classes exist here.
 *
 * @package LightOfFuture
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

/**
 * Shared helpers for all Light of Future widgets.
 */
abstract class LOF_Widget_Base extends Widget_Base {

	/**
	 * Category.
	 *
	 * @return string[]
	 */
	public function get_categories() {
		return array( 'light-of-future' );
	}

	/**
	 * Keywords.
	 *
	 * @return string[]
	 */
	public function get_keywords() {
		return array( 'light', 'future', 'lof', 'ngo' );
	}

	/**
	 * Eyebrow, title and intro controls.
	 *
	 * @param string $eyebrow Default eyebrow.
	 * @param string $title   Default title.
	 * @param string $intro   Default intro.
	 */
	protected function add_heading_controls( $eyebrow = '', $title = '', $intro = '' ) {
		$this->add_control( 'eyebrow', array( 'label' => __( 'Small label', 'light-of-future' ), 'type' => Controls_Manager::TEXT, 'default' => $eyebrow, 'label_block' => true ) );
		$this->add_control( 'title', array( 'label' => __( 'Heading', 'light-of-future' ), 'type' => Controls_Manager::TEXTAREA, 'rows' => 2, 'default' => $title ) );
		$this->add_control( 'intro', array( 'label' => __( 'Introduction', 'light-of-future' ), 'type' => Controls_Manager::TEXTAREA, 'rows' => 3, 'default' => $intro ) );
		$this->add_control(
			'heading_tag',
			array(
				'label'   => __( 'Heading level', 'light-of-future' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h2',
				'options' => array( 'h1' => 'H1', 'h2' => 'H2', 'h3' => 'H3' ),
			)
		);
	}

	/**
	 * Tone (background) and alignment controls.
	 *
	 * @param string $tone Default tone.
	 */
	protected function add_layout_controls( $tone = 'cream' ) {
		$this->add_control(
			'tone',
			array(
				'label'   => __( 'Background', 'light-of-future' ),
				'type'    => Controls_Manager::SELECT,
				'default' => $tone,
				'options' => array(
					'cream' => __( 'Cream', 'light-of-future' ),
					'white' => __( 'White', 'light-of-future' ),
					'sand'  => __( 'Sand', 'light-of-future' ),
					'earth' => __( 'Earth Brown', 'light-of-future' ),
					'sun'   => __( 'Sunrise (gold)', 'light-of-future' ),
				),
			)
		);
		$this->add_control(
			'align',
			array(
				'label'   => __( 'Heading alignment', 'light-of-future' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => array(
					'left'   => array( 'title' => __( 'Left', 'light-of-future' ), 'icon' => 'eicon-text-align-left' ),
					'center' => array( 'title' => __( 'Centre', 'light-of-future' ), 'icon' => 'eicon-text-align-center' ),
				),
			)
		);
	}

	/**
	 * Button controls.
	 *
	 * @param string $prefix Control prefix.
	 * @param string $label  Default label.
	 * @param string $url    Default URL.
	 * @param string $title  Panel label.
	 */
	protected function add_button_controls( $prefix, $label, $url, $title ) {
		$this->add_control( $prefix . '_label', array( 'label' => $title, 'type' => Controls_Manager::TEXT, 'default' => $label, 'separator' => 'before' ) );
		$this->add_control( $prefix . '_url', array( 'label' => __( 'Link', 'light-of-future' ), 'type' => Controls_Manager::URL, 'default' => array( 'url' => $url ) ) );
	}

	/**
	 * Style tab: colours, typography and spacing.
	 */
	protected function add_style_controls() {
		$this->start_controls_section( 'style_section', array( 'label' => __( 'Style', 'light-of-future' ), 'tab' => Controls_Manager::TAB_STYLE ) );
		$this->add_control( 'bg_color', array( 'label' => __( 'Custom background colour', 'light-of-future' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .lof-section' => 'background-color: {{VALUE}}; background-image: none;' ) ) );
		$this->add_control( 'title_color', array( 'label' => __( 'Heading colour', 'light-of-future' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .lof-section__title' => 'color: {{VALUE}};' ) ) );
		$this->add_control( 'text_color', array( 'label' => __( 'Text colour', 'light-of-future' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .lof-section__intro, {{WRAPPER}} .lof-section p' => 'color: {{VALUE}};' ) ) );
		$this->add_group_control( Group_Control_Typography::get_type(), array( 'name' => 'title_typo', 'label' => __( 'Heading typography', 'light-of-future' ), 'selector' => '{{WRAPPER}} .lof-section__title' ) );
		$this->add_responsive_control(
			'section_padding',
			array(
				'label'      => __( 'Section padding', 'light-of-future' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'rem', 'vh' ),
				'selectors'  => array( '{{WRAPPER}} .lof-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);
		$this->end_controls_section();
	}

	/**
	 * Open a section wrapper.
	 *
	 * @param array  $s     Settings.
	 * @param string $class Extra class.
	 */
	protected function open_section( $s, $class = '' ) {
		$tone = isset( $s['tone'] ) ? $s['tone'] : 'cream';
		printf( '<section class="lof-section lof-tone-%1$s %2$s">', esc_attr( $tone ), esc_attr( $class ) );
		echo '<div class="lof-container">';
	}

	/**
	 * Close a section wrapper.
	 */
	protected function close_section() {
		echo '</div></section>';
	}

	/**
	 * Print the eyebrow, heading and intro.
	 *
	 * @param array $s Settings.
	 */
	protected function render_heading( $s ) {
		if ( empty( $s['eyebrow'] ) && empty( $s['title'] ) && empty( $s['intro'] ) ) {
			return;
		}
		$align = isset( $s['align'] ) && 'left' === $s['align'] ? 'left' : 'center';
		$tag   = isset( $s['heading_tag'] ) && in_array( $s['heading_tag'], array( 'h1', 'h2', 'h3' ), true ) ? $s['heading_tag'] : 'h2';
		printf( '<header class="lof-section__head lof-align-%s lof-reveal">', esc_attr( $align ) );
		if ( ! empty( $s['eyebrow'] ) ) {
			printf( '<p class="lof-eyebrow">%s</p>', esc_html( $s['eyebrow'] ) );
		}
		if ( ! empty( $s['title'] ) ) {
			printf( '<%1$s class="lof-section__title">%2$s</%1$s>', tag_escape( $tag ), nl2br( esc_html( $s['title'] ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		if ( ! empty( $s['intro'] ) ) {
			printf( '<p class="lof-section__intro">%s</p>', nl2br( esc_html( $s['intro'] ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		echo '</header>';
	}

	/**
	 * Print a button if it has a label and link.
	 *
	 * @param array  $s      Settings.
	 * @param string $prefix Control prefix.
	 * @param string $style  Button style class.
	 */
	protected function render_button( $s, $prefix, $style = 'sun' ) {
		$label = isset( $s[ $prefix . '_label' ] ) ? (string) $s[ $prefix . '_label' ] : '';
		$link  = isset( $s[ $prefix . '_url' ] ) && is_array( $s[ $prefix . '_url' ] ) ? $s[ $prefix . '_url' ] : array();
		if ( '' === $label || empty( $link['url'] ) ) {
			return;
		}
		$key = $prefix . '_link';
		$this->add_link_attributes( $key, $link );
		$this->add_render_attribute( $key, 'class', 'lof-btn lof-btn--' . $style );
		printf( '<a %1$s>%2$s</a>', $this->get_render_attribute_string( $key ), esc_html( $label ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Elementor escapes attributes.
	}

	/**
	 * Whether a Media control holds a real image (not Elementor's placeholder).
	 *
	 * @param mixed $media Media control value.
	 * @return bool
	 */
	protected function has_media( $media ) {
		if ( ! is_array( $media ) ) {
			return false;
		}
		return ! empty( $media['id'] ) || ( ! empty( $media['url'] ) && false === strpos( $media['url'], 'placeholder' ) );
	}

	/**
	 * Print an image from a Media control, or a brand placeholder.
	 *
	 * @param mixed  $media Media control value.
	 * @param string $size  Image size.
	 * @param string $class Class.
	 * @param bool   $eager Load eagerly (above the fold).
	 */
	protected function render_media( $media, $size = 'large', $class = '', $eager = false ) {
		if ( is_array( $media ) && ! empty( $media['id'] ) ) {
			$attrs = array( 'class' => $class, 'loading' => $eager ? 'eager' : 'lazy', 'decoding' => 'async' );
			if ( $eager ) {
				$attrs['fetchpriority'] = 'high';
			}
			echo wp_get_attachment_image( (int) $media['id'], $size, false, $attrs );
			return;
		}
		if ( $this->has_media( $media ) ) {
			printf( '<img src="%1$s" alt="" class="%2$s" loading="%3$s" decoding="async">', esc_url( $media['url'] ), esc_attr( $class ), $eager ? 'eager' : 'lazy' );
			return;
		}
		printf( '<span class="lof-placeholder %1$s" aria-hidden="true"><img src="%2$s" alt="" loading="lazy" decoding="async"></span>', esc_attr( $class ), esc_url( lof_brand_url( 'symbol-light.svg' ) ) );
	}

	/**
	 * Show a hint to editors (only inside the Elementor editor) when a
	 * dynamic widget has nothing to display. Visitors see nothing.
	 *
	 * @param string $message Message.
	 */
	protected function editor_notice( $message ) {
		if ( class_exists( '\Elementor\Plugin' ) && isset( \Elementor\Plugin::$instance->editor ) && \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			printf( '<div class="lof-editor-notice">%s</div>', esc_html( $message ) );
		}
	}
}
