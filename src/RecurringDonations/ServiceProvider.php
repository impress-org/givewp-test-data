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
		// Actions
		Hooks::addAction( 'give-test-data-pages-end', RecurringDonationsSettings::class, 'renderPagesOptions' );
	}
}
