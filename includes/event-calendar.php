<?php

/**
 * Event Calendar Functions
 *
 * @package Plugin/User/Alert/Event/Functions
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Filter query arguments for the calendar page, and add a `meta_query` clause
 * excluding posts from the view that have alerted any users.
 *
 * Public calendar pages are only for displaying public events, not for
 * displaying curated calendars for each individual user (at least not yet?)
 *
 * @since 0.1.0
 *
 * @param array $args
 * @return array
 */
function wp_user_alerts_calendar_page_main_query_args( $args = array() ) {

	// Bail if in WordPress admin
	if ( is_admin() ) {
		return $args;
	}

	// Add meta query clause
	$args[] = array(
		'relation' => 'AND',
		array(
			'key'     => 'wp_user_alerts_user_ids',
			'compare' => 'NOT EXISTS'
		)
	);

	// Return modified arguments
	return $args;
}
