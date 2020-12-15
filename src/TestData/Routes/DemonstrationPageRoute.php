<?php

namespace GiveTestData\TestData\Routes;

use Give\TestData\Factories\PageFactory;
use Throwable;
use WP_REST_Request;
use WP_REST_Response;

/**
 * Class DemonstrationPageRoute
 * @package GiveTestData\TestData\Routes
 */
class DemonstrationPageRoute extends Endpoint {

	/** @var string */
	protected $endpoint = 'give-test-data/generate-demonstration-page';

	/**
	 * @var PageFactory
	 */
	private $pageFactory;

	/**
	 * DemonstrationPageRoute constructor.
	 *
	 * @param  PageFactory  $pageFactory
	 */
	public function __construct( PageFactory $pageFactory ) {
		$this->pageFactory = $pageFactory;
	}

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
					'args'                => [],
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
			'properties' => [],
		];
	}

	/**
	 *
	 * @return WP_REST_Response
	 * @since 1.0.0
	 */
	public function handleRequest() {
		global $wpdb;

		// Definition
		$page = $this->pageFactory->definition();

		// Start DB transaction
		$wpdb->query( 'START TRANSACTION' );

		try {

			wp_insert_post( $page );

			do_action( 'give-test-data-insert-demonstration-page' );

			$wpdb->query( 'COMMIT' );

			$responseData = [
				'status' => true,
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
