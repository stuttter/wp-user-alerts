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

	// Maybe return popups
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

	// Get popups
	$popups = get_user_option( 'dismissed_popup_ids', get_current_user_id() );

	// Bail if no popups
	if ( empty( $popups ) ) {
		return array();
	}

	// Query for popups
	return wp_user_alerts_get_posts( array(
		'numberposts' => -1,
		'include'     => $popups,
		'post_type'   => 'any'
	) );
}
