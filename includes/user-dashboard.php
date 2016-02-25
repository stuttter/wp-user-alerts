<?php


/**
 * Return array of possible user IDs to query for
 *
 * @since 0.1.0
 *
 * @return array
 */
function wp_user_alerts_get_meta_query_user() {
	$users = (array) wp_get_displayed_user_field( 'ID' );
	return apply_filters( 'wp_user_alerts_get_meta_query_user', array_filter( $users ) );
}

/**
 * Return array of possible user roles to query for
 *
 * @since 0.1.0
 *
 * @return array
 */
function wp_user_alerts_get_meta_query_role() {
	$roles = (array) wp_get_displayed_user_field( 'roles' );
	return apply_filters( 'wp_user_alerts_get_meta_query_role', array_filter( $roles ) );
}

/**
 * Return array of news alerts
 *
 * @since 0.1.0
 *
 * @return array
 */
function wp_user_alerts_get_news_alerts() {
	return wp_user_alerts_get_posts( array(
		'numberposts' => 10,
		'post_type'   => 'post',
		'meta_query' => wp_user_alerts_get_meta_query( array(
			'user'   => wp_user_alerts_get_meta_query_user(),
			'role'   => wp_user_alerts_get_meta_query_role(),
			'method' => 'feed'
		) )
	) );
}

/**
 * Return array of events
 *
 * @since 0.1.0
 *
 * @return array
 */
function wp_user_alerts_get_events_alerts() {
	return wp_user_alerts_get_posts( array(
		'numberposts' => 10,
		'post_type'   => 'event',
		'meta_query' => wp_user_alerts_get_meta_query( array(
			'user'   => wp_user_alerts_get_meta_query_user(),
			'role'   => wp_user_alerts_get_meta_query_role(),
			'method' => 'feed'
		) )
	) );
}

/**
 * Get all alerts
 *
 * @since 0.1.0
 *
 * @param array $args
 */
function wp_user_alerts_get_posts( $args = array() ) {

	// Parse arguments
	$r = wp_parse_args( $args, array(
		'post_type'   => 'post',
		'post_status' => 'publish',
		'meta_query'  => array( array() )
	) );

	// Filter the alert arguments
	$posts = apply_filters( 'wp_user_alerts_get_alerts', $r, $args );

	// Get the posts
	return get_posts( $posts );
}

/**
 * Get the meta query for querying for alerts
 *
 * @since 0.1.0
 *
 * @param  array  $args
 *
 * @return array
 */
function wp_user_alerts_get_meta_query( $args = array() ) {

	// Parse args
	$r = wp_parse_args( $args, array(
		'user'     => array( 1 ),
		'role'     => array(),
		'priority' => array(),
		'method'   => array()
	) );

	// Empty query array
	$queries = array();

	$queries['or']  = $or  = array( 'relation' => 'OR'  );
	$queries['and'] = $and = array( 'relation' => 'AND' );

	// Single users
	if ( ! empty( $r['user'] ) ) {
		$queries['or'][] = array(
			'key'     => 'wp_user_alerts_user',
			'value'   => implode( ',', (array) $r['user'] ),
			'compare' => 'IN',
			'type'    => 'NUMERIC'
		);
	}

	// User Roles
	if ( ! empty( $r['role'] ) ) {
		$queries['or'][] = array(
			'key'     => 'wp_user_alerts_role',
			'value'   => implode( ',', (array) $r['role'] ),
			'compare' => 'IN',
			'type'    => 'CHAR'
		);
	}

	// Methods
	if ( ! empty( $r['method'] ) ) {
		$queries['and'][] = array(
			'key'     => 'wp_user_alerts_method',
			'value'   => implode( ',', (array) $r['method'] ),
			'compare' => 'IN',
			'type'    => 'CHAR'
		);
	}

	// Priorities
	if ( ! empty( $r['priority'] ) ) {
		$queries['and'][] = array(
			'key'     => 'wp_user_alerts_priority',
			'value'   => implode( ',', (array) $r['priority'] ),
			'compare' => 'IN',
			'type'    => 'CHAR'
		);
	}

	// Filter the queries
	$queries = apply_filters( 'wp_user_alerts_get_meta_query', $queries, $r, $args );

	// Default relation
	$meta_query_args = array(
		'relation' => 'AND'
	);

	// OR queries
	if ( $queries['or'] !== $or ) {
		array_push( $meta_query_args, $queries['or'] );
	}

	// AND queries
	if ( $queries['and'] !== $and ) {
		array_push( $meta_query_args, $queries['and'] );
	}

	return $meta_query_args;
}

/**
 * Filter sections and add "Home" section
 *
 * @since 0.1.0
 *
 * @param array $sections
 */
function wp_user_alerts_add_sections( $sections = array() ) {

	// News
	$sections[] = array(
		'id'           => 'news',
		'slug'         => 'news',
		'url'          => '',
		'label'        => esc_html__( 'News', 'wp-user-alerts' ),
		'show_in_menu' => true,
		'order'        => 10
	);

	// Dismissed
	$sections[] =  array(
		'id'           => 'dismissed',
		'slug'         => 'dismissed',
		'url'          => '',
		'label'        => esc_html__( 'Dismissed', 'wp-user-alerts' ),
		'show_in_menu' => 'visible',
		'order'        => 100
	);

	// Return sections
	return $sections;
}

/**
 * Do popups on all pages of a User Dashboard powered site
 *
 * @since 0.1.0
 */
function wp_user_alerts_do_popups() {
	wp_user_dashboard_get_template_part( 'popups' );
}
