<?php

namespace GiveTestData\Funds;

use WP_CLI;
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
		// Add CLI commands
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			WP_CLI::add_command( 'give test-funds', give()->make( FundCommand::class ) );
		}

		/**
		 * Inject Fund ID into revenue data
		 */
		add_filter(
			'give-test-data-revenue-definition',
			function ( $args ) {
				$args['fund_id'] = give( FundFactory::class )->getRandomFund();

				return $args;
			}
		);

		// Add settings section
		SettingsPage::addPageSection( 'give-test-data', 'funds', esc_html__( 'Generate Funds', 'give-test-data' ) );
		SettingsPage::addPageSectionContent( 'give-test-data', 'funds', FundsSettings::class );
	}
}
