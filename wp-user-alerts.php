<?php

/**
 * Plugin Name: WP User Alerts
 * Plugin URI:  https://wordpress.org/plugins/wp-user-alerts/
 * Author:      John James Jacoby
 * Author URI:  https://profiles.wordpress.org/johnjamesjacoby/
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Description: Alert registered users when new content is published
 * Version:     0.1.0
 * Text Domain: wp-user-alerts
 * Domain Path: /assets/lang/
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
	require_once $plugin_path . 'includes/capabilities.php';
	require_once $plugin_path . 'includes/class-user-alerts.php';
	require_once $plugin_path . 'includes/class-wp-user-groups-walker.php';
	require_once $plugin_path . 'includes/functions.php';
	require_once $plugin_path . 'includes/admin.php';
	require_once $plugin_path . 'includes/post-types.php';
	require_once $plugin_path . 'includes/metadata.php';
	require_once $plugin_path . 'includes/metaboxes.php';
	require_once $plugin_path . 'includes/notices.php';
	require_once $plugin_path . 'includes/popups.php';
	require_once $plugin_path . 'includes/post-sections.php';
	require_once $plugin_path . 'includes/user-dashboard.php';
	require_once $plugin_path . 'includes/user-groups.php';
	require_once $plugin_path . 'includes/hooks.php';
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
	return 201604020002;
}
