<?php

namespace GiveTestData\TestData\Routes;

use WP_REST_Request;
use WP_REST_Response;
use Throwable;
use GiveTestData\TestData\Factories\DonorFactory as DonorFactory;
use GiveTestData\TestData\Repositories\DonorRepository as DonorRepository;

/**
 * Class DonorsRoute
 * @package GiveTestData\TestData\Routes
 */
class DonorsRoute extends Endpoint {

	/**
	 * Maximum number of donors to generate per request
	 * @var int
	 */
	private $limit = 30;

	/** @var string */
	protected $endpoint = 'give-test-data/generate-donors';

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
						'count' => [
							'type'              => 'integer',
							'required'          => true,
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
			'title'      => 'give-test-data',
			'type'       => 'object',
			'properties' => [
				'count' => [
					'type'        => 'integer',
					'description' => esc_html__( 'Number of donors to generate', 'give-test-data' ),
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

		$donorFactory    = give( DonorFactory::class );
		$donorRepository = give( DonorRepository::class );
		$count           = $request->get_param( 'count' );

		// Check donors count and limit if necessary
		$donorsCount = ( $count > $this->limit ) ? $this->limit : $count;
		// Generate donors
		$donors = $donorFactory->make( $donorsCount );
		// Start DB transaction
		$wpdb->query( 'START TRANSACTION' );

		try {

			foreach ( $donors as $donor ) {
				$donorRepository->insertDonor( $donor );
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
