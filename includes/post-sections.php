<?php

/**
 * Plugin Name: Cut Time - Post Sections
 * Plugin URI:  https://cuttime.net
 * Description: Customize WP Post Suctions, for Events and more
 * Author:      The Flox Team
 * Author URI:  https://flox.io
 * Version:     0.1.0
 * Text Domain: ct-post-protect
 * Domain Path: /lang
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Add section support to events
 *
 * @since 0.1.0
 */
function ct_post_sections_add_event_support() {
	add_post_type_support( 'event', 'sections' );
}
add_action( 'init', 'ct_post_sections_add_event_support' );

/**
 * Filter sections, and unset "featured" in events
 *
 * @since 0.1.0
 *
 * @param array $sections
 */
function ct_post_sections_filter_sections( $sections = array() ) {

	// Remove "featured" section
	if ( 'event' === get_current_screen()->post_type ) {
		unset( $sections['featured'] );
	}

	// Return modified sections
	return $sections;
}
add_filter( 'wp_get_post_sections', 'ct_post_sections_filter_sections' );
