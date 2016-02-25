<?php

/**
 * User Alerts User Groups
 *
 * @package UserAlerts/UserGroups
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Add Groups to Types
 *
 * @since 0.1.0
 *
 * @param array $types
 */
function wp_user_alerts_add_user_groups_to_types( $types = array() ) {

	// Bail if User Groups is not active
	if ( ! function_exists( '_wp_user_groups' ) ) {
		return $types;
	}

	// Empty types array
	$new_types = array();

	// Get user groups
	$groups = wp_get_user_group_objects();

	// Add user groups to "Who to Alert" section
	foreach ( $groups as $taxonomy_id => $taxonomy ) {
		$new_types[ $taxonomy_id ] = (object) array(
			'name'      => $taxonomy->labels->name,
			'callback'  => 'wp_user_alerts_user_group_picker',
			'object_id' => $taxonomy_id
	   );
	}

	return array_merge( $new_types, $types );
}

/**
 * Output a series of checkboxes to allow users to pick groups
 *
 * @since 0.1.0
 *
 * @param array Arguments
 */
function wp_user_alerts_user_group_picker( $args = array() ) {

	// Get groups in taxonomy
	$groups = (array) get_terms( $args['object_id'], array(
		'taxonomy'     => $args['object_id'],
		'hierarchical' => 0,
		'hide_empty'   => 0
	) );

	// Get meta data
	$post  = get_post();
	$_meta = get_post_meta( $post->ID, 'wp_user_alerts_user_group' ); ?>

	<div id="alert-<?php echo esc_attr( $args['object_id'] ); ?>" class="tabs-panel alerts-picker"<?php echo $args['visible']; ?>>
		<ul data-wp-lists="list:<?php echo esc_attr( $args['post_type'] ); ?>" class="categorychecklist form-no-clear">

			<?php foreach ( $groups as $details ) : ?>

				<li class="alert-<?php echo esc_attr( $args['object_id'] ); ?>-<?php echo esc_attr( $details->term_id ); ?>">
					<label class="selectit">
						<input value="<?php echo esc_attr( $args['object_id'] ); ?>-<?php echo esc_attr( $details->term_id ); ?>" type="checkbox" name="wp_user_alerts_user_group[]" id="" <?php checked( in_array( "{$args['object_id']}-{$details->term_id}", $_meta, true ) ); ?> />
						<?php echo translate_user_role( $details->name ); ?>
						<span class="label"><?php printf( _n( '%s Person', '%s People', $details->count, 'wp-user-alerts' ), number_format_i18n( $details->count ) ); ?></span>
					</label>
				</li>

			<?php endforeach; ?>

		</ul>
	</div>

	<?php
}

/**
 * Delete User Groups meta
 *
 * @since 0.1.0
 *
 * @param  int     $post_id
 * @param  object  $post
 */
function wp_user_alerts_delete_user_groups_meta( $post_id = 0, $post = null ) {

	// Bail if User Groups is not active
	if ( ! function_exists( '_wp_user_groups' ) ) {
		return;
	}

	delete_user_meta( $post_id, 'wp_user_alerts_user_group' );
}

/**
 * Add User Groups meta
 *
 * @since 0.1.0
 *
 * @param  int     $post_id
 * @param  object  $post
 */
function wp_user_alerts_add_user_groups_meta( $post_id = 0, $post = null ) {

	// Bail if User Groups is not active
	if ( ! function_exists( '_wp_user_groups' ) ) {
		return;
	}

	// Add user groups to "Who to Alert" section
	if ( ! empty( $_POST['wp_user_alerts_user_group'] ) ) {
		foreach ( $_POST['wp_user_alerts_user_group'] as $group_id ) {
			add_post_meta( $post_id, 'wp_user_alerts_user_group', $group_id );
		}
	}
}

/**
 * Get User Groups meta
 *
 * @since 0.1.0
 *
 * @param  int     $post_id
 * @param  object  $post
 */
function wp_user_alerts_get_user_groups_meta( $post_id = 0 ) {

	// Bail if User Groups is not active
	if ( ! function_exists( '_wp_user_groups' ) ) {
		return array();
	}

	// Get groups to alert
	return get_post_meta( $post_id, 'wp_user_alerts_user_group' );
}

/**
 * Get array of user IDs to alert based on group IDs saved to post ID
 *
 * @since 0.1.0
 *
 * @param int $post_id
 *
 * @return array
 */
function wp_user_alerts_get_user_group_member_ids( $post_id = 0 ) {

	// Default empty arrays
	$all_user_ids = $all_term_ids = $groups = array();

	// Get groups to alert
	$group_metas = wp_user_alerts_get_user_groups_meta( $post_id );

	// Bail if no groups to alert
	if ( empty( $group_metas ) ) {
		return $all_user_ids;
	}

	// Loop through group IDs and get user IDs
	foreach ( $group_metas as $group ) {

		// Get the last hyphen
		$last_pos = strrpos( $group, '-' );

		// Blow the parts up
		$taxonomy = substr( $group, 0, $last_pos );
		$group_id = substr( $group, $last_pos + 1 );

		// Skip missing or empty taxonomies
		if ( empty( $taxonomy ) || ! taxonomy_exists( $taxonomy ) ) {
			continue;
		}

		// Setup a new array
		if ( ! isset( $groups[ $taxonomy ] ) ) {
			$groups[ $taxonomy ] = array();
		}

		// Avoid duplicates
		if ( in_array( $group_id, $groups[ $taxonomy ], true ) ) {
			continue;
		}

		// Put them back together like: $groups[taxonomy][term_id]
		array_push( $groups[ $taxonomy ], $group_id );
	}

	// Assemble the term IDs
	foreach ( $groups as $taxonomy => $term_ids ) {
		$all_term_ids = array_merge( $all_term_ids, $term_ids );
	}

	// Get all user IDs in 1 swoop
	$all_term_ids = array_unique( $all_term_ids, SORT_NUMERIC );
	$all_user_ids = get_objects_in_term( $term_ids, array_keys( $groups ) );

	// Avoid duplicate user IDs and sort numerically
	return array_unique( $all_user_ids, SORT_NUMERIC );
}

/**
 * Filter user IDs to alert from registered user groups
 *
 * @since 0.1.0
 *
 * @param  array  $all_user_ids
 * @param  int    $post_id
 *
 * @return array
 */
function wp_user_alerts_filter_alert_group_user_ids( $all_user_ids = array(), $post_id = 0 ) {

	// Get user IDs from group
	$group_user_ids = wp_user_alerts_get_user_group_member_ids( $post_id );

	// Merge and return
	return array_merge( $all_user_ids, $group_user_ids );
}
