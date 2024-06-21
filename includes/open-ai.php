<?php
/**
 * Connect to Open AI - For GPT4 Vision
 *
 * This file contains functions to connect to Open AI and retrieve alt text for images using GPT4 Vision.
 *
 * @package NjwMedia\Open AI
 * @since 1.0.0
 */

/**
 * Set up headers for Open AI API request.
 *
 * @param string $api_key The Open AI API key.
 * @return array The headers array.
 */
function njw_setup_open_ai( $api_key ) {
	$headers = [
		'Authorization' => 'Bearer ' . $api_key,
		'Content-Type'  => 'application/json',
	];

	return $headers;
}

/**
 * Get alt text for an image from Open AI.
 *
 * @param string $image_url The URL of the image.
 * @param string $slug Optional. The slug of the post or page where the image is mentioned.
 * @return string|null The alt text for the image, or null if an error occurred.
 */
function njw_get_image_alt_from_open_ai( $image_url, $slug = '' ) {
	$api_key = njw_get_single_option( 'API_ACCESS' );
	$headers = njw_setup_open_ai( $api_key );

	// Get the prompt info.
	$prompt         = njw_get_single_option( 'PROMPT', njw_media_get_config( 'AI_PROMPT' ) );
	$prefix         = ! empty( $slug ) ? 'The image provided with this prompt is mentioned in ' . $slug . '.' : '';
	$prompt_to_send = $prefix . $prompt;

	$body = [
		'model'      => 'gpt-4o',
		'messages'   => [
			[
				'role'    => 'user',
				'content' => [
					[
						'type' => 'text',
						'text' => $prompt_to_send,
					],
					[
						'type'      => 'image_url',
						'image_url' => [
							'url' => $image_url,
						],
					],
				],
			],
		],
		'max_tokens' => 300,
	];

	$response = wp_remote_request(
		'https://api.openai.com/v1/engines/davinci-codex/completions',
		[
			'method'  => 'POST',
			'headers' => $headers,
			'body'    => wp_json_encode( $body ),
		]
	);

	if ( is_wp_error( $response ) ) {
		return null;
	}

	$data = json_decode( wp_remote_retrieve_body( $response ), true );

	if ( isset( $data['choices'][0]['message']['content'] ) ) {
		return $data['choices'][0]['message']['content'];
	} else {
		return null;
	}
}
