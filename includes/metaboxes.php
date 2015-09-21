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
			<div class="panel" data-severity="info">
				<div class="alert-timestamp"><?php esc_html_e( 'January 1, 2015, at 11:00 am:', 'wp-user-alerts' ); ?></div>
				<div class="alert-post-content"><?php echo wpautop( wp_kses( get_post_field( 'post_content', get_the_ID() ), array() ) ); ?></div>
			</div>
		</td>
	</tr>

<?php
}