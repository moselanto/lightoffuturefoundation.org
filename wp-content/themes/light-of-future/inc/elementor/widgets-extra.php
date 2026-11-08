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
		$this->add_control( 'note', array( 'label' => __( 'Small print', 'light-of-future' ), 'type' => Controls_Manager::TEXT, 'label_block' => true, 'default' => __( 'Secure online giving by M-PESA and card through Paystack is being set up. Until then, we will guide you to complete your gift.', 'light-of-future' ) ) );
		$this->add_control( 'image', array( 'label' => __( 'Photo (optional)', 'light-of-future' ), 'type' => Controls_Manager::MEDIA ) );
		$this->add_layout_controls( 'earth' );
		$this->end_controls_section();
		$this->add_style_controls();
	}
	protected function render() {
		$s      = $this->get_settings_for_display();
		$target = ! empty( $s['target']['url'] ) ? $s['target']['url'] : lof_donate_url();
		if ( function_exists( 'lof_pay_enabled' ) && lof_pay_enabled() && is_page( 'donate' ) ) {
			$this->open_section( $s, 'lof-give lof-give--paystack' );
			$tick = '<svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true" focusable="false"><path fill="currentColor" d="M9 16.2 4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4z"/></svg>';
			echo '<div class="lof-pay2">';
			echo '<div class="lof-pay2__info">';
			printf( '<p class="lof-eyebrow">%s</p>', esc_html__( 'Give online', 'light-of-future' ) );
			printf( '<h2 class="lof-pay2__title">%s</h2>', esc_html__( 'Make your gift today', 'light-of-future' ) );
			printf( '<p class="lof-pay2__lead">%s</p>', esc_html__( 'Choose an amount, once, monthly or yearly. Your gift goes to school support, safe homes, food, health care and clean water for families who need it most.', 'light-of-future' ) );
			echo '<ul class="lof-pay2__trust">';
			foreach ( array(
				__( 'Secure checkout by Paystack. Card details never touch our website.', 'light-of-future' ),
				__( 'Registered with the NGO Board of Kenya on 23 May 2025.', 'light-of-future' ),
				__( 'An email receipt for every gift.', 'light-of-future' ),
				__( 'Give anonymously if you prefer, and change or cancel a monthly or yearly gift at any time.', 'light-of-future' ),
			) as $t ) {
				printf( '<li><span class="lof-pay2__tick">%1$s</span>%2$s</li>', $tick, esc_html( $t ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG.
			}
			echo '</ul>';
			printf( '<p class="lof-pay2__methods-k">%s</p><ul class="lof-pay2__methods"><li>M-PESA</li><li>Visa</li><li>Mastercard</li></ul>', esc_html__( 'Pay with', 'light-of-future' ) );
			// 0.63.9: direct giving by M-PESA Paybill or bank transfer, from Light of Future > Settings.
			$lof_pb   = function_exists( 'lof_core_setting' ) ? (string) lof_core_setting( 'mpesa_paybill' ) : '';
			$lof_pba  = function_exists( 'lof_core_setting' ) ? (string) lof_core_setting( 'mpesa_account' ) : '';
			$lof_bank = function_exists( 'lof_core_setting' ) ? (string) lof_core_setting( 'bank_name' ) : '';
			$lof_bacc = function_exists( 'lof_core_setting' ) ? (string) lof_core_setting( 'bank_account' ) : '';
			if ( $lof_pb || $lof_bacc ) {
				printf( '<div class="lof-pay2__direct"><p class="lof-pay2__methods-k">%s</p><dl>', esc_html__( 'Other ways to give', 'light-of-future' ) );
				if ( $lof_pb ) {
					printf( '<div><dt>%1$s</dt><dd>%2$s <strong>%3$s</strong>%4$s</dd></div>', esc_html__( 'M-PESA Paybill', 'light-of-future' ), esc_html__( 'Business no.', 'light-of-future' ), esc_html( $lof_pb ), $lof_pba ? ' &middot; ' . esc_html__( 'Account no.', 'light-of-future' ) . ' <strong>' . esc_html( $lof_pba ) . '</strong>' : '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped parts.
				}
				if ( $lof_bacc ) {
					printf( '<div><dt>%1$s</dt><dd>%2$s &middot; %3$s <strong>%4$s</strong></dd></div>', esc_html__( 'Bank transfer', 'light-of-future' ), esc_html( $lof_bank ? $lof_bank : __( 'Our bank', 'light-of-future' ) ), esc_html__( 'Account no.', 'light-of-future' ), esc_html( $lof_bacc ) );
				}
				echo '</dl></div>';
			}
			printf( '<details class="lof-pay2__manage"><summary>%s</summary>', esc_html__( 'Already give monthly? Manage your gift', 'light-of-future' ) );
			echo do_shortcode( '[lof_manage_giving]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in the shortcode.
			echo '</details>';
			echo '</div><div class="lof-pay2__form">';
			echo do_shortcode( '[lof_donate_form]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in the shortcode.
			echo '</div></div>';
			$this->close_section();
			return;
		}
		$has    = $this->has_media( isset( $s['image'] ) ? $s['image'] : array() );
		$this->open_section( $s, 'lof-give' . ( $has ? ' lof-give--photo lof-give--card' : '' ) );
		if ( $has ) {
			$tick = '<svg viewBox="0 0 24 24" width="14" height="14" aria-hidden="true" focusable="false"><path fill="currentColor" d="M9 16.2 4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4z"/></svg>';
			echo '<div class="lof-give__card lof-reveal"><div class="lof-give__visual">';
			$this->render_media( $s['image'], 'large', 'lof-give__img' );
			echo '<div class="lof-give__overlay">';
			$this->render_heading( array_merge( $s, array( 'align' => 'left' ) ) );
			echo '<ul class="lof-give__trust">';
			foreach ( array(
				__( 'Registered with the NGO Board of Kenya, 23 May 2025', 'light-of-future' ),
				__( 'You choose where your gift goes', 'light-of-future' ),
				( ( 'live' === lof_setting( 'payment_mode', 'off' ) || ( function_exists( 'lof_pay_enabled' ) && lof_pay_enabled() ) ) ? __( 'Secure M-PESA and card payments through Paystack', 'light-of-future' ) : __( 'Our team guides you to complete your gift', 'light-of-future' ) ),
			) as $t ) {
				printf( '<li><span class="lof-give__tick">%1$s</span>%2$s</li>', $tick, esc_html( $t ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG.
			}
			echo '</ul></div></div><div class="lof-give__body">';
			printf( '<p class="lof-give__formtitle">%s</p>', esc_html__( 'Make your gift', 'light-of-future' ) );
		} else {
			echo '<div class="lof-give__grid"><div class="lof-give__body lof-reveal">';
			$this->render_heading( array_merge( $s, array( 'align' => isset( $s['align'] ) ? $s['align'] : 'center' ) ) );
		}
		$amounts = array();
		foreach ( (array) $s['amounts'] as $row ) {
			if ( (int) $row['amount'] > 0 ) {
				$amounts[] = $row;
			}
		}
		$uid   = 'lof-give-' . $this->get_id();
		$funds = array( 'general' => __( 'Where it is needed most', 'light-of-future' ) );
		if ( post_type_exists( 'lof_programme' ) ) {
			foreach ( get_posts( array( 'post_type' => 'lof_programme', 'posts_per_page' => 20, 'orderby' => array( 'menu_order' => 'ASC', 'title' => 'ASC' ), 'no_found_rows' => true ) ) as $prog ) {
				$funds[ $prog->post_name ] = get_the_title( $prog );
			}
		}
		$action = strtok( $target, '?#' );
		printf( '<form class="lof-givepanel" method="get" action="%1$s" data-lof-give>', esc_url( $action ) );
		printf( '<fieldset class="lof-givepanel__step"><legend><span>1</span>%s</legend><div class="lof-give__amounts">', esc_html__( 'Choose an amount (KES)', 'light-of-future' ) );
		foreach ( $amounts as $n => $row ) {
			$amount = (int) $row['amount'];
			printf(
				'<label class="lof-chip"><input type="radio" name="amount" value="%1$d"%2$s><strong>KES %3$s</strong>%4$s</label>',
				$amount,
				1 === $n ? ' checked' : '',
				esc_html( number_format_i18n( $amount ) ),
				! empty( $row['label'] ) ? '<span>' . esc_html( $row['label'] ) . '</span>' : ''
			);
		}
		if ( ! empty( $s['custom_label'] ) ) {
			printf( '<label class="lof-chip lof-chip--other"><input type="radio" name="amount" value="other"%2$s><strong>%1$s</strong></label>', esc_html( $s['custom_label'] ), $amounts ? '' : ' checked' );
		}
		echo '</div>';
		printf( '<p class="lof-givepanel__other"><label for="%1$s-other">%2$s</label><span class="lof-givepanel__kes"><span aria-hidden="true">KES</span><input id="%1$s-other" type="number" name="amount_other" min="50" step="50" inputmode="numeric" placeholder="%3$s"></span></p>', esc_attr( $uid ), esc_html__( 'Your own amount', 'light-of-future' ), esc_attr__( 'e.g. 1500', 'light-of-future' ) );
		echo '</fieldset>';
		printf( '<fieldset class="lof-givepanel__step"><legend><span>2</span>%1$s</legend><label class="screen-reader-text" for="%2$s-fund">%1$s</label><select id="%2$s-fund" name="fund">', esc_html__( 'Choose where it goes', 'light-of-future' ), esc_attr( $uid ) );
		foreach ( $funds as $key => $label ) {
			printf( '<option value="%1$s">%2$s</option>', esc_attr( $key ), esc_html( $label ) );
		}
		echo '</select></fieldset>';
		$lof_live = 'live' === lof_setting( 'payment_mode', 'off' ) || ( function_exists( 'lof_pay_enabled' ) && lof_pay_enabled() );
		printf( '<button type="submit" class="lof-btn lof-btn--sun lof-givepanel__submit">%s</button>', $lof_live ? esc_html__( 'Continue to give', 'light-of-future' ) : esc_html__( 'Ask us how to give', 'light-of-future' ) );
		if ( ! $lof_live ) {
			printf( '<p class="lof-givepanel__how">%s</p>', esc_html__( 'Online payment is not open yet. Choose an amount and where it goes, then send us a short message. Our team replies with how to complete your gift.', 'light-of-future' ) );
		}
		echo '</form>';
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
		$this->add_heading_controls( __( 'Write to us', 'light-of-future' ), __( 'Send us a message', 'light-of-future' ), __( 'We read every message and reply within 2 working days.', 'light-of-future' ) );
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
			echo '<div class="lof-formsec__next"><p class="lof-formsec__next-title">' . esc_html__( 'What happens next', 'light-of-future' ) . '</p><ol>';
			printf( '<li><strong>%1$s</strong><span>%2$s</span></li>', esc_html__( 'You send a message', 'light-of-future' ), esc_html__( 'Tell us a little about yourself and what you have in mind.', 'light-of-future' ) );
			printf( '<li><strong>%1$s</strong><span>%2$s</span></li>', esc_html__( 'We get back to you', 'light-of-future' ), esc_html__( 'We read every message and reply within 2 working days.', 'light-of-future' ) );
			printf( '<li><strong>%1$s</strong><span>%2$s</span></li>', esc_html__( 'We agree the next step', 'light-of-future' ), esc_html__( 'Together we find the best way for you to make a difference.', 'light-of-future' ) );
			echo '</ol></div>';
		}
		echo '</div><div class="lof-formsec__form lof-reveal">';
		echo lof_core_enquiry_form( array( 'interest' => $s['interest'], 'button' => $s['button'], 'id' => 'lof-form-' . $this->get_id() ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside.
		echo '</div></div>';
		$this->close_section();
	}
}

/**
 * Full-width home slider: one photo, headline and call to action per slide,
 * with a figures bar. Uses only the foundation's own photos.
 */
class LOF_Widget_Slider extends LOF_Widget_Base {
	public function get_name() {
		return 'lof-slider';
	}
	public function get_title() {
		return __( 'LOF Home Slider', 'light-of-future' );
	}
	public function get_icon() {
		return 'eicon-slides';
	}
	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Slides', 'light-of-future' ) ) );
		$r = new Repeater();
		$r->add_control( 'image', array( 'label' => __( 'Photo', 'light-of-future' ), 'type' => Controls_Manager::MEDIA, 'description' => __( 'Only photos the foundation has consent to publish.', 'light-of-future' ) ) );
		$r->add_control( 'focus', array( 'label' => __( 'Photo focus', 'light-of-future' ), 'type' => Controls_Manager::SELECT, 'default' => 'center', 'options' => array( 'center' => __( 'Centre', 'light-of-future' ), 'top' => __( 'Top', 'light-of-future' ), 'right' => __( 'Right', 'light-of-future' ), 'left' => __( 'Left', 'light-of-future' ) ) ) );
		$r->add_control( 'tab', array( 'label' => __( 'Short label (progress bar)', 'light-of-future' ), 'type' => Controls_Manager::TEXT ) );
		$r->add_control( 'eyebrow', array( 'label' => __( 'Eyebrow', 'light-of-future' ), 'type' => Controls_Manager::TEXT, 'label_block' => true ) );
		$r->add_control( 'title', array( 'label' => __( 'Headline', 'light-of-future' ), 'type' => Controls_Manager::TEXTAREA, 'rows' => 2 ) );
		$r->add_control( 'highlight', array( 'label' => __( 'Gold words in headline', 'light-of-future' ), 'type' => Controls_Manager::TEXT ) );
		$r->add_control( 'text', array( 'label' => __( 'Text', 'light-of-future' ), 'type' => Controls_Manager::TEXTAREA, 'rows' => 3 ) );
		$r->add_control( 'btn1_label', array( 'label' => __( 'Button 1', 'light-of-future' ), 'type' => Controls_Manager::TEXT ) );
		$r->add_control( 'btn1_url', array( 'label' => __( 'Button 1 link', 'light-of-future' ), 'type' => Controls_Manager::URL ) );
		$r->add_control( 'btn2_label', array( 'label' => __( 'Button 2', 'light-of-future' ), 'type' => Controls_Manager::TEXT ) );
		$r->add_control( 'btn2_url', array( 'label' => __( 'Button 2 link', 'light-of-future' ), 'type' => Controls_Manager::URL ) );
		$this->add_control( 'slides', array( 'label' => __( 'Slides', 'light-of-future' ), 'type' => Controls_Manager::REPEATER, 'fields' => $r->get_controls(), 'title_field' => '{{{ tab || title }}}' ) );
		$this->add_control( 'interval', array( 'label' => __( 'Seconds per slide', 'light-of-future' ), 'type' => Controls_Manager::NUMBER, 'default' => 7, 'min' => 4, 'max' => 15 ) );
		$this->add_control( 'figures_heading', array( 'label' => __( 'Figures bar', 'light-of-future' ), 'type' => Controls_Manager::HEADING, 'separator' => 'before' ) );
		foreach ( array( 1, 2, 3, 4 ) as $n ) {
			/* translators: %d: number */
			$this->add_control( 'fig' . $n, array( 'label' => sprintf( __( 'Figure %d (figure|label)', 'light-of-future' ), $n ), 'type' => Controls_Manager::TEXT, 'label_block' => true ) );
		}
		$this->end_controls_section();
	}
	protected function render() {
		$s      = $this->get_settings_for_display();
		$slides = array();
		foreach ( (array) ( isset( $s['slides'] ) ? $s['slides'] : array() ) as $sl ) {
			if ( ! empty( $sl['title'] ) && $this->has_media( isset( $sl['image'] ) ? $sl['image'] : array() ) ) {
				if ( apply_filters( 'lof_slider_skip_shop', true ) && false !== stripos( (string) $sl['title'], 'Handmade in Kenya' ) ) {
					continue;
				}
				$slides[] = $sl;
			}
		}
		if ( ! $slides ) {
			$this->editor_notice( __( 'Add at least one slide with a photo and a headline.', 'light-of-future' ) );
			return;
		}
		$total = count( $slides );
		$secs  = max( 4, min( 15, (int) ( isset( $s['interval'] ) ? $s['interval'] : 7 ) ) );
		printf( '<section class="lof-cine" data-lof-cine data-interval="%1$d" aria-roledescription="carousel" aria-label="%2$s" style="--lof-cine-ms:%3$dms">', (int) $secs, esc_attr__( 'Light of Future Foundation highlights', 'light-of-future' ), (int) $secs * 1000 );
		echo '<div class="lof-cine__track">';
		foreach ( $slides as $i => $sl ) {
			$title = esc_html( (string) $sl['title'] );
			if ( ! empty( $sl['highlight'] ) ) {
				$hl  = esc_html( $sl['highlight'] );
				$pos = stripos( $title, $hl );
				if ( false !== $pos ) {
					$title = substr( $title, 0, $pos ) . '<span class="lof-hl">' . substr( $title, $pos, strlen( $hl ) ) . '</span>' . substr( $title, $pos + strlen( $hl ) );
				}
			}
			$focus = isset( $sl['focus'] ) ? sanitize_html_class( $sl['focus'] ) : 'center';
			/* translators: 1: slide number, 2: total */
			printf( '<article class="lof-cine__slide lof-cine__slide--%1$s%2$s" aria-roledescription="slide" aria-label="%3$s"%4$s>', esc_attr( $focus ), 0 === $i ? ' is-active' : '', esc_attr( sprintf( __( '%1$d of %2$d', 'light-of-future' ), $i + 1, $total ) ), 0 === $i ? '' : ' aria-hidden="true"' );
			echo '<div class="lof-cine__media">';
			$this->render_media( $sl['image'], 'full', 'lof-cine__img', 0 === $i );
			echo '</div><div class="lof-cine__shade" aria-hidden="true"></div>';
			echo '<div class="lof-container lof-cine__inner"><div class="lof-cine__content">';
			if ( ! empty( $sl['eyebrow'] ) ) {
				printf( '<p class="lof-cine__eyebrow">%s</p>', esc_html( $sl['eyebrow'] ) );
			}
			printf( '<%1$s class="lof-cine__title">%2$s</%1$s>', 0 === $i ? 'h1' : 'h2', $title ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above.
			if ( ! empty( $sl['text'] ) ) {
				printf( '<p class="lof-cine__text">%s</p>', esc_html( $sl['text'] ) );
			}
			echo '<div class="lof-btn-row">';
			foreach ( array( 'btn1' => 'sun', 'btn2' => 'ghost' ) as $b => $style ) {
				if ( ! empty( $sl[ $b . '_label' ] ) && ! empty( $sl[ $b . '_url' ]['url'] ) ) {
					printf( '<a class="lof-btn lof-btn--%1$s" href="%2$s"%4$s>%3$s</a>', esc_attr( $style ), esc_url( $sl[ $b . '_url' ]['url'] ), esc_html( $sl[ $b . '_label' ] ), 0 === $i ? '' : ' tabindex="-1"' );
				}
			}
			echo '</div></div></div></article>';
		}
		echo '</div>';
		if ( $total > 1 ) {
			echo '<div class="lof-cine__ui"><div class="lof-container lof-cine__ui-inner">';
			echo '<div class="lof-cine__tabs">';
			foreach ( $slides as $i => $sl ) {
				$label = ! empty( $sl['tab'] ) ? $sl['tab'] : wp_trim_words( $sl['title'], 4, '' );
				$thumb = ( ! empty( $sl['image']['id'] ) ) ? wp_get_attachment_image_url( (int) $sl['image']['id'], 'thumbnail' ) : '';
				printf( '<button type="button" class="lof-cine__tab%1$s" data-go="%2$d"%3$s><span class="lof-cine__bar"><span></span></span>%6$s<span class="lof-cine__tab-n">%4$02d</span><span class="lof-cine__tab-l">%5$s</span></button>', 0 === $i ? ' is-active' : '', (int) $i, 0 === $i ? ' aria-current="true"' : '', (int) $i + 1, esc_html( $label ), $thumb ? '<span class="lof-cine__thumb" aria-hidden="true"><img src="' . esc_url( $thumb ) . '" alt="" width="44" height="44" loading="lazy" decoding="async"></span>' : '' );
			}
			echo '</div><div class="lof-cine__ctrl">';
			printf( '<span class="lof-cine__count" aria-live="polite"><strong>01</strong> / %02d</span>', (int) $total );
			printf( '<button type="button" class="lof-cine__btn" data-prev aria-label="%s"><svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M15.4 5.4 14 4l-8 8 8 8 1.4-1.4L8.8 12z"/></svg></button>', esc_attr__( 'Previous slide', 'light-of-future' ) );
			printf( '<button type="button" class="lof-cine__btn lof-cine__pause" data-pause aria-pressed="false" aria-label="%s"><svg class="lof-i-pause" viewBox="0 0 24 24" width="16" height="16" aria-hidden="true"><path fill="currentColor" d="M7 5h4v14H7zM13 5h4v14h-4z"/></svg><svg class="lof-i-play" viewBox="0 0 24 24" width="16" height="16" aria-hidden="true"><path fill="currentColor" d="M8 5v14l11-7z"/></svg></button>', esc_attr__( 'Pause slideshow', 'light-of-future' ) );
			printf( '<button type="button" class="lof-cine__btn" data-next aria-label="%s"><svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="m8.6 18.6 1.4 1.4 8-8-8-8-1.4 1.4 6.6 6.6z"/></svg></button>', esc_attr__( 'Next slide', 'light-of-future' ) );
			echo '</div></div></div>';
		}
		echo '</section>';
		$figs = array();
		foreach ( array( 1, 2, 3, 4 ) as $n ) {
			if ( ! empty( $s[ 'fig' . $n ] ) && false !== strpos( $s[ 'fig' . $n ], '|' ) ) {
				$figs[] = array_map( 'trim', explode( '|', $s[ 'fig' . $n ], 2 ) );
			}
		}
		if ( $figs ) {
			echo '<div class="lof-figbar"><div class="lof-container"><dl class="lof-figbar__grid">';
			foreach ( $figs as $f ) {
				printf( '<div><dd>%1$s</dd><dt>%2$s</dt></div>', esc_html( $f[0] ), esc_html( $f[1] ) );
			}
			echo '</dl></div></div>';
		}
	}
}
