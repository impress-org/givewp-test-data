<?php

namespace GiveTestData\ManualDonations;

use Throwable;
use GiveTestData\TestData\Framework\MetaRepository;

class ManualDonations {

	private const GATEWAY = 'manual_donation';

	/**
	 * @param  int  $donationID
	 * @param  array  $donation
	 */
	public function updateDonationMeta( $donationID, $donation ) {

		global $wpdb;

		// Check gateway
		if ( $donation[ 'payment_gateway' ] !== self::GATEWAY ) {
			return;
		}

		// Start DB transaction
		$wpdb->query( 'START TRANSACTION' );

		try {
			// Update donation meta
			$metaRepository = new MetaRepository( 'give_donationmeta', 'donation_id' );
			$metaRepository->persist( $donationID, [ '_give_manually_added_donation' => 1 ] );

			$wpdb->query( 'COMMIT' );

		} catch ( Throwable $e ) {
			$wpdb->query( 'ROLLBACK' );
		}
	}

}
