<?php defined( 'ABSPATH' ) or exit; ?>

<h3><?php esc_html_e( 'Donations Currencies', 'give-test-data' ); ?></h3>

<table class="give-test-data-table give-table">
	<?php foreach ( $currencies as $currencyCode => $currencyName ) : ?>
	<tr>
		<td>
			<label>
				<input type="checkbox" name="give-test-data-currency[<?php echo $currencyCode; ?>]" checked />
				<?php echo esc_html( $currencyName ); ?>
			</label>
		</td>
	</tr>
	<?php endforeach; ?>
</table>
