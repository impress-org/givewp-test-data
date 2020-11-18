<?php

namespace GiveTestData\TestData\AdminSettings;

use GiveTestData\Addon\View;

/**
 * Class Donors
 * @package GiveTestData\TestData
 */
class Donors implements SettingsPage {

	/**
	 * Render options
	 * @return string
	 * @since 1.0.0
	 */
	public function renderPage() {
		return View::load( 'admin/donors' );
	}
}
