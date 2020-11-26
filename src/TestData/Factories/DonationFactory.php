<?php

namespace GiveTestData\TestData\Factories;

use GiveTestData\TestData\Framework\Factory;

/**
 * Class Donation
 * @package GiveTestData\TestData\Factories
 */
class DonationFactory extends Factory {
	/**
	 * @var string
	 */
	private $status;
	/**
	 * @var int
	 */
	private $amount;

	/**
	 * @var string
	 */
	private $currency;

	/**
	 * @var string
	 */
	private $startDate;

	/**
	 * @param string $status
	 */
	public function setDonationStatus( $status ) {
		$this->status = $status;
	}

	/**
	 * Check is valid donation status
	 *
	 * @param string $status
	 *
	 * @return bool
	 */
	public function checkDonationStatus( $status ) {
		if ( 'random' === $status ) {
			return true;
		}

		return in_array( $status, array_keys( give_get_payment_statuses() ) );
	}

	/**
	 * @return string
	 */
	public function getDonationStatus() {
		if ( is_null( $this->status ) ) {
			return 'publish';
		}

		if ( 'random' === $this->status ) {
			return $this->randomDonationStatus();
		}

		return $this->status;
	}

	/**
	 * @param int $amount
	 */
	public function setDonationAmount( $amount ) {
		$this->amount = absint( $amount );
	}

	/**
	 * @return int
	 */
	public function getDonationAmount() {
		if ( is_null( $this->amount ) || ! $this->amount ) {
			return $this->randomAmount();
		}

		return $this->amount;
	}


	/**
	 * @param string $currency
	 */
	public function setDonationCurrency( $currency ) {
		$this->currency = $currency;
	}

	/**
	 * @return int
	 */
	public function getDonationCurrency() {
		if ( is_null( $this->currency ) || ! $this->currency ) {
			return give_get_option( 'currency' );
		}

		return $this->currency;
	}


	/**
	 * @param string $date
	 */
	public function setDonationStartDate( $date ) {
		$this->startDate = $date;
	}

	/**
	 * @return string
	 */
	public function getDonationDate() {
		if ( ! $this->isValidDate( $this->startDate ) ) {
			return $this->faker->dateTimeThisYear()->format( 'Y-m-d H:i:s' );
		}

		return $this->faker->dateTimeBetween( $this->startDate, $endDate = 'now' )->format( 'Y-m-d H:i:s' );
	}

	/**
	 * @param string $date
	 *
	 * @return bool
	 */
	public function isValidDate( $date ) {
		if ( empty( $date ) || false === strpos( $date, '-' ) ) {
			return false;
		}

		list ( $year, $month, $day ) = explode( '-', $date );

		return checkdate( $month, $day, $year );
	}

	/**
	 * Donation definition
	 *
	 * @return array
	 */
	public function definition() {
		$donationForm = $this->randomDonationForm();

		return [
			'donor_id'             => $this->randomDonor(),
			'payment_form_id'      => $donationForm['id'],
			'payment_form_title'   => $donationForm['post_title'],
			'payment_total'        => $this->getDonationAmount(),
			'payment_currency'     => $this->getDonationCurrency(),
			'payment_gateway'      => $this->randomGateway(),
			'payment_mode'         => $this->randomPaymentMode(),
			'payment_status'       => $this->getDonationStatus(),
			'payment_purchase_key' => $this->faker->md5(),
			'completed_date'       => $this->getDonationDate(),
		];
	}
}
