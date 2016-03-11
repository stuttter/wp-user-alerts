<?php

/**
 * User Alerts Notices
 *
 * @package Plugins/User/Alerts/Notices
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Return array of notice alerts
 *
 * @since 0.1.0
 *
 * @return array
 */
function wp_user_alerts_get_notices() {

	// Dismissed notices are excluded
	$dismissed = wp_list_pluck( wp_user_alerts_get_dismissed_notices(), 'ID' );

	// Get alerts
	return wp_user_alerts_get_posts( array(
		'numberposts' => 10,
		'exclude'     => $dismissed,
		'post_type'   => 'any',
		'meta_query' => wp_user_alerts_get_meta_query( array(
			'user'   => wp_user_alerts_get_meta_query_user(),
			'role'   => wp_user_alerts_get_meta_query_role(),
			'method' => 'notice'
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
function wp_user_alerts_get_dismissed_notices() {
	return wp_user_alerts_get_posts( array(
		'numberposts' => -1,
		'post_type'   => 'any',
		'meta_query' => wp_user_alerts_get_meta_query( array(
			'dismissed' => wp_user_alerts_get_meta_query_dismissed(),
			'method'    => 'feed'
		) )
	) );
}
