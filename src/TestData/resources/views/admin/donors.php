<?php defined( 'ABSPATH' ) or exit; ?>

<h3><?php esc_html_e( 'Donors', 'give-test-data' ); ?></h3>

<table class="give-test-data-table give-table">
    <tbody>
    <tr>
        <td class="row-title">
			<?php esc_html_e( 'Donors count', 'give-test-data' ); ?>
            <p><?php esc_html_e( 'Set the number of Donors to generate.', 'give-test-data' ); ?></p>
        </td>
        <td>
            <input type="number" id="give-test-data-donors-count" placeholder="10" min="1" step="1" size="4" value="10"
                   required/>
        </td>
    </tr>
    </tbody>
</table>


<?php do_action( 'give-test-data-after-donors-table' ); ?>

<br/>

<div>
    <button class="button button-primary" id="give-test-data-generate-donors">
		<?php esc_html_e( 'Generate Donors', 'give-test-data' ); ?>
    </button>
</div>
