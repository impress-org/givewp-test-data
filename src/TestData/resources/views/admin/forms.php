<?php defined( 'ABSPATH' ) or exit; ?>

<h3><?php esc_html_e( 'Donation Forms', 'give-test-data' ); ?></h3>

<table class="give-test-data-table give-table">
    <tbody>
	<?php do_action( 'give-test-data-forms-start' ); ?>

    <tr>
        <td class="row-title">
			<?php esc_html_e( 'Donation Forms count', 'give-test-data' ); ?>
            <p><?php esc_html_e( 'Set the number of Donation Forms to generate', 'give-test-data' ); ?></p>
        </td>
        <td>
            <input type="number" id="give-test-data-forms-count" value="10" min="1" step="1" size="4" required/>
        </td>
    </tr>

    <tr>
        <td class="row-title">
			<?php esc_html_e( 'Form templates', 'give-test-data' ); ?>
            <p><?php esc_html_e( 'Set which form templates to use.', 'give-test-data' ); ?></p>
        </td>
        <td>
            <label>
                <input type="checkbox" name="give_test_data_form_template[]" value="sequoia" checked/>
				<?php esc_html_e( 'Multi-step form', 'give-test-data' ); ?>
            </label>

            <br/>

            <label>
                <input type="checkbox" name="give_test_data_form_template[]" value="legacy" checked/>
				<?php esc_html_e( 'Legacy form', 'give-test-data' ); ?>
            </label>
        </td>
    </tr>

    <tr>
        <td class="row-title">
			<?php esc_html_e( 'Set Donation goal', 'give-test-data' ); ?>
            <p> &nbsp; </p>
        </td>
        <td>
            <input type="checkbox" id="give-test-data-forms-set-goal"/>
        </td>
    </tr>

    <tr>
        <td class="row-title">
			<?php esc_html_e( 'Generate Form T&C', 'give-test-data' ); ?>
            <p> &nbsp; </p>
        </td>
        <td>
            <input type="checkbox" id="give-test-data-forms-set-tc"/>
        </td>
    </tr>

	<?php do_action( 'give-test-data-forms-end' ); ?>

    </tbody>
</table>


<?php do_action( 'give-test-data-after-pages-table' ); ?>

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
				<?php esc_html_e( 'If you have to use consistent data option from Admin UI, there is a couple of drawbacks you should be aware of.',
					'give-test-data' ); ?>
            </em>

            <br/><br/>

            <div>
				<?php esc_html_e( '50 donation forms are generated in batch and they are generated in 1 request.', 'give-test-data' ); ?>
            </div>

            <div>
				<?php esc_html_e( 'Each new request is another cycle which means that random data will be generated from the beginning.',
					'give-test-data' ); ?>
            </div>

            <br/>

            <div>
				<?php esc_html_e( 'For example: if you set donation form count to 60, they will be generated in 2 requests or 2 cycles.', 'give-test-data' ); ?>
            </div>

            <div>
				<?php esc_html_e( 'Each cycle is starting to generate random data from the beginning which means that the first 50 donation forms will be random/unique, but starting from donation form 51 to donation form 60, the generated data will be the same as for donation form 1 to donation form 10.',
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
    <button class="button button-primary" id="give-test-data-generate-forms">
		<?php esc_html_e( 'Generate Donation Forms', 'give-test-data' ); ?>
    </button>
</div>
