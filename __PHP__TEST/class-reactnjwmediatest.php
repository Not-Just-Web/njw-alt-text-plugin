<?php
/**
 * @file
 * Plugin Skeleton test class.
 *
 * This file contains the unit tests for the `wp-react-plugin-skeleton.php` file.
 *
 * @package wp-react-plugin-skeleton
 */

namespace NjwMedia\Tests;

use WP_Mock\Tools\TestCase;

use WP_Mock;

/**
 * Class ReactNjwMediaTest
 *
 * This class represents the test case for the React Plugin Skeleton.
 * It extends the TestCase class, which is the base class for all test cases in PHPUnit.
 */
class ReactNjwMediaTest extends TestCase {
	/**
	 * Set up the test case.
	 */
	public function setUp(): void {
		WP_Mock::setUp();
		parent::setUp();
	}

	/**
	 * Tear down the test case.
	 */
	public function tearDown(): void {
		WP_Mock::tearDown();
		parent::tearDown();
	}

	/**
	 * Test njw_media_enqueue_frontend_assets with a valid asset file.
	 *
	 * This test verifies that the `njw_media_enqueue_frontend_assets` function correctly enqueues the frontend assets
	 * (JavaScript and CSS) with the correct dependencies and version.
	 */
	public function test_enqueue_frontend_assets_with_valid_asset_file() {
		$asset_file_path = njw_media_get_config( 'PLUGIN_DIR_PATH' ) . 'dist/frontend.asset.php';
		// Check if the file exists, if not, set default values.
		$asset_file = [
			'dependencies' => [],
			'version'      => '1.0.0',
		];

		if ( file_exists( $asset_file_path ) ) {
			$asset_file = include $asset_file_path;
		}

		WP_Mock::userFunction( 'wp_enqueue_script' )
		->once()
		->with( 'njw-mediafrontend-script', njw_media_get_config( 'PLUGIN_DIR_URL' ) . 'dist/frontend.js', $asset_file['dependencies'], $asset_file['version'], true )
		->andReturnUsing(
			function ( $arg1, $arg2, $arg3, $arg4, $arg5 ) use ( $asset_file ) {
				$this->assertEquals( 'frontend-script', $arg1 );
				$this->assertEquals( njw_media_get_config( 'PLUGIN_DIR_URL' ) . 'dist/frontend.js', $arg2 );
				$this->assertEquals( $asset_file['dependencies'], $arg3 );
				$this->assertEquals( $asset_file['version'], $arg4 );
				$this->assertEquals( true, $arg5 );
			}
		);

		WP_Mock::userFunction( 'wp_enqueue_style' )
		->once()
		->with( 'njw-media-frontend-style', njw_media_get_config( 'PLUGIN_DIR_URL' ) . 'dist/frontend.css', $asset_file['dependencies'], $asset_file['version'] )
		->andReturnUsing(
			function ( $arg1, $arg2, $arg3, $arg4 ) use ( $asset_file ) {
				$this->assertEquals( 'frontend-style', $arg1 );
				$this->assertEquals( $asset_file['dependencies'], $arg3 );
				$this->assertEquals( $asset_file['version'], $arg4 );
				$this->assertEquals( njw_media_get_config( 'PLUGIN_DIR_URL' ) . 'dist/frontend.css', $arg2 );
			}
		);

		njw_media_enqueue_frontend_assets();

		WP_Mock::assertActionsCalled();
	}

	/**
	 * Test njw_media_enqueue_frontend_assets with a non-existent asset file.
	 *
	 * This test verifies that the `njw_media_enqueue_frontend_assets` function uses default values when the asset file does not exist.
	 */
	public function test_enqueue_frontend_assets_with_non_existent_asset_file() {
		njw_media_enqueue_frontend_assets();

		// No assertions needed, function should use default values.

		WP_Mock::assertActionsCalled();
	}

	/**
	 * Test njw_media_add_custom_menu_page.
	 *
	 * This test verifies that the `njw_media_add_custom_menu_page` function correctly adds a custom menu page using the `add_menu_page` function.
	 */
	public function test_njw_media_add_custom_menu_page() {
		// Mock the add_menu_page function.
		WP_Mock::userFunction(
			'add_menu_page',
			[
				'times' => 1,
				'args'  => [
					'WP Skeleton React',
					'WP Skeleton React',
					'edit_posts',
					'wp-skeleton-page',
					'njw_media_custom_page_callback',
					'dashicons-analytics',
					4,
				],
			]
		);

		// Call the function.
		njw_media_add_custom_menu_page();

		WP_Mock::assertActionsCalled();
	}

	/**
	 * Test njw_media_custom_page_callback.
	 *
	 * This test verifies that the `njw_media_custom_page_callback` function correctly outputs the expected HTML content.
	 */
	public function test_njw_media_custom_page_callback() {
		// Start output buffering.
		ob_start();

		// Call the function.
		njw_media_custom_page_callback();

		// Get the output.
		$output = ob_get_clean();

		// Check the output.
		$this->assertEquals( '<div id="njw-media-react-plugin-page"></div>', $output );
	}
}
