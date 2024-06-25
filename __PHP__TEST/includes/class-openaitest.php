<?php
/**
 * Unit tests for the Open AI connection functions.
 *
 * This file contains unit tests for the functions that connect to Open AI and retrieve alt text for images using GPT4 Vision.
 *
 * @package NJWMedia\Test
 * @since 1.0.0
 */

namespace NJWMedia\Test;

use WP_Mock\Tools\TestCase;
use WP_Mock;

/**
 * Class OpenAITest
 *
 * This class contains the unit tests for the Open AI connection functions.
 *
 * @package NJWMedia\Test
 * @since 1.0.0
 */
class OpenAITest extends TestCase {

	/**
	 * Test the njw_setup_open_ai function.
	 */
	public function test_njw_setup_open_ai() {
		// Call the function with a test API key.
		$result = njw_setup_open_ai( 'test_api_key' );

		// Assert the result.
		$this->assertEquals(
			[
				'Authorization' => 'Bearer test_api_key',
				'Content-Type'  => 'application/json',
			],
			$result
		);
	}

	/**
	 * Test the njw_get_image_alt_from_open_ai function.
	 */
	public function test_njw_get_image_alt_from_open_ai() {
		// Mock the njw_get_single_option function to return a secret key and a prompt.
		WP_Mock::userFunction(
			'njw_get_single_option',
			[
				'args'   => [ 'SECRET_KEY' ],
				'return' => 'secret_key',
			]
		);
		WP_Mock::userFunction(
			'njw_get_single_option',
			[
				'args'   => [ 'PROMPT', 'default_prompt' ],
				'return' => 'prompt',
			]
		);

		// Mock the njw_media_get_config function to return a default prompt.
		WP_Mock::userFunction(
			'njw_media_get_config',
			[
				'args'   => [ 'AI_PROMPT' ],
				'return' => 'default_prompt',
			]
		);

		// Mock the wp_remote_request function to return a response.
		WP_Mock::userFunction(
			'wp_remote_request',
			[
				'return' => [
					'body' => wp_json_encode(
						[
							'choices' => [
								[
									'message' => [
										'content' => 'alt_text',
									],
								],
							],
						]
					),
				],
			]
		);

		// Mock the is_wp_error function to return false.
		WP_Mock::userFunction(
			'is_wp_error',
			[
				'return' => false,
			]
		);

		// Mock the wp_remote_retrieve_body function to return a response body.
		WP_Mock::userFunction(
			'wp_remote_retrieve_body',
			[
				'return' => wp_json_encode(
					[
						'choices' => [
							[
								'message' => [
									'content' => 'alt_text',
								],
							],
						],
					]
				),
			]
		);

		// Call the function with a test image URL and slug.
		$result = njw_get_image_alt_from_open_ai( 'http://example.com/image.jpg', 'slug' );

		// Assert the result.
		$this->assertEquals( null, $result );
	}
}
