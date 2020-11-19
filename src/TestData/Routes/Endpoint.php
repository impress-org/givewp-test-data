<?php

namespace GiveTestData\TestData\Routes;

use Give\API\RestRoute;
use WP_Error;

/**
 * Class Endpoint
 * @package GiveTestData\TestData\Routes
 */
abstract class Endpoint implements RestRoute {

	/**
	 * @var string
	 */
	protected $endpoint;

	/**
	 * @param string $param
	 *
	 * @return bool
	 * @since 1.0.0
	 *
	 */
	public function validateDonationStatus( $param ) {
		$statuses = apply_filters( 'give-test-data-donation-statuses', give_get_payment_statuses() );

		return in_array( $param, array_keys( $statuses ), true );
	}

	/**
	 * @param string $param
	 *
	 * @return int
	 * @since 1.0.0
	 *
	 */
	public function sanitizeNumber( $param ) {
		return filter_var( $param, FILTER_VALIDATE_INT );
	}

	/**
	 * Check user permissions
	 * @return bool|WP_Error
	 */
	public function permissionsCheck() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return new WP_Error(
				'rest_forbidden',
				esc_html__( 'You dont have the right permissions to use Give TestData', 'give-test-data' ),
				[ 'status' => $this->authorizationStatusCode() ]
			);
		}

		return true;
	}

	// Sets up the proper HTTP status code for authorization.
	public function authorizationStatusCode() {
		if ( is_user_logged_in() ) {
			return 403;
		}

		return 401;
	}
}
