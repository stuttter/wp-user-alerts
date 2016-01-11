<?php

/**
 * User Alerts Metadata
 *
 * @package User/Alerts/Metadata
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Register alert metadata keys & sanitization callbacks
 *
 * @since 0.1.0
 */
function wp_user_alerts_register_metadata() {

	// Posts
	register_meta( 'post', 'wp_user_alert_roles',      'wp_user_alerts_sanitize_roles'      );
	register_meta( 'post', 'wp_user_alert_users',      'wp_user_alerts_sanitize_users'      );
	register_meta( 'post', 'wp_user_alert_methods',    'wp_user_alerts_sanitize_methods'    );
	register_meta( 'post', 'wp_user_alert_priorities', 'wp_user_alerts_sanitize_priorities' );

	// Users
	register_meta( 'user', 'cellular_number',  'wp_user_alerts_sanitize_cellular_number'  );
	register_meta( 'user', 'cellular_carrier', 'wp_user_alerts_sanitize_cellular_carrier' );
}

/**
 * Sanitize user alert users for saving
 *
 * @since 0.1.0
 *
 * @param array $users
 */
function wp_user_alerts_sanitize_users( $users = array() ) {
	return $users;
}

/**
 * Sanitize user alert roles for saving
 *
 * @since 0.1.0
 *
 * @param array $roles
 */
function wp_user_alerts_sanitize_roles( $roles = array() ) {
	return $roles;
}

/**
 * Sanitize user alert methods for saving
 *
 * @since 0.1.0
 *
 * @param array $methods
 */
function wp_user_alerts_sanitize_methods( $methods = array() ) {
	return $methods;
}

/**
 * Sanitize user alert priorities for saving
 *
 * @since 0.1.0
 *
 * @param array $priorities
 */
function wp_user_alerts_sanitize_priorities( $priorities = array() ) {
	return $priorities;
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
