<?php

namespace GiveTestData\TestData\Commands;

use WP_CLI;
use Throwable;
use GiveTestData\TestData\Factories\DonationFactory as DonationFactory;
use GiveTestData\TestData\Repositories\DonationRepository as DonationRepository;

/**
 * Class DonationSeedCommand
 * @package GiveTestData\TestData\Commands
 *
 * A WP-CLI command for seeding donations.
 */
class DonationSeedCommand {
	/**
	 * @var DonationFactory
	 */
	private $donationFactory;
	/**
	 * @var DonationRepository
	 */
	private $donationRepository;

	/**
	 * @param  DonationFactory  $donationFactory
	 * @param  DonationRepository  $donationRepository
	 */
	public function __construct(
		DonationFactory $donationFactory,
		DonationRepository $donationRepository
	) {
		$this->donationFactory    = $donationFactory;
		$this->donationRepository = $donationRepository;
	}

	/**
	 * Generates Donations
	 *
	 * ## OPTIONS
	 *
	 * [--count=<count>]
	 * : Number of donations to generate
	 * default: 10
	 *
	 * [--status=<status>]
	 * : Donation status
	 * default: publish
	 * options:
	 *   - publish
	 *   - random
	 * get all available statuses with command:
	 *     wp give test-donation-statuses
	 *
	 * [--total-revenue=<amount>]
	 * : Total revenue amount to be generated
	 * default: 0
	 *
	 * [--currency=<currency>]
	 * : Donation currency
	 * default: GiveWP default currency
	 *
	 * [--preview=<preview>]
	 * : Preview generated data
	 * default: false
	 *
	 * [--start-date=<date>]
	 * : Set donation start date. Date format is YYYY-MM-DD
	 * default: false
	 *
	 * ## EXAMPLES
	 *
	 *     wp give test-donations --count=50 --status=random --total-revenue=10000 --currency=USD --start-date=2020-11-22
	 *
	 * @when after_wp_load
	 */
	public function __invoke( $args, $assocArgs ) {
		global $wpdb;
		// Get CLI args
		$count        = WP_CLI\Utils\get_flag_value( $assocArgs, 'count', $default = 10 );
		$preview      = WP_CLI\Utils\get_flag_value( $assocArgs, 'preview', $default = false );
		$status       = WP_CLI\Utils\get_flag_value( $assocArgs, 'status', $default = 'publish' );
		$totalRevenue = WP_CLI\Utils\get_flag_value( $assocArgs, 'total-revenue', $default = 0 );
		$currency     = WP_CLI\Utils\get_flag_value( $assocArgs, 'currency', $default = give_get_option( 'currency' ) );
		$startDate    = WP_CLI\Utils\get_flag_value( $assocArgs, 'start-date', $default = false );

		try {
			// Factory config
			$this->donationFactory->setDonationStatus( $status );
			$this->donationFactory->setDonationCurrency( $currency );

			if ( $totalRevenue ) {
				$this->donationFactory->setDonationAmount( ( $totalRevenue / $count ) );
			}

			if ( $startDate ) {
				$this->donationFactory->setDonationStartDate( $startDate );
			}

			// Generate donations
			$donations = $this->donationFactory->make( $count );

		} catch ( Throwable $e ) {
			return WP_CLI::error( $e->getMessage() );
		}

		if ( $preview ) {
			WP_CLI\Utils\format_items(
				'table',
				$donations,
				array_keys( $this->donationFactory->definition() )
			);
		} else {
			$progress = WP_CLI\Utils\make_progress_bar( 'Generating donations', $count );

			// Start DB transaction
			$wpdb->query( 'START TRANSACTION' );

			try {

				foreach ( $donations as $donation ) {
					$this->donationRepository->insertDonation( $donation );
					$progress->tick();
				}

				$wpdb->query( 'COMMIT' );

				$progress->finish();

			} catch ( Throwable $e ) {
				$wpdb->query( 'ROLLBACK' );

				WP_CLI::error( $e->getMessage() );
			}
		}

	}
}
