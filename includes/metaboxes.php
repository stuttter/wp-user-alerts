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
	ob_start(); ?>

	<input type="hidden" name="wp_user_alerts_metabox_nonce" value="<?php echo wp_create_nonce( 'wp_user_alerts' ); ?>" />
	<table class="form-table rowfat">
		<tr class="broadcast">
			<td>
				
			</td>
			<td colspan="3">
				<label>
					<input type="checkbox" id="wp_user_alerts_broadcast" name="wp_user_alerts_broadcast" value="" />
					<?php esc_html_e( 'Broadcast this alert to everyone', 'wp-user-alerts' ); ?>
					<p class="description">
						<?php esc_html_e( 'A broadcast is a specific type of alert that uses all communication channels.', 'wp-user-alerts' ); ?>
					</p>
				</label>
			</td>
		</tr>

		<tr>
			<th>
				<label for="wp_user_alert_users"><?php esc_html_e( 'Users', 'wp-user-alerts'); ?></label>
			</th>

			<td>
				<?php wp_user_alerts_users_picker(); ?>
			</td>

			<th>
				<label for="wp_user_alert_users_by"><?php esc_html_e( 'Via', 'wp-user-alerts'); ?></label>
			</th>

			<td>
				<?php wp_user_alerts_type_picker(); ?>
			</td>
		</tr>
	</table>

	<?php

	// End & flush the output buffer
	ob_end_flush();
}
