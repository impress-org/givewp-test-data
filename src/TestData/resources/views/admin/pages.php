<?php defined( 'ABSPATH' ) or exit; ?>

<h3><?php esc_html_e( 'Pages', 'give-test-data' ); ?></h3>

<table class="give-test-data-table give-table">
	<tbody>

		<?php do_action( 'give-test-data-pages-start' ); ?>

		<tr>
			<td class="row-title">
				<?php esc_html_e( 'GiveWP demonstration page', 'give-test-data' ); ?>
				<p><?php esc_html_e( 'Pages that demonstrate core shortcodes/blocks', 'give-test-data' ); ?></p>
			</td>
			<td colspan="2">
				<input type="checkbox" checked/>
			</td>
		</tr>

		<?php do_action( 'give-test-data-pages-end' ); ?>

	</tbody>
</table>

<?php do_action( 'give-test-data-after-pages-table' ); ?>

<br />

<div>
	<button class="button button-primary">
		<?php esc_html_e( 'Generate Pages', 'give-test-data' ); ?>
	</button>
</div>


