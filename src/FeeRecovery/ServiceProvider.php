<?php

namespace GiveTestData\FeeRecovery;

use Give\Helpers\Hooks;
use Give\ServiceProviders\ServiceProvider as GiveServiceProvider;
use GiveTestData\FeeRecovery\AdminSettings\FeeRecoverySettings;

/**
 * Class ServiceProvider
 * @package GiveTestData\FeeRecovery
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
		// Update donation meta on donation insert
		Hooks::addAction( 'give-test-data-insert-donation', FeeRecovery::class, 'addFee', 10, 3 );
		Hooks::addAction( 'give-test-data-after-donations-table', FeeRecoverySettings::class, 'renderDonationOptions', 20 );
	}
}
