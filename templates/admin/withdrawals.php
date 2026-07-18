<?php

defined( 'ABSPATH' ) || exit;

$table = new CWFW_Withdrawals_List_Table();

$table->prepare_items();

?>

<div class="wrap">

	<h1>

		<?php esc_html_e(
			'Consumer Withdrawals',
			'consumer-withdrawal-for-woocommerce'
		); ?>

	</h1>

	<form method="get">

		<input type="hidden" name="page" value="cwfw-withdrawals">

		<?php

		$table->search_box(
			esc_html__( 'Search', 'consumer-withdrawal-for-woocommerce' ),
			'cwfw-search'
		);

		$table->display();

		?>

	</form>

</div>