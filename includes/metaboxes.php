<?php

/**
 * User Alerts Metaboxes
 *
 * @package User/Alerts/Metaboxes
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Maybe add the alert metabox, if the post type supports alerts
 *
 * @since 0.1.0
 *
 * @param  string  $post_type
 */
function wp_user_alerts_admin_metaboxes( $post_type ) {

	// Bail if post type does not support alerts
	if ( ! post_type_supports( $post_type, 'alerts' ) ) {
		return;
	}

	// Add the metabox
	add_meta_box( 'wp-user-alerts', esc_html__( 'Alerts', 'wp-user-alerts' ), 'wp_user_alerts_metabox', $post_type, 'normal', 'high' );
}

/**
 * Show the user-alerts metabox
 *
 * @since 0.1.0
 *
 * @param array $args
 */
function wp_user_alerts_metabox() {

	// Start an output buffer
	ob_start();

	// Before
	do_action( 'wp_user_alerts_metabox_before' ); ?>

	<input type="hidden" name="wp_user_alerts_metabox_nonce" value="<?php echo wp_create_nonce( 'wp_user_alerts' ); ?>" />
	<table class="form-table rowfat wp-user-alerts">
		<thead>
			<th><?php esc_html_e( 'Who to alert',      'wp-user-alerts'); ?></th>
			<th><?php esc_html_e( 'How to alert them', 'wp-user-alerts'); ?></th>
		</thead>
		<tbody>

			<?php do_action( 'wp_user_alerts_metabox_rows' ); ?>

		</tbody>
	</table>

	<?php

	// After
	do_action( 'wp_user_alerts_metabox_after' );

	// End & flush the output buffer
	ob_end_flush();
}

/**
 * Default user-alerts who and how metabox row
 *
 * @since 0.1.0
 */
function wp_user_alerts_metabox_who_and_how() {
?>

	<tr class="who-and-how">
		<td>
			<?php wp_user_alerts_types(); ?>
		</td>

		<td>
			<?php wp_user_alerts_methods(); ?>
		</td>
	</tr>

<?php
}

/**
 * Default user-alerts preview metabox row
 *
 * @since 0.1.0
 */
function wp_user_alerts_metabox_preview() {
?>

	<tr class="alert-preview">
		<td colspan="2">
			<div class="panel" data-priority="info">
				<div class="alert-timestamp"><?php echo get_the_date( 'F j, Y g:i a:' ); ?></div>
				<div class="alert-post-content"><?php echo wpautop( wp_kses( get_post_field( 'post_content', get_the_ID() ), array() ) ); ?></div>
			</div>
		</td>
	</tr>

<?php
}

/**
 * Save alert meta data to parent post ID
 *
 * @since 0.1.0
 */
function wp_user_alerts_save_alerts_metabox( $post_id = 0, $post = null ) {

	// Bail on autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Bail if not supported
	if ( ! post_type_supports( $post->post_type, 'alerts' ) ) {
		return;
	}

	// Delete all relative meta data by matching key
	delete_post_meta( $post_id, 'wp_user_alerts_user'     );
	delete_post_meta( $post_id, 'wp_user_alerts_role'     );
	delete_post_meta( $post_id, 'wp_user_alerts_method'   );
	delete_post_meta( $post_id, 'wp_user_alerts_priority' );

	// Users
	if ( ! empty( $_POST['wp_user_alerts_users'] ) ) {
		foreach ( $_POST['wp_user_alerts_users'] as $user_id ) {
			add_post_meta( $post_id, 'wp_user_alerts_user', $user_id );
		}
	}

	// Roles
	if ( ! empty( $_POST['wp_user_alerts_roles'] ) ) {
		foreach ( $_POST['wp_user_alerts_roles'] as $user_id ) {
			add_post_meta( $post_id, 'wp_user_alerts_role', $user_id );
		}
	}

	// Methods
	if ( ! empty( $_POST['wp_user_alerts_methods'] ) ) {
		foreach ( $_POST['wp_user_alerts_methods'] as $user_id ) {
			add_post_meta( $post_id, 'wp_user_alerts_method', $user_id );
		}
	}

	// Priorities
	if ( ! empty( $_POST['wp_user_alerts_priorities'] ) ) {
		foreach ( $_POST['wp_user_alerts_priorities'] as $user_id ) {
			add_post_meta( $post_id, 'wp_user_alerts_priority', $user_id );
		}
	}
}

/** User Profiles *************************************************************/

/**
 * Output an SMS metabox
 *
 * @since 0.1.0
 */
function wp_user_alerts_add_sms_metabox( $type = '', $user = null ) {

	// Register metabox for the user's SMS preferences
	add_meta_box(
		'smsdiv',
		_x( 'Cellular', 'users user-admin edit screen', 'wp-user-alerts' ),
		'wp_user_alerts_sms_metabox',
		$type,
		'normal',
		'core',
		$user
	);
}

/**
 * Output the SMS metabox
 *
 * @since 0.1.0
 *
 * @param object $user
 */
function wp_user_alerts_sms_metabox( $user = null ) {

	// Get cellular carriers
	$carriers = wp_user_alerts_get_cellular_carriers(); ?>

	<table class="form-table">
		<tr class="user-cellular-number-wrap">
			<th><label for="cellular_number"><?php esc_html_e( 'Number', 'wp-user-alerts' ); ?></label></th>
			<td><input type="tel" name="cellular_number" id="cellular_number" value="<?php echo esc_attr( $user->cellular_number ); ?>" class="regular-text"></td>
		</tr>

		<tr class="user-cellular-carrier">
			<th><label for="cellular_carrier"><?php esc_html_e( 'Carrier', 'wp-user-alerts' ); ?></label></th>
			<td>
				<select name="cellular_carrier" id="cellular_carrier" >
				<option value="0" <?php selected( false, $user->cellular_carrier ); ?>><?php esc_html_e( '&mdash; Not Listed &mdash; ', 'wp-user-alerts' ); ?></option>

				<?php foreach ( $carriers as $carrier_id => $carrier ) : ?>

					<option value="<?php echo esc_attr( $carrier_id ); ?>" <?php selected( $carrier_id, $user->cellular_carrier ); ?>><?php echo esc_html( $carrier->name ); ?></option>

				<?php endforeach; ?>

				</select>
				<p class="description"><?php esc_html_e( 'Usage charges may apply to incoming messages. Please check with your cellular carrier for more information.', 'wp-user-alerts' ); ?></p>
			</td>
		</tr>
	</table>

	<?php
}

/**
 * Update a user's cellular data
 *
 * @since 0.1.0
 *
 * @param type $user_id
 */
function wp_user_alerts_save_sms_metabox( $user_id = 0 ) {

	// Number
	if ( isset( $_POST['cellular_number'] ) ) {
		update_user_meta( $user_id, 'cellular_number', $_POST['cellular_number'] );
	}

	// Carrier
	if ( isset( $_POST['cellular_carrier'] ) ) {
		update_user_meta( $user_id, 'cellular_carrier', $_POST['cellular_carrier'] );
	}
}

/** Types *********************************************************************/

/**
 * Output the alert types picker, which allows a post author to pick which users
 * will be alerted. Defaults are "Users" and "Roles" but some other plugins may
 * be supported in the future (like WP User Groups, for example.)
 *
 * @since 0.1.0
 */
function wp_user_alerts_types() {

	// Get the post type
	$post_type = get_post_type();

	// Get alert types
	$types = wp_user_alerts_get_alert_types();

	// Reset position
	$position = 0;

	// Start an output buffer
	ob_start(); ?>

	<ul id="user-alert-who-tabs" class="category-tabs"><?php
		foreach ( $types as $type_id => $type ) {
			if ( empty( $type->name ) ) {
				continue;
			}

			$class = empty( $position )
				? 'tabs'
				: 'hide-if-no-js';

			?><li class="<?php echo esc_attr( $class ); ?>"><a href="#alert-<?php echo esc_attr( $type_id ); ?>"><?php echo esc_html( $type->name ); ?></a></li><?php

			++$position;
		}
	?></ul>

	<?php

	// Reset position
	$position = 0;

	// Loop through types & look for callback
	foreach ( $types as $type_id => $type ) {
		if ( is_callable( $type->callback ) ) {

			// Is visible?
			$visible = empty( $position )
				? ''
				: ' style="display: none;"';

			// Object ID
			$object_id = empty( $type->object_id )
				? ''
				: $type->object_id;

			// Callback
			call_user_func( $type->callback, array(
				'post_type' => $post_type,
				'visible'   => $visible,
				'object_id' => $object_id
			) );

			++$position;
		}
	}

	// All done
	ob_end_flush();
}

/**
 * Display users in a list with checkboxes to let a post author pick from them.
 *
 * @since 0.1.0
 */
function wp_user_alerts_users_picker( $args = array() ) {

	// Query for users
	$users = get_users( array(
		'count_total' => false,
		'orderby'     => 'display_name'
	) );

	// Get meta data
	$post  = get_post();
	$_meta = wp_parse_id_list( get_post_meta( $post->ID, 'wp_user_alerts_user' ) ); ?>

	<div id="alert-users" class="tabs-panel alerts-picker"<?php echo $args['visible']; ?>>
		<select data-placeholder="<?php esc_html_e( 'Search for People', 'wp-user-alerts' );?>" name="wp_user_alerts_users[]" id="<?php echo esc_attr( $args['post_type'] ); ?>-checklist" multiple="multiple"><?php

			foreach ( $users as $user ) :
				$user->filter = 'display';

				// Prefer first & last name, fallback to display name
				if ( ! empty( $user->first_name ) && ! empty( $user->last_name ) ) {
					$display_name = "{$user->first_name} {$user->last_name}";
				} else {
					$display_name = $user->display_name;
				}

				?><option value="<?php echo esc_attr( $user->ID ); ?>" <?php selected( in_array( (int) $user->ID, $_meta, true ) ); ?>><?php echo esc_html( $display_name ); ?></option><?php

			endforeach; ?>

		</select>
	</div>

	<?php
}

/**
 * Display roles in a list with checkboxes to let a post author pick from them.
 *
 * @since 0.1.0
 */
function wp_user_alerts_roles_picker( $args = array() ) {

	// Query for users
	$roles = $GLOBALS['wp_roles']->roles;

	// Get meta data
	$post  = get_post();
	$_meta = wp_parse_id_list( get_post_meta( $post->ID, 'wp_user_alerts_role' ) ); ?>

	<div id="alert-roles" class="tabs-panel alerts-picker"<?php echo $args['visible']; ?>>
		<ul id="<?php echo esc_attr( $args['post_type'] ); ?>-checklist" data-wp-lists="list:<?php echo esc_attr( $args['post_type'] ); ?>" class="categorychecklist form-no-clear">

			<?php foreach ( $roles as $role => $details ) : ?>

				<li class="alert-role-<?php echo esc_attr( $role ); ?>">
					<label class="selectit">
						<input value="<?php echo esc_attr( $role ); ?>" type="checkbox" name="wp_user_alerts_roles[]" id="" <?php checked( in_array( $role, $_meta, true ) ); ?> />
						<?php echo translate_user_role( $details['name'] ); ?>
					</label>
				</li>

			<?php endforeach; ?>

		</ul>
	</div>

	<?php
}

/** Methods *******************************************************************/

/**
 * Output user alert methods section
 *
 * @since 0.1.0
 */
function wp_user_alerts_methods() {
?>

	<ul id="user-alert-how-tabs" class="category-tabs">
		<li class="tabs"><a href="#alert-methods"><?php esc_html_e( 'Methods', 'wp-user-alerts' ); ?></a></li>
		<li class="hide-if-no-js"><a href="#alert-priorities"><?php esc_html_e( 'Priorities', 'wp-user-alerts' ); ?></a></li>
	</ul>

<?php

	// Methods
	wp_user_alerts_methods_picker();

	// Priorities
	wp_user_alerts_priority_picker();
}

/**
 * Display a list of possible alert types
 *
 * @since 0.1.0
 */
function wp_user_alerts_methods_picker() {

	// Get the post type
	$post_type = get_post_type();

	// Query for users
	$methods = wp_user_alerts_get_alert_methods();

	// Get meta data
	$post  = get_post();
	$_meta = get_post_meta( $post->ID, 'wp_user_alerts_method' ); ?>

	<div id="alert-methods" class="tabs-panel alerts-picker">
		<ul id="<?php echo esc_attr( $post_type ); ?>-checklist" data-wp-lists="list:<?php echo esc_attr( $post_type ); ?>" class="categorychecklist form-no-clear">

			<?php foreach ( $methods as $method_id => $method ) : ?>

				<li class="alert-method-<?php echo esc_attr( $method_id ); ?>">
					<label class="selectit">
						<input value="<?php echo esc_attr( $method_id ); ?>" type="checkbox" name="wp_user_alerts_methods[]" id="" <?php checked( in_array( $method_id, $_meta, true ) ); ?> />
						<?php echo esc_html( $method->name ); ?>
					</label>
				</li>

			<?php endforeach; ?>

		</ul>
	</div>

	<?php
}

/**
 * Display a list of possible alert types
 *
 * @since 0.1.0
 */
function wp_user_alerts_priority_picker() {

	// Get the post type
	$post_type = get_post_type();

	// Query for users
	$priorities = wp_user_alerts_get_alert_priorities();

	// Get meta data
	$post  = get_post();
	$_meta = get_post_meta( $post->ID, 'wp_user_alerts_priority' ); ?>

	<div id="alert-priorities" class="tabs-panel alerts-picker" style="display: none;">
		<ul id="<?php echo esc_attr( $post_type ); ?>-checklist" data-wp-lists="list:<?php echo esc_attr( $post_type ); ?>" class="categorychecklist form-no-clear">

			<?php foreach ( $priorities as $priority_id => $priority ) : ?>

				<li class="alert-priority-<?php echo esc_attr( $priority_id ); ?>">
					<label class="selectit">
						<input value="<?php echo esc_attr( $priority_id ); ?>" type="radio" name="wp_user_alerts_priorities[]" class="alert-priority" data-priority="<?php echo esc_attr( $priority_id ); ?>" id="" <?php checked( in_array( $priority_id, $_meta, true ) ); ?> />
						<?php echo esc_html( $priority->name ); ?>
					</label>
				</li>

			<?php endforeach; ?>

		</ul>
	</div>

	<?php
}

/**
 * Display hierarchical user groups form fields.
 *
 * @since 0.1.0
 *
 * @param WP_Post $post Post object.
 * @param array   $box {
 *     Categories meta box arguments.
 *
 *     @type string   $id       Meta box ID.
 *     @type string   $title    Meta box title.
 *     @type callback $callback Meta box display callback.
 *     @type array    $args {
 *         Extra meta box arguments.
 *
 *         @type string $taxonomy Taxonomy. Default 'category'.
 *     }
 * }
 */
function wp_user_alerts_groups_picker( $post, $box = array() ) {

	// Get args from box array
	$args = ! empty( $box['args'] )
		? (array) $box['args']
		: array();

	// Parse the args
	$r = wp_parse_args( $args, array(
		'taxonomy' => 'user-groups'
	) ); ?>

	<div id="taxonomy-<?php echo esc_attr( $r['taxonomy'] ); ?>" class="alerts-picker">
		<input type="hidden" name="tax_input['<?php echo esc_attr( $r['taxonomy'] ); ?>'][]" value="0" />

		<ul id="<?php echo esc_attr( $r['taxonomy'] ); ?>checklist" data-wp-lists="list:<?php echo esc_attr( $r['taxonomy'] ); ?>" class="categorychecklist form-no-clear">
			<?php wp_user_groups_terms_checklist( $post->ID, array( 'taxonomy' => $r['taxonomy'] ) ); ?>
		</ul>
	</div>

	<?php
}

/**
 * Output an unordered list of checkbox input elements labelled with term names.
 *
 * Based on wp_terms_checklist().
 *
 * @since 0.1.5
 *
 * @param int          $post Optional. Post ID. Default 0.
 * @param array|string $args {
 *     Optional. Array or string of arguments for generating a terms checklist. Default empty array.
 *
 *     @type int    $descendants_and_self ID of the category to output along with its descendants.
 *                                        Default 0.
 *     @type array  $selected_cats        List of categories to mark as checked. Default false.
 *     @type array  $popular_cats         List of categories to receive the "popular-category" class.
 *                                        Default false.
 *     @type object $walker               Walker object to use to build the output.
 *                                        Default is a Walker_Category_Checklist instance.
 *     @type string $taxonomy             Taxonomy to generate the checklist for. Default 'category'.
 *     @type bool   $checked_ontop        Whether to move checked items out of the hierarchy and to
 *                                        the top of the list. Default true.
 *     @type bool   $echo                 Whether to echo the generated markup. False to return the markup instead
 *                                        of echoing it. Default true.
 * }
 */
function wp_user_groups_terms_checklist( $post = 0, $args = array() ) {

	/**
	 * Filter the taxonomy terms checklist arguments.
	 *
	 * @since 3.4.0
	 *
	 * @see wp_terms_checklist()
	 *
	 * @param array $args    An array of arguments.
	 * @param int   $post_id The post ID.
	 */
	$params = apply_filters( 'wp_user_groups_terms_checklist', $args, $post );

	$r = wp_parse_args( $params, array(
		'descendants_and_self' => 0,
		'selected_cats'        => false,
		'popular_cats'         => false,
		'walker'               => null,
		'taxonomy'             => 'user-groups',
		'checked_ontop'        => true,
		'echo'                 => true,
	) );

	if ( empty( $r['walker'] ) || ! ( $r['walker'] instanceof Walker ) ) {
		$walker = new WP_User_Groups_Walker_Checklist;
	} else {
		$walker = $r['walker'];
	}

	// Get taxonomy
	$tax      = $r['taxonomy'];
	$taxonomy = get_taxonomy( $tax );

	$descendants_and_self = (int) $r['descendants_and_self'];
	$args                 = array( 'taxonomy' => $tax );

	// Setup arguments
	$args['disabled']  = ! current_user_can( $taxonomy->cap->assign_terms );
	$args['list_only'] = ! empty( $r['list_only'] );

	if ( is_array( $r['selected_cats'] ) ) {
		$args['selected_cats'] = $r['selected_cats'];
	} elseif ( $post ) {
		$args['selected_cats'] = wp_get_object_terms( $post, $r['taxonomy'], array_merge( $args, array( 'fields' => 'ids' ) ) );
	} else {
		$args['selected_cats'] = array();
	}

	if ( is_array( $r['popular_cats'] ) ) {
		$args['popular_cats'] = $r['popular_cats'];
	} else {
		$args['popular_cats'] = get_terms( $tax, array(
			'fields'       => 'ids',
			'orderby'      => 'count',
			'order'        => 'DESC',
			'number'       => 10,
			'hierarchical' => false
		) );
	}

	if ( true === $descendants_and_self ) {
		$terms = (array) get_terms( $tax, array(
			'child_of'     => $descendants_and_self,
			'hierarchical' => 0,
			'hide_empty'   => 0
		) );

		$self = get_term( $descendants_and_self, $tax );

		array_unshift( $terms, $self );
	} else {
		$terms = (array) get_terms( $tax, array( 'get' => 'all' ) );
	}

	$output = '';

	// Post process $terms rather than adding an exclude to the get_terms()
	// query to keep the query the same across all posts (for any query cache)
	if ( $r['checked_ontop'] ) {
		$checked_terms = array();
		$keys          = array_keys( $terms );

		foreach ( $keys as $k ) {
			if ( in_array( $terms[ $k ]->term_id, $args['selected_cats'] ) ) {
				$checked_terms[] = $terms[ $k ];
				unset( $terms[ $k ] );
			}
		}

		// Put checked cats on top
		$output .= call_user_func_array( array( $walker, 'walk' ), array( $checked_terms, 0, $args ) );
	}

	// Then the rest of them
	$output .= call_user_func_array( array( $walker, 'walk' ), array( $terms, 0, $args ) );

	if ( $r['echo'] ) {
		echo $output;
	}

	return $output;
}
