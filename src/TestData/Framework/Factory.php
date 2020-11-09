<?php

namespace GiveTestData\TestData\Framework;

use Faker\Generator;

abstract class Factory implements FactoryContract {
	use ProviderForwarder;

	/** @var Generator */
	protected $faker;

	public function __construct( Generator $faker ) {
		$this->faker = $faker;
	}

	/**
	 * @param int $count
	 *
	 * @return \Generator
	 */
	public function make( $count ) {
		for ( $i = 0; $i < $count; $i ++ ) {
			yield $this->definition();
		}
	}

	abstract public function definition();
}
