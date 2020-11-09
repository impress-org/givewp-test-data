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
		Hooks::addAction( 'give-test-data-donations-end', FeeRecoverySettings::class, 'renderDonationOptions', 20 );
		Hooks::addAction( 'give-test-data-forms-end', FeeRecoverySettings::class, 'renderPagesOptions' );
	}
}
