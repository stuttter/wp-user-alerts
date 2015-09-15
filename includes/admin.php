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
	wp_enqueue_style( 'wp_user_alerts', wp_user_alerts_get_plugin_url() . '/assets/css/user-alerts.css', false, wp_user_alerts_get_asset_version(), false );
}

/**
 * Display a list of possible alert types
 *
 * @since 0.1.0
 */
function wp_user_alerts_type_picker() {

	// Get the post type
	$post_type = get_post_type();

	// Query for users
	$types = wp_user_alerts_get_alert_types(); ?>

	<div id="user-alert-<?php echo esc_attr( $post_type ); ?>" class="alerts-picker">
		<ul id="<?php echo esc_attr( $post_type ); ?>-checklist" data-wp-lists="list:<?php echo esc_attr( $post_type ); ?>" class="categorychecklist form-no-clear">

			<?php foreach ( $types as $type_id => $type ) : ?>

				<li>
					<label class="selectit">
						<input value="<?php echo esc_attr( $type_id ); ?>" type="checkbox" name="user_alert[]" id="" />
						<?php echo esc_html( $type->name ); ?>
					</label>
				</li>

			<?php endforeach; ?>

		</ul>
	</div>

	<?php
}

/**
 * Display users in a list with checkboxes to let a post author pick from them.
 *
 * @since 0.1.0
 */
function wp_user_alerts_users_picker() {

	// Get the post type
	$post_type = get_post_type();

	// Query for users
	$users = get_users( array(
		'count_total' => false,
		'fields' => array(
			'ID', 'display_name', 'user_email'
		)
	) ); ?>

	<div id="user-alert-<?php echo esc_attr( $post_type ); ?>" class="alerts-picker">
		<input type="hidden" name="user_alert['<?php echo esc_attr( $post_type ); ?>'][]" value="0" />

		<ul id="<?php echo esc_attr( $post_type ); ?>-checklist" data-wp-lists="list:<?php echo esc_attr( $post_type ); ?>" class="categorychecklist form-no-clear">

			<?php foreach ( $users as $user ) : ?>

				<li>
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
 * Display hierarchical user groups form fields.
 *
 * @since 0.1.5
 *
 * @todo Create taxonomy-agnostic wrapper for this.
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
function wp_user_alerts_groups_picker( $post, $box ) {

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
