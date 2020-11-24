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

<br/>

<div>
    <button id="give-test-data-generate-funds" class="button button-primary">
		<?php esc_html_e( 'Generate Funds', 'give-test-data' ); ?>
    </button>
</div>
