<?php

namespace GiveTestData\TestData\Routes;

use WP_REST_Request;
use WP_REST_Response;
use Throwable;
use GiveTestData\TestData\Factories\DonationFactory as DonationFactory;
use GiveTestData\TestData\Repositories\DonationRepository as DonationRepository;

/**
 * Class FundOverviewRoute
 * @package GiveFunds\Routes
 */
class DonationsRoute extends Endpoint {

	/**
	 * Maximum number of donations to generate per request
	 * @var int
	 */
	private $limit = 30;

	/** @var string */
	protected $endpoint = 'give-test-data/generate-donations';

	/**
	 * @inheritDoc
	 */
	public function registerRoute() {
		register_rest_route(
			'give-api/v2',
			$this->endpoint,
			[
				[
					'methods'             => 'POST',
					'callback'            => [ $this, 'handleRequest' ],
					'permission_callback' => [ $this, 'permissionsCheck' ],
					'args'                => [
						'status'  => [
							'type'              => 'string',
							'required'          => true,
							'validate_callback' => [ $this, 'validateDonationStatus' ],
						],
						'count'   => [
							'type'              => 'integer',
							'required'          => true,
							'sanitize_callback' => [ $this, 'sanitizeNumber' ],
						],
						'revenue' => [
							'type'              => 'integer',
							'required'          => false,
							'sanitize_callback' => [ $this, 'sanitizeNumber' ],
						],
					],
				],
				'schema' => [ $this, 'getSchema' ]
			]
		);
	}

	/**
	 * @return array
	 * @since 1.0.0
	 */
	public function getSchema() {
		return [
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'give-funds',
			'type'       => 'object',
			'properties' => [
				'status'  => [
					'type'        => 'string',
					'description' => esc_html__( 'Donation status', 'give-test-data' ),
				],
				'count'   => [
					'type'        => 'integer',
					'description' => esc_html__( 'Start Date', 'give-funds' ),
				],
				'revenue' => [
					'type'        => 'integer',
					'description' => esc_html__( 'End Date', 'give-funds' ),
				],
			],
		];
	}

	/**
	 * @param WP_REST_Request $request
	 *
	 * @return WP_REST_Response
	 * @since 1.0.0
	 */
	public function handleRequest( WP_REST_Request $request ) {
		$donationFactory    = give( DonationFactory::class );
		$donationRepository = give( DonationRepository::class );

		$status  = $request->get_param( 'status' );
		$count   = $request->get_param( 'count' );
		$revenue = $request->get_param( 'revenue' );

		$donationFactory->setDonationStatus( $status );

		if ( $revenue ) {
			$donationFactory->setDonationAmount( $revenue );
		}

		// Check donations count and limit if necessary
		$donationsCount = ( $count > $this->limit ) ? $this->limit : $count;
		// Generate donations
		$donations = $donationFactory->make( $donationsCount );
		// Start DB transaction
		$GLOBALS['wpdb']->query( 'START TRANSACTION' );

		try {

			foreach ( $donations as $donation ) {
				$donationRepository->insertDonation( $donation );
			}

			$GLOBALS['wpdb']->query( 'COMMIT' );

			$responseData = [
				'status'  => true,
				'hasMore' => ( $count > $this->limit )
					? ( $count - $this->limit )
					: 0
			];

		} catch ( Throwable $e ) {
			$GLOBALS['wpdb']->query( 'ROLLBACK' );

			$responseData = [
				'status'  => false,
				'message' => $e->getMessage()
			];
		}

		return new WP_REST_Response( $responseData );
	}

}
