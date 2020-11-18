<?php

namespace GiveTestData\TestData\AdminSettings;

interface SettingsPage {
	/**
	 * Render settings page
	 * @return string
	 */
	public function renderPage();
}
