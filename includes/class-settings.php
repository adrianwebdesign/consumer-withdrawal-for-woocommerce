<?php
/**
 * Plugin settings.
 *
 * Registers the Consumer Withdrawal settings page
 * inside WooCommerce > Settings.
 *
 * @package ConsumerWithdrawal
 */

defined( 'ABSPATH' ) || exit;

class CWFW_Settings {

	/**
	 * Register WordPress hooks.
	 *
	 * @return void
	 */
	public function init() {

		add_filter(
			'woocommerce_get_settings_pages',
			array( $this, 'add_settings_page' )
		);

	}

	/**
	 * Register our WooCommerce settings page.
	 *
	 * @param array $settings Existing WooCommerce settings pages.
	 *
	 * @return array
	 */
	public function add_settings_page( $settings ) {

		require_once CWFW_PLUGIN_PATH . 'includes/class-settings-page.php';

		$settings[] = new CWFW_Settings_Page();

		return $settings;

	}

}