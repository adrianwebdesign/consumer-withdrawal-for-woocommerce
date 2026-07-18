<?php
defined( 'ABSPATH' ) || exit;

$request = $order->get_meta( '_cwfw_request' );
?>

<h2><?php esc_html_e( 'New Withdrawal Request', 'consumer-withdrawal-for-woocommerce' ); ?></h2>

<p>

<?php
printf(
	esc_html__( 'Order: #%d', 'consumer-withdrawal-for-woocommerce' ),
	$order->get_id()
);
?>

</p>

<p>

<strong><?php esc_html_e( 'Customer:', 'consumer-withdrawal-for-woocommerce' ); ?></strong>

<?php echo esc_html( $order->get_formatted_billing_full_name() ); ?>

</p>

<p>

<strong><?php esc_html_e( 'Email:', 'consumer-withdrawal-for-woocommerce' ); ?></strong>

<?php echo esc_html( $order->get_billing_email() ); ?>

</p>

<p>

<strong><?php esc_html_e(
	'Request submitted on:',
	'consumer-withdrawal-for-woocommerce'
); ?></strong>

<?php
echo esc_html( $request['requested_at'] );
?>

</p>

<?php if ( ! empty( $request['products'] ) ) : ?>

	<h3><?php esc_html_e( 'Products requested for withdrawal', 'consumer-withdrawal-for-woocommerce' ); ?></h3>

	<ul>

	<?php foreach ( $request['products'] as $item_id ) :

		$item = $order->get_item( $item_id );

		if ( ! $item ) {
			continue;
		}

	?>

		<li>

			<strong><?php echo esc_html( $item->get_name() ); ?></strong>

			<?php if ( $item->get_quantity() > 1 ) : ?>

				<?php
				if ( $item->get_quantity() > 1 ) {

					printf(
						esc_html__( ' × %d', 'consumer-withdrawal-for-woocommerce' ),
						$item->get_quantity()
					);

				}
				?>

			<?php endif; ?>

		</li>

	<?php endforeach; ?>

	</ul>

<?php endif; ?>

<p>

<a href="<?php echo esc_url( admin_url( 'post.php?post=' . $order->get_id() . '&action=edit' ) ); ?>">

<?php esc_html_e( 'Open Order', 'consumer-withdrawal-for-woocommerce' ); ?>

</a>

</p>