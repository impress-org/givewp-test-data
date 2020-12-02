<?php defined( 'ABSPATH' ) or exit; ?>

<h3><?php esc_html_e( 'Donations Currency', 'give-test-data' ); ?></h3>

<table class="give-test-data-table give-table">
	<?php foreach ( $currencies as $currencyCode => $currencyName ) :

		$checked = ( $currencyCode === $defaultCurrency ) ? 'checked' : '';
		?>
        <tr>
            <td>
                <label>
                    <input type="radio" name="donation_currency"
                           value="<?php echo $currencyCode; ?>" <?php echo $checked; ?>/>
					<?php echo esc_html( $currencyName ); ?>
                </label>
            </td>
        </tr>
	<?php endforeach; ?>
</table>
