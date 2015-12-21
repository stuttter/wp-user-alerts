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
	register_meta( 'post', 'wp_user_alert_types',      'wp_user_alerts_sanitize_types'      );
	register_meta( 'post', 'wp_user_alert_methods',    'wp_user_alerts_sanitize_methods'    );
	register_meta( 'post', 'wp_user_alert_severities', 'wp_user_alerts_sanitize_severities' );

	// Users
	register_meta( 'user', 'cellular_number',  'wp_user_alerts_sanitize_cellular_number'  );
	register_meta( 'user', 'cellular_carrier', 'wp_user_alerts_sanitize_cellular_carrier' );
}

/**
 * Sanitize user alert types for saving
 *
 * @since 0.1.0
 *
 * @param array $types
 */
function wp_user_alerts_sanitize_types( $types = array() ) {
	return $types;
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
 * Sanitize user alert severities for saving
 *
 * @since 0.1.0
 *
 * @param array $severities
 */
function wp_user_alerts_sanitize_severities( $severities = array() ) {
	return $severities;
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
