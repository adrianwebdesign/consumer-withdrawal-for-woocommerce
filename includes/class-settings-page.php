<?php
/**
 * Consumer Withdrawal Settings Page.
 *
 * Adds a new settings tab under:
 *
 * WooCommerce → Settings → Consumer Withdrawal
 *
 * @package ConsumerWithdrawal
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WC_Settings_Page' ) ) {
	return;
}

class CWFW_Settings_Page extends WC_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {

		$this->id    = 'consumer_withdrawal';
		$this->label = __( 'Consumer Withdrawal', 'consumer-withdrawal-for-woocommerce' );

		parent::__construct();

	}

	/**
	 * Get plugin settings.
	 *
	 * @return array
	 */
	public function get_settings() {

		$settings = array(

			array(
				'title' => __( 'Consumer Withdrawal', 'consumer-withdrawal-for-woocommerce' ),
				'type'  => 'title',
				'desc'  => __( 'Configure the withdrawal request options.', 'consumer-withdrawal-for-woocommerce' ),
				'id'    => 'cwfw_general',
			),

			array(
				'title'   => __( 'Enable Plugin', 'consumer-withdrawal-for-woocommerce' ),
				'desc'    => __( 'Enable Consumer Withdrawal.', 'consumer-withdrawal-for-woocommerce' ),
				'id'      => 'cwfw_enabled',
				'default' => 'yes',
				'type'    => 'checkbox',
			),

			array(
				'title'             => __( 'Withdrawal Period', 'consumer-withdrawal-for-woocommerce' ),
				'desc'              => __( 'Number of days customers can request withdrawal.', 'consumer-withdrawal-for-woocommerce' ),
				'id'                => 'cwfw_period',
				'type'              => 'number',
				'default'           => 14,
				'custom_attributes' => array(
					'min'  => 1,
					'step' => 1,
				),
			),

			array(
				'title'   => __( 'Administrator Email', 'consumer-withdrawal-for-woocommerce' ),
				'desc'    => __( 'Email address that receives withdrawal notifications.', 'consumer-withdrawal-for-woocommerce' ),
				'id'      => 'cwfw_admin_email',
				'type'    => 'email',
				'default' => get_option( 'admin_email' ),
			),

			array(
				'title'   => __( 'Enable Guest Requests', 'consumer-withdrawal-for-woocommerce' ),
				'desc'    => __( 'Allow guest customers to submit withdrawal requests.', 'consumer-withdrawal-for-woocommerce' ),
				'id'      => 'cwfw_guest_requests',
				'default' => 'yes',
				'type'    => 'checkbox',
			),

			array(
				'type' => 'sectionend',
				'id'   => 'cwfw_general',
			),

		);

		return $settings;

	}

}