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
 * @param  array  $args
 */
function wp_user_alerts_users_by_email( $args = array() ) {

	// Parse args
	$r = wp_parse_args( $args, array(
		'user_ids' => array(),
		'subject'  => '',
		'message'  => ''
	) );

	// Loop through user IDs
	foreach ( $r['user_ids'] as $user_id ) {

		// Get cellular address
		$user_email = get_userdata( $user_id )->user_email;

		// Skip if not email address
		if ( ! is_email( $user_email ) ) {
			continue;
		}

		// Send email
		wp_mail( $user_email, $r['subject'], $r['message'] );
	}
}

/**
 * Alert users about a post by sending them an SMS message
 *
 * @since 0.1.0
 *
 * @param  array  $args
 */
function wp_user_alerts_users_by_sms( $args = array() ) {

	// Parse args
	$r = wp_parse_args( $args, array(
		'user_ids' => array(),
		'subject'  => '',
		'message'  => ''
	) );

	// Loop through user IDs
	foreach ( $r['user_ids'] as $user_id ) {

		// Get cellular address
		$user_cell = wp_user_alerts_get_user_cellular_address( $user_id );

		// Skip if not email address
		if ( ! is_email( $user_cell ) ) {
			continue;
		}

		// Send email
		wp_mail( $user_cell, $r['subject'], $r['message'] );
	}
}

/**
 * Alert users about a post via the theme
 *
 * @since 0.1.0
 *
 * @param  array  $args
 */
function wp_user_alerts_users_by_web( $args = array() ) {

	// Parse args
	$r = wp_parse_args( $args, array(
		'user_ids' => array(),
		'subject'  => '',
		'message'  => ''
	) );

	// Loop through user IDs
	foreach ( $r['user_ids'] as $user_id ) {

	}
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

	// Get the priority, methods, and user IDs
	$priority = wp_user_alerts_get_post_priority( $post_id )->name;
	$user_ids = wp_user_alerts_get_post_user_ids( $post_id );
	$methods  = wp_user_alerts_get_post_methods( $post_id );

	// Append priority label to subject
	$subject = "[{$priority}] " . wp_kses( $post->post_title, array() );

	// Strip tags from post content
	$message = wp_kses( $post->post_content, array() );

	// Do the alerts
	foreach ( $methods as $method ) {

		// Standard action
		do_action( 'wp_user_alerts_send_alerts', $method, $user_ids, $subject, $message );

		// Dynamic action
		do_action( "wp_user_alerts_send_{$method}", array(
			'user_ids' => $user_ids,
			'subject'  => $subject,
			'message'  => $message
		) );
	}
}

/**
 * Get the priority of a post
 *
 * @since 0.1.0
 *
 * @param  int  $post_id
 *
 * @return object
 */
function wp_user_alerts_get_post_priority( $post_id = 0 ) {

	// Get priority for alert
	$priority   = get_post_meta( $post_id, 'wp_user_alerts_priority' );
	$priorities = wp_user_alerts_get_alert_priorities();

	// Use priority
	if ( isset( $priorities->{$priority} ) ) {
		return $priorities[ $priority ];

	// Fallback to "Info"
	} elseif ( isset( $priorities->info ) ) {
		return $priorities->info;

	// "Info" is broken, so force it
	} else {
		return (object) array(
		   'name'     => esc_html__( 'Info', 'wp-user-alerts' ),
		   'callback' => ''
		);
	}
}

/**
 * Get alert methods for a post
 *
 * @since 0.1.0
 *
 * @param int $post_id
 *
 * @return array
 */
function wp_user_alerts_get_post_methods( $post_id = 0 ) {
	return get_post_meta( $post_id, 'wp_user_alerts_method' );
}

/**
 * Get array of user IDs to alert
 *
 * To add or remove user IDs to this function, you'll need to add a filter hook
 * to `wp_user_alerts_get_post_user_ids`, and merge your results with the rest
 * of the other functions that are filtering it.
 *
 * Failure to run `array_merge()` in your filter will clobber the existing
 * results. Failuer to return an array will cause fatal errors. Hook in wisely,
 * and always return a healthy array.
 *
 * @since 0.1.0
 *
 * @param int $post_id
 *
 * @return array
 */
function wp_user_alerts_get_post_user_ids( $post_id = 0 ) {

	// Allow everything to hook in and filter the user IDs
	$all_user_ids = apply_filters( 'wp_user_alerts_get_post_user_ids', array(), $post_id );

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
function wp_user_alerts_filter_post_role_user_ids( $all_user_ids = array(), $post_id = 0 ) {

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
function wp_user_alerts_filter_post_user_ids( $all_user_ids = array(), $post_id = 0 ) {

	// Get all single users
	$user_ids = get_post_meta( $post_id, 'wp_user_alerts_user' );

	// Bail if no single users
	if ( empty( $user_ids ) ) {
		return $all_user_ids;
	}

	// Merge and return
	return array_merge( $all_user_ids, $user_ids );
}
