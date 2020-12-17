<?php

namespace GiveTestData\TestData\Helpers;

use GiveTestData\Funds\ServiceProvider as Funds;
use GiveTestData\CurrencySwitcher\ServiceProvider as CurrencySwitcher;
use GiveTestData\FeeRecovery\ServiceProvider as FeeRecovery;
use GiveTestData\RecurringDonations\ServiceProvider as RecurringDonations;

/**
 * Class Addons
 * @package GiveTestData\TestData\Helpers
 */
class Addons {
	/**
	 * Get add-ons
	 *
	 * @return array[]
	 * @since 1.0.0
	 */
	public static function getAddons() {
		return [
			[
				'isActive'        => defined( 'GIVE_FUNDS_VERSION' ),
				'serviceProvider' => Funds::class,
			],
			[
				'isActive'        => defined( 'GIVE_CURRENCY_SWITCHER_VERSION' ),
				'serviceProvider' => CurrencySwitcher::class,
			],
			[
				'isActive'        => defined( 'GIVE_RECURRING_VERSION' ),
				'serviceProvider' => RecurringDonations::class,
			],
			[
				'isActive'        => defined( 'GIVE_FEE_RECOVERY_VERSION' ),
				'serviceProvider' => FeeRecovery::class,
			],
			[
				'isActive'        => defined( 'GIVE_FUNDS_ADDON_VERSION' ),
				'serviceProvider' => Funds::class,
			],
		];
	}

	/**
	 * Get active add-ons
	 * @return array[]
	 * @since 1.0.0
	 */
	public static function getActiveAddons() {
		return array_filter(
			self::getAddons(),
			function ( $addon ) {
				return $addon[ 'isActive' ];
			}
		);
	}
}
