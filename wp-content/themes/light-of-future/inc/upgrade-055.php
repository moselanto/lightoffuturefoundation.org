<?php
/**
 * 0.55.0 one-off upgrades (wp-admin only):
 * - Gallery added to the main menu under Impact.
 * - WordPress admin email and the matching administrator login email become
 *   info@lightoffuturefoundation.org; a test email is sent and the result shown.
 * - Large uploaded photos (over 160 KB) are resized to 1600 px wide and
 *   re-saved at quality 74, a few per admin page load, keeping a .orig copy.
 *
 * @package LightOfFuture
 */

defined( 'ABSPATH' ) || exit;

/**
 * Gallery under Impact in the main menu.
 */
function lof_upgrade_menu_gallery() {
	if ( (int) get_option( 'lof_menu_gallery_rev', 0 ) >= 1 ) {
		return;
	}
	$page = get_page_by_path( 'gallery', OBJECT, 'page' );
	if ( ! $page || 'publish' !== $page->post_status ) {
		return;
	}
	$loc = get_theme_mod( 'nav_menu_locations', array() );
	if ( empty( $loc['lof-primary'] ) ) {
		update_option( 'lof_menu_gallery_rev', 1, false );
		return;
	}
	$menu_id = (int) $loc['lof-primary'];
	$items   = (array) wp_get_nav_menu_items( $menu_id );
	$parent  = 0;
	$last    = 0;
	foreach ( $items as $it ) {
		if ( 'page' === $it->object && (int) $it->object_id === (int) $page->ID ) {
			update_option( 'lof_menu_gallery_rev', 1, false );
			return;
		}
		if ( ! $parent && 0 === (int) $it->menu_item_parent && 'impact' === strtolower( trim( $it->title ) ) ) {
			$parent = (int) $it->ID;
		}
	}
	foreach ( $items as $it ) {
		if ( $parent && (int) $it->menu_item_parent === $parent ) {
			$last = max( $last, (int) $it->menu_order );
		}
	}
	$pos = $last ? $last + 1 : 0;
	if ( $pos ) {
		foreach ( $items as $it ) {
			if ( (int) $it->menu_order >= $pos ) {
				wp_update_post( array( 'ID' => (int) $it->ID, 'menu_order' => (int) $it->menu_order + 1 ) );
			}
		}
	}
	$id = wp_update_nav_menu_item(
		$menu_id,
		0,
		array(
			'menu-item-title'     => __( 'Gallery', 'light-of-future' ),
			'menu-item-object'    => 'page',
			'menu-item-object-id' => (int) $page->ID,
			'menu-item-type'      => 'post_type',
			'menu-item-status'    => 'publish',
			'menu-item-parent-id' => $parent,
			'menu-item-position'  => $pos,
		)
	);
	if ( ! is_wp_error( $id ) ) {
		update_option( 'lof_menu_gallery_rev', 1, false );
	}
}
add_action( 'admin_init', 'lof_upgrade_menu_gallery', 35 );

/**
 * Admin and login email, plus a test email.
 */
function lof_upgrade_admin_email() {
	if ( (int) get_option( 'lof_admin_email_rev', 0 ) >= 1 || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$new = 'info@lightoffuturefoundation.org';
	$old = (string) get_option( 'admin_email' );
	update_option( 'admin_email', $new );
	delete_option( 'new_admin_email' );
	delete_option( 'adminhash' );
	$changed = array();
	$me      = wp_get_current_user();
	foreach ( get_users( array( 'role' => 'administrator', 'fields' => array( 'ID', 'user_email', 'user_login' ) ) ) as $u ) {
		$match = ( $old && strtolower( $u->user_email ) === strtolower( $old ) ) || ( $me && (int) $me->ID === (int) $u->ID );
		if ( $match && strtolower( $u->user_email ) !== $new && ! email_exists( $new ) ) {
			$r = wp_update_user( array( 'ID' => (int) $u->ID, 'user_email' => $new ) );
			if ( ! is_wp_error( $r ) ) {
				$changed[] = $u->user_login;
			}
			break;
		}
	}
	delete_user_meta( get_current_user_id(), '_new_email' );
	$sent = wp_mail(
		$new,
		'Test email from lightoffuturefoundation.org',
		"This is a test email sent by the Light of Future Foundation website on " . wp_date( 'j F Y, H:i' ) . ".\n\nIf you can read this, emails from the website (enquiries, password resets and shop notices) reach info@lightoffuturefoundation.org."
	);
	update_option( 'lof_admin_email_report', array( 'old' => $old, 'users' => $changed, 'sent' => (bool) $sent, 'at' => time() ), false );
	update_option( 'lof_admin_email_rev', 1, false );
}
add_action( 'admin_init', 'lof_upgrade_admin_email', 36 );

/**
 * Show the result once on the Dashboard.
 */
function lof_admin_email_notice() {
	$r = get_option( 'lof_admin_email_report' );
	if ( ! is_array( $r ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	printf(
		'<div class="notice notice-success"><p><strong>Light of Future email:</strong> admin email set to info@lightoffuturefoundation.org (was %1$s). Login email changed for: %2$s. Test email %3$s.</p></div>',
		esc_html( $r['old'] ? $r['old'] : '-' ),
		esc_html( $r['users'] ? implode( ', ', $r['users'] ) : 'no account (already set or in use)' ),
		$r['sent'] ? 'handed to the mail server' : 'could NOT be sent by the server'
	);
}
add_action( 'admin_notices', 'lof_admin_email_notice' );

/**
 * Shrink large uploaded photos in small batches.
 */
function lof_upgrade_shrink_images() {
	if ( (int) get_option( 'lof_shrink_rev', 0 ) >= 1 || ! current_user_can( 'manage_options' ) || wp_doing_ajax() ) {
		return;
	}
	$done = get_option( 'lof_shrink_done', array() );
	$done = is_array( $done ) ? $done : array();
	$ids  = get_posts( array( 'post_type' => 'attachment', 'post_mime_type' => array( 'image/webp', 'image/jpeg' ), 'posts_per_page' => 400, 'fields' => 'ids', 'post_status' => 'inherit', 'no_found_rows' => true ) );
	$work = 0;
	foreach ( $ids as $id ) {
		if ( in_array( (int) $id, $done, true ) ) {
			continue;
		}
		if ( $work >= 6 ) {
			update_option( 'lof_shrink_done', $done, false );
			return;
		}
		$done[] = (int) $id;
		$file   = get_attached_file( $id );
		if ( ! $file || ! file_exists( $file ) || filesize( $file ) < 160 * 1024 ) {
			continue;
		}
		$work++;
		$ed = wp_get_image_editor( $file );
		if ( is_wp_error( $ed ) ) {
			continue;
		}
		$size = $ed->get_size();
		if ( ! empty( $size['width'] ) && $size['width'] > 1600 ) {
			$ed->resize( 1600, null, false );
		}
		$ed->set_quality( 74 );
		$tmp = $file . '.lof-tmp.' . pathinfo( $file, PATHINFO_EXTENSION );
		$res = $ed->save( $tmp );
		if ( is_wp_error( $res ) || empty( $res['path'] ) || ! file_exists( $res['path'] ) ) {
			continue;
		}
		if ( filesize( $res['path'] ) < filesize( $file ) * 0.9 ) {
			if ( ! file_exists( $file . '.orig' ) ) {
				copy( $file, $file . '.orig' );
			}
			rename( $res['path'], $file );
			$meta = wp_get_attachment_metadata( $id );
			if ( is_array( $meta ) && ! empty( $res['width'] ) ) {
				$meta['width']    = (int) $res['width'];
				$meta['height']   = (int) $res['height'];
				$meta['filesize'] = (int) filesize( $file );
				wp_update_attachment_metadata( $id, $meta );
			}
		} else {
			wp_delete_file( $res['path'] );
		}
	}
	update_option( 'lof_shrink_done', $done, false );
	update_option( 'lof_shrink_rev', 1, false );
	if ( function_exists( 'do_action' ) ) {
		do_action( 'litespeed_purge_all' );
	}
}
add_action( 'admin_init', 'lof_upgrade_shrink_images', 37 );
