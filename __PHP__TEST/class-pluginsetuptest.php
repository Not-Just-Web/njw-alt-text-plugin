<?php
/**
 * @file
 * Plugin Skeleton test class.
 *
 * This file contains the unit tests for the `njw-alt-text-plugin.php` file.
 *
 * @package NJWMedia\Test
 */

namespace NjwMedia\Tests;

use WP_Mock\Tools\TestCase;
use WP_Mock;

/**
 * Represents a test case for the PluginSetup class.
 *
 * @package njw-alt-text-plugin
 * @subpackage __PHP__TEST
 */
class PluginSetupTest extends TestCase {

	/**
	 * Set up the test environment before each test method is run.
	 */
	public function setUp(): void {
		WP_Mock::setUp();
	}

	/**
	 * Clean up the test environment after each test method is run.
	 */
	public function tearDown(): void {
		WP_Mock::tearDown();
	}

	/**
	 * Test if the constants are defined.
	 */
	public function testConstantsAreDefined() {
		$constant_name_prefix = 'NJW_MEDIA_';
		$this->assertTrue( defined( $constant_name_prefix . 'PLUGIN_DIR_PATH' ) );
		$this->assertTrue( defined( $constant_name_prefix . 'PLUGIN_DIR_URL' ) );
		$this->assertTrue( defined( $constant_name_prefix . 'PLUGIN_FILE_NAME' ) );
	}
}
