<?php
/**
 * This file contains Activation used in the Plugin Skeleton application.
 *
 * @package NjwMedia\Activation
 * @since 1.0.0
 */

/**
 * Enqueues frontend assets for the NJW Media plugin.
 *
 * This function is responsible for enqueueing the necessary CSS and JavaScript files
 * required for the frontend functionality of the NJW Media plugin.
 *
 * @since 1.0.0
 */
function njw_media_enqueue_frontend_assets() {

	$asset_file_path = njw_media_get_config( 'PLUGIN_DIR_PATH' ) . 'dist/frontend.asset.php';
	// Check if the file exists, if not, set default values.
	$asset_file = [
		'dependencies' => [],
		'version'      => '1.0.0',
	];

	if ( file_exists( $asset_file_path ) ) {
		$asset_file = include $asset_file_path;
	}

	wp_enqueue_script( 'njw-media-frontend-script', njw_media_get_config( 'PLUGIN_DIR_URL' ) . 'dist/frontend.js', $asset_file['dependencies'], $asset_file['version'], true );
	wp_enqueue_style( 'njw-media-frontend-style', njw_media_get_config( 'PLUGIN_DIR_URL' ) . 'dist/frontend.css', [], $asset_file['version'] );
}
add_action( 'wp_enqueue_scripts', 'njw_media_enqueue_frontend_assets' );

/**
 * Enqueues the necessary admin assets for the NJW Media plugin.
 *
 * This function is responsible for enqueueing the CSS and JavaScript files required for the admin area of the NJW Media plugin.
 * It should be called within the admin_enqueue_scripts action hook.
 *
 * @since 1.0.0
 */
function njw_media_enqueue_admin_assets() {
	$asset_file_path = njw_media_get_config( 'PLUGIN_DIR_PATH' ) . 'dist/blocks.asset.php';
	$asset_file      = [
		'dependencies' => [],
		'version'      => '1.0.0',
	];

	if ( file_exists( $asset_file_path ) ) {
		$asset_file = include $asset_file_path;
	}
	wp_enqueue_script( 'njw-media-admin-script', njw_media_get_config( 'PLUGIN_DIR_URL' ) . 'dist/blocks.js', $asset_file['dependencies'], $asset_file['version'], true );
	wp_enqueue_style( 'njw-media-admin-style', njw_media_get_config( 'PLUGIN_DIR_URL' ) . 'dist/blocks.css', [], $asset_file['version'] );
}
add_action( 'admin_enqueue_scripts', 'njw_media_enqueue_admin_assets' );

/**
 * Enqueues the necessary assets for the NJW Media plugin option page.
 *
 * This function is responsible for enqueueing the CSS and JavaScript files required for the option page of the NJW Media plugin.
 * It should be called within the admin_enqueue_scripts action hook.
 *
 * @since 1.0.0
 */
function njw_media_enqueue_cms_page_assets() {
	// phpcs:ignore
	if ( isset( $_GET['page'] ) && $_GET['page'] === 'wp-skeleton-page' ) {
		$asset_file_path = njw_media_get_config( 'PLUGIN_DIR_PATH' ) . 'dist/cms.asset.php';
		$asset_file      = [
			'dependencies' => [],
			'version'      => '1.0.0',
		];

		if ( file_exists( $asset_file_path ) ) {
			$asset_file = include $asset_file_path;
		}
		wp_enqueue_script( 'njw-media-cms-script', njw_media_get_config( 'PLUGIN_DIR_URL' ) . 'dist/cms.js', $asset_file['dependencies'], $asset_file['version'], true );
		wp_enqueue_style( 'njw-media-cms-style', njw_media_get_config( 'PLUGIN_DIR_URL' ) . 'dist/cms.css', [], $asset_file['version'] );
	}
}
add_action( 'admin_enqueue_scripts', 'njw_media_enqueue_cms_page_assets' );

/**
 * Enqueues the necessary assets for the NJW Media plugin option page.
 *
 * This function is responsible for enqueueing the CSS and JavaScript files required for the option page of the NJW Media plugin.
 * It should be called within the admin_enqueue_scripts action hook.
 *
 * @since 1.0.0
 */
function njw_media_enqueue_option_page_assets() {
	// phpcs:ignore
	if ( isset( $_GET['page'] ) && $_GET['page'] === 'njw_media' ) {
		$asset_file_path = njw_media_get_config( 'PLUGIN_DIR_PATH' ) . 'dist/option-page.asset.php';
		$asset_file      = [
			'dependencies' => [],
			'version'      => '1.0.0',
		];

		if ( file_exists( $asset_file_path ) ) {
			$asset_file = include $asset_file_path;
		}
		wp_enqueue_script( 'njw-media-option-page-script', njw_media_get_config( 'PLUGIN_DIR_URL' ) . 'dist/option-page.js', $asset_file['dependencies'], $asset_file['version'], true );
		wp_enqueue_style( 'njw-media-option-page-style', njw_media_get_config( 'PLUGIN_DIR_URL' ) . 'dist/option-page.css', [], $asset_file['version'] );
	}
}
add_action( 'admin_enqueue_scripts', 'njw_media_enqueue_option_page_assets' );

/**
 * Enqueue editor script.
 */
function njw_media_editor_script() {
	$asset_file = include njw_media_api_config( 'PLUGIN_DIR_PATH' ) . 'dist/editor.asset.php';
	wp_enqueue_script( 'njw-media-editor-script', njw_media_api_config( 'PLUGIN_DIR_URL' ) . 'dist/editor.js', $asset_file['dependencies'], $asset_file['version'], true );
}
add_action( 'enqueue_block_editor_assets', 'njw_media_editor_script' );

/**
 * Adds a custom menu page to the WordPress admin area.
 *
 * This function adds a custom menu page to the WordPress admin area using the add_menu_page function.
 * The custom menu page will be accessible to users with the manage_options capability.
 *
 * @since 1.0.0
 */
function njw_media_add_custom_menu_page() {
	add_menu_page(
		'NJW Media',
		'NJW Media',
		'edit_posts',  // This is the capability so that editor role can access this page.
		'wp-skeleton-page',
		'njw_media_custom_page_callback',
		'dashicons-analytics',
		4
	);
}
add_action( 'admin_menu', 'njw_media_add_custom_menu_page' );

/**
 * Callback function for the custom menu page.
 *
 * This function is the callback function for the custom menu page added by the njw_media_add_custom_menu_page function.
 * It is responsible for displaying the content of the custom menu page.
 *
 * @since 1.0.0
 */
function njw_media_custom_page_callback() {
	echo '<div id="njw-media-react-plugin-page"></div>';
	// Add your custom page content here.
}
