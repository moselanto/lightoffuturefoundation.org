<?php
/**
 * Content types and taxonomies.
 *
 * @package LightOfFutureCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Definitions: post type => [singular, plural, slug, icon, public, supports].
 *
 * @return array
 */
function lof_core_post_type_defs() {
	return array(
		'lof_programme' => array( __( 'Programme', 'light-of-future-core' ), __( 'Programmes', 'light-of-future-core' ), 'programmes', 'dashicons-heart', true, array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes', 'elementor' ) ),
		'lof_project'   => array( __( 'Project', 'light-of-future-core' ), __( 'Projects', 'light-of-future-core' ), 'projects', 'dashicons-hammer', true, array( 'title', 'editor', 'excerpt', 'thumbnail', 'elementor' ) ),
		'lof_story'     => array( __( 'Impact story', 'light-of-future-core' ), __( 'Impact stories', 'light-of-future-core' ), 'stories', 'dashicons-format-quote', true, array( 'title', 'editor', 'excerpt', 'thumbnail', 'elementor' ) ),
		'lof_leader'    => array( __( 'Leader', 'light-of-future-core' ), __( 'Leadership', 'light-of-future-core' ), 'leadership', 'dashicons-groups', false, array( 'title', 'editor', 'thumbnail', 'page-attributes' ) ),
		'lof_partner'   => array( __( 'Partner', 'light-of-future-core' ), __( 'Partners', 'light-of-future-core' ), 'partners', 'dashicons-awards', false, array( 'title', 'thumbnail', 'page-attributes' ) ),
		'lof_resource'  => array( __( 'Report or document', 'light-of-future-core' ), __( 'Reports & documents', 'light-of-future-core' ), 'resources', 'dashicons-media-document', true, array( 'title', 'editor', 'excerpt' ) ),
		'lof_metric'    => array( __( 'Impact figure', 'light-of-future-core' ), __( 'Impact figures', 'light-of-future-core' ), 'impact-figures', 'dashicons-chart-bar', false, array( 'title', 'page-attributes' ) ),
	);
}

/**
 * Register post types and taxonomies.
 */
function lof_core_register_post_types() {
	foreach ( lof_core_post_type_defs() as $type => $def ) {
		list( $singular, $plural, $slug, $icon, $public, $supports ) = $def;
		register_post_type(
			$type,
			array(
				'labels'          => array(
					'name'          => $plural,
					'singular_name' => $singular,
					/* translators: %s: item type */
					'add_new_item'  => sprintf( __( 'Add %s', 'light-of-future-core' ), $singular ),
					/* translators: %s: item type */
					'edit_item'     => sprintf( __( 'Edit %s', 'light-of-future-core' ), $singular ),
					'menu_name'     => $plural,
				),
				'public'          => $public,
				'show_ui'         => true,
				'show_in_menu'    => 'lof-core',
				'show_in_rest'    => true,
				'has_archive'     => $public ? $slug : false,
				'rewrite'         => $public ? array( 'slug' => $slug, 'with_front' => false ) : false,
				'menu_icon'       => $icon,
				'supports'        => $supports,
				'capability_type' => 'post',
				'map_meta_cap'    => true,
			)
		);
	}

	register_taxonomy(
		'lof_story_topic',
		array( 'lof_story' ),
		array(
			'label'             => __( 'Story topics', 'light-of-future-core' ),
			'hierarchical'      => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array( 'slug' => 'stories/topic', 'with_front' => false ),
		)
	);
	register_taxonomy(
		'lof_resource_type',
		array( 'lof_resource' ),
		array(
			'label'             => __( 'Document types', 'light-of-future-core' ),
			'hierarchical'      => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array( 'slug' => 'resources/type', 'with_front' => false ),
		)
	);
}
add_action( 'init', 'lof_core_register_post_types' );
