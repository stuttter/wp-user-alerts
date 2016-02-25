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
	return wp_user_alerts_get_posts( array(
		'numberposts' => 10,
		'exclude'     => get_user_option( 'dismissed_popup_ids', get_current_user_id() ),
		'post_type'   => 'any',
		'meta_query' => wp_user_alerts_get_meta_query( array(
			'user'   => wp_user_alerts_get_meta_query_user(),
			'role'   => wp_user_alerts_get_meta_query_role(),
			'method' => 'popup'
		) )
	) );
}

/**
 * Return array of modal alerts
 *
 * @since 0.1.0
 *
 * @return array
 */
function wp_user_alerts_get_dismissed_popups() {
	return wp_user_alerts_get_posts( array(
		'numberposts' => -1,
		'include'     => get_user_option( 'dismissed_popup_ids', get_current_user_id() ),
		'post_type'   => 'any'
	) );
}

/**
 * Dismiss a popup for a user
 *
 * @since 0.1.0
 *
 * @param int $id
 *
 * @return bool
 */
function wp_user_alerts_dismiss_popup( $id = 0 ) {

	// Get dismissed alerts
	$user_id = get_current_user_id();
	$exclude = (array) get_user_option( 'dismissed_popup_ids', $user_id );

	// Add item to array and sort
	array_push( $exclude, $id );
	ksort( $exclude );

	// Remove duplicates and empties
	$exclude = array_unique( array_filter( $exclude ) );

	// Update option
	return update_user_option( $user_id, 'dismissed_popup_ids', $exclude );
}
