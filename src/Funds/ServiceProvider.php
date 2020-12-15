<?php

namespace GiveTestData\Funds;

use WP_CLI;
use Give\Helpers\Hooks;
use GiveTestData\TestData\Helpers\SettingsPage;
use GiveTestData\Funds\AdminSettings\FundsSettings;
use Give\ServiceProviders\ServiceProvider as GiveServiceProvider;

/**
 * Class ServiceProvider
 * @package GiveTestData\Funds
 */
class ServiceProvider implements GiveServiceProvider {
	/**
	 * @inheritDoc
	 */
	public function register() {
	}

	/**
	 * @inheritDoc
	 */
	public function boot() {
		/**
		 * Load assets
		 */
		add_action( 'admin_enqueue_scripts', function () {
			wp_enqueue_script(
				'give-test-data-script-funds',
				GIVE_TEST_DATA_URL . 'public/js/funds.js',
				[ 'give-test-data-script-backend' ],
				GIVE_TEST_DATA_VERSION,
				true
			);
		} );

		// Register REST route
		Hooks::addAction( 'rest_api_init', FundsRoute::class, 'registerRoute' );

		// Add settings section
		SettingsPage::addPageSection( 'give-test-data', 'funds', esc_html__( 'Generate Funds', 'give-test-data' ) );
		SettingsPage::addPageSectionContent( 'give-test-data', 'funds', FundsSettings::class );
	}
}
