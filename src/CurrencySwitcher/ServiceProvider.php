<?php

namespace GiveTestData\CurrencySwitcher;

use Give\Helpers\Hooks;
use Give\ServiceProviders\ServiceProvider as GiveServiceProvider;
use GiveTestData\CurrencySwitcher\AdminSettings\CurrencySwitcherSettings;

/**
 * Class ServiceProvider
 * @package GiveTestData\CurrencySwitcher
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
		Hooks::addAction( 'give-test-data-after-donations-table', CurrencySwitcherSettings::class, 'renderDonationOptions', 20 );
		// Add donation currency meta
		Hooks::addAction( 'give-test-data-insert-donation', CurrencySwitcher::class, 'addDonationCurrencyMeta', 10, 2 );
		// Set donation currency
		Hooks::addFilter( 'give-test-data-donation-definition', CurrencySwitcher::class, 'setDonationCurrency', 10, 2 );
	}
}
