<?php

/**
 * User Alert Post Types
 *
 * @package User/Alert/PostTypes
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Register the User Alert post types
 *
 * @since 0.1.0
 */
function wp_user_alerts_register_post_types() {
	register_post_type( 'alert', wp_user_alerts_get_post_type_args() );
}

/**
 * Return the post type arguments
 *
 * @since 0.1.0
 *
 * @return array
 */
function wp_user_alerts_get_post_type_args() {

	// Labels
	$labels = array(
		'name'                  => _x( 'Alerts', 'post type general name', 'wp-user-alerts' ),
		'singular_name'         => _x( 'Alert', 'post type singular name', 'wp-user-alerts' ),
		'add_new'               => _x( 'Add New', 'activity', 'wp-user-alerts' ),
		'add_new_item'          => __( 'Add New Alert', 'wp-user-alerts' ),
		'edit_item'             => __( 'Edit Alert', 'wp-user-alerts' ),
		'new_item'              => __( 'New Alert', 'wp-user-alerts' ),
		'view_item'             => __( 'View Alert', 'wp-user-alerts' ),
		'search_items'          => __( 'Search Alerts', 'wp-user-alerts' ),
		'not_found'             => __( 'No activity found.', 'wp-user-alerts' ),
		'not_found_in_trash'    => __( 'No activity found in Trash.', 'wp-user-alerts' ),
		'parent_item_colon'     => __( 'Parent:', 'wp-user-alerts' ),
		'all_items'             => __( 'All Alerts', 'wp-user-alerts' ),
		'featured_image'        => __( 'Photo', 'wp-user-alerts' ),
		'set_featured_image'    => __( 'Set featured image', 'wp-user-alerts' ),
		'remove_featured_image' => __( 'Remove photo', 'wp-user-alerts' ),
		'use_featured_image'    => __( 'Use as featured image', 'wp-user-alerts' ),
	);

	// Capability types
	$cap_types = array(
		'alert',
		'alerts'
	);

	// Capabilities
	$caps = array(
		'create_posts'        => 'create_alerts',
		'edit_posts'          => 'edit_alerts',
		'edit_others_posts'   => 'edit_others_alerts',
		'publish_posts'       => 'publish_alerts',
		'read_private_posts'  => 'read_private_alerts',
		'read_hidden_posts'   => 'read_hidden_alerts',
		'delete_posts'        => 'delete_alerts',
		'delete_others_posts' => 'delete_others_alerts'
	);

	// Filter & return
	return apply_filters( 'wp_user_alerts_get_post_type_args', array(
		'labels'               => $labels,
		'supports'             => false,
		'description'          => '',
		'public'               => true,
		'hierarchical'         => false,
		'exclude_from_search'  => true,
		'publicly_queryable'   => false,
		'show_ui'              => true,
		'show_in_menu'         => true,
		'show_in_nav_menus'    => false,
		'show_in_admin_bar'    => false,
		'menu_position'        => 2,
		'menu_icon'            => 'dashicons-warning',
		'capabilities'         => $caps,
		'capability_type'      => $cap_types,
		'register_meta_box_cb' => null,
		'taxonomies'           => array(),
		'has_archive'          => false,
		'rewrite'              => false,
		'query_var'            => true,
		'can_export'           => true,
		'delete_with_user'     => false,
	) );
}
