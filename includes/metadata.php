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
function wp_user_alerts_register_post_metadata() {
	register_meta( 'post', 'wp_user_alerts_types',      'wp_user_alerts_sanitize_types'      );
	register_meta( 'post', 'wp_user_alerts_methods',    'wp_user_alerts_sanitize_methods'    );
	register_meta( 'post', 'wp_user_alerts_severities', 'wp_user_alerts_sanitize_severities' );
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