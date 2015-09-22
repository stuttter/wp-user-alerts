<?php

/**
 * Plugin Name: WP User Alerts
 * Plugin URI:  https://wordpress.org/plugins/wp-user-alerts/
 * Description: Alert users of goings-on when posting new content
 * Author:      John James Jacoby
 * Version:     0.1.0
 * Author URI:  https://profiles.wordpress.org/johnjamesjacoby/
 * License:     GPL v2 or later
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Include the User Groups files
 *
 * @since 0.1.0
 */
function _wp_user_alerts() {

	// Get the plugin path
	$dir = plugin_dir_path( __FILE__ );

	// Include the files
	include $dir . '/includes/admin.php';
	require $dir . 'includes/class-user-alerts.php';
	require $dir . 'includes/class-wp-user-groups-walker.php';
	include $dir . '/includes/capabilities.php';
	include $dir . '/includes/functions.php';
	include $dir . '/includes/post-types.php';
	//include $dir . '/includes/taxonomies.php';
	include $dir . '/includes/metadata.php';
	include $dir . '/includes/metaboxes.php';
	include $dir . '/includes/hooks.php';
}
add_action( 'plugins_loaded', '_wp_user_alerts' );

/**
 * Return the plugin's URL
 *
 * @since 0.1.0
 *
 * @return string
 */
function wp_user_alerts_get_plugin_url() {
	return plugin_dir_url( __FILE__ );
}

/**
 * Return the asset version
 *
 * @since 0.1.0
 *
 * @return int
 */
function wp_user_alerts_get_asset_version() {
	return 201509110001;
}
