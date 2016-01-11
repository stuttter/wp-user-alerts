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
	   'sms' => (object) array(
		   'name'     => esc_html__( 'SMS (Text)', 'wp-user-alerts' ),
		   'callback' => 'wp_user_alerts_users_by_sms'
	   ),
	   'dashboard' => (object) array(
		   'name'     => esc_html__( 'Dashboard', 'wp-user-alerts' ),
		   'callback' => 'wp_user_alerts_users_by_dashboard'
	   ),
	   'popup' => (object) array(
		   'name'     => esc_html__( 'Pop-Up', 'wp-user-alerts' ),
		   'callback' => 'wp_user_alerts_users_by_modal'
	   )
	) );
}

/**
 * Return an array of registered alert priorities
 *
 * @since 0.1.0
 *
 * @return array
 */
function wp_user_alerts_get_alert_priorities() {
	return apply_filters( 'wp_user_alerts_get_alert_priorities', array(
	   'info' => (object) array(
		   'name'     => esc_html__( 'Info', 'wp-user-alerts' ),
		   'callback' => ''
	   ),
	   'reminder' => (object) array(
		   'name'     => esc_html__( 'Reminder', 'wp-user-alerts' ),
		   'callback' => ''
	   ),
	   'important' => (object) array(
		   'name'     => esc_html__( 'Important', 'wp-user-alerts' ),
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

/**
 * Insert an alert
 *
 * @todo everything
 *
 * @since 0.1.0
 *
 * @param array $args
 */
function wp_user_alerts_insert( $args = array() ) {

	// Parse args
	$r = wp_parse_args( $args, array(
		'post_parent'  => 0,
		'post_author'  => 0,
		'post_content' => ''
	) );

	// Insert the post
	wp_insert_post( $r );
}

/**
 * Return array of cellular carriers
 *
 * @since 0.1.0
 *
 * @return array
 */
function wp_user_alerts_get_cellular_carriers() {
	return apply_filters( 'wp_user_alerts_get_cellular_carriers', array(

		// American
		'att' => (object) array(
			'name'   => 'AT&T',
			'format' => '@txt.att.net'
		),
		'comcast' => (object) array(
			'name'   => 'Comcast',
			'format' => '@comcastpcs.textmsg.com'
		),
		'dobson' => (object) array(
			'name'   => 'Dobson',
			'format' => '@mobile.dobson.net'
		),
		'sprint' => (object) array(
			'name'   => 'Sprint',
			'format' => '@messaging.sprintpcs.com'
		),
		't-mobile' => (object) array(
			'name'   => 'T-Mobile',
			'format' => '@tmomail.net'
		),
		'uscellular' => (object) array(
			'name'   => 'U.S. Cellular',
			'format' => '@email.uscc.net'
		),
		'virgin' => (object) array(
			'name'   => 'Virgin',
			'format' => '@vmobl.com'
		),
		'verizon' => (object) array(
			'name'   => 'Verizon',
			'format' => '@vtext.com'
		),
	) );
}
