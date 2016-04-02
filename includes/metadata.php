<?php

/**
 * User Alerts Metadata
 *
 * @package User/Alerts/Metadata
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Return array of user IDs that were alerted
 *
 * @since 0.1.0
 *
 * @param mixed $post
 *
 * @return array
 */
function wp_user_alerts_get_user_ids( $post = null ) {
	$_post    = get_post( $post );
	$user_ids = ! empty( $_post )
		? get_post_meta( $_post->ID, 'wp_user_alerts_user_ids', true )
		: array();

	return (array) apply_filters( 'wp_user_alerts_get_user_ids', $user_ids, $_post, $post );
}

/**
 * @since 0.1.0
 *
 * @param int $post_id
 * @param int $user_id
 * @return type
 */
function wp_user_alert_is_dismissed( $post_id = 0, $user_id = 0 ) {

	// Get post ID
	if ( empty( $post_id ) ) {
		$post_id = get_the_ID();
	}

	// Get user ID
	if ( empty( $user_id ) ) {
		$user_id = get_current_user_id();
	}

	// Defaults
	$already   = false;
	$dismissed = array();

	// Get user & look in meta
	if ( ! empty( $post_id ) && ! empty( $user_id ) ) {
		$dismissed = get_post_meta( $post_id, 'wp_user_alerts_dismissed' );
		$already   = in_array( $user_id, $dismissed );
	}

	// Filter and return
	return apply_filters( 'wp_user_alert_is_dismissed', $already, $dismissed, $post_id, $user_id );
}

/**
 * Register alert metadata keys & sanitization callbacks
 *
 * @since 0.1.0
 */
function wp_user_alerts_register_metadata() {

	// Posts
	register_meta( 'post', 'wp_user_alerts_role',       'wp_user_alerts_sanitize_role'       );
	register_meta( 'post', 'wp_user_alerts_user',       'wp_user_alerts_sanitize_user'       );
	register_meta( 'post', 'wp_user_alerts_method',     'wp_user_alerts_sanitize_method'     );
	register_meta( 'post', 'wp_user_alerts_priority',   'wp_user_alerts_sanitize_priority'   );
	register_meta( 'post', 'wp_user_alerts_user_group', 'wp_user_alerts_sanitize_user_group' );

	// Users
	register_meta( 'user', 'cellular_number',  'wp_user_alerts_sanitize_cellular_number'  );
	register_meta( 'user', 'cellular_carrier', 'wp_user_alerts_sanitize_cellular_carrier' );
}

/**
 * Sanitize user alert user for saving
 *
 * @since 0.1.0
 *
 * @param int $user
 */
function wp_user_alerts_sanitize_user( $user = 0 ) {
	return $user;
}

/**
 * Sanitize user alert role for saving
 *
 * @since 0.1.0
 *
 * @param string $role
 */
function wp_user_alerts_sanitize_role( $role = '' ) {
	return $role;
}

/**
 * Sanitize user alert method for saving
 *
 * @since 0.1.0
 *
 * @param int $method
 */
function wp_user_alerts_sanitize_method( $method = '' ) {
	return $method;
}

/**
 * Sanitize user alert priority for saving
 *
 * @since 0.1.0
 *
 * @param int $priority
 */
function wp_user_alerts_sanitize_priority( $priority = '' ) {
	return $priority;
}

/**
 * Sanitize user alert user-group for saving
 *
 * @since 0.1.0
 *
 * @param int $user_group
 */
function wp_user_alerts_sanitize_user_group( $user_group = '' ) {
	return $user_group;
}

/**
 * Sanitize user alert cellular number for saving
 *
 * @since 0.1.0
 *
 * @param array $number
 */
function wp_user_alerts_sanitize_cellular_number( $number = array() ) {
	return $number;
}

/**
 * Sanitize user alert cellular carrier for saving
 *
 * @since 0.1.0
 *
 * @param array $carrier
 */
function wp_user_alerts_sanitize_cellular_carrier( $carrier = array() ) {

	// Get possible carriers
	$carriers = array_keys( wp_user_alerts_get_cellular_carriers() );

	// Return carrier if in array, or false if not
	return in_array( $carrier, $carriers )
		? $carrier
		: false;
}
