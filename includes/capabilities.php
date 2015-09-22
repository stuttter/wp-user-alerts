<?php

/**
 * User Alerts Capabilities
 *
 * @package User/Alerts/Capabilities
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Maps alert capabilities
 *
 * @since 0.1.0
 *
 * @param  array   $caps     Capabilities for meta capability
 * @param  string  $cap      Capability name
 * @param  int     $user_id  User id
 * @param  array   $args     Arguments
 *
 * @return array   Actual capabilities for meta capability
 */
function wp_user_alerts_meta_caps( $caps = array(), $cap = '', $user_id = 0, $args = array() ) {

	// What capability is being checked?
	switch ( $cap ) {

		// Reading
		case 'read_alert' :
			$caps = array( 'list_users' );
			break;

		// Creating
		case 'create_alerts' :
			$caps = array( 'do_not_allow' );
			break;

		// Editing
		case 'publish_alerts' :
		case 'edit_alerts' :
		case 'edit_others_alerts' :
		case 'edit_alert' :

		// Deleting
		case 'delete_alert' :
		case 'delete_alerts' :
		case 'delete_others_alerts'  :
			$caps = array( 'list_users' );
			break;
	}

	return apply_filters( 'wp_user_alerts_meta_caps', $caps, $cap, $user_id, $args );
}
