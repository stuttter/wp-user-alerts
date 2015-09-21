<?php

/**
 * User Alerts Functions
 *
 * @package UserAlerts/Functions
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Add post type support for alerts to the "post" post type by default
 *
 * @since 0.1.0
 */
function wp_register_default_user_alert_post_types() {
	add_post_type_support( 'post', 'alerts' );
}

/**
 * Return an array of registered alert types
 *
 * @since 0.1.0
 *
 * @return array
 */
function wp_user_alerts_get_alert_types() {
	return apply_filters( 'wp_user_alerts_get_alert_types', array(
	   'users' => (object) array(
		   'name'     => esc_html__( 'Users', 'wp-user-alerts' ),
		   'callback' => 'wp_user_alerts_users_picker'
	   ),
	   'roles' => (object) array(
		   'name'     => esc_html__( 'Roles', 'wp-user-alerts' ),
		   'callback' => 'wp_user_alerts_roles_picker'
	   )
	) );
}

/**
 * Return an array of registered alert methods
 *
 * @since 0.1.0
 *
 * @return array
 */
function wp_user_alerts_get_alert_methods() {
	return apply_filters( 'wp_user_alerts_get_alert_methods', array(
	   'email' => (object) array(
		   'name'     => esc_html__( 'Email', 'wp-user-alerts' ),
		   'callback' => 'wp_user_alerts_users_by_email'
	   ),
	   'dashboard' => (object) array(
		   'name'     => esc_html__( 'Dashboard', 'wp-user-alerts' ),
		   'callback' => 'wp_user_alerts_users_by_dashboard'
	   ),
	   'popup' => (object) array(
		   'name'     => esc_html__( 'Pop-up', 'wp-user-alerts' ),
		   'callback' => 'wp_user_alerts_users_by_modal'
	   )
	) );
}

/**
 * Return an array of registered alert severities
 *
 * @since 0.1.0
 *
 * @return array
 */
function wp_user_alerts_get_alert_severities() {
	return apply_filters( 'wp_user_alerts_get_alert_severities', array(
	   'info' => (object) array(
		   'name'     => esc_html__( 'Info', 'wp-user-alerts' ),
		   'callback' => ''
	   ),
	   'notice' => (object) array(
		   'name'     => esc_html__( 'Notice', 'wp-user-alerts' ),
		   'callback' => ''
	   ),
	   'warning' => (object) array(
		   'name'     => esc_html__( 'Warning', 'wp-user-alerts' ),
		   'callback' => ''
	   ),
	   'error' => (object) array(
		   'name'     => esc_html__( 'Error', 'wp-user-alerts' ),
		   'callback' => ''
	   ),
	   'critical' => (object) array(
		   'name'     => esc_html__( 'Critical', 'wp-user-alerts' ),
		   'callback' => ''
	   ),
	   'alert' => (object) array(
		   'name'     => esc_html__( 'Alert', 'wp-user-alerts' ),
		   'callback' => ''
	   ),
	   'emergency' => (object) array(
		   'name'     => esc_html__( 'Emergency', 'wp-user-alerts' ),
		   'callback' => ''
	   )
	) );
}

/**
 * Alert users about a post by sending them an email
 *
 * @since 0.1.0
 *
 * @param  array  $users
 * @param  int    $post
 */
function wp_user_alerts_users_by_email( $users = array(), $post = 0 ) {

}

/**
 * Alert users about a post via the theme
 *
 * @since 0.1.0
 *
 * @param  array  $users
 * @param  int    $post
 */
function wp_user_alerts_users_by_web( $users = array(), $post = 0 ) {

}
