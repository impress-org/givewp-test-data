<?php

namespace GiveTestData\TestData\AdminSettings;

use GiveTestData\Addon\View;
use InvalidArgumentException;

/**
 * Class Settings
 * @package GiveTestData\TestData\AdminSettings
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
	 * Get settings page sections.
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
	 * Get section pages.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function get_sections_content() {
		$content = [
			'forms'     => Forms::class,
			'donors'    => Donors::class,
			'donations' => Donations::class,
			'pages'     => Pages::class
		];

		return apply_filters( 'give_get_sections_pages_' . $this->id, $content );
	}

	/**
	 * Get current section settings page
	 *
	 * @return SettingsPage
	 * @since  1.0.0
	 */
	public function get_current_settings_page() {
		$pages   = $this->get_sections_content();
		$section = give_get_current_setting_section();

		if ( ! array_key_exists( $section, $pages ) ) {
			throw new InvalidArgumentException(
				sprintf( 'Section %s doesn\t have registered content', $section )
			);
		}

		$settingsPage = give( $pages[ $section ] );

		if ( ! $settingsPage instanceof SettingsPage ) {
			throw new InvalidArgumentException(
				sprintf( '%s must implement the %s\SettingsPage interface', get_class( $settingsPage ), __NAMESPACE__ )
			);
		}

		return $settingsPage;
	}

	/**
	 * Output the settings.
	 *
	 * @return void
	 * @since  1.0.0
	 */
	public function output() {
		$settingsPage = $this->get_current_settings_page();
		// Render settings.
		View::render(
			'admin/content',
			[
				'content' => $settingsPage->renderPage(),
			]
		);
	}
}
