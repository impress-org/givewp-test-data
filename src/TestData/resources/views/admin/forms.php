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

<br/>

<div>
    <button class="button button-primary" id="give-test-data-generate-forms">
		<?php esc_html_e( 'Generate Donation Forms', 'give-test-data' ); ?>
    </button>
</div>
