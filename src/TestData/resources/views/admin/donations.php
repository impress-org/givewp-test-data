<?php defined( 'ABSPATH' ) or exit; ?>

<h3><?php esc_html_e( 'Donations', 'give-test-data' ); ?></h3>
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
                            name="give_test_data_status[<?php echo $key; ?>]"
                            data-status="<?php echo $key; ?>"
                            data-name="<?php echo esc_html( $status ); ?>"
                    />
					<?php echo esc_html( $status ); ?>
                </label>
            </td>
            <td>
                <input type="number" min="1" size="7" name="give_test_data_count[<?php echo $key; ?>]" disabled/>
            </td>
            <td>
                <input type="number" min="1" size="7" name="give_test_data_revenue[<?php echo $key; ?>]" disabled/>
            </td>
        </tr>
	<?php endforeach; ?>
    </tbody>
</table>

<h3><?php esc_html_e( 'Set date', 'give-test-data' ); ?></h3>

<table class="give-test-data-table give-table">
    <tr>
        <td>
			<?php esc_html_e( 'Set the earliest donation date', 'give-test-data' ); ?>
        </td>
        <td>
            <input id="give-test-data-start-date" name="give_test_data_start_date" type="date"/>
        </td>
    </tr>
</table>


<?php do_action( 'give-test-data-after-donations-table' ); ?>

<h3><?php esc_html_e( 'Consistent data', 'give-test-data' ); ?></h3>

<table class="give-test-data-table give-table">
    <tr>
        <td>
			<?php esc_html_e( 'Generate the same data each time', 'give-test-data' ); ?>
        </td>
        <td>
            <input name="donations_consitent_data" class="give-test-data-info-expand" value="1" type="checkbox"/>
        </td>
    </tr>
    <tr>
        <td colspan="2" class="give-hidden give-test-data-info-container">

            <br/>

            <em>
                <span class="dashicons dashicons-editor-help"></span>
				<?php esc_html_e( 'If you have to use consistent data option from Admin UI, there is a couple of drawbacks you should be aware of',
					'give-test-data' ); ?>
            </em>

            <br/><br/>

            <div>
				<?php esc_html_e( '50 donations are generated in batch for each donation status and they are generated in 1 request.',
					'give-test-data' ); ?>
            </div>

            <div>
				<?php esc_html_e( 'Each new request is another cycle which means that random data will be generated from the beginning.',
					'give-test-data' ); ?>
            </div>

            <br/>

            <div>
				<?php esc_html_e( 'For example: if you set donations count to 60, they will be generated in 2 requests or 2 cycles.', 'give-test-data' ); ?>
            </div>

            <div>
				<?php esc_html_e( 'Each cycle is starting to generate random data from the beginning which means that the first 50 donations will be random/unique, but starting from donation 51 to donation 60, the generated data will be the same as for donation 1 to donation 10.',
					'give-test-data' ); ?>
            </div>

            <br/>
            <strong>
				<?php esc_html_e( 'A better way to generate consistent data is by using WP CLI.', 'give-test-data' ); ?>
            </strong>
        </td>
    </tr>
</table>

<br/>

<div>
    <button id="give-test-data-generate-donations" class="button button-primary">
		<?php esc_html_e( 'Generate Donations', 'give-test-data' ); ?>
    </button>
</div>


