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

	// Get dismissed alerts
	$user_id = get_current_user_id();
	$exclude = get_user_option( 'dismissed_notice_ids', $user_id );

	// Get posts
	return wp_user_alerts_get_posts( array(
		'numberposts' => 10,
		'exclude'     => $exclude,
		'post_type'   => 'any',
		'meta_query' => wp_user_alerts_get_meta_query( array(
			'user'   => wp_user_alerts_get_meta_query_user(),
			'role'   => wp_user_alerts_get_meta_query_role(),
			'method' => 'notice'
		) )
	) );
}

/**
 * Dismiss a notice for a user
 *
 * @since 0.1.0
 *
 * @param int $id
 *
 * @return bool
 */
function wp_user_alerts_dismiss_notice( $id = 0 ) {

	// Get dismissed alerts
	$user_id = get_current_user_id();
	$exclude = get_user_option( 'dismissed_notice_ids', $user_id );

	// Add item to array and sort
	array_push( $exclude, $id );
	ksort( $exclude );

	// Remove duplicates and empties
	$exclude = array_unique( array_filter( $exclude ) );

	// Update option
	return update_user_option( $user_id, 'dismissed_notice_ids', $exclude );
}
