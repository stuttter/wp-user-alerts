<?php

/**
 * User Alerts Hooks
 *
 * @package UserAlerts/Hooks
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

// Global
add_action( 'init', 'wp_user_alerts_register_post_types'    );
add_action( 'init', 'wp_user_alerts_register_post_metadata' );

// Register the default alert types
add_action( 'init', 'wp_register_default_user_alert_post_types' );

// Enqueue assets
add_action( 'admin_head', 'wp_user_alerts_admin_assets' );

// Maybe add a metabox
add_action( 'add_meta_boxes', 'wp_user_alerts_admin_metaboxes' );

// Default rows
add_action( 'wp_user_alerts_metabox_rows', 'wp_user_alerts_metabox_who_and_how' );
add_action( 'wp_user_alerts_metabox_rows', 'wp_user_alerts_metabox_preview'     );
