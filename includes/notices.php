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
	return wp_user_alerts_get_posts( array(
		'numberposts' => 10,
		'exclude'     => get_user_option( 'dismissed_notice_ids', get_current_user_id() ),
		'post_type'   => 'any',
		'meta_query' => wp_user_alerts_get_meta_query( array(
			'user'   => wp_user_alerts_get_meta_query_user(),
			'role'   => wp_user_alerts_get_meta_query_role(),
			'method' => 'notice'
		) )
	) );
}

/**
 * Return array of notice alerts
 *
 * @since 0.1.0
 *
 * @return array
 */
function wp_user_alerts_get_dismissed_notices() {

	// Get notices
	$notices = get_user_option( 'dismissed_notice_ids', get_current_user_id() );

	// Bail if no notices
	if ( empty( $notices ) ) {
		return array();
	}

	// Query for notices
	return wp_user_alerts_get_posts( array(
		'numberposts' => -1,
		'include'     => $notices,
		'post_type'   => 'any',
		'meta_query' => array( array(
			'key' => 'wp_user_alerts_dismissed'
		) )
	) );
}
