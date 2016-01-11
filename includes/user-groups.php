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
 * Delete User Groups meta
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
