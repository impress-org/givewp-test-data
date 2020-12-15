<?php

namespace GiveTestData\TestData;

use WP_CLI;
use Give\Helpers\Hooks;
use GiveTestData\TestData\Helpers\Assets;
use GiveTestData\TestData\Helpers\SettingsPage;
use GiveTestData\TestData\AdminSettings\Settings as AddonSettingsPage;
use GiveTestData\Addon\Language;
use Give\ServiceProviders\ServiceProvider as GiveServiceProvider;
use GiveTestData\TestData\Routes\DonationsRoute;
use GiveTestData\TestData\Routes\DonorsRoute;
use GiveTestData\TestData\Routes\FormsRoute;
use GiveTestData\TestData\Routes\DemonstrationPageRoute;


/**
 * Class ServiceProvider
 * @package GiveTestData\TestData
 */
class ServiceProvider implements GiveServiceProvider {
	/**
	 * @inheritDoc
	 */
	public function register() {
	}

	/**
	 * @inheritDoc
	 */
	public function boot() {
		// Register REST routes
		Hooks::addAction( 'rest_api_init', DonationsRoute::class, 'registerRoute' );
		Hooks::addAction( 'rest_api_init', DonorsRoute::class, 'registerRoute' );
		Hooks::addAction( 'rest_api_init', FormsRoute::class, 'registerRoute' );
		Hooks::addAction( 'rest_api_init', DemonstrationPageRoute::class, 'registerRoute' );

		// Load add-on translations.
		Hooks::addAction( 'init', Language::class, 'load' );

		// Load assets.
		if ( isset( $_GET[ 'tab' ] ) && $_GET[ 'tab' ] === 'give-test-data' ) {
			Hooks::addAction( 'admin_enqueue_scripts', Assets::class, 'load' );
		}

		// Register settings page
		SettingsPage::registerPage( AddonSettingsPage::class, 'tools' );
	}
}
