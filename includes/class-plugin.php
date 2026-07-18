<?php
/**
 * Main plugin class.
 *
 * This class is responsible for bootstrapping the plugin.
 * It loads all required files and initializes the plugin components.
 *
 * @package ConsumerWithdrawal
 */

defined( 'ABSPATH' ) || exit;

class CWFW_Plugin {

	/**
	 * Plugin constructor.
	 *
	 * The constructor should remain lightweight.
	 * Do not initialize components here.
	 * Use the run() method instead.
	 */
	public function __construct() {}

	/**
	 * Initialize the plugin.
	 *
	 * This method is called from the main plugin file.
	 *
	 * @return void
	 */
	public function run() {

		$this->load_files();

		$this->init();

	}

	/**
	 * Load all required plugin files.
	 *
	 * Every new component should be loaded here.
	 *
	 * @return void
	 */
	private function load_files() {

		require_once CWFW_PLUGIN_PATH . 'includes/functions.php';

        require_once CWFW_PLUGIN_PATH . 'includes/class-settings.php';

        require_once CWFW_PLUGIN_PATH . 'includes/class-withdrawal.php';

        require_once CWFW_PLUGIN_PATH . 'includes/class-account.php';

		require_once CWFW_PLUGIN_PATH . 'includes/class-request.php';

		require_once CWFW_PLUGIN_PATH . 'includes/class-email.php';

		require_once CWFW_PLUGIN_PATH . 'includes/class-ajax.php';

		require_once CWFW_PLUGIN_PATH . 'includes/class-admin.php';

		require_once CWFW_PLUGIN_PATH . 'includes/class-admin-page.php';

		require_once CWFW_PLUGIN_PATH . 'includes/admin/class-withdrawals-list-table.php';

	}

	/**
	 * Initialize plugin components.
	 *
	 * Every component should initialize itself.
	 *
	 * @return void
	 */
	private function init() {

        $settings = new CWFW_Settings();
        $settings->init();
    
        $account = new CWFW_Account();
        $account->init();

		$ajax = new CWFW_Ajax();
		$ajax->init();

		$admin = new CWFW_Admin();
		$admin->init();

		$admin_page = new CWFW_Admin_Page();
        $admin_page->init();
    
    }

}