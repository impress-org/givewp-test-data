<?php

namespace GiveTestData\TestData\Framework\Provider;

class RandomPaymentMode extends RandomProvider {

	public function __invoke() {
		return $this->faker->boolean( 80 ) ? 'live' : 'test';
	}
}
