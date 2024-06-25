<?php
/**
 * @file
 * Plugin Skeleton test class.
 *
 * This file contains the unit tests for the `includes/activation.php` file.
 *
 * @package NJWMedia\Test
 */

namespace NjwMedia\Tests;

use WP_Mock\Tools\TestCase;
use WP_Mock;

/**
 * Class ApiTest
 *
 * This class contains unit tests for the njw-alt-text-plugin.
 */
class ApiTest extends TestCase {

	/**
	 * Set up the test case.
	 */
	public function setUp(): void {
		WP_Mock::setUp();
		WP_Mock::userFunction(
			'njw_media_save_log',
			[
				'args'   => [ \Mockery::type( 'array' ), \Mockery::type( 'string' ), \Mockery::type( 'string' ) ],
				'return' => [
					'abc' => 'def',
					'def' => 'ghi',
				],
			]
		);

		WP_Mock::userFunction( 'wp_json_send' )
		->with( WP_Mock\Functions::type( 'array' ), 200 )
		->andReturn( true );

		parent::setUp();
	}

	/**
	 * Tear down the test case.
	 */
	public function tearDown(): void {
		WP_Mock::tearDown();
		\Mockery::close();
		parent::tearDown();
	}

	/**
	 * Test that njw_access_to_editor correctly checks the access key and user capabilities.
	 *
	 * @return void
	 */
	public function test_njw_access_to_editor() {
		$request = \Mockery::mock( 'WP_REST_Request' );
		$request->shouldReceive( 'get_header' )
			->with( 'access-key' )
			->andReturn( 'test_key' );

		$request->shouldReceive( 'get_param' )
			->with( 'access_key' )
			->andReturn( 'test_key' );

		$request->shouldReceive( 'set_header' )
			->andReturn( $request );
		$request->shouldReceive( 'set_param' )
			->andReturn( $request );

		WP_Mock::userFunction(
			'current_user_can',
			[
				'args'  => 'edit_posts',
				'times' => 0, // This function should not be called because the access key is correct.
			]
		);

		$this->assertFalse( njw_access_to_editor( $request ) );

		$request->set_header( 'access-key', 'wrong_key' );
		$request->set_param( 'access_key', 'wrong_key' );

		WP_Mock::userFunction(
			'current_user_can',
			[
				'args'   => 'edit_posts',
				'return' => true,
			]
		);

		$this->assertFalse( njw_access_to_editor( $request ) );

		WP_Mock::userFunction(
			'current_user_can',
			[
				'args'   => 'edit_posts',
				'return' => false,
			]
		);

		$this->assertFalse( njw_access_to_editor( $request ) );
	}

	/**
	 * Test the njw_media_search_replace function.
	 */
	public function test_njw_media_search_replace() {
		$request = \Mockery::mock( 'WP_REST_Request' );
		$request->shouldReceive( 'get_param' )
			->with( 'search' )
			->andReturn( 'old_link' );
		$request->shouldReceive( 'get_param' )
			->with( 'replace' )
			->andReturn( 'new_link' );
		// Mock the njw_run_cli_command function.

		$mock = \Mockery::mock( 'alias:njw_run_cli_command' );
		$mock->shouldReceive( 'njw_run_cli_command' )
			->with( 'echo \'Hello World\'' )
			->andReturn(
				[
					'output'      => [ 'Hello World' ],
					'return_code' => 0,
				]
			);

		$result = njw_media_search_replace( $request );

		$this->assertNotTrue( $result );
	}

	/**
	 * Test the njw_update_media_alt_text function.
	 */
	public function test_njw_update_media_alt_text() {
		$request = \Mockery::mock( 'WP_REST_Request' );
		$request->shouldReceive( 'get_param' )
			->with( 'media_id' )
			->once()
			->andReturn( 1 );

		$request->shouldReceive( 'get_param' )
			->with( 'alt_text' )
			->once()
			->andReturn( 'new_alt_text' );

		WP_Mock::userFunction( 'current_user_can' )
			->with( 'manage_options' )
			->once()
			->andReturn( true );

		WP_Mock::userFunction( 'get_post_meta' )
			->once()
			->with( 1, '_wp_attachment_image_alt', true )
			->andReturn( 'old_alt_text' );

		WP_Mock::userFunction( 'update_post_meta' )
			->once()
			->with( 1, '_wp_attachment_image_alt', 'new_alt_text' )
			->andReturn( true );

		WP_Mock::userFunction( 'wp_get_attachment_url' )
			->once()
			->with( 1 )
			->andReturn( 'http://example.com/image.jpg' );

		$result = njw_update_media_alt_text( $request );

		$this->assertNotTrue( $result );
	}

	/**
	 * Test the njw_media_get_alt_text_from_open_ai function.
	 */
	public function test_njw_media_get_alt_text_from_open_ai() {
		$request = \Mockery::mock( 'WP_REST_Request' );
		$request->shouldReceive( 'get_param' )
			->with( 'media_id' )
			->andReturn( 1 );
		$request->shouldReceive( 'get_param' )
			->with( 'mediaUrl' )
			->andReturn( 'http://example.com/image.jpg' );

		WP_Mock::userFunction( 'wp_get_attachment_url' )
			->once()
			->with( 1 )
			->andReturn( 'http://example.com/image.jpg' );

		WP_Mock::userFunction( 'get_post_meta' )
			->with( 1, '_wp_attachment_image_alt', true )
			->andReturn( 'old_alt_text' );

		WP_Mock::userFunction( 'njw_get_image_alt_from_open_ai' )
			->with( 'http://example.com/image.jpg' )
			->andReturn( 'new_alt_text' );

		$result = njw_media_get_alt_text_from_open_ai( $request );

		$this->assertNotTrue( $result );
	}
}
