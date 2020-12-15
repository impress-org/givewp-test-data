<?php namespace GiveTestData;

use GiveTestData\Addon\Environment;
use GiveTestData\TestData\Helpers\Addons;
use GiveTestData\TestData\ServiceProvider;

/**
 * Plugin Name: Give - Test Data Generator
 * Plugin URI:  https://givewp.com/
 * Description: This plugin makes it easy to generate test (dummy) donors, donations, donation forms, and more using an easy-to-use interface.
 * Version:     1.0.0
 * Author:      GiveWP
 * Author URI:  https://givewp.com/
 * Text Domain: give-test-data
 * Domain Path: /languages
 */
defined( 'ABSPATH' ) or exit;

// Add-on name
define( 'GIVE_TEST_DATA_NAME', 'Give - Test Data Generator' );

// Versions
define( 'GIVE_TEST_DATA_VERSION', '1.0.0' );
define( 'GIVE_TEST_DATA_MIN_GIVE_VERSION', '2.9.5' );

// Add-on paths
define( 'GIVE_TEST_DATA_FILE', __FILE__ );
define( 'GIVE_TEST_DATA_DIR', plugin_dir_path( GIVE_TEST_DATA_FILE ) );
define( 'GIVE_TEST_DATA_URL', plugin_dir_url( GIVE_TEST_DATA_FILE ) );
define( 'GIVE_TEST_DATA_BASENAME', plugin_basename( GIVE_TEST_DATA_FILE ) );

require GIVE_TEST_DATA_DIR . 'vendor/autoload.php';

// Register the add-on service provider with the GiveWP core.
add_action(
	'before_give_init',
	function () {
		// Check Give min required version.
		if ( Environment::giveMinRequiredVersionCheck() ) {
			give()->registerServiceProvider( ServiceProvider::class );

			// Load active add-ons
			foreach ( Addons::getActiveAddons() as $addon ) {
				give()->registerServiceProvider( $addon[ 'serviceProvider' ] );
			}
		}
	}
);

// Check to make sure GiveWP core is installed and compatible with this add-on.
add_action( 'admin_init', [ Environment::class, 'checkEnvironment' ] );
