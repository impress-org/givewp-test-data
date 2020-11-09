<?php

namespace GiveTestData\Funds\AdminSettings;

use GiveTestData\Addon\View;
use GiveTestData\TestData\AdminSettings\SettingsPage;

/**
 * Class FundsSettings
 * @package GiveTestData\Funds\AdminSettings
 */
class FundsSettings implements SettingsPage {

	/**
	 * Render donations options
	 * @return void
	 * @since 1.0.0
	 */
	public function renderPage() {
		View::render( 'Funds.funds-table' );
	}
}
