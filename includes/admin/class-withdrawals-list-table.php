<?php
/**
 * Withdrawals List Table.
 *
 * @package ConsumerWithdrawal
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class CWFW_Withdrawals_List_Table extends WP_List_Table {

	/**
	 * Constructor.
	 */
	public function __construct() {

		parent::__construct(
			array(
				'singular' => __( 'Withdrawal', 'consumer-withdrawal-for-woocommerce' ),
				'plural'   => __( 'Withdrawals', 'consumer-withdrawal-for-woocommerce' ),
				'ajax'     => false,
			)
		);

	}

	/**
	 * Columns.
	 *
	 * @return array
	 */
	public function get_columns() {

		return array(
			'order'      => __( 'Order', 'consumer-withdrawal-for-woocommerce' ),
			'customer'   => __( 'Customer', 'consumer-withdrawal-for-woocommerce' ),
			'email'      => __( 'Email', 'consumer-withdrawal-for-woocommerce' ),
			'requested'  => __( 'Requested', 'consumer-withdrawal-for-woocommerce' ),
			'status'     => __( 'Status', 'consumer-withdrawal-for-woocommerce' ),
			'action'     => __( 'Action', 'consumer-withdrawal-for-woocommerce' ),
		);

	}

	/**
 * Prepare table items.
 *
 * @return void
 */
public function prepare_items() {

	$this->_column_headers = array(
		$this->get_columns(),
		array(),
		$this->get_sortable_columns(),
	);

	$this->items = array();

	$per_page     = 20;
	$current_page = $this->get_pagenum();

	$orderby = isset( $_GET['orderby'] )
		? sanitize_text_field( wp_unslash( $_GET['orderby'] ) )
		: 'requested';

	$sort_order = isset( $_GET['order'] )
		? strtoupper( sanitize_text_field( wp_unslash( $_GET['order'] ) ) )
		: 'DESC';

	$search = isset( $_GET['s'] )
		? strtolower( sanitize_text_field( wp_unslash( $_GET['s'] ) ) )
		: '';

	/*
	|--------------------------------------------------------------------------
	| Load all orders
	|--------------------------------------------------------------------------
	*/

	$orders = wc_get_orders(
		array(
			'limit'  => -1,
			'status' => array_keys( wc_get_order_statuses() ),
		)
	);

	foreach ( $orders as $order ) {

		$request = $order->get_meta( '_cwfw_request' );

		if ( empty( $request ) ) {
			continue;
		}

		$customer = strtolower( $order->get_formatted_billing_full_name() );
		$email    = strtolower( $order->get_billing_email() );
		$order_id = (string) $order->get_id();

		if ( ! empty( $search ) ) {

			if (
				strpos( $customer, $search ) === false &&
				strpos( $email, $search ) === false &&
				strpos( $order_id, $search ) === false
			) {
				continue;
			}

		}

		$this->items[] = array(
			'order'     => $order,
			'customer'  => $order->get_formatted_billing_full_name(),
			'email'     => $order->get_billing_email(),
			'requested' => $request['requested_at'] ?? '',
			'status'    => strtolower( $request['status'] ?? 'pending' ),
		);

	}

	/*
	|--------------------------------------------------------------------------
	| Sort
	|--------------------------------------------------------------------------
	*/

	usort(
		$this->items,
		function ( $a, $b ) use ( $orderby, $sort_order ) {

			switch ( $orderby ) {

				case 'order':

					$value_a = $a['order']->get_id();
					$value_b = $b['order']->get_id();

					break;

				case 'customer':

					$value_a = $a['customer'];
					$value_b = $b['customer'];

					break;

				case 'status':

					$value_a = $a['status'];
					$value_b = $b['status'];

					break;

				default:

					$value_a = strtotime( $a['requested'] );
					$value_b = strtotime( $b['requested'] );

			}

			if ( $value_a == $value_b ) {
				return 0;
			}

			if ( 'ASC' === $sort_order ) {
				return ( $value_a < $value_b ) ? -1 : 1;
			}

			return ( $value_a > $value_b ) ? -1 : 1;

		}
	);

	/*
	|--------------------------------------------------------------------------
	| Pagination
	|--------------------------------------------------------------------------
	*/

	$total_items = count( $this->items );

	$this->items = array_slice(
		$this->items,
		( $current_page - 1 ) * $per_page,
		$per_page
	);

	$this->set_pagination_args(
		array(
			'total_items' => $total_items,
			'per_page'    => $per_page,
		)
	);

}

	/**
	 * Render table columns.
	 *
	 * @param array  $item        Current row.
	 * @param string $column_name Column name.
	 *
	 * @return string
	 */
	public function column_default( $item, $column_name ) {

		switch ( $column_name ) {

			case 'order':

				return sprintf(
					'<strong>#%d</strong>',
					$item['order']->get_id()
				);

			case 'customer':

				return esc_html( $item['customer'] );

			case 'email':

				return esc_html( $item['email'] );

			case 'requested':

				return esc_html( $item['requested'] );

			case 'status':

				$status = strtolower( $item['status'] );

				$colors = array(
					'pending'   => '#f0ad4e',
					'approved'  => '#5cb85c',
					'rejected'  => '#d9534f',
					'completed' => '#0275d8',
				);
				
				$labels = array(
					'pending'   => __( 'Pending', 'consumer-withdrawal-for-woocommerce' ),
					'approved'  => __( 'Approved', 'consumer-withdrawal-for-woocommerce' ),
					'rejected'  => __( 'Rejected', 'consumer-withdrawal-for-woocommerce' ),
					'completed' => __( 'Completed', 'consumer-withdrawal-for-woocommerce' ),
				);
				
				$color = $colors[ $status ] ?? '#777';
				$label = $labels[ $status ] ?? ucfirst( $status );
				
				return sprintf(
					'<span style="
						background:%1$s;
						color:#fff;
						padding:4px 10px;
						border-radius:20px;
						font-size:12px;
						font-weight:600;
					">%2$s</span>',
					esc_attr( $color ),
					esc_html( $label )
				);

			case 'action':

				return sprintf(
					'<a class="button button-small" href="%s">%s</a>',
					admin_url(
						'post.php?post=' .
						$item['order']->get_id() .
						'&action=edit'
					),
					__( 'View Order', 'consumer-withdrawal-for-woocommerce' )
				);

		}

		return '';

	}

	/**
	 * Get sortable columns.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {

		return array(
			'order'     => array( 'order', false ),
			'customer'  => array( 'customer', false ),
			'requested' => array( 'requested', true ),
			'status'    => array( 'status', false ),
		);

	}

	

}