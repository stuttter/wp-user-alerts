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

/**
 * Return the SMS email address for a given user
 *
 * @since 0.1.0
 *
 * @param  id $user_id
 *
 * @return mixed
 */
function wp_user_alerts_get_user_cellular_address( $user_id = 0 ) {

	// Get all supported carriers
	$carriers = wp_user_alerts_get_cellular_carriers();

	// Get user data
	$user    = get_userdata( $user_id );
	$cell    = $user->cellular_number;
	$carrier = $user->cellular_carrier;

	// Bail if carrier not found
	if ( ! isset( $carriers[ $carrier ] ) ) {
		return false;
	}

	// Concatenate the cell address
	$address = "{$cell}{$carriers[ $carrier ]->format}";

	// Filter & return
	return apply_filters( 'wp_user_alerts_get_user_cellular_address', $address, $user_id, $cell, $carrier );
}

/**
 * Send all of the alerts
 *
 * @since 0.1.0
 */
function wp_user_alerts_maybe_do_all_alerts( $post_id = 0, $post = null ) {

	// Bail on autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Bail if not supported
	if ( ! post_type_supports( $post->post_type, 'alerts' ) ) {
		return;
	}

	// Bail if not publishing
	if ( 'publish' !== get_post_status( $post_id ) ) {
		return;
	}

	// Get the users to alert
	$users   = wp_user_alerts_get_users_to_alert( $post_id );
	$methods = wp_user_alerts_get_user_alert_methods( $post_id );

	// Get Priorities
	$priority       = get_post_meta( $post_id, 'wp_user_alerts_priority' );
	$priorities     = wp_user_alerts_get_alert_priorities();
	$priority_label = isset( $priorities->$priority )
		? $priorities[ $priority ]->name
		: esc_html__( 'Info', 'wp-user-alerts' );

	// Append priority label to subject
	$subject = "[{$priority_label}] " . wp_kses( $post->post_title, array() );

	// Strip tags from post content
	$message = wp_kses( $post->post_content, array() );

	// Email
	if ( in_array( 'email', $methods, true ) ) {
		foreach ( $users as $user_id ) {
			$user = get_userdata( $user_id );
			if ( is_email( $user->user_email ) ) {
				wp_mail( $user->user_email, $subject, $message );
			}
		}
	}

	// SMS
	if ( in_array( 'sms', $methods, true ) ) {
		foreach ( $users as $user_id ) {
			$user_cell = wp_user_alerts_get_user_cellular_address( $user_id );
			if ( is_email( $user_cell ) ) {
				wp_mail( $user_cell, $subject, $message );
			}
		}
	}
}

/**
 * Get array of user IDs to alert
 *
 * @since 0.1.0
 *
 * @param int $post_id
 *
 * @return array
 */
function wp_user_alerts_get_users_to_alert( $post_id = 0 ) {

	// Allow everything to hook in and filter the user IDs
	$all_user_ids = apply_filters( 'wp_user_alerts_get_users_to_alert', array(), $post_id );

	// Remove duplicates
	$deduped_user_ids = array_unique( $all_user_ids, SORT_NUMERIC );

	// Return array
	return $deduped_user_ids;
}

/**
 * Filter user IDs to alert from registered roles
 *
 * @since 0.1.0
 *
 * @param  array  $all_user_ids
 * @param  int    $post_id
 *
 * @return array
 */
function wp_user_alerts_get_role_user_ids( $all_user_ids = array(), $post_id = 0 ) {

	// Get all roles
	$role_ids = get_post_meta( $post_id, 'wp_user_alerts_role' );

	// Bail if no roles
	if ( empty( $role_ids ) ) {
		return $all_user_ids;
	}

	// Get user IDs
	$role_user_ids = get_users( array(
		'role__in' => $role_ids,
		'fields'   => 'ID'
	) );

	// Merge and return
	return array_merge( $all_user_ids, $role_user_ids );
}

/**
 * Filter user IDs to alert from registered roles
 *
 * @since 0.1.0
 *
 * @param  array  $all_user_ids
 * @param  int    $post_id
 *
 * @return array
 */
function wp_user_alerts_get_user_ids( $all_user_ids = array(), $post_id = 0 ) {
	$user_ids = get_post_meta( $post_id, 'wp_user_alerts_user' );

	return array_merge( $all_user_ids, $user_ids );
}

/**
 * Get user alert methods for a post
 *
 * @since 0.1.0
 *
 * @param int $post_id
 *
 * @return array
 */
function wp_user_alerts_get_user_alert_methods( $post_id = 0 ) {
	return get_post_meta( $post_id, 'wp_user_alerts_method' );
}
