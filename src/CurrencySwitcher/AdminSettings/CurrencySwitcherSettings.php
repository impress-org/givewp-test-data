<?php

namespace GiveTestData\CurrencySwitcher\AdminSettings;

use GiveTestData\Addon\View;

/**
 * Class Options
 * @package GiveTestData\CurrencySwitcher
 */
class CurrencySwitcherSettings {

	/**
	 * Render options
	 * @return void
	 * @since 1.0.0
	 */
	public function renderDonationOptions() {
		// Get active currencies
		$currencies = give_cs_get_active_currencies( null );

		if ( ! empty( $currencies ) ) {
			View::render(
				'CurrencySwitcher.donation-options',
				[
					'currencies' => $currencies,
				]
			);
		}
	}
}
