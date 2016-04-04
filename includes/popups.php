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

	// Bail if user is not logged in
	if ( ! is_user_logged_in() ) {
		return false;
	}

	// Dismissed notices are excluded
	$dismissed = wp_list_pluck( wp_user_alerts_get_dismissed_popups(), 'ID' );

	// Get alerts
	return wp_user_alerts_get_posts( array(
		'numberposts' => 10,
		'exclude'     => $dismissed,
		'meta_query' => wp_user_alerts_get_meta_query( array(
			'user'   => wp_user_alerts_get_meta_query_user(),
			'role'   => wp_user_alerts_get_meta_query_role(),
			'method' => 'popup'
		) )
	) );
}

/**
 * Get an array of posts the user
 *
 * @since 0.1.0
 *
 * @param arary $args
 *
 * @return array
 */
function wp_user_alerts_get_dismissed_popups() {
	return wp_user_alerts_get_posts( array(
		'numberposts' => -1,
		'meta_query' => wp_user_alerts_get_meta_query( array(
			'user'      => wp_user_alerts_get_meta_query_user(),
			'dismissed' => wp_user_alerts_get_meta_query_dismissed(),
			'method'    => 'popup'
		) )
	) );
}
