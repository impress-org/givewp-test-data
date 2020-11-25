<?php

namespace GiveTestData\TestData\Routes;

use Throwable;
use WP_REST_Request;
use WP_REST_Response;
use GiveTestData\TestData\Factories\DonationFormFactory as DonationFormFactory;
use GiveTestData\TestData\Repositories\DonationFormRepository as DonationFormRepository;

/**
 * Class FormsRoute
 * @package GiveTestData\TestData\Routes
 */
class FormsRoute extends Endpoint {

	/**
	 * Maximum number of donation forms to generate per request
	 * @var int
	 */
	private $limit = 30;

	/** @var string */
	protected $endpoint = 'give-test-data/generate-forms';

	/**
	 * @var DonationFormFactory
	 */
	private $donationFormFactory;

	/**
	 * @var DonationFormRepository
	 */
	private $donationFormRepository;

	/**
	 * FormsRoute constructor.
	 *
	 * @param DonationFormFactory $donationFormFactory
	 * @param DonationFormRepository $donationFormRepository
	 */
	public function __construct(
		DonationFormFactory $donationFormFactory,
		DonationFormRepository $donationFormRepository
	) {
		$this->donationFormFactory    = $donationFormFactory;
		$this->donationFormRepository = $donationFormRepository;
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
					'args'                => [
						'count'      => [
							'type'              => 'integer',
							'required'          => true,
							'sanitize_callback' => [ $this, 'sanitizeNumber' ],
						],
						'template'   => [
							'type'              => 'string',
							'required'          => true,
							'validate_callback' => 'validateFormTemplates',
						],
						'setGoal'    => [
							'type'     => 'boolean',
							'required' => false,
						],
						'generateTC' => [
							'type'     => 'boolean',
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
	public function getSchema() {
		return [
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'give-test-data',
			'type'       => 'object',
			'properties' => [
				'count'      => [
					'type'        => 'integer',
					'description' => esc_html__( 'Number of Donation Forms to generate', 'give-test-data' ),
				],
				'template'   => [
					'type'        => 'string',
					'description' => esc_html__( 'Donation Form template', 'give-test-data' ),
				],
				'setGoal'    => [
					'type'        => 'boolean',
					'description' => esc_html__( 'Set Donation Goal', 'give-test-data' ),
				],
				'generateTC' => [
					'type'        => 'boolean',
					'description' => esc_html__( 'Generate Donation Terms & Conditions', 'give-test-data' ),
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

		$count    = $request->get_param( 'count' );
		$template = $request->get_param( 'template' );
		$setGoal  = $request->get_param( 'setGoal' );
		$setTerms = $request->get_param( 'setTerms' );

		// Check forms count and limit if necessary
		$formsCount = ( $count > $this->limit ) ? $this->limit : $count;

		// Factory config
		$this->donationFormFactory->setFormTemplate( $template );
		$this->donationFormFactory->setDonationFormGoal( $setGoal );
		$this->donationFormFactory->setTermsAndConditions( $setTerms );

		// Generate donation forms
		$forms = $this->donationFormFactory->make( $formsCount );
		// Start DB transaction
		$wpdb->query( 'START TRANSACTION' );

		try {

			foreach ( $forms as $form ) {
				$this->donationFormRepository->insertDonationForm( $form );
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
