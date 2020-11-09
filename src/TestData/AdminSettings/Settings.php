<?php

namespace GiveTestData\TestData\AdminSettings;

use GiveTestData\Addon\View;
use InvalidArgumentException;

/**
 * Example code to show how to add setting page to give settings.
 *
 * @package     GiveTestData\Addon
 * @subpackage  Classes/Give_BP_Admin_Settings
 * @copyright   Copyright (c) 2020, GiveWP
 */
class Settings extends \Give_Settings_Page {

	/**
	 * Settings constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->id          = 'give-test-data';
		$this->label       = esc_html__( 'Test Data', 'give-test-data' );
		$this->default_tab = 'forms';

		parent::__construct();
	}

	/**
	 * Get sections.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function get_sections() {
		$sections = [
			'forms'     => esc_html__( 'Generate Donation Forms', 'give-test-data' ),
			'donors'    => esc_html__( 'Generate Donors', 'give-test-data' ),
			'donations' => esc_html__( 'Generate Donations', 'give-test-data' ),
			'pages'     => esc_html__( 'Generate Pages', 'give-test-data' ),
		];

		return apply_filters( 'give_get_sections_' . $this->id, $sections );
	}

	/**
	 * Get sections pages.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function get_sections_pages() {
		$actions = [
			'forms'     => Forms::class,
			'donors'    => Donors::class,
			'donations' => Donations::class,
			'pages'     => Pages::class,
		];

		return apply_filters( 'give_get_sections_pages_' . $this->id, $actions );
	}

	/**
	 * Output the settings.
	 *
	 * @return void
	 * @since  1.0.0
	 */
	public function output() {
		$section = give_get_current_setting_section();
		$actions = $this->get_sections_pages();

		if ( array_key_exists( $section, $actions ) ) {

			$page = give( $actions[ $section ] );

			if ( ! $page instanceof SettingsPage ) {
				throw new InvalidArgumentException(
					sprintf( '%s must implement the %s\SettingsPage interface', get_class( $page ), __NAMESPACE__ )
				);
			}

			// Render settings.
			View::render(
				'admin/content',
				[
					'content' => $page->renderPage(),
				]
			);
		}
	}
}
