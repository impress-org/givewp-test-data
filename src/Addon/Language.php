<?php

namespace GiveTestData\Addon;

/**
 * Helper class responsible for loading add-on translations.
 *
 * @package     GiveTestData\Addon\Helpers
 * @copyright   Copyright (c) 2020, GiveWP
 */
class Language {
	/**
	 * Load language.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function load() {

		// Set filter for plugin's languages directory.
		$langDir = apply_filters(
			sprintf( '%s_languages_directory', 'give-test-data' ), // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores, WordPress.NamingConventions.ValidHookName.NotLowercase
			dirname( GIVE_TEST_DATA_BASENAME ) . '/languages/'
		);

		// Traditional WordPress plugin locale filter.
		$locale = apply_filters( 'plugin_locale', get_locale(), 'give-test-data' );
		$moFile = sprintf( '%1$s-%2$s.mo', 'give-test-data', $locale );

		// Setup paths to current locale file.
		$moFileLocal  = $langDir . $moFile;
		$moFileGlobal = WP_LANG_DIR . 'give-test-data' . $moFile;

		if ( file_exists( $moFileGlobal ) ) {
			// Look in global /wp-content/languages/TEXTDOMAIN folder.
			load_textdomain( 'give-test-data', $moFileGlobal );
		} elseif ( file_exists( $moFileLocal ) ) {
			// Look in local /wp-content/plugins/TEXTDOMAIN/languages/ folder.
			load_textdomain( 'give-test-data', $moFileLocal );
		} else {
			// Load the default language files.
			load_plugin_textdomain( 'give-test-data', false, $langDir );
		}
	}
}
