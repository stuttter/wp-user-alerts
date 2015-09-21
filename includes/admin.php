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

	wp_enqueue_style( 'wp_user_alerts',  $url . '/assets/css/user-alerts.css', false,             $ver, false );
	wp_enqueue_script( 'wp_user_alerts', $url . '/assets/js/tabs.js',          array( 'jquery' ), $ver, true  );
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

	<ul id="user-alert-who-tabs" class="category-tabs">

		<?php

		foreach ( $types as $type_id => $type ) {
			if ( ! empty( $type->name ) ) {
				$class = empty( $position ) ? 'tabs' : 'hide-if-no-js'; ?>

				<li class="<?php echo esc_attr( $class ); ?>"><a href="#alert-<?php echo esc_attr( $type_id ); ?>"><?php echo esc_html( $type->name ); ?></a></li>

		<?php
			++$position;

			}
		} ?>

	</ul>

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

			call_user_func( $type->callback, $post_type, $visible );

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
function wp_user_alerts_users_picker( $post_type = '', $visible = '' ) {

	// Query for users
	$users = get_users( array(
		'count_total' => false,
		'fields' => array(
			'ID', 'display_name', 'user_email'
		)
	) ); ?>

	<div id="alert-users" class="tabs-panel alerts-picker"<?php echo $visible; ?>>
		<ul id="<?php echo esc_attr( $post_type ); ?>-checklist" data-wp-lists="list:<?php echo esc_attr( $post_type ); ?>" class="categorychecklist form-no-clear">

			<?php foreach ( $users as $user ) : ?>

				<li class="alert-user-<?php echo esc_attr( $user->user_nicename ); ?>">
					<label class="selectit">
						<input value="<?php echo esc_attr( $user->ID ); ?>" type="checkbox" name="user_alert[]" id="" />
						<?php echo esc_html( sprintf( '%s - %s', $user->display_name, $user->user_email ) ); ?>
					</label>
				</li>

			<?php endforeach; ?>

		</ul>
	</div>

	<?php
}

/**
 * Display roles in a list with checkboxes to let a post author pick from them.
 *
 * @since 0.1.0
 */
function wp_user_alerts_roles_picker( $post_type = '', $visible = '' ) {

	// Get the post type
	$post_type = get_post_type();

	// Query for users
	$roles = $GLOBALS['wp_roles']->roles; ?>

	<div id="alert-roles" class="tabs-panel alerts-picker"<?php echo $visible; ?>>
		<ul id="<?php echo esc_attr( $post_type ); ?>-checklist" data-wp-lists="list:<?php echo esc_attr( $post_type ); ?>" class="categorychecklist form-no-clear">

			<?php foreach ( $roles as $role => $details ) : ?>

				<li class="alert-role-<?php echo esc_attr( $role ); ?>">
					<label class="selectit">
						<input value="<?php echo esc_attr( $role ); ?>" type="checkbox" name="role_alert[]" id="" />
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
		<li class="hide-if-no-js"><a href="#alert-severities"><?php esc_html_e( 'Severities', 'wp-user-alerts' ); ?></a></li>
	</ul>

<?php

	// Methods
	wp_user_alerts_methods_picker();

	// Severities
	wp_user_alerts_severity_picker();
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
	$methods = wp_user_alerts_get_alert_methods(); ?>

	<div id="alert-methods" class="tabs-panel alerts-picker">
		<ul id="<?php echo esc_attr( $post_type ); ?>-checklist" data-wp-lists="list:<?php echo esc_attr( $post_type ); ?>" class="categorychecklist form-no-clear">

			<?php foreach ( $methods as $method_id => $method ) : ?>

				<li class="alert-method-<?php echo esc_attr( $method_id ); ?>">
					<label class="selectit">
						<input value="<?php echo esc_attr( $method_id ); ?>" type="checkbox" name="user_alert[]" id="" />
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
function wp_user_alerts_severity_picker() {

	// Get the post type
	$post_type = get_post_type();

	// Query for users
	$severities = wp_user_alerts_get_alert_severities(); ?>

	<div id="alert-severities" class="tabs-panel alerts-picker" style="display: none;">
		<ul id="<?php echo esc_attr( $post_type ); ?>-checklist" data-wp-lists="list:<?php echo esc_attr( $post_type ); ?>" class="categorychecklist form-no-clear">

			<?php foreach ( $severities as $severity_id => $severity ) : ?>

				<li class="alert-severity-<?php echo esc_attr( $severity_id ); ?>">
					<label class="selectit">
						<input value="<?php echo esc_attr( $severity_id ); ?>" type="radio" name="alert_severity[]" id="" <?php checked( $severity_id, 'info' ); ?> />
						<?php echo esc_html( $severity->name ); ?>
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
	$args                 = array( 'taxonomy' => $r['taxonomy'] );

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
