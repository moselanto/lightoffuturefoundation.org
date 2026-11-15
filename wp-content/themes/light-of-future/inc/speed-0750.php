<?php
/**
 * 0.75.0 Home speed: jQuery, the Elementor frontend scripts, the WooCommerce cart and
 * order-attribution scripts and shop.js are not needed to show the first screen of Home
 * (the slider, menu and media scripts do not use them). On Home they now start after the
 * page has loaded and the browser is idle, or at the first tap, key press or scroll,
 * whichever comes first. They still run in the original order.
 * A tap on a button before they have started is replayed once they are ready.
 * Turn off with add_filter( 'lof_home_delay_js', '__return_false' ).
 *
 * @package LightOfFuture
 */

defined( 'ABSPATH' ) || exit;

/**
 * Whether delayed scripts apply to this request.
 *
 * @return bool
 */
function lof_home_delay_on() {
	if ( is_admin() || ! is_front_page() || isset( $_GET['elementor-preview'] ) || is_customize_preview() || ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return false;
	}
	return (bool) apply_filters( 'lof_home_delay_js', true );
}

/**
 * Script element IDs to delay, in no particular order (document order is kept).
 *
 * @return array
 */
function lof_home_delay_ids() {
	return (array) apply_filters(
		'lof_home_delay_ids',
		array(
			'jquery-core-js',
			'jquery-migrate-js',
			'wc-jquery-blockui-js',
			'wc-add-to-cart-js',
			'wc-js-cookie-js',
			'woocommerce-js',
			'sourcebuster-js-js',
			'wc-order-attribution-js',
			'elementor-webpack-runtime-js',
			'elementor-frontend-modules-js',
			'jquery-ui-core-js-before',
			'jquery-ui-core-js',
			'elementor-frontend-js',
			'lof-shop-js',
		)
	);
}

/**
 * Rewrite the chosen scripts so the browser does not run them, and add the small starter.
 *
 * @param string $html Page.
 * @return string
 */
function lof_home_delay_scripts( $html ) {
	if ( ! is_string( $html ) || false === stripos( $html, '</body>' ) ) {
		return $html;
	}
	$ids   = array_flip( lof_home_delay_ids() );
	$count = 0;
	$html  = preg_replace_callback(
		'#<script\b([^>]*)>(.*?)</script>#is',
		function ( $m ) use ( $ids, &$count ) {
			if ( ! preg_match( '/\sid=["\']([^"\']+)["\']/i', $m[1], $id ) || ! isset( $ids[ $id[1] ] ) ) {
				return $m[0];
			}
			$count++;
			if ( preg_match( '/\ssrc=["\']([^"\']+)["\']/i', $m[1], $src ) ) {
				return '<script type="text/lof-delay" id="' . esc_attr( $id[1] ) . '" data-lof-src="' . $src[1] . '"></script>';
			}
			return '<script type="text/lof-delay" id="' . esc_attr( $id[1] ) . '">' . $m[2] . '</script>';
		},
		$html
	);
	if ( ! $count ) {
		return $html;
	}
	$js = "(function(){var go=false,ready=false,q=[];function run(){if(go){return;}go=true;var list=[].slice.call(document.querySelectorAll('script[type=\"text/lof-delay\"]'));"
		. "(function next(){var o=list.shift();if(!o){ready=true;document.documentElement.classList.add('lof-js-ready');try{window.dispatchEvent(new Event('lof:delayed-ready'));}catch(e){}q.forEach(function(el){if(document.contains(el)){el.click();}});q=[];return;}"
		. "var s=document.createElement('script');if(o.id){s.id=o.id;}var u=o.getAttribute('data-lof-src');if(u){s.src=u;s.async=false;s.onload=s.onerror=next;o.parentNode.replaceChild(s,o);}else{s.text=o.text;o.parentNode.replaceChild(s,o);next();}})();}"
		. "function idle(){setTimeout(run," . (int) apply_filters( 'lof_home_late_ms', 8000 ) . ");}"
		. "if(document.readyState==='complete'){idle();}else{window.addEventListener('load',idle,{once:true});}"
		. "['pointerdown','keydown','touchstart','scroll','wheel'].forEach(function(t){window.addEventListener(t,run,{once:true,passive:true});});"
		. "document.addEventListener('click',function(e){if(ready){return;}var b=e.target.closest&&e.target.closest('button,[role=button],[data-lof-chat] a,.lof-btn-order,.add_to_cart_button');if(!b||(b.tagName==='A'&&b.getAttribute('href')&&b.getAttribute('href').charAt(0)!=='#'&&!b.classList.contains('add_to_cart_button'))){return;}if(b.closest('[data-lof-cine],.lof-header,.lof-nav,#lof-consent')){return;}e.preventDefault();e.stopPropagation();if(q.indexOf(b)<0){q.push(b);}run();},true);})();";
	// 0.76.0: on Home the analytics tag also waits for the first tap/key/scroll or the same late timer.
	if ( apply_filters( 'lof_home_late_gtag', true ) ) {
		$ms   = (int) apply_filters( 'lof_home_late_ms', 8000 );
		$html = str_replace( "function later(){if('requestIdleCallback' in window){requestIdleCallback(go,{timeout:3000});}else{setTimeout(go,1500);}}", 'function later(){setTimeout(go,' . $ms . ');}', $html );
	}
	$starter = '<script id="lof-delay-js">' . $js . '</script>';
	$pos     = strripos( $html, '</body>' );
	return substr( $html, 0, $pos ) . $starter . "\n" . substr( $html, $pos );
}

/**
 * Start after the 0.62 buffer so this rewrite sees the page first.
 */
function lof_home_delay_buffer() {
	if ( lof_home_delay_on() && ! wp_doing_ajax() && ! is_feed() ) {
		ob_start( 'lof_home_delay_scripts' );
	}
}
add_action( 'template_redirect', 'lof_home_delay_buffer', 3 );
