<?php
/**
 * Email notifications.
 *
 * Handles all plugin email notifications.
 *
 * @package ConsumerWithdrawal
 */

defined( 'ABSPATH' ) || exit;

class CWFW_Email {

	/**
	 * Send an email.
	 *
	 * @param string $to Recipient email.
	 * @param string $subject Email subject.
	 * @param string $message Email content.
	 *
	 * @return bool
	 */
	private function send( $to, $subject, $message ) {

		$headers = array(
			'Content-Type: text/html; charset=UTF-8',
		);
	
		return wp_mail(
			$to,
			$subject,
			$message,
			$headers
		);
	
	}

	/**
	 * Load email template.
	 *
	 * @param string   $template Template filename.
	 * @param WC_Order $order    Order object.
	 * @param string   $subject  Email subject.
	 *
	 * @return string
	 */
	private function get_template( $template, WC_Order $order, $subject ) {

		ob_start();

		include CWFW_PLUGIN_PATH . 'templates/emails/header.php';

		wc_get_template(
			'emails/' . $template,
			array(
				'order' => $order,
			),
			'consumer-withdrawal/',
			CWFW_PLUGIN_PATH . 'templates/'
		);

		include CWFW_PLUGIN_PATH . 'templates/emails/footer.php';

		return ob_get_clean();

	}

	/**
	 * Send notification to administrator.
	 *
	 * @param WC_Order $order Order object.
	 *
	 * @return bool
	 */
	public function send_admin_notification( WC_Order $order ) {

		$subject = sprintf(
			__( 'New Withdrawal Request - Order #%d', 'consumer-withdrawal-for-woocommerce' ),
			$order->get_id()
		);

		$message = $this->get_template(
			'admin-notification.php',
			$order,
			$subject
		);

		return $this->send(
			cwfw_get_admin_email(),
			$subject,
			$message
		);

	}

	/**
	 * Send confirmation email to customer.
	 *
	 * @param WC_Order $order Order object.
	 *
	 * @return bool
	 */
	public function send_customer_confirmation( WC_Order $order ) {

		$subject = __(
			'Your withdrawal request has been received',
			'consumer-withdrawal-for-woocommerce'
		);
	
		$message = $this->get_template(
			'customer-confirmation.php',
			$order,
			$subject
		);
	
		return $this->send(
			$order->get_billing_email(),
			$subject,
			$message
		);
	
	}
	

}