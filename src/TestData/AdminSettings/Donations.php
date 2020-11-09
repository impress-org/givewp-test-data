<?php

namespace GiveTestData\TestData\AdminSettings;

use GiveTestData\Addon\View;

/**
 * Class Donations
 * @package GiveTestData\TestData
 */
class Donations implements SettingsPage {

	/**
	 * Render options
	 * @return string
	 * @since 1.0.0
	 */
	public function renderPage() {
		return View::load(
			'admin/donations',
			[
				'statuses' => give_get_payment_statuses(),
			]
		);
	}
}
