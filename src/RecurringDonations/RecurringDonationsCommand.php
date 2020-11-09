<?php

namespace GiveTestData\RecurringDonations;

use WP_CLI;
use GiveTestData\TestData\Framework\ProviderForwarder;
use GiveTestData\TestData\Factories\DonationFactory as DonationsFactory;

/**
 * A WP-CLI command for seeding test data.
 */
class RecurringDonationsCommand {
	/**
	 * @var RecurringDonationsCommand
	 */
	private $donationsFactory;

	use ProviderForwarder;

	/**
	 * @param DonationsFactory $donationsFactory
	 */
	public function __construct( DonationsFactory $donationsFactory ) {
		$this->donationsFactory = $donationsFactory;
	}

	/**
	 * @param $args
	 * @param array $assocArgs
	 */
	public function __invoke( $args, $assocArgs ) {
		$count   = WP_CLI\Utils\get_flag_value( $assocArgs, 'count', $default = 10 );
		$preview = WP_CLI\Utils\get_flag_value( $assocArgs, 'preview', $default = false );

		$donations = $this->donationsFactory->make( $count );

		if ( $preview ) {
			WP_CLI\Utils\format_items(
				'table',
				$donations,
				array_keys( $this->donationsFactory->definition() )
			);
		} else {
			global $wpdb;
			$progress = WP_CLI\Utils\make_progress_bar( 'Generating recurring donations', $count );
			foreach ( $donations as $donation ) {

				$progress->tick();
			}
			$progress->finish();
		}
	}
}
