<?php

/**
 * User Alerts Hooks
 *
 * @package UserAlerts/Hooks
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

// Global
add_action( 'init', 'wp_user_alerts_register_post_types' );
add_action( 'init', 'wp_user_alerts_register_metadata'   );

// Register the default alert types
add_action( 'init', 'wp_register_default_user_alert_post_types' );

// Caps
add_filter( 'map_meta_cap', 'wp_user_alerts_meta_caps', 10, 4 );

// Enqueue assets
add_action( 'admin_head', 'wp_user_alerts_admin_assets' );

// Maybe add a metabox
add_action( 'add_meta_boxes', 'wp_user_alerts_admin_metaboxes' );

// Maybe save metabox contents
add_action( 'save_post', 'wp_user_alerts_save_alerts_metabox', 8,  2 );
add_action( 'save_post', 'wp_user_alerts_maybe_do_all_alerts', 12, 2 );

// User Profiles metabox
add_action( 'wp_user_profiles_add_profile_meta_boxes', 'wp_user_alerts_add_sms_metabox', 10, 2 );

// Save User Profile
add_action( 'personal_options_update',  'wp_user_alerts_save_sms_metabox' );
add_action( 'edit_user_profile_update', 'wp_user_alerts_save_sms_metabox' );

// Support for User Groups
add_filter( 'wp_user_alerts_get_alert_types', 'wp_user_alerts_add_user_groups_to_types' );
add_filter( 'wp_user_alerts_delete_metas',    'wp_user_alerts_delete_user_groups_meta'  );
add_filter( 'wp_user_alerts_add_metas',       'wp_user_alerts_add_user_groups_meta'     );

// Default rows
add_action( 'wp_user_alerts_metabox_rows', 'wp_user_alerts_metabox_who_and_how' );
add_action( 'wp_user_alerts_metabox_rows', 'wp_user_alerts_metabox_preview'     );

// Quick edit
add_filter( 'page_row_actions',        'wp_user_alerts_disable_quick_edit_link', 10, 2 );
add_filter( 'bulk_actions-edit-alert', 'wp_user_alerts_disable_bulk_action'            );

// List Table
add_filter( 'disable_months_dropdown', 'wp_user_alerts_disable_months_dropdown', 10, 2 );
add_action( 'restrict_manage_posts',   'wp_user_alerts_add_dropdown_filters'           );

// Columns
add_filter( 'manage_alert_posts_columns',         'wp_user_alerts_manage_posts_columns'             );
add_filter( 'manage_alert_posts_custom_column',   'wp_user_alerts_manage_custom_column_data', 10, 2 );
add_filter( 'manage_edit-alert_sortable_columns', 'wp_user_alerts_sortable_columns' );
add_filter( 'list_table_primary_column',          'wp_user_alerts_list_table_primary_column', 10, 2 );

// Admin only filter for list-table sorting
if ( is_admin() ) {
	add_filter( 'pre_get_posts', 'wp_user_alerts_maybe_sort_by_fields'   );
	add_filter( 'pre_get_posts', 'wp_user_alerts_maybe_filter_by_fields' );
}

// Users to alert
add_filter( 'wp_user_alerts_get_post_user_ids', 'wp_user_alerts_filter_post_user_ids',       77, 2 );
add_filter( 'wp_user_alerts_get_post_user_ids', 'wp_user_alerts_filter_post_role_user_ids',  88, 2 );
add_filter( 'wp_user_alerts_get_post_user_ids', 'wp_user_alerts_filter_post_group_user_ids', 99, 2 );

// Send alerts by method
add_action( 'wp_user_alerts_send_email', 'wp_user_alerts_users_by_email', 10 );
add_action( 'wp_user_alerts_send_sms',   'wp_user_alerts_users_by_sms',   10 );
