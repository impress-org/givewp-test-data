<?php

namespace GiveTestData\TestData\Helpers;

use InvalidArgumentException;

/**
 * Helper class responsible for adding settings pages.
 *
 * @package     GiveTestData\Addon\Helpers
 * @copyright   Copyright (c) 2020, GiveWP
 */
class SettingsPage {

	/**
	 * Register settings page.
	 *
	 * @param string $class subclass of Give_Settings_Page
	 * @param string $page
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public static function registerPage( $class, $page = 'settings' ) {
		add_filter(
			"give-{$page}_get_settings_pages",
			function () use ( $class ) {
				if ( ! class_exists( $class ) ) {
					throw new InvalidArgumentException( "The class {$class} does not exist" );
				}

				if ( ! is_subclass_of( $class, \Give_Settings_Page::class ) ) {
					throw new InvalidArgumentException(
						"{$class} class must extend the Give_Settings_Page class"
					);
				}

				return give( $class )->get_settings();
			}
		);
	}

	/**
	 * Add settings to the existing Settings page.
	 *
	 * @param string $settingsId - settings page ID
	 * @param string $sectionId - settings page section
	 * @param array $settings
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public static function addSettings( $settingsId, $sectionId, $settings ) {
		add_filter(
			sprintf( 'give_get_settings_%s', $settingsId ),
			function ( $pageSettings ) use ( $settingsId, $sectionId, $settings ) {
				// Check settings page and section
				if ( ! \Give_Admin_Settings::is_setting_page( $settingsId, $sectionId ) ) {
					return $pageSettings;
				}

				return array_merge( $pageSettings, $settings );
			}
		);
	}

	/**
	 * Add Settings page section.
	 *
	 * @param string $settingsId - settings page ID
	 * @param string $sectionId
	 * @param string $sectionName
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public static function addPageSection( $settingsId, $sectionId, $sectionName ) {
		add_filter(
			sprintf( 'give_get_sections_%s', $settingsId ),
			function ( $sections ) use ( $sectionId, $sectionName ) {
				$sections[ $sectionId ] = $sectionName;
				return $sections;
			}
		);
	}

	/**
	 * Add Settings page section content.
	 *
	 * @param string $settingsId - settings page ID
	 * @param string $sectionId
	 * @param string $settingsPage
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public static function addPageSectionContent( $settingsId, $sectionId, $settingsPage ) {
		add_filter(
			sprintf( 'give_get_sections_pages_%s', $settingsId ),
			function ( $sections ) use ( $sectionId, $settingsPage ) {
				$sections[ $sectionId ] = $settingsPage;
				return $sections;
			}
		);
	}

	/**
	 * Remove Settings page section.
	 *
	 * @param string $settingsId - settings page ID
	 * @param string $sectionId
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public static function removePageSection( $settingsId, $sectionId ) {
		add_filter(
			sprintf( 'give_get_sections_%s', $settingsId ),
			function ( $sections ) use ( $sectionId ) {
				if ( isset( $sections[ $sectionId ] ) ) {
					unset( $sections[ $sectionId ] );
				}

				return $sections;
			},
			999
		);
	}

}
