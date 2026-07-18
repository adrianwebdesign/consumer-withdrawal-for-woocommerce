<?php

defined( 'ABSPATH' ) || exit;

class CWFW_Admin {

	public function init() {

		add_action(
			'woocommerce_admin_order_data_after_order_details',
			array( $this, 'display_request' )
		);
	
		add_action(
			'woocommerce_process_shop_order_meta',
			array( $this, 'save_request' )
		);
	
	}

	/**
 * Display withdrawal information.
 *
 * @param WC_Order $order Order object.
 *
 * @return void
 */
public function display_request( $order ) {

	$request = $order->get_meta( '_cwfw_request' );

	if ( empty( $request ) ) {
		return;
	}

	$status = $request['status'] ?? 'pending';

	?>

	<div class="order_data_column">

		<h4><?php esc_html_e( 'Consumer Withdrawal', 'consumer-withdrawal-for-woocommerce' ); ?></h4>

		<p>

			<label for="cwfw_status">

				<strong><?php esc_html_e( 'Status', 'consumer-withdrawal-for-woocommerce' ); ?></strong>

			</label>

			<br>

			<select id="cwfw_status" name="cwfw_status">

				<option value="pending" <?php selected( $status, 'pending' ); ?>>
					<?php esc_html_e( 'Pending', 'consumer-withdrawal-for-woocommerce' ); ?>
				</option>

				<option value="approved" <?php selected( $status, 'approved' ); ?>>
					<?php esc_html_e( 'Approved', 'consumer-withdrawal-for-woocommerce' ); ?>
				</option>

				<option value="rejected" <?php selected( $status, 'rejected' ); ?>>
					<?php esc_html_e( 'Rejected', 'consumer-withdrawal-for-woocommerce' ); ?>
				</option>

				<option value="completed" <?php selected( $status, 'completed' ); ?>>
					<?php esc_html_e( 'Completed', 'consumer-withdrawal-for-woocommerce' ); ?>
				</option>

			</select>

		</p>

		<p>

			<strong><?php esc_html_e( 'Requested at', 'consumer-withdrawal-for-woocommerce' ); ?></strong>

			<br>

			<?php
			if ( ! empty( $request['requested_at'] ) ) {

				echo esc_html(
					wp_date(
						get_option( 'date_format' ) . ' ' . get_option( 'time_format' ),
						strtotime( $request['requested_at'] ),
						wp_timezone()
					)
				);
			
			} else {
			
				echo '&mdash;';
			
			}
			?>

		</p>

		<?php if ( ! empty( $request['products'] ) ) : ?>

			<h4><?php esc_html_e( 'Products', 'consumer-withdrawal-for-woocommerce' ); ?></h4>

			<ul>

				<?php foreach ( $request['products'] as $item_id ) :

					$item = $order->get_item( $item_id );

					if ( ! $item ) {
						continue;
					}

				?>

					<li>

					&check; <?php echo esc_html( $item->get_name() ); ?>

						<?php
						if ( $item->get_quantity() > 1 ) {

							printf(
								esc_html__( ' (× %d)', 'consumer-withdrawal-for-woocommerce' ),
								$item->get_quantity()
							);

						}
						?>

					</li>

				<?php endforeach; ?>

			</ul>

		<?php endif; ?>

	</div>

	<?php

}

/**
 * Save withdrawal status.
 *
 * @param int $order_id Order ID.
 *
 * @return void
 */
public function save_request( $order_id ) {

	if ( ! current_user_can( 'edit_shop_order', $order_id ) ) {
		return;
	}

	if ( ! isset( $_POST['cwfw_status'] ) ) {
		return;
	}

	$order = wc_get_order( $order_id );

	if ( ! $order ) {
		return;
	}

	$request = $order->get_meta( '_cwfw_request' );

	if ( empty( $request ) ) {
		return;
	}

	$request['status'] = sanitize_text_field(
		wp_unslash( $_POST['cwfw_status'] )
	);

	$order->update_meta_data(
		'_cwfw_request',
		$request
	);

	$order->save();

}

}