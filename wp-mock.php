// Bootstrap WP_Mock to initialize built-in features.
WP_Mock::bootstrap();

WP_Mock::userFunction(
	'plugin_dir_path',
	[
		'return' => __DIR__ . '/',
	]
);

WP_Mock::userFunction(
	'plugin_dir_url',
	[
		'return' => '/wp-content/plugins/njw-alt-text-plugin/',
	]
);

$wpdb         = Mockery::mock( 'overload:wpdb' );
$wpdb->prefix = 'wp_';
$wpdb->shouldReceive( 'get_charset_collate' )
	->andReturn( 'utf8mb4_unicode_ci' ); // Replace with the value you want the method to return
$wpdb->shouldReceive( 'prepare' )
	->andReturnUsing(
		function ( $query, $args ) {
			// Here you can define what the prepare method should return.
			// For example, you can just return the query string:
			return $query;
		}
	);
$wpdb->shouldReceive( 'get_results' )
	->andReturnUsing(
		function ( $query, $output_type ) {
			// Here you can define what the get_results method should return.
			// For example, you can just return an empty array:
			return [];
		}
	);
$WP_Error->shouldReceive( 'get_error_message' )
	->andReturn( 'Error message' ); // Replace with the value you want the method to return

WP_Mock::userFunction(
	'wp_json_encode',
	[
		'return' => '{}', // Replace with the value you want the function to return
	]
);

WP_Mock::userFunction(
	'wp_remote_request',
	[
		'return' => [], // Replace with the value you want the function to return
	]
);

// Mock the wp_enqueue_media function
WP_Mock::userFunction(
	'wp_enqueue_media',
	[
		'return' => true,
	]
);

// Mock the get_site_url function
WP_Mock::userFunction(
	'get_site_url',
	[
		'return' => 'http://your-site-url.com', // Replace with the value you want the function to return
	]
);

// Mock the dbDelta function
WP_Mock::userFunction(
	'dbDelta',
	[
		'return' => true,
	]
);

// Mock the wp_cache_get function
WP_Mock::userFunction(
	'wp_cache_get',
	[
		'return' => 'mock_cache_value', // Replace with the value you want the function to return
	]
);

// Mock the wp_generate_password function
WP_Mock::userFunction(
	'wp_generate_password',
	[
		'return' => 'mock_password', // Replace with the value you want the function to return
	]
);

// Mock the get_option function
WP_Mock::userFunction(
	'get_option',
	[
		'return' => 'mock_option_value', // Replace with the value you want the function to return
	]
);

// Mock the wp_create_nonce function
WP_Mock::userFunction(
	'wp_create_nonce',
	[
		'return' => 'mock_nonce', // Replace with the value you want the function to return
	]
);

WP_Mock::userFunction(
	'register_activation_hook',
	[
		'return' => true,
	]
);
