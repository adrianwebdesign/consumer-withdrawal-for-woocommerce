<?php
defined( 'ABSPATH' ) || exit;

$request = $order->get_meta( '_cwfw_request' );
?>

<h2><?php esc_html_e( 'Withdrawal Request Received', 'consumer-withdrawal-for-woocommerce' ); ?></h2>

<p>
<?php
printf(
	esc_html__( 'Hello %s,', 'consumer-withdrawal-for-woocommerce' ),
	esc_html( $order->get_billing_first_name() )
);
?>
</p>

<p>
<?php esc_html_e(
	'We have successfully received your withdrawal request.',
	'consumer-withdrawal-for-woocommerce'
); ?>
</p>

<p>
<?php
printf(
	esc_html__( 'Order: #%d', 'consumer-withdrawal-for-woocommerce' ),
	$order->get_id()
);
?>
</p>

<p>
<strong>
<?php esc_html_e(
	'Request submitted on:',
	'consumer-withdrawal-for-woocommerce'
); ?>
</strong>

<?php
echo esc_html( $request['requested_at'] );
?>
</p>

<?php if ( ! empty( $request['products'] ) ) : ?>

	<h3>
	<?php esc_html_e(
		'Products included in your withdrawal request',
		'consumer-withdrawal-for-woocommerce'
	); ?>
	</h3>

	<ul>

	<?php foreach ( $request['products'] as $item_id ) :

		$item = $order->get_item( $item_id );

		if ( ! $item ) {
			continue;
		}

	?>

		<li>

			<strong><?php echo esc_html( $item->get_name() ); ?></strong>

			<?php
			if ( $item->get_quantity() > 1 ) {

				printf(
					esc_html__( ' × %d', 'consumer-withdrawal-for-woocommerce' ),
					$item->get_quantity()
				);

			}
			?>

		</li>

	<?php endforeach; ?>

	</ul>

<?php endif; ?>

<p>
<?php esc_html_e(
	'Our team will review your request as soon as possible.',
	'consumer-withdrawal-for-woocommerce'
); ?>
</p>

<p>
<?php esc_html_e(
	'This email confirms that we have received your withdrawal request. Please keep it for your records.',
	'consumer-withdrawal-for-woocommerce'
); ?>
</p>

<p>
<?php esc_html_e(
	'Thank you for shopping with us.',
	'consumer-withdrawal-for-woocommerce'
); ?>
</p>