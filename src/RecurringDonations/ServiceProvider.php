<?php

namespace GiveTestData\RecurringDonations;

use WP_CLI;
use Give\Helpers\Hooks;
use Give\ServiceProviders\ServiceProvider as GiveServiceProvider;
use GiveTestData\RecurringDonations\AdminSettings\RecurringDonationsSettings;

/**
 * Class ServiceProvider
 * @package GiveTestData\RecurringDonations
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
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			$this->addCommands();
		}

		// Actions
		Hooks::addAction( 'give-test-data-pages-end', RecurringDonationsSettings::class, 'renderPagesOptions' );
		// Update donation meta on donation insert
		Hooks::addAction( 'give-test-data-insert-donation', RecurringDonations::class, 'insertRecurringDonation', 10, 2 );
	}

	/**
	 * Register CLI commands
	 */
	private function addCommands() {
		WP_CLI::add_command( 'give recurring-donations', give()->make( RecurringDonationsCommand::class ) );
	}
}
