<?php

namespace GiveTestData\Funds;

use GiveTestData\TestData\Framework\Provider\RandomProvider;

/**
 * Class RandomFundProvider
 * @package GiveTestData\Funds
 *
 * Returns a random Fund ID from the give_funds table.
 */
class RandomFundProvider extends RandomProvider {

	public function __invoke() {
		global $wpdb;
		$fundIds = $wpdb->get_col( "SELECT id FROM {$wpdb->prefix}give_funds" );

		return $this->faker->randomElement( $fundIds );
	}
}
