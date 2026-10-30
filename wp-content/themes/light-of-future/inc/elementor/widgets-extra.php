<?php
/**
 * Light of Future Elementor widgets: giving band, gallery, ribbon and
 * enquiry form.
 *
 * @package LightOfFuture
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;

/**
 * Giving band with suggested amounts.
 */
class LOF_Widget_Donate extends LOF_Widget_Base {
	public function get_name() {
		return 'lof-donate';
	}
	public function get_title() {
		return __( 'LOF Giving Band (amounts)', 'light-of-future' );
	}
	public function get_icon() {
		return 'eicon-price-table';
	}
	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Content', 'light-of-future' ) ) );
		$this->add_heading_controls( __( 'Give today', 'light-of-future' ), __( 'Your gift keeps the light on', 'light-of-future' ), '' );
		$r = new Repeater();
		$r->add_control( 'amount', array( 'label' => __( 'Amount (KES)', 'light-of-future' ), 'type' => Controls_Manager::NUMBER, 'default' => 1000, 'min' => 1 ) );
		$r->add_control( 'label', array( 'label' => __( 'What it helps with', 'light-of-future' ), 'type' => Controls_Manager::TEXT, 'label_block' => true ) );
		$this->add_control(
			'amounts',
			array(
				'label'       => __( 'Suggested amounts', 'light-of-future' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $r->get_controls(),
				'title_field' => 'KES {{{ amount }}}',
				'default'     => array( array( 'amount' => 500 ), array( 'amount' => 1000 ), array( 'amount' => 2500 ), array( 'amount' => 5000 ) ),
			)
		);
		$this->add_control( 'custom_label', array( 'label' => __( '"Other amount" label', 'light-of-future' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Other amount', 'light-of-future' ) ) );
		$this->add_control( 'target', array( 'label' => __( 'Amounts link to', 'light-of-future' ), 'type' => Controls_Manager::URL, 'default' => array( 'url' => '/donate/' ), 'description' => __( 'The chosen amount is added as ?amount= so the donation form can pre-fill it.', 'light-of-future' ) ) );
		$this->add_control( 'note', array( 'label' => __( 'Small print', 'light-of-future' ), 'type' => Controls_Manager::TEXT, 'label_block' => true, 'default' => __( 'Secure giving by M-PESA and card through Paystack.', 'light-of-future' ) ) );
		$this->add_control( 'image', array( 'label' => __( 'Photo (optional)', 'light-of-future' ), 'type' => Controls_Manager::MEDIA ) );
		$this->add_layout_controls( 'earth' );
		$this->end_controls_section();
		$this->add_style_controls();
	}
	protected function render() {
		$s      = $this->get_settings_for_display();
		$target = ! empty( $s['target']['url'] ) ? $s['target']['url'] : lof_donate_url();
		$has    = $this->has_media( isset( $s['image'] ) ? $s['image'] : array() );
		$this->open_section( $s, 'lof-give' . ( $has ? ' lof-give--photo' : '' ) );
		echo '<div class="lof-give__grid">';
		if ( $has ) {
			echo '<figure class="lof-give__media lof-reveal">';
			$this->render_media( $s['image'], 'large', 'lof-give__img' );
			echo '</figure>';
		}
		echo '<div class="lof-give__body lof-reveal">';
		$this->render_heading( array_merge( $s, array( 'align' => $has ? 'left' : ( isset( $s['align'] ) ? $s['align'] : 'center' ) ) ) );
		echo '<ul class="lof-give__amounts">';
		foreach ( (array) $s['amounts'] as $row ) {
			$amount = (int) $row['amount'];
			if ( $amount < 1 ) {
				continue;
			}
			printf(
				'<li><a class="lof-chip" href="%1$s"><strong>KES %2$s</strong>%3$s</a></li>',
				esc_url( add_query_arg( 'amount', $amount, $target ) ),
				esc_html( number_format_i18n( $amount ) ),
				! empty( $row['label'] ) ? '<span>' . esc_html( $row['label'] ) . '</span>' : ''
			);
		}
		if ( ! empty( $s['custom_label'] ) ) {
			printf( '<li><a class="lof-chip lof-chip--other" href="%1$s"><strong>%2$s</strong></a></li>', esc_url( $target ), esc_html( $s['custom_label'] ) );
		}
		echo '</ul>';
		if ( ! empty( $s['note'] ) ) {
			printf( '<p class="lof-give__note">%s</p>', esc_html( $s['note'] ) );
		}
		echo '</div></div>';
		$this->close_section();
	}
}

/**
 * Photo gallery with Elementor's lightbox.
 */
class LOF_Widget_Gallery extends LOF_Widget_Base {
	public function get_name() {
		return 'lof-gallery';
	}
	public function get_title() {
		return __( 'LOF Photo Gallery', 'light-of-future' );
	}
	public function get_icon() {
		return 'eicon-gallery-masonry';
	}
	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Content', 'light-of-future' ) ) );
		$this->add_heading_controls( __( 'Gallery', 'light-of-future' ), __( 'A glimpse into our work', 'light-of-future' ), '' );
		$this->add_control( 'gallery', array( 'label' => __( 'Photos', 'light-of-future' ), 'type' => Controls_Manager::GALLERY, 'description' => __( 'Only use photos with consent from the people shown (guardian consent for children). Captions come from each photo\'s caption in the Media Library.', 'light-of-future' ) ) );
		$this->add_layout_controls( 'white' );
		$this->end_controls_section();
		$this->add_style_controls();
	}
	protected function render() {
		$s    = $this->get_settings_for_display();
		$pics = isset( $s['gallery'] ) && is_array( $s['gallery'] ) ? array_filter( $s['gallery'], function ( $p ) { return ! empty( $p['id'] ); } ) : array();
		if ( ! $pics ) {
			$this->editor_notice( __( 'Choose photos in the Photos field.', 'light-of-future' ) );
			return;
		}
		$this->open_section( $s );
		$this->render_heading( $s );
		$group = 'lof-gallery-' . $this->get_id();
		echo '<ul class="lof-mosaic">';
		foreach ( array_values( $pics ) as $i => $p ) {
			$id      = (int) $p['id'];
			$full    = wp_get_attachment_image_url( $id, 'large' );
			$caption = wp_get_attachment_caption( $id );
			printf(
				'<li class="lof-mosaic__item lof-reveal"><a href="%1$s" data-elementor-open-lightbox="yes" data-elementor-lightbox-slideshow="%2$s"%3$s>%4$s%5$s</a></li>',
				esc_url( $full ),
				esc_attr( $group ),
				$caption ? ' data-elementor-lightbox-title="' . esc_attr( $caption ) . '"' : '',
				wp_get_attachment_image( $id, 'lof-card', false, array( 'loading' => 'lazy' ) ),
				$caption ? '<span class="lof-mosaic__cap">' . esc_html( $caption ) . '</span>' : ''
			); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped parts.
		}
		echo '</ul>';
		$this->close_section();
	}
}

/**
 * Moving ribbon of words (pauses on hover; static with reduced motion).
 */
class LOF_Widget_Ribbon extends LOF_Widget_Base {
	public function get_name() {
		return 'lof-ribbon';
	}
	public function get_title() {
		return __( 'LOF Word Ribbon', 'light-of-future' );
	}
	public function get_icon() {
		return 'eicon-animation-text';
	}
	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Content', 'light-of-future' ) ) );
		$this->add_control( 'words', array( 'label' => __( 'Words (one per line)', 'light-of-future' ), 'type' => Controls_Manager::TEXTAREA, 'rows' => 6, 'default' => "Education\nClean water\nSafe homes\nYouth talent\nDignity\nHope" ) );
		$this->add_control(
			'tone',
			array(
				'label'   => __( 'Background', 'light-of-future' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'sun',
				'options' => array( 'sun' => __( 'Sunrise (gold)', 'light-of-future' ), 'earth' => __( 'Earth Brown', 'light-of-future' ), 'cream' => __( 'Cream', 'light-of-future' ) ),
			)
		);
		$this->end_controls_section();
	}
	protected function render() {
		$s     = $this->get_settings_for_display();
		$words = array_filter( array_map( 'trim', explode( "\n", (string) $s['words'] ) ) );
		if ( ! $words ) {
			return;
		}
		$line = '';
		foreach ( $words as $w ) {
			$line .= '<span>' . esc_html( $w ) . '</span><i aria-hidden="true"></i>';
		}
		printf(
			'<div class="lof-ribbon lof-tone-%1$s"><p class="screen-reader-text">%2$s</p><div class="lof-ribbon__track" aria-hidden="true"><div class="lof-ribbon__set">%3$s</div><div class="lof-ribbon__set">%3$s</div></div><button class="lof-ribbon__pause" type="button" aria-pressed="false" aria-label="%4$s" data-label-pause="%4$s" data-label-play="%5$s"><span aria-hidden="true"></span></button></div>',
			esc_attr( $s['tone'] ),
			esc_html( implode( ', ', $words ) ),
			$line, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above.
			esc_attr__( 'Pause moving text', 'light-of-future' ),
			esc_attr__( 'Play moving text', 'light-of-future' )
		);
	}
}

/**
 * Secure enquiry form (from the Core plugin) with heading and side text.
 */
class LOF_Widget_Form extends LOF_Widget_Base {
	public function get_name() {
		return 'lof-form';
	}
	public function get_title() {
		return __( 'LOF Enquiry Form', 'light-of-future' );
	}
	public function get_icon() {
		return 'eicon-form-horizontal';
	}
	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Content', 'light-of-future' ) ) );
		$this->add_heading_controls( __( 'Write to us', 'light-of-future' ), __( 'Send us a message', 'light-of-future' ), __( 'We read every message and reply as soon as we can.', 'light-of-future' ) );
		$this->add_control(
			'interest',
			array(
				'label'   => __( 'Pre-selected interest', 'light-of-future' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'general',
				'options' => function_exists( 'lof_core_enquiry_types' ) ? lof_core_enquiry_types() : array( 'general' => 'General' ),
			)
		);
		$this->add_control( 'button', array( 'label' => __( 'Button label', 'light-of-future' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Send message', 'light-of-future' ) ) );
		$this->add_control( 'show_details', array( 'label' => __( 'Show contact details beside the form', 'light-of-future' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' ) );
		$this->add_layout_controls( 'white' );
		$this->end_controls_section();
		$this->add_style_controls();
	}
	protected function render() {
		$s = $this->get_settings_for_display();
		if ( ! function_exists( 'lof_core_enquiry_form' ) ) {
			$this->editor_notice( __( 'Activate the Light of Future Core plugin to show the form.', 'light-of-future' ) );
			return;
		}
		$side = isset( $s['show_details'] ) && 'yes' === $s['show_details'];
		$this->open_section( $s, 'lof-formsec' . ( $side ? ' lof-formsec--side' : '' ) );
		echo '<div class="lof-formsec__grid"><div class="lof-formsec__intro">';
		$this->render_heading( array_merge( $s, array( 'align' => 'left' ) ) );
		if ( $side ) {
			echo '<ul class="lof-formsec__details">';
			foreach ( array( 'phone' => 'tel:', 'email' => 'mailto:', 'address' => '' ) as $key => $scheme ) {
				$v = lof_setting( $key );
				if ( '' === $v ) {
					continue;
				}
				if ( 'tel:' === $scheme ) {
					printf( '<li><a href="%1$s">%2$s</a></li>', esc_url( 'tel:' . preg_replace( '/[^0-9+]/', '', $v ) ), esc_html( $v ) );
				} elseif ( 'mailto:' === $scheme ) {
					printf( '<li><a href="%1$s">%2$s</a></li>', esc_url( 'mailto:' . antispambot( $v ) ), esc_html( antispambot( $v ) ) );
				} else {
					printf( '<li>%s</li>', esc_html( $v ) );
				}
			}
			echo '</ul>';
		}
		echo '</div><div class="lof-formsec__form lof-reveal">';
		echo lof_core_enquiry_form( array( 'interest' => $s['interest'], 'button' => $s['button'], 'id' => 'lof-form-' . $this->get_id() ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside.
		echo '</div></div>';
		$this->close_section();
	}
}
