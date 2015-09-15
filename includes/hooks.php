<?php

/**
 * User Alerts Hooks
 *
 * @package UserAlerts/Hooks
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

// Register the default taxonomies
add_action( 'init', 'wp_register_default_user_alert_post_types' );

// Enqueue assets
add_action( 'admin_head', 'wp_user_alerts_admin_assets' );

// Maybe add a metabox
add_action( 'add_meta_boxes', 'wp_user_alerts_admin_metaboxes' );
