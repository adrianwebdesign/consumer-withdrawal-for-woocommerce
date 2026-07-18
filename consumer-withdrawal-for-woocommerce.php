<?php
/**
 * Plugin Name: Consumer Withdrawal for WooCommerce
 * Plugin URI:  https://wordpress.org/plugins/consumer-withdrawal-for-woocommerce/
 * Description: Adds a consumer withdrawal request button to WooCommerce orders according to EU legislation.
 * Version:     1.0.0
 * Author:      Adrian Webdesign
 * Author URI:  https://adrianwebdesign.ro
 * License:     GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: consumer-withdrawal-for-woocommerce
 * Domain Path: /languages
 *
 * Requires at least: 6.6
 * Requires PHP:      8.1
 *
 * WC requires at least: 10.0
 * WC tested up to:     10.9.4
 *
 * @package ConsumerWithdrawal
 */

defined( 'ABSPATH' ) || exit;

/**
 * ------------------------------------------------------------------------
 * Plugin Constants
 * ------------------------------------------------------------------------
 */

define( 'CWFW_VERSION', '1.0.0' );

define( 'CWFW_PLUGIN_FILE', __FILE__ );

define( 'CWFW_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

define( 'CWFW_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

define( 'CWFW_TEXT_DOMAIN', 'consumer-withdrawal-for-woocommerce' );


/**
 * ------------------------------------------------------------------------
 * Load plugin translations.
 * ------------------------------------------------------------------------
 */

function cwfw_load_textdomain() {

	load_plugin_textdomain(
		'consumer-withdrawal-for-woocommerce',
		false,
		dirname( plugin_basename( __FILE__ ) ) . '/languages'
	);

}

add_action( 'plugins_loaded', 'cwfw_load_textdomain' );


/**
 * ------------------------------------------------------------------------
 * Check whether WooCommerce is active.
 * ------------------------------------------------------------------------
 */

function cwfw_is_woocommerce_active() {

	return class_exists( 'WooCommerce' );

}


/**
 * ------------------------------------------------------------------------
 * Display an admin notice if WooCommerce is not active.
 * ------------------------------------------------------------------------
 */

function cwfw_missing_woocommerce_notice() {

	?>

	<div class="notice notice-error">
		<p>

			<strong>
				<?php esc_html_e(
					'Consumer Withdrawal for WooCommerce requires WooCommerce to be installed and activated.',
					'consumer-withdrawal-for-woocommerce'
				); ?>
			</strong>

		</p>
	</div>

	<?php

}


/**
 * ------------------------------------------------------------------------
 * Initialize the plugin.
 * ------------------------------------------------------------------------
 */

function cwfw_init() {

	if ( ! cwfw_is_woocommerce_active() ) {

		add_action(
			'admin_notices',
			'cwfw_missing_woocommerce_notice'
		);

		return;

	}

	require_once CWFW_PLUGIN_PATH . 'includes/class-plugin.php';

	$plugin = new CWFW_Plugin();

	$plugin->run();

}

add_action( 'plugins_loaded', 'cwfw_init' );

add_action( 'before_woocommerce_init', function () {

	if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {

		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
			'custom_order_tables',
			__FILE__,
			true
		);

	}

} );