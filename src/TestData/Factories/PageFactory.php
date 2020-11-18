<?php

namespace GiveTestData\TestData\Factories;

use GiveTestData\TestData\Framework\Factory;

/**
 * Class PageFactory
 * @package GiveTestData\TestData\Factories
 */
class PageFactory extends Factory {

	/**
	 * Donor definition
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function definition() {
		return [
			'post_title'   => 'GiveWP Demonstration page',
			'post_content' => $this->getContent(),
			'post_status'  => 'publish',
			'post_author'  => $this->randomAuthor(),
			'post_type'    => 'page',
		];
	}

	/**
	 * Page content
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function getContent() {

		$giveShortcodes = $this->getGiveShortcodes();

		$content = '';

		foreach( $giveShortcodes as $shortcode ) {
			$content .= "<h3>[{$shortcode}]</h3>";
			$content .= $this->getGiveShortcodeContent( $shortcode );
		}

		return $content;

	}

	/**
	 * Get GiveWP shortcodes
	 *
	 * @return array
	 */
	private function getGiveShortcodes() {

		$shortcodes = [];

		foreach(  $GLOBALS['shortcode_tags'] as $shortcode => $action ) {
			if( false !== strpos( $shortcode, 'give_' ) ) {
				$shortcodes[] = $shortcode;
			}
		}

		return $shortcodes;
	}

	/**
	 * Execute shortcode
	 *
	 * @param string $shortcode
	 *
	 * @return string
	 */
	private function getGiveShortcodeContent( $shortcode ) {
		ob_start();
		echo do_shortcode( "[{$shortcode }]" );
		return ob_get_clean();
	}
}
