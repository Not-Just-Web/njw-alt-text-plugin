<?php
/**
 * @file
 * Plugin Skeleton test class.
 *
 * This file contains the unit tests for the `includes/helpers.php` file.
 *
 * @package NJWMedia\Test
 */

namespace NjwMedia\Tests;

use WP_Mock\Tools\TestCase;
use WP_Mock;
use stdClass;

/**
 * Class HelpersTest
 *
 * This class contains unit tests for the helper functions in the njwmedia plugin.
 */
class HelpersTest extends TestCase {

	/**
	 * Set up the test case.
	 */
	public function setUp(): void {
		WP_Mock::setUp();
	}

	/**
	 * Tear down the test case.
	 */
	public function tearDown(): void {
		WP_Mock::tearDown();
	}

	/**
	 * Test the njw_media_json_validate_and_return function.
	 *
	 * @return void
	 */
	public function test_njw_media_json_validate_and_return() {
		$expected_object       = new stdClass();
		$expected_object->test = 'value';
		$this->assertEquals( $expected_object, njw_media_json_validate_and_return( '{"test": "value"}' ) );
		$this->assertEquals( 'Invalid JSON', njw_media_json_validate_and_return( 'Invalid JSON' ) );
	}

	/**
	 * Test the njw_generate_random_string function.
	 *
	 * @return void
	 */
	public function test_njw_generate_random_string() {
		WP_Mock::userFunction(
			'wp_generate_password',
			[
				'args'   => [ 12, false, false ],
				'return' => 'randomstring',
			]
		);

		$this->assertEquals( 'randomstring', njw_generate_random_string() );
	}

	/**
	 * Test the njw_media_api_config function.
	 *
	 * @return void
	 */
	public function test_njw_media_api_config() {
		WP_Mock::userFunction( 'get_site_url', [ 'return' => 'http://example.com' ] );
		WP_Mock::userFunction( 'njw_media_get_config', [ 'return' => 'config' ] );
		WP_Mock::userFunction( 'get_option', [ 'return' => [] ] );

		$this->assertIsArray( njw_media_api_config() );
	}

	/**
	 * Test the njw_run_cli_command function.
	 *
	 * @return void
	 */
	public function test_njw_run_cli_command() {
		$text    = 'Hello, World!';
		$command = 'echo ' . $text;
		// Run the WP CLI command.
		// Alternative (using temporary variables).
		$command_op  = njw_run_cli_command( $command );
		$output      = array_key_exists( 'output', $command_op ) ? $command_op['output'] : [];
		$return_code = array_key_exists( 'return_code', $command_op ) ? $command_op['return_code'] : [];
		$this->assertEquals( [ $text ], $output );
		$this->assertEquals( 0, $return_code );
	}

	/**
	 * Test the njw_media_get_page function.
	 *
	 * @return void
	 */
	public function test_njw_media_get_page() {
		$_GET['page'] = 'test_page';

		$this->assertEquals( 'test_page', njw_media_get_page() );
	}

	/**
	 * Test the njw_get_single_option function.
	 *
	 * @return void
	 */
	public function test_njw_get_single_option() {
		WP_Mock::userFunction(
			'wp_generate_password',
			[
				'args'   => [ 12, false, false ],
				'return' => 'mock_access_key',
			]
		);
		$this->assertEquals( 'mock_access_key', njw_get_single_option( 'ACCESS_KEY', 'default' ) );
		$this->assertEquals( 'default', njw_get_single_option( 'non_existent_key', 'default' ) );
	}

	/**
	 * Test the njw_media_log function.
	 *
	 * @return void
	 */
	public function test_njw_media_log() {
		define( 'WP_DEBUG', true );

		njw_media_log( 'Test message' );
		$this->assertConditionsMet();
	}
}
