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
 * Return array of allowed post statuses
 *
 * @since 0.1.0
 *
 * @return array
 */
function wp_user_alerts_get_allowed_post_statuses() {
	return array( 'publish', 'private' );
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

	// Default methods array
	$methods = array(
	   'email' => (object) array(
		   'name'     => esc_html__( 'Email', 'wp-user-alerts' ),
		   'callback' => 'wp_user_alerts_users_by_email',
		   'type'     => 'direct',
		   'checked'  => false
	   ),
	   'sms' => (object) array(
		   'name'     => esc_html__( 'SMS (Text)', 'wp-user-alerts' ),
		   'callback' => 'wp_user_alerts_users_by_sms',
		   'type'     => 'direct',
		   'checked'  => false
	   )
	);

	// User dashboard methods
	if ( function_exists( '_wp_user_dashboard' ) ) {
		$methods['feed'] = (object) array(
		   'name'     => esc_html__( 'Feed', 'wp-user-alerts' ),
		   'callback' => 'wp_user_alerts_users_by_feed',
		   'type'     => 'web',
		   'checked'  => true
	   );
	   $methods['notice'] = (object) array(
		   'name'     => esc_html__( 'Notice', 'wp-user-alerts' ),
		   'callback' => 'wp_user_alerts_users_by_notice',
		   'type'     => 'web',
		   'checked'  => false
	   );
	   $methods['popup'] = (object) array(
		   'name'     => esc_html__( 'Pop-Up', 'wp-user-alerts' ),
		   'callback' => 'wp_user_alerts_users_by_modal',
		   'type'     => 'web',
		   'checked'  => false
	   );
	}

	return apply_filters( 'wp_user_alerts_get_alert_methods', $methods );
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

	// Setup the From header
	$headers = array( 'From: ' . get_bloginfo( 'name' ) . ' <' . get_option( 'admin_email' ) . '>' );

	// Loop through user IDs
	foreach ( $r['user_ids'] as $user_id ) {

		// Get cellular address
		$user_email = get_userdata( $user_id )->user_email;

		// Skip if not email address
		if ( ! is_email( $user_email ) ) {
			continue;
		}

		// Send email
		wp_mail( $user_email, $r['subject'], $r['message'], $headers );
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

	// Setup the From header
	$headers = array( 'From: ' . get_bloginfo( 'name' ) . ' <' . get_option( 'admin_email' ) . '>' );

	// Loop through user IDs
	foreach ( $r['user_ids'] as $user_id ) {

		// Skip if blocked
		if ( in_array( 'block_texts', (array) get_user_meta( $user_id, 'cellular_privacy', true ), true ) ) {
			continue;
		}

		// Get cellular address
		$user_cell = wp_user_alerts_get_user_cellular_address( $user_id );

		// Skip if not email address
		if ( ! is_email( $user_cell ) ) {
			continue;
		}

		// Send email
		wp_mail( $user_cell, $r['subject'], $r['message'], $headers );
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
		'alltel' => (object) array(
			'name'   => 'AllTel',
			'format' => '@message.alltel.com'
		),
		'boost' => (object) array(
			'name'   => 'Boost',
			'format' => '@myboostmobile.com'
		),
		'cricket' => (object) array(
			'name'   => 'Cricket',
			'format' => '@sms.mycricket.com'
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
	$address  = '';

	// Get user data
	$user    = get_userdata( $user_id );
	$cell    = $user->cellular_number;
	$carrier = $user->cellular_carrier;

	// Format address
	if ( ! empty( $user->cellular_number ) ) {

		// Bail if carrier not found
		if ( ! isset( $carriers[ $carrier ] ) ) {
			return false;
		}

		// Format number for email address usage
		$cell = wp_user_alerts_sanizite_cellular_number( $cell );

		// Concatenate the cell address
		$address = "{$cell}{$carriers[ $carrier ]->format}";
	}

	// Filter & return
	return apply_filters( 'wp_user_alerts_get_user_cellular_address', $address, $user_id, $cell, $carrier );
}

/**
 * Only allow numbers and traditional cellular characters
 *
 * @since 0.1.0
 *
 * @param  string $number
 *
 * @return string
 */
function wp_user_alerts_sanizite_cellular_number( $number = '' ) {
	return preg_replace( '/[^0-9+]/', '', $number );
}

/**
 * Send all of the alerts
 *
 * @since 0.1.0
 *
 * @param  string   $new_status
 * @param  string   $old_status
 * @param  WP_Post  $post
 */
function wp_user_alerts_maybe_do_all_alerts( $new_status, $old_status, $post = null ) {

	// Allowed Statuses
	$allowed_statuses = wp_user_alerts_get_allowed_post_statuses();

	// Bail if already published
	if ( in_array( $old_status, $allowed_statuses, true ) ) {
		return;
	}

	// Bail if new status is not publish
	if ( ! in_array( $new_status, $allowed_statuses, true ) ) {
		return;
	}

	// Bail on autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Bail if not supported
	if ( ! post_type_supports( $post->post_type, 'alerts' ) ) {
		return;
	}

	// Bail if not publishing
	if ( ! in_array( get_post_status( $post->ID ), $allowed_statuses, true ) ) {
		return;
	}

	// Get the priority, methods, and user IDs
	$user_ids = wp_user_alerts_get_alert_user_ids( $post->ID );
	$methods  = wp_user_alerts_get_post_methods( $post->ID );

	// Append priority label to subject
	$subject = wp_user_alerts_get_alert_message_subject( $post );

	// Save user IDs to postmeta
	update_post_meta( $post->ID, 'wp_user_alerts_user_ids', $user_ids );

	// Do the alerts
	foreach ( $methods as $method ) {

		// Strip tags from post content
		$message = wp_user_alerts_get_alert_message_body( $post, $method );

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
 * Return text to use as the message body, based on the alert priority
 *
 * @since 0.1.0
 *
 * @param mixed $post
 */
function wp_user_alerts_get_alert_message_subject( $post = 0 ) {
	$priority = wp_user_alerts_get_alert_priority( $post->ID )->name;
	$subject  = "[{$priority}] " . wp_kses( $post->post_title, array() );
	return $subject;
}

/**
 * Return text to use as the message body, based on the alert method
 *
 * @since 0.1.0
 *
 * @param mixed $post
 */
function wp_user_alerts_get_alert_message_body( $post = 0, $method = '' ) {

	// Message
	$content = wp_kses( $post->post_content, array() );
	$message = wp_html_excerpt( $post->post_content, 100 );
	$axcerpt = get_post_meta( $post->ID, 'wp_user_alerts_message', true );

	// Override the message
	if ( ! empty( $axcerpt ) ) {
		$message = $axcerpt;
	}

	// Get methods to check for override
	$methods = wp_user_alerts_get_alert_methods();

	// Use message
	$use_message = isset( $methods[ $method ]->message )
		? (bool) $methods[ $method ]->message
		: false;

	// Force the message
	if ( ! empty( $message ) && ( true === $use_message ) ) {
		$content = $message;
	}

	return $content;
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
function wp_user_alerts_get_alert_priority( $post_id = 0 ) {

	// Get priority for alert
	$priority   = get_post_meta( $post_id, 'wp_user_alerts_priority', true );
	$priorities = wp_user_alerts_get_alert_priorities();

	// Use priority
	if ( isset( $priorities[ $priority ] ) ) {
		return $priorities[ $priority ];

	// Fallback to "Info"
	} elseif ( isset( $priorities['info'] ) ) {
		return $priorities['info'];

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
 * to `wp_user_alerts_get_alert_user_ids`, and merge your results with the rest
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
function wp_user_alerts_get_alert_user_ids( $post_id = 0 ) {

	// Allow everything to hook in and filter the user IDs
	$all_user_ids = apply_filters( 'wp_user_alerts_get_alert_user_ids', array(), $post_id );

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
function wp_user_alerts_filter_alert_role_user_ids( $all_user_ids = array(), $post_id = 0 ) {

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
function wp_user_alerts_filter_alert_user_ids( $all_user_ids = array(), $post_id = 0 ) {

	// Get all single users
	$user_ids = get_post_meta( $post_id, 'wp_user_alerts_user' );

	// Bail if no single users
	if ( empty( $user_ids ) ) {
		return $all_user_ids;
	}

	// Merge and return
	return array_merge( $all_user_ids, $user_ids );
}

/**
 * Dismiss an alert for a user
 *
 * @since 0.1.0
 *
 * @param int $post_id
 *
 * @return bool
 */
function wp_user_alerts_dismiss_alert( $post_id = 0, $user_id = 0 ) {

	// Get user & look in meta
	$dismissed = get_post_meta( $post_id, 'wp_user_alerts_dismissed' );
	$already   = in_array( $user_id, $dismissed );

	// Add the meta
	if ( false === $already ) {
		add_post_meta( $post_id, 'wp_user_alerts_dismissed', $user_id );
		clean_post_cache( $post_id );
		return true;
	}

	// Return if dismissed
	return $already;
}

/**
 * Get all alerts
 *
 * @since 0.1.0
 *
 * @param array $args
 */
function wp_user_alerts_get_posts( $args = array() ) {

	// Parse arguments
	$r = wp_parse_args( $args, array(
		'post_type'   => 'post',
		'post_status' => wp_user_alerts_get_allowed_post_statuses(),
		'meta_query'  => array( array() )
	) );

	// Filter the alert arguments
	$posts = apply_filters( 'wp_user_alerts_get_alerts', $r, $args );

	// Get the posts
	return get_posts( $posts );
}

/**
 * Get the meta query for querying for alerts
 *
 * @since 0.1.0
 *
 * @param  array  $args
 *
 * @return array
 */
function wp_user_alerts_get_meta_query( $args = array() ) {

	// Parse args
	$r = wp_parse_args( $args, array(
		'user'      => array( 1 ),
		'role'      => array(),
		'priority'  => array(),
		'method'    => array(),
		'dismissed' => array()
	) );

	// Empty query array
	$queries = array();

	$queries['or']  = $or  = array( 'relation' => 'OR'  );
	$queries['and'] = $and = array( 'relation' => 'AND' );

	// Single users
	if ( ! empty( $r['user'] ) ) {
		$queries['or'][] = array(
			'key'     => 'wp_user_alerts_user',
			'value'   => implode( ',', (array) $r['user'] ),
			'compare' => 'IN',
			'type'    => 'NUMERIC'
		);
	}

	// User Roles
	if ( ! empty( $r['role'] ) ) {
		$queries['or'][] = array(
			'key'     => 'wp_user_alerts_role',
			'value'   => implode( ',', (array) $r['role'] ),
			'compare' => 'IN',
			'type'    => 'CHAR'
		);
	}

	// Methods
	if ( ! empty( $r['method'] ) ) {
		$queries['and'][] = array(
			'key'     => 'wp_user_alerts_method',
			'value'   => implode( ',', (array) $r['method'] ),
			'compare' => 'IN',
			'type'    => 'CHAR'
		);
	}

	// Priorities
	if ( ! empty( $r['priority'] ) ) {
		$queries['and'][] = array(
			'key'     => 'wp_user_alerts_priority',
			'value'   => implode( ',', (array) $r['priority'] ),
			'compare' => 'IN',
			'type'    => 'CHAR'
		);
	}

	// Dismissed
	if ( ! empty( $r['dismissed'] ) ) {
		$queries['and'][] = array(
			'key'     => 'wp_user_alerts_dismissed',
			'value'   => implode( ',', (array) $r['dismissed'] ),
			'compare' => 'IN',
			'type'    => 'NUMERIC'
		);
	}

	// Filter the queries
	$queries = apply_filters( 'wp_user_alerts_get_meta_query', $queries, $r, $args );

	// Default relation
	$meta_query_args = array(
		'relation' => 'AND'
	);

	// OR queries
	if ( $queries['or'] !== $or ) {
		array_push( $meta_query_args, $queries['or'] );
	}

	// AND queries
	if ( $queries['and'] !== $and ) {
		array_push( $meta_query_args, $queries['and'] );
	}

	return $meta_query_args;
}

/**
 * Dismiss an alert for a user
 *
 * @since 0.1.0
 *
 * @param int $user_id
 *
 * @return bool
 */
function wp_user_alerts_delete_user( $user_id = 0 ) {

	// Get dismissed meta
	$notices   = wp_list_pluck( wp_user_alerts_get_dismissed_notices(), 'ID' );
	$popups    = wp_list_pluck( wp_user_alerts_get_dismissed_popups(),  'ID' );
	$dismissed = array_merge( $notices, $popups );

	// Loop through and delete the meta data
	foreach ( $dismissed as $post_id ) {
		$deleted = delete_post_meta( $post_id, 'wp_user_alerts_dismissed', $user_id );
	}

	return $deleted;
}
