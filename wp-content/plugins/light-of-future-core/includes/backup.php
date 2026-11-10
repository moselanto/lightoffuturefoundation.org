<?php
/**
 * Media backup (0.46.5): zip archives of the media that lives ONLY on the server.
 *
 * WordPress admin: Light of Future > Media backup (manage_options).
 *
 * Sources (none of these are in git):
 * - wp-content/plugins/light-of-future-media/ (gallery photos in assets/photos/), one zip;
 * - wp-content/plugins/light-of-future-video-* (1..12, including video-8 with Margaret's
 *   videos) and wp-content/uploads/lof-videos/, split into zips of at most 45 MB each
 *   (files in order; a single file larger than 45 MB goes alone).
 *
 * Output: wp-content/uploads/lof-backups/<random 20-character folder>/ with the zips and a
 * manifest.json (every source path, size and sha1; every zip name, size and sha1). The
 * folders are protected by index.html and an .htaccess "Require all denied".
 *
 * Downloads only through a token (32 random characters, valid 48 hours):
 *   https://SITE/?lof_backup=TOKEN&manifest=1      manifest JSON
 *   https://SITE/?lof_backup=TOKEN&file=ZIPNAME    one zip from the manifest
 * Options: lof_core_backup (finished backup: folder, token, expiry, file list) and
 * lof_core_backup_job (a build in progress). "Revoke token / delete backups" removes both
 * and deletes the files.
 *
 * @package LightOfFutureCore
 */

defined( 'ABSPATH' ) || exit;

define( 'LOF_BACKUP_MAX_BYTES', 45 * 1024 * 1024 );
define( 'LOF_BACKUP_TTL', 48 * HOUR_IN_SECONDS );

/**
 * Base folder for backups (uploads/lof-backups).
 *
 * @return string
 */
function lof_backup_base() {
	$up = wp_upload_dir( null, false );
	return wp_normalize_path( trailingslashit( $up['basedir'] ) . 'lof-backups' );
}

/**
 * Write index.html and a deny-all .htaccess into a folder.
 *
 * @param string $dir Folder.
 */
function lof_backup_protect( $dir ) {
	if ( ! is_dir( $dir ) ) {
		wp_mkdir_p( $dir );
	}
	if ( ! file_exists( $dir . '/index.html' ) ) {
		file_put_contents( $dir . '/index.html', '' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
	}
	file_put_contents( $dir . '/.htaccess', "Require all denied\n<IfModule !mod_authz_core.c>\nOrder allow,deny\nDeny from all\n</IfModule>\n" ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
}

/**
 * Path of a file relative to the WordPress root, e.g. wp-content/plugins/x/y.jpg.
 *
 * @param string $abs Absolute path.
 * @return string
 */
function lof_backup_rel( $abs ) {
	$abs     = wp_normalize_path( $abs );
	$content = wp_normalize_path( WP_CONTENT_DIR );
	if ( 0 === strpos( $abs, $content . '/' ) ) {
		return 'wp-content/' . ltrim( substr( $abs, strlen( $content ) ), '/' );
	}
	$root = wp_normalize_path( ABSPATH );
	if ( 0 === strpos( $abs, $root ) ) {
		return ltrim( substr( $abs, strlen( $root ) ), '/' );
	}
	return 'other/' . ltrim( $abs, '/' );
}

/**
 * Every regular file under a folder, sorted by path.
 *
 * @param string $dir Folder.
 * @return array[] List of array( abs, rel, size ).
 */
function lof_backup_collect( $dir ) {
	$out = array();
	if ( ! is_dir( $dir ) ) {
		return $out;
	}
	$it = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $dir, FilesystemIterator::SKIP_DOTS ) );
	foreach ( $it as $file ) {
		if ( ! $file->isFile() || $file->isLink() ) {
			continue;
		}
		$abs   = wp_normalize_path( $file->getPathname() );
		$out[] = array(
			'abs'  => $abs,
			'rel'  => lof_backup_rel( $abs ),
			'size' => (int) $file->getSize(),
		);
	}
	usort(
		$out,
		function ( $a, $b ) {
			return strnatcasecmp( $a['rel'], $b['rel'] );
		}
	);
	return $out;
}

/**
 * The server-only media sources.
 *
 * @return array{photos:array,videos:array}
 */
function lof_backup_sources() {
	$plugins = wp_normalize_path( WP_PLUGIN_DIR );
	$photos  = lof_backup_collect( $plugins . '/light-of-future-media' );
	$dirs    = glob( $plugins . '/light-of-future-video-*', GLOB_ONLYDIR );
	$dirs    = is_array( $dirs ) ? $dirs : array();
	natsort( $dirs );
	$videos = array();
	foreach ( $dirs as $d ) {
		$videos = array_merge( $videos, lof_backup_collect( $d ) );
	}
	$up     = wp_upload_dir( null, false );
	$videos = array_merge( $videos, lof_backup_collect( wp_normalize_path( trailingslashit( $up['basedir'] ) . 'lof-videos' ) ) );
	return array(
		'photos' => $photos,
		'videos' => $videos,
	);
}

/**
 * Sum of file sizes.
 *
 * @param array $files Files.
 * @return int
 */
function lof_backup_bytes( $files ) {
	$t = 0;
	foreach ( $files as $f ) {
		$t += (int) $f['size'];
	}
	return $t;
}

/**
 * Plan the zips: one for the photos plugin, videos in parts of at most 45 MB.
 *
 * @param array  $src   Sources from lof_backup_sources().
 * @param string $stamp Date stamp for file names.
 * @return array[]
 */
function lof_backup_plan( $src, $stamp ) {
	$zips = array();
	if ( $src['photos'] ) {
		$zips[] = array(
			'name'  => 'lof-photos-' . $stamp . '.zip',
			'files' => $src['photos'],
			'done'  => false,
		);
	}
	$part  = array();
	$bytes = 0;
	$n     = 0;
	$flush = function () use ( &$zips, &$part, &$bytes, &$n, $stamp ) {
		if ( $part ) {
			++$n;
			$zips[] = array(
				'name'  => sprintf( 'lof-videos-%s-%02d.zip', $stamp, $n ),
				'files' => $part,
				'done'  => false,
			);
		}
		$part  = array();
		$bytes = 0;
	};
	foreach ( $src['videos'] as $f ) {
		// Zip overhead per entry: local + central headers (about 100 bytes) plus the name twice.
		$cost = (int) $f['size'] + 200 + 2 * strlen( $f['rel'] );
		if ( $part && $bytes + $cost > LOF_BACKUP_MAX_BYTES ) {
			$flush();
		}
		$part[] = $f;
		$bytes += $cost;
	}
	$flush();
	return $zips;
}

/**
 * Build one planned zip.
 *
 * @param string $dir     Backup folder.
 * @param array  $zip     Planned zip (by reference: size, sha1, done, skipped are set).
 * @param array  $sources Manifest source list (by reference).
 * @return bool
 */
function lof_backup_build_zip( $dir, &$zip, &$sources ) {
	$path = $dir . '/' . $zip['name'];
	if ( file_exists( $path ) ) {
		wp_delete_file( $path );
	}
	$za = new ZipArchive();
	if ( true !== $za->open( $path, ZipArchive::CREATE | ZipArchive::OVERWRITE ) ) {
		return false;
	}
	// Photos and videos are already compressed: store them (fast, predictable size).
	$store         = method_exists( $za, 'setCompressionName' ) && defined( 'ZipArchive::CM_STORE' );
	$zip['skipped'] = array();
	$added         = array();
	foreach ( $zip['files'] as $f ) {
		if ( ! is_readable( $f['abs'] ) ) {
			$zip['skipped'][] = $f['rel'];
			continue;
		}
		if ( ! $za->addFile( $f['abs'], $f['rel'] ) ) {
			$zip['skipped'][] = $f['rel'];
			continue;
		}
		if ( $store ) {
			$za->setCompressionName( $f['rel'], ZipArchive::CM_STORE );
		}
		$added[] = $f;
	}
	if ( ! $za->close() ) {
		return false;
	}
	foreach ( $added as $f ) {
		$sources[] = array(
			'path' => $f['rel'],
			'size' => (int) filesize( $f['abs'] ),
			'sha1' => (string) sha1_file( $f['abs'] ),
			'zip'  => $zip['name'],
		);
	}
	clearstatcache( true, $path );
	$zip['size']  = (int) filesize( $path );
	$zip['sha1']  = (string) sha1_file( $path );
	$zip['count'] = count( $added );
	$zip['done']  = true;
	return true;
}

/**
 * Delete a backup folder (only inside uploads/lof-backups).
 *
 * @param string $name 20-character folder name.
 */
function lof_backup_delete_dir( $name ) {
	if ( ! is_string( $name ) || ! preg_match( '/^[A-Za-z0-9]{20}$/', $name ) ) {
		return;
	}
	$dir = lof_backup_base() . '/' . $name;
	if ( ! is_dir( $dir ) ) {
		return;
	}
	$it = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $dir, FilesystemIterator::SKIP_DOTS ), RecursiveIteratorIterator::CHILD_FIRST );
	foreach ( $it as $item ) {
		if ( $item->isDir() && ! $item->isLink() ) {
			rmdir( $item->getPathname() ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_rmdir
		} else {
			unlink( $item->getPathname() ); // phpcs:ignore WordPress.WP.AlternativeFunctions.unlink_unlink
		}
	}
	rmdir( $dir ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_rmdir
}

/**
 * Remove the finished backup, any build in progress, and the token.
 */
function lof_backup_revoke_all() {
	$b = get_option( 'lof_core_backup' );
	if ( is_array( $b ) && ! empty( $b['dir'] ) ) {
		lof_backup_delete_dir( $b['dir'] );
	}
	$j = get_option( 'lof_core_backup_job' );
	if ( is_array( $j ) && ! empty( $j['dir'] ) ) {
		lof_backup_delete_dir( $j['dir'] );
	}
	delete_option( 'lof_core_backup' );
	delete_option( 'lof_core_backup_job' );
}

/**
 * Admin page URL.
 *
 * @param array $args Extra query args.
 * @return string
 */
function lof_backup_page_url( $args = array() ) {
	return add_query_arg( array_merge( array( 'page' => 'lof-media-backup' ), $args ), admin_url( 'admin.php' ) );
}

/**
 * Build handler (admin-post.php?action=lof_backup_build): starts a new build or continues
 * the one in progress. Builds whole zips until about 200 seconds have passed, then returns
 * to the page, which continues automatically.
 */
function lof_backup_handle_build() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You are not allowed to do this.', 'light-of-future-core' ), 403 );
	}
	check_admin_referer( 'lof_backup_build', 'lof_backup_nonce' );
	if ( ! class_exists( 'ZipArchive' ) ) {
		wp_safe_redirect( lof_backup_page_url( array( 'lof_msg' => 'nozip' ) ) );
		exit;
	}
	ignore_user_abort( true );
	if ( function_exists( 'set_time_limit' ) ) {
		@set_time_limit( 300 ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
	}
	$start  = time();
	$limit  = (int) ini_get( 'max_execution_time' );
	$budget = $limit > 0 ? max( 15, min( 200, $limit - 20 ) ) : 200;

	$job = get_option( 'lof_core_backup_job' );
	if ( ! is_array( $job ) || empty( $job['dir'] ) || empty( $job['zips'] ) ) {
		lof_backup_revoke_all();
		$src  = lof_backup_sources();
		$plan = lof_backup_plan( $src, gmdate( 'Ymd' ) );
		if ( ! $plan ) {
			wp_safe_redirect( lof_backup_page_url( array( 'lof_msg' => 'empty' ) ) );
			exit;
		}
		$name = wp_generate_password( 20, false, false );
		lof_backup_protect( lof_backup_base() );
		lof_backup_protect( lof_backup_base() . '/' . $name );
		$job = array(
			'dir'     => $name,
			'zips'    => $plan,
			'sources' => array(),
			'started' => time(),
		);
		update_option( 'lof_core_backup_job', $job, false );
	}
	$dir = lof_backup_base() . '/' . $job['dir'];
	if ( ! is_dir( $dir ) ) {
		delete_option( 'lof_core_backup_job' );
		wp_safe_redirect( lof_backup_page_url( array( 'lof_msg' => 'error' ) ) );
		exit;
	}
	$built = 0;
	foreach ( $job['zips'] as $i => $zip ) {
		if ( ! empty( $zip['done'] ) ) {
			continue;
		}
		if ( $built > 0 && ( time() - $start ) >= $budget ) {
			break;
		}
		$sources = $job['sources'];
		if ( ! lof_backup_build_zip( $dir, $zip, $sources ) ) {
			wp_safe_redirect( lof_backup_page_url( array( 'lof_msg' => 'error' ) ) );
			exit;
		}
		$job['zips'][ $i ] = $zip;
		$job['sources']    = $sources;
		update_option( 'lof_core_backup_job', $job, false );
		++$built;
	}
	foreach ( $job['zips'] as $zip ) {
		if ( empty( $zip['done'] ) ) {
			wp_safe_redirect( lof_backup_page_url( array( 'lof_msg' => 'partial' ) ) );
			exit;
		}
	}

	// All zips built: manifest, token, finished record.
	$zips    = array();
	$names   = array();
	$skipped = array();
	foreach ( $job['zips'] as $zip ) {
		$names[] = $zip['name'];
		$zips[]  = array(
			'name'  => $zip['name'],
			'size'  => (int) $zip['size'],
			'sha1'  => (string) $zip['sha1'],
			'files' => (int) $zip['count'],
		);
		if ( ! empty( $zip['skipped'] ) ) {
			$skipped = array_merge( $skipped, $zip['skipped'] );
		}
	}
	$manifest = array(
		'site'      => home_url( '/' ),
		'generated' => gmdate( 'c' ),
		'core'      => LOF_CORE_VERSION,
		'restore'   => 'Unzip each archive at the WordPress root: entry paths start with wp-content/.',
		'max_zip'   => LOF_BACKUP_MAX_BYTES,
		'zips'      => $zips,
		'sources'   => $job['sources'],
		'skipped'   => $skipped,
	);
	file_put_contents( $dir . '/manifest.json', wp_json_encode( $manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
	update_option(
		'lof_core_backup',
		array(
			'dir'     => $job['dir'],
			'token'   => wp_generate_password( 32, false, false ),
			'expires' => time() + LOF_BACKUP_TTL,
			'files'   => $names,
			'zips'    => $zips,
			'sources' => count( $job['sources'] ),
			'skipped' => count( $skipped ),
			'created' => time(),
		),
		false
	);
	delete_option( 'lof_core_backup_job' );
	wp_safe_redirect( lof_backup_page_url( array( 'lof_msg' => 'done' ) ) );
	exit;
}
add_action( 'admin_post_lof_backup_build', 'lof_backup_handle_build' );

/**
 * Revoke handler: delete the token, the zips and any build in progress.
 */
function lof_backup_handle_revoke() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You are not allowed to do this.', 'light-of-future-core' ), 403 );
	}
	check_admin_referer( 'lof_backup_revoke', 'lof_backup_nonce' );
	lof_backup_revoke_all();
	wp_safe_redirect( lof_backup_page_url( array( 'lof_msg' => 'revoked' ) ) );
	exit;
}
add_action( 'admin_post_lof_backup_revoke', 'lof_backup_handle_revoke' );

/**
 * Menu: Light of Future > Media backup.
 */
function lof_backup_menu() {
	add_submenu_page( 'lof-core', __( 'Media backup', 'light-of-future-core' ), __( 'Media backup', 'light-of-future-core' ), 'manage_options', 'lof-media-backup', 'lof_backup_page' );
}
add_action( 'admin_menu', 'lof_backup_menu', 20 );

/**
 * Human size.
 *
 * @param int $bytes Bytes.
 * @return string
 */
function lof_backup_size( $bytes ) {
	return size_format( (int) $bytes, 1 );
}

/**
 * Admin page.
 */
function lof_backup_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- display only.
	$msg    = isset( $_GET['lof_msg'] ) ? sanitize_key( wp_unslash( $_GET['lof_msg'] ) ) : '';
	$backup = get_option( 'lof_core_backup' );
	$job    = get_option( 'lof_core_backup_job' );
	$src    = lof_backup_sources();
	$notes  = array(
		'done'    => array( 'success', __( 'Backup ready. Copy the links below; they work for 48 hours.', 'light-of-future-core' ) ),
		'partial' => array( 'info', __( 'Backup in progress: more archives are still to be built. This page continues automatically; keep it open.', 'light-of-future-core' ) ),
		'revoked' => array( 'success', __( 'Token revoked and backup files deleted.', 'light-of-future-core' ) ),
		'empty'   => array( 'warning', __( 'No media files were found to back up.', 'light-of-future-core' ) ),
		'nozip'   => array( 'error', __( 'The PHP ZipArchive extension is not available on this server, so archives cannot be built.', 'light-of-future-core' ) ),
		'error'   => array( 'error', __( 'An archive could not be written (check free disk space and folder permissions). Revoke to clean up, then try again.', 'light-of-future-core' ) ),
	);
	echo '<div class="wrap"><h1>' . esc_html__( 'Media backup', 'light-of-future-core' ) . '</h1>';
	if ( isset( $notes[ $msg ] ) ) {
		echo '<div class="notice notice-' . esc_attr( $notes[ $msg ][0] ) . '"><p>' . esc_html( $notes[ $msg ][1] ) . '</p></div>';
	}
	echo '<p>' . esc_html__( 'The gallery photos (Light of Future Media plugin) and the story and programme videos exist only on this server, not in git. This tool packs them into zip archives (videos split into parts of at most 45 MB) with a manifest of every file and its SHA-1 checksum, for download through private links that expire after 48 hours.', 'light-of-future-core' ) . '</p>';

	$photo_bytes = lof_backup_bytes( $src['photos'] );
	$video_bytes = lof_backup_bytes( $src['videos'] );
	echo '<table class="widefat striped" style="max-width:720px"><tbody>';
	/* translators: 1: number of files 2: size */
	echo '<tr><th>' . esc_html__( 'Photos (light-of-future-media plugin)', 'light-of-future-core' ) . '</th><td>' . esc_html( sprintf( __( '%1$d files, %2$s', 'light-of-future-core' ), count( $src['photos'] ), lof_backup_size( $photo_bytes ) ) ) . '</td></tr>';
	/* translators: 1: number of files 2: size */
	echo '<tr><th>' . esc_html__( 'Videos (light-of-future-video-* plugins and uploads/lof-videos)', 'light-of-future-core' ) . '</th><td>' . esc_html( sprintf( __( '%1$d files, %2$s', 'light-of-future-core' ), count( $src['videos'] ), lof_backup_size( $video_bytes ) ) ) . '</td></tr>';
	$free = function_exists( 'disk_free_space' ) ? @disk_free_space( wp_normalize_path( WP_CONTENT_DIR ) ) : false; // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
	if ( false !== $free ) {
		echo '<tr><th>' . esc_html__( 'Free disk space', 'light-of-future-core' ) . '</th><td>' . esc_html( lof_backup_size( (int) $free ) ) . ( $free < ( $photo_bytes + $video_bytes ) * 1.1 ? ' <strong>' . esc_html__( '(not enough for a full backup)', 'light-of-future-core' ) . '</strong>' : '' ) . '</td></tr>';
	}
	echo '</tbody></table>';

	if ( ! class_exists( 'ZipArchive' ) ) {
		echo '<div class="notice notice-error inline"><p>' . esc_html( $notes['nozip'][1] ) . '</p></div>';
	}

	$in_progress = is_array( $job ) && ! empty( $job['zips'] );
	echo '<form id="lof-backup-build" method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '" style="margin:1.25rem 0 .5rem">';
	echo '<input type="hidden" name="action" value="lof_backup_build">';
	wp_nonce_field( 'lof_backup_build', 'lof_backup_nonce' );
	if ( $in_progress ) {
		$done = 0;
		foreach ( $job['zips'] as $z ) {
			$done += empty( $z['done'] ) ? 0 : 1;
		}
		/* translators: 1: built archives 2: total archives */
		echo '<p>' . esc_html( sprintf( __( 'Built %1$d of %2$d archives.', 'light-of-future-core' ), $done, count( $job['zips'] ) ) ) . '</p>';
		submit_button( __( 'Continue building', 'light-of-future-core' ), 'primary', 'submit', false );
	} else {
		submit_button( is_array( $backup ) ? __( 'Build a new backup (replaces the current one)', 'light-of-future-core' ) : __( 'Build backup', 'light-of-future-core' ), 'primary', 'submit', false );
	}
	echo '</form>';
	if ( $in_progress && 'partial' === $msg ) {
		echo '<script>setTimeout(function(){var f=document.getElementById("lof-backup-build");if(f){f.submit();}},1500);</script>';
	}

	if ( is_array( $backup ) && ! empty( $backup['token'] ) ) {
		$expired = time() > (int) $backup['expires'];
		echo '<h2>' . esc_html__( 'Download links', 'light-of-future-core' ) . '</h2>';
		if ( $expired ) {
			echo '<p><strong>' . esc_html__( 'The token has expired. Build a new backup to get new links.', 'light-of-future-core' ) . '</strong></p>';
		} else {
			/* translators: %s: date and time */
			echo '<p>' . esc_html( sprintf( __( 'Valid until %s. Anyone with a link can download that file until then, so share them privately.', 'light-of-future-core' ), wp_date( 'j M Y, H:i', (int) $backup['expires'] ) ) ) . '</p>';
			$lines = array( add_query_arg( array( 'lof_backup' => $backup['token'], 'manifest' => '1' ), home_url( '/' ) ) );
			foreach ( (array) $backup['files'] as $name ) {
				$lines[] = add_query_arg( array( 'lof_backup' => $backup['token'], 'file' => $name ), home_url( '/' ) );
			}
			echo '<textarea readonly class="large-text code" rows="' . esc_attr( min( 20, count( $lines ) + 1 ) ) . '" onclick="this.select()">' . esc_textarea( implode( "\n", $lines ) ) . '</textarea>';
			echo '<table class="widefat striped" style="max-width:720px;margin-top:1rem"><thead><tr><th>' . esc_html__( 'Archive', 'light-of-future-core' ) . '</th><th>' . esc_html__( 'Files', 'light-of-future-core' ) . '</th><th>' . esc_html__( 'Size', 'light-of-future-core' ) . '</th></tr></thead><tbody>';
			foreach ( (array) $backup['zips'] as $z ) {
				echo '<tr><td><code>' . esc_html( $z['name'] ) . '</code></td><td>' . esc_html( (string) (int) $z['files'] ) . '</td><td>' . esc_html( lof_backup_size( $z['size'] ) ) . '</td></tr>';
			}
			echo '</tbody></table>';
			if ( ! empty( $backup['skipped'] ) ) {
				/* translators: %d: number of files */
				echo '<p><strong>' . esc_html( sprintf( __( '%d unreadable files were skipped; see "skipped" in the manifest.', 'light-of-future-core' ), (int) $backup['skipped'] ) ) . '</strong></p>';
			}
		}
	}
	if ( is_array( $backup ) || $in_progress ) {
		echo '<form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '" style="margin-top:1.5rem">';
		echo '<input type="hidden" name="action" value="lof_backup_revoke">';
		wp_nonce_field( 'lof_backup_revoke', 'lof_backup_nonce' );
		submit_button( __( 'Revoke token / delete backups', 'light-of-future-core' ), 'delete', 'submit', false );
		echo '</form>';
	}
	echo '</div>';
}

/**
 * Front-end download endpoint:
 *   /?lof_backup=TOKEN&manifest=1   manifest JSON
 *   /?lof_backup=TOKEN&file=NAME    one archive listed in the manifest
 * Nothing is served without a valid, unexpired token.
 */
function lof_backup_serve() {
	// phpcs:disable WordPress.Security.NonceVerification.Recommended -- token-protected download.
	if ( ! isset( $_GET['lof_backup'] ) ) {
		return;
	}
	$token = sanitize_text_field( wp_unslash( $_GET['lof_backup'] ) );
	$file  = isset( $_GET['file'] ) ? (string) wp_unslash( $_GET['file'] ) : '';
	$man   = isset( $_GET['manifest'] );
	// phpcs:enable
	if ( ! defined( 'DONOTCACHEPAGE' ) ) {
		define( 'DONOTCACHEPAGE', true );
	}
	do_action( 'litespeed_control_set_nocache', 'lof backup download' );
	nocache_headers();
	header( 'X-LiteSpeed-Cache-Control: no-cache' );
	header( 'X-Robots-Tag: noindex, nofollow' );
	header( 'X-Content-Type-Options: nosniff' );

	$b = get_option( 'lof_core_backup' );
	$ok = is_array( $b ) && ! empty( $b['token'] ) && ! empty( $b['dir'] )
		&& 32 === strlen( $token ) && hash_equals( (string) $b['token'], $token )
		&& time() <= (int) $b['expires']
		&& preg_match( '/^[A-Za-z0-9]{20}$/', (string) $b['dir'] );
	if ( ! $ok ) {
		status_header( 403 );
		header( 'Content-Type: text/plain; charset=utf-8' );
		echo 'Forbidden';
		exit;
	}
	$dir  = lof_backup_base() . '/' . $b['dir'];
	$real = realpath( $dir );
	if ( $man ) {
		$name = 'manifest.json';
		$type = 'application/json; charset=utf-8';
	} else {
		$name = basename( wp_normalize_path( $file ) );
		$type = 'application/zip';
		if ( '' === $name || $name !== $file || ! in_array( $name, (array) $b['files'], true ) ) {
			status_header( 404 );
			header( 'Content-Type: text/plain; charset=utf-8' );
			echo 'Not found';
			exit;
		}
	}
	$path = realpath( $dir . '/' . $name );
	if ( ! $real || ! $path || 0 !== strpos( wp_normalize_path( $path ), wp_normalize_path( $real ) . '/' ) || ! is_file( $path ) || ! is_readable( $path ) ) {
		status_header( 404 );
		header( 'Content-Type: text/plain; charset=utf-8' );
		echo 'Not found';
		exit;
	}
	ignore_user_abort( true );
	if ( function_exists( 'set_time_limit' ) ) {
		@set_time_limit( 0 ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
	}
	while ( ob_get_level() ) {
		ob_end_clean();
	}
	status_header( 200 );
	header( 'Content-Type: ' . $type );
	header( 'Content-Length: ' . (string) filesize( $path ) );
	if ( ! $man ) {
		header( 'Content-Disposition: attachment; filename="' . $name . '"' );
	}
	$fh = fopen( $path, 'rb' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen
	if ( $fh ) {
		while ( ! feof( $fh ) ) {
			echo fread( $fh, 1048576 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped,WordPress.WP.AlternativeFunctions.file_system_operations_fread
			flush();
			if ( connection_aborted() ) {
				break;
			}
		}
		fclose( $fh ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fclose
	}
	exit;
}
add_action( 'init', 'lof_backup_serve', 1 );
