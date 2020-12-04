<?php

namespace GiveTestData\RecurringDonations;

use GiveTestData\TestData\Framework\Factory;

/**
 * Class PageFactory
 * @package GiveTestData\RecurringDonations
 */
class PageFactory extends Factory {
	/**
	 * Donor definition
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function definition() {
		return [
			'post_title'   => 'Recurring Donations Demonstration page',
			'post_content' => '[give_subscriptions]',
			'post_status'  => 'publish',
			'post_author'  => $this->randomAuthor(),
			'post_type'    => 'page',
		];
	}
}
