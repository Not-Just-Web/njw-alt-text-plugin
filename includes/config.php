<?php
/**
 * This file contains config functions used in the NJW Plugin Skeleton application.
 *
 * @package NjwMedia\Config
 * @since 1.0.0
 */

/**
 *
 * Method named njw_media_define_constant_with_prefix --> This is a nicer way to define a constant with a prefix so it doesn't collide with other constants.
 * --> Starts with prefix 'njw_media_'
 *
 * @param  string $constant_name The name of the constant.
 * @param  mixed  $value         The value of the constant.
 * @return void
 */
function njw_media_define_constant_with_prefix( $constant_name, $value ) {
	$constant_name_prefix = 'njw_media_';
	$new_constant_name    = $constant_name_prefix . $constant_name;
	if ( ! defined( $new_constant_name ) ) {
		define( $new_constant_name, $value );
	}
}

njw_media_define_constant_with_prefix( 'NAMESPACE', 'njw-media' ); // Prefix starts with `njw_media_'.
njw_media_define_constant_with_prefix( 'PROXY_ROUTE', 'v1/proxy' ); // Prefix starts with `njw_media_' .
njw_media_define_constant_with_prefix( 'ROUTE', 'v1' ); // Prefix starts with `njw_media_' .
njw_media_define_constant_with_prefix( 'SETTINGS_NAME', 'am-trx-settings' ); // Prefix starts with `njw_media_' .



/**
 * Retrieves the configuration settings for Trackonomics.
 *
 * @param  string $constant_name The name of the constant.
 * @return string The configuration settings for Trackonomics.
 */
function njw_media_get_config( $constant_name ) {
	return constant( 'njw_media_' . $constant_name );
}
