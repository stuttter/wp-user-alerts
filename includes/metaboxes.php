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

		<?php do_action( 'wp_user_alerts_metabox_rows' ); ?>

	</table>

	<?php

	// After
	do_action( 'wp_user_alerts_metabox_after' );

	// End & flush the output buffer
	ob_end_flush();
}

/**
 * Default user-alerts metabox rows
 *
 * @since 0.1.0
 */
function wp_user_alerts_default_metabox_rows() {
?>

	<tr>
		<th>
			<label for="wp_user_alert_users"><?php esc_html_e( 'Who', 'wp-user-alerts'); ?></label>
		</th>

		<td>
			<?php wp_user_alerts_types(); ?>
		</td>

		<th>
			<label for="wp_user_alert_users_by"><?php esc_html_e( 'How', 'wp-user-alerts'); ?></label>
		</th>

		<td>
			<?php wp_user_alerts_methods(); ?>
		</td>
	</tr>

<?php
}
