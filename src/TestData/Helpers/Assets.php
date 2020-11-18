<?php

namespace GiveTestData\TestData\Helpers;

/**
 * Helper class responsible for loading add-on assets.
 *
 * @package     GiveTestData\Addon
 * @copyright   Copyright (c) 2020, GiveWP
 */
class Assets {

	/**
	 * Load add-on backend assets.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public static function load() {
		wp_enqueue_style(
			'give-test-data-style-backend',
			GIVE_TEST_DATA_URL . 'public/css/give-test-data-admin.css',
			[],
			GIVE_TEST_DATA_VERSION
		);

		wp_enqueue_script(
			'give-test-data-script-backend',
			GIVE_TEST_DATA_URL . 'public/js/give-test-data-admin.js',
			[],
			GIVE_TEST_DATA_VERSION,
			true
		);
	}
}
