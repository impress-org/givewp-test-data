<?php

namespace GiveTestData\TestData\Commands;

use WP_CLI;
use GiveTestData\TestData\Factories\PageFactory;
/**
 * Class DonorSeedCommand
 * @package GiveTestData\TestData\Commands
 *
 * A WP-CLI command for seeding pages.
 */
class PageSeedCommand {

	/**
	 * @var PageFactory
	 */
	private $pageFactory;

	public function __construct( PageFactory $pageFactory ) {
		$this->pageFactory = $pageFactory;
	}

	/**
	 * Generates GiveWP demonstartion page with all GiveWP shortcodes
	 *
	 * [--preview=<preview>]
	 * : Preview generated data
	 * default: false
	 *
	 * ## EXAMPLES
	 *
	 *     wp give test-demonstration-page --preview=true
	 *
	 * @when after_wp_load
	 */
	public function __invoke( $args, $assocArgs ) {
		$preview = WP_CLI\Utils\get_flag_value( $assocArgs, 'preview', $default = false );

		$page = $this->pageFactory->definition();

		if ( $preview ) {
			WP_CLI\Utils\format_items(
				'table',
				[ $page ],
				array_keys( $page )
			);
		} else {
			$progress = WP_CLI\Utils\make_progress_bar( 'Generating demonstration pages', 1 );

			wp_insert_post( $page );
			$progress->finish();
		}
	}
}
