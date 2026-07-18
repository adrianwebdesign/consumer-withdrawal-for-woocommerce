<?php

defined( 'ABSPATH' ) || exit;

class CWFW_Ajax {

	/**
	 * Register AJAX actions.
	 */
	public function init() {

		add_action(
			'wp_ajax_cwfw_submit',
			array( $this, 'submit' )
		);

	}

	/**
	 * Submit withdrawal request.
	 */
	public function submit() {

		check_ajax_referer( 'cwfw_nonce', 'nonce' );
	
		$order_id = absint( $_POST['order_id'] ?? 0 );
	
		$order = wc_get_order( $order_id );
	
		if ( ! $order ) {
			wp_send_json_error();
		}
	
		if ( get_current_user_id() !== $order->get_user_id() ) {
			wp_send_json_error();
		}

		$withdrawal = new CWFW_Withdrawal();

		if ( ! $withdrawal->can_withdraw( $order ) ) {
			wp_send_json_error();
		}
	
		$products = array();
	
		if ( ! empty( $_POST['products'] ) ) {
	
			$products = json_decode(
				wp_unslash( $_POST['products'] ),
				true
			);
	
			if ( ! is_array( $products ) ) {
				wp_send_json_error();
			}
	
			$products = array_map( 'absint', $products );
	
		}
	
		$request = new CWFW_Request();
	
		if ( ! $request->create( $order_id, $products ) ) {
			wp_send_json_error();
		}
	
		wp_send_json_success();
	
	}

}