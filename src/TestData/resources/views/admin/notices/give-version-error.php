<?php defined( 'ABSPATH' ) or exit; ?>

<strong>
	<?php _e( 'Activation Error:', 'give-test-data' ); ?>
</strong>
<?php _e( 'You must have', 'give-test-data' ); ?> <a href="https://givewp.com" target="_blank">GiveWP</a>
<?php _e( 'version', 'give-test-data' ); ?> <?php echo GIVE_VERSION; ?>+
<?php printf( esc_html__( 'for the %1$s add-on to activate', 'give-test-data' ), GIVE_TEST_DATA_NAME ); ?>.

