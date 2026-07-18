<?php
/**
 * Admin page.
 *
 * @package ConsumerWithdrawal
 */

defined( 'ABSPATH' ) || exit;

class CWFW_Admin_Page {

	/**
	 * Initialize.
	 *
	 * @return void
	 */
	public function init() {

		add_action(
			'admin_menu',
			array( $this, 'register_page' )
		);

	}

	/**
	 * Register submenu.
	 *
	 * @return void
	 */
	public function register_page() {

		add_submenu_page(
			'woocommerce',
			__( 'Consumer Withdrawals', 'consumer-withdrawal-for-woocommerce' ),
			__( 'Consumer Withdrawals', 'consumer-withdrawal-for-woocommerce' ),
			'manage_woocommerce',
			'cwfw-withdrawals',
			array( $this, 'render' )
		);

	}

	/**
	 * Render page.
	 *
	 * @return void
	 */
	public function render() {

		include CWFW_PLUGIN_PATH . 'templates/admin/withdrawals.php';

	}

}