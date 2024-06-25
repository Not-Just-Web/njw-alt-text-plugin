<?php
/**
 * This file contains helper functions used in the NJW Skeleton application.
 *
 * @package NjwMedia\Helpers
 * @since 1.0.0
 */

if ( ! function_exists( 'njw_media_json_validate_and_return' ) ) {
	/**
	 * Method named njw_media_json_validate_and_return.
	 *
	 * @param  string $text --> passed the json text.
	 * @return string | array -> based on passed text.
	 */
	function njw_media_json_validate_and_return( $text ) {
		// decode the JSON data.
		$result = json_decode( $text );

		// switch and check possible JSON errors.
		switch ( json_last_error() ) {
			case JSON_ERROR_NONE:
				$error = ''; // JSON is valid // No error has occurred.
				break;
			case JSON_ERROR_DEPTH:
				$error = 'The maximum stack depth has been exceeded.';
				break;
			case JSON_ERROR_STATE_MISMATCH:
				$error = 'Invalid or malformed JSON.';
				break;
			case JSON_ERROR_CTRL_CHAR:
				$error = 'Control character error, possibly incorrectly encoded.';
				break;
			case JSON_ERROR_SYNTAX:
				$error = 'Syntax error, malformed JSON.';
				break;
			// PHP >= 5.3.3.
			case JSON_ERROR_UTF8:
				$error = 'Malformed UTF-8 characters, possibly incorrectly encoded.';
				break;
			// PHP >= 5.5.0.
			case JSON_ERROR_RECURSION:
				$error = 'One or more recursive references in the value to be encoded.';
				break;
			// PHP >= 5.5.0.
			case JSON_ERROR_INF_OR_NAN:
				$error = 'One or more NAN or INF values in the value to be encoded.';
				break;
			case JSON_ERROR_UNSUPPORTED_TYPE:
				$error = 'A value of a type that cannot be encoded was given.';
				break;
			default:
				$error = 'Unknown JSON error occured.';
				break;
		}

		if ( $error !== '' ) {
			// return text as it is :D , since it's not a valid js.
			return $text;
		}

		// everything is OK.
		return $result;
	}
}

if ( ! function_exists( 'njw_generate_random_string' ) ) {
	/**
	 * Generates a random string of specified length.
	 *
	 * @param int $length The length of the random string to generate. Default is 12.
	 * @return string The generated random string.
	 */
	function njw_generate_random_string( $length = 12 ) {
		return wp_generate_password( $length, false, false );
	}
}

if ( ! function_exists( 'njw_media_api_config' ) ) {
	/**
	 * Method njw_media_api_config
	 *
	 * @devnote : Plugin setting page will overwrite the setting present on option page.
	 *
	 * @return string | array -> based on passed text.
	 */
	function njw_media_api_config() {
		$default_option = [
			'API_PROXY_URL' => get_site_url() . '/wp-json/' . njw_media_get_config( 'NAMESPACE' ) . '/' . njw_media_get_config( 'ROUTE' ) . '/',
			'SECRET_KEY'    => '',
			'PROMPT'        => njw_media_get_config( 'AI_PROMPT' ),
			'ACCESS_KEY'    => njw_generate_random_string(),
		];

		$njw_options   = get_option( njw_media_get_config( 'SETTINGS_NAME' ) ); // get the data from the plugin setting page.
		$return_option = ! empty( $njw_options ) && is_array( $njw_options ) ? $njw_options : $default_option;
		return ! empty( $return_option ) && is_array( $return_option ) ? $return_option : $default_option;
	}
}

if ( ! function_exists( 'njw_run_cli_command' ) ) {
	/**
	 * Function njw_run_cli_command
	 *
	 * @param string $command The command to be executed.
	 * @return array The output of the command.
	 */
	function njw_run_cli_command( $command ) {
		$output      = [];
		$return_code = 0;
		// phpcs:ignore
		exec( $command, $output, $return_code );

		$return_data = [
			'output'      => $output,
			'return_code' => $return_code,
		];
		return $return_data;
	}
}

if ( ! function_exists( 'njw_media_get_page' ) ) {
	/**
	 * Method njw_media_get_page
	 *
	 * @return string | array -> based on passed text.
	 */
	function njw_media_get_page() {
		// phpcs:ignore
		return isset( $_GET['page'] ) ? $_GET['page'] : null;
	}
}

if ( ! function_exists( 'njw_get_single_option' ) ) {
	/**
	 * Method njw_get_single_option
	 *
	 * @param string $key           The key to get the value.
	 * @param string $default_value The default value if key not found.
	 * @return string | array -> based on passed text.
	 */
	function njw_get_single_option( $key, $default_value = '' ) {
		$options = njw_media_api_config();
		if ( array_key_exists( $key, $options ) ) {
			$db_value = $options[ $key ];
			return ! empty( $db_value ) ? $db_value : $default_value;
		} else {
			return $default_value;
		}
	}
}

if ( ! function_exists( 'njw_media_log' ) ) {
	/**
	 * Logs a custom message.
	 *
	 * @param string $message The message to be logged.
	 * @return void
	 */
	function njw_media_log( $message ) {
		if ( WP_DEBUG === true ) {
			if ( is_array( $message ) || is_object( $message ) ) {
				// phpcs:ignore
				error_log( print_r( $message, true ) );
			} else {
				// phpcs:ignore
				error_log( $message );
			}
		}
	}
}
