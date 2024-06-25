<?php
/**
 * Unit tests for the functions in the database.php file of the NJW Plugin Skeleton application.
 *
 * @package NJWMedia\Test
 */

namespace NJWMedia\Test;

use WP_Mock;
use WP_Mock\Tools\TestCase;

/**
 * Class DatabaseTest.
 *
 * @package njw-alt-media-text
 */
class DatabaseTest extends TestCase {

	/**
	 * Set up the test environment before each test.
	 */
	public function setUp(): void {
		WP_Mock::setUp();
	}

	/**
	 * Clean up the test environment after each test.
	 */
	public function tearDown(): void {
		WP_Mock::tearDown();
	}

	/**
	 * Test the njw_query_db_log_table function.
	 *
	 * @return void
	 */
	public function test_njw_query_db_log_table() {
		// Mock the njw_get_table_name function to return 'link_log'.
		WP_Mock::userFunction(
			'njw_get_table_name',
			[
				'args'   => [ 'LINK' ],
				'return' => 'link_log',
			]
		);

		// Call the function with test data.
		$result = njw_query_db_log_table( "id = '123'" );

		// Assert the result.
		$this->assertEquals(
			[
				'query'       => 'Your desired SQL string',
				'output_type' => 'OBJECT',
				'results'     => 'mock_results',
			],
			$result
		);
	}

	/**
	 * Test the njw_log_table_column_name function.
	 *
	 * @return void
	 */
	public function test_njw_log_table_column_name() {
		// Call the function with 'LINK' and assert the result.
		$result = njw_log_table_column_name( 'LINK' );
		$this->assertEquals( [ 'id', 'old_link', 'new_link' ], $result );

		// Call the function with 'MEDIA' and assert the result.
		$result = njw_log_table_column_name( 'MEDIA' );
		$this->assertEquals( [ 'id', 'media_id', 'media_url', 'generated_alt_text', 'old_alt' ], $result );

		// Call the function with an unknown log type and assert the result.
		$result = njw_log_table_column_name( 'UNKNOWN' );
		$this->assertEquals( [], $result );
	}


	/**
	 * Test the njw_media_save_log function.
	 *
	 * @return void
	 */
	public function test_njw_media_save_log() {

		// Mock the njw_get_table_name function to return 'link_log'.
		WP_Mock::userFunction(
			'njw_get_table_name',
			[
				'args'   => [ 'LINK' ],
				'return' => 'link_log',
			]
		);

		// Mock the njw_log_table_column_name function to return an array of column names.
		WP_Mock::userFunction(
			'njw_log_table_column_name',
			[
				'args'   => [ 'LINK' ],
				'return' => [ 'id', 'old_link', 'new_link' ],
			]
		);

		// Mock the njw_query_db_log_table function to return null.
		WP_Mock::userFunction(
			'njw_query_db_log_table',
			[
				'args'   => [ " unique_identifier =  '123' " ],
				'return' => null,
			]
		);

		// Call the function with test data.
		$result = njw_media_save_log(
			[
				'id'       => '123',
				'old_link' => 'http://example.com/old',
				'new_link' => 'http://example.com/new',
			],
			'id',
			'LINK'
		);

		// Assert the result.
		$this->assertEquals(
			[
				'id'         => '123',
				'success'    => true,
				'result'     => [
					'query'       => 'Your desired SQL string',
					'output_type' => 'OBJECT',
					'results'     => 'mock_results',
				],
				'store_data' => [
					'id'       => '123',
					'old_link' => 'http://example.com/old',
					'new_link' => 'http://example.com/new',
				],
			],
			$result
		);
	}
}
