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
	 * @var DonationFactory
	 */
	private $donationFactory;

	/**
	 * @var DonationRepository
	 */
	private $donationRepository;

	public function __construct(
		DonationFactory $donationFactory,
		DonationRepository $donationRepository
	) {
		$this->donationFactory    = $donationFactory;
		$this->donationRepository = $donationRepository;
	}

	/**
	 * @inheritDoc
	 */
	public
	function registerRoute() {
		register_rest_route(
			'give-api/v2',
			$this->endpoint,
			[
				[
					'methods'             => 'POST',
					'callback'            => [ $this, 'handleRequest' ],
					'permission_callback' => [ $this, 'permissionsCheck' ],
					'args'                => [
						'status'    => [
							'type'              => 'string',
							'required'          => true,
							'validate_callback' => [ $this, 'validateDonationStatus' ],
						],
						'count'     => [
							'type'              => 'integer',
							'required'          => true,
							'sanitize_callback' => [ $this, 'sanitizeNumber' ],
						],
						'revenue'   => [
							'type'              => 'integer',
							'required'          => false,
							'sanitize_callback' => [ $this, 'sanitizeNumber' ],
						],
						'startDate' => [
							'type'     => 'string',
							'required' => false,
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
	public
	function getSchema() {
		return [
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'give-funds',
			'type'       => 'object',
			'properties' => [
				'status'    => [
					'type'        => 'string',
					'description' => esc_html__( 'Donation status', 'give-test-data' ),
				],
				'count'     => [
					'type'        => 'integer',
					'description' => esc_html__( 'Number of donations to generate', 'give-test-data' ),
				],
				'revenue'   => [
					'type'        => 'integer',
					'description' => esc_html__( 'Revenue amount', 'give-test-data' ),
				],
				'startDate' => [
					'type'        => 'string',
					'description' => esc_html__( 'Start date', 'give-test-data' ),
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
		global $wpdb;

		$status    = $request->get_param( 'status' );
		$count     = $request->get_param( 'count' );
		$revenue   = $request->get_param( 'revenue' );
		$startDate = $request->get_param( 'startDate' );

		$this->donationFactory->setDonationStatus( $status );

		if ( $revenue ) {
			$this->donationFactory->setDonationAmount( $revenue );
		}

		if ( $startDate ) {
			$this->donationFactory->setDonationStartDate( $startDate );
		}

		// Check donations count and limit if necessary
		$donationsCount = ( $count > $this->limit ) ? $this->limit : $count;
		// Generate donations
		$donations = $this->donationFactory->make( $donationsCount );
		// Start DB transaction
		$wpdb->query( 'START TRANSACTION' );

		try {

			foreach ( $donations as $donation ) {
				$this->donationRepository->insertDonation( $donation );
			}

			$wpdb->query( 'COMMIT' );

			$responseData = [
				'status'  => true,
				'hasMore' => ( $count > $this->limit )
					? ( $count - $this->limit )
					: 0
			];

		} catch ( Throwable $e ) {
			$wpdb->query( 'ROLLBACK' );

			$responseData = [
				'status'  => false,
				'message' => $e->getMessage()
			];
		}

		return new WP_REST_Response( $responseData );
	}

}
