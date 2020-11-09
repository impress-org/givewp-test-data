<?php

namespace GiveTestData\Addon;

/**
 * Helper class responsible for checking the add-on environment.
 *
 * @package     GiveTestData\Addon\Helpers
 * @copyright   Copyright (c) 2020, GiveWP
 */
class Environment {

	/**
	 * Check environment.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public static function checkEnvironment() {
		// Check is GiveWP active
		if ( ! static::isGiveActive() ) {
			add_action( 'admin_notices', [ Notices::class, 'giveInactive' ] );

			return;
		}
		// Check min required version
		if ( ! static::giveMinRequiredVersionCheck() ) {
			add_action( 'admin_notices', [ Notices::class, 'giveVersionError' ] );
		}
	}

	/**
	 * Check min required version of GiveWP.
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	public static function giveMinRequiredVersionCheck() {
		return defined( 'GIVE_VERSION' ) && version_compare( GIVE_VERSION, GIVE_TEST_DATA_MIN_GIVE_VERSION, '>=' );
	}

	/**
	 * Check if GiveWP is active.
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	public static function isGiveActive() {
		return defined( 'GIVE_VERSION' );
	}
}
