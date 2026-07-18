<?php
/**
 * Customer account functionality.
 *
 * Displays the withdrawal button
 * on the order details page.
 *
 * @package ConsumerWithdrawal
 */

defined( 'ABSPATH' ) || exit;

class CWFW_Account {

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function init() {

		add_action(
			'woocommerce_order_details_after_order_table',
			array( $this, 'render_button' )
		);

        add_action(
            'wp_enqueue_scripts',
            array( $this, 'enqueue_styles' )
        );

		add_action(
			'wp_enqueue_scripts',
			array( $this, 'enqueue_scripts' )
		);

	}

	/**
 * Render withdrawal button.
 *
 * @param WC_Order $order Order object.
 *
 * @return void
 */
public function render_button( $order ) {

	$withdrawal = new CWFW_Withdrawal();

	if ( ! $withdrawal->can_withdraw( $order ) ) {
		return;
	}

	$request = $order->get_meta( '_cwfw_request' );

	/*
	|--------------------------------------------------------------------------
	| Request already submitted
	|--------------------------------------------------------------------------
	*/

	if ( ! empty( $request ) ) {

		$date = '';

		if ( ! empty( $request['requested_at'] ) ) {

			$date = wp_date(
				get_option( 'date_format' ) . ' \a\t ' . get_option( 'time_format' ),
				strtotime( $request['requested_at'] ),
				wp_timezone()
			);

		}

		printf(
			'<div class="woocommerce-message">
				<strong>%s</strong><br>
				%s
			</div>',
			sprintf(
				esc_html__(
					'Withdrawal request submitted on %s.',
					'consumer-withdrawal-for-woocommerce'
				),
				esc_html( $date )
			),
			esc_html__(
				'Our team will contact you shortly.',
				'consumer-withdrawal-for-woocommerce'
			)
		);

		return;

	}

	/*
	|--------------------------------------------------------------------------
	| Display button
	|--------------------------------------------------------------------------
	*/

	wc_get_template(
		'withdraw-button.php',
		array(
			'order' => $order,
		),
		'',
		CWFW_PLUGIN_PATH . 'templates/'
	);

}
    
    public function enqueue_styles() {

        wp_enqueue_style(
            'cwfw-frontend',
            CWFW_PLUGIN_URL . 'assets/css/frontend.css',
            array(),
            CWFW_VERSION
        );
    
    }

	public function enqueue_scripts() {

		wp_enqueue_script(
			'cwfw-frontend',
			CWFW_PLUGIN_URL . 'assets/js/frontend.js',
			array(),
			CWFW_VERSION,
			true
		);

		wp_localize_script(
			'cwfw-frontend',
			'cwfw',
		
			array(
		
				'ajax_url' => admin_url( 'admin-ajax.php' ),
		
				'nonce' => wp_create_nonce( 'cwfw_nonce' ),
		
				'i18n' => array(
		
					'select_product' => __(
						'Please select at least one product.',
						'consumer-withdrawal-for-woocommerce'
					),
		
					'submit_error' => __(
						'Unable to submit the withdrawal request.',
						'consumer-withdrawal-for-woocommerce'
					),
		
					'unexpected_error' => __(
						'An unexpected error occurred.',
						'consumer-withdrawal-for-woocommerce'
					),
		
					'success' => __(
						'Your withdrawal request has been submitted successfully.',
						'consumer-withdrawal-for-woocommerce'
					),
		
				),
		
			)
		);
	
	}
}