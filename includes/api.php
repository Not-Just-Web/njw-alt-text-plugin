<?php
/**
 * This file contains api endpoint used in the Plugin Skeleton application.
 *
 * @package NjwMedia\API
 * @since 1.0.0
 */

/**
 * REST API Proxy to consume gateway service.
 * reason: CORS cannot be enabled on the gateway service API.
 * URL: <domain>/njw-media/api-proxy/<endpoint>.
 * accepts up to 3 nested paths for now. Just add more if you need more.
 *
 * Also create user query endpoint for search user with meta key.
 */
add_action(
	'rest_api_init',
	function () {
		// accept 1 path.
		register_rest_route(
			njw_media_get_config( 'NAMESPACE' ),
			njw_media_get_config( 'PROXY_ROUTE' ) . '/(?P<one>[^/]+)',
			[
				'methods'             => [ 'GET', 'POST', 'PUT', 'DELETE' ],
				'callback'            => 'njw_media_api_proxy',
				'permission_callback' => '__return_true',
			]
		);
		// accept 2 paths.
		register_rest_route(
			njw_media_get_config( 'NAMESPACE' ),
			njw_media_get_config( 'PROXY_ROUTE' ) . '/(?P<one>[^/]+)/(?P<two>[^/]+)',
			[
				'methods'             => [ 'GET', 'POST', 'PUT', 'DELETE' ],
				'callback'            => 'njw_media_api_proxy',
				'permission_callback' => '__return_true',
			]
		);
		// accept 3 paths.
		register_rest_route(
			njw_media_get_config( 'NAMESPACE' ),
			njw_media_get_config( 'PROXY_ROUTE' ) . '/(?P<one>[^/]+)/(?P<two>[^/]+)/(?P<three>[^/]+)',
			[
				'methods'             => [ 'GET', 'POST', 'PUT', 'DELETE' ],
				'callback'            => 'njw_media_api_proxy',
				'permission_callback' => '__return_true',
			]
		);
		// accept 4 paths.
		register_rest_route(
			njw_media_get_config( 'NAMESPACE' ),
			njw_media_get_config( 'PROXY_ROUTE' ) . '/(?P<one>[^/]+)/(?P<two>[^/]+)/(?P<three>[^/]+)/(?P<four>[^/]+)',
			[
				'methods'             => [ 'GET', 'POST', 'PUT', 'DELETE' ],
				'callback'            => 'njw_media_api_proxy',
				'permission_callback' => '__return_true',
			]
		);
		// accept 5 paths.
		register_rest_route(
			njw_media_get_config( 'NAMESPACE' ),
			njw_media_get_config( 'PROXY_ROUTE' ) . '/(?P<one>[^/]+)/(?P<two>[^/]+)/(?P<three>[^/]+)/(?P<four>[^/]+)/(?P<five>[^/]+)',
			[
				'methods'             => [ 'GET', 'POST', 'PUT', 'DELETE' ],
				'callback'            => 'njw_media_api_proxy',
				'permission_callback' => '__return_true',
			]
		);

		/**
		 * API endpoint for running WP CLI search replace command.
		 * Only accessible with admin login authorization header.
		 */
		register_rest_route(
			njw_media_get_config( 'NAMESPACE' ),
			njw_media_get_config( 'ROUTE' ) . '/search-replace',
			[
				'methods'             => [ 'POST' ],
				'callback'            => 'njw_media_search_replace',
				'permission_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			]
		);

		register_rest_route(
			njw_media_get_config( 'NAMESPACE' ),
			njw_media_get_config( 'ROUTE' ) . '/alt-meta-update',
			[
				'methods'             => 'POST',
				'callback'            => 'njw_update_media_alt_text',
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			]
		);

		register_rest_route(
			njw_media_get_config( 'NAMESPACE' ),
			njw_media_get_config( 'ROUTE' ) . '/openai/generate/(?P<media_id>\d+)',
			[
				'methods'             => 'GET',
				'callback'            => 'njw_media_get_alt_text_from_open_ai',
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			]
		);
	}
);

/**
 * Get the gateway endpoint from the proxy url.
 * the endpoint should be everything after /aremedia-trial-team/api-proxy/.
 * eg: if URL is https://beautyheaven.com.au/wp-json/aremedia-trial-team/api-proxy/trials/23, then endpoint will be "/trials/23".
 *
 * @param string $proxy_url The URL of the proxy.
 * @return string The endpoint of the proxy URL.
 */
function njw_media_gateway_endpoint( $proxy_url ) {
	$path     = '/' . njw_media_get_config( 'NAMESPACE' ) . '/' . njw_media_get_config( 'ROUTE' ) . '/';
	$endpoint = strstr( $proxy_url, $path );
	return str_replace( $path, '', $endpoint );
}

/**
 * Proxies the API request to the Trackonomics API.
 *
 * This function takes a request object and sends it to the Trackonomics API for processing.
 *
 * @param mixed $req The request object to be sent to the API.
 * @return mixed The response from the Trackonomics API.
 * @throws Exception If there is an error with the API request.
 */
function njw_media_api_proxy( $req ) {

	try {
		$method            = $req->get_method();
		$njw_media_options = njw_media_api_config();
		$njw_media_api     = array_key_exists( 'API_PROXY_URL', $njw_media_options ) ? $njw_media_options['API_PROXY_URL'] : '';
		$njw_access_key    = array_key_exists( 'API_ACCESS_KEY', $njw_media_options ) ? $njw_media_options['API_ACCESS_KEY'] : '898f8bcc906c497d8ed8a224b17ff2d8';
		$headers           = [
			'access-key: ' . $njw_access_key,
			'Content-Type: application/json',
			'Cache-Control: no-cache',
		];

		// build the URL.
		$endpoint = njw_media_gateway_endpoint( $req->get_route() );
		$url      = $njw_media_api . '/' . $endpoint;

		if ( $method === 'GET' || $method === 'DELETE' ) {
			$body = $req->get_query_params();
			$url  = ! empty( $body ) ? $url . '?' . http_build_query( $body ) : $url;

		} else {
			$body = njw_media_json_validate_and_return( $req->get_body(), true );
		}

		$client = curl_init(); //phpcs:ignore

		// set the URL.
		curl_setopt( $client, CURLOPT_URL, $url ); //phpcs:ignore
		// set payload.
		$body = wp_json_encode( $body );
		curl_setopt( $client, CURLOPT_POSTFIELDS, $body ); //phpcs:ignore
		// set headers.
		curl_setopt( $client, CURLOPT_HTTPHEADER, $headers ); //phpcs:ignore
		curl_setopt( $client, CURLOPT_CUSTOMREQUEST, $method ); //phpcs:ignore
		// set so content is returned as a variable.
		curl_setopt( $client, CURLOPT_RETURNTRANSFER, true ); //phpcs:ignore

		// Curl - Version 4.
		curl_setopt( $client, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 ); //phpcs:ignore

		$response    = curl_exec( $client ); //phpcs:ignore
		$http_status = curl_getinfo( $client, CURLINFO_HTTP_CODE ); //phpcs:ignore

		if ( $http_status !== 200 ) {
			if ( $response ) {
				$err_response = njw_media_json_validate_and_return( $response );
				if ( is_array( $err_response ) ) {
					$err_msg = array_key_exists( 'message', $err_response ) ? $err_response['message'] : 'Sorry, something went wrong. Please try again later.';
					throw new Exception( $err_msg, $http_status );
				} elseif ( is_object( $err_response ) ) {
					$err_msg = $err_response->message;
					throw new Exception( $err_msg, $http_status );
				} else {
					wp_send_json( $err_response, $http_status );
				}
			} else {
				throw new Exception( 'Error in API' );
			}
		}
		// response.
		$result = new WP_REST_Response( njw_media_json_validate_and_return( $response ), $http_status );

		// Set headers.
		$result->header( 'Cache-Control', 'no-cache' );

		return $result;

	} catch ( Exception $err ) {
		wp_send_json( $err->getMessage(), $err->getCode() );
	}
}


/**
 * Callback function for the search replace API endpoint.
 * Runs the WP CLI search replace command.
 *
 * @param WP_REST_Request $request The REST request object.
 * @return WP_REST_Response The REST response object.
 */
function njw_media_search_replace( $request ) {
	$search  = $request->get_param( 'search' );
	$replace = $request->get_param( 'replace' );

	if ( empty( $search ) || empty( $replace ) ) {
		return new WP_Error( 'invalid_params', 'Search and replace parameters are required.', [ 'status' => 400 ] );
	}

	$command = "wp search-replace '{$search}' '{$replace}' --all-tables --precise --recurse-objects --skip-columns=guid";

	// Run the WP CLI command.
	// phpcs:ignore
	exec( $command, $output, $return_code );

	$alt_response = [
		'old_link' => $search,
		'new_link' => $replace,
	];

	if ( $return_code !== 0 ) {
		return new WP_Error( 'command_failed', 'Failed to run WP CLI search replace command.', [ 'status' => 500 ] );
	}

	njw_media_save_log( $alt_text_response, 'old_link', 'LINK' );

	return wp_send_json(
		[
			...$alt_response,
			'output' => $output,
		],
		200
	);
}


/**
 * Callback function for updating media alt text.
 *
 * @param WP_REST_Request $request The REST request object.
 * @return WP_REST_Response The REST response object.
 */
function njw_update_media_alt_text( $request ) {
	// Check if user is admin.
	if ( ! current_user_can( 'manage_options' ) ) {
		return new WP_Error( 'unauthorized', 'Unauthorized access.', [ 'status' => 401 ] );
	}

	// Get media ID and alt text from request.
	$media_id = $request->get_param( 'media_id' );
	$alt_text = $request->get_param( 'alt_text' );

	// Check if media ID and alt text are provided.
	if ( empty( $media_id ) || empty( $alt_text ) ) {
		return new WP_Error( 'invalid_params', 'Media ID and alt text are required.', [ 'status' => 400 ] );
	}
	$prev_alt_text = get_post_meta( $media_id, '_wp_attachment_image_alt', true );
	// Update media alt text.
	$updated = update_post_meta( $media_id, '_wp_attachment_image_alt', $alt_text );

	$media_url = wp_get_attachment_url( $media_id );

	$alt_text_response = [
		'media_id'     => $media_id,
		'media_url'    => $media_url,
		'old_alt'      => $prev_alt_text,
		'new_alt_text' => $alt_text,
	];

	njw_media_save_log( $alt_text_response, 'media_id', 'MEDIA' );

	if ( $updated ) {
		return wp_json_send( $alt_text_response, 200 );
	} else {
		return new WP_Error( 'update_failed', 'Failed to update media alt text.', [ 'status' => 500 ] );
	}
}

/**
 * Get alt text from OpenAI.
 *
 * @since 1.0.0
 *
 * @param WP_REST_Request $request The WordPress REST request object.
 *
 * @return string|WP_Error The generated alt text or an error.
 */
function njw_media_get_alt_text_from_open_ai( WP_REST_Request $request ) {
	$media_id = $request->get_param( 'media_id' );

	if ( empty( $open_ai_key ) ) {
		return new WP_Error( 'no_openai_key', 'Open AI Key not found in headers', [ 'status' => 401 ] );
	}

	// Get media URL using media ID.
	$media_url     = wp_get_attachment_url( $media_id );
	$prev_alt_text = get_post_meta( $media_id, '_wp_attachment_image_alt', true );

	if ( empty( $media_url ) ) {
		return new WP_Error( 'no_media_url', 'Media URL not found for the given media ID', [ 'status' => 404 ] );
	}

	$alt_text = njw_get_image_alt_from_open_ai( $media_url );

	if ( empty( $alt_text ) ) {
		return new WP_Error( 'no_alt_text', "Wasn't able to generate alt text for the image.", [ 'status' => 404 ] );
	}

	$alt_text_response = [
		'media_id'     => $media_id,
		'media_url'    => $media_url,
		'old_alt'      => $prev_alt_text,
		'new_alt_text' => $alt_text,
	];

	njw_media_save_log( $alt_text_response, 'media_id', 'MEDIA' );

	return wp_send_json( $alt_text_response, 200 );
}
