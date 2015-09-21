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
	$plugin_path = plugin_dir_path( __FILE__ );

	// Required files
	require $plugin_path . 'includes/class-user-alerts.php';
	require $plugin_path . 'includes/class-wp-user-groups-walker.php';
	require $plugin_path . 'includes/functions.php';
	require $plugin_path . 'includes/admin.php';
	require $plugin_path . 'includes/post-types.php';
	require $plugin_path . 'includes/metadata.php';
	require $plugin_path . 'includes/metaboxes.php';
	require $plugin_path . 'includes/hooks.php';
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