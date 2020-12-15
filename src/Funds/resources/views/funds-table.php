<?php defined( 'ABSPATH' ) or exit; ?>

<h3><?php esc_html_e( 'Funds', 'give-test-data' ); ?></h3>

<table class="give-test-data-table give-table">
    <tbody>
    <tr>
        <td class="row-title">
			<?php esc_html_e( 'Funds count', 'give-test-data' ); ?>
            <p><?php esc_html_e( 'Set the number of Funds to generate', 'give-test-data' ); ?></p>
        </td>
        <td>
            <input id="give-test-data-funds-count" type="number" placeholder="5" min="1" step="1" size="4" value="5"
                   required/>
        </td>
    </tr>
    </tbody>
</table>


<?php do_action( 'give-test-data-after-funds-table' ); ?>

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
				<?php esc_html_e( '50 funds are generated in batch and they are generated in 1 request.',
					'give-test-data' ); ?>
            </div>

            <div>
				<?php esc_html_e( 'Each new request is another cycle which means that random data will be generated from the beginning.',
					'give-test-data' ); ?>
            </div>

            <br/>

            <div>
				<?php esc_html_e( 'For example: if you set funds count to 60, they will be generated in 2 requests or 2 cycles.', 'give-test-data' ); ?>
            </div>

            <div>
				<?php esc_html_e( 'Each cycle is starting to generate random data from the beginning which means that the first 50 funds will be random/unique, but starting from fund 51 to fund 60, the generated data will be the same as for fund 1 to fund 10.',
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
    <button id="give-test-data-generate-funds" class="button button-primary">
		<?php esc_html_e( 'Generate Funds', 'give-test-data' ); ?>
    </button>
</div>
