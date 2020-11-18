<?php

namespace GiveTestData\Addon;

/**
 * Helper class responsible for showing add-on notices.
 *
 * @package     GiveTestData\Addon\Helpers
 * @copyright   Copyright (c) 2020, GiveWP
 */
class Notices {

	/**
	 * GiveWP min required version notice.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public static function giveVersionError() {
		Give()->notices->register_notice(
			[
				'id'          => 'give-test-data-activation-error',
				'type'        => 'error',
				'description' => View::load( 'admin/notices/give-version-error' ),
				'show'        => true,
			]
		);
	}

	/**
	 * GiveWP inactive notice.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public static function giveInactive() {
		echo View::load( 'admin/notices/give-inactive' );
	}
}
