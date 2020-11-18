<?php

namespace GiveTestData\TestData\AdminSettings;

use GiveTestData\Addon\View;

/**
 * Class Forms
 * @package GiveTestData\TestData
 */
class Forms implements SettingsPage {

	/**
	 * Render options
	 * @return void
	 * @since 1.0.0
	 */
	public function renderPage() {
		return View::load( 'admin/forms' );
	}
}
