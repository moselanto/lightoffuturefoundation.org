<?php
/**
 * 0.77.0 Donate and About polish.
 * - "Other ways to give" card on the Donate page: M-PESA Paybill and bank transfer, with copy
 *   buttons and short steps. Values come from Light of Future > Settings; when a field there is
 *   empty, the foundation's details are used (Paybill 522522, account 1354817087, KCB).
 * - Donate form: the step labels (How often?, Currency) no longer sit on a white patch over the
 *   option pills; options are separate pills under each label.
 * - About (and any page with the intro panels): the Vision/Mission icon sits beside the
 *   eyebrow text instead of on top of it.
 * 0.77.1: the card heading inherited the cream colour of the earth-tone section; now uses the heading colour.
 *
 * @package LightOfFuture
 */

defined( 'ABSPATH' ) || exit;

/**
 * A Light of Future setting with a fallback.
 *
 * @param string $key      Setting key.
 * @param string $fallback Value when the setting is empty.
 * @return string
 */
function lof_give_setting( $key, $fallback ) {
	$v = function_exists( 'lof_core_setting' ) ? trim( (string) lof_core_setting( $key ) ) : '';
	return '' !== $v ? $v : $fallback;
}

/**
 * Other ways to give card.
 *
 * @return string
 */
function lof_give_ways_html() {
	$pb   = lof_give_setting( 'mpesa_paybill', '522522' );
	$pba  = lof_give_setting( 'mpesa_account', '1354817087' );
	$bank = lof_give_setting( 'bank_name', 'Kenya Commercial Bank (KCB)' );
	$bacc = lof_give_setting( 'bank_account', '1354817087' );
	if ( ! apply_filters( 'lof_give_ways', true ) || ( ! $pb && ! $bacc ) ) {
		return '';
	}
	$copy = function ( $v, $label ) {
		return '<span class="lof-ways__val">' . esc_html( $v ) . '</span><button type="button" class="lof-ways__copy" data-lof-copy="' . esc_attr( $v ) . '" aria-label="' . esc_attr( sprintf( /* translators: %s: what is copied */ __( 'Copy %s', 'light-of-future' ), $label ) ) . '">' . esc_html__( 'Copy', 'light-of-future' ) . '</button>';
	};
	$phone = '<svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true" focusable="false"><path fill="currentColor" d="M7 2h10a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2zm0 3v13h10V5H7zm5 14.2a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/></svg>';
	$bankI = '<svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true" focusable="false"><path fill="currentColor" d="M12 2 2 7v2h20V7L12 2zM4 11v7h3v-7H4zm6.5 0v7h3v-7h-3zM17 11v7h3v-7h-3zM2 20v2h20v-2H2z"/></svg>';
	$o  = '<section class="lof-ways" aria-labelledby="lof-ways-title">';
	$o .= '<h3 class="lof-ways__title" id="lof-ways-title">' . esc_html__( 'Other ways to give', 'light-of-future' ) . '</h3>';
	$o .= '<p class="lof-ways__intro">' . esc_html__( 'Prefer to send directly? Use M-PESA Paybill or a bank transfer.', 'light-of-future' ) . '</p>';
	if ( $pb ) {
		$o .= '<div class="lof-ways__card"><div class="lof-ways__head"><span class="lof-ways__ico">' . $phone . '</span><div><p class="lof-ways__name">' . esc_html__( 'M-PESA Paybill', 'light-of-future' ) . '</p><p class="lof-ways__sub">' . esc_html__( 'From any Safaricom line, any amount', 'light-of-future' ) . '</p></div></div>';
		$o .= '<dl class="lof-ways__rows"><div><dt>' . esc_html__( 'Business no.', 'light-of-future' ) . '</dt><dd>' . $copy( $pb, __( 'business number', 'light-of-future' ) ) . '</dd></div>';
		if ( $pba ) {
			$o .= '<div><dt>' . esc_html__( 'Account no.', 'light-of-future' ) . '</dt><dd>' . $copy( $pba, __( 'account number', 'light-of-future' ) ) . '</dd></div>';
		}
		$o .= '</dl><ol class="lof-ways__steps"><li>' . esc_html__( 'Open M-PESA, then Lipa na M-PESA, then Pay Bill.', 'light-of-future' ) . '</li><li>' . esc_html( $pba ? sprintf( /* translators: 1: paybill 2: account */ __( 'Enter business no. %1$s and account no. %2$s.', 'light-of-future' ), $pb, $pba ) : sprintf( /* translators: %s: paybill */ __( 'Enter business no. %s.', 'light-of-future' ), $pb ) ) . '</li><li>' . esc_html__( 'Enter the amount and your PIN, then confirm.', 'light-of-future' ) . '</li></ol></div>';
	}
	if ( $bacc ) {
		$o .= '<div class="lof-ways__card"><div class="lof-ways__head"><span class="lof-ways__ico">' . $bankI . '</span><div><p class="lof-ways__name">' . esc_html__( 'Bank transfer', 'light-of-future' ) . '</p><p class="lof-ways__sub">' . esc_html( $bank ) . '</p></div></div>';
		$o .= '<dl class="lof-ways__rows"><div><dt>' . esc_html__( 'Bank', 'light-of-future' ) . '</dt><dd><span class="lof-ways__val lof-ways__val--text">' . esc_html( $bank ) . '</span></dd></div><div><dt>' . esc_html__( 'Account no.', 'light-of-future' ) . '</dt><dd>' . $copy( $bacc, __( 'bank account number', 'light-of-future' ) ) . '</dd></div></dl>';
		$o .= '<p class="lof-ways__hint">' . esc_html__( 'Please use your name as the payment reference.', 'light-of-future' ) . '</p></div>';
	}
	$o .= '<p class="lof-ways__note">' . esc_html__( 'Gave by Paybill or bank? Send us your name and the M-PESA or bank reference and we will email your receipt.', 'light-of-future' ) . '</p>';
	$o .= '</section>';
	return $o;
}

/**
 * Styles and the copy button script.
 */
function lof_give_ways_assets() {
	if ( is_admin() ) {
		return;
	}
	$css = '.lof-ways{margin:1.75rem 0 1.25rem;padding:0}html body .lof-ways .lof-ways__title{font-size:1.15rem;margin:0 0 .3rem;color:var(--lof-heading)!important}html body .lof-ways .lof-ways__name{color:var(--lof-heading)!important}.lof-ways__intro{margin:0 0 1rem;color:var(--lof-muted);font-size:.95rem}'
		. '.lof-ways__card{background:var(--lof-surface,#fff);border:1px solid var(--lof-line);border-radius:18px;padding:1.1rem 1.15rem 1rem;margin:0 0 .85rem;box-shadow:0 18px 40px -32px rgba(43,29,14,.45)}'
		. '.lof-ways__head{display:flex;align-items:center;gap:.8rem;margin:0 0 .85rem}.lof-ways__ico{flex:none;display:inline-flex;align-items:center;justify-content:center;width:44px;height:44px;border-radius:14px;background:var(--lof-gold);color:#2E1E0E}'
		. '.lof-ways__name{margin:0;font-weight:800;color:var(--lof-heading);font-size:1.02rem;line-height:1.3}.lof-ways__sub{margin:.1rem 0 0;color:var(--lof-muted);font-size:.88rem;line-height:1.4}'
		. '.lof-ways__rows{margin:0;padding:0;display:grid;gap:.5rem}.lof-ways__rows>div{display:flex;align-items:center;justify-content:space-between;gap:.75rem;flex-wrap:wrap;padding:.6rem .75rem;border-radius:12px;background:var(--lof-surface-2,#F5EBCB)}'
		. '.lof-ways__rows dt{margin:0;font-size:.78rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--lof-muted)}.lof-ways__rows dd{margin:0;display:flex;align-items:center;gap:.6rem;min-width:0}'
		. '.lof-ways__val{font-weight:800;font-size:1.08rem;letter-spacing:.04em;color:var(--lof-heading);font-variant-numeric:tabular-nums;overflow-wrap:anywhere}.lof-ways__val--text{font-size:.95rem;letter-spacing:0}'
		. '.lof-ways__copy{min-height:36px;padding:.35rem .8rem;border-radius:999px;border:1.5px solid var(--lof-line-strong);background:transparent;color:var(--lof-heading);font-weight:700;font-size:.82rem;cursor:pointer}.lof-ways__copy:hover,.lof-ways__copy.is-done{background:var(--lof-earth);border-color:var(--lof-earth);color:var(--lof-cream)}'
		. '.lof-ways__steps{margin:.85rem 0 0;padding-left:1.2rem;color:var(--lof-muted);font-size:.92rem;line-height:1.55}.lof-ways__steps li{margin:0 0 .2rem}.lof-ways__hint,.lof-ways__note{margin:.75rem 0 0;color:var(--lof-muted);font-size:.88rem;line-height:1.5}'
		// Donate form: labels above separate option pills, no white patch.
		. 'html body .lofpay__seg{background:transparent!important;padding:0!important;border:0!important;border-radius:0!important;display:grid!important;grid-template-columns:repeat(3,minmax(0,1fr))!important;gap:.5rem!important;margin:0 0 1.35rem!important;min-width:0}'
		. 'html body .lofpay__seg.lofpay__seg--small{grid-template-columns:repeat(2,minmax(0,140px))!important}'
		. 'html body .lofpay__seg .lofpay__label,html body.lof-page-donate .lof-pay2__form .lofpay__seg .lofpay__label{background:transparent!important;margin:0 0 .65rem!important;padding:0!important;float:none;width:100%}'
		. 'html body .lofpay__seg label span{min-height:48px;padding:.5rem .6rem;background:#fff;border:1.5px solid rgba(92,61,30,.18);border-radius:999px;text-align:center;line-height:1.2;flex-wrap:wrap;row-gap:.2rem}'
		. 'html body .lofpay__seg input:checked+span{border-color:transparent}'
		// About intro panels: icon beside the eyebrow.
		. 'html body .lof-intro__panel{padding:1.5rem 1.4rem 1.5rem!important}html body .lof-intro__panel::before{top:1.5rem!important;left:1.4rem!important;width:44px;height:44px}'
		. 'html body .lof-intro__panel>.lof-eyebrow{display:flex;align-items:center;min-height:44px;margin:0 0 .9rem!important;padding-left:calc(44px + .9rem)!important;line-height:1.25}'
		. 'html body .lof-intro__panel>h3{margin-top:0!important}';
	echo '<style id="lof-donate-0770">' . $css . "</style>\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static CSS.
}
add_action( 'wp_head', 'lof_give_ways_assets', 100 );

/**
 * Copy buttons (Donate page only).
 */
function lof_give_ways_script() {
	if ( is_admin() || ! is_page( 'donate' ) ) {
		return;
	}
	echo "<script id=\"lof-ways-copy\">document.addEventListener('click',function(e){var b=e.target.closest&&e.target.closest('[data-lof-copy]');if(!b){return;}var v=b.getAttribute('data-lof-copy'),t=b.textContent;function ok(){b.textContent='Copied';b.classList.add('is-done');setTimeout(function(){b.textContent=t;b.classList.remove('is-done');},1800);}if(navigator.clipboard&&window.isSecureContext){navigator.clipboard.writeText(v).then(ok,function(){});}else{var i=document.createElement('textarea');i.value=v;i.setAttribute('readonly','');i.style.position='fixed';i.style.opacity='0';document.body.appendChild(i);i.select();try{document.execCommand('copy');ok();}catch(x){}i.remove();}});</script>\n";
}
add_action( 'wp_footer', 'lof_give_ways_script', 50 );
