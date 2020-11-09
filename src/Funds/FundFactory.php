<?php

namespace GiveTestData\Funds;

use GiveTestData\TestData\Framework\Factory;

class FundFactory extends Factory {

	/**
	 * @return array
	 */
	public function definition() {
		return [
			'title'         => $this->faker->catchPhrase(),
			'description'   => $this->faker->sentence( 6, true ),
			'is_default'    => 0,
			'author_id'     => $this->randomAuthor(),
			'date_created'  => $this->faker->dateTimeThisYear()->format( 'Y-m-d H:i:s' ),
			'date_modified' => $this->faker->dateTimeThisYear()->format( 'Y-m-d H:i:s' ),
		];
	}
}
