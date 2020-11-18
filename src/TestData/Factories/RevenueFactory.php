<?php

namespace GiveTestData\TestData\Factories;

use Give\ValueObjects\Money;
use GiveTestData\TestData\Framework\Factory;

class RevenueFactory extends Factory {
	public function definition() {
		$donationForm = $this->randomDonationForm();

		return [
			'donation_id' => $this->randomDonation(),
			'form_id'     => $donationForm['id'],
			'amount'      => Money::of( $this->randomAmount(), give_get_option( 'currency' ) )->getMinorAmount(),
		];
	}
}
