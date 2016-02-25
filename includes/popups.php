<?php

/**
 * User Alerts Popups
 *
 * @package Plugins/User/Alerts/Popups
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Return array of modal alerts
 *
 * @since 0.1.0
 *
 * @return array
 */
function wp_user_alerts_get_popups() {

	// Get dismissed alerts
	$user_id = get_current_user_id();
	$exclude = get_user_option( 'dismissed_modal_ids', $user_id );

	// Get posts
	return wp_user_alerts_get_posts( array(
		'numberposts' => 10,
		'exclude'     => $exclude,
		'post_type'   => 'any',
		'meta_query' => wp_user_alerts_get_meta_query( array(
			'user'   => wp_user_dashboard_get_meta_query_user(),
			'role'   => wp_user_dashboard_get_meta_query_role(),
			'method' => 'popup'
		) )
	) );
}
