<?php
/**
 * 0.47.0 Google tag (GA4) with Google Consent Mode v2, cookie notice, and search-engine
 * verification tags.
 *
 * - Nothing is printed until a GA4 Measurement ID (G-XXXXXXXXXX) is saved in
 *   Light of Future > Organisation details. No ID = no Google tag and no cookie notice.
 * - Consent Mode v2: every storage type defaults to "denied" before the tag loads. Only
 *   analytics_storage is granted, and only after the visitor clicks Accept on the notice.
 *   Advertising signals (ad_storage, ad_user_data, ad_personalization) always stay denied:
 *   the foundation runs no ads. Kenya Data Protection Act 2019: consent before tracking.
 * - The visitor's choice is stored in localStorage + a first-party cookie (lof_consent,
 *   180 days), entirely in the browser, so LiteSpeed page cache is unaffected.
 * - Any link to #cookie-settings (e.g. in the footer or Privacy Policy) reopens the notice.
 * - Logged-in editors are not tracked (their visits would skew a small site's numbers).
 * - Verification meta tags for Google Search Console and Bing Webmaster Tools are printed
 *   on the home page when their tokens are saved.
 * - Off switch: add_filter( 'lof_core_analytics_enabled', '__return_false' );
 *
 * Notice wording is short and functional; it awaits the foundation's approval with the
 * Privacy Policy (CONTENT-GAPS.md). Filter: lof_core_consent_text.
 *
 * @package LightOfFutureCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Saved GA4 Measurement ID, or '' if missing or malformed.
 *
 * @return string
 */
function lof_core_ga4_id() {
	$id = strtoupper( trim( lof_core_setting( 'ga4_id' ) ) );
	return preg_match( '/^G-[A-Z0-9]{4,16}$/', $id ) ? $id : '';
}

/**
 * Should the Google tag load on this request?
 *
 * @return bool
 */
function lof_core_analytics_active() {
	if ( is_admin() || is_feed() || is_robots() || wp_doing_ajax() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
		return false;
	}
	if ( '' === lof_core_ga4_id() || ! apply_filters( 'lof_core_analytics_enabled', true ) ) {
		return false;
	}
	if ( is_user_logged_in() && current_user_can( 'edit_posts' ) ) {
		return false;
	}
	return true;
}

/**
 * Search-engine verification tags (home page only).
 */
function lof_core_verification_tags() {
	if ( ! is_front_page() ) {
		return;
	}
	$google = preg_replace( '/[^A-Za-z0-9_\-]/', '', lof_core_setting( 'gsc_verification' ) );
	$bing   = preg_replace( '/[^A-Za-z0-9]/', '', lof_core_setting( 'bing_verification' ) );
	if ( $google ) {
		printf( '<meta name="google-site-verification" content="%s">' . "\n", esc_attr( $google ) );
	}
	if ( $bing ) {
		printf( '<meta name="msvalidate.01" content="%s">' . "\n", esc_attr( $bing ) );
	}
}
add_action( 'wp_head', 'lof_core_verification_tags', 1 );

/**
 * Consent defaults + Google tag, as early in <head> as possible.
 */
function lof_core_analytics_head() {
	if ( ! lof_core_analytics_active() ) {
		return;
	}
	$id = lof_core_ga4_id();
	?>
<script id="lof-consent-default">
window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}
gtag('consent','default',{ad_storage:'denied',ad_user_data:'denied',ad_personalization:'denied',analytics_storage:'denied',functionality_storage:'granted',security_storage:'granted',wait_for_update:500});
gtag('set','ads_data_redaction',true);gtag('set','url_passthrough',false);
(function(){try{var c=localStorage.getItem('lof_consent')||((document.cookie.match(/(?:^|; )lof_consent=([^;]+)/)||[])[1]||'');if(c==='granted'){gtag('consent','update',{analytics_storage:'granted'});}}catch(e){}})();
gtag('js',new Date());gtag('config','<?php echo esc_js( $id ); ?>',{anonymize_ip:true});
</script>
<script async src="<?php echo esc_url( 'https://www.googletagmanager.com/gtag/js?id=' . rawurlencode( $id ) ); ?>"></script>
	<?php
}
add_action( 'wp_head', 'lof_core_analytics_head', 1 );

/**
 * Preconnect for the tag host (small speed gain).
 *
 * @param array  $urls          URLs.
 * @param string $relation_type Relation.
 * @return array
 */
function lof_core_analytics_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type && lof_core_analytics_active() ) {
		$urls[] = 'https://www.googletagmanager.com';
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'lof_core_analytics_hints', 10, 2 );

/**
 * Cookie notice markup, styles and behaviour (footer; hidden until needed).
 */
function lof_core_consent_notice() {
	if ( ! lof_core_analytics_active() ) {
		return;
	}
	$privacy = get_privacy_policy_url();
	if ( ! $privacy ) {
		$page    = get_page_by_path( 'privacy-policy' );
		$privacy = $page ? get_permalink( $page ) : '';
	}
	$text = apply_filters( 'lof_core_consent_text', __( 'We use analytics cookies to understand how visitors use this website so we can improve it. They are only set if you accept.', 'light-of-future-core' ) );
	?>
<div id="lof-consent" class="lof-consent" role="dialog" aria-live="polite" aria-label="<?php esc_attr_e( 'Cookie choice', 'light-of-future-core' ); ?>" hidden>
	<p class="lof-consent__text"><?php echo esc_html( $text ); ?>
	<?php if ( $privacy ) : ?>
		<a href="<?php echo esc_url( $privacy ); ?>"><?php esc_html_e( 'Privacy Policy', 'light-of-future-core' ); ?></a>
	<?php endif; ?>
	</p>
	<div class="lof-consent__actions">
		<button type="button" class="lof-consent__btn lof-consent__btn--ghost" data-lof-consent="denied"><?php esc_html_e( 'Decline', 'light-of-future-core' ); ?></button>
		<button type="button" class="lof-consent__btn" data-lof-consent="granted"><?php esc_html_e( 'Accept', 'light-of-future-core' ); ?></button>
	</div>
</div>
<style id="lof-consent-css">
.lof-consent{position:fixed;left:16px;right:16px;bottom:16px;z-index:9990;max-width:560px;margin:0 auto;background:#5C3D1E;color:#FCF6E1;border-radius:14px;padding:16px 18px;box-shadow:0 10px 30px rgba(0,0,0,.25);font-size:15px;line-height:1.5;display:flex;flex-wrap:wrap;gap:12px;align-items:center}
.lof-consent[hidden]{display:none}
.lof-consent__text{margin:0;flex:1 1 260px}
.lof-consent__text a{color:#FBD62B;text-decoration:underline}
.lof-consent__actions{display:flex;gap:8px;flex:0 0 auto}
.lof-consent__btn{min-height:44px;padding:0 18px;border-radius:999px;border:2px solid #F4AF16;background:#F4AF16;color:#2b1b0b;font-weight:700;font-size:15px;cursor:pointer}
.lof-consent__btn--ghost{background:transparent;color:#FCF6E1}
.lof-consent__btn:focus-visible{outline:3px solid #FBD62B;outline-offset:2px}
@media (max-width:760px){.lof-consent{bottom:calc(84px + env(safe-area-inset-bottom,0px));left:10px;right:10px}}
</style>
<script id="lof-consent-js">
(function(){var box=document.getElementById('lof-consent');if(!box){return;}
function get(){try{return localStorage.getItem('lof_consent')||((document.cookie.match(/(?:^|; )lof_consent=([^;]+)/)||[])[1]||'');}catch(e){return '';}}
function set(v){try{localStorage.setItem('lof_consent',v);}catch(e){}document.cookie='lof_consent='+v+';path=/;max-age='+(180*86400)+';SameSite=Lax'+(location.protocol==='https:'?';Secure':'');}
function show(){box.hidden=false;}
function hide(){box.hidden=true;}
if(!get()){show();}
box.addEventListener('click',function(e){var b=e.target.closest('[data-lof-consent]');if(!b){return;}var v=b.getAttribute('data-lof-consent');set(v);if(typeof gtag==='function'){gtag('consent','update',{analytics_storage:v==='granted'?'granted':'denied'});}hide();});
document.addEventListener('click',function(e){var a=e.target.closest('a[href$="#cookie-settings"]');if(a){e.preventDefault();show();}});
})();
</script>
	<?php
}
add_action( 'wp_footer', 'lof_core_consent_notice', 50 );

/**
 * Key conversions as GA4 events (only sent when the visitor accepted analytics).
 * donate_start: Donate form submitted; generate_lead: enquiry form submitted;
 * contact_click: tel:, mailto: and WhatsApp links.
 */
function lof_core_analytics_events() {
	if ( ! lof_core_analytics_active() ) {
		return;
	}
	?>
<script id="lof-ga-events">
(function(){if(typeof gtag!=='function'){return;}
document.addEventListener('submit',function(e){var f=e.target;if(!f||!f.querySelector){return;}var a=f.querySelector('input[name="action"]');var act=a?a.value:'';
if(act==='lof_donate'){gtag('event','donate_start',{form:'donate'});}
else if(act==='lof_enquiry'){gtag('event','generate_lead',{form:'enquiry'});}},true);
document.addEventListener('click',function(e){var l=e.target.closest('a[href]');if(!l){return;}var h=l.getAttribute('href')||'';var m=h.indexOf('tel:')===0?'phone':(h.indexOf('mailto:')===0?'email':(/wa\.me|whatsapp\.com/.test(h)?'whatsapp':''));if(m){gtag('event','contact_click',{method:m});}},true);
})();
</script>
	<?php
}
add_action( 'wp_footer', 'lof_core_analytics_events', 60 );
