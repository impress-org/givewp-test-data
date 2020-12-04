<?php

namespace GiveTestData\FeeRecovery\AdminSettings;

use GiveTestData\Addon\View;

/**
 * Class FeeRecoverySettings
 * @package GiveTestData\FeeRecovery\AdminSettings
 */
class FeeRecoverySettings {

	/**
	 * Render donations options
	 * @return void
	 * @since 1.0.0
	 */
	public function renderDonationOptions() {
		View::render( 'FeeRecovery.donation-options' );
	}
}
