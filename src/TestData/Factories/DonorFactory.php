<?php

namespace GiveTestData\TestData\Factories;

use GiveTestData\TestData\Framework\Factory;

/**
 * Class DonorFactory
 * @package GiveTestData\TestData\Factories
 */
class DonorFactory extends Factory {

	/**
	 * Donor definition
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function definition() {
		return [
			'first_name'   => $this->faker->firstName(),
			'last_name'    => $this->faker->lastName(),
			'email'        => $this->faker->safeEmail(),
			'date_created' => $this->faker->dateTimeThisYear()->format( 'Y-m-d H:i:s' ),
		];
	}
}
