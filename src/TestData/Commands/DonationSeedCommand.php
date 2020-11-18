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
	 * @param DonationFactory $donationFactory
	 * @param DonationRepository $donationRepository
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
	 * ## EXAMPLES
	 *
	 *     wp give test-donations --count=50 --status=random --total-revenue=10000 --currency=USD
	 *
	 * @when after_wp_load
	 */
	public function __invoke( $args, $assocArgs ) {
		// Get CLI args
		$count        = WP_CLI\Utils\get_flag_value( $assocArgs, 'count', $default = 10 );
		$preview      = WP_CLI\Utils\get_flag_value( $assocArgs, 'preview', $default = false );
		$status       = WP_CLI\Utils\get_flag_value( $assocArgs, 'status', $default = 'publish' );
		$totalRevenue = WP_CLI\Utils\get_flag_value( $assocArgs, 'total-revenue', $default = 0 );
		$currency     = WP_CLI\Utils\get_flag_value( $assocArgs, 'currency', $default = give_get_option( 'currency' ) );

		// Check donation status
		if ( ! $this->donationFactory->checkDonationStatus( $status ) ) {
			WP_CLI::error(
				WP_CLI::colorize( "Invalid donation status: %g{$status}%n \nGet all available donation statuses: %gwp give test-donation-statuses%n" )
			);
		}

		// Factory config
		$this->donationFactory->setDonationStatus( $status );
		$this->donationFactory->setDonationCurrency( $currency );

		if ( $totalRevenue ) {
			$this->donationFactory->setDonationAmount( ( $totalRevenue / $count ) );
		}

		// Generate donations
		$donations = $this->donationFactory->make( $count );

		if ( $preview ) {
			WP_CLI\Utils\format_items(
				'table',
				$donations,
				array_keys( $this->donationFactory->definition() )
			);
		} else {
			$progress = WP_CLI\Utils\make_progress_bar( 'Generating donations', $count );

			// Start DB transaction
			$GLOBALS['wpdb']->query( 'START TRANSACTION' );

			try {
				
				foreach ( $donations as $donation ) {
					$this->donationRepository->insertDonation( $donation );
					$progress->tick();
				}

				$GLOBALS['wpdb']->query( 'COMMIT' );

				$progress->finish();

			} catch ( Throwable $e ) {
				$GLOBALS['wpdb']->query( 'ROLLBACK' );

				WP_CLI::error( $e->getMessage() );
			}
		}

	}
}
