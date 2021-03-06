<?php

/**
 * User Alert Post Sections
 *
 * @package Plugin/User/Alert/Post/Sections
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Add dashboard section
 *
 * @since 0.1.0
 *
 * @param array $sections
 *
 * @return array
 */
function wp_user_alerts_add_post_section( $sections = array() ) {

	// Bail if no User Dashboard
	if ( ! function_exists( '_wp_user_dashboard' ) ) {
		return $sections;
	}

	// Add dashboard section
	$sections['dashboard'] = array(
		'label' => _x( 'Alerted Only', 'Post section', 'wp-user-alerts' ),
		'icon'  => 'hidden'
	);

	// Return modified sections
	return $sections;
}
