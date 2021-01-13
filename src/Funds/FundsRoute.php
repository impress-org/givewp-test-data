<?php

namespace GiveTestData\Funds;


use WP_REST_Request;
use WP_REST_Response;
use Throwable;
use GiveTestData\TestData\Routes\Endpoint;
use Give\TestData\Addons\Funds\FundFactory;
use Give\TestData\Addons\Funds\FundRepository;

/**
 * Class FundsRoute
 * @package GiveTestData\Funds
 */
class FundsRoute extends Endpoint {

	/**
	 * Maximum number of donors to generate per request
	 * @var int
	 */
	private $limit = 30;

	/** @var string */
	protected $endpoint = 'give-test-data/generate-funds';

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
						'count'  => [
							'type'              => 'integer',
							'required'          => true,
							'sanitize_callback' => [ $this, 'sanitizeNumber' ],
						],
						'params' => [
							'type'     => 'json',
							'required' => false,
							'default'  => '',
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
				'count'  => [
					'type'        => 'integer',
					'description' => esc_html__( 'Number of funds to generate', 'give-test-data' ),
				],
				'params' => [
					'type'        => 'json',
					'description' => esc_html__( 'Additional params', 'give-test-data' ),
				],
			],
		];
	}

	/**
	 * @param  WP_REST_Request  $request
	 *
	 * @return WP_REST_Response
	 * @since 1.0.0
	 */
	public function handleRequest( WP_REST_Request $request ) {
		global $wpdb;

		$fundFactory    = give( FundFactory::class );
		$fundRepository = give( FundRepository::class );
		$count          = $request->get_param( 'count' );
		$params         = $request->get_param( 'params' );
		$consistent     = ( isset( $params[ 'donations_consitent_data' ] ) && $params[ 'donations_consitent_data' ] );

		// Check funds count and limit if necessary
		$fundsCount = ( $count > $this->limit ) ? $this->limit : $count;
		// Generate funds
		$funds = $fundFactory->consistent( $consistent )->make( $fundsCount );
		// Start DB transaction
		$wpdb->query( 'START TRANSACTION' );

		try {

			foreach ( $funds as $fund ) {
				$fundRepository->insertFund( $fund );
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
