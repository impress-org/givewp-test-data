<?php

namespace GiveTestData\TestData\AdminSettings;

use GiveTestData\Addon\View;

/**
 * Class Pages
 * @package GiveTestData\TestData
 */
class Pages implements SettingsPage {

	/**
	 * Render options
	 * @return string
	 * @since 1.0.0
	 */
	public function renderPage() {
		return View::load( 'admin/pages' );
	}
}
