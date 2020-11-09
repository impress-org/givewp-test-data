<?php

namespace GiveTestData\Addon;

use InvalidArgumentException;

/**
 * Helper class responsible for loading add-on views.
 *
 * @package     GiveTestData\Addon\Helpers
 * @copyright   Copyright (c) 2020, GiveWP
 */
class View {
	/**
	 * Default add-on domain
	 */
	const DEFAULT_DOMAIN = 'TestData';

	/**
	 * @param string $view Template name
	 * When using multiple domains within this add-on, the domain directory can be set by using "." in the template name.
	 * String before the "." character is domain directory, and everything after is the template file path
	 * Example usage: View::render( 'DomainName.templateName' );
	 * This will try to load src/DomainName/resources/view/templateName.php file
	 *
	 * @param array $templateParams Arguments for template.
	 * @param bool $echo
	 *
	 * @return string|void
	 * @throws InvalidArgumentException if template file not exist
	 *
	 * @since 1.0.0
	 */
	public static function load( $view, $templateParams = [], $echo = false ) {
		// Get domain and file path
		list ( $domain, $file ) = static::getPaths( $view );
		$template               = GIVE_TEST_DATA_DIR . "src/{$domain}/resources/views/{$file}.php";

		if ( ! file_exists( $template ) ) {
			throw new InvalidArgumentException( "View template file {$template} not exist" );
		}

		ob_start();
		extract( $templateParams );
		include $template;
		$content = ob_get_clean();

		if ( ! $echo ) {
			return $content;
		}

		echo $content;
	}

	/**
	 * @param string $view
	 * @param array $vars
	 *
	 * @since 1.0.0
	 */
	public static function render( $view, $vars = [] ) {
		static::load( $view, $vars, true );
	}

	/**
	 * Get domain and template file path
	 *
	 * @param string $path
	 *
	 * @return array
	 * @since 1.0.0
	 */
	private static function getPaths( $path ) {
		// Check for . delimiter
		if ( false === strpos( $path, '.' ) ) {
			return [
				self::DEFAULT_DOMAIN,
				$path,
			];
		}

		return explode( '.', $path, 2 );
	}
}
