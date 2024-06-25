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
	$constant_name_prefix = 'NJW_MEDIA_';
	$new_constant_name    = $constant_name_prefix . $constant_name;
	if ( ! defined( $new_constant_name ) ) {
		define( $new_constant_name, $value );
	}
}

njw_media_define_constant_with_prefix( 'NAMESPACE', 'njw-media' ); // Prefix starts with `njw_media_'.
njw_media_define_constant_with_prefix( 'PROXY_ROUTE', 'v1/proxy' ); // Prefix starts with `njw_media_' .
njw_media_define_constant_with_prefix( 'ROUTE', 'v1' ); // Prefix starts with `njw_media_' .
njw_media_define_constant_with_prefix( 'SETTINGS_NAME', 'njw-alt-settings' ); // Prefix starts with `njw_media_' .
njw_media_define_constant_with_prefix( 'LINK_TABLE_LOG', 'njw_link_log' ); // Prefix starts with `njw_media_' .
njw_media_define_constant_with_prefix( 'ALT_TABLE_LOG', 'njw_alt_log' ); // Prefix starts with `njw_media_' .
njw_media_define_constant_with_prefix( 'AI_PROMPT', 'Please provide the alt text (to be used on html img tag) for this image, also use provided image url or article slug to determine character name, movies or any to get relevant info if any ensuring it describes the image for individuals who cannot see it. Only report output on plain text and make it precise and maximum 125 character.' ); // Prefix starts with `njw_media_' .



/**
 * Retrieves the configuration settings for Trackonomics.
 *
 * @param  string $constant_name The name of the constant.
 * @return string The configuration settings for Trackonomics.
 */
function njw_media_get_config( $constant_name ) {
	return constant( 'NJW_MEDIA_' . $constant_name );
}
