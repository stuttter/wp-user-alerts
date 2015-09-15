<?php

/**
 * User Groups Alerts
 *
 * @package UserAlerts/Classes/UserAlert
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WP_User_Alert' ) ) :
/**
 * The main User Taxonomy class
 *
 * @since 0.1.0
 */
class WP_User_Alert {

	/**
	 * The unique ID to use for the taxonomy type
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	public $post_type = '';

	/**
	 * Array of taxonomy properties
	 *
	 * Use the custom `singular` and `plural` arguments to let this class
	 * generate labels for you. Note that labels cannot be translated using
	 * this method, so if you need different languages, use the `$labels`
	 * array below.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	public $args = array();

	/**
	 * Array of taxonomy labels, if you'd like to customize them completely
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	public $labels = array();

	/**
	 * Main constructor
	 *
	 * @since 0.1.0
	 *
	 * @param  string  $post_type
	 * @param  string  $slug
	 * @param  array   $args
	 * @param  array   $labels
	 */
	public function __construct( $post_type = '', $args = array(), $labels = array() ) {

		// Bail if no taxonomy is passed
		if ( empty( $post_type ) ) {
			return;
		}

		/** Class Variables ***************************************************/

		// Set the taxonomy
		$this->post_type = sanitize_key( $post_type );
		$this->args      = $args;
		$this->labels    = $labels;

		// Hook into actions & filters
		$this->hooks();
	}

	/**
	 * Hook in to actions & filters
	 *
	 * @since 0.1.1
	 */
	protected function hooks() {

		// Column styling
		add_action( 'admin_head', array( $this, 'admin_head' ) );

		// Update the groups when the edit user page is updated
		add_action( 'personal_options_update',  array( $this, 'save_terms_for_user' ) );
		add_action( 'edit_user_profile_update', array( $this, 'save_terms_for_user' ) );

		// Add section to the edit user page in the admin to select group
		add_action( 'show_user_profile', array( $this, 'edit_user_relationships' ), 99 );
		add_action( 'edit_user_profile', array( $this, 'edit_user_relationships' ), 99 );

		// Cleanup stuff
		add_action( 'delete_user',   array( $this, 'delete_term_relationships' ) );
	}

	/**
	 * Add the administration page for this taxonomy
	 *
	 * @since 0.1.0
	 */
	public function add_admin_page() {}

	/**
	 * This tells WordPress to highlight the "Users" menu item when viewing a
	 * user taxonomy.
	 *
	 * @since 0.1.0
	 *
	 * @global string $plugin_page
	 */
	public function admin_menu_highlight() {}

	/**
	 * Filter the body class
	 *
	 * @since 0.1.0
	 */
	public function admin_load() {
		add_filter( 'admin_body_class', array( $this, 'admin_body_class' ) );
	}

	/**
	 * Add a class for this taxonomy
	 *
	 * @since 0.1.0
	 *
	 * @param   string $classes
	 * @return  string
	 */
	public function admin_body_class( $classes = '' ) {}

	/**
	 * Stylize custom columns
	 *
	 * @since 0.1.0
	 */
	public function admin_head() {}

	/**
	 * Manage columns for user taxonomies
	 *
	 * @since 0.1.0
	 *
	 * @param   array $columns
	 * @return  array
	 */
	public function manage_edit_users_column( $columns = array() ) {}

	/**
	 * Output the data for the "Users" column when viewing user taxonomies
	 *
	 * @since 0.1.0
	 *
	 * @param string $display
	 * @param string $column
	 * @param string $term_id
	 */
	public function manage_custom_column( $display = false, $column = '', $term_id = 0 ) {
		if ( 'users' === $column ) {
			$term  = get_term( $term_id, $this->taxonomy );
			$args  = array( $this->taxonomy => $term->slug );
			$users = admin_url( 'users.php' );
			$url   = add_query_arg( $args, $users );
			$text  = number_format_i18n( $term->count );
			echo '<a href="' . esc_url( $url ) . '">' . esc_html( $text ) . '</a>';
		}
	}

	/**
	 * Output a "Relationships" section to show off taxonomy groupings
	 *
	 * @since 0.1.0
	 *
	 * @param  mixed  $user
	 */
	public function edit_user_relationships( $user = false ) {}

	/**
	 * Output row actions when editing a user
	 *
	 * @since 0.1.1
	 *
	 * @param object $term
	 */
	protected function row_actions( $tax = array(), $term = false ) {}

	/**
	 * Delete term relationships
	 *
	 * @since 0.1.0
	 *
	 * @param int $user_id
	 */
	public function delete_term_relationships( $user_id = 0 ) {}
}
endif;
