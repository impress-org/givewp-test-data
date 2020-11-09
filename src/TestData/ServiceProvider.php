<?php

namespace GiveTestData\TestData;

use WP_CLI;
use Give\Helpers\Hooks;
use Faker\Factory as FakerFactory;
use Faker\Generator as FakerGenerator;
use GiveTestData\TestData\Helpers\Assets;
use GiveTestData\TestData\Helpers\SettingsPage;
use GiveTestData\TestData\AdminSettings\Settings as AddonSettingsPage;
use GiveTestData\Addon\Language;
use GiveTestData\TestData\Commands\DonorSeedCommand;
use GiveTestData\TestData\Commands\DonationSeedCommand;
use GiveTestData\TestData\Commands\DonationStatusCommand;
use Give\ServiceProviders\ServiceProvider as GiveServiceProvider;


/**
 * Class ServiceProvider
 * @package GiveTestData\TestData
 */
class ServiceProvider implements GiveServiceProvider {
	/**
	 * @inheritDoc
	 */
	public function register() {
		// Instead of passing around an instance, bind a singleton to the container.
		give()->singleton(
			FakerGenerator::class,
			function () {
				return FakerFactory::create();
			}
		);
	}

	/**
	 * @inheritDoc
	 */
	public function boot() {
		// Add CLI commands
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			$this->addCommands();
		}

		// Load add-on translations.
		Hooks::addAction( 'init', Language::class, 'load' );
		// Load assets.
		Hooks::addAction( 'admin_enqueue_scripts', Assets::class, 'load' );
		// Register settings page
		SettingsPage::registerPage( AddonSettingsPage::class, 'tools' );
	}

	/**
	 * Add CLI comands
	 */
	private function addCommands() {
		WP_CLI::add_command( 'give test-donors', give()->make( DonorSeedCommand::class ) );
		WP_CLI::add_command( 'give test-donations', give()->make( DonationSeedCommand::class ) );
		WP_CLI::add_command( 'give test-donation-statuses', give()->make( DonationStatusCommand::class ) );
	}
}
