<?php

namespace GiveTestData\RecurringDonations\AdminSettings;

use GiveTestData\Addon\View;

/**
 * Class Donations
 * @package GiveTestData\RecurringDonations
 */
class RecurringDonationsSettings {

	/**
	 * Render donations options
	 * @return void
	 * @since 1.0.0
	 */
	public function renderDonationOptions() {
		// Get active currencies
		$statuses = give_recurring_get_subscription_statuses();

		if ( ! empty( $statuses ) ) {
			View::render(
				'RecurringDonations.donation-options',
				[
					'statuses' => $statuses,
				]
			);
		}
	}

	/**
	 * Render page options
	 * @return void
	 * @since 1.0.0
	 */
	public function renderPagesOptions() {
		View::render( 'RecurringDonations.pages-options' );
	}
}
