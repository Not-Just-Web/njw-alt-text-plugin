<?php
/**
 * Unit tests for the settings page functions.
 *
 * This file contains unit tests for the functions that render the settings page for the NJW Media plugin.
 *
 * @package NJWMedia\Test
 * @since 1.0.0
 */

namespace NJWMedia\Test;

use WP_Mock\Tools\TestCase;
use WP_Mock;

/**
 * Class SettingsPageTest.
 *
 * This class contains the unit tests for the settings page functions.
 *
 * @package NJWMedia\Test
 * @since 1.0.0
 */
class SettingsPageTest extends TestCase {

	/**
	 * Test the njw_media__options_page function.
	 */
	public function test_njw_media__options_page() {
		WP_Mock::userFunction(
			'settings_fields',
			[
				'args'   => [ 'njw_media_Plugin_page' ],
				'return' => function () {
					return njw_media_options_settings_field();
				},
			]
		);

		WP_Mock::userFunction(
			'do_settings_sections',
			[
				'args'   => [ 'njw_media_Plugin_page' ],
				'return' => '',
			]
		);


		// Mock the WordPress functions used in the function
		WP_Mock::userFunction(
			'esc_attr',
			[
				'return_arg' => 0,
			]
		);
		WP_Mock::userFunction(
			'esc_html',
			[
				'return_arg' => 0,
			]
		);
		WP_Mock::userFunction(
			'njw_get_single_option',
			[
				'return' => 'test_value',
			]
		);
		WP_Mock::userFunction(
			'njw_generate_random_string',
			[
				'return' => 'random_string',
			]
		);

		// Call the function and capture the output.
		ob_start();
		njw_media__options_page();
		$output = ob_get_clean();

		// Assert that the output contains the expected HTML.
		$this->assertStringContainsString( '<div class="njw-media-field ACCESS_KEY">', $output );
		$this->assertStringContainsString( '<input type="text" name="njw-alt-settings[ACCESS_KEY]" value=""/>', $output );

		// Assert that the output contains the expected HTML.
		$this->assertStringContainsString( '<div class="njw-media-field SECRET_KEY">', $output );
		$this->assertStringContainsString( '<input type="text" name="njw-alt-settings[SECRET_KEY]" value=""/>', $output );

		// Assert that the output contains the expected HTML.
		$this->assertStringContainsString( '<div class="njw-media-field API_PROXY_URL">', $output );
		$this->assertStringContainsString( '<input type="text" name="njw-alt-settings[API_PROXY_URL]" value="/wp-json/njw-media/v1/"/>', $output );
	}
}
