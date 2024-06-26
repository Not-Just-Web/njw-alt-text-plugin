<?php
/**
 * Plugin Name: NJW ALT - Media Text Plugin
 * Plugin URI: https://github.com/snj-adhikari/njw-alt-text-plugin
 * Description: A plugin to generate alt text for the media.
 * Version: 1.2
 * Author: Sanjay Adhikari ( Not Just Web )
 * Author URI: https://notjustweb.com/
 * License: GPL2.
 *
 * @package NjwMedia
 */

$plugin_dir_path = plugin_dir_path( __FILE__ );
$plugin_dir_url  = plugin_dir_url( __FILE__ );
$file_path       = __FILE__;

require $plugin_dir_path . 'includes/config.php';

// Register the config.
njw_media_define_constant_with_prefix( 'PLUGIN_DIR_PATH', $plugin_dir_path );
njw_media_define_constant_with_prefix( 'PLUGIN_DIR_URL', $plugin_dir_url );
njw_media_define_constant_with_prefix( 'PLUGIN_FILE_NAME', $file_path );

require $plugin_dir_path . 'includes/helpers.php';
require $plugin_dir_path . 'includes/database.php';
require $plugin_dir_path . 'includes/activation.php';
require $plugin_dir_path . 'includes/setting-page.php';
require $plugin_dir_path . 'includes/open-ai.php';
require $plugin_dir_path . 'includes/api.php';
