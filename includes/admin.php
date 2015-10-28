<?php

/**
 * User Groups Admin
 *
 * @package UserAlerts/Admin
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Tweak admin styling for a user alerts layout
 *
 * @since 0.1.0
 */
function wp_user_alerts_admin_assets() {
	$url = wp_user_alerts_get_plugin_url();
	$ver = wp_user_alerts_get_asset_version();

	wp_enqueue_style( 'wp_user_alerts',  $url . 'assets/css/user-alerts.css', false,             $ver, false );
	wp_enqueue_script( 'wp_user_alerts', $url . 'assets/js/metabox.js',       array( 'jquery' ), $ver, true  );
}

/**
 * Filter alerts posts list-table columns
 *
 * @since 0.1.0
 *
 * @param   array  $columns
 * @return  array
 */
function wp_user_alerts_manage_posts_columns( $columns = array() ) {

	// Override all columns
	$new_columns = array(
		'cb'       => '<input type="checkbox" />',
		'content'  => esc_html__( 'Content',  'wp-user-alerts' ),
		'who'      => esc_html__( 'Who',      'wp-user-alerts' ),
		'how'      => esc_html__( 'How',      'wp-user-alerts' ),
		'severity' => esc_html__( 'Severity', 'wp-user-alerts' )
	);

	// Return overridden columns
	return apply_filters( 'wp_user_alerts_manage_posts_columns', $new_columns, $columns );
}

/**
 * Force the primary column
 *
 * @since 0.1.0
 *
 * @return string
 */
function wp_user_alerts_list_table_primary_column( $name = '', $screen_id = '' ) {

	// Only on this screen
	if ( 'edit-alert' === $screen_id ) {
		$name = 'username';
	}

	// Return possibly overridden name
	return $name;
}

/**
 * Sortable alerts columns
 *
 * @since 0.1.0
 *
 * @param   array  $columns
 *
 * @return  array
 */
function wp_user_alerts_sortable_columns( $columns = array() ) {

	// Override columns
	$columns = array(
		'type'     => 'type',
		'username' => 'username',
		'session'  => 'session',
		'when'     => 'when'
	);

	return $columns;
}

/**
 * Set the relevant query vars for sorting posts by our front-end sortables.
 *
 * @since 0.1.0
 *
 * @param WP_Query $wp_query The current WP_Query object.
 */
function wp_user_alerts_maybe_sort_by_fields( WP_Query $wp_query ) {

	// Bail if not 'activty' post type
	if ( empty( $wp_query->query['post_type'] ) || ! in_array( 'alert', (array) $wp_query->query['post_type'] ) ) {
		return;
	}

	// Default order
	$order = 'DESC';

	// Some default order values
	if ( ! empty( $_REQUEST['order'] ) ) {
		$new_order = strtolower( $_REQUEST['order'] );
		if ( ! in_array( $order, array( 'asc', 'desc' ) ) ) {
			$order = $new_order;
		}
	}
}

/**
 * Set the relevant query vars for filtering posts by our front-end filters.
 *
 * @since 0.1.0
 *
 * @param WP_Query $wp_query The current WP_Query object.
 */
function wp_user_alerts_maybe_filter_by_fields( WP_Query $wp_query ) {

	// Bail if not 'activty' post type
	if ( empty( $wp_query->query['post_type'] ) || ! in_array( 'alert', (array) $wp_query->query['post_type'] ) ) {
		return;
	}
}

/**
 * Output content for each alerts item
 *
 * @since 0.1.0
 *
 * @param  string  $column
 * @param  int     $post_id
 */
function wp_user_alerts_manage_custom_column_data( $column = '', $post_id = 0 ) {

	// Get post & metadata
	$post = get_post( $post_id );
}

/**
 * Disable months dropdown
 *
 * @since 0.1.2
 */
function wp_user_alerts_disable_months_dropdown( $disabled = false, $post_type = 'post' ) {

	// Disable dropdown for alerts
	if ( 'alert' === $post_type ) {
		$disabled = true;
	}

	// Return maybe modified value
	return $disabled;
}

/**
 * Unset the "Quick Edit" row action
 *
 * @since 0.1.0
 *
 * @param array $actions
 */
function wp_user_alerts_disable_quick_edit_link( $actions = array(), $post = '' ) {

	// Unset the quick edit action
	if ( 'alert' === $post->post_type ) {
		unset( $actions['inline hide-if-no-js'] );
	}

	return $actions;
}

/**
 * Filter bulk actions & unset the edit action
 *
 * @since 0.1.0
 *
 * @param   array  $actions
 * @return  array
 */
function wp_user_alerts_disable_bulk_action( $actions = array() ) {

	// No bulk edit
	unset( $actions['edit'] );

	// Return without bulk edit
	return $actions;
}

/**
 * Output dropdowns & filters
 *
 * @since 0.1.2
 */
function wp_user_alerts_add_dropdown_filters( $post_type = '' ) {

	// Bail if not the correct post type
	if ( 'alert' !== $post_type ) {
		return;
	}

	// Query for users
	$users = get_users( array(
		'count_total' => false,
		'orderby'     => 'display_name'
	) );

	// Current user
	$current_user = ! empty( $_GET['wp-user-alerts-user'] )
		? (int) $_GET['wp-user-alerts-user']
		: 0;

	// Start an output buffer
	ob_start(); ?>

	<label class="screen-reader-text" for="wp-user-alerts-user"><?php esc_html_e( 'Filter by user', 'wp-user-alerts' ); ?></label>
	<select name="wp-user-alerts-user" id="wp-user-alerts-user">
		<option value="0"><?php esc_html_e( 'All users', 'wp-user-alerts' ); ?></option>

		<?php foreach ( $users as $user ) : ?>

			<option value="<?php echo esc_attr( $user->ID ); ?>" <?php selected( $current_user ); ?>><?php echo esc_html( $user->display_name ); ?></option>

		<?php endforeach; ?>

	</select>

	<?php

	// Output the filters
	ob_end_flush();
}
