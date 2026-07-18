<?php

defined( 'ABSPATH' ) || exit;

?>

<p class="cwfw-withdrawal">

	<a href="#"
	   class="woocommerce-button button cwfw-withdraw-button"
	   data-order-id="<?php echo esc_attr( $order->get_id() ); ?>">

		<?php esc_html_e(
			'Withdraw from Contract',
			'consumer-withdrawal-for-woocommerce'
		); ?>

	</a>

</p>

<div class="cwfw-message"></div>

<div class="cwfw-modal-overlay" style="display:none;">

	<div class="cwfw-modal">

		<h3>
			<?php esc_html_e(
				'Withdrawal Request',
				'consumer-withdrawal-for-woocommerce'
			); ?>
		</h3>


		<div class="cwfw-products">

	<p class="cwfw-products-description">

		<?php esc_html_e(
			'Select the products you wish to withdraw from this order.',
			'consumer-withdrawal-for-woocommerce'
		); ?>

	</p>

	<p class="cwfw-products-note">

		<?php esc_html_e(
			'All products are selected by default. Uncheck any products you wish to keep.',
			'consumer-withdrawal-for-woocommerce'
		); ?>

	</p>

	<?php foreach ( $order->get_items() as $item_id => $item ) : ?>

		<label class="cwfw-product">

			<input
				type="checkbox"
				class="cwfw-product-checkbox"
				value="<?php echo esc_attr( $item_id ); ?>"
				checked
			>

			<span>

				<?php echo esc_html( $item->get_name() ); ?>

				<?php if ( $item->get_quantity() > 1 ) : ?>

					<small>

					<?php
					printf(
						esc_html__( ' × %d', 'consumer-withdrawal-for-woocommerce' ),
						$item->get_quantity()
					);
					?>

					</small>

				<?php endif; ?>

			</span>

		</label>

	<?php endforeach; ?>

</div>

		<div class="cwfw-modal-actions">

			<button
				type="button"
				class="button cwfw-cancel">

				<?php esc_html_e(
					'Cancel',
					'consumer-withdrawal-for-woocommerce'
				); ?>

			</button>

			<button
				type="button"
				class="button alt cwfw-submit">

				<?php esc_html_e(
					'Submit Withdrawal Request',
					'consumer-withdrawal-for-woocommerce'
				); ?>

			</button>

		</div>

	</div>

</div>