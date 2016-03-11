<?php


/**
 * Return array of possible user IDs to query for active alerts
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
 * Return array of possible user IDs to query for dismissed alerts
 *
 * @since 0.1.0
 *
 * @return array
 */
function wp_user_alerts_get_meta_query_dismissed() {
	$users = (array) wp_get_displayed_user_field( 'ID' );
	return apply_filters( 'wp_user_alerts_get_meta_query_dismissed', array_filter( $users ) );
}

/**
 * Return array of possible user roles to query for active alerts
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
