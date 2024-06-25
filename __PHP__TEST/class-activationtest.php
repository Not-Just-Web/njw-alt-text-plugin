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
 * Class ActivationTest
 *
 * This class represents the test case for the React Plugin Skeleton.
 * It extends the TestCase class, which is the base class for all test cases in PHPUnit.
 */
class ActivationTest extends TestCase {
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
		->with( 'njw-media-frontend-script', njw_media_get_config( 'PLUGIN_DIR_URL' ) . 'dist/frontend.js', $asset_file['dependencies'], $asset_file['version'], true )
		->andReturnUsing(
			function ( $arg1, $arg2, $arg3, $arg4, $arg5 ) use ( $asset_file ) {
				$this->assertEquals( 'njw-media-frontend-script', $arg1 );
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
				$this->assertEquals( 'njw-media-frontend-style', $arg1 );
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
	public function test_enqueue_media_enqueue_with_non_existent_asset_file() {
		njw_media_enqueue_frontend_assets();

		// No assertions needed, function should use default values.

		WP_Mock::assertActionsCalled();
	}


	/**
	 * Test njw_media_enqueue_admin_assets with a valid asset file.
	 *
	 * This test verifies that the `njw_media_enqueue_admin_assets` function correctly enqueues the frontend assets
	 * (JavaScript and CSS) with the correct dependencies and version.
	 */
	public function test_enqueue_admin_block_assets_with_valid_asset_file() {
		$asset_file_path = njw_media_get_config( 'PLUGIN_DIR_PATH' ) . 'dist/blocks.asset.php';
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
		->with( 'njw-media-admin-script', njw_media_get_config( 'PLUGIN_DIR_URL' ) . 'dist/block.js', $asset_file['dependencies'], $asset_file['version'], true )
		->andReturnUsing(
			function ( $arg1, $arg2, $arg3, $arg4, $arg5 ) use ( $asset_file ) {
				$this->assertEquals( 'njw-media-admin-script', $arg1 );
				$this->assertEquals( njw_media_get_config( 'PLUGIN_DIR_URL' ) . 'dist/block.js', $arg2 );
				$this->assertEquals( $asset_file['dependencies'], $arg3 );
				$this->assertEquals( $asset_file['version'], $arg4 );
				$this->assertEquals( true, $arg5 );
			}
		);

		WP_Mock::userFunction( 'wp_enqueue_style' )
		->once()
		->with( 'njw-media-admin-style', njw_media_get_config( 'PLUGIN_DIR_URL' ) . 'dist/block.css', $asset_file['dependencies'], $asset_file['version'] )
		->andReturnUsing(
			function ( $arg1, $arg2, $arg3, $arg4 ) use ( $asset_file ) {
				$this->assertEquals( 'njw-media-admin-style', $arg1 );
				$this->assertEquals( $asset_file['dependencies'], $arg3 );
				$this->assertEquals( $asset_file['version'], $arg4 );
				$this->assertEquals( njw_media_get_config( 'PLUGIN_DIR_URL' ) . 'dist/block.css', $arg2 );
			}
		);

		njw_media_enqueue_admin_assets();

		WP_Mock::assertActionsCalled();
	}

	/**
	 * Test njw_media_enqueue_admin_assets with a non-existent asset file.
	 *
	 * This test verifies that the `njw_media_enqueue_admin_assets` function uses default values when the asset file does not exist.
	 */
	public function test_enqueue_block_asset_with_non_existent_asset_file() {
		njw_media_enqueue_admin_assets();

		// No assertions needed, function should use default values.

		WP_Mock::assertActionsCalled();
	}

	/**
	 * Test njw_media_enqueue_cms_page_assets with a valid asset file.
	 *
	 * This test verifies that the `njw_media_enqueue_cms_page_assets` function correctly enqueues the frontend assets
	 * (JavaScript and CSS) with the correct dependencies and version.
	 */
	public function test_enqueue_cms_page_asset_with_valid_asset_file() {
		$asset_file_path = njw_media_get_config( 'PLUGIN_DIR_PATH' ) . 'dist/cms.asset.php';
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
		->with( 'njw-admin-cms-script', njw_media_get_config( 'PLUGIN_DIR_URL' ) . 'dist/cms.js', $asset_file['dependencies'], $asset_file['version'], true )
		->andReturnUsing(
			function ( $arg1, $arg2, $arg3, $arg4, $arg5 ) use ( $asset_file ) {
				$this->assertEquals( 'njw-admin-cms-script', $arg1 );
				$this->assertEquals( njw_media_get_config( 'PLUGIN_DIR_URL' ) . 'dist/cms.js', $arg2 );
				$this->assertEquals( $asset_file['dependencies'], $arg3 );
				$this->assertEquals( $asset_file['version'], $arg4 );
				$this->assertEquals( true, $arg5 );
			}
		);

		WP_Mock::userFunction( 'wp_enqueue_style' )
		->once()
		->with( 'njw-admin-cms-style', njw_media_get_config( 'PLUGIN_DIR_URL' ) . 'dist/cms.css', $asset_file['dependencies'], $asset_file['version'] )
		->andReturnUsing(
			function ( $arg1, $arg2, $arg3, $arg4 ) use ( $asset_file ) {
				$this->assertEquals( 'njw-admin-cms-style', $arg1 );
				$this->assertEquals( $asset_file['dependencies'], $arg3 );
				$this->assertEquals( $asset_file['version'], $arg4 );
				$this->assertEquals( njw_media_get_config( 'PLUGIN_DIR_URL' ) . 'dist/cms.css', $arg2 );
			}
		);

		njw_media_enqueue_cms_page_assets();

		WP_Mock::assertActionsCalled();
	}

	/**
	 * Test njw_media_enqueue_cms_page_assets with a non-existent asset file.
	 *
	 * This test verifies that the `njw_media_enqueue_cms_page_assets` function uses default values when the asset file does not exist.
	 */
	public function test_enqueue_cms_with_non_existent_asset_file() {
		njw_media_enqueue_cms_page_assets();

		// No assertions needed, function should use default values.

		WP_Mock::assertActionsCalled();
	}

	/**
	 * Test njw_media_enqueue_option_page_assets with a valid asset file.
	 *
	 * This test verifies that the `njw_media_enqueue_option_page_assets` function correctly enqueues the frontend assets
	 * (JavaScript and CSS) with the correct dependencies and version.
	 */
	public function test_enqueue_option_page_asset_with_valid_asset_file() {
		$asset_file_path = njw_media_get_config( 'PLUGIN_DIR_PATH' ) . 'dist/option-page.asset.php';
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
		->with( 'njw-media-option-script', njw_media_get_config( 'PLUGIN_DIR_URL' ) . 'dist/option-page.js', $asset_file['dependencies'], $asset_file['version'], true )
		->andReturnUsing(
			function ( $arg1, $arg2, $arg3, $arg4, $arg5 ) use ( $asset_file ) {
				$this->assertEquals( 'njw-media-option-script', $arg1 );
				$this->assertEquals( njw_media_get_config( 'PLUGIN_DIR_URL' ) . 'dist/option-page.js', $arg2 );
				$this->assertEquals( $asset_file['dependencies'], $arg3 );
				$this->assertEquals( $asset_file['version'], $arg4 );
				$this->assertEquals( true, $arg5 );
			}
		);

		WP_Mock::userFunction( 'wp_enqueue_style' )
		->once()
		->with( 'njw-media-option-style', njw_media_get_config( 'PLUGIN_DIR_URL' ) . 'dist/option-page.css', $asset_file['dependencies'], $asset_file['version'] )
		->andReturnUsing(
			function ( $arg1, $arg2, $arg3, $arg4 ) use ( $asset_file ) {
				$this->assertEquals( 'njw-media-option-style', $arg1 );
				$this->assertEquals( $asset_file['dependencies'], $arg3 );
				$this->assertEquals( $asset_file['version'], $arg4 );
				$this->assertEquals( njw_media_get_config( 'PLUGIN_DIR_URL' ) . 'dist/option-page.css', $arg2 );
			}
		);

		njw_media_enqueue_option_page_assets();

		WP_Mock::assertActionsCalled();
	}

	/**
	 * Test njw_media_enqueue_option_page_assets with a non-existent asset file.
	 *
	 * This test verifies that the `njw_media_enqueue_option_page_assets` function uses default values when the asset file does not exist.
	 */
	public function test_enqueue_option_with_non_existent_asset_file() {
		njw_media_enqueue_option_page_assets();

		// No assertions needed, function should use default values.

		WP_Mock::assertActionsCalled();
	}

	/**
	 * Test njw_media_enqueue_editor_page_assets with a valid asset file.
	 *
	 * This test verifies that the `njw_media_enqueue_editor_page_assets` function correctly enqueues the frontend assets
	 * (JavaScript and CSS) with the correct dependencies and version.
	 */
	public function test_enqueue_editor_page_asset_with_valid_asset_file() {
		$asset_file_path = njw_media_get_config( 'PLUGIN_DIR_PATH' ) . 'dist/editor.asset.php';
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
		->with( 'njw-media-option-script', njw_media_get_config( 'PLUGIN_DIR_URL' ) . 'dist/editor.js', $asset_file['dependencies'], $asset_file['version'], true )
		->andReturnUsing(
			function ( $arg1, $arg2, $arg3, $arg4, $arg5 ) use ( $asset_file ) {
				$this->assertEquals( 'njw-media-option-script', $arg1 );
				$this->assertEquals( njw_media_get_config( 'PLUGIN_DIR_URL' ) . 'dist/editor.js', $arg2 );
				$this->assertEquals( $asset_file['dependencies'], $arg3 );
				$this->assertEquals( $asset_file['version'], $arg4 );
				$this->assertEquals( true, $arg5 );
			}
		);

		WP_Mock::userFunction( 'wp_enqueue_style' )
		->once()
		->with( 'njw-media-option-style', njw_media_get_config( 'PLUGIN_DIR_URL' ) . 'dist/editor.css', $asset_file['dependencies'], $asset_file['version'] )
		->andReturnUsing(
			function ( $arg1, $arg2, $arg3, $arg4 ) use ( $asset_file ) {
				$this->assertEquals( 'njw-media-option-style', $arg1 );
				$this->assertEquals( $asset_file['dependencies'], $arg3 );
				$this->assertEquals( $asset_file['version'], $arg4 );
				$this->assertEquals( njw_media_get_config( 'PLUGIN_DIR_URL' ) . 'dist/editor.css', $arg2 );
			}
		);

		njw_media_enqueue_editor_page_assets();

		WP_Mock::assertActionsCalled();
	}

	/**
	 * Test njw_media_enqueue_editor_page_assets with a non-existent asset file.
	 *
	 * This test verifies that the `njw_media_enqueue_editor_page_assets` function uses default values when the asset file does not exist.
	 */
	public function test_enqueue_editor_with_non_existent_asset_file() {
		njw_media_enqueue_editor_page_assets();

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
					'NJW Media',
					'NJW Media',
					'edit_posts',  // This is the capability so that editor role can access this page.
					'njw-wp-media',
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

	/**
	 * Test the njw_media_upload_mimes function.
	 *
	 * This function tests the behavior of the njw_media_upload_mimes function by calling it with a sample array
	 * and checking that the csv mime type was added to the result.
	 *
	 * @since 1.0.0
	 */
	public function test_njw_media_upload_mimes() {
		// Call the function with a sample array
		$result = njw_media_upload_mimes(['jpg' => 'image/jpeg']);

		// Check that the csv mime type was added
		$this->assertEquals(['jpg' => 'image/jpeg', 'csv' => 'text/csv'], $result);
	}

	/**
	 * Test the njw_media_plugin_activation function.
	 *
	 * This function tests the behavior of the njw_media_plugin_activation function by mocking the
	 * njw_create_link_log_table and njw_create_media_log_table functions, calling the function,
	 * and checking that the mocked functions were called.
	 *
	 * @since 1.0.0
	 */
	public function test_njw_media_plugin_activation() {
		// Mock the njw_create_link_log_table and njw_create_media_log_table functions
		WP_Mock::userFunction('njw_create_link_log_table');
		WP_Mock::userFunction('njw_create_media_log_table');

		// Call the function
		njw_media_plugin_activation();

		// Check that the functions were called
		$this->assertConditionsMet();
	}

}
