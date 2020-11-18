<?php

namespace GiveTestData\FeeRecovery\AdminSettings;

use GiveTestData\Addon\View;

/**
 * Class Options
 * @package GiveTestData\FeeRecovery
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

	/**
	 * Render page options
	 * @return void
	 * @since 1.0.0
	 */
	public function renderPagesOptions() {
		View::render( 'FeeRecovery.pages-options' );
	}
}
