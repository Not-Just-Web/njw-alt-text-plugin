<?php
/**
 * This file contains unit tests for the config functions in the NJW Plugin Skeleton application.
 *
 * @package NJWMedia\Test
 * @since 1.0.0
 */

namespace NJWMEDIA\Test;

use WP_Mock\Tools\TestCase;
use WP_Mock;

/**
 * Class ConfigTest.
 *
 * @package njw-alt-media-text
 */
class ConfigTest extends TestCase {

	/**
	 * Set up WP_Mock
	 */
	public function setUp(): void {
		WP_Mock::setUp();
	}

	/**
	 * Tear down WP_Mock
	 */
	public function tearDown(): void {
		WP_Mock::tearDown();
	}

	/**
	 * Test the njw_media_define_constant_with_prefix function.
	 *
	 * This function tests the behavior of the njw_media_define_constant_with_prefix function.
	 * It checks if the constant is defined with the correct prefix and if the value is set correctly.
	 *
	 * @return void
	 */
	public function test_njw_media_define_constant_with_prefix() {
		$constant_name = 'TEST_CONSTANT';
		$value         = 'test_value';

		njw_media_define_constant_with_prefix( $constant_name, $value );

		$this->assertTrue( defined( 'NJW_MEDIA_' . $constant_name ) );
		$this->assertEquals( $value, constant( 'NJW_MEDIA_' . $constant_name ) );
	}

	/**
	 * Test the njw_media_get_config function.
	 *
	 * This function tests the behavior of the njw_media_get_config function.
	 * It checks if the function returns the correct value for a given constant name.
	 *
	 * @return void
	 */
	public function test_njw_media_get_config() {
		$constant_name = 'TEST_ANOTHER';
		$value         = 'test_value';

		// Define the constant.
		njw_media_define_constant_with_prefix( $constant_name, $value );

		// Call the function and check the return value.
		$this->assertEquals( $value, njw_media_get_config( $constant_name ) );
	}
}
