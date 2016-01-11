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
	register_meta( 'post', 'wp_user_alerts_role',     'wp_user_alerts_sanitize_role'     );
	register_meta( 'post', 'wp_user_alerts_user',     'wp_user_alerts_sanitize_user'     );
	register_meta( 'post', 'wp_user_alerts_method',   'wp_user_alerts_sanitize_method'   );
	register_meta( 'post', 'wp_user_alerts_prioritY', 'wp_user_alerts_sanitize_priority' );

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
function wp_user_alerts_sanitize_role( $role = 'spectator' ) {
	return $role;
}

/**
 * Sanitize user alert method for saving
 *
 * @since 0.1.0
 *
 * @param int $method
 */
function wp_user_alerts_sanitize_method( $method = 0 ) {
	return $method;
}

/**
 * Sanitize user alert priority for saving
 *
 * @since 0.1.0
 *
 * @param int $priority
 */
function wp_user_alerts_sanitize_priority( $priority = 0 ) {
	return $priority;
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
