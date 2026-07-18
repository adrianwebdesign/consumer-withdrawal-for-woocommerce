<?php

defined( 'ABSPATH' ) || exit;

class CWFW_Request {

	/**
	 * Save withdrawal request.
	 *
	 * @param int   $order_id Order ID.
	 * @param array $products Selected product IDs.
	 *
	 * @return bool
	 */
	public function create( $order_id, $products = array() ) {

		$order = wc_get_order( $order_id );

		if ( ! $order ) {
			return false;
		}

		if ( $order->get_meta( '_cwfw_request' ) ) {
			return false;
		}

		// No product selection means the customer requests withdrawal of the entire order.
		if ( empty( $products ) ) {

			$products = array_keys( $order->get_items() );

		}

		$order->update_meta_data(
			'_cwfw_request',
			array(
				'status'       => 'pending',
				'requested_at' => current_time( 'mysql' ),
				'products'     => $products,
			)
		);

		$order->save();

		$email = new CWFW_Email();

		$email->send_admin_notification( $order );

		$email->send_customer_confirmation( $order );

		return true;

	}

}