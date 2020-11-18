<?php defined( 'ABSPATH' ) or exit; ?>

<h3><?php esc_html_e( 'Recurring Donations', 'give-test-data' ); ?></h3>

<table class="give-test-data-table give-table">
	<thead>
		<tr>
			<th>
				<strong>
					<?php esc_html_e( 'Status', 'give-test-data' ); ?>
				</strong>
			</th>
			<th>
				<strong>
					<?php esc_html_e( 'Count', 'give-test-data' ); ?>
				</strong>
			</th>
			<th>
				<strong>
					<?php esc_html_e( 'Revenue', 'give-test-data' ); ?>
				</strong>
			</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ( $statuses as $key => $status ) : ?>
			<tr>
				<td>
					<label>
						<input
							type="checkbox"
							name="give_test_data_status[recurring-<?php echo $key; ?>]"
							data-status="recurring-<?php echo $key; ?>"
						/>
						<?php echo esc_html( $status ); ?>
					</label>
				</td>
				<td>
					<input
						type="number"
						min="1"
						size="7"
						name="give_test_data_count[recurring-<?php echo $key; ?>]"
						disabled
					/>
				</td>
				<td>
					<input
						type="number"
						min="1"
						size="7"
						name="give_test_data_revenue[recurring-<?php echo $key; ?>]"
						disabled
					/>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
