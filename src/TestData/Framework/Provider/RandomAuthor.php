<?php

namespace GiveTestData\TestData\Framework\Provider;

/**
 * Returns a random Author ID from the users table.
 */
class RandomAuthor extends RandomProvider {

	public function __invoke() {
		global $wpdb;
		$authorIDs = $wpdb->get_col( "SELECT id FROM {$wpdb->prefix}users" );

		return $this->faker->randomElement( $authorIDs );
	}
}
