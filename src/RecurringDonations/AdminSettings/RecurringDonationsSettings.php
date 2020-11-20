<?php

namespace GiveTestData\RecurringDonations\AdminSettings;

use GiveTestData\Addon\View;

/**
 * Class Donations
 * @package GiveTestData\RecurringDonations
 */
class RecurringDonationsSettings {
	/**
	 * Render page options
	 * @return void
	 * @since 1.0.0
	 */
	public function renderPagesOptions() {
		View::render( 'RecurringDonations.pages-options' );
	}
}
